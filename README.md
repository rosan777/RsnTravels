# RsnTravel

A small travel agency and hotel booking demo built with Laravel.

Live: https://travel-demo-v0-0-0.vercel.app

You can browse destinations, tour packages and hotels without an account, search for free rooms by date, and log in with a demo account to see the owner, staff or customer side. It's a portfolio demo, so payments are fake and nothing is charged.

## Demo accounts

The password is the same as the email. The login page also has one-click buttons.

| Role | Email |
|---|---|
| Agency Owner | owner@example.com |
| Hotel Staff | staff@example.com |
| Customer | customer@example.com |

## What's in it

- Home page with a rotating hero and a search bar
- Destinations, tour packages (filter by place, budget, days, trip type) and hotels (filter by city, stars, price)
- Room search that only shows rooms that are actually free for your dates
- Separate areas for owner, staff and customer
- English / বাংলা toggle
- Demo data: 9 destinations, 12 packages, 8 hotels, about 1,500 bookings

Still being built: the booking flow, staff room board, owner tools and the AI-assisted pricing and trip planner.

## Run it locally

You need PHP 8.3 and Composer.

```bash
git clone https://github.com/rosan777/RsnTravel.git
cd RsnTravel
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
php artisan seed:demo
php artisan serve
```

Then open http://127.0.0.1:8000.

`php artisan seed:demo` is safe to run again, it won't create duplicates.

## Tests

```bash
vendor/bin/phpunit
```

## Built with

- Laravel 13, PHP 8.3, Blade
- Bootstrap 4.4.1 (CDN)
- SQLite on your machine, Neon Postgres on the live site
- Vercel with the community `vercel-php` runtime

## Where things are

| Path | What it does |
|---|---|
| `app/Http/Controllers/RsnController.php` | All pages, split into Public, Owner, Staff and Customer areas |
| `app/Http/Middleware/RestrictedAccess.php` | Role check, used as `restricted_access:owner` |
| `routes/web.php` | Routes (route name = method name) |
| `resources/views/` | Blade templates, one folder per area |
| `app/Support/translations.php` | English and Bangla texts |
| `app/Console/Commands/SeedDemo.php` | Demo data |
| `api/index.php`, `vercel.json` | Vercel setup |

## Deploying to Vercel

1. Create a Neon database and copy its connection string.
2. Import the repo in Vercel. `vercel.json` already sets the PHP runtime and most settings.
3. Add these environment variables: `APP_KEY` (from `php artisan key:generate --show`), `APP_URL`, `DB_URL` (the Neon string) and `SETUP_TOKEN` (any long random text).
4. After it deploys, open `/setup-demo/<SETUP_TOKEN>` once. It runs the migrations and loads the demo data.

---

© 2024 OANRO. All rights reserved.
