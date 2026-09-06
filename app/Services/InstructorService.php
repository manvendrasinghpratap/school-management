<?php

namespace App\Services;

use App\Models\Instructor;
use App\Models\Staff;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InstructorService
{
    /**
     * Create an instructor profile for an existing staff member.
     */
    public function create(array $data): Instructor
    {
        return DB::transaction(function () use ($data) {

            $schoolId = $this->schoolId();

            $staff = Staff::query()
                ->where('id', $data['staff_id'])
                ->where('school_id', $schoolId)
                ->first();

            if (!$staff) {
                throw ValidationException::withMessages([
                    'staff_id' => 'The selected staff member does not belong to this school.',
                ]);
            }

            if ($staff->instructor()->exists()) {
                throw ValidationException::withMessages([
                    'staff_id' => 'This staff member already has an instructor profile.',
                ]);
            }

            return Instructor::create([
                'staff_id' => $staff->id,
                'specialization' => $data['specialization'] ?? null,
                'qualification' => $data['qualification'] ?? null,
            ]);
        });
    }

    /**
     * Update an instructor profile.
     */
    public function update(Instructor $instructor, array $data): Instructor
    {
        return DB::transaction(function () use ($instructor, $data) {

            $this->ensureSameSchool($instructor);

            $instructor->update([
                'specialization' => $data['specialization'] ?? null,
                'qualification' => $data['qualification'] ?? null,
            ]);

            return $instructor->fresh(['staff.department']);
        });
    }

    /**
     * Delete an instructor profile.
     *
     * This does NOT delete the staff member.
     */
    public function delete(Instructor $instructor): void
    {
        DB::transaction(function () use ($instructor) {

            $this->ensureSameSchool($instructor);

            $instructor->delete();
        });
    }

    /**
     * Get the currently authenticated user's school.
     */
    protected function schoolId(): int
    {
        $schoolId = Auth::user()?->school_id;

        if (!$schoolId) {
            abort(403, 'No school is assigned to this user.');
        }

        return (int) $schoolId;
    }

    /**
     * Ensure the instructor belongs to the authenticated user's school.
     */
    protected function ensureSameSchool(Instructor $instructor): void
    {
        $schoolId = $this->schoolId();

        $belongsToSchool = $instructor->staff()
            ->where('school_id', $schoolId)
            ->exists();

        if (!$belongsToSchool) {
            abort(403, 'You are not authorized to access this instructor.');
        }
    }
}
