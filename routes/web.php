<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Web\Account\Controllers\PasskeyController;
use Modules\Web\Home\Controllers\HomeController;
use Spatie\Health\Http\Controllers\HealthCheckJsonResultsController;

// Home
Route::get('/', HomeController::class)->name('home');

// Account
Route::resource('passkeys', PasskeyController::class)->only(['index']);

// Health
Route::get('/health', HealthCheckJsonResultsController::class)
    ->middleware(['auth', 'can:viewHealth', 'cache.bypass'])
    ->name('health');
