<?php

use App\Http\Controllers\Admin\ScholarshipController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin/scholarships')
    ->middleware(['auth'])
    ->group(function () {
        Route::get('/', [ScholarshipController::class, 'index'])
            ->middleware('can:fees.view')
            ->name('admin.scholarships.index');

        Route::get('/create', [ScholarshipController::class, 'create'])
            ->middleware('can:fees.manage')
            ->name('admin.scholarships.create');

        Route::post('/', [ScholarshipController::class, 'store'])
            ->middleware('can:fees.manage')
            ->name('admin.scholarships.store');

        Route::get('/{scholarship}', [ScholarshipController::class, 'show'])
            ->middleware('can:fees.view')
            ->name('admin.scholarships.show');

        Route::get('/{scholarship}/edit', [ScholarshipController::class, 'edit'])
            ->middleware('can:fees.manage')
            ->name('admin.scholarships.edit');

        Route::put('/{scholarship}', [ScholarshipController::class, 'update'])
            ->middleware('can:fees.manage')
            ->name('admin.scholarships.update');

        Route::patch('/{scholarship}/toggle-status', [ScholarshipController::class, 'toggleStatus'])
            ->middleware('can:fees.manage')
            ->name('admin.scholarships.toggle-status');

        Route::delete('/{scholarship}', [ScholarshipController::class, 'destroy'])
            ->middleware('can:fees.manage')
            ->name('admin.scholarships.destroy');
    });
