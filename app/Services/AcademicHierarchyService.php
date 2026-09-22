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
    /**
     * Get the authenticated user's school ID.
     */
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

    /**
     * Get active academic years belonging to the current school.
     *
     * Current academic year appears first.
     */
    public function academicYears(): Collection
    {
        return AcademicYears::query()
            ->where('school_id', $this->schoolId())
            ->where('is_active', 1)
            ->whereNull('deleted_at')
            ->orderByDesc('is_current')
            ->orderByDesc('start_date')
            ->get();
    }

    /**
     * Get the current active academic year.
     */
    public function currentAcademicYear(): ?AcademicYears
    {
        return AcademicYears::query()
            ->where('school_id', $this->schoolId())
            ->where('is_active', 1)
            ->where('is_current', 1)
            ->whereNull('deleted_at')
            ->orderByDesc('start_date')
            ->first();
    }

    /**
     * Get active classes belonging to the current school.
     *
     * Academic year is validated first because the hierarchy begins
     * with Academic Year -> Class.
     *
     * Classes themselves are school-owned and are not duplicated
     * per academic year in the classes table.
     */
    public function classes(int $academicYearId): Collection
    {
        $schoolId = $this->schoolId();

        // Validate that the academic year belongs to this school.
        $this->academicYear($academicYearId);

        return Classes::query()
            ->where('school_id', $schoolId)
            ->where('is_active', 1)
            ->whereNull('deleted_at')
            ->orderBy('name')
            ->get([
                'id',
                'school_id',
                'name',
                'code',
            ]);
    }

    /**
     * Get active sections belonging to a school-owned class.
     *
     * IMPORTANT:
     * sections does NOT contain school_id in the live database.
     *
     * Therefore school ownership is established through:
     *
     * classes.school_id
     *       ↓
     * sections.class_id
     */
    public function sections(int $classId): Collection
    {
        $schoolId = $this->schoolId();

        $class = Classes::query()
            ->where('id', $classId)
            ->where('school_id', $schoolId)
            ->where('is_active', 1)
            ->whereNull('deleted_at')
            ->firstOrFail();

        return Section::query()
            ->where('class_id', $class->id)
            ->where('is_active', 1)
            ->whereNull('deleted_at')
            ->orderBy('name')
            ->get();
    }

    /**
     * Get active students for the exact academic hierarchy:
     *
     * Academic Year -> Class -> Section -> Student
     *
     * Student enrollment is the source of truth.
     */
    public function students(
        int $academicYearId,
        int $classId,
        int $sectionId
    ): Collection {
        $schoolId = $this->schoolId();

        /*
         * Validate Academic Year ownership.
         */
        $this->academicYear($academicYearId);

        /*
         * Validate Class ownership.
         */
        $class = Classes::query()
            ->where('id', $classId)
            ->where('school_id', $schoolId)
            ->where('is_active', 1)
            ->whereNull('deleted_at')
            ->firstOrFail();

        /*
         * Validate Section through its school-owned Class.
         *
         * Do NOT query sections.school_id because that column
         * does not exist in the live database.
         */
        Section::query()
            ->where('id', $sectionId)
            ->where('class_id', $class->id)
            ->where('is_active', 1)
            ->whereNull('deleted_at')
            ->firstOrFail();

        /*
         * Get only students who have an ACTIVE enrollment
         * matching the exact selected hierarchy.
         */
        return Student::query()
            ->where('students.school_id', $schoolId)
            ->where('students.status', 'active')
            ->whereNull('students.deleted_at')
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
     * Used by edit forms to restore:
     *
     * Academic Year -> Class -> Section -> Student
     */
    public function studentHierarchy(int $studentId): array
    {
        $schoolId = $this->schoolId();

        /*
         * First verify that the student belongs to this school
         * and is active.
         */
        Student::query()
            ->where('id', $studentId)
            ->where('school_id', $schoolId)
            ->where('status', 'active')
            ->whereNull('deleted_at')
            ->firstOrFail();

        /*
         * Find the student's most recent active enrollment.
         */
        $enrollment = StudentEnrollment::query()
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

        /*
         * Validate that the enrollment's class belongs to
         * the current school.
         */
        Classes::query()
            ->where('id', $enrollment->class_id)
            ->where('school_id', $schoolId)
            ->where('is_active', 1)
            ->whereNull('deleted_at')
            ->firstOrFail();

        /*
         * Validate that the enrollment's section belongs
         * to that class.
         */
        Section::query()
            ->where('id', $enrollment->section_id)
            ->where('class_id', $enrollment->class_id)
            ->where('is_active', 1)
            ->whereNull('deleted_at')
            ->firstOrFail();

        return [
            'academic_year_id' => (int) $enrollment->academic_year_id,
            'class_id' => (int) $enrollment->class_id,
            'section_id' => (int) $enrollment->section_id,
            'student_id' => $studentId,
        ];
    }

    /**
     * Validate the complete hierarchy.
     *
     * Academic Year
     *      ↓
     * Class
     *      ↓
     * Section
     *      ↓
     * Student
     *
     * Student enrollment is checked as the final source of truth.
     */
    public function validateStudentHierarchy(
        int $academicYearId,
        int $classId,
        int $sectionId,
        int $studentId
    ): Student {
        $schoolId = $this->schoolId();

        /*
         * 1. Validate Academic Year.
         */
        $this->academicYear($academicYearId);

        /*
         * 2. Validate Class belongs to the current school.
         */
        $class = Classes::query()
            ->where('id', $classId)
            ->where('school_id', $schoolId)
            ->where('is_active', 1)
            ->whereNull('deleted_at')
            ->firstOrFail();

        /*
         * 3. Validate Section belongs to the selected Class.
         *
         * IMPORTANT:
         * No sections.school_id condition.
         */
        Section::query()
            ->where('id', $sectionId)
            ->where('class_id', $class->id)
            ->where('is_active', 1)
            ->whereNull('deleted_at')
            ->firstOrFail();

        /*
         * 4. Validate Student belongs to the current school.
         */
        $student = Student::query()
            ->where('id', $studentId)
            ->where('school_id', $schoolId)
            ->where('status', 'active')
            ->whereNull('deleted_at')
            ->firstOrFail();

        /*
         * 5. Validate exact active enrollment.
         *
         * This prevents combinations such as:
         *
         * Student Daniel
         * Academic Year 2026/2027
         * Class JSS 1
         * Section B
         *
         * when Daniel is actually enrolled in JSS 1 / Section A.
         */
        $enrolled = StudentEnrollment::query()
            ->where('school_id', $schoolId)
            ->where('student_id', $studentId)
            ->where('academic_year_id', $academicYearId)
            ->where('class_id', $classId)
            ->where('section_id', $sectionId)
            ->where('status', 'active')
            ->whereNull('deleted_at')
            ->exists();

        if (! $enrolled) {
            throw ValidationException::withMessages([
                'student_id' => [
                    'The selected student is not actively enrolled in the selected academic year, class and section.',
                ],
            ]);
        }

        return $student;
    }

    /**
     * Get one school-owned active academic year.
     */
    public function academicYear(int $academicYearId): AcademicYears
    {
        return AcademicYears::query()
            ->where('id', $academicYearId)
            ->where('school_id', $this->schoolId())
            ->where('is_active', 1)
            ->whereNull('deleted_at')
            ->firstOrFail();
    }
}