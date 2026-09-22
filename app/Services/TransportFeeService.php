<?php

namespace App\Services;

use App\Models\RouteStudent;
use App\Models\TransportFee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TransportFeeService
{
    public function __construct(
        protected AcademicHierarchyService $academicHierarchy,
    ) {
    }

    private function schoolId(): int
    {
        abort_unless(
            auth()->user()?->school_id,
            403,
            'No school is assigned to the current user.'
        );

        return (int) auth()->user()->school_id;
    }

    public function save(
        array $data,
        ?TransportFee $fee = null
    ): TransportFee {
        $schoolId = $this->schoolId();

        return DB::transaction(function () use ($data, $fee, $schoolId) {
            $assignment = RouteStudent::query()
                ->where('id', $data['route_student_id'])
                ->where('school_id', $schoolId)
                ->whereNull('deleted_at')
                ->firstOrFail();

            if ($fee && (int) $fee->school_id !== $schoolId) {
                abort(404);
            }

            if ((int) $assignment->student_id !== (int) $data['student_id']) {
                throw ValidationException::withMessages([
                    'route_student_id' => 'The selected route assignment does not belong to the selected student.',
                ]);
            }

            $this->academicHierarchy->validateStudentHierarchy(
                (int) $data['academic_year_id'],
                (int) $data['class_id'],
                (int) $data['section_id'],
                (int) $data['student_id']
            );

            // Prevent duplicate fees for the same route assignment and month.
            $duplicate = TransportFee::query()
                ->where('school_id', $schoolId)
                ->where('route_student_id', $assignment->id)
                ->whereDate('fee_month', $data['fee_month'])
                ->when(
                    $fee,
                    fn ($query) => $query->where('id', '!=', $fee->id)
                )
                ->whereNull('deleted_at')
                ->exists();

            if ($duplicate) {
                throw ValidationException::withMessages([
                    'fee_month' => 'A transport fee already exists for this route assignment and month.',
                ]);
            }

            // These fields are form-only and are not stored in transport_fees.
            unset(
                $data['academic_year_id'],
                $data['class_id'],
                $data['section_id'],
                $data['student_id']
            );

            $data['school_id'] = $schoolId;

            if ($fee) {
                $fee->fill($data);
                $fee->save();

                return $fee->fresh();
            }

            return TransportFee::create($data);
        });
    }

    public function delete(TransportFee $fee): void
    {
        if ((int) $fee->school_id !== $this->schoolId()) {
            abort(404);
        }

        $fee->delete();
    }
}
