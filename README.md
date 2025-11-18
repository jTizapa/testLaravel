# Gym Payments Platform

Simple B2B2C payments management for gym memberships (Laravel REST API + Vue SPA).

## Estructura
- `api/`: Laravel 11 (Sanctum, Spatie permission, Pest).
- `app/`: Vue 3 + Pinia + Router + TypeScript (Vite + Vitest).
- `docker-compose.yaml`: Postgres + Redis + PHP + Node.

## Inicio rápido (Docker)
```bash
# copiar envs
cp .env .env.local
cp api/.env.example api/.env
cp app/.env.example app/.env

# levantar servicios
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
Tokens con Sanctum: `/api/v1/auth/login` (usuarios seed: admin@gymstack.test / password).

## Frontend (Vue)
```bash
cd app
npm install
npm run dev
npm run test:unit
```
Env SPA: `app/.env` define `VITE_APP_API_URL=http://localhost:8000/api/v1` y `VITE_APP_WS_URL=ws://localhost:6001`.
UI: Vuetify + @mdi/font. Realtime: Echo/Pusher (canal `dashboard`).
Si usas Docker, instala deps en el contenedor `node`: `docker compose exec node npm install`.

## Notas 
- Canal de broadcast: `dashboard` (eventos `payment.recorded`, `subscription.status_changed`).
- Canal de logs estructurados: `structured` en `storage/logs/structured.log`.
- Websockets locales: Pusher fake (`key: local`, ws en `6001`). Ajusta `VITE_APP_WS_URL` si cambias host/puerto.
