<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

it('believes forwarded headers only from a proxy on a private address, and never the host', function (string $remote, string $ip, bool $secure) {
    Route::get('/_client', fn () => ['ip' => request()->ip(), 'secure' => request()->secure(), 'host' => request()->getHost()]);

    $this->withServerVariables(['REMOTE_ADDR' => $remote])
        ->getJson('http://app.example.com/_client', [
            'X-Forwarded-For' => '198.51.100.7',
            'X-Forwarded-Proto' => 'https',
            'X-Forwarded-Host' => 'attacker.example',
        ])
        ->assertExactJson(['ip' => $ip, 'secure' => $secure, 'host' => 'app.example.com']);
})->with([
    'a private proxy' => ['10.89.0.5', '198.51.100.7', true],
    'a client' => ['203.0.113.9', '203.0.113.9', false],
]);
