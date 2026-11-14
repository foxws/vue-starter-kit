<?php

declare(strict_types=1);

namespace Foundation\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerTelescope();
    }

    public function boot(): void
    {
        //
    }

    protected function registerTelescope(): void
    {
        if ((bool) config('telescope.enabled') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }
}
