# Speed ID

A smart public service queue and reporting platform built with Laravel 13.

## Features

- **SpeedQ** — Queue management system with service slots, ticket generation, and live queue display
- **SpeedReport** — Public reporting system with tracking codes, status updates, and media uploads
- **SpeedSOS** — Emergency request system with real-time monitoring and map integration
- **SpeedNews** — News and announcement publishing with categories and scheduling

## Demo Accounts

| Role  | Email                | Password   |
|-------|----------------------|------------|
| Admin | admin@speedid.test   | password   |
| User  | user@speedid.test    | password   |

Run `php artisan db:seed` to create these accounts.

## Setup

```bash
composer setup
```

This runs: install, copy `.env`, `key:generate`, `migrate`, `npm install`, `npm run build`, and `db:seed`.

## Development

```bash
composer dev
```

Concurrent dev servers: Laravel (`:8000`), queue worker, Pail log watcher, and Vite HMR.

## Testing

```bash
composer test
```

Uses SQLite in-memory database. No external MySQL needed.

## Stack

- Laravel 13 / PHP 8.4
- Tailwind CSS v3 (Breeze Blade + Alpine)
- MySQL 8+
- Spatie Laravel Permission & Activitylog
- Chart.js, Leaflet, Lucide icons
