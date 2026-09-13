<?php

use App\Http\Controllers\Admin\FeeCategoryController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->middleware('auth')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Fee Categories
        |--------------------------------------------------------------------------
        */

        Route::get('/fee-categories', [FeeCategoryController::class, 'index'])
            ->name('admin.fee-categories.index');

        Route::get('/fee-categories/create', [FeeCategoryController::class, 'create'])
            ->name('admin.fee-categories.create');

        Route::post('/fee-categories', [FeeCategoryController::class, 'store'])
            ->name('admin.fee-categories.store');

        Route::get('/fee-categories/{feeCategory}', [FeeCategoryController::class, 'show'])
            ->name('admin.fee-categories.show');

        Route::get('/fee-categories/{feeCategory}/edit', [FeeCategoryController::class, 'edit'])
            ->name('admin.fee-categories.edit');

        Route::put('/fee-categories/{feeCategory}', [FeeCategoryController::class, 'update'])
            ->name('admin.fee-categories.update');

        Route::patch('/fee-categories/{feeCategory}/toggle-status', [FeeCategoryController::class, 'toggleStatus'])
            ->name('admin.fee-categories.toggle-status');

        Route::delete('/fee-categories/{feeCategory}', [FeeCategoryController::class, 'destroy'])
            ->name('admin.fee-categories.destroy');
    });