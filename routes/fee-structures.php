<?php

use App\Http\Controllers\Admin\FeeStructureController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->middleware(['auth'])
    ->group(function () {

        Route::get(
            'fee-structures',
            [FeeStructureController::class, 'index']
        )->name('admin.fee-structures.index');

        Route::get(
            'fee-structures/create',
            [FeeStructureController::class, 'create']
        )->name('admin.fee-structures.create');

        Route::post(
            'fee-structures',
            [FeeStructureController::class, 'store']
        )->name('admin.fee-structures.store');

        Route::get(
            'fee-structures/{feeStructure}',
            [FeeStructureController::class, 'show']
        )->name('admin.fee-structures.show');

        Route::get(
            'fee-structures/{feeStructure}/edit',
            [FeeStructureController::class, 'edit']
        )->name('admin.fee-structures.edit');

        Route::put(
            'fee-structures/{feeStructure}',
            [FeeStructureController::class, 'update']
        )->name('admin.fee-structures.update');

        Route::patch(
            'fee-structures/{feeStructure}/toggle-status',
            [FeeStructureController::class, 'toggleStatus']
        )->name('admin.fee-structures.toggle-status');

        Route::delete(
            'fee-structures/{feeStructure}',
            [FeeStructureController::class, 'destroy']
        )->name('admin.fee-structures.destroy');
        Route::get(
    'fee-structures/academic-years/{academicYear}/terms',
    [FeeStructureController::class, 'termsByAcademicYear']
)->name('admin.fee-structures.terms');

Route::get(
    'fee-structures/academic-years/{academicYear}/classes',
    [FeeStructureController::class, 'classesByAcademicYear']
)->name('admin.fee-structures.classes');
    });