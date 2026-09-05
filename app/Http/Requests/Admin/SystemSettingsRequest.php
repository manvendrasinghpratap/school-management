<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

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

            'grading_enabled' => [
                'nullable',
                'boolean',
            ],
        ];
    }
}