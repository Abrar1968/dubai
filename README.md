# Dubai Tourism & Travel - Laravel 12 Project

This is a Laravel 12 application with Vue 3, Inertia.js, Alpine.js, and Tailwind CSS v4 for managing tourism and travel services.

## Tech Stack

- **Backend**: Laravel 12
- **Frontend**: Vue 3 with TypeScript
- **CSS Framework**: Tailwind CSS v4
- **JavaScript Framework**: Alpine.js
- **Routing**: Inertia.js
- **Database**: MySQL
- **Authentication**: Laravel Fortify

## Project Structure

The project includes:
- Hajj & Umrah packages management
- Tour & Travel services
- Typing services
- User authentication with two-factor authentication
- Admin panel foundation

## Installation & Setup

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js 18+ and npm
- MySQL database

### Installation Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/broken-demigod/dubai_tt.git
   cd dubai_tt
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node dependencies**
   ```bash
   npm install
   ```

4. **Environment configuration**
   - Copy `.env.example` to `.env`
   - Update database credentials:
     ```
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=dubai
     DB_USERNAME=root
     DB_PASSWORD=your_password
     ```

5. **Generate application key**
   ```bash
   php artisan key:generate
   ```

6. **Create database**
   ```bash
   mysql -u root -p
   CREATE DATABASE dubai;
   exit;
   ```

7. **Run migrations**
   ```bash
   php artisan migrate
   ```

8. **Build assets**
   ```bash
   npm run build
   ```

## Development

### Running the development server

```bash
composer dev
```

This will start:
- Laravel development server on `http://localhost:8000`
- Queue worker
- Vite development server for hot module replacement

### Or run individually:

**Start Laravel server:**
```bash
php artisan serve
```

**Start Vite dev server:**
```bash
npm run dev
```

**Start queue worker:**
```bash
php artisan queue:listen
```

## Git Workflow

### Branches
- `main` - Production-ready code
- `abrar` - Abrar's development branch
- `rizwan` - Rizwan's development branch

### Pushing to GitHub

**Note**: You need to authenticate with GitHub before pushing. If you encounter authentication errors:

1. **Using Personal Access Token (recommended)**
   ```bash
   git config credential.helper store
   git push -u origin main
   # Enter your GitHub username and Personal Access Token when prompted
   ```

2. **Push all branches**
   ```bash
   git push -u origin main
   git push -u origin abrar
   git push -u origin rizwan
   ```

### Current Branch
You are currently on the `abrar` branch.

## Features Installed

### Alpine.js
Alpine.js is installed and configured for admin panel interactivity. It's globally available on the `window` object.

```html
<!-- Example usage in blade/vue templates -->
<div x-data="{ open: false }">
    <button @click="open = !open">Toggle</button>
    <div x-show="open">Content</div>
</div>
```

### Tailwind CSS v4
Already configured with custom theme and utilities. CSS file: `resources/css/app.css`

## Testing

```bash
php artisan test
```

Or with Pest:
```bash
./vendor/bin/pest
```

## Building for Production

```bash
npm run build
```

## Common Commands

```bash
# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Create new migration
php artisan make:migration create_table_name

# Create new model
php artisan make:model ModelName -m

# Create new controller
php artisan make:controller ControllerName

# Format code
composer pint
npm run format

# Lint code
npm run lint
```

## Project Sections

1. **Hajj & Umrah Services** - `/resources/js/pages/hajj&umrah/`
2. **Tour & Travel** - `/resources/js/pages/tour&travel/`
3. **Typing Services** - `/resources/js/pages/typing/`
4. **Settings** - `/resources/js/pages/settings/`
5. **Authentication** - `/resources/js/pages/auth/`

## Contributing

1. Create a feature branch from your development branch (abrar or rizwan)
2. Make your changes
3. Commit with descriptive messages
4. Push to your branch
5. Create a Pull Request to `main`

## License

This project is proprietary and confidential.
