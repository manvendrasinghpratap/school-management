<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SystemSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('settings.update') ?? false;
    }

    public function rules(): array
    {
        return [
            'timezone' => [
                'required',
                'timezone',
            ],

            'date_format' => [
                'required',
                'string',
                'max:50',
            ],

            'currency' => [
                'required',
                'string',
                'max:10',
            ],

            'language' => [
                'required',
                'string',
                'max:10',
            ],

            'attendance_enabled' => [
                'nullable',
                'boolean',
            ],

            'attendance_mode' => [
                'nullable',
                Rule::in([
                    'daily',
                    'subject',
                    'both',
                ]),
            ],
            'attendance_allow_late' => [    
                'nullable',
                'boolean',
            ], 

            'attendance_allow_excused' => [
                'nullable',
                'boolean',     
            ], 

            'grading_enabled' => [
                'nullable',
                'boolean',
            ],
        ];
    }
}