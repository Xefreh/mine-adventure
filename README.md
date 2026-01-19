# Mine Adventure

A modern **Learning Management System (LMS)** designed specifically for teaching Java programming and Minecraft plugin/mod development. Mine Adventure combines interactive lessons with hands-on code execution and automated JUnit 5 testing.

## Features

### For Students
- **Interactive Courses** - Browse courses with difficulty indicators (Easy/Medium/Hard)
- **Progress Tracking** - Track completed lessons, learning streaks, and overall progress
- **In-Browser Code Editor** - Write and run Java code with Monaco Editor
- **Automated Testing** - Submit code and get instant feedback via JUnit 5 tests
- **Multiple Content Types** - Videos, text lessons, resources, quizzes, and coding assignments
- **Sequential Learning** - Unlock lessons progressively as you complete them

### For Instructors
- **Course Management** - Create and organize courses with chapters and lessons
- **Drag-and-Drop Ordering** - Easily reorder chapters, lessons, and content blocks
- **5 Block Types** - Video, Text, Resources, Quiz, and Assignment blocks
- **Code Assignments** - Create Java coding challenges with starter code and JUnit tests
- **FAQ Management** - Add course-specific FAQs

## Tech Stack

| Layer          | Technology                    |
|----------------|-------------------------------|
| Backend        | Laravel 12 (PHP 8.4)          |
| Frontend       | React 19 + TypeScript         |
| Styling        | Tailwind CSS 4 + shadcn/ui    |
| Build          | Vite 7                        |
| Database       | SQLite / PostgreSQL           |
| Code Execution | Judge0 API                    |
| Testing        | JUnit 5 (Java) + Pest 4 (PHP) |
| Auth           | WorkOS SSO                    |

## Requirements

- PHP 8.4+
- Composer
- Node.js 20+ and npm
- SQLite or PostgreSQL

## Installation

### Step 1: Install PHP, Composer & Laravel Installer

Laravel provides a one-command installer that sets up PHP, Composer, and the Laravel installer:

**macOS:**
```bash
/bin/bash -c "$(curl -fsSL https://php.new/install/mac/8.4)"
```

**Windows (PowerShell as Administrator):**
```powershell
Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; iex ((New-Object System.Net.WebClient).DownloadString('https://php.new/install/windows/8.4'))
```

**Linux:**
```bash
/bin/bash -c "$(curl -fsSL https://php.new/install/linux/8.4)"
```

After installation, restart your terminal.

### Step 2: Clone and Setup the Project

```bash
# Clone the repository
git clone https://github.com/your-username/mine-adventure.git
cd mine-adventure

# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Run database migrations
php artisan migrate

# Build frontend assets
npm run build
```

### Step 3: Configure Environment

Edit your `.env` file with the required settings:

```env
APP_NAME="Mine Adventure"
APP_URL=http://localhost:8000

# Database (SQLite is default)
DB_CONNECTION=sqlite

# Judge0 API for code execution
JUDGE0_API_URL=your-judge0-instance-url
JUDGE0_API_KEY=your-api-key

# WorkOS Authentication
WORKOS_CLIENT_ID=your-client-id
WORKOS_API_KEY=your-api-key
```

### Step 4: Start Development Server

```bash
# Start all services (Laravel, Vite, Queue)
composer run dev
```

Or start services individually:

```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Vite dev server
npm run dev

# Terminal 3: Queue worker (for background jobs)
php artisan queue:work
```

Visit `http://localhost:8000` to access the application.

## Project Structure

```
mine-adventure/
├── app/
│   ├── Http/Controllers/     # Request handlers
│   │   └── Admin/            # Admin management endpoints
│   ├── Models/               # Eloquent models
│   ├── Enums/                # BlockType, CourseDifficulty
│   └── Services/             # Business logic (Judge0, etc.)
├── resources/js/
│   ├── pages/                # React page components
│   │   ├── admin/            # Admin dashboard pages
│   │   ├── courses/          # Course browsing
│   │   ├── lessons/          # Lesson display
│   │   └── dashboard.tsx     # Student dashboard
│   ├── components/           # Reusable React components
│   │   ├── blocks/           # Content block renderers
│   │   └── ui/               # shadcn/ui components
│   └── types/                # TypeScript definitions
├── routes/
│   ├── web.php               # Main routes
│   ├── lms.php               # LMS-specific routes
│   └── auth.php              # Authentication routes
├── database/
│   ├── migrations/           # Database schema
│   └── factories/            # Model factories for testing
└── tests/                    # Feature & unit tests
```

## Development

### Running Tests

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/CourseTest.php

# Run tests matching a name
php artisan test --filter=dashboard
```

### Code Formatting

```bash
# Format PHP code
vendor/bin/pint

# Lint JavaScript/TypeScript
npm run lint
```

### Generate TypeScript Routes

```bash
php artisan wayfinder:generate
```

## Database Schema

The application uses these main entities:

- **Users** - Students and administrators
- **Courses** - Learning paths with difficulty levels
- **Chapters** - Course sections (ordered)
- **Lessons** - Individual learning units
- **Lesson Blocks** - Content containers (Video, Text, Resources, Quiz, Assignment)
- **Block Assignments** - Code challenges with JUnit tests

See `docs/schema.dbml` for the complete database diagram.

## Docker Deployment

Build and run with Docker:

```bash
# Build the image
docker build -t mine-adventure .

# Run the container
docker run -p 8000:80 mine-adventure
```

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Make your changes
4. Run tests (`php artisan test`)
5. Format code (`vendor/bin/pint`)
6. Commit your changes (`git commit -m 'Add amazing feature'`)
7. Push to the branch (`git push origin feature/amazing-feature`)
8. Open a Pull Request
