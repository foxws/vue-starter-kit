<?php

declare(strict_types=1);

namespace Domain\Users\Actions;

use Domain\Users\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * @param  array<string, string>  $input
     */
    public function update(User $user, array $input): void
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)->ignore($user)],
        ])->validateWithBag('updateProfileInformation');

        DB::transaction(function () use ($user, $input): void {
            if ($input['email'] !== $user->email) {
                $this->updateVerifiedUser($user, $input);

                return;
            }

            $user->updateOrFail([
                'name' => $input['name'],
            ]);
        });
    }

    /**
     * @param  array<string, string>  $input
     */
    protected function updateVerifiedUser(User $user, array $input): void
    {
        $user->forceFill([
            'name' => $input['name'],
            'email' => $input['email'],
            'email_verified_at' => null,
        ])->saveOrFail();

        $user->sendEmailVerificationNotification();
    }
}
