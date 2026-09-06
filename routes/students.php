<?php

use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\StudentDocumentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Student Management
        Route::get('students', [StudentController::class, 'index'])
            ->name('students.index')
            ->middleware('permission:students.view');

        Route::get('students/create', [StudentController::class, 'create'])
            ->name('students.create')
            ->middleware('permission:students.create');

        Route::post('students', [StudentController::class, 'store'])
            ->name('students.store')
            ->middleware('permission:students.create');

        Route::get('students/{student}', [StudentController::class, 'show'])
            ->name('students.show')
            ->middleware('permission:students.view');

        Route::get('students/{student}/edit', [StudentController::class, 'edit'])
            ->name('students.edit')
            ->middleware('permission:students.update');

        Route::put('students/{student}', [StudentController::class, 'update'])
            ->name('students.update')
            ->middleware('permission:students.update');

        Route::delete('students/{student}', [StudentController::class, 'destroy'])
            ->name('students.destroy')
            ->middleware('permission:students.delete');


        // Student Documents
        Route::get(
            'students/{student}/documents',
            [StudentDocumentController::class, 'index']
        )
            ->name('students.documents.index')
            ->middleware('permission:students.documents.view');

        Route::post(
            'students/{student}/documents',
            [StudentDocumentController::class, 'store']
        )
            ->name('students.documents.store')
            ->middleware('permission:students.documents.manage');

        Route::get(
            'students/{student}/documents/{document}/download',
            [StudentDocumentController::class, 'download']
        )
            ->name('students.documents.download')
            ->middleware('permission:students.documents.view');

        Route::delete(
            'students/{student}/documents/{document}',
            [StudentDocumentController::class, 'destroy']
        )
            ->name('students.documents.destroy')
            ->middleware('permission:students.documents.manage');
    });