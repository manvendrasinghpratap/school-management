<?php

use App\Http\Controllers\Admin\InstructorController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('instructors', [InstructorController::class, 'index'])
            ->name('instructors.index')
            ->middleware('permission:instructors.view');

        Route::get('instructors/create', [InstructorController::class, 'create'])
            ->name('instructors.create')
            ->middleware('permission:instructors.create');

        Route::post('instructors', [InstructorController::class, 'store'])
            ->name('instructors.store')
            ->middleware('permission:instructors.create');

        Route::get('instructors/{instructor}', [InstructorController::class, 'show'])
            ->name('instructors.show')
            ->middleware('permission:instructors.view');

        Route::get('instructors/{instructor}/edit', [InstructorController::class, 'edit'])
            ->name('instructors.edit')
            ->middleware('permission:instructors.update');

        Route::put('instructors/{instructor}', [InstructorController::class, 'update'])
            ->name('instructors.update')
            ->middleware('permission:instructors.update');

        Route::delete('instructors/{instructor}', [InstructorController::class, 'destroy'])
            ->name('instructors.destroy')
            ->middleware('permission:instructors.delete');
    });