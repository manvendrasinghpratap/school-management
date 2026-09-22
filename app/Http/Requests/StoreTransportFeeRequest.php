<?php

namespace App\Http\Requests;

// use App\Models\Invoice;
use App\Models\RouteStudent;
use App\Services\AcademicHierarchyService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTransportFeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check()
            && (bool) auth()->user()?->school_id;
    }

    public function rules(): array
    {
        $schoolId = (int) auth()->user()->school_id;

        return [
            /*
            |--------------------------------------------------------------------------
            | Academic Hierarchy
            |--------------------------------------------------------------------------
            */

            'academic_year_id' => [
                'required',
                'integer',
                Rule::exists('academic_years', 'id')->where(
                    fn ($query) => $query
                        ->where('school_id', $schoolId)
                        ->where('is_active', 1)
                        ->whereNull('deleted_at')
                ),
            ],

            'class_id' => [
                'required',
                'integer',
                Rule::exists('classes', 'id')->where(
                    fn ($query) => $query
                        ->where('school_id', $schoolId)
                        ->where('is_active', 1)
                        ->whereNull('deleted_at')
                ),
            ],

            /*
             * IMPORTANT:
             *
             * sections does NOT contain school_id.
             *
             * School ownership is established through:
             *
             * classes.school_id
             *       ↓
             * classes.id = sections.class_id
             *
             * The complete hierarchy is subsequently validated by
             * AcademicHierarchyService::validateStudentHierarchy().
             */
            'section_id' => [
                'required',
                'integer',
                Rule::exists('sections', 'id')->where(
                    fn ($query) => $query
                        ->where('class_id', $this->input('class_id'))
                        ->where('is_active', 1)
                        ->whereNull('deleted_at')
                ),
            ],

            'student_id' => [
                'required',
                'integer',
                Rule::exists('students', 'id')->where(
                    fn ($query) => $query
                        ->where('school_id', $schoolId)
                        ->where('status', 'active')
                        ->whereNull('deleted_at')
                ),
            ],

            /*
            |--------------------------------------------------------------------------
            | Transport Assignment
            |--------------------------------------------------------------------------
            */

            'route_student_id' => [
                'required',
                'integer',
                Rule::exists('route_students', 'id')->where(
                    fn ($query) => $query
                        ->where('school_id', $schoolId)
                        ->whereNull('deleted_at')
                ),
            ],

            /*
            |--------------------------------------------------------------------------
            | Fee
            |--------------------------------------------------------------------------
            */

            'fee_month' => [
                'required',
                'date',
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0.01',
                'max:999999999.99',
            ],

            'status' => [
                'required',
                Rule::in([
                    'pending',
                    'invoiced',
                    'paid',
                    'waived',
                ]),
            ],

            /*
            |--------------------------------------------------------------------------
            | Optional Invoice
            |--------------------------------------------------------------------------
            */

            'invoice_id' => [
                'nullable',
                'integer',
                Rule::exists('invoices', 'id')->where(
                    fn ($query) => $query
                        ->where('school_id', $schoolId)
                        ->whereNull('deleted_at')
                ),
            ],

            'notes' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ];
    }

    /**
     * Validate relationships that cannot be checked by the
     * individual field validation rules.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $schoolId = (int) auth()->user()->school_id;

            /*
            |--------------------------------------------------------------------------
            | 1. Validate complete Academic Hierarchy
            |--------------------------------------------------------------------------
            |
            | Academic Year
            |      ↓
            | Class
            |      ↓
            | Section
            |      ↓
            | Student
            |
            | StudentEnrollment is the final source of truth.
            |
            */

            try {
                app(AcademicHierarchyService::class)
                    ->validateStudentHierarchy(
                        (int) $this->input('academic_year_id'),
                        (int) $this->input('class_id'),
                        (int) $this->input('section_id'),
                        (int) $this->input('student_id')
                    );
            } catch (\Throwable $e) {
                $validator->errors()->add(
                    'student_id',
                    'The selected student does not belong to the selected academic year, class and section.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | 2. Validate Route Assignment belongs to Student
            |--------------------------------------------------------------------------
            */

            $assignment = RouteStudent::query()
                ->where('id', (int) $this->input('route_student_id'))
                ->where('school_id', $schoolId)
                ->whereNull('deleted_at')
                ->first();

            if (! $assignment) {
                $validator->errors()->add(
                    'route_student_id',
                    'The selected route assignment was not found.'
                );

                return;
            }

            if (
                (int) $assignment->student_id
                !== (int) $this->input('student_id')
            ) {
                $validator->errors()->add(
                    'route_student_id',
                    'The selected route assignment does not belong to the selected student.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | 3. Validate Optional Invoice belongs to Current School
            |--------------------------------------------------------------------------
            */

            // if ($this->filled('invoice_id')) {
            //     $invoice = Invoice::query()
            //         ->where('id', (int) $this->input('invoice_id'))
            //         ->where('school_id', $schoolId)
            //         ->whereNull('deleted_at')
            //         ->first();

            //     if (! $invoice) {
            //         $validator->errors()->add(
            //             'invoice_id',
            //             'The selected invoice does not belong to this school.'
            //         );
            //     }
            // }
        });
    }
}