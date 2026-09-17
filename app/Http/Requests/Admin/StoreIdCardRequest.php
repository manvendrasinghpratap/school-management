<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreIdCardRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check() && (bool) auth()->user()->school_id; }

    public function rules(): array
    {
        return [
            'template_id' => ['required','integer','exists:id_card_templates,id'],
            'holder_type' => ['required', Rule::in(['student','staff'])],
            'student_id' => ['nullable','integer','exists:students,id'],
            'staff_id' => ['nullable','integer','exists:staff,id'],
            'issued_at' => ['required','date'],
            'expires_at' => ['nullable','date','after_or_equal:issued_at'],
        ];
    }
}
