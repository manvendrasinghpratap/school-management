<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('student-courses.create') ?? false;
    }

    public function rules(): array
    {
        return [
            'academic_year_id' => [
                'required',
                'integer',
                'exists:academic_years,id',
            ],

            'term_id' => [
                'nullable',
                'integer',
                'exists:terms,id',
            ],

            'class_id' => [
                'required',
                'integer',
                'exists:classes,id',
            ],

            'section_id' => [
                'nullable',
                'integer',
                'exists:sections,id',
            ],

            'student_id' => [
                'required',
                'integer',
                'exists:students,id',
            ],

            'course_id' => [
                'required',
                'integer',
                'exists:courses,id',
            ],

            'status' => [
                'required',
                'in:enrolled,completed,dropped',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'academic_year_id.required' =>
                'Please select an academic year.',

            'academic_year_id.exists' =>
                'The selected academic year does not exist.',

            'term_id.exists' =>
                'The selected term does not exist.',

            'class_id.required' =>
                'Please select a class.',

            'class_id.exists' =>
                'The selected class does not exist.',

            'section_id.exists' =>
                'The selected section does not exist.',

            'student_id.required' =>
                'Please select a student.',

            'student_id.exists' =>
                'The selected student does not exist.',

            'course_id.required' =>
                'Please select a course.',

            'course_id.exists' =>
                'The selected course does not exist.',

            'status.required' =>
                'Please select a registration status.',

            'status.in' =>
                'The selected registration status is invalid.',
        ];
    }
}