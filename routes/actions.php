<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Web\Settings\Controllers\ApplicationSettingsController;

// Settings
Route::prefix('/settings')->name('settings.')->group(function () {
    Route::singleton('application', ApplicationSettingsController::class)->only(['show', 'update']);
});
