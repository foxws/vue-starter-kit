<?php

declare(strict_types=1);

use Domain\Users\Models\User;
use Laravel\Sanctum\Sanctum;

it('lists the available endpoints', function () {
    $this->getJson(route('api.home'))
        ->assertSuccessful()
        ->assertJsonPath('version', 'v1')
        ->assertJsonPath('links.user', route('api.user'));
});

it('returns the authenticated user', function () {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    $this->getJson(route('api.user'))
        ->assertSuccessful()
        ->assertJsonPath('id', $user->getRouteKey())
        ->assertJsonPath('email', $user->email)
        ->assertJsonMissingPath('password');
});

it('rejects guests', function () {
    $this->getJson(route('api.user'))
        ->assertUnauthorized();
});
