<?php

use App\Http\Controllers\Install;
use Illuminate\Support\Facades\Route;

Route::prefix('install')->as('install.')->middleware(['web', 'prevent_installation'])->group(function () {
    Route::get('', Install\RequirementController::class)->name('requirements');
    Route::get('permissions', Install\PermissionController::class)->name('permissions');
    Route::get('database', Install\DatabaseController::class)->name('database');
    Route::get('finalize', Install\FinalizeController::class)->name('finalize');
    Route::get('finish', Install\FinishedController::class)->name('finished');

    Route::resource('setup', Install\SetupController::class)->only(['index', 'store'])->name('index', 'setup');
    Route::resource('user', Install\UserController::class)->only(['index', 'store'])->name('index', 'user');
});
