<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStudentRequest extends FormRequest
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
            'student_number' => [
                'required',
                'string',
                'max:100',
                Rule::unique('students', 'student_number')
                    ->where(fn ($query) => $query->where(
                        'school_id',
                        $schoolId
                    )),
            ],

            'admission_number' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('students', 'admission_number')
                    ->where(fn ($query) => $query->where(
                        'school_id',
                        $schoolId
                    )),
            ],

            'first_name' => [
                'required',
                'string',
                'max:100',
            ],

            'middle_name' => [
                'nullable',
                'string',
                'max:100',
            ],

            'last_name' => [
                'required',
                'string',
                'max:100',
            ],

            'photo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'date_of_birth' => [
                'nullable',
                'date',
                'before:today',
            ],

            'nationality' => [
                'nullable',
                'string',
                'max:100',
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
                'max:30',
            ],

            'admission_date' => [
                'required',
                'date',
            ],

            'status' => [
                'nullable',
                Rule::in([
                    'active',
                    'inactive',
                    'graduated',
                    'transferred',
                    'withdrawn',
                ]),
            ],

            'address' => [
                'nullable',
                'string',
                'max:2000',
            ],

            /*
            |--------------------------------------------------------------------------
            | Guardians
            |--------------------------------------------------------------------------
            */

            'guardians' => [
                'nullable',
                'array',
            ],

            'guardians.*' => [
                'required',
                'array',
            ],

            'guardians.*.id' => [
                'required',
                'integer',
                'distinct',
                Rule::exists('guardians', 'id')
                    ->where(fn ($query) => $query->where(
                        'school_id',
                        $schoolId
                    )),
            ],

            'guardians.*.relationship' => [
                'required',
                'string',
                'max:100',
            ],

            'guardians.*.is_primary' => [
                'nullable',
                'boolean',
            ],

            'guardians.*.is_emergency_contact' => [
                'nullable',
                'boolean',
            ],
        ];
    }
}