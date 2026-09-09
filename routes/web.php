<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () { return view('frontend'); });
Route::get('/backend', function () { return view('backend'); }); 

Route::get('/admin/dashboard', function () {
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
    require base_path('routes/classes.php');
    require base_path('routes/sections.php');
    require base_path('routes/departments.php');
    require base_path('routes/courses.php');
    require base_path('routes/staff.php');
    require base_path('routes/instructors.php');
    require base_path('routes/student-promotions.php');
    require base_path('routes/student-enrollments.php');
    require base_path('routes/academic-years.php');
    require base_path('routes/terms.php');
    require base_path('routes/dashboard.php');
    require base_path('routes/graduations.php');
    require base_path('routes/alumni.php');
    require base_path('routes/timetable.php');
    require base_path('routes/attendance.php');
    require base_path('routes/leaves.php');
    require base_path('routes/staff-attendance.php');
    require base_path('routes/examinations.php');
    require base_path('routes/exam-schedules.php');
    require base_path('routes/marks.php'); 
    require base_path('routes/student-courses.php');

});

require __DIR__.'/auth.php';