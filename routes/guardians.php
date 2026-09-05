<?php

use App\Http\Controllers\Admin\GuardianController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::resource(
            'guardians',
            GuardianController::class
        );
    });