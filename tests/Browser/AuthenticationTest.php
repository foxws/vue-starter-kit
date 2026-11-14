<?php

declare(strict_types=1);

use Domain\Users\Models\User;

it('renders the home page without javascript errors', function () {
    visit('/')
        ->assertSee('Vue Starter Kit')
        ->assertNoJavaScriptErrors();
});

it('logs a user in with their password', function () {
    $user = User::factory()->create(['email' => 'jane@example.com']);

    visit('/login')
        ->fill('email', 'jane@example.com')
        ->fill('password', 'password')
        ->press('Continue')
        ->assertPathIs('/')
        ->assertNoJavaScriptErrors();

    $this->assertAuthenticatedAs($user);
});
