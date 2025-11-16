# Gym Payments Platform

Simple B2B2C payments management for gym memberships (Laravel REST API + Vue SPA).

## Structure
- `api/`: Laravel 11 backend with Sanctum auth, Spatie permission, Pest tests and Artisan commands.
- `app/`: Vue 3 + Pinia + Router + TypeScript SPA powered by Vite and Vitest.
- `docker-compose.yaml`: Postgres + Redis + PHP + Node containers that mirror local dev.

## Getting started
```bash
# copy env templates
cp .env .env.local
cp api/.env.example api/.env

# build containers (optional, recommended for parity)
docker compose up --build
```

## Backend (Laravel)
```bash
cd api
composer install
php artisan key:generate
php artisan migrate --seed
php artisan test
```
Auth tokens are issued via Sanctum (`/api/v1/auth/login`).

## Frontend (Vue)
```bash
cd app
npm install
npm run dev
npm run test:unit
```
The SPA reads `VITE_APP_API_URL` from `app/.env` or `package.json` scripts when served behind Nginx.

## Notes
- Filament/Tenancy were removed from this workspace; backend logic now uses a single database.
- Logs, queues and broadcasts rely on array drivers until further configuration.
- Follow the phased plan in the issue tracker before extending functionality.
