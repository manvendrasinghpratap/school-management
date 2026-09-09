<?php

use App\Http\Controllers\Admin\GraduationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('graduations', [GraduationController::class, 'index'])
        ->name('graduations.index')
        ->middleware('permission:graduation.view');

    Route::get('graduations/create', [GraduationController::class, 'create'])
        ->name('graduations.create')
        ->middleware('permission:graduation.manage');

    Route::post('graduations', [GraduationController::class, 'store'])
        ->name('graduations.store')
        ->middleware('permission:graduation.manage');

    Route::get('graduations/{graduation}', [GraduationController::class, 'show'])
        ->name('graduations.show')
        ->middleware('permission:graduation.view');

    Route::put('graduations/{graduation}/approve', [GraduationController::class, 'approve'])
        ->name('graduations.approve')
        ->middleware('permission:graduation.manage');

    Route::put('graduations/{graduation}/complete', [GraduationController::class, 'complete'])
        ->name('graduations.complete')
        ->middleware('permission:graduation.manage');

    Route::delete('graduations/{graduation}', [GraduationController::class, 'destroy'])
        ->name('graduations.destroy')
        ->middleware('permission:graduation.manage');
});