<?php

use App\Http\Controllers\Admin\AcademicHierarchyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Common Academic Hierarchy AJAX Routes
|--------------------------------------------------------------------------
|
| These routes are intentionally module-independent.
| They are protected by authenticated session access and the service
| enforces school isolation on every query.
|
*/

Route::prefix('admin/academic-hierarchy')
    ->middleware(['auth'])
    ->name('admin.academic-hierarchy.')
    ->group(function () {
        Route::get('/classes', [
            AcademicHierarchyController::class,
            'classes',
        ])->name('classes');

        Route::get('/sections', [
            AcademicHierarchyController::class,
            'sections',
        ])->name('sections');

        Route::get('/students', [
            AcademicHierarchyController::class,
            'students',
        ])->name('students');
    });
