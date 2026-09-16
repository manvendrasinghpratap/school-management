<?php

use App\Http\Controllers\Admin\StudentFeeController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin/student-fees')
    ->middleware(['auth'])
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Student Fee Listing
        |--------------------------------------------------------------------------
        */

        Route::get('/', [StudentFeeController::class, 'index'])
            ->middleware('can:fees.view')
            ->name('admin.student-fees.index');


        /*
        |--------------------------------------------------------------------------
        | Create Student Fee
        |--------------------------------------------------------------------------
        */

        Route::get('/create', [StudentFeeController::class, 'create'])
            ->middleware('can:fees.manage')
            ->name('admin.student-fees.create');


        /*
        |--------------------------------------------------------------------------
        | AJAX - Filter Classes
        |--------------------------------------------------------------------------
        |
        | Academic Year → Class
        |
        */

        Route::get('/filter-classes', [StudentFeeController::class, 'filterClasses'])
            ->middleware('can:fees.manage')
            ->name('admin.student-fees.filter-classes');


        /*
        |--------------------------------------------------------------------------
        | AJAX - Filter Sections
        |--------------------------------------------------------------------------
        |
        | Class → Section
        |
        */

        Route::get('/filter-sections', [StudentFeeController::class, 'filterSections'])
            ->middleware('can:fees.manage')
            ->name('admin.student-fees.filter-sections');


        /*
        |--------------------------------------------------------------------------
        | AJAX - Filter Students
        |--------------------------------------------------------------------------
        |
        | Academic Year → Class → Section → Student
        |
        */

        Route::get('/filter-students', [StudentFeeController::class, 'filterStudents'])
            ->middleware('can:fees.manage')
            ->name('admin.student-fees.filter-students');


        /*
        |--------------------------------------------------------------------------
        | Store
        |--------------------------------------------------------------------------
        */

        Route::post('/', [StudentFeeController::class, 'store'])
            ->middleware('can:fees.manage')
            ->name('admin.student-fees.store');

        /*
        |--------------------------------------------------------------------------
        | View
        |--------------------------------------------------------------------------
        */

        Route::get('/{studentFee}', [StudentFeeController::class, 'show'])
            ->middleware('can:fees.view')
            ->name('admin.student-fees.show');


        /*
        |--------------------------------------------------------------------------
        | Edit
        |--------------------------------------------------------------------------
        */

        Route::get('/{studentFee}/edit', [StudentFeeController::class, 'edit'])
            ->middleware('can:fees.manage')
            ->name('admin.student-fees.edit');


        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        Route::put('/{studentFee}', [StudentFeeController::class, 'update'])
            ->middleware('can:fees.manage')
            ->name('admin.student-fees.update');


        /*
        |--------------------------------------------------------------------------
        | Cancel
        |--------------------------------------------------------------------------
        */

        Route::patch('/{studentFee}/cancel', [StudentFeeController::class, 'cancel'])
            ->middleware('can:fees.manage')
            ->name('admin.student-fees.cancel');


        /*
        |--------------------------------------------------------------------------
        | Delete
        |--------------------------------------------------------------------------
        */

        Route::delete('/{studentFee}', [StudentFeeController::class, 'destroy'])
            ->middleware('can:fees.manage')
            ->name('admin.student-fees.destroy');

    });