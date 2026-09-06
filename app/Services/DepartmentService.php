<?php

namespace App\Services;

use App\Models\Department;
use Illuminate\Support\Facades\DB;

class DepartmentService
{
    public function create(array $data): Department
    {
        return DB::transaction(function () use ($data) {
            $schoolId = $this->schoolId();

            $data['school_id'] = $schoolId;
            $data['is_active'] = $data['is_active'] ?? true;

            return Department::create($data);
        });
    }

    public function update(Department $department, array $data): Department
    {
        return DB::transaction(function () use ($department, $data) {
            $this->ensureSameSchool($department);

            unset($data['school_id']);

            $department->update($data);

            return $department->fresh();
        });
    }

    public function delete(Department $department): void
    {
        DB::transaction(function () use ($department) {
            $this->ensureSameSchool($department);

            $department->delete();
        });
    }

    protected function schoolId(): int
    {
        $user = auth()->user();

        abort_unless(
            $user,
            403,
            'You must be authenticated.'
        );

        abort_unless(
            $user->school_id,
            403,
            'No school is assigned to the current user.'
        );

        return (int) $user->school_id;
    }

    protected function ensureSameSchool(Department $department): void
    {
        $schoolId = $this->schoolId();

        abort_unless(
            (int) $department->school_id === $schoolId,
            403,
            'You are not authorized to access this department.'
        );
    }
}