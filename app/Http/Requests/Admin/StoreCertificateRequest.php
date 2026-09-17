<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreCertificateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && (bool) auth()->user()->school_id;
    }

    public function rules(): array
    {
        return [
            'template_id' => [
                'required',
                'integer',
                'exists:certificate_templates,id',
            ],

            'student_id' => [
                'required',
                'integer',
                'exists:students,id',
            ],

            'issue_date' => [
                'required',
                'date',
            ],

            'course_or_class' => [
                'nullable',
                'string',
                'max:255',
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ];
    }
}