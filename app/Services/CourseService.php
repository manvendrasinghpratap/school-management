<?php

namespace App\Services;

use App\Models\Courses;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CourseService
{
    public function create(array $data): Courses
    {
        return DB::transaction(function () use ($data) {

            $schoolId = $this->schoolId();

            $this->validateDepartment(
                $data['department_id'] ?? null,
                $schoolId
            );

            $data['school_id'] = $schoolId;
            $data['is_compulsory'] = $data['is_compulsory'] ?? false;
            $data['is_active'] = $data['is_active'] ?? true;

            return Courses::create($data);
        });
    }

    public function update(Courses $course, array $data): Courses
    {
        return DB::transaction(function () use ($course, $data) {

            $this->ensureSameSchool($course);

            $schoolId = $this->schoolId();

            $this->validateDepartment(
                $data['department_id'] ?? null,
                $schoolId
            );

            unset($data['school_id']);

            $course->update($data);

            return $course->fresh(['department']);
        });
    }

    public function delete(Courses $course): void
    {
        DB::transaction(function () use ($course) {

            $this->ensureSameSchool($course);

            $course->delete();
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
                'department_id' => 'The selected department does not belong to your school.',
            ]);
        }
    }

    protected function schoolId(): int
    {
        $schoolId = Auth::user()?->school_id;

        if (! $schoolId) {
            throw ValidationException::withMessages([
                'school' => 'Your account is not assigned to a school.',
            ]);
        }

        return (int) $schoolId;
    }

    protected function ensureSameSchool(Courses $course): void
    {
        $schoolId = $this->schoolId();

        if ((int) $course->school_id !== $schoolId) {
            abort(403, 'You are not authorized to access this course.');
        }
    }
}