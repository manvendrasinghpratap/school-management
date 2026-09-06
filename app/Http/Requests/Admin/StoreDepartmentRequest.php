<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDepartmentRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('departments', 'name')
                    ->where(fn ($query) => $query->where('school_id', $schoolId)),
            ],

            'code' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('departments', 'code')
                    ->where(fn ($query) => $query->where('school_id', $schoolId)),
            ],

            'description' => [
                'nullable',
                'string',
                'max:5000',
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
            'name.required' => 'Department name is required.',
            'name.unique' => 'This department name already exists in your school.',

            'code.unique' => 'This department code already exists in your school.',

            'description.max' => 'Description may not exceed 5000 characters.',

            'is_active.boolean' => 'Department status must be active or inactive.',
        ];
    }
}