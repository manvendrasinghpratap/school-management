<?php

use App\Http\Controllers\Admin\GradeCalculationController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware('auth')->group(function () {

    Route::get('/grade-calculation', [GradeCalculationController::class, 'index'])
        ->name('admin.grade-calculation.index')
        ->middleware('permission:results.view');

    Route::post('/grade-calculation/calculate', [GradeCalculationController::class, 'calculate'])
        ->name('admin.grade-calculation.calculate')
        ->middleware('permission:results.calculate');

});