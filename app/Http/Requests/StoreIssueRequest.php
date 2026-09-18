<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreIssueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'book_copy_id' => $this->input('book_copy_id')
                ? (int) $this->input('book_copy_id')
                : null,

            'library_member_id' => $this->input('library_member_id')
                ? (int) $this->input('library_member_id')
                : null,
        ]);
    }

    public function rules(): array
    {
        $schoolId = (int) auth()->user()->school_id;

        return [

            /*
            |--------------------------------------------------------------------------
            | Physical Book Copy
            |--------------------------------------------------------------------------
            */

            'book_copy_id' => [
                'required',
                'integer',

                Rule::exists('book_copies', 'id')
                    ->where(function ($query) use ($schoolId) {
                        $query->where('school_id', $schoolId)
                              ->where('status', 'available');
                    }),
            ],

            /*
            |--------------------------------------------------------------------------
            | Library Member
            |--------------------------------------------------------------------------
            */

            'library_member_id' => [
                'required',
                'integer',

                Rule::exists('library_members', 'id')
                    ->where(function ($query) use ($schoolId) {
                        $query->where('school_id', $schoolId)
                              ->where('status', 'active');
                    }),
            ],

            /*
            |--------------------------------------------------------------------------
            | Issue Date
            |--------------------------------------------------------------------------
            */

            'issued_date' => [
                'required',
                'date',
            ],

            /*
            |--------------------------------------------------------------------------
            | Due Date
            |--------------------------------------------------------------------------
            */

            'due_date' => [
                'required',
                'date',
                'after_or_equal:issued_date',
            ],

            /*
            |--------------------------------------------------------------------------
            | Due Date / Date-Time Compatibility
            |--------------------------------------------------------------------------
            |
            | due_at is the newer circulation field.
            | We allow it from the form, but the service will normalize
            | the final due date/time.
            |
            */

            'due_at' => [
                'nullable',
                'date',
                'after:issued_date',
            ],

            /*
            |--------------------------------------------------------------------------
            | Optional Notes
            |--------------------------------------------------------------------------
            */

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

            'book_copy_id.required' =>
                'Please select a physical book copy.',

            'book_copy_id.exists' =>
                'The selected book copy is not available for issue.',

            'library_member_id.required' =>
                'Please select a library member.',

            'library_member_id.exists' =>
                'The selected library member is not active or does not belong to this school.',

            'issued_date.required' =>
                'Please enter the issue date.',

            'due_date.required' =>
                'Please enter the due date.',

            'due_date.after_or_equal' =>
                'The due date must be on or after the issue date.',

            'due_at.after' =>
                'The due date/time must be after the issue date.',
        ];
    }
}