<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UserPasswordController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Users
        |--------------------------------------------------------------------------
        */

        Route::resource('users', UserController::class)
            ->middleware('permission:users.view');


        /*
        |--------------------------------------------------------------------------
        | Activate User
        |--------------------------------------------------------------------------
        */

        Route::put(
            'users/{user}/activate',
            [UserController::class, 'activate']
        )
            ->name('users.activate')
            ->middleware('permission:users.update');


        /*
        |--------------------------------------------------------------------------
        | Deactivate User
        |--------------------------------------------------------------------------
        */

        Route::put(
            'users/{user}/deactivate',
            [UserController::class, 'deactivate']
        )
            ->name('users.deactivate')
            ->middleware('permission:users.update');


        /*
        |--------------------------------------------------------------------------
        | Restore User
        |--------------------------------------------------------------------------
        */

        Route::put(
            'users/{user}/restore',
            [UserController::class, 'restore']
        )
            ->name('users.restore')
            ->middleware('permission:users.update');


        /*
        |--------------------------------------------------------------------------
        | Change User Password
        |--------------------------------------------------------------------------
        */

        Route::put(
            'users/{user}/password',
            [UserPasswordController::class, 'update']
        )
            ->name('users.password.update')
            ->middleware('permission:users.update');

    });