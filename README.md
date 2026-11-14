# Laravel Boilerplate

[![Tests](https://github.com/foxws/laravel-boilerplate/actions/workflows/tests.yml/badge.svg)](https://github.com/foxws/laravel-boilerplate/actions/workflows/tests.yml)
[![Code Quality](https://github.com/foxws/laravel-boilerplate/actions/workflows/code-quality.yml/badge.svg)](https://github.com/foxws/laravel-boilerplate/actions/workflows/code-quality.yml)
[![License](https://img.shields.io/github/license/foxws/laravel-boilerplate)](LICENSE)

A starting point for Laravel applications, built from the setup we use in [foxws/app](https://github.com/foxws/app) and [stry](https://github.com/francoism90/stry): Inertia with Vue and Nuxt UI, Fortify authentication, Octane on FrankenPHP and a domain-driven `src/` layout.

## What's included

- **Laravel 13** on PHP 8.4+, served by **Octane** with **FrankenPHP**
- **Inertia v3**, **Vue 3**, **Nuxt UI 4** and **Tailwind CSS 4**, built with **Vite+**
- **Fortify**: login, registration (toggled at runtime), password reset, email verification, password confirmation, two-factor authentication and passkeys
- **Sanctum** for the API, with a versioned `/api/v1` and a `UserResource`
- **Wayfinder** for typed routes and actions in TypeScript
- **foxws/laravel-ddd** and **foxws/laravel-essentials** for the domain layout and strict defaults
- Spatie packages: **csp**, **medialibrary**, **model-states**, **options**, **permission**, **responsecache**, **settings**, **tags** and **translatable**
- Tooling: **Pest**, **Larastan**, **Pint**, **Telescope**, **Pail**, **Pao**, **IDE Helper**, **Boost** and **spatie/guidelines-skills**

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

The seeder creates `administrator@example.com` with the password `password` and the `super-admin` role. Registration is off by default; turn it on in **Settings → Application** after you log in as the admin.

## Endpoints

| Method | URI            | Description                                    |
| ------ | -------------- | ---------------------------------------------- |
| GET    | `/`            | Home page                                      |
| GET    | `/api/v1`      | API index (response cached)                    |
| GET    | `/api/v1/user` | The authenticated user (Sanctum token or session) |

## Commands

```bash
composer dev            # Octane, queue, logs and Vite
composer test           # Pint check and Pest
composer types:check    # Larastan
pnpm run check:fix      # Format and lint JS, TS and Vue
pnpm run types:check    # vue-tsc
```

## License

The MIT License (MIT). See [LICENSE](LICENSE) for more information.
