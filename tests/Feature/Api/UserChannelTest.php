<?php

declare(strict_types=1);

use Domain\Users\Models\User;
use Illuminate\Support\Facades\Broadcast;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    // Tests broadcast with the "null" driver, which authorizes every channel, so switch
    // to Reverb and register the channels again on it.
    config([
        'broadcasting.default' => 'reverb',
        'broadcasting.connections.reverb.key' => 'app-key',
        'broadcasting.connections.reverb.secret' => 'app-secret',
        'broadcasting.connections.reverb.app_id' => 'app-id',
    ]);

    Broadcast::forgetDrivers();

    require base_path('routes/channels.php');
});

function authorizeChannel(User $user): array
{
    return ['socket_id' => '1234.5678', 'channel_name' => "private-users.{$user->getRouteKey()}"];
}

it('authorizes a user on their own channel', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post('/broadcasting/auth', authorizeChannel($user))
        ->assertSuccessful()
        ->assertJsonStructure(['auth']);
});

it('forbids a user from listening on the channel of another user', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post('/broadcasting/auth', authorizeChannel(User::factory()->create()))
        ->assertForbidden();
});

it('authorizes an api token on the channel of its user', function () {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    $this->postJson('/api/v1/broadcasting/auth', authorizeChannel($user))
        ->assertSuccessful()
        ->assertJsonStructure(['auth']);
});

it('returns 401 when the api request has no token', function () {
    $this->postJson('/api/v1/broadcasting/auth', authorizeChannel(User::factory()->create()))
        ->assertUnauthorized();
});

it('sends broadcast notifications on the channel of the user', function () {
    $user = User::factory()->create();

    expect($user->receivesBroadcastNotificationsOn())->toBe("users.{$user->getRouteKey()}");
});
