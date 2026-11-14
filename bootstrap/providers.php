<?php

declare(strict_types=1);

use Foundation\Providers\AppServiceProvider;
use Foundation\Providers\AuthServiceProvider;
use Foundation\Providers\FortifyServiceProvider;
use Foundation\Providers\HealthServiceProvider;
use Foundation\Providers\HorizonServiceProvider;
use Foundation\Providers\InertiaServiceProvider;
use Foundation\Providers\RouteServiceProvider;
use Foundation\Providers\TypeScriptTransformerServiceProvider;

return [
    AppServiceProvider::class,
    AuthServiceProvider::class,
    FortifyServiceProvider::class,
    HealthServiceProvider::class,
    HorizonServiceProvider::class,
    InertiaServiceProvider::class,
    RouteServiceProvider::class,
    TypeScriptTransformerServiceProvider::class,
];
