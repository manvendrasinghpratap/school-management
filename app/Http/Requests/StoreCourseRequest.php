<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check()
            && auth()->user()->school_id !== null;
    }

    public function rules(): array
    {
        $schoolId = (int) auth()->user()->school_id;

        return [
            'department_id' => [
                'nullable',
                'integer',
                Rule::exists('departments', 'id')
                    ->where(fn ($query) => $query->where('school_id', $schoolId)),
            ],

            'course_code' => [
                'required',
                'string',
                'max:100',
                Rule::unique('courses', 'course_code')
                    ->where(fn ($query) => $query->where('school_id', $schoolId)),
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'credit_hours' => [
                'nullable',
                'numeric',
                'min:0',
                'max:999.99',
            ],

            'is_compulsory' => [
                'nullable',
                'boolean',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ];
    }
}