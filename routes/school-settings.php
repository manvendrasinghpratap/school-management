<?php

use App\Http\Controllers\Admin\SchoolController;
use App\Http\Controllers\Admin\SystemSettingsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Initial school setup
        Route::get(
            'school/setup',
            [SchoolController::class, 'create']
        )
            ->name('school.setup')
            ->middleware('permission:schools.create');

        Route::post(
            'school/setup',
            [SchoolController::class, 'store']
        )
            ->name('school.setup.store')
            ->middleware('permission:schools.create');

        // School profile
        Route::get(
            'school/{school}/edit',
            [SchoolController::class, 'edit']
        )
            ->name('school.edit')
            ->middleware('permission:schools.view');

        Route::put(
            'school/{school}',
            [SchoolController::class, 'update']
        )
            ->name('school.update')
            ->middleware('permission:schools.update');

        // School settings
        Route::get(
            'school/{school}/settings',
            [SystemSettingsController::class, 'edit']
        )
            ->name('school.settings.edit')
            ->middleware('permission:settings.view');

        Route::put(
            'school/{school}/settings',
            [SystemSettingsController::class, 'update']
        )
            ->name('school.settings.update')
            ->middleware('permission:settings.update');
    });