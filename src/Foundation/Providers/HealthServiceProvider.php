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
