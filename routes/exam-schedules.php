<?php

use App\Http\Controllers\Admin\ExamScheduleController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware('auth')->group(function () {

    Route::get('/exam-schedules', [ExamScheduleController::class, 'index'])
        ->name('admin.exam-schedules.index')
        ->middleware('permission:exam-schedules.view');

    Route::get('/exam-schedules/create', [ExamScheduleController::class, 'create'])
        ->name('admin.exam-schedules.create')
        ->middleware('permission:exam-schedules.manage');

    Route::post('/exam-schedules', [ExamScheduleController::class, 'store'])
        ->name('admin.exam-schedules.store')
        ->middleware('permission:exam-schedules.manage');

    Route::get('/exam-schedules/{examSchedule}', [ExamScheduleController::class, 'show'])
        ->name('admin.exam-schedules.show')
        ->middleware('permission:exam-schedules.view');

    Route::get('/exam-schedules/{examSchedule}/edit', [ExamScheduleController::class, 'edit'])
        ->name('admin.exam-schedules.edit')
        ->middleware('permission:exam-schedules.manage');

    Route::put('/exam-schedules/{examSchedule}', [ExamScheduleController::class, 'update'])
        ->name('admin.exam-schedules.update')
        ->middleware('permission:exam-schedules.manage');

    Route::delete('/exam-schedules/{examSchedule}', [ExamScheduleController::class, 'destroy'])
        ->name('admin.exam-schedules.destroy')
        ->middleware('permission:exam-schedules.manage');
});