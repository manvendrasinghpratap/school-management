<?php

namespace App\Http\Requests\Admin;

use App\Models\Section;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check()
            && auth()->user()->school_id !== null;
    }

    public function rules(): array
    {
        $schoolId = (int) auth()->user()->school_id;

        $section = $this->route('section');

        $sectionId = $section instanceof Section
            ? $section->id
            : $section;

        return [
            'class_id' => [
                'required',
                'integer',
                Rule::exists('classes', 'id')
                    ->where(fn ($query) => $query->where('school_id', $schoolId)),
            ],

            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('sections', 'name')
                    ->where(fn ($query) => $query->where(
                        'class_id',
                        $this->input('class_id')
                    ))
                    ->ignore($sectionId),
            ],

            'code' => [
                'nullable',
                'string',
                'max:50',
            ],

            'capacity' => [
                'nullable',
                'integer',
                'min:1',
                'max:100000',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'class_id.required' => 'Please select a class.',

            'class_id.exists' =>
                'The selected class does not belong to your school.',

            'name.required' =>
                'Please enter the section name.',

            'name.unique' =>
                'This section already exists in the selected class.',

            'code.max' =>
                'The section code may not exceed 50 characters.',

            'capacity.integer' =>
                'The capacity must be a whole number.',

            'capacity.min' =>
                'The capacity must be at least 1.',

            'capacity.max' =>
                'The capacity is too large.',
        ];
    }
}