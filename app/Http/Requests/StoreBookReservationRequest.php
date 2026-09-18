<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBookReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'book_id' => $this->input('book_id') !== null
                ? (int) $this->input('book_id')
                : null,

            'library_member_id' => $this->input('library_member_id') !== null
                ? (int) $this->input('library_member_id')
                : null,
        ]);
    }

    public function rules(): array
    {
        $schoolId = (int) auth()->user()->school_id;

        return [
            'book_id' => [
                'required',
                'integer',
                Rule::exists('books', 'id')->where(
                    fn ($query) => $query->where('school_id', $schoolId)
                ),
            ],

            'library_member_id' => [
                'required',
                'integer',
                Rule::exists('library_members', 'id')->where(
                    fn ($query) => $query
                        ->where('school_id', $schoolId)
                        ->where('status', 'active')
                ),
            ],

            'reserved_at' => [
                'nullable',
                'date',
            ],

            'expires_at' => [
                'nullable',
                'date',
                'after:reserved_at',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'book_id.exists' =>
                'The selected book does not belong to your school.',

            'library_member_id.exists' =>
                'The selected library member is not active or does not belong to your school.',

            'expires_at.after' =>
                'The reservation expiry must be after the reservation date.',
        ];
    }
}
