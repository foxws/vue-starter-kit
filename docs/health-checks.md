# Health checks

The starter kit doesn't ship a health check package. Pick what fits how you run the app.

## Container health checks

Laravel already serves `/up` (see `health:` in `bootstrap/app.php`). It boots the app and returns `200`, or `500` when booting fails. For Docker, Podman or Kubernetes that's usually enough.

Docker / Podman Compose:

```yaml
services:
    app:
        healthcheck:
            test: ['CMD', 'curl', '-fsS', 'http://localhost:8000/up']
            interval: 30s
            timeout: 5s
            retries: 3
            start_period: 20s
```

Containerfile / Dockerfile:

```dockerfile
HEALTHCHECK --interval=30s --timeout=5s --start-period=20s --retries=3 \
  CMD curl -fsS http://localhost:8000/up || exit 1
```

Use the port Octane listens on. If the image has no `curl`, use `wget -qO- http://localhost:8000/up` instead.

For Horizon or scheduler containers, which don't serve HTTP, check the process instead:

```yaml
healthcheck:
    test: ['CMD', 'php', 'artisan', 'horizon:status']
```

To check the database or Redis on `/up`, listen for the `DiagnosingHealth` event and throw when a dependency is down:

```php
use Illuminate\Foundation\Events\DiagnosingHealth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;

Event::listen(DiagnosingHealth::class, function (): void {
    DB::connection()->getPdo();
});
```

## spatie/laravel-health

Use [spatie/laravel-health](https://spatie.be/docs/laravel-health) when you want stored results, notifications on failures, a JSON results page or Oh Dear integration.

### Install

```bash
composer require spatie/laravel-health
php artisan vendor:publish --tag="health-config"
php artisan vendor:publish --tag="health-migrations"
php artisan migrate
```

### Register the checks

Create `src/Foundation/Providers/HealthServiceProvider.php` and add it to `bootstrap/providers.php`:

```php
<?php

declare(strict_types=1);

namespace Foundation\Providers;

use Domain\Users\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Spatie\Health\Checks\Checks\CacheCheck;
use Spatie\Health\Checks\Checks\DatabaseCheck;
use Spatie\Health\Checks\Checks\DebugModeCheck;
use Spatie\Health\Checks\Checks\EnvironmentCheck;
use Spatie\Health\Checks\Checks\OptimizedAppCheck;
use Spatie\Health\Checks\Checks\UsedDiskSpaceCheck;
use Spatie\Health\Facades\Health;

class HealthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->configureChecks();
        $this->configureGates();
    }

    protected function configureChecks(): void
    {
        Health::checks([
            DatabaseCheck::new(),
            CacheCheck::new(),
            UsedDiskSpaceCheck::new()
                ->warnWhenUsedSpaceIsAbovePercentage(70)
                ->failWhenUsedSpaceIsAbovePercentage(90),
            DebugModeCheck::new(),
            EnvironmentCheck::new(),
            OptimizedAppCheck::new()
                ->if(app()->isProduction()),
        ]);
    }

    protected function configureGates(): void
    {
        Gate::define('viewHealth', fn (User $user): bool => $user->isSuperAdmin());
    }
}
```

### Schedule and prune

In `routes/console.php`:

```php
use Illuminate\Database\Console\PruneCommand;
use Spatie\Health\Commands\RunHealthChecksCommand;
use Spatie\Health\Models\HealthCheckResultHistoryItem;

Schedule::command(RunHealthChecksCommand::class)
    ->everyFiveMinutes();

Schedule::command(PruneCommand::class, [
    '--model' => [
        HealthCheckResultHistoryItem::class,
    ]])
    ->withoutOverlapping(10)
    ->dailyAt('01:00');
```

### Results page

In `routes/web.php`:

```php
use Spatie\Health\Http\Controllers\HealthCheckJsonResultsController;

Route::get('/health', HealthCheckJsonResultsController::class)
    ->middleware(['auth', 'can:viewHealth', 'cache.bypass'])
    ->name('health');
```

Add a link for super-admins in `resources/js/components/Ui/UserMenu.vue`, next to the Horizon item:

```ts
import { health } from '@/routes'

{
  label: 'Health',
  icon: 'i-lucide-heart-pulse',
  to: health.url(),
  target: '_blank',
  external: true,
},
```

To let an external monitor read the results without logging in, set `HEALTH_SECRET_TOKEN` in `.env` and send it in the `X-Secret-Token` header.

### Test

`tests/Feature/Health/HealthCheckTest.php`:

```php
<?php

declare(strict_types=1);

use Domain\Users\Models\User;

it('shows the health check results to a super-admin', function () {
    $user = User::factory()->create();
    $user->assignRole('super-admin');

    $this->artisan('health:check')->assertSuccessful();

    $this->actingAs($user)
        ->getJson(route('health'))
        ->assertSuccessful()
        ->assertJsonStructure(['finishedAt', 'checkResults']);
});

it('hides the health check results from regular users', function () {
    $this->actingAs(User::factory()->create())
        ->getJson(route('health'))
        ->assertForbidden();
});
```
