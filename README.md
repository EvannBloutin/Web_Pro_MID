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

## Demo accounts
| Role   | E-mail              | Password |
|--------|---------------------|----------|
| Admin  | admin@library.test  | password |
| Member | member@library.test | password |

## Where is what
- Models: `app/Models` - Controllers: `app/Http/Controllers` - Views: `resources/views`
- Routes: `routes/web.php` - Migrations: `database/migrations` - Seeder: `database/seeders/DatabaseSeeder.php`
- Report (database design + feature list): `Report.docx` / `Report.pdf` at the root of the zip.
