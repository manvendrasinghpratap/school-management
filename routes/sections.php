<?php

use App\Http\Controllers\Admin\SectionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('sections', SectionController::class);
    });