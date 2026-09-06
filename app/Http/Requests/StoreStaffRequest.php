<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStaffRequest extends FormRequest
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
            'user_id' => [
                'nullable',
                'integer',
                Rule::exists('users', 'id')
                    ->where(fn ($query) => $query->where(
                        'school_id',
                        $schoolId
                    )),
            ],

            'department_id' => [
                'nullable',
                'integer',
                Rule::exists('departments', 'id')
                    ->where(fn ($query) => $query->where(
                        'school_id',
                        $schoolId
                    )),
            ],

            'staff_number' => [
                'required',
                'string',
                'max:100',
                Rule::unique('staff', 'staff_number'),
            ],

            'first_name' => [
                'required',
                'string',
                'max:255',
            ],

            'middle_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'last_name' => [
                'required',
                'string',
                'max:255',
            ],

            'photo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'date_of_birth' => [
                'nullable',
                'date',
                'before:today',
            ],

            'nationality' => [
                'nullable',
                'string',
                'max:255',
            ],

            'gender' => [
                'nullable',
                Rule::in([
                    'male',
                    'female',
                    'other',
                ]),
            ],

            'phone' => [
                'nullable',
                'string',
                'max:50',
            ],

            'staff_type' => [
                'nullable',
                'string',
                'max:100',
            ],

            'employment_date' => [
                'nullable',
                'date',
            ],

            'status' => [
                'required',
                Rule::in([
                    'active',
                    'inactive',
                    'terminated',
                ]),
            ],
        ];
    }
}