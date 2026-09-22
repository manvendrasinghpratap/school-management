<?php

use App\Http\Controllers\Admin\TransportFeeController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin/transport/fees')
    ->middleware(['auth'])
    ->group(function () {
        Route::get('/', [TransportFeeController::class, 'fees'])
            ->middleware('permission:transport.fees.view')
            ->name('admin.transport.fees.index');

        Route::get('/create', [TransportFeeController::class, 'createFee'])
            ->middleware('permission:transport.fees.manage')
            ->name('admin.transport.fees.create');

        Route::post('/', [TransportFeeController::class, 'storeFee'])
            ->middleware('permission:transport.fees.manage')
            ->name('admin.transport.fees.store');

        Route::get('/assignments', [TransportFeeController::class, 'assignments'])
            ->middleware('permission:transport.fees.manage')
            ->name('admin.transport.fees.assignments');

        Route::get('/{fee}/edit', [TransportFeeController::class, 'editFee'])
            ->middleware('permission:transport.fees.manage')
            ->name('admin.transport.fees.edit');

        Route::put('/{fee}', [TransportFeeController::class, 'updateFee'])
            ->middleware('permission:transport.fees.manage')
            ->name('admin.transport.fees.update');

        Route::patch('/{fee}', [TransportFeeController::class, 'updateFee'])
            ->middleware('permission:transport.fees.manage')
            ->name('admin.transport.fees.patch');

        Route::delete('/{fee}', [TransportFeeController::class, 'destroyFee'])
            ->middleware('permission:transport.fees.manage')
            ->name('admin.transport.fees.destroy');

        Route::get('/{fee}', [TransportFeeController::class, 'showFee'])
            ->middleware('permission:transport.fees.view')
            ->name('admin.transport.fees.show');
    });
