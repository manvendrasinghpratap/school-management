<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAcademicYearRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check()
            && auth()->user()->school_id !== null;
    }

    public function rules(): array
    {
        $schoolId = auth()->user()?->school_id;

        return [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('academic_years', 'name')
                    ->where(fn ($query) => $query->where('school_id', $schoolId)),
            ],

            'start_date' => [
                'required',
                'date',
            ],

            'end_date' => [
                'required',
                'date',
                'after:start_date',
            ],

            'is_current' => [
                'nullable',
                'boolean',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' =>
                'Please enter the academic year name.',

            'name.max' =>
                'The academic year name may not exceed 100 characters.',

            'name.unique' =>
                'This academic year already exists for this school.',

            'start_date.required' =>
                'Please select the academic year start date.',

            'start_date.date' =>
                'The start date must be a valid date.',

            'end_date.required' =>
                'Please select the academic year end date.',

            'end_date.date' =>
                'The end date must be a valid date.',

            'end_date.after' =>
                'The end date must be after the start date.',
        ];
    }
}