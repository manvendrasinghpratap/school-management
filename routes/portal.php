<?php

use App\Http\Controllers\Portal\NotificationController;
use App\Http\Controllers\Portal\ParentPortalController;
use App\Http\Controllers\Portal\ProfileController;
use App\Http\Controllers\Portal\StudentPortalController;
use App\Http\Controllers\Portal\TeacherPortalController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('portal')->name('portal.')->group(function () {
    Route::get('/', function () {
        $user = request()->user();
        if ($user->hasRole('Student')) return redirect()->route('portal.student.dashboard');
        if ($user->hasRole('Parent')) return redirect()->route('portal.parent.dashboard');
        if ($user->hasRole('Teacher')) return redirect()->route('portal.teacher.dashboard');
        abort(403, 'No portal role is configured for this account.');
    })->name('index');

    Route::middleware('portal.role:Student')->prefix('student')->name('student.')->group(function () {
        Route::get('/dashboard', [StudentPortalController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [StudentPortalController::class, 'profile'])->name('profile');
        Route::get('/attendance', [StudentPortalController::class, 'attendance'])->name('attendance');
        Route::get('/timetable', [StudentPortalController::class, 'timetable'])->name('timetable');
        Route::get('/courses', [StudentPortalController::class, 'courses'])->name('courses');
        Route::get('/results', [StudentPortalController::class, 'results'])->name('results');
        Route::get('/fees', [StudentPortalController::class, 'fees'])->name('fees');
        Route::get('/documents', [StudentPortalController::class, 'documents'])->name('documents');
    });

    Route::middleware('portal.role:Parent')->prefix('parent')->name('parent.')->group(function () {
        Route::get('/dashboard', [ParentPortalController::class, 'dashboard'])->name('dashboard');
        Route::get('/children', [ParentPortalController::class, 'children'])->name('children');
        Route::get('/children/{student}', [ParentPortalController::class, 'child'])->name('child');
        Route::get('/children/{student}/attendance', [ParentPortalController::class, 'attendance'])->name('attendance');
        Route::get('/children/{student}/results', [ParentPortalController::class, 'results'])->name('results');
        Route::get('/children/{student}/fees', [ParentPortalController::class, 'fees'])->name('fees');
    });

    Route::middleware('portal.role:Teacher')->prefix('teacher')->name('teacher.')->group(function () {
        Route::get('/dashboard', [TeacherPortalController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [TeacherPortalController::class, 'profile'])->name('profile');
        Route::get('/courses', [TeacherPortalController::class, 'courses'])->name('courses');
        Route::get('/students', [TeacherPortalController::class, 'students'])->name('students');
        Route::get('/attendance', [TeacherPortalController::class, 'attendance'])->name('attendance');
        Route::get('/marks', [TeacherPortalController::class, 'marks'])->name('marks');
        Route::get('/timetable', [TeacherPortalController::class, 'timetable'])->name('timetable');
    });

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'read'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.read-all');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'password'])->name('profile.password');
});
