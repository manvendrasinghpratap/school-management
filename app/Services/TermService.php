<?php

namespace App\Services;

use App\Models\AcademicYears;
use App\Models\Terms;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TermService
{
    /**
     * Create a new term.
     */
    public function create(array $data): Terms
    {
        return DB::transaction(function () use ($data) {

            $schoolId = $this->schoolId();

            $academicYear = $this->getAcademicYear(
                (int) $data['academic_year_id'],
                $schoolId
            );

            $this->validateDates(
                $data['start_date'],
                $data['end_date']
            );

            $this->validateWithinAcademicYear(
                $data['start_date'],
                $data['end_date'],
                $academicYear
            );

            $this->validateTermNumber(
                (int) $data['term_number'],
                $academicYear->id
            );

            $this->validateName(
                $data['name'],
                $academicYear->id
            );

            $isCurrent = (bool) ($data['is_current'] ?? false);

            /*
             * Only one current term is allowed
             * within an academic year.
             */
            if ($isCurrent) {
                Terms::where('school_id', $schoolId)
                    ->where('academic_year_id', $academicYear->id)
                    ->update([
                        'is_current' => false,
                    ]);
            }

            return Terms::create([
                'school_id' => $schoolId,
                'academic_year_id' => $academicYear->id,
                'name' => trim($data['name']),
                'term_number' => (int) $data['term_number'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'is_current' => $isCurrent,
                'is_active' => (bool) ($data['is_active'] ?? true),
            ]);
        });
    }

    /**
     * Update an existing term.
     */
    public function update(Terms $term, array $data): Terms
    {
        return DB::transaction(function () use ($term, $data) {

            $schoolId = $this->schoolId();

            $this->ensureSameSchool($term);

            $academicYear = $this->getAcademicYear(
                (int) $data['academic_year_id'],
                $schoolId
            );

            $this->validateDates(
                $data['start_date'],
                $data['end_date']
            );

            $this->validateWithinAcademicYear(
                $data['start_date'],
                $data['end_date'],
                $academicYear
            );

            $this->validateTermNumber(
                (int) $data['term_number'],
                $academicYear->id,
                $term->id
            );

            $this->validateName(
                $data['name'],
                $academicYear->id,
                $term->id
            );

            $isCurrent = (bool) ($data['is_current'] ?? false);

            if ($isCurrent) {
                Terms::where('school_id', $schoolId)
                    ->where('academic_year_id', $academicYear->id)
                    ->where('id', '!=', $term->id)
                    ->update([
                        'is_current' => false,
                    ]);
            }

            $term->update([
                'academic_year_id' => $academicYear->id,
                'name' => trim($data['name']),
                'term_number' => (int) $data['term_number'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'is_current' => $isCurrent,
                'is_active' => (bool) ($data['is_active'] ?? true),
            ]);

            return $term->fresh();
        });
    }

    /**
     * Delete a term.
     */
    public function delete(Terms $term): void
    {
        DB::transaction(function () use ($term) {

            $this->ensureSameSchool($term);

            /*
             * Do not allow deletion if the term is being
             * referenced by student enrollments.
             */
            if (
                DB::table('student_enrollments')
                    ->where('term_id', $term->id)
                    ->exists()
            ) {
                throw ValidationException::withMessages([
                    'term' =>
                        'This term cannot be deleted because it has student enrollments assigned to it.',
                ]);
            }

            $term->delete();
        });
    }

    /**
     * Set a term as current.
     */
    public function setCurrent(Terms $term): Terms
    {
        return DB::transaction(function () use ($term) {

            $schoolId = $this->schoolId();

            $this->ensureSameSchool($term);

            Terms::where('school_id', $schoolId)
                ->where('academic_year_id', $term->academic_year_id)
                ->where('id', '!=', $term->id)
                ->update([
                    'is_current' => false,
                ]);

            $term->update([
                'is_current' => true,
            ]);

            return $term->fresh();
        });
    }

    /**
     * Get an academic year belonging to the current school.
     */
    protected function getAcademicYear(
        int $academicYearId,
        int $schoolId
    ): AcademicYears {
        $academicYear = AcademicYears::query()
            ->where('id', $academicYearId)
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->first();

        if (!$academicYear) {
            throw ValidationException::withMessages([
                'academic_year_id' =>
                    'The selected academic year does not belong to your school or is inactive.',
            ]);
        }

        return $academicYear;
    }

    /**
     * Validate term dates.
     */
    protected function validateDates(
        string $startDate,
        string $endDate
    ): void {
        if ($endDate < $startDate) {
            throw ValidationException::withMessages([
                'end_date' =>
                    'The term end date must be after the start date.',
            ]);
        }
    }

    /**
     * Ensure term dates fall within the academic year.
     */
    protected function validateWithinAcademicYear(
        string $startDate,
        string $endDate,
        AcademicYears $academicYear
    ): void {
        $academicYearStart = $academicYear->start_date->format('Y-m-d');
        $academicYearEnd = $academicYear->end_date->format('Y-m-d');

        if ($startDate < $academicYearStart) {
            throw ValidationException::withMessages([
                'start_date' =>
                    'The term start date cannot be before the academic year start date.',
            ]);
        }

        if ($endDate > $academicYearEnd) {
            throw ValidationException::withMessages([
                'end_date' =>
                    'The term end date cannot be after the academic year end date.',
            ]);
        }
    }

    /**
     * Validate term number uniqueness.
     */
    protected function validateTermNumber(
        int $termNumber,
        int $academicYearId,
        ?int $ignoreId = null
    ): void {
        $query = Terms::query()
            ->where('academic_year_id', $academicYearId)
            ->where('term_number', $termNumber);

        if ($ignoreId !== null) {
            $query->where('id', '!=', $ignoreId);
        }

        if ($query->exists()) {
            throw ValidationException::withMessages([
                'term_number' =>
                    'This term number is already used in the selected academic year.',
            ]);
        }
    }

    /**
     * Validate term name uniqueness within academic year.
     */
    protected function validateName(
        string $name,
        int $academicYearId,
        ?int $ignoreId = null
    ): void {
        $query = Terms::query()
            ->where('academic_year_id', $academicYearId)
            ->where('name', trim($name));

        if ($ignoreId !== null) {
            $query->where('id', '!=', $ignoreId);
        }

        if ($query->exists()) {
            throw ValidationException::withMessages([
                'name' =>
                    'A term with this name already exists in the selected academic year.',
            ]);
        }
    }

    /**
     * Ensure the term belongs to the current school.
     */
    protected function ensureSameSchool(Terms $term): void
    {
        if ((int) $term->school_id !== $this->schoolId()) {
            abort(
                403,
                'You are not authorized to access this term.'
            );
        }
    }

    /**
     * Get current user's school ID.
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