<?php

use App\Http\Controllers\Admin\PaymentController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin/payments')
    ->middleware(['auth'])
    ->group(function () {

        Route::get('/', [PaymentController::class, 'index'])
            ->middleware('can:payments.view')
            ->name('admin.payments.index');

        Route::get('/create', [PaymentController::class, 'create'])
            ->middleware('can:payments.create')
            ->name('admin.payments.create');

        /*
         * AJAX filters
         */
        Route::get('/filter-classes', [PaymentController::class, 'filterClasses'])
            ->middleware('can:payments.create')
            ->name('admin.payments.filter-classes');

        Route::get('/filter-sections', [PaymentController::class, 'filterSections'])
            ->middleware('can:payments.create')
            ->name('admin.payments.filter-sections');

        Route::get('/filter-students', [PaymentController::class, 'filterStudents'])
            ->middleware('can:payments.create')
            ->name('admin.payments.filter-students');

        Route::get('/filter-invoices', [PaymentController::class, 'filterInvoices'])
            ->middleware('can:payments.create')
            ->name('admin.payments.filter-invoices');

        Route::post('/', [PaymentController::class, 'store'])
            ->middleware('can:payments.create')
            ->name('admin.payments.store');

        Route::get('/{payment}', [PaymentController::class, 'show'])
            ->middleware('can:payments.view')
            ->name('admin.payments.show');

        Route::patch('/{payment}/reverse', [PaymentController::class, 'reverse'])
            ->middleware('can:payments.reverse')
            ->name('admin.payments.reverse');
    });