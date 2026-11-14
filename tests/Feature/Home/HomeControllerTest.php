<?php

declare(strict_types=1);

use Domain\Users\Models\User;
use Modules\Web\Home\Controllers\HomeController;

it('renders the home page for guests', function () {
    $this->get(action(HomeController::class))
        ->assertSuccessful()
        ->assertInertia(fn ($page) => $page->component('HomePage')->where('auth', null));
});

it('shares the authenticated user on the home page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(action(HomeController::class))
        ->assertSuccessful()
        ->assertInertia(fn ($page) => $page->where('auth.id', $user->getRouteKey()));
});
