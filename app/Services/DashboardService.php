<?php

namespace App\Services;

use App\Models\AcademicYears;
use App\Models\Courses;
use App\Models\Department;
use App\Models\Instructor;
use App\Models\Section;
use App\Models\Staff;
use App\Models\Student;
use App\Models\Terms;
use App\Models\Classes;
use Illuminate\Support\Facades\Auth;

class DashboardService
{
    public function getDashboardData(): array
    {
        $schoolId = $this->schoolId();

        $currentAcademicYear = AcademicYears::query()
            ->where('school_id', $schoolId)
            ->where('is_current', true)
            ->where('is_active', true)
            ->first();

        $currentTerm = null;

        if ($currentAcademicYear) {
            $currentTerm = Terms::query()
                ->where('school_id', $schoolId)
                ->where('academic_year_id', $currentAcademicYear->id)
                ->where('is_current', true)
                ->where('is_active', true)
                ->first();
        }

        return [
            'statistics' => [
                'students' => Student::query()
                    ->where('school_id', $schoolId)
                    ->count(),

                'active_students' => Student::query()
                    ->where('school_id', $schoolId)
                    ->where('status', 'active')
                    ->count(),

                'staff' => Staff::query()
                    ->where('school_id', $schoolId)
                    ->count(),

                'active_staff' => Staff::query()
                    ->where('school_id', $schoolId)
                    ->where('status', 'active')
                    ->count(),

                'instructors' => Instructor::query()
                    ->whereHas('staff', function ($query) use ($schoolId) {
                        $query->where('school_id', $schoolId);
                    })
                    ->count(),

                'classes' => Classes::query()
                    ->where('school_id', $schoolId)
                    ->count(),

                'sections' => Section::query()
                    ->whereHas('class', function ($query) use ($schoolId) {
                        $query->where('school_id', $schoolId);
                    })
                    ->count(),

                'departments' => Department::query()
                    ->where('school_id', $schoolId)
                    ->count(),

                'courses' => Courses::query()
                    ->where('school_id', $schoolId)
                    ->count(),

                'academic_years' => AcademicYears::query()
                    ->where('school_id', $schoolId)
                    ->count(),

                'terms' => Terms::query()
                    ->where('school_id', $schoolId)
                    ->count(),
            ],

            'currentAcademicYear' => $currentAcademicYear,

            'currentTerm' => $currentTerm,

            'recentStudents' => Student::query()
                ->where('school_id', $schoolId)
                ->latest()
                ->take(5)
                ->get(),

            'recentStaff' => Staff::query()
                ->where('school_id', $schoolId)
                ->latest()
                ->take(5)
                ->get(),
        ];
    }

    protected function schoolId(): int
    {
        $schoolId = Auth::user()?->school_id;

        if (!$schoolId) {
            abort(403, 'No school is assigned to this user.');
        }

        return (int) $schoolId;
    }
}