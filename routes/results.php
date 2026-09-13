<?php

use App\Http\Controllers\Admin\ResultsController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware('auth')->group(function () {

    Route::get('/results', [ResultsController::class, 'index'])
        ->name('admin.results.index')
        ->middleware('permission:results.view');

    Route::get('/results/{result}', [ResultsController::class, 'show'])
        ->name('admin.results.show')
        ->middleware('permission:results.view');

    Route::post('/results/{result}/approve', [ResultsController::class, 'approve'])
        ->name('admin.results.approve')
        ->middleware('permission:results.approve');

    Route::post('/results/{result}/publish', [ResultsController::class, 'publish'])
        ->name('admin.results.publish')
        ->middleware('permission:results.publish');

});