<?php

use App\Http\Controllers\Admin\StudentPromotionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get(
            'student-promotions',
            [StudentPromotionController::class, 'index']
        )
            ->name('student-promotions.index')
            ->middleware('permission:promotions.view');


        /*
         * AJAX: sections for a class.
         *
         * IMPORTANT:
         * These routes must appear before
         * student-promotions/{studentPromotion}.
         */
        Route::get(
            'student-promotions/filter-sections',
            [StudentPromotionController::class, 'filterSections']
        )
            ->name('student-promotions.filter-sections')
            ->middleware('permission:promotions.create');


        /*
         * AJAX: students for academic year/class/section.
         */
        Route::get(
            'student-promotions/filter-students',
            [StudentPromotionController::class, 'filterStudents']
        )
            ->name('student-promotions.filter-students')
            ->middleware('permission:promotions.create');


        Route::get(
            'student-promotions/create',
            [StudentPromotionController::class, 'create']
        )
            ->name('student-promotions.create')
            ->middleware('permission:promotions.create');


        Route::post(
            'student-promotions',
            [StudentPromotionController::class, 'store']
        )
            ->name('student-promotions.store')
            ->middleware('permission:promotions.create');


        Route::get(
            'student-promotions/{studentPromotion}',
            [StudentPromotionController::class, 'show']
        )
            ->name('student-promotions.show')
            ->middleware('permission:promotions.view');


        Route::get(
            'student-promotions/{studentPromotion}/edit',
            [StudentPromotionController::class, 'edit']
        )
            ->name('student-promotions.edit')
            ->middleware('permission:promotions.update');


        Route::put(
            'student-promotions/{studentPromotion}',
            [StudentPromotionController::class, 'update']
        )
            ->name('student-promotions.update')
            ->middleware('permission:promotions.update');


        Route::put(
            'student-promotions/{studentPromotion}/approve',
            [StudentPromotionController::class, 'approve']
        )
            ->name('student-promotions.approve')
            ->middleware('permission:promotions.approve');


        Route::put(
            'student-promotions/{studentPromotion}/reject',
            [StudentPromotionController::class, 'reject']
        )
            ->name('student-promotions.reject')
            ->middleware('permission:promotions.reject');


        Route::delete(
            'student-promotions/{studentPromotion}',
            [StudentPromotionController::class, 'destroy']
        )
            ->name('student-promotions.destroy')
            ->middleware('permission:promotions.delete');

    });