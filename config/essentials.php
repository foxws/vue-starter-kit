<?php

declare(strict_types=1);

use Domain\Users\Models\User;
use Foxws\Essentials\Configurables\AggressivePrefetching;
use Foxws\Essentials\Configurables\AutomaticallyEagerLoadRelationships;
use Foxws\Essentials\Configurables\EnforceMorphMap;
use Foxws\Essentials\Configurables\FakeSleep;
use Foxws\Essentials\Configurables\ForceHttpsScheme;
use Foxws\Essentials\Configurables\ForceRootUrl;
use Foxws\Essentials\Configurables\ForceSecurePassword;
use Foxws\Essentials\Configurables\ImmutableDates;
use Foxws\Essentials\Configurables\ModelShouldBeStrict;
use Foxws\Essentials\Configurables\PreventStrayRequests;
use Foxws\Essentials\Configurables\ProhibitDestructiveCommands;
use Foxws\Essentials\Configurables\ResourceWithoutWrapping;
use Foxws\Essentials\Configurables\SetTrustedProxies;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Tags\Tag;

return [

    /*
    |--------------------------------------------------------------------------
    | Configurable Features
    |--------------------------------------------------------------------------
    |
    | This array contains a list of configurable features that can be enabled
    | or disabled in your application.
    | Each feature is represented by a class that implements the Configurable interface.
    | You can add or remove features from this array as needed.
    |
    */

    'configurables' => [
        AggressivePrefetching::class,
        AutomaticallyEagerLoadRelationships::class,
        EnforceMorphMap::class,
        FakeSleep::class,
        ForceHttpsScheme::class,
        ForceRootUrl::class,
        ForceSecurePassword::class,
        ImmutableDates::class,
        ModelShouldBeStrict::class,
        PreventStrayRequests::class,
        ProhibitDestructiveCommands::class,
        ResourceWithoutWrapping::class,
        SetTrustedProxies::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Trusted Proxies
    |--------------------------------------------------------------------------
    |
    | Whose X-Forwarded-For, -Port and -Proto headers are believed: addresses
    | or networks separated by commas, "private_ranges", or "*" for any.
    | "auto" is "*" on Laravel Cloud and "private_ranges" elsewhere.
    | X-Forwarded-Host is never trusted.
    |
    */

    'trusted_proxies' => env('TRUSTED_PROXIES', 'auto'),

    /*
    |--------------------------------------------------------------------------
    | Model Morph Map
    |--------------------------------------------------------------------------
    |
    | This array defines the morph map for your Eloquent models.
    | The morph map allows you to customize the names used for polymorphic relationships.
    |
    */

    'morph_map' => [
        'media' => Media::class,
        'tag' => Tag::class,
        'user' => User::class,
    ],

];
