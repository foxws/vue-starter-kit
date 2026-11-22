# Vue Starter Kit

[![Tests](https://github.com/foxws/vue-starter-kit/actions/workflows/tests.yml/badge.svg)](https://github.com/foxws/vue-starter-kit/actions/workflows/tests.yml)
[![Code Quality](https://github.com/foxws/vue-starter-kit/actions/workflows/code-quality.yml/badge.svg)](https://github.com/foxws/vue-starter-kit/actions/workflows/code-quality.yml)
[![License](https://img.shields.io/github/license/foxws/vue-starter-kit)](LICENSE)

A Laravel starter kit, built from the setup we use in [foxws/app](https://github.com/foxws/app) and [stry](https://github.com/francoism90/stry): Inertia with Vue and Nuxt UI, Fortify authentication, Octane on FrankenPHP and a domain-driven `src/` layout.

## What's included

- **Laravel 13** on PHP 8.5+, served by **Octane** with **FrankenPHP**
- **Inertia v3**, **Vue 3**, **Nuxt UI 4** and **Tailwind CSS 4**, built with **Vite+**
- **Fortify**: login, registration (toggled at runtime), password reset, email verification, password confirmation, two-factor authentication and passkeys
- **Sanctum** for the API, with a versioned `/api/v1`
- **Scout** with [foxws/laravel-scout-builder](https://github.com/foxws/laravel-scout-builder) for searchable, filterable and sortable endpoints (the `database` driver out of the box)
- **spatie/laravel-data** with **spatie/laravel-typescript-transformer**, so data objects and enums become TypeScript types in `resources/js/types/generated.ts`
- **Wayfinder** for typed routes and actions in TypeScript
- **Reverb** with **Echo**, set up for [Inertia live props](https://inertiajs.com/docs/v3/data-props/live-props) and `useEcho()`
- **Horizon** for Redis queues; see [docs/health-checks.md](docs/health-checks.md) for container health checks or adding spatie/laravel-health
- **foxws/laravel-ddd** and **foxws/laravel-essentials** for the domain layout and strict defaults
- Spatie packages: **csp**, **medialibrary**, **model-states**, **options**, **permission**, **responsecache**, **settings**, **tags** and **translatable**
- Tooling: **Pest** (with browser tests), **Larastan**, **Pint**, **Telescope**, **Pail**, **Pao**, **IDE Helper**, **Boost** and **spatie/guidelines-skills**

## Structure

```
src/
├── Domain/        # Models, actions, states, policies (e.g. Domain/Users)
├── Foundation/    # Providers, middleware, settings and helpers
├── Integrations/  # Clients for third-party services
├── Modules/
│   ├── Api/       # Versioned API controllers and resources
│   └── Web/       # Inertia controllers and responses
└── Support/       # Framework glue: CSP presets, Inertia middleware
```

Routes live in `routes/web.php` (pages), `routes/actions.php` (Inertia actions under `/actions`) and `routes/api.php` (`/api/v1`).

## Getting started

Click **Use this template** on GitHub, clone your new repository and run:

```bash
composer setup     # install, .env, key, migrate, pnpm install and build
php artisan db:seed
composer dev
```

The defaults use SQLite, the database queue and the database cache, so nothing else has to run locally. For production, switch the cache, sessions and the response cache to Redis (the response cache needs a store that supports tags).

Reverb runs on `localhost:8080` as part of `composer dev`. The browser gets its connection details from the `echo` prop, so they can be changed without a rebuild; set `VITE_REVERB_HOST`, `VITE_REVERB_PORT` and `VITE_REVERB_SCHEME` when browsers reach Reverb through a reverse proxy. Without a `REVERB_APP_KEY`, Echo falls back to the null broadcaster.

Behind a proxy, set `APP_URL` to the public address. `X-Forwarded-For`, `-Port` and `-Proto` count from private addresses, or from any on Laravel Cloud: set `TRUSTED_PROXIES` for a proxy with a public address. `X-Forwarded-Host` never counts, so the proxy has to pass `Host` on (nginx: `proxy_set_header Host $host;`).

The seeder creates `administrator@example.com` with the password `password` and the `super-admin` role. Registration is off by default; turn it on in **Settings → Application** after you log in as the admin.

## Endpoints

| Method | URI             | Description                                                                                  |
| ------ | --------------- | -------------------------------------------------------------------------------------------- |
| GET    | `/`             | Home page                                                                                    |
| GET    | `/api/v1`       | API index (response cached)                                                                  |
| GET    | `/api/v1/user`  | The authenticated user (Sanctum token or session)                                            |
| GET    | `/api/v1/users` | Search users with `?query=`, sort with `?sort=-newest`, page with `?page[number]=2` (admins) |
| GET    | `/horizon`      | Horizon dashboard (super-admins)                                                             |

Private channels are authorized at `/broadcasting/auth` (session) and `/api/v1/broadcasting/auth` (Sanctum token), with the channels in `routes/channels.php`.

## Commands

```bash
composer dev            # Octane, queue, Reverb, logs and Vite
composer test           # Pint check and Pest
composer types:check    # Larastan
composer types:generate # Write TypeScript types for #[TypeScript] classes and enums
pnpm run check:fix      # Format and lint JS, TS and Vue
pnpm run types:check    # vue-tsc
```

## Testing

`php artisan test` runs the unit, feature and browser suites. Browser tests use [Pest's browser plugin](https://pestphp.com/docs/browser-testing) and need Chromium:

```bash
npx playwright install --with-deps chromium
php artisan test --testsuite=Browser
```

## Optional packages

These depend on the project, so they aren't installed:

- [spatie/laravel-db-snapshots](https://github.com/spatie/laravel-db-snapshots) for database dumps

## License

The MIT License (MIT). See [LICENSE](LICENSE) for more information.
