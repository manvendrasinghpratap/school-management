<?php

use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserPermissionController;
use App\Http\Controllers\Admin\UserRoleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Roles
    |--------------------------------------------------------------------------
    */

    Route::resource('roles', RoleController::class)
        ->middleware('permission:roles.view');


    /*
    |--------------------------------------------------------------------------
    | Role Permissions
    |--------------------------------------------------------------------------
    */

    Route::get('roles/{role}/permissions', [RoleController::class, 'permissions'])
        ->name('roles.permissions.edit')
        ->middleware('permission:roles.update');

    Route::put('roles/{role}/permissions', [RoleController::class, 'updatePermissions'])
        ->name('roles.permissions.update')
        ->middleware('permission:roles.update');


    /*
    |--------------------------------------------------------------------------
    | Permissions
    |--------------------------------------------------------------------------
    */

    Route::resource('permissions', PermissionController::class)
        ->middleware('permission:permissions.view');


    /*
    |--------------------------------------------------------------------------
    | User Roles
    |--------------------------------------------------------------------------
    */

    Route::get('users/{user}/roles', [UserRoleController::class, 'edit'])
        ->name('users.roles.edit')
        ->middleware('permission:users.assign-roles');

    Route::put('users/{user}/roles', [UserRoleController::class, 'update'])
        ->name('users.roles.update')
        ->middleware('permission:users.assign-roles');


    /*
    |--------------------------------------------------------------------------
    | User Direct Permissions
    |--------------------------------------------------------------------------
    */

    Route::get('users/{user}/permissions', [UserPermissionController::class, 'edit'])
        ->name('users.permissions.edit')
        ->middleware('permission:users.assign-permissions');

    Route::put('users/{user}/permissions', [UserPermissionController::class, 'update'])
        ->name('users.permissions.update')
        ->middleware('permission:users.assign-permissions');

});