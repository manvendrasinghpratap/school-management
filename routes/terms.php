<?php

use App\Http\Controllers\Admin\TermController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Terms / Semesters
        |--------------------------------------------------------------------------
        */

        Route::patch(
            'terms/{term}/set-current',
            [TermController::class, 'setCurrent']
        )->name('terms.set-current');

        Route::resource(
            'terms',
            TermController::class
        )->parameters([
            'terms' => 'term',
        ]);
    });