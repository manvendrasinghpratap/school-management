<?php

namespace App\Services;

use App\Models\AcademicYears;
use App\Models\Classes;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentEnrollment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AcademicHierarchyService
{
    public function schoolId(): int
    {
        $schoolId = Auth::user()?->school_id;

        abort_unless(
            $schoolId,
            403,
            'No school is assigned to the current user.'
        );

        return (int) $schoolId;
    }

    public function academicYears(): Collection
    {
        return AcademicYears::query()
            ->where('school_id', $this->schoolId())
            ->where('is_active', 1)
            ->orderByDesc('is_current')
            ->orderByDesc('start_date')
            ->get();
    }

    public function currentAcademicYear(): ?AcademicYears
    {
        return AcademicYears::query()
            ->where('school_id', $this->schoolId())
            ->where('is_active', 1)
            ->where('is_current', 1)
            ->orderByDesc('start_date')
            ->first();
    }

    public function classes(int $academicYearId): Collection
    {
        $schoolId = $this->schoolId();

        $this->academicYear($academicYearId);

        return Classes::query()
            ->where('school_id', $schoolId)
            ->where('is_active', 1)
            ->orderBy('name')
            ->get([
                'id',
                'school_id',
                'name',
                'code',
            ]);
    }

    public function sections(int $classId): Collection
    {
        $schoolId = $this->schoolId();

        Classes::query()
            ->where('id', $classId)
            ->where('school_id', $schoolId)
            ->where('is_active', 1)
            ->firstOrFail();

        return Section::query()
            ->where('class_id', $classId)
            ->where('is_active', 1)
            ->orderBy('name')
            ->get([
                'id',
                'class_id',
                'name',
                'code',
            ]);
    }

    public function students(
        int $academicYearId,
        int $classId,
        int $sectionId
    ): Collection {
        $schoolId = $this->schoolId();

        $this->academicYear($academicYearId);

        Classes::query()
            ->where('id', $classId)
            ->where('school_id', $schoolId)
            ->where('is_active', 1)
            ->firstOrFail();

        Section::query()
            ->where('id', $sectionId)
            ->where('class_id', $classId)
            ->where('is_active', 1)
            ->firstOrFail();

        return Student::query()
            ->where('students.school_id', $schoolId)
            ->where('students.status', 'active')
            ->whereExists(function ($query) use (
                $schoolId,
                $academicYearId,
                $classId,
                $sectionId
            ) {
                $query->selectRaw('1')
                    ->from('student_enrollments')
                    ->whereColumn(
                        'student_enrollments.student_id',
                        'students.id'
                    )
                    ->where('student_enrollments.school_id', $schoolId)
                    ->where('student_enrollments.academic_year_id', $academicYearId)
                    ->where('student_enrollments.class_id', $classId)
                    ->where('student_enrollments.section_id', $sectionId)
                    ->where('student_enrollments.status', 'active')
                    ->whereNull('student_enrollments.deleted_at');
            })
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get([
                'students.id',
                'students.school_id',
                'students.student_number',
                'students.first_name',
                'students.middle_name',
                'students.last_name',
            ]);
    }

    /**
     * Resolve the most recent active enrollment for a student.
     *
     * Used by edit forms to restore the Academic Year -> Class -> Section
     * selection without storing hierarchy fields on route_students.
     */
    public function studentHierarchy(int $studentId): array
    {
        $schoolId = $this->schoolId();

        Student::query()
            ->where('id', $studentId)
            ->where('school_id', $schoolId)
            ->where('status', 'active')
            ->firstOrFail();

        $enrollment = StudentEnrollment::query()
            ->with('academicYear')
            ->where('school_id', $schoolId)
            ->where('student_id', $studentId)
            ->where('status', 'active')
            ->whereNull('deleted_at')
            ->orderByDesc('academic_year_id')
            ->orderByDesc('id')
            ->first();

        if (! $enrollment) {
            return [
                'academic_year_id' => null,
                'class_id' => null,
                'section_id' => null,
                'student_id' => $studentId,
            ];
        }

        return [
            'academic_year_id' => $enrollment->academic_year_id,
            'class_id' => $enrollment->class_id,
            'section_id' => $enrollment->section_id,
            'student_id' => $studentId,
        ];
    }

    public function validateStudentHierarchy(
        int $academicYearId,
        int $classId,
        int $sectionId,
        int $studentId
    ): Student {
        $schoolId = $this->schoolId();

        $this->academicYear($academicYearId);

        Classes::query()
            ->where('id', $classId)
            ->where('school_id', $schoolId)
            ->where('is_active', 1)
            ->firstOrFail();

        Section::query()
            ->where('id', $sectionId)
            ->where('class_id', $classId)
            ->where('is_active', 1)
            ->firstOrFail();

        $student = Student::query()
            ->where('id', $studentId)
            ->where('school_id', $schoolId)
            ->where('status', 'active')
            ->firstOrFail();

        $enrolled = StudentEnrollment::query()
            ->where('school_id', $schoolId)
            ->where('student_id', $studentId)
            ->where('academic_year_id', $academicYearId)
            ->where('class_id', $classId)
            ->where('section_id', $sectionId)
            ->where('status', 'active')
            ->exists();

        if (! $enrolled) {
            throw ValidationException::withMessages([
                'student_id' => [
                    'The selected student is not actively enrolled in the selected academic year, class and section.'
                ],
            ]);
        }

        return $student;
    }

    public function academicYear(int $academicYearId): AcademicYears
    {
        return AcademicYears::query()
            ->where('id', $academicYearId)
            ->where('school_id', $this->schoolId())
            ->where('is_active', 1)
            ->firstOrFail();
    }
}
