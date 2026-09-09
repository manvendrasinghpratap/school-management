<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentPromotionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [

            'academic_year_id' => [
                'required',
                'integer',
                'exists:academic_years,id',
            ],

            'from_class_id' => [
                'required',
                'integer',
                'exists:classes,id',
            ],

            'from_section_id' => [
                'nullable',
                'integer',
                'exists:sections,id',
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

            'status' => [
                'required',
                'in:pending,rejected',
            ],

            'remarks' => [
                'nullable',
                'string',
                'max:5000',
            ],

        ];
    }
}