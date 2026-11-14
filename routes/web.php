<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Web\Account\Controllers\PasskeyController;
use Modules\Web\Home\Controllers\HomeController;

// Home
Route::get('/', HomeController::class)->name('home');

// Account
Route::resource('passkeys', PasskeyController::class)->only(['index']);
