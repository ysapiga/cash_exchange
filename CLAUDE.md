# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

```bash
# Development (runs server, queue, logs, and Vite in parallel)
composer dev

# Individual services
php artisan serve
php artisan queue:listen
php artisan pail        # real-time logs
npm run dev             # Vite dev server

# Build
npm run build

# Database
php artisan migrate

# Admin user creation
php artisan make:admin {name} {email} {password}

# Tests
./vendor/bin/phpunit

# Linting
./vendor/bin/pint
```

## Architecture

This is a **currency exchange rate display app** for cash47.com.ua, built with Laravel 12 and Filament 3.2.

### Public-facing layer
Two routes in `routes/web.php`:
- `GET /` — home page showing currency buy/sell rates and conversion rates (`HomeController`)
- `POST /contact-request` — submit a contact form (`ContactRequestController`)

The frontend (`resources/views/home.blade.php`) uses Tailwind CSS v4 with a dark theme (`bg-[#262b3b]`), rendering buy/sell prices with emerald/red coloring. Vite is configured with base URL `https://cash47.com.ua/`.

### Admin panel
Filament resources under `/admin` (all CRUD). Resources live in `app/Filament/Resources/`:
- `CurrencyResource` — currency codes with emoji icons
- `CurrencyRateResource` — buy/sell prices per currency, with position ordering
- `ConversionRateResource` — cross-currency conversion rates, with position ordering
- `ContactRequestResource` — incoming requests, tracks `is_pending` status
- `CustomersResource` — customer profiles
- `ContactInfoResource` — single-record contact info (Google Maps coordinates, etc.)

### Models & relations
- `Currency` → has many `CurrencyRate`
- `CurrencyRate` → belongs to `Currency`; has `position` for custom ordering
- `ConversionRate` → has `from_currency`/`to_currency` and `position`
- `ContactRequest` / `Customer` — standalone; observed for Telegram notifications

### Telegram notifications
`app/Observers/ContactRequestObserver.php` and `CustomerObserver.php` fire on model events (create/update/delete) and send messages via Telegram. Bot token and chat ID are in `config/services.php` and read from env vars `TELEGRAM_BOT_TOKEN` / `TELEGRAM_CHAT_ID`.

### Key env vars
```
DB_DATABASE=cash_exchange   # MySQL
TELEGRAM_BOT_TOKEN=
TELEGRAM_CHAT_ID=
```
Session, cache, and queue all use the `database` driver.
