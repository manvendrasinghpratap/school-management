<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreHostelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && (bool) auth()->user()?->school_id;
    }

    public function rules(): array
    {
        $schoolId = (int) auth()->user()->school_id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('hostels', 'code')
                    ->where(fn ($q) => $q->where('school_id', $schoolId))
                    ->ignore($this->route('hostel')?->id),
            ],
            'hostel_type' => ['required', Rule::in(['boys', 'girls', 'mixed', 'staff'])],
            'address' => ['nullable', 'string', 'max:2000'],
            'description' => ['nullable', 'string', 'max:5000'],
            'warden_staff_id' => [
                'nullable',
                'integer',
                Rule::exists('staff', 'id')->where(
                    fn ($q) => $q->where('school_id', $schoolId)
                ),
            ],
            'capacity' => ['required', 'integer', 'min:0'],
            'monthly_fee' => ['required', 'numeric', 'min:0', 'max:999999999.99'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ];
    }
}
