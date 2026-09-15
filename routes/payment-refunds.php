<?php

use App\Http\Controllers\Admin\PaymentRefundController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin/payment-refunds')
    ->middleware(['auth'])
    ->group(function () {

        // Refund request listing
        Route::get('/', [PaymentRefundController::class, 'index'])
            ->middleware('can:payment-refunds.view')
            ->name('admin.payment-refunds.index');

        // Refund details
        Route::get('/{paymentRefund}', [PaymentRefundController::class, 'show'])
            ->middleware('can:payment-refunds.view')
            ->name('admin.payment-refunds.show');

        // Approve refund
        Route::patch('/{paymentRefund}/approve', [PaymentRefundController::class, 'approve'])
            ->middleware('can:payment-refunds.approve')
            ->name('admin.payment-refunds.approve');

        // Reject refund
        Route::patch('/{paymentRefund}/reject', [PaymentRefundController::class, 'reject'])
            ->middleware('can:payment-refunds.reject')
            ->name('admin.payment-refunds.reject');

        // Process refund
        Route::patch('/{paymentRefund}/process', [PaymentRefundController::class, 'process'])
            ->middleware('can:payment-refunds.process')
            ->name('admin.payment-refunds.process');

        // Cancel refund request
        Route::patch('/{paymentRefund}/cancel', [PaymentRefundController::class, 'cancel'])
            ->middleware('can:payment-refunds.cancel')
            ->name('admin.payment-refunds.cancel');
    });