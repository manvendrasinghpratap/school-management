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
        | User Management
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | Specific routes such as users/create must be declared BEFORE
        | the users/{user} wildcard route.
        |
        */


        /*
        |--------------------------------------------------------------------------
        | Users - List
        |--------------------------------------------------------------------------
        */

        Route::get('users', [UserController::class, 'index'])
            ->name('users.index')
            ->middleware('permission:users.view');


        /*
        |--------------------------------------------------------------------------
        | Users - Create
        |--------------------------------------------------------------------------
        |
        | These routes MUST come before users/{user}.
        |
        */

        Route::get('users/create', [UserController::class, 'create'])
            ->name('users.create')
            ->middleware('permission:users.create');

        Route::post('users', [UserController::class, 'store'])
            ->name('users.store')
            ->middleware('permission:users.create');


        /*
        |--------------------------------------------------------------------------
        | Users - View
        |--------------------------------------------------------------------------
        */

        Route::get('users/{user}', [UserController::class, 'show'])
            ->name('users.show')
            ->middleware('permission:users.view');


        /*
        |--------------------------------------------------------------------------
        | Users - Edit
        |--------------------------------------------------------------------------
        */

        Route::get('users/{user}/edit', [UserController::class, 'edit'])
            ->name('users.edit')
            ->middleware('permission:users.update');


        /*
        |--------------------------------------------------------------------------
        | Users - Update
        |--------------------------------------------------------------------------
        */

        Route::put('users/{user}', [UserController::class, 'update'])
            ->name('users.update')
            ->middleware('permission:users.update');


        /*
        |--------------------------------------------------------------------------
        | Users - Delete
        |--------------------------------------------------------------------------
        */

        Route::delete('users/{user}', [UserController::class, 'destroy'])
            ->name('users.destroy')
            ->middleware('permission:users.delete');


        /*
        |--------------------------------------------------------------------------
        | Users - Activate
        |--------------------------------------------------------------------------
        */

        Route::put('users/{user}/activate', [UserController::class, 'activate'])
            ->name('users.activate')
            ->middleware('permission:users.update');


        /*
        |--------------------------------------------------------------------------
        | Users - Deactivate
        |--------------------------------------------------------------------------
        */

        Route::put('users/{user}/deactivate', [UserController::class, 'deactivate'])
            ->name('users.deactivate')
            ->middleware('permission:users.update');


        /*
        |--------------------------------------------------------------------------
        | Users - Restore
        |--------------------------------------------------------------------------
        */

        Route::put('users/{user}/restore', [UserController::class, 'restore'])
            ->name('users.restore')
            ->middleware('permission:users.update');


        /*
        |--------------------------------------------------------------------------
        | Users - Password
        |--------------------------------------------------------------------------
        */

        Route::put('users/{user}/password', [UserPasswordController::class, 'update'])
            ->name('users.password.update')
            ->middleware('permission:users.update');

    });