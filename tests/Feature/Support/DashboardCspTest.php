<?php

declare(strict_types=1);

use Domain\Users\Models\User;

it('gives the horizon dashboard the nonce of the app policy', function () {
    $user = User::factory()->create();
    $user->assignRole('super-admin');

    $response = $this->actingAs($user)->get(route('horizon.index'));

    $response->assertSuccessful();

    preg_match("/'nonce-([^']+)'/", (string) $response->headers->get('Content-Security-Policy'), $matches);

    expect($matches[1] ?? null)->not->toBeNull()
        ->and($response->getContent())->toContain('<script type="module" nonce="'.$matches[1].'"');
});
