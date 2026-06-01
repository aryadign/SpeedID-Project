# SpeedID-Website — AGENTS.md

## Stack
- **Laravel 13** (PHP 8.4), **Tailwind CSS v3** (via Breeze), **Vite 8**
- MySQL 8+ (default); `DB_CONNECTION=mysql` in `.env`
- PHPUnit 12 via `composer test` (runs `artisan config:clear` first)
- `.npmrc` has `ignore-scripts=true` — `npm install` skips build scripts

## Commands
| Command | What it runs |
|---|---|
| `composer setup` | Full first-time setup: install, copy `.env`, `key:generate`, `migrate`, `npm install`, `npm run build` |
| `composer dev` | Concurrent: `artisan serve`, `queue:listen`, `pail`, `vite` |
| `composer test` | `artisan config:clear && artisan test` |
| `npm run build` | `vite build` |
| `npm run dev` | `vite` (dev server) |
| `php artisan test --filter=test_name` | Run a single test |

## Testing
- `phpunit.xml` uses **SQLite `:memory:`** — no external DB needed for tests
- Feature tests extend `Tests\TestCase`; Unit tests extend `PHPUnit\Framework\TestCase`

## Installed packages (in addition to Laravel skeleton)
- **spatie/laravel-permission** — RBAC (roles Admin, User)
- **spatie/laravel-activitylog** — activity logging
- **simplesoftwareio/simple-qrcode** — QR ticket generation
- **intervention/image** — image manipulation
- **laravel/breeze** (Blade + Alpine stack) — authentication scaffolding
- **alpinejs** — lightweight interactivity
- **chart.js** — admin dashboard charts
- **leaflet** + OpenStreetMap — map integration
- **lucide** — icon library

## Design system (`tailwind.config.js`)
- Font: **Outfit** (loaded via Bunny CDN, `@fonts` Blade directive)
- Primary: `#2563EB`, Secondary: `#081C3A`, Tertiary: `#00B4D8`
- Custom border radii: `rounded-sm: 10px`, `rounded-md: 16px`, `rounded-lg: 20px`, `rounded-xl: 28px`
- Custom shadows: `shadow-card-sm`, `shadow-card-md`, `shadow-card-lg`
- Colors defined: `primary`, `secondary`, `tertiary`, `surface`, `surface-alt`, `border`, `text-primary`, `text-secondary`, `text-muted`

## Framework quirks
- Uses PHP 8 **attributes** for Eloquent: `#[Fillable([...])]`, `#[Hidden([...])]` (not `$fillable`/`$hidden` props)
- Auth scaffolding installed via **Laravel Breeze** (Blade stack)
- Session/cache/queue drivers default to `database` — requires running migrations
- `public/storage` is gitignored; run `php artisan storage:link` for local file serving

## Project docs
- `.agents/prd.md` — Product Requirements Document (all features, database, roles, workflow)
- `.agents/design.md` — Design system & UI/UX guide (colors, typography, components, layout)
- `.agents/task-instruction.md` — Workflow instructions, development rules, task checklist format
- `.agents/tasklist.md` — Current task progress tracker
