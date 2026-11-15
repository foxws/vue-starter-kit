---
name: health-checks-development
description: >
  Add health checks to this application. Use when the user asks for health checks, uptime or readiness
  probes, Docker/Podman/Kubernetes healthchecks, the /up endpoint, DiagnosingHealth, or spatie/laravel-health
  (checks, results page, notifications, Oh Dear).
license: MIT
metadata:
  author: foxws
---

# Health checks

The starter kit ships no health check package on purpose. Many apps only need Laravel's `/up` route for container health checks. Read `docs/health-checks.md` before you change anything; it has the exact code to use.

## Pick the approach

1. **Container health check only** (Docker, Podman, Kubernetes): use `/up`, already registered in `bootstrap/app.php`. Don't install a package. Add a `healthcheck` / `HEALTHCHECK` that requests `/up` on the Octane port. Use `php artisan horizon:status` for Horizon containers.
2. **Dependency checks on `/up`**: listen for `Illuminate\Foundation\Events\DiagnosingHealth` and throw when the database, cache or Redis is unreachable.
3. **Stored results, notifications, a results page or Oh Dear**: install `spatie/laravel-health`. Ask the user first, since it adds a dependency.

## Installing spatie/laravel-health

Follow `docs/health-checks.md` in this order:

- `composer require spatie/laravel-health`, publish the config and migrations, then migrate.
- Create `Foundation\Providers\HealthServiceProvider` in `src/Foundation/Providers` and register it in `bootstrap/providers.php` (keep the list alphabetical).
- Define the `viewHealth` gate for super-admins in that provider.
- Schedule `RunHealthChecksCommand` and prune `HealthCheckResultHistoryItem` in `routes/console.php`.
- Add the `health` route in `routes/web.php` with the `auth`, `can:viewHealth` and `cache.bypass` middleware.
- Add the Health item to the super-admin section of `resources/js/components/Ui/UserMenu.vue`, using the Wayfinder `health` route from `@/routes`.
- Add `# HEALTH_SECRET_TOKEN=` to `.env.example` and `.env.ci`.
- Add the `/health` row to the endpoints table in `README.md`.
- Add `tests/Feature/Health/HealthCheckTest.php` and run it.

Use `search-docs` or the package docs (https://spatie.be/docs/laravel-health) for checks beyond the defaults.
