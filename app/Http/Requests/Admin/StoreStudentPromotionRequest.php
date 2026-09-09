<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentPromotionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [

            /*
             * Academic year selected on the promotion form.
             *
             * The service will additionally verify that this
             * belongs to the authenticated user's school and
             * matches the student's active enrollment.
             */
            'academic_year_id' => [
                'required',
                'integer',
                'exists:academic_years,id',
            ],

            'student_id' => [
                'required',
                'integer',
                'exists:students,id',
            ],

            'to_class_id' => [
                'required',
                'integer',
                'exists:classes,id',
            ],

            'to_section_id' => [
                'required',
                'integer',
                'exists:sections,id',
            ],

            'promotion_date' => [
                'required',
                'date',
            ],

            'remarks' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ];
    }

    public function messages(): array
    {
        return [

            'academic_year_id.required' =>
                'Please select an academic year.',

            'academic_year_id.exists' =>
                'The selected academic year is invalid.',

            'student_id.required' =>
                'Please select a student.',

            'to_class_id.required' =>
                'Please select the destination class.',

            'to_section_id.required' =>
                'Please select the destination section.',

            'promotion_date.required' =>
                'Please enter the promotion date.',
        ];
    }
}