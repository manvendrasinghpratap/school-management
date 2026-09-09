<?php

use App\Http\Controllers\Admin\ExaminationController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->middleware('auth')
    ->group(function () {

        Route::get(
            '/examinations',
            [ExaminationController::class, 'index']
        )
            ->name('admin.examinations.index')
            ->middleware('permission:examinations.view');

        Route::get(
            '/examinations/create',
            [ExaminationController::class, 'create']
        )
            ->name('admin.examinations.create')
            ->middleware('permission:examinations.create');

        Route::post(
            '/examinations',
            [ExaminationController::class, 'store']
        )
            ->name('admin.examinations.store')
            ->middleware('permission:examinations.create');

        Route::get(
            '/examinations/{examination}',
            [ExaminationController::class, 'show']
        )
            ->name('admin.examinations.show')
            ->middleware('permission:examinations.view');

        Route::get(
            '/examinations/{examination}/edit',
            [ExaminationController::class, 'edit']
        )
            ->name('admin.examinations.edit')
            ->middleware('permission:examinations.update');

        Route::put(
            '/examinations/{examination}',
            [ExaminationController::class, 'update']
        )
            ->name('admin.examinations.update')
            ->middleware('permission:examinations.update');

        Route::delete(
            '/examinations/{examination}',
            [ExaminationController::class, 'destroy']
        )
            ->name('admin.examinations.destroy')
            ->middleware('permission:examinations.delete');

    });