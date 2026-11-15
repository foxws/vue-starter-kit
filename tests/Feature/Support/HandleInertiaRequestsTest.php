<?php

declare(strict_types=1);

use Domain\Users\Models\User;
use Foundation\Settings\GeneralSettings;

it('shares validation errors with the next inertia response', function () {
    User::factory()->create(['email' => 'test@example.com']);

    $response = $this
        ->from(route('login'))
        ->post(route('login.store'), [
            'email' => 'test@example.com',
            'password' => 'wrong-password',
        ]);

    $response->assertRedirect(route('login'));

    $this->get(route('login'))
        ->assertInertia(fn ($page) => $page->has('errors.email'));
});

it('shares the site name from the application settings', function () {
    $settings = app(GeneralSettings::class);
    $settings->site_name = 'Boilerplate Test';
    $settings->save();

    $this->get(route('login'))
        ->assertInertia(fn ($page) => $page->where('app', 'Boilerplate Test'));
});

it('gives the auth pages relative urls, whatever scheme urls are generated with', function () {
    $this->get(route('login'))
        ->assertInertia(fn ($page) => $page
            ->where('action', '/login')
            ->where('forgotPasswordUrl', '/forgot-password'));
});

it('renders the reset password page with the token from the link', function () {
    $this->get(route('password.reset', ['token' => 'secret-token', 'email' => 'jane@example.com']))
        ->assertSuccessful()
        ->assertInertia(fn ($page) => $page
            ->component('Auth/AuthResetPassword')
            ->where('action', '/reset-password')
            ->where('token', 'secret-token')
            ->where('email', 'jane@example.com'));
});

it('shares where browsers reach reverb as the echo config', function () {
    config([
        'reverb.apps.apps.0.options.wsKey' => 'app-key',
        'reverb.apps.apps.0.options.wsHost' => 'ws.example.test',
        'reverb.apps.apps.0.options.wsPort' => 443,
        'reverb.apps.apps.0.options.wsScheme' => 'https',
    ]);

    $this->get(route('login'))
        ->assertInertia(fn ($page) => $page->where('echo', [
            'key' => 'app-key',
            'host' => 'ws.example.test',
            'port' => 443,
            'scheme' => 'https',
        ]));
});
