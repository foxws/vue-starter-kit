<?php

declare(strict_types=1);

use Domain\Users\Models\User;

it('shows the health check results to a super-admin', function () {
    $user = User::factory()->create();
    $user->assignRole('super-admin');

    $this->artisan('health:check')->assertSuccessful();

    $this->actingAs($user)
        ->getJson(route('health'))
        ->assertSuccessful()
        ->assertJsonStructure(['finishedAt', 'checkResults']);
});

it('hides the health check results from regular users', function () {
    $this->actingAs(User::factory()->create())
        ->getJson(route('health'))
        ->assertForbidden();
});
