<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreHostelRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && (bool) auth()->user()?->school_id;
    }

    public function rules(): array
    {
        return [
            'hostel_id' => [
                'required',
                'integer',
                Rule::exists('hostels', 'id')->where(
                    fn ($q) => $q
                        ->where('school_id', (int) auth()->user()->school_id)
                        ->where('status', 'active')
                        ->whereNull('deleted_at')
                ),
            ],
            'room_number' => ['required', 'string', 'max:100'],
            'floor' => ['nullable', 'string', 'max:50'],
            'room_type' => ['nullable', 'string', 'max:100'],
            'capacity' => ['required', 'integer', 'min:1'],
            'monthly_fee' => ['required', 'numeric', 'min:0', 'max:999999999.99'],
            'status' => [
                'required',
                Rule::in(['available', 'full', 'maintenance', 'inactive']),
            ],
        ];
    }
}
