<?php

declare(strict_types=1);

namespace Modules\Api\Authentication\Controllers;

use Illuminate\Http\JsonResponse;
use Spatie\ResponseCache\Attributes\Cache;

#[Cache(lifetime: 60 * 60, tags: ['api'])]
class HomeController
{
    public function __invoke(): JsonResponse
    {
        return response()->json([
            'name' => config('app.name'),
            'version' => 'v1',
            'links' => [
                'user' => route('api.user'),
                'users' => route('api.users.index'),
            ],
        ]);
    }
}
