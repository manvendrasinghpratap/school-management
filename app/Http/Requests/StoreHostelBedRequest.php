<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreHostelBedRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && (bool) auth()->user()?->school_id;
    }

    public function rules(): array
    {
        $schoolId = (int) auth()->user()->school_id;

        return [
            'room_id' => [
                'required',
                'integer',
                Rule::exists('hostel_rooms', 'id')->where(
                    fn ($q) => $q->where('school_id', $schoolId)
                ),
            ],
            'bed_number' => ['required', 'string', 'max:100'],
            'status' => [
                'required',
                Rule::in(['available', 'occupied', 'maintenance', 'inactive']),
            ],
        ];
    }
}
