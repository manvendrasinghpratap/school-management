<?php

namespace App\Services;

use App\Models\AcademicYears;
use App\Models\Graduation;
use App\Models\Student;
use App\Models\StudentEnrollment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class GraduationService
{
    /**
     * Create a pending graduation record.
     */
    public function create(
        User $user,
        array $data
    ): Graduation {
        return DB::transaction(function () use ($user, $data) {
            $schoolId = (int) $user->school_id;

            if (!$schoolId) {
                throw ValidationException::withMessages([
                    'graduation' => 'No school is assigned to the current user.',
                ]);
            }

            $student = Student::query()
                ->where('id', $data['student_id'] ?? 0)
                ->where('school_id', $schoolId)
                ->whereNull('deleted_at')
                ->first();

            if (!$student) {
                throw ValidationException::withMessages([
                    'student_id' => 'The selected student is invalid.',
                ]);
            }

            if ($student->status !== 'active') {
                throw ValidationException::withMessages([
                    'student_id' => 'Only active students can be graduated.',
                ]);
            }

            $academicYear = AcademicYears::query()
                ->where('id', $data['academic_year_id'] ?? 0)
                ->where('school_id', $schoolId)
                ->where('is_active', true)
                ->whereNull('deleted_at')
                ->first();

            if (!$academicYear) {
                throw ValidationException::withMessages([
                    'academic_year_id' => 'The selected academic year is invalid.',
                ]);
            }

            $activeEnrollments = StudentEnrollment::query()
                ->where('student_id', $student->id)
                ->where('school_id', $schoolId)
                ->where('status', 'active')
                ->whereNull('deleted_at')
                ->with([
                    'academicYear',
                    'class',
                    'section',
                ])
                ->get();

            if ($activeEnrollments->count() !== 1) {
                throw ValidationException::withMessages([
                    'student_id' =>
                        'The student must have exactly one active enrollment before graduation.',
                ]);
            }

            /** @var StudentEnrollment $enrollment */
            $enrollment = $activeEnrollments->first();

            if (!$enrollment->class) {
                throw ValidationException::withMessages([
                    'student_id' =>
                        'The student\'s active enrollment has no valid class.',
                ]);
            }

            if ((int) $enrollment->class->school_id !== $schoolId) {
                throw ValidationException::withMessages([
                    'student_id' =>
                        'The student\'s class does not belong to the current school.',
                ]);
            }

            if (!$enrollment->class->is_active) {
                throw ValidationException::withMessages([
                    'student_id' =>
                        'The student\'s current class is inactive.',
                ]);
            }

            /*
             * Current SMS graduation point:
             *
             * Primary 1 → Primary 2 → Primary 3
             * JSS 1 → JSS 2 → JSS 3
             * SS 1 → SS 2 → SS 3
             *
             * Graduation occurs at SS 3.
             */
            if ($enrollment->class->name !== 'SS 3') {
                throw ValidationException::withMessages([
                    'student_id' =>
                        'The student is not eligible for graduation. Graduation is available to students in SS 3.',
                ]);
            }

            $alreadyGraduated = Graduation::query()
                ->where('school_id', $schoolId)
                ->where('student_id', $student->id)
                ->where('academic_year_id', $academicYear->id)
                ->exists();

            if ($alreadyGraduated) {
                throw ValidationException::withMessages([
                    'student_id' =>
                        'A graduation record already exists for this student in the selected academic year.',
                ]);
            }

            return Graduation::create([
                'school_id' => $schoolId,
                'student_id' => $student->id,
                'academic_year_id' => $academicYear->id,
                'graduation_date' => $data['graduation_date'],
                'qualification' => $data['qualification'] ?? null,
                'status' => 'pending',
                'approved_by' => null,
                'remarks' => $data['remarks'] ?? null,
            ]);
        });
    }

    /**
     * Approve a pending graduation.
     */
    public function approve(
        User $user,
        Graduation $graduation
    ): Graduation {
        return DB::transaction(function () use ($user, $graduation) {
            $schoolId = (int) $user->school_id;

            if (!$schoolId) {
                throw ValidationException::withMessages([
                    'graduation' => 'No school is assigned to the current user.',
                ]);
            }

            if ((int) $graduation->school_id !== $schoolId) {
                throw ValidationException::withMessages([
                    'graduation' => 'The selected graduation is invalid.',
                ]);
            }

            if ($graduation->status !== 'pending') {
                throw ValidationException::withMessages([
                    'graduation' =>
                        'Only pending graduation records can be approved.',
                ]);
            }

            $student = Student::query()
                ->where('id', $graduation->student_id)
                ->where('school_id', $schoolId)
                ->whereNull('deleted_at')
                ->first();

            if (!$student) {
                throw ValidationException::withMessages([
                    'graduation' => 'The selected student is invalid.',
                ]);
            }

            if ($student->status !== 'active') {
                throw ValidationException::withMessages([
                    'graduation' =>
                        'Only active students can have a graduation approved.',
                ]);
            }

            $academicYear = AcademicYears::query()
                ->where('id', $graduation->academic_year_id)
                ->where('school_id', $schoolId)
                ->where('is_active', true)
                ->whereNull('deleted_at')
                ->first();

            if (!$academicYear) {
                throw ValidationException::withMessages([
                    'graduation' =>
                        'The graduation academic year is invalid.',
                ]);
            }

            $activeEnrollments = StudentEnrollment::query()
                ->where('student_id', $student->id)
                ->where('school_id', $schoolId)
                ->where('status', 'active')
                ->whereNull('deleted_at')
                ->with('class')
                ->get();

            if ($activeEnrollments->count() !== 1) {
                throw ValidationException::withMessages([
                    'graduation' =>
                        'The student must have exactly one active enrollment before graduation can be approved.',
                ]);
            }

            /** @var StudentEnrollment $enrollment */
            $enrollment = $activeEnrollments->first();

            if (!$enrollment->class || $enrollment->class->name !== 'SS 3') {
                throw ValidationException::withMessages([
                    'graduation' =>
                        'The student is no longer eligible for graduation because the active enrollment is not SS 3.',
                ]);
            }

            $graduation->update([
                'status' => 'approved',
                'approved_by' => $user->id,
            ]);

            return $graduation->fresh([
                'student',
                'academicYear',
                'approvedBy',
            ]);
        });
    }

    /**
     * Mark an approved graduation as completed.
     */
    public function complete(
        User $user,
        Graduation $graduation
    ): Graduation {
        return DB::transaction(function () use ($user, $graduation) {
            $schoolId = (int) $user->school_id;

            if (!$schoolId) {
                throw ValidationException::withMessages([
                    'graduation' => 'No school is assigned to the current user.',
                ]);
            }

            if ((int) $graduation->school_id !== $schoolId) {
                throw ValidationException::withMessages([
                    'graduation' => 'The selected graduation is invalid.',
                ]);
            }

            if ($graduation->status !== 'approved') {
                throw ValidationException::withMessages([
                    'graduation' =>
                        'Only approved graduation records can be completed.',
                ]);
            }

            $graduation->update([
                'status' => 'completed',
            ]);

            return $graduation->fresh([
                'student',
                'academicYear',
                'approvedBy',
            ]);
        });
    }

    /**
     * Delete a pending graduation record.
     */
    public function delete(
        User $user,
        Graduation $graduation
    ): void {
        DB::transaction(function () use ($user, $graduation) {
            $schoolId = (int) $user->school_id;

            if (!$schoolId) {
                throw ValidationException::withMessages([
                    'graduation' => 'No school is assigned to the current user.',
                ]);
            }

            if ((int) $graduation->school_id !== $schoolId) {
                throw ValidationException::withMessages([
                    'graduation' => 'The selected graduation is invalid.',
                ]);
            }

            if ($graduation->status !== 'pending') {
                throw ValidationException::withMessages([
                    'graduation' =>
                        'Only pending graduation records can be deleted.',
                ]);
            }

            $graduation->delete();
        });
    }
}