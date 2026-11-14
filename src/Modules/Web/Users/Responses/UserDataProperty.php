<?php

declare(strict_types=1);

namespace Modules\Web\Users\Responses;

use Domain\Users\DataObjects\UserData;
use Domain\Users\Models\User;
use Inertia\PropertyContext;
use Inertia\ProvidesInertiaProperty;

readonly class UserDataProperty implements ProvidesInertiaProperty
{
    public function __construct(
        protected ?User $user = null,
    ) {}

    public function toInertiaProperty(PropertyContext $context): mixed
    {
        return once(fn (): ?UserData => $this->user
            ? UserData::from($this->user->loadMissing('roles', 'permissions'))
            : null);
    }
}
