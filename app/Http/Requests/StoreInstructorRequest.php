<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInstructorRequest extends FormRequest
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
        $schoolId = auth()->user()?->school_id;

        return [
            'staff_id' => [
                'required',
                'integer',
                Rule::exists('staff', 'id')->where(
                    fn ($query) => $query->where('school_id', $schoolId)
                ),
            ],

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
            'staff_id.required' =>
                'Please select a staff member.',

            'staff_id.exists' =>
                'The selected staff member does not belong to this school.',

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
