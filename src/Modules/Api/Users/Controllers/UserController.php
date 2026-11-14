<?php

declare(strict_types=1);

namespace Modules\Api\Users\Controllers;

use Domain\Users\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Modules\Api\Users\Resources\UserResource;

class UserController implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth:sanctum'),
        ];
    }

    public function __invoke(#[CurrentUser] User $user): UserResource
    {
        Gate::authorize('view', $user);

        return UserResource::make($user->load('roles', 'permissions'));
    }
}
