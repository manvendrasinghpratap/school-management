<?php

use App\Http\Controllers\Admin\ReportCardController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware('auth')->group(function () {

    Route::get('/report-cards', [ReportCardController::class, 'index'])
        ->name('admin.report-cards.index')
        ->middleware('permission:report-cards.view');

    Route::get('/report-cards/{reportCard}', [ReportCardController::class, 'show'])
        ->name('admin.report-cards.show')
        ->middleware('permission:report-cards.view');

    Route::post('/report-cards/generate/{result}', [ReportCardController::class, 'generate'])
        ->name('admin.report-cards.generate')
        ->middleware('permission:report-cards.generate');

    Route::post('/report-cards/{reportCard}/publish', [ReportCardController::class, 'publish'])
        ->name('admin.report-cards.publish')
        ->middleware('permission:report-cards.generate');

    Route::get('/report-cards/{reportCard}/pdf', [ReportCardController::class, 'pdf'])
        ->name('admin.report-cards.pdf')
        ->middleware('permission:report-cards.view');
});