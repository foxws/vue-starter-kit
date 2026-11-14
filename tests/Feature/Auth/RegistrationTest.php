<?php

declare(strict_types=1);

use Domain\Users\Models\User;
use Foundation\Settings\GeneralSettings;

function allowRegistration(bool $allowed): void
{
    $settings = app(GeneralSettings::class);
    $settings->allow_registration = $allowed;
    $settings->save();
}

it('hides registration while it is disabled in the settings', function () {
    allowRegistration(false);

    $this->get(route('register'))->assertNotFound();

    $this->post(route('register.store'), [
        'name' => 'Jane',
        'email' => 'jane@example.com',
        'password' => 'Sup3r-Secret-Passw0rd!',
        'password_confirmation' => 'Sup3r-Secret-Passw0rd!',
    ])->assertNotFound();

    expect(User::query()->where('email', 'jane@example.com')->exists())->toBeFalse();
});

it('registers a user when registration is enabled', function () {
    allowRegistration(true);

    $this->get(route('register'))
        ->assertSuccessful()
        ->assertInertia(fn ($page) => $page->component('Auth/AuthRegister'));

    $this->post(route('register.store'), [
        'name' => 'Jane',
        'email' => 'jane@example.com',
        'password' => 'Sup3r-Secret-Passw0rd!',
        'password_confirmation' => 'Sup3r-Secret-Passw0rd!',
    ])->assertRedirect();

    $this->assertAuthenticated();
    expect(User::query()->where('email', 'jane@example.com')->exists())->toBeTrue();
});
