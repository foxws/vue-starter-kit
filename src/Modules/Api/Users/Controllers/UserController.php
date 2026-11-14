<?php

declare(strict_types=1);

namespace Modules\Api\Users\Controllers;

use Domain\Users\DataObjects\UserData;
use Domain\Users\Models\User;
use Foxws\ScoutBuilder\AllowedSort;
use Foxws\ScoutBuilder\ScoutBuilder;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Spatie\LaravelData\PaginatedDataCollection;

class UserController implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth:sanctum'),
            new Middleware('cache.bypass'),
        ];
    }

    /**
     * Search users with ?query=, sort with ?sort=-newest and page with ?page[number]=2.
     *
     * @return PaginatedDataCollection<array-key, UserData>
     */
    public function index(Request $request): PaginatedDataCollection
    {
        Gate::authorize('viewAny', User::class);

        $users = ScoutBuilder::for(User::class, $request)
            ->allowedSorts(
                AllowedSort::latest('newest', 'created_at'),
                AllowedSort::oldest('oldest', 'created_at'),
                AllowedSort::field('name'),
            )
            ->defaultSort('newest')
            ->jsonPaginate();

        return UserData::collect($users, PaginatedDataCollection::class);
    }

    public function show(#[CurrentUser] User $user): UserData
    {
        Gate::authorize('view', $user);

        return UserData::from($user->load('roles', 'permissions'));
    }
}
