<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTermRequest extends FormRequest
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
            'academic_year_id' => [
                'required',
                'integer',
                Rule::exists('academic_years', 'id')
                    ->where(fn ($query) => $query
                        ->where('school_id', $schoolId)
                        ->where('is_active', true)
                    ),
            ],

            'name' => [
                'required',
                'string',
                'max:100',
            ],

            'term_number' => [
                'required',
                'integer',
                'min:1',
                'max:10',
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
            'academic_year_id.required' =>
                'Please select an academic year.',

            'academic_year_id.exists' =>
                'The selected academic year is invalid or does not belong to your school.',

            'name.required' =>
                'Please enter the term name.',

            'term_number.required' =>
                'Please enter the term number.',

            'term_number.min' =>
                'The term number must be at least 1.',

            'term_number.max' =>
                'The term number cannot be greater than 10.',

            'start_date.required' =>
                'Please select the term start date.',

            'end_date.required' =>
                'Please select the term end date.',

            'end_date.after' =>
                'The term end date must be after the start date.',
        ];
    }
}