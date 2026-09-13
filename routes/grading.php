<?php

use App\Http\Controllers\Admin\GradingController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Grading Setup
    |--------------------------------------------------------------------------
    */

    Route::get('/grading', [GradingController::class, 'index'])
        ->name('admin.grading.index')
        ->middleware('permission:grading.view');

    Route::get('/grading/create', [GradingController::class, 'create'])
        ->name('admin.grading.create')
        ->middleware('permission:grading.manage');

    Route::post('/grading', [GradingController::class, 'store'])
        ->name('admin.grading.store')
        ->middleware('permission:grading.manage');

    Route::get('/grading/{grade}', [GradingController::class, 'show'])
        ->name('admin.grading.show')
        ->middleware('permission:grading.view');

    Route::get('/grading/{grade}/edit', [GradingController::class, 'edit'])
        ->name('admin.grading.edit')
        ->middleware('permission:grading.manage');

    Route::put('/grading/{grade}', [GradingController::class, 'update'])
        ->name('admin.grading.update')
        ->middleware('permission:grading.manage');

    Route::delete('/grading/{grade}', [GradingController::class, 'destroy'])
        ->name('admin.grading.destroy')
        ->middleware('permission:grading.manage');
});