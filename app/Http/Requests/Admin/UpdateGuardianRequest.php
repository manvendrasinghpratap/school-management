<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGuardianRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $guardian = $this->route('guardian');

        $guardianId = $guardian?->id ?? $guardian;

        $schoolId = auth()->user()?->school_id;

        return [
            'guardian_number' => [
                'required',
                'string',
                'max:100',
                Rule::unique('guardians', 'guardian_number')
                    ->where(fn ($query) =>
                        $query->where('school_id', $schoolId)
                    )
                    ->ignore($guardianId),
            ],

            'title' => [
                'nullable',
                'string',
                'max:50',
            ],

            'first_name' => [
                'required',
                'string',
                'max:255',
            ],

            'middle_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'last_name' => [
                'required',
                'string',
                'max:255',
            ],

            'address' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'state' => [
                'nullable',
                'string',
                'max:255',
            ],

            'local_government' => [
                'nullable',
                'string',
                'max:255',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:50',
            ],

            'whatsapp' => [
                'nullable',
                'string',
                'max:50',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'occupation' => [
                'nullable',
                'string',
                'max:255',
            ],
        ];
    }
}