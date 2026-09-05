<?php
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\StudentDocumentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('students', StudentController::class);
    Route::get('/students/{student}/documents', [StudentDocumentController::class, 'index'])->name('students.documents.index');
    Route::post('/students/{student}/documents', [StudentDocumentController::class, 'store'])->name('students.documents.store');
    Route::get('/students/{student}/documents/{document}/download', [StudentDocumentController::class, 'download'])->name('students.documents.download');
    Route::delete('/students/{student}/documents/{document}', [StudentDocumentController::class, 'destroy'])->name('students.documents.destroy');
});
// Route::prefix('admin/students')
//     ->name('admin.students.')
//     ->group(function () {

//         Route::get('/', function () {
//             return 'Student route is working!';
//         })->name('index');

//     });