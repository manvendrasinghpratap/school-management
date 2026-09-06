<?php

use App\Http\Controllers\Admin\GuardianController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // View guardians
        Route::get('guardians', [GuardianController::class, 'index'])
            ->name('guardians.index')
            ->middleware('permission:guardians.view');

        // Create guardian form
        Route::get('guardians/create', [GuardianController::class, 'create'])
            ->name('guardians.create')
            ->middleware('permission:guardians.create');

        // Store guardian
        Route::post('guardians', [GuardianController::class, 'store'])
            ->name('guardians.store')
            ->middleware('permission:guardians.create');

        // View guardian
        Route::get('guardians/{guardian}', [GuardianController::class, 'show'])
            ->name('guardians.show')
            ->middleware('permission:guardians.view');

        // Edit guardian form
        Route::get('guardians/{guardian}/edit', [GuardianController::class, 'edit'])
            ->name('guardians.edit')
            ->middleware('permission:guardians.update');

        // Update guardian
        Route::put('guardians/{guardian}', [GuardianController::class, 'update'])
            ->name('guardians.update')
            ->middleware('permission:guardians.update');

        // Delete guardian
        Route::delete('guardians/{guardian}', [GuardianController::class, 'destroy'])
            ->name('guardians.destroy')
            ->middleware('permission:guardians.delete');
    });