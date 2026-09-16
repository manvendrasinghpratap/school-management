<?php

use App\Http\Controllers\Admin\ReportsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'permission:reports.view'])
    ->prefix('admin/reports')
    ->name('admin.reports.')
    ->group(function () {
        Route::get('/', [ReportsController::class, 'index'])->name('index');

        Route::get('/students', [ReportsController::class, 'students'])->name('students');
        Route::get('/students/excel', [ReportsController::class, 'studentsExcel'])->name('students.excel');
        Route::get('/students/pdf', [ReportsController::class, 'studentsPdf'])->name('students.pdf');

        Route::get('/enrollment', [ReportsController::class, 'enrollment'])->name('enrollment');
        Route::get('/enrollment/excel', [ReportsController::class, 'enrollmentExcel'])->name('enrollment.excel');
        Route::get('/enrollment/pdf', [ReportsController::class, 'enrollmentPdf'])->name('enrollment.pdf');

        Route::get('/academic-performance', [ReportsController::class, 'academicPerformance'])->name('academic-performance');
        Route::get('/academic-performance/excel', [ReportsController::class, 'academicPerformanceExcel'])->name('academic-performance.excel');
        Route::get('/academic-performance/pdf', [ReportsController::class, 'academicPerformancePdf'])->name('academic-performance.pdf');

        Route::get('/attendance', [ReportsController::class, 'attendance'])->name('attendance');
        Route::get('/attendance/excel', [ReportsController::class, 'attendanceExcel'])->name('attendance.excel');
        Route::get('/attendance/pdf', [ReportsController::class, 'attendancePdf'])->name('attendance.pdf');

        Route::get('/staff', [ReportsController::class, 'staff'])->name('staff');
        Route::get('/staff/excel', [ReportsController::class, 'staffExcel'])->name('staff.excel');
        Route::get('/staff/pdf', [ReportsController::class, 'staffPdf'])->name('staff.pdf');
    });
