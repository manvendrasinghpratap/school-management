<?php

namespace App\Http\Requests;

use App\Services\AcademicHierarchyService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class StoreRouteStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && (bool) auth()->user()?->school_id;
    }

    public function rules(): array
    {
        $sid = (int) auth()->user()->school_id;

        return [
            'academic_year_id' => [
                'required',
                'integer',
                'min:1',
                Rule::exists('academic_years', 'id')
                    ->where(fn ($q) => $q
                        ->where('school_id', $sid)
                        ->where('is_active', 1)),
            ],

            'class_id' => [
                'required',
                'integer',
                'min:1',
                Rule::exists('classes', 'id')
                    ->where(fn ($q) => $q
                        ->where('school_id', $sid)
                        ->where('is_active', 1)),
            ],

            'section_id' => [
                'required',
                'integer',
                'min:1',
                Rule::exists('sections', 'id')
                    ->where(fn ($q) => $q
                        ->where('class_id', $this->input('class_id'))
                        ->where('is_active', 1)),
            ],

            'route_id' => [
                'required',
                'integer',
                'min:1',
                Rule::exists('transport_routes', 'id')
                    ->where(fn ($q) => $q->where('school_id', $sid)),
            ],

            'student_id' => [
                'required',
                'integer',
                'min:1',
                Rule::exists('students', 'id')
                    ->where(fn ($q) => $q
                        ->where('school_id', $sid)
                        ->where('status', 'active')),
            ],

            'pickup_point' => [
                'nullable',
                'string',
                'max:255',
            ],

            'dropoff_point' => [
                'nullable',
                'string',
                'max:255',
            ],

            'start_date' => [
                'nullable',
                'date',
            ],

            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],

            'status' => [
                'required',
                Rule::in(['active', 'inactive']),
            ],
        ];
    }

    protected function withValidator($validator): void
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
            } catch (ValidationException $e) {
                foreach ($e->errors() as $field => $messages) {
                    foreach ((array) $messages as $message) {
                        $validator->errors()->add($field, $message);
                    }
                }
            } catch (ModelNotFoundException) {
                $validator->errors()->add(
                    'student_id',
                    'The selected academic year, class, section and student combination is invalid.'
                );
            }
        });
    }
}
