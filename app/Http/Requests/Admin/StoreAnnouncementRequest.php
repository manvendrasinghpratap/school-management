<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAnnouncementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check()
            && (bool) auth()->user()->school_id;
    }

    public function rules(): array
    {
        $schoolId = (int) auth()->user()->school_id;

        return [
            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'body' => [
                'required',
                'string',
            ],

            'audience_type' => [
                'required',
                Rule::in([
                    'all',
                    'students',
                    'parents',
                    'staff',
                    'class',
                    'section',
                ]),
            ],

            'class_id' => [
                'nullable',
                'integer',
                Rule::exists('classes', 'id')
                    ->where(fn ($query) => $query->where('school_id', $schoolId)),
            ],

            'section_id' => [
                'nullable',
                'integer',
                Rule::exists('sections', 'id'),
            ],

            'publish_at' => [
                'nullable',
                'date',
            ],

            'expires_at' => [
                'nullable',
                'date',
                'after_or_equal:publish_at',
            ],

            'status' => [
                'required',
                Rule::in([
                    'draft',
                    'published',
                    'archived',
                ]),
            ],

            'is_pinned' => [
                'nullable',
                'boolean',
            ],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $audience = $this->input('audience_type');
            $classId = $this->input('class_id');
            $sectionId = $this->input('section_id');

            if ($audience === 'class' && !$classId) {
                $validator->errors()->add(
                    'class_id',
                    'Please select a class for a class announcement.'
                );
            }

            if ($audience === 'section' && !$sectionId) {
                $validator->errors()->add(
                    'section_id',
                    'Please select a section for a section announcement.'
                );
            }

            if ($sectionId && $classId) {
                $validSection = \App\Models\Section::whereKey($sectionId)
                    ->where('class_id', $classId)
                    ->exists();

                if (!$validSection) {
                    $validator->errors()->add(
                        'section_id',
                        'The selected section does not belong to the selected class.'
                    );
                }
            }
        });
    }

    public function attributes(): array
    {
        return [
            'audience_type' => 'audience',
            'class_id' => 'class',
            'section_id' => 'section',
            'publish_at' => 'publish date',
            'expires_at' => 'expiry date',
            'is_pinned' => 'pinned',
        ];
    }
}