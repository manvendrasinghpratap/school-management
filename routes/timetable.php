<?php

use App\Http\Controllers\Admin\TimetableController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('timetables', [TimetableController::class,'index'])->name('timetables.index')->middleware('permission:timetable.view');
    Route::get('timetables/create', [TimetableController::class,'create'])->name('timetables.create')->middleware('permission:timetable.manage');
    Route::post('timetables', [TimetableController::class,'store'])->name('timetables.store')->middleware('permission:timetable.manage');
    Route::get('timetables/{timetable}', [TimetableController::class,'show'])->name('timetables.show')->middleware('permission:timetable.view');
    Route::get('timetables/{timetable}/edit', [TimetableController::class,'edit'])->name('timetables.edit')->middleware('permission:timetable.manage');
    Route::put('timetables/{timetable}', [TimetableController::class,'update'])->name('timetables.update')->middleware('permission:timetable.manage');
    Route::delete('timetables/{timetable}', [TimetableController::class,'destroy'])->name('timetables.destroy')->middleware('permission:timetable.manage');
    Route::put('timetables/{timetable}/restore', [TimetableController::class,'restore'])->name('timetables.restore')->middleware('permission:timetable.manage');
});