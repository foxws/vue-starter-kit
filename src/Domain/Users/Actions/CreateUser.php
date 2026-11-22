<?php

declare(strict_types=1);

namespace Domain\Users\Actions;

use Domain\Users\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class CreateUser
{
    /**
     * Creates a user whether or not registration is open, as for an account made on the command line. Those
     * don't have to prove their email address, so it counts as verified unless $verified is false.
     *
     * @param  array<string, mixed>  $input
     *
     * @throws ValidationException
     */
    public function __invoke(array $input, bool $verified = true): User
    {
        /** @var array{name: string, email: string, password: string} $validated */
        $validated = Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)],
            'password' => ['required', 'string', Password::default()],
        ])->validate();

        return User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'email_verified_at' => $verified ? now() : null,
        ]);
    }
}
