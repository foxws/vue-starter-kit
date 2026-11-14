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
