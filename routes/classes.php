<?php

use App\Http\Controllers\Admin\ClassController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::resource('classes', ClassController::class);

    });