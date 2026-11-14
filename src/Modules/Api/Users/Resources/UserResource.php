<?php

declare(strict_types=1);

namespace Modules\Api\Users\Resources;

use Domain\Users\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin User
 */
class UserResource extends JsonResource
{
    /**
     * @var bool
     */
    public $preserveKeys = true;

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getRouteKey(),
            'name' => $this->name,
            'email' => $this->email,
            'avatar' => $this->whenAppended('avatar'),
            'roles' => $this->whenLoaded('roles', fn () => $this->assigned_roles),
            'permissions' => $this->whenLoaded('permissions', fn () => $this->assigned_permissions),
            'state' => $this->state->toArray(),
            'two_factor_enabled' => filled($this->two_factor_confirmed_at),
            'email_verified_at' => $this->email_verified_at?->toDateTimeString(),
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
