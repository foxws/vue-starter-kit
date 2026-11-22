<?php

declare(strict_types=1);

use Domain\Users\Models\User;
use Foundation\Settings\GeneralSettings;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

// Unique accounts: the throttles count in the cache, which tests don't always roll back.
beforeEach(function () {
    $this->email = Str::lower(Str::random(16)).'@example.com';
    $this->user = User::factory()->create(['email' => $this->email, 'password' => 'password']);
});

dataset('unconfirmed passwords', [
    'another confirmation' => [['password' => 'An0ther-Secret-Passw0rd!', 'password_confirmation' => 'A-Typ0-In-The-Passw0rd!']],
    'no confirmation' => [['password' => 'An0ther-Secret-Passw0rd!']],
    'no password' => [[]],
]);

it('refuses to reset to a password that is not confirmed', function (array $input) {
    $this->post(route('password.update'), [
        'token' => Password::createToken($this->user),
        'email' => $this->email,
        ...$input,
    ])->assertSessionHasErrors('password');

    expect(Hash::check('password', $this->user->refresh()->password))->toBeTrue();
})->with('unconfirmed passwords');

it('refuses to change to a password that is not confirmed', function (array $input) {
    $this->actingAs($this->user)
        ->put(route('user-password.update'), ['current_password' => 'password', ...$input])
        ->assertSessionHasErrorsIn('updatePassword', 'password');

    expect(Hash::check('password', $this->user->refresh()->password))->toBeTrue();
})->with('unconfirmed passwords');

it('refuses to register with a password that is not confirmed', function (array $input) {
    $settings = app(GeneralSettings::class);
    $settings->allow_registration = true;
    $settings->save();

    $this->post(route('register.store'), ['name' => 'Jane', 'email' => 'jane@example.com', ...$input])
        ->assertSessionHasErrors('password');

    expect(User::query()->where('email', 'jane@example.com')->exists())->toBeFalse();
})->with('unconfirmed passwords');
