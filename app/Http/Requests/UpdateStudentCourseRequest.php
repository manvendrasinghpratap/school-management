<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('student-courses.update') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     */
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

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'academic_year_id.required' =>
                'Please select an academic year.',

            'academic_year_id.integer' =>
                'The academic year selection is invalid.',

            'academic_year_id.exists' =>
                'The selected academic year does not exist.',


            'term_id.integer' =>
                'The term selection is invalid.',

            'term_id.exists' =>
                'The selected term does not exist.',


            'class_id.required' =>
                'Please select a class.',

            'class_id.integer' =>
                'The class selection is invalid.',

            'class_id.exists' =>
                'The selected class does not exist.',


            'section_id.integer' =>
                'The section selection is invalid.',

            'section_id.exists' =>
                'The selected section does not exist.',


            'student_id.required' =>
                'Please select a student.',

            'student_id.integer' =>
                'The student selection is invalid.',

            'student_id.exists' =>
                'The selected student does not exist.',


            'course_id.required' =>
                'Please select a course.',

            'course_id.integer' =>
                'The course selection is invalid.',

            'course_id.exists' =>
                'The selected course does not exist.',


            'status.required' =>
                'Please select a registration status.',

            'status.in' =>
                'The selected registration status is invalid.',
        ];
    }

    /**
     * Get custom attribute names for validation errors.
     */
    public function attributes(): array
    {
        return [
            'academic_year_id' => 'academic year',
            'term_id' => 'term',
            'class_id' => 'class',
            'section_id' => 'section',
            'student_id' => 'student',
            'course_id' => 'course',
            'status' => 'registration status',
        ];
    }
}