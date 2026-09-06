<?php

namespace App\Services;

use App\Models\Classes;
use Illuminate\Support\Facades\DB;

class ClassService
{
    public function create(array $data): Classes
    {
        return DB::transaction(function () use ($data) {
            $schoolId = $this->schoolId();

            $this->validateDepartment($data['department_id'] ?? null, $schoolId);

            $data['school_id'] = $schoolId;
            $data['is_active'] = $data['is_active'] ?? true;

            return Classes::create($data);
        });
    }

    public function update(Classes $class, array $data): Classes
    {
        return DB::transaction(function () use ($class, $data) {
            $this->ensureSameSchool($class);

            $schoolId = $this->schoolId();

            $this->validateDepartment(
                $data['department_id'] ?? null,
                $schoolId
            );

            unset($data['school_id']);

            $class->update($data);

            return $class->fresh([
                'department',
                'level',
            ]);
        });
    }

    public function delete(Classes $class): void
    {
        DB::transaction(function () use ($class) {
            $this->ensureSameSchool($class);

            $class->delete();
        });
    }

    protected function validateDepartment(
        mixed $departmentId,
        int $schoolId
    ): void {
        if (!$departmentId) {
            return;
        }

        $exists = DB::table('departments')
            ->where('id', $departmentId)
            ->where('school_id', $schoolId)
            ->exists();

        abort_unless(
            $exists,
            403,
            'The selected department does not belong to your school.'
        );
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

    protected function ensureSameSchool(Classes $class): void
    {
        $schoolId = $this->schoolId();

        abort_unless(
            (int) $class->school_id === $schoolId,
            403,
            'You are not authorized to access this class.'
        );
    }
}