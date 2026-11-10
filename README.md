# Plant Nursery Inventory Management System

PNIMS is a Laravel application for managing nursery plant inventory. Authenticated users can create, view, update, and delete plant records, then filter reports by category and stock status.

## Features

- Login and registration with email or a unique username
- Auth-protected plant inventory CRUD
- Nonnegative, two-decimal unit prices and in-stock/out-of-stock statuses
- Plant reports for stock status, category, and price lists
- PDF and Excel-compatible CSV exports using the active report filters
- Each new plant records its creator through the `user_id` relationship

## Local setup

1. Install PHP/Composer dependencies with `composer install`.
2. Copy `.env.example` to `.env`, configure the database, and run `php artisan key:generate`.
3. Apply database migrations with `php artisan migrate`.
4. Install and build the frontend assets with `npm install` and `npm run build`.
5. Start Laravel with `php artisan serve` and create an account at `/register`.

Log in at `/login`, manage inventory at `/plants`, and open reports at `/plants/reports`.

Run the test suite with `php artisan test`.
