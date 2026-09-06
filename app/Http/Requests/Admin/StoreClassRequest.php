<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClassRequest extends FormRequest
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
                    ->where(
                        fn ($query) => $query->where(
                            'school_id',
                            $schoolId
                        )
                    ),
            ],

            'level_id' => [
                'nullable',
                'integer',
                'exists:levels,id',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'code' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('classes', 'code')
                    ->where(
                        fn ($query) => $query->where(
                            'school_id',
                            $schoolId
                        )
                    ),
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
            'department_id.exists' =>
                'The selected department does not belong to your school.',

            'level_id.exists' =>
                'The selected level does not exist.',

            'name.required' =>
                'Please enter the class name.',

            'code.unique' =>
                'This class code is already in use in your school.',
        ];
    }
}