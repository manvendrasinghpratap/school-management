<?php

use App\Http\Controllers\Admin\StaffAttendanceController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->middleware('auth')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Staff Attendance Listing
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/staff-attendance',
            [StaffAttendanceController::class, 'index']
        )
            ->name('admin.staff-attendance.index')
            ->middleware('permission:staff-attendance.view');


        /*
        |--------------------------------------------------------------------------
        | Create / Mark Staff Attendance
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/staff-attendance/create',
            [StaffAttendanceController::class, 'create']
        )
            ->name('admin.staff-attendance.create')
            ->middleware('permission:staff-attendance.mark');

        Route::post(
            '/staff-attendance',
            [StaffAttendanceController::class, 'store']
        )
            ->name('admin.staff-attendance.store')
            ->middleware('permission:staff-attendance.mark');


        /*
        |--------------------------------------------------------------------------
        | View Staff Attendance
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/staff-attendance/{staffAttendance}',
            [StaffAttendanceController::class, 'show']
        )
            ->name('admin.staff-attendance.show')
            ->middleware('permission:staff-attendance.view');


        /*
        |--------------------------------------------------------------------------
        | Edit / Update Staff Attendance
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/staff-attendance/{staffAttendance}/edit',
            [StaffAttendanceController::class, 'edit']
        )
            ->name('admin.staff-attendance.edit')
            ->middleware('permission:staff-attendance.update');

        Route::put(
            '/staff-attendance/{staffAttendance}',
            [StaffAttendanceController::class, 'update']
        )
            ->name('admin.staff-attendance.update')
            ->middleware('permission:staff-attendance.update');


        /*
        |--------------------------------------------------------------------------
        | Delete Staff Attendance
        |--------------------------------------------------------------------------
        */

        Route::delete(
            '/staff-attendance/{staffAttendance}',
            [StaffAttendanceController::class, 'destroy']
        )
            ->name('admin.staff-attendance.destroy')
            ->middleware('permission:staff-attendance.update');


        /*
        |--------------------------------------------------------------------------
        | Staff Attendance Reports
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/staff-attendance-reports',
            [StaffAttendanceController::class, 'reports']
        )
            ->name('admin.staff-attendance.reports')
            ->middleware('permission:staff-attendance.reports');

    });