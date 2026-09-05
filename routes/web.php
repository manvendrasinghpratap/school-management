<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () { return view('frontend'); });
Route::get('/backend', function () { return view('backend'); }); 

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Modular Feature Routes
    require base_path('routes/school-settings.php');
    // require base_path('routes/schools.php');
    require base_path('routes/rbac.php');
    require base_path('routes/user-management.php');
    require base_path('routes/students.php');
    require base_path('routes/guardians.php');
});

require __DIR__.'/auth.php';