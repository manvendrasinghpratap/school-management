<?php

use App\Http\Controllers\Admin\StudentCourseController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware('auth')->group(function () {

    Route::get('/student-courses', [
        StudentCourseController::class,
        'index',
    ])
        ->name('admin.student-courses.index')
        ->middleware('permission:student-courses.view');

    Route::get('/student-courses/create', [
        StudentCourseController::class,
        'create',
    ])
        ->name('admin.student-courses.create')
        ->middleware('permission:student-courses.create');

    /*
    |--------------------------------------------------------------------------
    | AJAX / Dynamic Form Data
    |--------------------------------------------------------------------------
    */

    Route::get('/student-courses/data/terms/{academicYear}', [
        StudentCourseController::class,
        'terms',
    ])
        ->name('admin.student-courses.data.terms')
        ->middleware('permission:student-courses.create');

    Route::get('/student-courses/data/classes/{academicYear}', [
        StudentCourseController::class,
        'classes',
    ])
        ->name('admin.student-courses.data.classes')
        ->middleware('permission:student-courses.create');

    Route::get('/student-courses/data/sections/{class}', [
        StudentCourseController::class,
        'sections',
    ])
        ->name('admin.student-courses.data.sections')
        ->middleware('permission:student-courses.create');

    Route::get('/student-courses/data/students', [
        StudentCourseController::class,
        'students',
    ])
        ->name('admin.student-courses.data.students')
        ->middleware('permission:student-courses.create');

    /*
    |--------------------------------------------------------------------------
    | Registration CRUD
    |--------------------------------------------------------------------------
    */

    Route::post('/student-courses', [
        StudentCourseController::class,
        'store',
    ])
        ->name('admin.student-courses.store')
        ->middleware('permission:student-courses.create');

    Route::get('/student-courses/{studentCourse}', [
        StudentCourseController::class,
        'show',
    ])
        ->name('admin.student-courses.show')
        ->middleware('permission:student-courses.view');

    Route::get('/student-courses/{studentCourse}/edit', [
        StudentCourseController::class,
        'edit',
    ])
        ->name('admin.student-courses.edit')
        ->middleware('permission:student-courses.update');

    Route::put('/student-courses/{studentCourse}', [
        StudentCourseController::class,
        'update',
    ])
        ->name('admin.student-courses.update')
        ->middleware('permission:student-courses.update');

    Route::delete('/student-courses/{studentCourse}', [
        StudentCourseController::class,
        'destroy',
    ])
        ->name('admin.student-courses.destroy')
        ->middleware('permission:student-courses.delete');
});