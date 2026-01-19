# Stage 1: Build PHP base (runtime deps + extensions)
FROM php:8.4-fpm-alpine AS php-base

# 1. Install Runtime Dependencies
# 2. Install Build Dependencies (virtual)
# 3. Configure & Install PHP Extensions
# 4. Remove Build Dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    libzip \
    libpng \
    libjpeg-turbo \
    freetype \
    oniguruma \
    libxml2 \
    icu-libs \
    libpq \
    # Ajout des dépendances de build virtuelles \
    && apk add --no-cache --virtual .build-deps \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    libxml2-dev \
    icu-dev \
    postgresql-dev \
    linux-headers \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_pgsql \
        mbstring \
        xml \
        bcmath \
        gd \
        zip \
        intl \
        opcache \
        pcntl \
    # Nettoyage immédiat \
    && apk del .build-deps

# Pin Composer version to major version 2 for stability
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Stage 2: Generate Wayfinder types (Cache optimized)
FROM php-base AS wayfinder
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-progress
# On copie seulement le nécessaire pour limiter l'invalidation du cache
COPY app ./app
COPY resources ./resources
COPY routes ./routes
COPY artisan ./
RUN php artisan wayfinder:generate --with-form

# Stage 3: Build frontend assets
FROM node:24-alpine AS frontend
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY resources ./resources
COPY vite.config.ts tsconfig.json components.json ./
COPY public ./publicz

# Retrieve generated Wayfinder types
COPY --from=wayfinder /app/resources/js/actions ./resources/js/actions
COPY --from=wayfinder /app/resources/js/routes ./resources/js/routes
COPY --from=wayfinder /app/resources/js/wayfinder ./resources/js/wayfinder

ENV SKIP_WAYFINDER=1
RUN npm run build

# Stage 4: Dependencies Build (Clean vendor)
FROM php-base AS deps
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-progress

# Stage 5: Final image
FROM php-base AS final

WORKDIR /app

# Copy Configs first
COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf
COPY docker/supervisord.conf /etc/supervisord.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/app.ini
COPY docker/start.sh /app/start.sh
RUN chmod +x /app/start.sh

# Copy Dependencies
COPY --from=deps --chown=www-data:www-data /app/vendor ./vendor
COPY --from=deps --chown=www-data:www-data /app/composer.* ./

# Copy App files
COPY --chown=www-data:www-data . .
COPY --from=frontend --chown=www-data:www-data /app/public/build ./public/build

# Final Setup
RUN composer dump-autoload --optimize --classmap-authoritative \
    && composer run-script post-autoload-dump \
    && mkdir -p /app/storage /app/bootstrap/cache \
    # Ensure www-data owns the critical directories \
    && chown -R www-data:www-data /app/storage /app/bootstrap/cache \
    && chmod -R 775 /app/storage /app/bootstrap/cache

EXPOSE 80

CMD ["/app/start.sh"]
