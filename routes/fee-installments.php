<?php

use App\Http\Controllers\Admin\FeeInstallmentController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->middleware(['auth'])
    ->group(function () {

        Route::get(
            'fee-installments',
            [FeeInstallmentController::class, 'index']
        )->name('admin.fee-installments.index');

        Route::get(
            'fee-installments/create',
            [FeeInstallmentController::class, 'create']
        )->name('admin.fee-installments.create');

        Route::post(
            'fee-installments',
            [FeeInstallmentController::class, 'store']
        )->name('admin.fee-installments.store');

        Route::get(
            'fee-installments/{feeInstallment}',
            [FeeInstallmentController::class, 'show']
        )->name('admin.fee-installments.show');

        Route::get(
            'fee-installments/{feeInstallment}/edit',
            [FeeInstallmentController::class, 'edit']
        )->name('admin.fee-installments.edit');

        Route::put(
            'fee-installments/{feeInstallment}',
            [FeeInstallmentController::class, 'update']
        )->name('admin.fee-installments.update');

        Route::patch(
            'fee-installments/{feeInstallment}/toggle-status',
            [FeeInstallmentController::class, 'toggleStatus']
        )->name('admin.fee-installments.toggle-status');

        Route::delete(
            'fee-installments/{feeInstallment}',
            [FeeInstallmentController::class, 'destroy']
        )->name('admin.fee-installments.destroy');
    });