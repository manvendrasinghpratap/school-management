<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UserPasswordController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserController::class)
        ->middleware('permission:users.view');

    Route::put('users/{user}/activate', [UserController::class, 'activate'])
        ->name('users.activate')
        ->middleware('permission:users.update');

    Route::put('users/{user}/deactivate', [UserController::class, 'deactivate'])
        ->name('users.deactivate')
        ->middleware('permission:users.update');

    Route::put('users/{user}/password', [UserPasswordController::class, 'update'])
        ->name('users.password.update')
        ->middleware('permission:users.update');
});
