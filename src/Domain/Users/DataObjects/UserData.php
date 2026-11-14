<?php

declare(strict_types=1);

namespace Domain\Users\DataObjects;

use Domain\Shared\DataObjects\StateData;
use Domain\Users\Models\User;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Lazy;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class UserData extends Data
{
    /**
     * @param  Lazy|list<string>  $roles
     * @param  Lazy|list<string>  $permissions
     */
    public function __construct(
        public string $id,
        public ?string $name,
        public string $email,
        public ?string $avatar,
        public StateData $state,
        public bool $two_factor_enabled,
        public Lazy|array $roles,
        public Lazy|array $permissions,
        public ?string $email_verified_at,
        public ?string $created_at,
        public ?string $updated_at,
    ) {}

    public static function fromModel(User $user): self
    {
        return new self(
            id: (string) $user->getRouteKey(),
            name: $user->name,
            email: $user->email,
            avatar: $user->avatar,
            state: StateData::fromState($user->state),
            two_factor_enabled: filled($user->two_factor_confirmed_at),
            roles: Lazy::whenLoaded('roles', $user, fn (): array => $user->assigned_roles->values()->all()),
            permissions: Lazy::whenLoaded('permissions', $user, fn (): array => $user->assigned_permissions->values()->all()),
            email_verified_at: $user->email_verified_at?->toDateTimeString(),
            created_at: $user->created_at?->toDateTimeString(),
            updated_at: $user->updated_at?->toDateTimeString(),
        );
    }
}
