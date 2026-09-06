<?php

use App\Http\Controllers\Admin\StaffController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('staff', [StaffController::class, 'index'])
            ->name('staff.index')
            ->middleware('permission:staff.view');

        Route::get('staff/create', [StaffController::class, 'create'])
            ->name('staff.create')
            ->middleware('permission:staff.create');

        Route::post('staff', [StaffController::class, 'store'])
            ->name('staff.store')
            ->middleware('permission:staff.create');

        Route::get('staff/{staff}', [StaffController::class, 'show'])
            ->name('staff.show')
            ->middleware('permission:staff.view');

        Route::get('staff/{staff}/edit', [StaffController::class, 'edit'])
            ->name('staff.edit')
            ->middleware('permission:staff.update');

        Route::put('staff/{staff}', [StaffController::class, 'update'])
            ->name('staff.update')
            ->middleware('permission:staff.update');

        Route::delete('staff/{staff}', [StaffController::class, 'destroy'])
            ->name('staff.destroy')
            ->middleware('permission:staff.delete');
    });