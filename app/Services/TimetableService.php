<?php

namespace App\Services;

use App\Models\AcademicYears;
use App\Models\Classes;
use App\Models\Courses;
use App\Models\Section;
use App\Models\Staff;
use App\Models\Terms;
use App\Models\Timetable;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TimetableService
{
    /**
     * Create a timetable entry.
     */
    public function create(User $user, array $data): Timetable
    {
        return DB::transaction(function () use ($user, $data) {
            $schoolId = $this->schoolId($user);

            $references = $this->validateReferences(
                $schoolId,
                $data
            );

            $this->validateTimeRange($data);

            $this->validateConflicts(
                $schoolId,
                $data
            );

            return Timetable::create([
                'school_id' => $schoolId,
                'course_id' => $references['course']->id,
                'class_id' => $references['class']->id,
                'section_id' => $references['section']?->id,
                'staff_id' => $references['staff']?->id,
                'academic_year_id' => $references['academicYear']->id,
                'term_id' => $references['term']?->id,
                'day_of_week' => $data['day_of_week'],
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time'],
                'room' => $data['room'] ?? null,
            ]);
        });
    }

    /**
     * Update an existing timetable entry.
     */
    public function update(
        User $user,
        Timetable $timetable,
        array $data
    ): Timetable {
        return DB::transaction(function () use (
            $user,
            $timetable,
            $data
        ) {
            $schoolId = $this->schoolId($user);

            $this->ensureOwnership(
                $schoolId,
                $timetable
            );

            $references = $this->validateReferences(
                $schoolId,
                $data
            );

            $this->validateTimeRange($data);

            $this->validateConflicts(
                $schoolId,
                $data,
                $timetable->id
            );

            $timetable->update([
                'course_id' => $references['course']->id,
                'class_id' => $references['class']->id,
                'section_id' => $references['section']?->id,
                'staff_id' => $references['staff']?->id,
                'academic_year_id' => $references['academicYear']->id,
                'term_id' => $references['term']?->id,
                'day_of_week' => $data['day_of_week'],
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time'],
                'room' => $data['room'] ?? null,
            ]);

            return $timetable->fresh([
                'school',
                'course',
                'class',
                'section',
                'staff',
                'academicYear',
                'term',
            ]);
        });
    }

    /**
     * Soft-delete a timetable entry.
     */
    public function delete(
        User $user,
        Timetable $timetable
    ): void {
        DB::transaction(function () use (
            $user,
            $timetable
        ) {
            $schoolId = $this->schoolId($user);

            $this->ensureOwnership(
                $schoolId,
                $timetable
            );

            $timetable->delete();
        });
    }

    /**
     * Restore a deleted timetable entry.
     */
    public function restore(
        User $user,
        int $timetableId
    ): Timetable {
        return DB::transaction(function () use (
            $user,
            $timetableId
        ) {
            $schoolId = $this->schoolId($user);

            $timetable = Timetable::withTrashed()
                ->where('id', $timetableId)
                ->where('school_id', $schoolId)
                ->first();

            if (!$timetable) {
                throw ValidationException::withMessages([
                    'timetable' => 'The selected timetable entry is invalid.',
                ]);
            }

            if (!$timetable->trashed()) {
                throw ValidationException::withMessages([
                    'timetable' => 'The selected timetable entry is already active.',
                ]);
            }

            $data = [
                'course_id' => $timetable->course_id,
                'class_id' => $timetable->class_id,
                'section_id' => $timetable->section_id,
                'staff_id' => $timetable->staff_id,
                'academic_year_id' => $timetable->academic_year_id,
                'term_id' => $timetable->term_id,
                'day_of_week' => $timetable->day_of_week,
                'start_time' => $timetable->start_time,
                'end_time' => $timetable->end_time,
                'room' => $timetable->room,
            ];

            $this->validateReferences(
                $schoolId,
                $data
            );

            $this->validateTimeRange($data);

            $this->validateConflicts(
                $schoolId,
                $data
            );

            $timetable->restore();

            return $timetable->fresh([
                'school',
                'course',
                'class',
                'section',
                'staff',
                'academicYear',
                'term',
            ]);
        });
    }

    /**
     * Get the current user's school ID.
     */
    protected function schoolId(User $user): int
    {
        $schoolId = (int) $user->school_id;

        if (!$schoolId) {
            throw ValidationException::withMessages([
                'timetable' => 'No school is assigned to the current user.',
            ]);
        }

        return $schoolId;
    }

    /**
     * Ensure the timetable belongs to the user's school.
     */
    protected function ensureOwnership(
        int $schoolId,
        Timetable $timetable
    ): void {
        if ((int) $timetable->school_id !== $schoolId) {
            throw ValidationException::withMessages([
                'timetable' => 'The selected timetable entry is invalid.',
            ]);
        }
    }

    /**
     * Validate all referenced records and school ownership.
     */
    protected function validateReferences(
        int $schoolId,
        array $data
    ): array {
        $course = Courses::query()
            ->where('id', $data['course_id'] ?? 0)
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->whereNull('deleted_at')
            ->first();

        if (!$course) {
            throw ValidationException::withMessages([
                'course_id' => 'The selected course is invalid.',
            ]);
        }

        $class = Classes::query()
            ->where('id', $data['class_id'] ?? 0)
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->whereNull('deleted_at')
            ->first();

        if (!$class) {
            throw ValidationException::withMessages([
                'class_id' => 'The selected class is invalid.',
            ]);
        }

        $section = null;

        if (!empty($data['section_id'])) {
            $section = Section::query()
                ->where('id', $data['section_id'])
                ->where('class_id', $class->id)
                ->where('is_active', true)
                ->whereNull('deleted_at')
                ->first();

            if (!$section) {
                throw ValidationException::withMessages([
                    'section_id' => 'The selected section does not belong to the selected class.',
                ]);
            }
        }

        $staff = null;

        if (!empty($data['staff_id'])) {
            $staff = Staff::query()
                ->where('id', $data['staff_id'])
                ->where('school_id', $schoolId)
                ->where('status', 'active')
                ->whereNull('deleted_at')
                ->first();

            if (!$staff) {
                throw ValidationException::withMessages([
                    'staff_id' => 'The selected staff member is invalid.',
                ]);
            }
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

        $term = null;

        if (!empty($data['term_id'])) {
            $term = Terms::query()
                ->where('id', $data['term_id'])
                ->where('school_id', $schoolId)
                ->where('academic_year_id', $academicYear->id)
                ->whereNull('deleted_at')
                ->first();

            if (!$term) {
                throw ValidationException::withMessages([
                    'term_id' => 'The selected term does not belong to the selected academic year.',
                ]);
            }
        }

        return [
            'course' => $course,
            'class' => $class,
            'section' => $section,
            'staff' => $staff,
            'academicYear' => $academicYear,
            'term' => $term,
        ];
    }

    /**
     * Validate timetable time range.
     */
    protected function validateTimeRange(array $data): void
    {
        $start = strtotime($data['start_time'] ?? '');
        $end = strtotime($data['end_time'] ?? '');

        if ($start === false || $end === false) {
            throw ValidationException::withMessages([
                'start_time' => 'A valid start and end time are required.',
            ]);
        }

        if ($start >= $end) {
            throw ValidationException::withMessages([
                'end_time' => 'The end time must be later than the start time.',
            ]);
        }
    }

    /**
     * Validate class, teacher and room conflicts.
     */
    protected function validateConflicts(
        int $schoolId,
        array $data,
        ?int $ignoreId = null
    ): void {
        $query = Timetable::query()
            ->where('school_id', $schoolId)
            ->where('academic_year_id', $data['academic_year_id'])
            ->where('day_of_week', $data['day_of_week'])
            ->where(function ($q) use ($data) {
                $q->where('start_time', '<', $data['end_time'])
                    ->where('end_time', '>', $data['start_time']);
            });

        if ($data['term_id'] ?? null) {
            $query->where(function ($q) use ($data) {
                $q->where('term_id', $data['term_id'])
                    ->orWhereNull('term_id');
            });
        }

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        $conflicts = $query->get();

        /*
         * Same class/section cannot have overlapping lessons.
         */
        foreach ($conflicts as $conflict) {
            $sameClass = (int) $conflict->class_id === (int) $data['class_id'];

            if (!$sameClass) {
                continue;
            }

            /*
             * If both entries have sections, they conflict only
             * when they belong to the same section.
             *
             * If either entry has no section, treat it as a
             * class-wide timetable entry and therefore conflicting.
             */
            $sameSection =
                $conflict->section_id === null ||
                ($data['section_id'] ?? null) === null ||
                (int) $conflict->section_id === (int) $data['section_id'];

            if ($sameSection) {
                throw ValidationException::withMessages([
                    'class_id' => 'This class/section already has a timetable entry during the selected time.',
                ]);
            }
        }

        /*
         * Same staff member cannot teach two overlapping lessons.
         */
        if (!empty($data['staff_id'])) {
            foreach ($conflicts as $conflict) {
                if (
                    $conflict->staff_id !== null &&
                    (int) $conflict->staff_id === (int) $data['staff_id']
                ) {
                    throw ValidationException::withMessages([
                        'staff_id' => 'This staff member is already assigned to another timetable entry during the selected time.',
                    ]);
                }
            }
        }

        /*
         * Same room cannot be occupied by two overlapping lessons.
         */
        if (!empty($data['room'])) {
            $requestedRoom = mb_strtolower(trim($data['room']));

            foreach ($conflicts as $conflict) {
                if (
                    $conflict->room !== null &&
                    mb_strtolower(trim($conflict->room)) === $requestedRoom
                ) {
                    throw ValidationException::withMessages([
                        'room' => 'This room is already assigned to another timetable entry during the selected time.',
                    ]);
                }
            }
        }
    }
}