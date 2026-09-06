<?php

namespace App\Services;

use App\Models\Department;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class StaffService
{
    public function create(array $data, ?UploadedFile $photo = null): Staff
    {
        return DB::transaction(function () use ($data, $photo) {
            $schoolId = $this->schoolId();

            $this->validateDepartment(
                $data['department_id'] ?? null,
                $schoolId
            );

            $this->validateUser(
                $data['user_id'] ?? null,
                $schoolId
            );

            $data['school_id'] = $schoolId;

            if ($photo) {
                $data['photo'] = $photo->store(
                    'staff/photos',
                    'public'
                );
            }

            return Staff::create($data);
        });
    }

    public function update(
        Staff $staff,
        array $data,
        ?UploadedFile $photo = null
    ): Staff {
        return DB::transaction(function () use ($staff, $data, $photo) {
            $this->ensureSameSchool($staff);

            $schoolId = $this->schoolId();

            $this->validateDepartment(
                $data['department_id'] ?? null,
                $schoolId
            );

            $this->validateUser(
                $data['user_id'] ?? null,
                $schoolId,
                $staff->user_id
            );

            unset($data['school_id']);

            if ($photo) {
                if ($staff->photo) {
                    Storage::disk('public')->delete($staff->photo);
                }

                $data['photo'] = $photo->store(
                    'staff/photos',
                    'public'
                );
            }

            $staff->update($data);

            return $staff->fresh([
                'department',
                'user',
                'instructor',
            ]);
        });
    }

    public function delete(Staff $staff): void
    {
        DB::transaction(function () use ($staff) {
            $this->ensureSameSchool($staff);

            if ($staff->photo) {
                Storage::disk('public')->delete($staff->photo);
            }

            $staff->delete();
        });
    }

    protected function validateDepartment(
        mixed $departmentId,
        int $schoolId
    ): void {
        if ($departmentId === null || $departmentId === '') {
            return;
        }

        $exists = Department::query()
            ->where('id', $departmentId)
            ->where('school_id', $schoolId)
            ->exists();

        if (! $exists) {
            throw ValidationException::withMessages([
                'department_id' =>
                    'The selected department does not belong to your school.',
            ]);
        }
    }

    protected function validateUser(
        mixed $userId,
        int $schoolId,
        ?int $currentUserId = null
    ): void {
        if ($userId === null || $userId === '') {
            return;
        }

        $user = User::query()
            ->where('id', $userId)
            ->where('school_id', $schoolId)
            ->first();

        if (! $user) {
            throw ValidationException::withMessages([
                'user_id' =>
                    'The selected user does not belong to your school.',
            ]);
        }

        $alreadyAssigned = Staff::query()
            ->where('user_id', $userId)
            ->when(
                $currentUserId,
                fn ($query) => $query->where(
                    'user_id',
                    '!=',
                    $currentUserId
                )
            )
            ->exists();

        if ($alreadyAssigned) {
            throw ValidationException::withMessages([
                'user_id' =>
                    'The selected user is already linked to another staff member.',
            ]);
        }
    }

    protected function schoolId(): int
    {
        $schoolId = Auth::user()?->school_id;

        if (! $schoolId) {
            throw ValidationException::withMessages([
                'school' =>
                    'Your account is not assigned to a school.',
            ]);
        }

        return (int) $schoolId;
    }

    protected function ensureSameSchool(Staff $staff): void
    {
        $schoolId = $this->schoolId();

        if ((int) $staff->school_id !== $schoolId) {
            abort(
                403,
                'You are not authorized to access this staff member.'
            );
        }
    }
}