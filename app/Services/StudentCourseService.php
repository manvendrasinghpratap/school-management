<?php

namespace App\Services;

use App\Models\AcademicYears;
use App\Models\Courses;
use App\Models\Student;
use App\Models\StudentCourse;
use App\Models\StudentEnrollment;
use App\Models\Terms;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StudentCourseService
{
    /**
     * Create a student course registration.
     */
    public function create(
        int $schoolId,
        array $data,
        int $userId
    ): StudentCourse {
        return DB::transaction(function () use (
            $schoolId,
            $data,
            $userId
        ) {

            $student = Student::query()
                ->where('id', $data['student_id'])
                ->where('school_id', $schoolId)
                ->whereNull('deleted_at')
                ->first();

            if (!$student) {
                throw ValidationException::withMessages([
                    'student_id' =>
                        'The selected student does not belong to this school.',
                ]);
            }

            $course = Courses::query()
                ->where('id', $data['course_id'])
                ->where('school_id', $schoolId)
                ->where('is_active', true)
                ->first();

            if (!$course) {
                throw ValidationException::withMessages([
                    'course_id' =>
                        'The selected course is not available for this school.',
                ]);
            }

            $academicYear = AcademicYears::query()
                ->where('id', $data['academic_year_id'])
                ->where('school_id', $schoolId)
                ->first();

            if (!$academicYear) {
                throw ValidationException::withMessages([
                    'academic_year_id' =>
                        'The selected academic year does not belong to this school.',
                ]);
            }

            $termId = $data['term_id'] ?? null;

            $this->validateTerm(
                $schoolId,
                $academicYear->id,
                $termId
            );

            $this->validateStudentEnrollment(
                schoolId: $schoolId,
                studentId: $student->id,
                academicYearId: $academicYear->id,
                termId: $termId,
                classId: (int) $data['class_id'],
                sectionId: isset($data['section_id'])
                    ? (int) $data['section_id']
                    : null
            );

            $duplicateQuery = StudentCourse::query()
                ->where('student_id', $student->id)
                ->where('course_id', $course->id)
                ->where('academic_year_id', $academicYear->id);

            if ($termId === null) {
                $duplicateQuery->whereNull('term_id');
            } else {
                $duplicateQuery->where('term_id', $termId);
            }

            if ($duplicateQuery->exists()) {
                throw ValidationException::withMessages([
                    'course_id' =>
                        'This student is already registered for the selected course in the selected academic year and term.',
                ]);
            }

            return StudentCourse::create([
                'student_id' => $student->id,
                'course_id' => $course->id,
                'academic_year_id' => $academicYear->id,
                'term_id' => $termId,
                'status' => $data['status'] ?? 'enrolled',
            ]);
        });
    }

    /**
     * Update a student course registration.
     */
    public function update(
        StudentCourse $registration,
        int $schoolId,
        array $data,
        int $userId
    ): StudentCourse {
        return DB::transaction(function () use (
            $registration,
            $schoolId,
            $data,
            $userId
        ) {

            $student = Student::query()
                ->where('id', $data['student_id'])
                ->where('school_id', $schoolId)
                ->whereNull('deleted_at')
                ->first();

            if (!$student) {
                throw ValidationException::withMessages([
                    'student_id' =>
                        'The selected student does not belong to this school.',
                ]);
            }

            $course = Courses::query()
                ->where('id', $data['course_id'])
                ->where('school_id', $schoolId)
                ->where('is_active', true)
                ->first();

            if (!$course) {
                throw ValidationException::withMessages([
                    'course_id' =>
                        'The selected course is not available for this school.',
                ]);
            }

            $academicYear = AcademicYears::query()
                ->where('id', $data['academic_year_id'])
                ->where('school_id', $schoolId)
                ->first();

            if (!$academicYear) {
                throw ValidationException::withMessages([
                    'academic_year_id' =>
                        'The selected academic year does not belong to this school.',
                ]);
            }

            $termId = $data['term_id'] ?? null;

            $this->validateTerm(
                $schoolId,
                $academicYear->id,
                $termId
            );

            $this->validateStudentEnrollment(
                schoolId: $schoolId,
                studentId: $student->id,
                academicYearId: $academicYear->id,
                termId: $termId,
                classId: (int) $data['class_id'],
                sectionId: isset($data['section_id'])
                    ? (int) $data['section_id']
                    : null
            );

            $duplicateQuery = StudentCourse::query()
                ->where('id', '!=', $registration->id)
                ->where('student_id', $student->id)
                ->where('course_id', $course->id)
                ->where('academic_year_id', $academicYear->id);

            if ($termId === null) {
                $duplicateQuery->whereNull('term_id');
            } else {
                $duplicateQuery->where('term_id', $termId);
            }

            if ($duplicateQuery->exists()) {
                throw ValidationException::withMessages([
                    'course_id' =>
                        'This student is already registered for the selected course in the selected academic year and term.',
                ]);
            }

            $registration->update([
                'student_id' => $student->id,
                'course_id' => $course->id,
                'academic_year_id' => $academicYear->id,
                'term_id' => $termId,
                'status' => $data['status'],
            ]);

            return $registration->fresh();
        });
    }

    /**
     * Delete a registration.
     */
    public function delete(
        StudentCourse $registration,
        int $schoolId
    ): void {
        $this->ensureRegistrationBelongsToSchool(
            $registration,
            $schoolId
        );

        $registration->delete();
    }

    /**
     * Ensure registration belongs to current school.
     */
    public function ensureRegistrationBelongsToSchool(
        StudentCourse $registration,
        int $schoolId
    ): void {
        $registration->loadMissing([
            'student',
            'course',
            'academicYear',
        ]);

        if (
            !$registration->student ||
            !$registration->course ||
            !$registration->academicYear ||
            $registration->student->school_id !== $schoolId ||
            $registration->course->school_id !== $schoolId ||
            $registration->academicYear->school_id !== $schoolId
        ) {
            abort(
                403,
                'You are not authorized to access this student course registration.'
            );
        }
    }

    /**
     * Validate term.
     */
    protected function validateTerm(
        int $schoolId,
        int $academicYearId,
        ?int $termId
    ): void {
        if ($termId === null) {
            return;
        }

        $term = Terms::query()
            ->where('id', $termId)
            ->where('academic_year_id', $academicYearId)
            ->where('school_id', $schoolId)
            ->first();

        if (!$term) {
            throw ValidationException::withMessages([
                'term_id' =>
                    'The selected term does not belong to the selected academic year.',
            ]);
        }
    }

    /**
     * Validate student's active enrollment.
     */
    protected function validateStudentEnrollment(
        int $schoolId,
        int $studentId,
        int $academicYearId,
        ?int $termId,
        int $classId,
        ?int $sectionId
    ): StudentEnrollment {

        $query = StudentEnrollment::query()
            ->where('school_id', $schoolId)
            ->where('student_id', $studentId)
            ->where('academic_year_id', $academicYearId)
            ->where('class_id', $classId)
            ->where('status', 'active')
            ->whereNull('deleted_at');

        if ($termId === null) {
            $query->whereNull('term_id');
        } else {
            $query->where('term_id', $termId);
        }

        if ($sectionId === null) {
            $query->whereNull('section_id');
        } else {
            $query->where('section_id', $sectionId);
        }

        $enrollment = $query
            ->latest('id')
            ->first();

        if (!$enrollment) {
            throw ValidationException::withMessages([
                'student_id' =>
                    'The selected student does not have an active enrollment matching the selected academic year, term, class and section.',
            ]);
        }

        return $enrollment;
    }
}