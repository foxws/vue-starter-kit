<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Api\Authentication\Controllers\HomeController;
use Modules\Api\Users\Controllers\UserController;

Route::name('api.')->prefix('v1')->group(function () {
    Route::get('/', HomeController::class)->name('home');

    Route::get('/user', UserController::class)
        ->middleware('cache.bypass')
        ->name('user');
});
