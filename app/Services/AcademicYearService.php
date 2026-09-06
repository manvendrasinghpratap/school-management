<?php

namespace App\Services;

use App\Models\AcademicYears;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AcademicYearService
{
    /**
     * Create an academic year.
     */
    public function create(array $data): AcademicYears
    {
        return DB::transaction(function () use ($data) {

            $schoolId = $this->schoolId();

            $this->validateDates(
                $data['start_date'],
                $data['end_date']
            );

            $this->validateName(
                $data['name'],
                $schoolId
            );

            $isCurrent = (bool) ($data['is_current'] ?? false);

            /*
             * Only one academic year can be current
             * for a school.
             */
            if ($isCurrent) {
                AcademicYears::query()
                    ->where('school_id', $schoolId)
                    ->update([
                        'is_current' => false,
                    ]);
            }

            return AcademicYears::create([
                'school_id'  => $schoolId,
                'name'       => trim($data['name']),
                'start_date' => $data['start_date'],
                'end_date'   => $data['end_date'],
                'is_current' => $isCurrent,
                'is_active'  => (bool) ($data['is_active'] ?? true),
            ]);
        });
    }

    /**
     * Update an academic year.
     */
    public function update(
        AcademicYears $academicYear,
        array $data
    ): AcademicYears {
        return DB::transaction(function () use ($academicYear, $data) {

            $schoolId = $this->schoolId();

            $this->ensureSameSchool($academicYear);

            $this->validateDates(
                $data['start_date'],
                $data['end_date']
            );

            $this->validateName(
                $data['name'],
                $schoolId,
                $academicYear->id
            );

            $isCurrent = (bool) ($data['is_current'] ?? false);

            /*
             * If this year becomes current,
             * remove current status from other years.
             */
            if ($isCurrent) {
                AcademicYears::query()
                    ->where('school_id', $schoolId)
                    ->where('id', '!=', $academicYear->id)
                    ->update([
                        'is_current' => false,
                    ]);
            }

            $academicYear->update([
                'name'       => trim($data['name']),
                'start_date' => $data['start_date'],
                'end_date'   => $data['end_date'],
                'is_current' => $isCurrent,
                'is_active'  => (bool) ($data['is_active'] ?? true),
            ]);

            return $academicYear->fresh();
        });
    }

    /**
     * Delete an academic year.
     *
     * AcademicYears currently do not have deleted_at,
     * so this is a hard delete.
     */
    public function delete(AcademicYears $academicYear): void
    {
        DB::transaction(function () use ($academicYear) {

            $this->ensureSameSchool($academicYear);

            /*
             * Do not allow deletion of an academic year
             * that already contains terms or enrollments.
             */
            if ($academicYear->terms()->exists()) {
                throw ValidationException::withMessages([
                    'academic_year' =>
                        'This academic year cannot be deleted because it has terms assigned to it.',
                ]);
            }

            if (
                DB::table('student_enrollments')
                    ->where('academic_year_id', $academicYear->id)
                    ->exists()
            ) {
                throw ValidationException::withMessages([
                    'academic_year' =>
                        'This academic year cannot be deleted because it has student enrollments.',
                ]);
            }

            $academicYear->delete();
        });
    }

    /**
     * Set an academic year as current.
     */
    public function setCurrent(
        AcademicYears $academicYear
    ): AcademicYears {
        return DB::transaction(function () use ($academicYear) {

            $schoolId = $this->schoolId();

            $this->ensureSameSchool($academicYear);

            AcademicYears::query()
                ->where('school_id', $schoolId)
                ->update([
                    'is_current' => false,
                ]);

            $academicYear->update([
                'is_current' => true,
            ]);

            return $academicYear->fresh();
        });
    }

    /**
     * Validate date range.
     */
    protected function validateDates(
        string $startDate,
        string $endDate
    ): void {
        if ($endDate < $startDate) {
            throw ValidationException::withMessages([
                'end_date' =>
                    'The academic year end date must be after the start date.',
            ]);
        }
    }

    /**
     * Validate academic year name uniqueness.
     */
    protected function validateName(
        string $name,
        int $schoolId,
        ?int $ignoreId = null
    ): void {
        $query = AcademicYears::query()
            ->where('school_id', $schoolId)
            ->where('name', trim($name));

        if ($ignoreId !== null) {
            $query->where('id', '!=', $ignoreId);
        }

        if ($query->exists()) {
            throw ValidationException::withMessages([
                'name' =>
                    'An academic year with this name already exists for this school.',
            ]);
        }
    }

    /**
     * Ensure the academic year belongs to the current school.
     */
    protected function ensureSameSchool(
        AcademicYears $academicYear
    ): void {
        $schoolId = $this->schoolId();

        if ((int) $academicYear->school_id !== $schoolId) {
            abort(
                403,
                'You are not authorized to access this academic year.'
            );
        }
    }

    /**
     * Get the authenticated user's school.
     */
    protected function schoolId(): int
    {
        $schoolId = Auth::user()?->school_id;

        if (!$schoolId) {
            abort(
                403,
                'No school is assigned to this user.'
            );
        }

        return (int) $schoolId;
    }
}