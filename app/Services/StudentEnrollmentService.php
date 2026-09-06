<?php

namespace App\Services;

use App\Models\AcademicYears;
use App\Models\Classes;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentEnrollment;
use App\Models\Terms;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StudentEnrollmentService
{
    /**
     * Create a student enrollment.
     */
    public function create(array $data): StudentEnrollment
    {
        return DB::transaction(function () use ($data) {

            $schoolId = $this->schoolId();

            $student = $this->getStudent(
                $data['student_id'],
                $schoolId
            );

            $academicYear = $this->getAcademicYear(
                $data['academic_year_id'],
                $schoolId
            );

            $term = $this->getTerm(
                $data['term_id'] ?? null,
                $academicYear->id,
                $schoolId
            );

            $class = $this->getClass(
                $data['class_id'],
                $schoolId
            );

            $section = $this->getSection(
                $data['section_id'] ?? null,
                $class->id
            );

            $this->validateDuplicateEnrollment(
                $student->id,
                $academicYear->id,
                $data['term_id'] ?? null
            );

            $enrollmentNumber = $this->generateEnrollmentNumber(
                $schoolId
            );

            return StudentEnrollment::create([
                'school_id'         => $schoolId,
                'student_id'        => $student->id,
                'academic_year_id'  => $academicYear->id,
                'term_id'           => $term?->id,
                'class_id'          => $class->id,
                'section_id'        => $section?->id,
                'enrollment_number' => $enrollmentNumber,
                'enrollment_date'   => $data['enrollment_date'],
                'status'            => $data['status'] ?? 'active',
                'notes'             => $data['notes'] ?? null,
                'created_by'        => Auth::id(),
                'updated_by'        => Auth::id(),
            ]);
        });
    }

    /**
     * Update an enrollment.
     */
    public function update(
        StudentEnrollment $enrollment,
        array $data
    ): StudentEnrollment {
        return DB::transaction(function () use ($enrollment, $data) {

            $schoolId = $this->schoolId();

            $this->ensureSameSchool($enrollment);

            $academicYear = $this->getAcademicYear(
                $data['academic_year_id'],
                $schoolId
            );

           $term = $this->getTerm(
    $data['term_id'] ?? null,
    $academicYear->id,
    $schoolId
);

            $class = $this->getClass(
                $data['class_id'],
                $schoolId
            );

            $section = $this->getSection(
                $data['section_id'] ?? null,
                $class->id
            );

            $this->validateDuplicateEnrollment(
                $enrollment->student_id,
                $academicYear->id,
                $data['term_id'] ?? null,
                $enrollment->id
            );

            $enrollment->update([
                'academic_year_id' => $academicYear->id,
                'term_id'          => $term?->id,
                'class_id'         => $class->id,
                'section_id'       => $section?->id,
                'enrollment_date'  => $data['enrollment_date'],
                'status'           => $data['status'],
                'notes'            => $data['notes'] ?? null,
                'updated_by'       => Auth::id(),
            ]);

            return $enrollment->fresh([
                'student',
                'academicYear',
                'term',
                'class',
                'section',
            ]);
        });
    }

    /**
     * Soft delete an enrollment.
     *
     * This does not delete the student.
     */
    public function delete(StudentEnrollment $enrollment): void
    {
        DB::transaction(function () use ($enrollment) {

            $this->ensureSameSchool($enrollment);

            $enrollment->update([
                'updated_by' => Auth::id(),
            ]);

            $enrollment->delete();
        });
    }

    /**
     * Restore a soft-deleted enrollment.
     */
    public function restore(
        StudentEnrollment $enrollment
    ): StudentEnrollment {
        $this->ensureSameSchool($enrollment);

        $enrollment->restore();

        return $enrollment->fresh([
            'student',
            'academicYear',
            'term',
            'class',
            'section',
        ]);
    }

    /**
     * Get a student belonging to the current school.
     */
    protected function getStudent(
        int $studentId,
        int $schoolId
    ): Student {
        $student = Student::query()
            ->where('id', $studentId)
            ->where('school_id', $schoolId)
            ->first();

        if (!$student) {
            throw ValidationException::withMessages([
                'student_id' =>
                    'The selected student does not belong to this school.',
            ]);
        }

        return $student;
    }

    /**
     * Get an academic year belonging to the current school.
     */
    protected function getAcademicYear(
        int $academicYearId,
        int $schoolId
    ): AcademicYears {
        $academicYear = AcademicYears::query()
            ->where('id', $academicYearId)
            ->where('school_id', $schoolId)
            ->first();

        if (!$academicYear) {
            throw ValidationException::withMessages([
                'academic_year_id' =>
                    'The selected academic year does not belong to this school.',
            ]);
        }

        return $academicYear;
    }

    /**
     * Get a term belonging to the selected academic year.
     *
     * Terms do not have school_id.
     * Their school is determined through academic_year_id.
     */
    protected function getTerm( ?int $termId, int $academicYearId, int $schoolId): ?Terms 
    {
        if (!$termId) {
            return null;
        }

        $term = Terms::query()
            ->where('id', $termId)
            ->where('school_id', $schoolId)
            ->where('academic_year_id', $academicYearId)
            ->first();

        if (!$term) {
                    throw ValidationException::withMessages([
                        'term_id' =>
                            'The selected term does not belong to the selected academic year and school.',
                    ]);
                }

                return $term;
    }

    /**
     * Get a class belonging to the current school.
     */
    protected function getClass(
        int $classId,
        int $schoolId
    ): Classes {
        $class = Classes::query()
            ->where('id', $classId)
            ->where('school_id', $schoolId)
            ->first();

        if (!$class) {
            throw ValidationException::withMessages([
                'class_id' =>
                    'The selected class does not belong to this school.',
            ]);
        }

        return $class;
    }

    /**
     * Get a section belonging to the selected class.
     *
     * Sections do not have school_id.
     * Their school is determined through class_id.
     */
    protected function getSection(
        ?int $sectionId,
        int $classId
    ): ?Section {
        if (!$sectionId) {
            return null;
        }

        $section = Section::query()
            ->where('id', $sectionId)
            ->where('class_id', $classId)
            ->first();

        if (!$section) {
            throw ValidationException::withMessages([
                'section_id' =>
                    'The selected section does not belong to the selected class.',
            ]);
        }

        return $section;
    }

    /**
     * Prevent duplicate active enrollment.
     */
    protected function validateDuplicateEnrollment(
        int $studentId,
        int $academicYearId,
        ?int $termId = null,
        ?int $ignoreId = null
    ): void {
        $query = StudentEnrollment::query()
            ->where('student_id', $studentId)
            ->where('academic_year_id', $academicYearId)
            ->where('status', 'active');

        if ($termId !== null) {
            $query->where('term_id', $termId);
        } else {
            $query->whereNull('term_id');
        }

        if ($ignoreId !== null) {
            $query->where('id', '!=', $ignoreId);
        }

        if ($query->exists()) {
            throw ValidationException::withMessages([
                'student_id' =>
                    'This student already has an active enrollment for the selected academic period.',
            ]);
        }
    }

    /**
     * Generate a unique enrollment number.
     */
    protected function generateEnrollmentNumber(
        int $schoolId
    ): string {
        do {
            $number = 'ENR-' .
                $schoolId . '-' .
                now()->format('YmdHis') . '-' .
                random_int(100, 999);
        } while (
            StudentEnrollment::withTrashed()
                ->where('enrollment_number', $number)
                ->exists()
        );

        return $number;
    }

    /**
     * Ensure enrollment belongs to the current school.
     */
    protected function ensureSameSchool(
        StudentEnrollment $enrollment
    ): void {
        $schoolId = $this->schoolId();

        if ((int) $enrollment->school_id !== $schoolId) {
            abort(
                403,
                'You are not authorized to access this enrollment.'
            );
        }
    }

    /**
     * Get current user's school.
     */
    protected function schoolId(): int
    {
        $schoolId = Auth::user()?->school_id;

        if (!$schoolId) {
            abort(
                403,
                'No school is assigned to this user.'
            );
        }

        return (int) $schoolId;
    }
}