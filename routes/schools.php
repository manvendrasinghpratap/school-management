<?php

use App\Http\Controllers\Admin\SchoolController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get(
            'schools',
            [SchoolController::class, 'index']
        )
            ->name('schools.index')
            ->middleware('permission:schools.view');

        Route::get(
            'schools/create',
            [SchoolController::class, 'create']
        )
            ->name('schools.create')
            ->middleware('permission:schools.create');

        Route::post(
            'schools',
            [SchoolController::class, 'store']
        )
            ->name('schools.store')
            ->middleware('permission:schools.create');

        Route::get(
            'schools/{school}',
            [SchoolController::class, 'show']
        )
            ->name('schools.show')
            ->middleware('permission:schools.view');

        Route::get(
            'schools/{school}/edit',
            [SchoolController::class, 'edit']
        )
            ->name('schools.edit')
            ->middleware('permission:schools.view');

        Route::put(
            'schools/{school}',
            [SchoolController::class, 'update']
        )
            ->name('schools.update')
            ->middleware('permission:schools.update');

        Route::delete(
            'schools/{school}',
            [SchoolController::class, 'destroy']
        )
            ->name('schools.destroy')
            ->middleware('permission:schools.delete');
    });