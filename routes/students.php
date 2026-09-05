<?php
use App\Http\Controllers\Admin\StudentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('students', StudentController::class);
});
// Route::prefix('admin/students')
//     ->name('admin.students.')
//     ->group(function () {

//         Route::get('/', function () {
//             return 'Student route is working!';
//         })->name('index');

//     });