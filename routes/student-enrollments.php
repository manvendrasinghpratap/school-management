<?php

use App\Http\Controllers\Admin\StudentEnrollmentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('student-enrollments', [
            StudentEnrollmentController::class,
            'index'
        ])
            ->name('student-enrollments.index')
            ->middleware('permission:enrollments.view');

        Route::get('student-enrollments/create', [
            StudentEnrollmentController::class,
            'create'
        ])
            ->name('student-enrollments.create')
            ->middleware('permission:enrollments.create');

        Route::post('student-enrollments', [
            StudentEnrollmentController::class,
            'store'
        ])
            ->name('student-enrollments.store')
            ->middleware('permission:enrollments.create');

        Route::get('student-enrollments/{enrollment}', [
            StudentEnrollmentController::class,
            'show'
        ])
            ->name('student-enrollments.show')
            ->middleware('permission:enrollments.view');

        Route::get('student-enrollments/{enrollment}/edit', [
            StudentEnrollmentController::class,
            'edit'
        ])
            ->name('student-enrollments.edit')
            ->middleware('permission:enrollments.update');

        Route::put('student-enrollments/{enrollment}', [
            StudentEnrollmentController::class,
            'update'
        ])
            ->name('student-enrollments.update')
            ->middleware('permission:enrollments.update');

        Route::delete('student-enrollments/{enrollment}', [
            StudentEnrollmentController::class,
            'destroy'
        ])
            ->name('student-enrollments.destroy')
            ->middleware('permission:enrollments.delete');

        Route::put('student-enrollments/{enrollment}/restore', [
            StudentEnrollmentController::class,
            'restore'
        ])
            ->name('student-enrollments.restore')
            ->middleware('permission:enrollments.update');
    });