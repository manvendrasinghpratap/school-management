<?php

namespace App\Services;

use App\Models\AcademicYears;
use App\Models\Classes;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentEnrollment;
use App\Models\StudentPromotion;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StudentPromotionService
{
    /**
     * Create a pending student promotion.
     *
     * Promotion means:
     *
     * Current Academic Year -> Next Academic Year
     * Current Class         -> Immediate Next Class
     * Current Section       -> Selected Destination Section
     *
     * Example:
     *
     * 2026/2027 JSS 1-A
     *        ↓
     * 2027/2028 JSS 2-A
     *
     * Important:
     * - Source academic year is taken from the student's active enrollment.
     * - Source class is taken from the student's active enrollment.
     * - Source section is taken from the student's active enrollment.
     * - Browser-supplied source information is never trusted.
     * - Destination academic year must be the immediate next academic year.
     * - Destination class must be the immediate next class.
     * - Destination section is mandatory.
     */
    public function create(
        User $user,
        array $data
    ): StudentPromotion {
        $schoolId = $this->schoolId($user);

        return DB::transaction(function () use (
            $schoolId,
            $user,
            $data
        ) {
            /*
            |--------------------------------------------------------------------------
            | 1. Validate student
            |--------------------------------------------------------------------------
            */

            $student = Student::query()
                ->where('school_id', $schoolId)
                ->whereKey($data['student_id'] ?? null)
                ->where('status', 'active')
                ->first();

            if (!$student) {
                throw ValidationException::withMessages([
                    'student_id' => [
                        'The selected student is invalid.'
                    ],
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | 2. Find the student's active enrollment
            |--------------------------------------------------------------------------
            |
            | This is the authoritative source of:
            | - current academic year
            | - current class
            | - current section
            |
            */

            $activeEnrollments = StudentEnrollment::query()
                ->where('school_id', $schoolId)
                ->where('student_id', $student->id)
                ->where('status', 'active')
                ->with([
                    'academicYear',
                    'class.level',
                    'section',
                ])
                ->orderByDesc('enrollment_date')
                ->orderByDesc('id')
                ->lockForUpdate()
                ->get();

            if ($activeEnrollments->isEmpty()) {
                throw ValidationException::withMessages([
                    'student_id' => [
                        'The student does not have an active enrollment.'
                    ],
                ]);
            }

            if ($activeEnrollments->count() > 1) {
                throw ValidationException::withMessages([
                    'student_id' => [
                        'The student has multiple active enrollments. Resolve the enrollment records before creating a promotion.'
                    ],
                ]);
            }

            $currentEnrollment = $activeEnrollments->first();

            /*
            |--------------------------------------------------------------------------
            | 3. Source academic year
            |--------------------------------------------------------------------------
            |
            | The source year always comes from the active enrollment.
            |
            */

            $fromAcademicYear = AcademicYears::query()
                ->where('school_id', $schoolId)
                ->where('is_active', true)
                ->whereKey($currentEnrollment->academic_year_id)
                ->first();

            if (!$fromAcademicYear) {
                throw ValidationException::withMessages([
                    'academic_year_id' => [
                        'The student\'s current academic year is invalid or inactive.'
                    ],
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | 4. Optional browser academic year validation
            |--------------------------------------------------------------------------
            |
            | The current form may still submit academic_year_id.
            |
            | If supplied, it must match the student's actual current
            | enrollment year.
            |
            */

            if (!empty($data['academic_year_id'])) {
                if (
                    (int) $data['academic_year_id'] !==
                    (int) $fromAcademicYear->id
                ) {
                    throw ValidationException::withMessages([
                        'academic_year_id' => [
                            'The selected academic year does not match the student\'s current active enrollment.'
                        ],
                    ]);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | 5. Determine next academic year
            |--------------------------------------------------------------------------
            |
            | Promotion must move into the next academic year.
            |
            | Example:
            |
            | 2026/2027 -> 2027/2028
            |
            */

            $toAcademicYear = $this->getNextAcademicYear(
                $fromAcademicYear,
                $schoolId
            );

            if (!$toAcademicYear) {
                throw ValidationException::withMessages([
                    'academic_year_id' => [
                        'No next academic year is available for this promotion.'
                    ],
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | 6. Current class
            |--------------------------------------------------------------------------
            */

            $fromClass = Classes::query()
                ->where('school_id', $schoolId)
                ->where('is_active', true)
                ->with('level')
                ->find($currentEnrollment->class_id);

            if (!$fromClass) {
                throw ValidationException::withMessages([
                    'student_id' => [
                        'The student\'s current class is no longer available.'
                    ],
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | 7. Current section
            |--------------------------------------------------------------------------
            */

            $fromSection = null;

            if ($currentEnrollment->section_id) {
                $fromSection = Section::query()
                    ->where('class_id', $fromClass->id)
                    ->where('is_active', true)
                    ->find($currentEnrollment->section_id);

                if (!$fromSection) {
                    throw ValidationException::withMessages([
                        'student_id' => [
                            'The student\'s current section is no longer available.'
                        ],
                    ]);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | 8. Destination class
            |--------------------------------------------------------------------------
            */

            $toClass = Classes::query()
                ->where('school_id', $schoolId)
                ->where('is_active', true)
                ->with('level')
                ->find($data['to_class_id'] ?? null);

            if (!$toClass) {
                throw ValidationException::withMessages([
                    'to_class_id' => [
                        'The selected destination class is invalid.'
                    ],
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | 9. Destination class cannot equal source class
            |--------------------------------------------------------------------------
            */

            if ((int) $fromClass->id === (int) $toClass->id) {
                throw ValidationException::withMessages([
                    'to_class_id' => [
                        'The destination class must be different from the current class.'
                    ],
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | 10. Validate immediate class progression
            |--------------------------------------------------------------------------
            */

            $this->validateClassProgression(
                $fromClass,
                $toClass,
                $schoolId
            );

            /*
            |--------------------------------------------------------------------------
            | 11. Destination section is mandatory
            |--------------------------------------------------------------------------
            */

            if (empty($data['to_section_id'])) {
                throw ValidationException::withMessages([
                    'to_section_id' => [
                        'Please select a destination section.'
                    ],
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | 12. Validate destination section
            |--------------------------------------------------------------------------
            */

            $toSection = Section::query()
                ->where('class_id', $toClass->id)
                ->where('is_active', true)
                ->find($data['to_section_id']);

            if (!$toSection) {
                throw ValidationException::withMessages([
                    'to_section_id' => [
                        'The selected destination section is invalid for the destination class.'
                    ],
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | 13. Destination year must be different
            |--------------------------------------------------------------------------
            */

            if (
                (int) $fromAcademicYear->id ===
                (int) $toAcademicYear->id
            ) {
                throw ValidationException::withMessages([
                    'academic_year_id' => [
                        'The destination academic year must be different from the current academic year.'
                    ],
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | 14. Prevent duplicate pending/approved promotion
            |--------------------------------------------------------------------------
            |
            | Duplicate check is now based on:
            | - student
            | - from academic year
            | - to academic year
            | - destination class
            |
            */

            $existingPromotion = StudentPromotion::query()
                ->where('school_id', $schoolId)
                ->where('student_id', $student->id)
                ->where('from_academic_year_id', $fromAcademicYear->id)
                ->where('to_academic_year_id', $toAcademicYear->id)
                ->whereIn('status', ['pending', 'approved'])
                ->where('to_class_id', $toClass->id)
                ->exists();

            if ($existingPromotion) {
                throw ValidationException::withMessages([
                    'student_id' => [
                        'A pending or approved promotion already exists for this student for the selected destination academic year and class.'
                    ],
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | 15. Create promotion
            |--------------------------------------------------------------------------
            */

            $promotion = StudentPromotion::create([
                'school_id'             => $schoolId,
                'student_id'            => $student->id,

                'from_class_id'         => $fromClass->id,
                'to_class_id'           => $toClass->id,

                'from_section_id'       => $fromSection?->id,
                'to_section_id'         => $toSection->id,

                'from_academic_year_id' => $fromAcademicYear->id,
                'to_academic_year_id'   => $toAcademicYear->id,

                'promotion_date'        => $data['promotion_date']
                    ?? now()->toDateString(),

                'status'                => 'pending',

                'remarks'               => $data['remarks'] ?? null,

                'approved_by'           => null,
            ]);

            return $promotion->fresh([
                'student',
                'fromClass',
                'toClass',
                'fromSection',
                'toSection',
                'fromAcademicYear',
                'toAcademicYear',
            ]);
        });
    }

    /**
     * Approve a pending promotion.
     *
     * Approval:
     * - locks the promotion
     * - verifies the student still has exactly one active enrollment
     * - verifies the source enrollment has not changed
     * - verifies source academic year
     * - verifies destination academic year
     * - validates destination class
     * - validates destination section
     * - creates destination enrollment in the NEXT academic year
     * - completes previous enrollment
     * - marks promotion approved
     */
    public function approve(
        User $user,
        StudentPromotion $promotion
    ): StudentPromotion {
        $schoolId = $this->schoolId($user);

        return DB::transaction(function () use (
            $user,
            $schoolId,
            $promotion
        ) {
            /*
            |--------------------------------------------------------------------------
            | 1. Lock promotion
            |--------------------------------------------------------------------------
            */

            $promotion = StudentPromotion::query()
                ->where('school_id', $schoolId)
                ->whereKey($promotion->id)
                ->lockForUpdate()
                ->first();

            if (!$promotion) {
                throw ValidationException::withMessages([
                    'promotion' => [
                        'The promotion could not be found.'
                    ],
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | 2. Only pending promotions can be approved
            |--------------------------------------------------------------------------
            */

            if ($promotion->status !== 'pending') {
                throw ValidationException::withMessages([
                    'promotion' => [
                        'Only pending promotions can be approved.'
                    ],
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | 3. Validate source academic year
            |--------------------------------------------------------------------------
            */

            $fromAcademicYear = AcademicYears::query()
                ->where('school_id', $schoolId)
                ->where('is_active', true)
                ->find($promotion->from_academic_year_id);

            if (!$fromAcademicYear) {
                throw ValidationException::withMessages([
                    'promotion' => [
                        'The source academic year is no longer available.'
                    ],
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | 4. Validate destination academic year
            |--------------------------------------------------------------------------
            */

            $toAcademicYear = AcademicYears::query()
                ->where('school_id', $schoolId)
                ->where('is_active', true)
                ->find($promotion->to_academic_year_id);

            if (!$toAcademicYear) {
                throw ValidationException::withMessages([
                    'promotion' => [
                        'The destination academic year is no longer available.'
                    ],
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | 5. Verify destination is the next academic year
            |--------------------------------------------------------------------------
            */

            $expectedNextYear = $this->getNextAcademicYear(
                $fromAcademicYear,
                $schoolId
            );

            if (
                !$expectedNextYear ||
                (int) $expectedNextYear->id !==
                (int) $toAcademicYear->id
            ) {
                throw ValidationException::withMessages([
                    'promotion' => [
                        'The destination academic year is not the immediate next academic year.'
                    ],
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | 6. Load student
            |--------------------------------------------------------------------------
            */

            $student = Student::query()
                ->where('school_id', $schoolId)
                ->whereKey($promotion->student_id)
                ->where('status', 'active')
                ->first();

            if (!$student) {
                throw ValidationException::withMessages([
                    'promotion' => [
                        'The student associated with this promotion could not be found.'
                    ],
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | 7. Lock active enrollment
            |--------------------------------------------------------------------------
            */

            $activeEnrollments = StudentEnrollment::query()
                ->where('school_id', $schoolId)
                ->where('student_id', $student->id)
                ->where('status', 'active')
                ->with([
                    'academicYear',
                    'class.level',
                    'section',
                ])
                ->orderByDesc('enrollment_date')
                ->orderByDesc('id')
                ->lockForUpdate()
                ->get();

            if ($activeEnrollments->isEmpty()) {
                throw ValidationException::withMessages([
                    'promotion' => [
                        'The student no longer has an active enrollment.'
                    ],
                ]);
            }

            if ($activeEnrollments->count() > 1) {
                throw ValidationException::withMessages([
                    'promotion' => [
                        'The student has multiple active enrollments. Resolve the enrollment records before approving this promotion.'
                    ],
                ]);
            }

            $currentEnrollment = $activeEnrollments->first();

            /*
            |--------------------------------------------------------------------------
            | 8. Verify source enrollment academic year
            |--------------------------------------------------------------------------
            */

            if (
                (int) $currentEnrollment->academic_year_id !==
                (int) $promotion->from_academic_year_id
            ) {
                throw ValidationException::withMessages([
                    'promotion' => [
                        'The student\'s academic year has changed since this promotion was created.'
                    ],
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | 9. Verify current class
            |--------------------------------------------------------------------------
            */

            if (
                (int) $currentEnrollment->class_id !==
                (int) $promotion->from_class_id
            ) {
                throw ValidationException::withMessages([
                    'promotion' => [
                        'The student\'s current class has changed since this promotion was created.'
                    ],
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | 10. Verify current section
            |--------------------------------------------------------------------------
            */

            if (
                (int) ($currentEnrollment->section_id ?? 0) !==
                (int) ($promotion->from_section_id ?? 0)
            ) {
                throw ValidationException::withMessages([
                    'promotion' => [
                        'The student\'s current section has changed since this promotion was created.'
                    ],
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | 11. Destination class
            |--------------------------------------------------------------------------
            */

            $toClass = Classes::query()
                ->where('school_id', $schoolId)
                ->where('is_active', true)
                ->with('level')
                ->find($promotion->to_class_id);

            if (!$toClass) {
                throw ValidationException::withMessages([
                    'promotion' => [
                        'The destination class is no longer available.'
                    ],
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | 12. Revalidate class progression
            |--------------------------------------------------------------------------
            */

            $fromClass = Classes::query()
                ->where('school_id', $schoolId)
                ->where('is_active', true)
                ->with('level')
                ->find($currentEnrollment->class_id);

            if (!$fromClass) {
                throw ValidationException::withMessages([
                    'promotion' => [
                        'The student\'s current class is no longer available.'
                    ],
                ]);
            }

            $this->validateClassProgression(
                $fromClass,
                $toClass,
                $schoolId
            );

            /*
            |--------------------------------------------------------------------------
            | 13. Destination section
            |--------------------------------------------------------------------------
            */

            if (!$promotion->to_section_id) {
                throw ValidationException::withMessages([
                    'promotion' => [
                        'The promotion has no destination section. Edit the promotion and select a destination section before approval.'
                    ],
                ]);
            }

            $toSection = Section::query()
                ->where('class_id', $toClass->id)
                ->where('is_active', true)
                ->find($promotion->to_section_id);

            if (!$toSection) {
                throw ValidationException::withMessages([
                    'promotion' => [
                        'The destination section is no longer available.'
                    ],
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | 14. Prevent duplicate destination enrollment
            |--------------------------------------------------------------------------
            |
            | IMPORTANT:
            | We now check the DESTINATION academic year.
            |
            */

            $existingDestination = StudentEnrollment::query()
                ->where('school_id', $schoolId)
                ->where('student_id', $student->id)
                ->where('academic_year_id', $promotion->to_academic_year_id)
                ->where('class_id', $toClass->id)
                ->where('status', 'active')
                ->exists();

            if ($existingDestination) {
                throw ValidationException::withMessages([
                    'promotion' => [
                        'The student already has an active enrollment in the destination academic year and class.'
                    ],
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | 15. Prevent duplicate destination enrollment by section
            |--------------------------------------------------------------------------
            */

            $existingDestinationSection = StudentEnrollment::query()
                ->where('school_id', $schoolId)
                ->where('student_id', $student->id)
                ->where('academic_year_id', $promotion->to_academic_year_id)
                ->where('class_id', $toClass->id)
                ->where('section_id', $toSection->id)
                ->where('status', 'active')
                ->exists();

            if ($existingDestinationSection) {
                throw ValidationException::withMessages([
                    'promotion' => [
                        'The student already has an active enrollment in the selected destination class and section for the destination academic year.'
                    ],
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | 16. Generate enrollment number
            |--------------------------------------------------------------------------
            */

            $enrollmentNumber = $this->generateEnrollmentNumber(
                $schoolId
            );

            /*
            |--------------------------------------------------------------------------
            | 17. Create destination enrollment
            |--------------------------------------------------------------------------
            |
            | IMPORTANT:
            | The new enrollment belongs to:
            |
            | promotion->to_academic_year_id
            |
            | NOT:
            |
            | promotion->from_academic_year_id
            |
            */

            $newEnrollment = StudentEnrollment::create([
                'school_id'         => $schoolId,
                'student_id'        => $student->id,

                'academic_year_id'  => $promotion->to_academic_year_id,

                /*
                 * A promotion into a new academic year should normally
                 * begin with no term from the previous year.
                 *
                 * Therefore we deliberately set this to null.
                 */
                'term_id'           => null,

                'class_id'          => $toClass->id,
                'section_id'        => $toSection->id,

                'enrollment_number' => $enrollmentNumber,

                'enrollment_date'   => $promotion->promotion_date,

                'status'            => 'active',

                'notes'             => 'Created through approved student promotion.',

                'created_by'        => $user->id,
                'updated_by'        => $user->id,
            ]);

            /*
            |--------------------------------------------------------------------------
            | 18. Complete previous enrollment
            |--------------------------------------------------------------------------
            */

            $currentEnrollment->update([
                'status'     => 'completed',
                'updated_by' => $user->id,
            ]);

            /*
            |--------------------------------------------------------------------------
            | 19. Mark promotion approved
            |--------------------------------------------------------------------------
            */

            $promotion->update([
                'status'      => 'approved',
                'approved_by' => $user->id,
            ]);

            /*
            |--------------------------------------------------------------------------
            | 20. Return fresh promotion
            |--------------------------------------------------------------------------
            */

            return $promotion->fresh([
                'student',
                'fromClass',
                'toClass',
                'fromSection',
                'toSection',
                'fromAcademicYear',
                'toAcademicYear',
                'approvedBy',
            ]);
        });
    }

    /**
     * Reject a pending promotion.
     */
    public function reject(
    User $user,
    StudentPromotion $promotion,
    ?string $remarks = null
): StudentPromotion {
    if ($promotion->status !== 'pending') {
        throw ValidationException::withMessages([
            'promotion' => 'Only pending promotions can be rejected.',
        ]);
    }

    if ((int) $promotion->school_id !== (int) $user->school_id) {
        throw ValidationException::withMessages([
            'promotion' => 'The selected promotion is invalid.',
        ]);
    }

    $promotion->update([
        'status' => 'rejected',
        'approved_by' => null,
        'rejected_by' => $user->id,
        'remarks' => $remarks ?? $promotion->remarks,
    ]);

    return $promotion->fresh();
}

    /**
     * Delete a promotion.
     *
     * student_promotions has no deleted_at column,
     * therefore this is a hard delete.
     *
     * Approved promotions cannot be deleted because they are
     * part of the student's academic history.
     */
    public function delete(
        User $user,
        StudentPromotion $promotion
    ): bool {
        $schoolId = $this->schoolId($user);

        if ((int) $promotion->school_id !== $schoolId) {
            throw ValidationException::withMessages([
                'promotion' => [
                    'You cannot delete a promotion belonging to another school.'
                ],
            ]);
        }

        if ($promotion->status === 'approved') {
            throw ValidationException::withMessages([
                'promotion' => [
                    'An approved promotion cannot be deleted because it is part of the student academic history.'
                ],
            ]);
        }

        return (bool) $promotion->delete();
    }

    /**
     * Get the immediate next academic year.
     *
     * Example:
     *
     * 2025/2026 -> 2026/2027
     * 2026/2027 -> 2027/2028
     *
     * The next year is determined by the earliest active academic year
     * whose start_date is after the source year's start_date.
     */
    public function getNextAcademicYear(
        AcademicYears $fromAcademicYear,
        int $schoolId
    ): ?AcademicYears {
        return AcademicYears::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->where('start_date', '>', $fromAcademicYear->start_date)
            ->orderBy('start_date', 'asc')
            ->first();
    }

    /**
     * Validate that destination class is exactly one class
     * after the source class.
     *
     * Supports cross-level progression:
     *
     * Primary 1 -> Primary 2
     * Primary 2 -> Primary 3
     * Primary 3 -> JSS 1
     * JSS 1     -> JSS 2
     * JSS 2     -> JSS 3
     * JSS 3     -> SSS 1
     */
    public function validateClassProgression(
        Classes $fromClass,
        Classes $toClass,
        int $schoolId
    ): void {
        $classes = Classes::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->with('level')
            ->get();

        if ($classes->isEmpty()) {
            throw ValidationException::withMessages([
                'to_class_id' => [
                    'No active classes are available for progression validation.'
                ],
            ]);
        }

        $orderedClasses = $this->orderClassesForProgression($classes);

        $fromIndex = $orderedClasses->search(
            fn (Classes $class) =>
                (int) $class->id === (int) $fromClass->id
        );

        $toIndex = $orderedClasses->search(
            fn (Classes $class) =>
                (int) $class->id === (int) $toClass->id
        );

        if ($fromIndex === false) {
            throw ValidationException::withMessages([
                'to_class_id' => [
                    'The student\'s current class could not be found in the active class progression.'
                ],
            ]);
        }

        if ($toIndex === false) {
            throw ValidationException::withMessages([
                'to_class_id' => [
                    'The selected destination class could not be found in the active class progression.'
                ],
            ]);
        }

        if ($toIndex !== ($fromIndex + 1)) {
            throw ValidationException::withMessages([
                'to_class_id' => [
                    'The student can only be promoted to the immediate next class.'
                ],
            ]);
        }
    }

    /**
     * Order active classes according to academic hierarchy.
     */
    private function orderClassesForProgression(
        Collection $classes
    ): Collection {
        return $classes
            ->sortBy(function (Classes $class) {
                $levelRank = $this->levelRank($class);
                $classNumber = $this->classNumber($class);

                return ($levelRank * 1000) + $classNumber;
            })
            ->values();
    }

    /**
     * Determine academic level order.
     */
    private function levelRank(Classes $class): int
    {
        $class->loadMissing('level');

        $levelName = strtolower(
            trim((string) ($class->level?->name ?? ''))
        );

        $levelCode = strtolower(
            trim((string) ($class->level?->code ?? ''))
        );

        $level = $levelCode ?: $levelName;

        return match (true) {
            str_contains($level, 'early'),
            str_contains($level, 'nursery'),
            str_contains($level, 'kindergarten'),
            $level === 'ey'
                => 1,

            str_contains($level, 'primary'),
            $level === 'pri'
                => 2,

            str_contains($level, 'junior'),
            str_contains($level, 'jss')
                => 3,

            str_contains($level, 'senior'),
            str_contains($level, 'sss'),
            $level === 'ss'
                => 4,

            default => throw ValidationException::withMessages([
                'to_class_id' => [
                    'Unable to determine the academic level for one of the classes involved in the promotion.'
                ],
            ]),
        };
    }

    /**
     * Extract numeric class number.
     *
     * Examples:
     *
     * Primary 1 = 1
     * Primary 2 = 2
     * Primary 3 = 3
     * JSS 1     = 1
     * JSS 2     = 2
     * JSS 3     = 3
     * SS 1      = 1
     */
    private function classNumber(Classes $class): int
    {
        if (!preg_match('/\d+/', (string) $class->name, $matches)) {
            throw ValidationException::withMessages([
                'to_class_id' => [
                    'Unable to determine the class number for one of the classes involved in the promotion.'
                ],
            ]);
        }

        $classNumber = (int) $matches[0];

        if ($classNumber <= 0) {
            throw ValidationException::withMessages([
                'to_class_id' => [
                    'One of the selected classes has an invalid class number.'
                ],
            ]);
        }

        return $classNumber;
    }

    /**
     * Generate a unique enrollment number.
     */
    private function generateEnrollmentNumber(
        int $schoolId
    ): string {
        do {
            $number = sprintf(
                'ENR-%d-%s-%d',
                $schoolId,
                now()->format('YmdHis'),
                random_int(100, 999)
            );
        } while (
            StudentEnrollment::withTrashed()
                ->where('enrollment_number', $number)
                ->exists()
        );

        return $number;
    }

    /**
     * Get authenticated user's school ID.
     */
    private function schoolId(User $user): int
    {
        if (!$user->school_id) {
            throw ValidationException::withMessages([
                'school' => [
                    'No school is assigned to the current user.'
                ],
            ]);
        }

        return (int) $user->school_id;
    }
}