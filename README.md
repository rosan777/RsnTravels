# RsnTravel

TravelDemo: a basic Laravel demo of a travel agency and hotel management site (tour packages, hotels, room booking, staff board, owner dashboard and rule based "AI-assisted" pricing). Built for the OANRO portfolio. It is a demo, not a product: payments are simulated.

## Demo logins

Password is the same as the email. The login page has one-click buttons.

| Role | Email |
|---|---|
| Agency Owner | owner@example.com |
| Hotel Staff | staff@example.com |
| Customer | customer@example.com |

Public pages (home, destinations, packages, hotels, room search) need no login.

## Run locally

Needs PHP 8.3 and Composer.

```
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
php artisan seed:demo
php artisan serve
```

Open http://127.0.0.1:8000. `php artisan seed:demo` can be run again safely, nothing is duplicated.

## Tests

```
vendor/bin/phpunit
```

## Stack

Laravel 13, PHP 8.3, Blade, Bootstrap 4.4.1 (CDN). SQLite locally, Neon Postgres live, hosted on Vercel.

## Code layout

- `app/Http/Controllers/RsnController.php`: every page, grouped as Public Area / Agency Owner Area / Hotel Staff Area / Customer Area
- `app/Http/Middleware/RestrictedAccess.php`: `restricted_access:owner,staff` route middleware
- `routes/web.php`: route name = controller method name
- `resources/views/<area>/`: `owner/`, `staff/`, `customer/`, `auth/`
- `app/Support/translations.php`: English / Bangla texts
- `app/Console/Commands/SeedDemo.php`: demo data

© 2024 OANRO. All rights reserved.
