<?php

namespace App\Services;

use App\Models\Classes;
use App\Models\Section;
use Illuminate\Support\Facades\DB;

class SectionService
{
    public function create(array $data): Section
    {
        return DB::transaction(function () use ($data) {
            $schoolId = $this->schoolId();

            $class = $this->getSchoolClass(
                (int) $data['class_id'],
                $schoolId
            );

            $data['class_id'] = $class->id;
            $data['is_active'] = $data['is_active'] ?? true;

            return Section::create($data);
        });
    }

    public function update(Section $section, array $data): Section
    {
        return DB::transaction(function () use ($section, $data) {
            $this->ensureSameSchool($section);

            $schoolId = $this->schoolId();

            $class = $this->getSchoolClass(
                (int) $data['class_id'],
                $schoolId
            );

            $data['class_id'] = $class->id;

            $section->update($data);

            return $section->fresh([
                'class',
            ]);
        });
    }

    public function delete(Section $section): void
    {
        DB::transaction(function () use ($section) {
            $this->ensureSameSchool($section);

            $section->delete();
        });
    }

    protected function getSchoolClass(int $classId, int $schoolId): Classes
    {
        return Classes::query()
            ->where('id', $classId)
            ->where('school_id', $schoolId)
            ->firstOrFail();
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

    protected function ensureSameSchool(Section $section): void
    {
        $schoolId = $this->schoolId();

        $belongsToSchool = Classes::query()
            ->where('id', $section->class_id)
            ->where('school_id', $schoolId)
            ->exists();

        abort_unless(
            $belongsToSchool,
            403,
            'You are not authorized to access this section.'
        );
    }
}