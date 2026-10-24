# Campus Library (Laravel 12 CRUD website)

Library management site: catalogue (books, categories), loans, reviews, roles (member / admin).

## Run it
Requirements: PHP 8.2+, Composer.

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite      # (Windows PowerShell: New-Item database/database.sqlite)
php artisan migrate --seed
php artisan serve
```
Open http://127.0.0.1:8000

## Deploy on Render

This project includes a Docker setup for a Render Web Service. In Render, create a new **Web Service**, connect this repository, choose **Docker** as the runtime, and deploy from the project root. Render provides the `PORT` variable automatically; the container listens on that port with Laravel's PHP server.

Add these environment variables in Render:

```text
APP_ENV=production
APP_DEBUG=false
APP_KEY=<generate with php artisan key:generate --show>
APP_URL=https://YOUR_SERVICE.onrender.com
DB_CONNECTION=sqlite
DB_DATABASE=/app/database/database.sqlite
LOG_CHANNEL=stderr
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

The container runs migrations on startup. SQLite is suitable for a demonstration, but its data is lost when Render recreates the service unless you attach a persistent disk. For real persistence, use a Render PostgreSQL database and set `DB_CONNECTION=pgsql` with the PostgreSQL connection variables.

## Demo accounts
| Role   | E-mail              | Password |
|--------|---------------------|----------|
| Admin  | admin@library.test  | password |
| Member | member@library.test | password |

## Where is what
- Models: `app/Models` - Controllers: `app/Http/Controllers` - Views: `resources/views`
- Routes: `routes/web.php` - Migrations: `database/migrations` - Seeder: `database/seeders/DatabaseSeeder.php`
- Report (database design + feature list): `Report.docx` / `Report.pdf` at the root of the zip.
