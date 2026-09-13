<?php

use App\Http\Controllers\Admin\TranscriptController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Transcript Listing
    |--------------------------------------------------------------------------
    */

    Route::get('/transcripts', [TranscriptController::class, 'index'])
        ->name('admin.transcripts.index')
        ->middleware('permission:transcripts.view');


    /*
    |--------------------------------------------------------------------------
    | Generate Transcript
    |--------------------------------------------------------------------------
    */

    Route::post('/transcripts/generate', [TranscriptController::class, 'generate'])
        ->name('admin.transcripts.generate')
        ->middleware('permission:transcripts.generate');


    /*
    |--------------------------------------------------------------------------
    | View Transcript
    |--------------------------------------------------------------------------
    */

    Route::get('/transcripts/{transcript}', [TranscriptController::class, 'show'])
        ->name('admin.transcripts.show')
        ->middleware('permission:transcripts.view');


    /*
    |--------------------------------------------------------------------------
    | View Transcript PDF
    |--------------------------------------------------------------------------
    */

    Route::get('/transcripts/{transcript}/pdf', [TranscriptController::class, 'pdf'])
        ->name('admin.transcripts.pdf')
        ->middleware('permission:transcripts.view');


    /*
    |--------------------------------------------------------------------------
    | Delete Transcript
    |--------------------------------------------------------------------------
    */

    Route::delete('/transcripts/{transcript}', [TranscriptController::class, 'destroy'])
        ->name('admin.transcripts.destroy')
        ->middleware('permission:transcripts.generate');

});