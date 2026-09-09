<?php

use App\Http\Controllers\Admin\LeaveController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->middleware('auth')
    ->group(function () {

        Route::get(
            '/leaves',
            [LeaveController::class, 'index']
        )
            ->name('admin.leaves.index')
            ->middleware('permission:leaves.view');

        Route::get(
            '/leaves/create',
            [LeaveController::class, 'create']
        )
            ->name('admin.leaves.create')
            ->middleware('permission:leaves.create');

        Route::post(
            '/leaves',
            [LeaveController::class, 'store']
        )
            ->name('admin.leaves.store')
            ->middleware('permission:leaves.create');

        Route::get(
            '/leaves/{leave}',
            [LeaveController::class, 'show']
        )
            ->name('admin.leaves.show')
            ->middleware('permission:leaves.view');

        Route::get(
            '/leaves/{leave}/edit',
            [LeaveController::class, 'edit']
        )
            ->name('admin.leaves.edit')
            ->middleware('permission:leaves.update');

        Route::put(
            '/leaves/{leave}',
            [LeaveController::class, 'update']
        )
            ->name('admin.leaves.update')
            ->middleware('permission:leaves.update');

        Route::post(
            '/leaves/{leave}/approve',
            [LeaveController::class, 'approve']
        )
            ->name('admin.leaves.approve')
            ->middleware('permission:leaves.approve');

        Route::post(
            '/leaves/{leave}/reject',
            [LeaveController::class, 'reject']
        )
            ->name('admin.leaves.reject')
            ->middleware('permission:leaves.reject');

        Route::post(
            '/leaves/{leave}/cancel',
            [LeaveController::class, 'cancel']
        )
            ->name('admin.leaves.cancel')
            ->middleware('permission:leaves.update');

        Route::delete(
            '/leaves/{leave}',
            [LeaveController::class, 'destroy']
        )
            ->name('admin.leaves.destroy')
            ->middleware('permission:leaves.delete');

        Route::get(
            '/leave-reports',
            [LeaveController::class, 'reports']
        )
            ->name('admin.leaves.reports')
            ->middleware('permission:leaves.reports');
    });