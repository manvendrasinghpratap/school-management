<?php
use App\Http\Controllers\Admin\FinanceReportController;

Route::prefix('admin/finance-reports')
    ->middleware(['auth'])
    ->group(function () {
        Route::get('/collection', [FinanceReportController::class,'collection'])->middleware('can:payments.view')->name('admin.finance-reports.collection'); 
        Route::get('/outstanding', [FinanceReportController::class,'outstanding'])->middleware('can:payments.view')->name('admin.finance-reports.outstanding');
        Route::get('/payments', [FinanceReportController::class,'payments'])->middleware('can:payments.view')->name('admin.finance-reports.payments');
        Route::get('/refunds', [FinanceReportController::class,'refunds'])->middleware('can:payment-refunds.view')->name('admin.finance-reports.refunds');
        Route::get('/payment-methods', [ FinanceReportController::class,'paymentMethods' ])->middleware('can:payments.view')->name('admin.finance-reports.payment-methods');
        Route::get('/collection/excel', [FinanceReportController::class, 'collectionExcel'])->middleware('can:payments.view')->name('admin.finance-reports.collection.excel');
        Route::get('/collection/pdf',[FinanceReportController::class, 'collectionPdf'])->middleware('can:payments.view')->name('admin.finance-reports.collection.pdf');
        Route::get('/outstanding/excel', [FinanceReportController::class, 'outstandingExcel'])->middleware('can:invoices.view')->name('admin.finance-reports.outstanding.excel');
        Route::get('/outstanding/pdf', [FinanceReportController::class, 'outstandingPdf'])->middleware('can:invoices.view')->name('admin.finance-reports.outstanding.pdf');
        Route::get('/payments/excel', [FinanceReportController::class, 'paymentExcel'])->middleware('can:payments.view')->name('admin.finance-reports.payments.excel');
        Route::get('/payments/pdf', [FinanceReportController::class, 'paymentPdf'])->middleware('can:payments.view')->name('admin.finance-reports.payments.pdf');
        Route::get('/payment-methods/excel', [FinanceReportController::class, 'paymentMethodsExcel'])->middleware('can:payments.view')->name('admin.finance-reports.payment-methods.excel');
        Route::get('/payment-methods/pdf', [FinanceReportController::class, 'paymentMethodsPdf'])->middleware('can:payments.view')->name('admin.finance-reports.payment-methods.pdf');
        Route::get('/refunds/excel', [FinanceReportController::class, 'refundsExcel'])->middleware('can:payment-refunds.view')->name('admin.finance-reports.refunds.excel'); 
        Route::get('/refunds/pdf', [FinanceReportController::class, 'refundsPdf'])->middleware('can:payment-refunds.view')->name('admin.finance-reports.refunds.pdf');

        
    });