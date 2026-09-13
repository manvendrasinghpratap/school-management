<?php

use App\Http\Controllers\Admin\InvoiceController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin/invoices')
    ->middleware(['auth'])
    ->group(function () {

        // Invoice listing
        Route::get('/', [InvoiceController::class, 'index'])
            ->middleware('can:invoices.view')
            ->name('admin.invoices.index');

        // Create invoice
        Route::get('/create', [InvoiceController::class, 'create'])
            ->middleware('can:invoices.manage')
            ->name('admin.invoices.create');

        // Dynamic Academic Year → Class
        Route::get('/filter-classes', [InvoiceController::class, 'filterClasses'])
            ->middleware('can:invoices.manage')
            ->name('admin.invoices.filter-classes');

        // Dynamic Class → Section
        Route::get('/filter-sections', [InvoiceController::class, 'filterSections'])
            ->middleware('can:invoices.manage')
            ->name('admin.invoices.filter-sections');

        // Dynamic Section → Student
        Route::get('/filter-students', [InvoiceController::class, 'filterStudents'])
            ->middleware('can:invoices.manage')
            ->name('admin.invoices.filter-students');

        // Student → Current Fee Assignments
        Route::get('/filter-student-fees', [InvoiceController::class, 'filterStudentFees'])
            ->middleware('can:invoices.manage')
            ->name('admin.invoices.filter-student-fees');

        // Store invoice
        Route::post('/', [InvoiceController::class, 'store'])
            ->middleware('can:invoices.manage')
            ->name('admin.invoices.store');

        // View invoice
        Route::get('/{invoice}', [InvoiceController::class, 'show'])
            ->middleware('can:invoices.view')
            ->name('admin.invoices.show');

        // Edit invoice
        Route::get('/{invoice}/edit', [InvoiceController::class, 'edit'])
            ->middleware('can:invoices.manage')
            ->name('admin.invoices.edit');

        // Update invoice
        Route::put('/{invoice}', [InvoiceController::class, 'update'])
            ->middleware('can:invoices.manage')
            ->name('admin.invoices.update');

        // Cancel invoice
        Route::patch('/{invoice}/cancel', [InvoiceController::class, 'cancel'])
            ->middleware('can:invoices.manage')
            ->name('admin.invoices.cancel');

        // Delete invoice
        Route::delete('/{invoice}', [InvoiceController::class, 'destroy'])
            ->middleware('can:invoices.manage')
            ->name('admin.invoices.destroy');
    });