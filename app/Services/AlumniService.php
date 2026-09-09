<?php

namespace App\Services;

use App\Models\Alumni;
use App\Models\Graduation;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AlumniService
{
    /**
     * Create an Alumni record from a completed graduation.
     */
    public function create(User $user, array $data): Alumni
    {
        return DB::transaction(function () use ($user, $data) {
            $schoolId = (int) $user->school_id;

            if (!$schoolId) {
                throw ValidationException::withMessages([
                    'alumni' => 'No school is assigned to the current user.',
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

            /*
            * A student can only have one Alumni record.
            *
            * If the existing record was soft deleted, restore it
            * instead of creating another row because student_id
            * is unique in the database.
            */
            $existingAlumni = Alumni::withTrashed()
                ->where('school_id', $schoolId)
                ->where('student_id', $student->id)
                ->first();

            if ($existingAlumni && !$existingAlumni->trashed()) {
                throw ValidationException::withMessages([
                    'student_id' => 'This student already has an Alumni record.',
                ]);
            }

            /*
            * The student must have a completed graduation.
            */
            $graduation = Graduation::query()
                ->where('school_id', $schoolId)
                ->where('student_id', $student->id)
                ->where('status', 'completed')
                ->with('academicYear')
                ->latest('graduation_date')
                ->latest('id')
                ->first();

            if (!$graduation) {
                throw ValidationException::withMessages([
                    'student_id' => 'The student cannot be added to Alumni because the graduation has not been completed.',
                ]);
            }

            $alumniData = [
                'school_id' => $schoolId,
                'student_id' => $student->id,
                'graduation_date' => $graduation->graduation_date,
                'graduation_year' => optional($graduation->academicYear)->name,
                'current_occupation' => $data['current_occupation'] ?? null,
                'current_employer' => $data['current_employer'] ?? null,
                'phone' => $data['phone'] ?? $student->phone,
                'email' => $data['email'] ?? null,
                'address' => $data['address'] ?? $student->address,
            ];

            /*
            * Restore a previously deleted Alumni record.
            */
            if ($existingAlumni && $existingAlumni->trashed()) {
                $existingAlumni->restore();

                $existingAlumni->update($alumniData);

                return $existingAlumni->fresh([
                    'student',
                ]);
            }

            /*
            * No previous Alumni record exists.
            * Create a new one.
            */
            return Alumni::create($alumniData);
        });
    }

    /**
     * Update Alumni information.
     */
    public function update(
        User $user,
        Alumni $alumni,
        array $data
    ): Alumni {

        return DB::transaction(function () use ($user, $alumni, $data) {

            $schoolId = (int) $user->school_id;

            if (!$schoolId) {
                throw ValidationException::withMessages([
                    'alumni' => 'No school is assigned to the current user.',
                ]);
            }

            /*
             * School isolation.
             */
            if ((int) $alumni->school_id !== $schoolId) {
                throw ValidationException::withMessages([
                    'alumni' => 'The selected Alumni record is invalid.',
                ]);
            }

            /*
             * Update only Alumni contact/career information.
             *
             * Student and graduation information remain
             * controlled by the original records.
             */
            $alumni->update([
                'current_occupation' => $data['current_occupation'] ?? null,
                'current_employer' => $data['current_employer'] ?? null,
                'phone' => $data['phone'] ?? null,
                'email' => $data['email'] ?? null,
                'address' => $data['address'] ?? null,
            ]);

            return $alumni->fresh([
                'student',
            ]);
        });
    }

    /**
     * Delete an Alumni record.
     *
     * Because the Alumni model uses SoftDeletes,
     * the original record remains in the database.
     */
    public function delete(User $user, Alumni $alumni): void
    {
        DB::transaction(function () use ($user, $alumni) {

            $schoolId = (int) $user->school_id;

            if (!$schoolId) {
                throw ValidationException::withMessages([
                    'alumni' => 'No school is assigned to the current user.',
                ]);
            }

            if ((int) $alumni->school_id !== $schoolId) {
                throw ValidationException::withMessages([
                    'alumni' => 'The selected Alumni record is invalid.',
                ]);
            }

            $alumni->delete();
        });
    }
}