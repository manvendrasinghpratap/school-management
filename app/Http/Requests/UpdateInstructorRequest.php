<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInstructorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check()
            && auth()->user()->school_id !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'specialization' => [
                'nullable',
                'string',
                'max:255',
            ],

            'qualification' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'specialization.string' =>
                'Specialization must be a valid text value.',

            'specialization.max' =>
                'Specialization may not exceed 255 characters.',

            'qualification.string' =>
                'Qualification must be a valid text value.',

            'qualification.max' =>
                'Qualification may not exceed 1000 characters.',
        ];
    }
}

