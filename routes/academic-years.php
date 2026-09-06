<?php

use App\Http\Controllers\Admin\AcademicYearController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Academic Years
        |--------------------------------------------------------------------------
        */

        Route::patch(
            'academic-years/{academicYear}/set-current',
            [AcademicYearController::class, 'setCurrent']
        )->name('academic-years.set-current');

        Route::resource(
            'academic-years',
            AcademicYearController::class
        )->parameters([
            'academic-years' => 'academicYear',
        ]);
    });