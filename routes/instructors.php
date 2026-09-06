<?php

use App\Http\Controllers\Admin\InstructorController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('instructors', InstructorController::class);
    });