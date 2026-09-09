<?php

use App\Http\Controllers\Admin\AttendanceController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->middleware('auth')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Student Attendance
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/attendance',
            [AttendanceController::class, 'index']
        )
            ->name('admin.attendance.index')
            ->middleware('permission:attendance.view');

        Route::get(
            '/attendance/create',
            [AttendanceController::class, 'create']
        )
            ->name('admin.attendance.create')
            ->middleware('permission:attendance.mark');

        Route::post(
            '/attendance',
            [AttendanceController::class, 'store']
        )
            ->name('admin.attendance.store')
            ->middleware('permission:attendance.mark');


        Route::get(
            '/attendance/students',
            [AttendanceController::class, 'students']
        )
            ->name('admin.attendance.students')
            ->middleware('permission:attendance.mark');


        Route::get(
            '/attendance/{attendance}',
            [AttendanceController::class, 'show']
        )
            ->name('admin.attendance.show')
            ->middleware('permission:attendance.view');

        Route::get(
            '/attendance/{attendance}/edit',
            [AttendanceController::class, 'edit']
        )
            ->name('admin.attendance.edit')
            ->middleware('permission:attendance.update');

        Route::put(
            '/attendance/{attendance}',
            [AttendanceController::class, 'update']
        )
            ->name('admin.attendance.update')
            ->middleware('permission:attendance.update');

        Route::delete(
            '/attendance/{attendance}',
            [AttendanceController::class, 'destroy']
        )
            ->name('admin.attendance.destroy')
            ->middleware('permission:attendance.update');

        Route::get(
            '/attendance-reports',
            [AttendanceController::class, 'reports']
        )
            ->name('admin.attendance.reports')
            ->middleware('permission:attendance.reports');

    });