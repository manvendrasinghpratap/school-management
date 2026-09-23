<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PortalController;
use App\Http\Controllers\Api\ProfileController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->middleware('api.request')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'store']);
    Route::middleware('auth:api')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
    });
});

Route::middleware(['api.request', 'auth:api', 'permission:api.access'])->group(function () {
    Route::get('/portal/dashboard', [PortalController::class, 'dashboard']);
    Route::get('/portal/student', [PortalController::class, 'student'])->middleware('role:Student');
    Route::get('/portal/parent/children', [PortalController::class, 'parentChildren'])->middleware('role:Parent');
    Route::get('/portal/parent/children/{student}', [PortalController::class, 'parentChild'])->middleware('role:Parent');
    Route::get('/portal/teacher', [PortalController::class, 'teacher'])->middleware('role:Teacher');
    Route::get('/portal/attendance', [PortalController::class, 'attendance'])->middleware('role:Student');

    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::patch('/profile', [ProfileController::class, 'update']);
    Route::patch('/profile/password', [ProfileController::class, 'password']);

    Route::get('/notifications', [PortalController::class, 'notifications']);
    Route::patch('/notifications/{notification}/read', [PortalController::class, 'notificationRead']);
});
