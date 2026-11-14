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

it('lets an admin search users', function () {
    $admin = User::factory()->create();
    $admin->assignRole('super-admin');

    User::factory()->create(['name' => 'Jane Doe']);
    User::factory()->create(['name' => 'John Smith']);

    Sanctum::actingAs($admin);

    $this->getJson(route('api.users.index', ['query' => 'Jane']))
        ->assertSuccessful()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.name', 'Jane Doe');
});

it('forbids regular users from listing users', function () {
    Sanctum::actingAs(User::factory()->create());

    $this->getJson(route('api.users.index'))
        ->assertForbidden();
});
