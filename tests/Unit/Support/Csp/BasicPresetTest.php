<?php

declare(strict_types=1);

use Spatie\Csp\Policy;
use Support\Csp\Presets\BasicPreset;

it('scopes connect sources to the application host', function () {
    config(['app.url' => 'https://app.example.com']);

    $policy = new Policy;
    (new BasicPreset)->configure($policy);

    expect($policy->getContents())
        ->toContain("connect-src 'self' blob: https://*.app.example.com wss://*.app.example.com")
        ->toContain("frame-ancestors 'none'")
        ->toContain("object-src 'none'");
});

it('upgrades insecure requests when the app runs on https', function () {
    config(['app.url' => 'https://app.example.com']);

    $policy = new Policy;
    (new BasicPreset)->configure($policy);

    expect($policy->getContents())->toContain('upgrade-insecure-requests');
});

it('leaves requests alone when the app runs on http', function () {
    config(['app.url' => 'http://localhost:8000']);

    $policy = new Policy;
    (new BasicPreset)->configure($policy);

    expect($policy->getContents())->not->toContain('upgrade-insecure-requests');
});
