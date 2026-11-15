<?php

declare(strict_types=1);

namespace Foundation\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->configureApiRoutes();
    }

    /**
     * The web channel authorization route is registered by "channels" in bootstrap/app.php,
     * this adds one for API clients authenticated with a Sanctum token.
     */
    protected function configureApiRoutes(): void
    {
        Broadcast::routes([
            'prefix' => 'api/v1',
            'middleware' => ['api', 'auth:sanctum'],
        ]);
    }
}
