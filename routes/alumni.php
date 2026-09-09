<?php

use App\Http\Controllers\Admin\AlumniController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('alumni', [AlumniController::class,'index'])->name('alumni.index')->middleware('permission:alumni.view');
        Route::get('alumni/create', [AlumniController::class,'create'])->name('alumni.create')->middleware('permission:alumni.manage');
        Route::post('alumni', [AlumniController::class,'store'])->name('alumni.store')->middleware('permission:alumni.manage');
        Route::get('alumni/{alumni}', [AlumniController::class,'show'])->name('alumni.show')->middleware('permission:alumni.view');
        Route::get('alumni/{alumni}/edit', [AlumniController::class,'edit'])->name('alumni.edit')->middleware('permission:alumni.manage');
        Route::put('alumni/{alumni}', [AlumniController::class,'update'])->name('alumni.update')->middleware('permission:alumni.manage');
        Route::delete('alumni/{alumni}', [AlumniController::class,'destroy'])->name('alumni.destroy')->middleware('permission:alumni.manage');
    });