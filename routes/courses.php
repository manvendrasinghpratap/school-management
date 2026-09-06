<?php

use App\Http\Controllers\Admin\CourseController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('courses', CourseController::class);
    });