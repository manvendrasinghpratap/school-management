<?php

namespace App\Http\Requests;

use App\Services\AcademicHierarchyService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreHostelAllocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && (bool) auth()->user()?->school_id;
    }

    public function rules(): array
    {
        $schoolId = (int) auth()->user()->school_id;

        return [
            'academic_year_id' => [
                'required',
                'integer',
                Rule::exists('academic_years', 'id')->where(
                    fn ($q) => $q
                        ->where('school_id', $schoolId)
                        ->where('is_active', 1)
                        ->whereNull('deleted_at')
                ),
            ],
            'class_id' => [
                'required',
                'integer',
                Rule::exists('classes', 'id')->where(
                    fn ($q) => $q
                        ->where('school_id', $schoolId)
                        ->where('is_active', 1)
                        ->whereNull('deleted_at')
                ),
            ],
            'section_id' => [
                'required',
                'integer',
                Rule::exists('sections', 'id')->where(
                    fn ($q) => $q
                        ->where('class_id', $this->input('class_id'))
                        ->where('is_active', 1)
                        ->whereNull('deleted_at')
                ),
            ],
            'student_id' => [
                'required',
                'integer',
                Rule::exists('students', 'id')->where(
                    fn ($q) => $q
                        ->where('school_id', $schoolId)
                        ->where('status', 'active')
                        ->whereNull('deleted_at')
                ),
            ],
            'hostel_id' => [
                'required',
                'integer',
                Rule::exists('hostels', 'id')->where(
                    fn ($q) => $q
                        ->where('school_id', $schoolId)
                        ->where('status', 'active')
                        ->whereNull('deleted_at')
                ),
            ],
            'room_id' => [
                'required',
                'integer',
                Rule::exists('hostel_rooms', 'id')->where(
                    fn ($q) => $q
                        ->where('school_id', $schoolId)
                        ->whereNull('deleted_at')
                ),
            ],
            'bed_id' => [
                'nullable',
                'integer',
                Rule::exists('hostel_beds', 'id')->where(
                    fn ($q) => $q
                        ->where('school_id', $schoolId)
                        ->whereNull('deleted_at')
                ),
            ],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'monthly_fee' => ['nullable', 'numeric', 'min:0', 'max:999999999.99'],
            'status' => [
                'required',
                Rule::in(['allocated', 'checked_in']),
            ],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            try {
                app(AcademicHierarchyService::class)->validateStudentHierarchy(
                    (int) $this->input('academic_year_id'),
                    (int) $this->input('class_id'),
                    (int) $this->input('section_id'),
                    (int) $this->input('student_id')
                );
            } catch (\Throwable) {
                $validator->errors()->add(
                    'student_id',
                    'The selected student is not actively enrolled in the selected academic year, class and section.'
                );
            }
        });
    }
}
