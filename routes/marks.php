<?php

use App\Http\Controllers\Admin\MarksController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware('auth')->group(function () {

    Route::get('/marks', [MarksController::class, 'index'])
        ->name('admin.marks.index')
        ->middleware('permission:marks.view');

    Route::get('/marks/create', [MarksController::class, 'create'])
        ->name('admin.marks.create')
        ->middleware('permission:marks.enter');

    Route::post('/marks', [MarksController::class, 'store'])
        ->name('admin.marks.store')
        ->middleware('permission:marks.enter');

    Route::get('/marks/{mark}', [MarksController::class, 'show'])
        ->name('admin.marks.show')
        ->middleware('permission:marks.view');

    Route::get('/marks/{mark}/edit', [MarksController::class, 'edit'])
        ->name('admin.marks.edit')
        ->middleware('permission:marks.update');

    Route::put('/marks/{mark}', [MarksController::class, 'update'])
        ->name('admin.marks.update')
        ->middleware('permission:marks.update');

    Route::post('/marks/{mark}/submit', [MarksController::class, 'submit'])
        ->name('admin.marks.submit')
        ->middleware('permission:marks.enter');

    Route::post('/marks/{mark}/approve', [MarksController::class, 'approve'])
        ->name('admin.marks.approve')
        ->middleware('permission:marks.approve');

});
