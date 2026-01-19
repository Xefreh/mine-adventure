# 6.1 Installation et configuration de l'environnement de travail

> **Compétence visée :** Installer et configurer son environnement de travail en fonction du projet web ou web mobile

## Logiciels installés

- **IDE** : PhpStorm (support PHP, Laravel, TypeScript)
- **PHP** : 8.4 via script Laravel officiel
- **Composer** : 2.x (gestion dépendances PHP)
- **Node.js** : 22.x avec npm 10.x
- **Git** : 2.x (versioning)

## Base de données

- **Développement** : SQLite (sans serveur)
- **Production** : PostgreSQL 18 (géré par Laravel Cloud)

## Installation du projet

```bash
git clone https://github.com/[username]/mine-adventure.git
cd mine-adventure
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
```

## Lancement

```bash
composer run dev  # Lance Laravel + Vite
```

L'application est accessible sur `http://localhost:8000`

## Services externes

```env
# Judge0 (exécution de code)
JUDGE0_API_URL=https://instance-judge0.com
JUDGE0_API_KEY=xxxxx

# WorkOS (authentification)
WORKOS_CLIENT_ID=client_xxxxx
WORKOS_API_KEY=sk_xxxxx
```

## Structure du projet

```
mine-adventure/
├── app/           # PHP (Controllers, Models, Services)
├── resources/js/  # React/TypeScript (components, pages)
├── database/      # Migrations, factories, seeders
├── routes/        # Définition des routes
└── tests/         # Tests automatisés
```

## Qualité de code

- **PHP** : Laravel Pint (`./vendor/bin/pint`)
- **JS/TS** : ESLint + Prettier (`npm run lint`)
