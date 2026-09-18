<?php

namespace App\Http\Requests;

use App\Models\LibraryAuthor;
use App\Models\LibraryCategory;
use App\Models\LibraryPublisher;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBookRequest extends FormRequest
{
    /**
     * Determine whether the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check()
            && (int) auth()->user()->school_id > 0;
    }

    /**
     * Prepare request data before validation.
     *
     * The current books table stores publisher as TEXT rather than
     * publisher_id, so convert the selected publisher ID into its name.
     */
    protected function prepareForValidation(): void
    {
        $schoolId = (int) auth()->user()->school_id;

        if (
            $this->filled('publisher_id')
            && $schoolId > 0
        ) {
            $publisher = LibraryPublisher::query()
                ->where('school_id', $schoolId)
                ->find($this->input('publisher_id'));

            if ($publisher) {
                $this->merge([
                    'publisher' => $publisher->name,
                ]);
            }
        }
    }

    /**
     * Validation rules.
     */
    public function rules(): array
    {
        $schoolId = (int) auth()->user()->school_id;

        return [

            /*
            |--------------------------------------------------------------------------
            | Book Information
            |--------------------------------------------------------------------------
            */

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'isbn' => [
                'nullable',
                'string',
                'max:100',

                Rule::unique('books', 'isbn')
                    ->where(
                        fn ($query) => $query->where(
                            'school_id',
                            $schoolId
                        )
                    ),
            ],

            /*
            |--------------------------------------------------------------------------
            | Category
            |--------------------------------------------------------------------------
            |
            | category_id is required because the live books table requires it.
            |--------------------------------------------------------------------------
            */

            'category_id' => [
                'required',
                'integer',

                Rule::exists(
                    'library_categories',
                    'id'
                )->where(
                    fn ($query) => $query->where(
                        'school_id',
                        $schoolId
                    )
                ),
            ],

            /*
            |--------------------------------------------------------------------------
            | Legacy / Display Author
            |--------------------------------------------------------------------------
            */

            'author' => [
                'nullable',
                'string',
                'max:255',
            ],

            /*
            |--------------------------------------------------------------------------
            | Publisher
            |--------------------------------------------------------------------------
            |
            | publisher_id comes from the form.
            | publisher is converted to the publisher name in
            | prepareForValidation().
            |--------------------------------------------------------------------------
            */

            'publisher_id' => [
                'nullable',
                'integer',

                Rule::exists(
                    'library_publishers',
                    'id'
                )->where(
                    fn ($query) => $query->where(
                        'school_id',
                        $schoolId
                    )
                ),
            ],

            'publisher' => [
                'nullable',
                'string',
                'max:255',
            ],

            /*
            |--------------------------------------------------------------------------
            | Shelf
            |--------------------------------------------------------------------------
            */

            'shelf_location' => [
                'nullable',
                'string',
                'max:255',
            ],

            /*
            |--------------------------------------------------------------------------
            | Quantity
            |--------------------------------------------------------------------------
            */

            'quantity' => [
                'required',
                'integer',
                'min:0',
            ],

            /*
            |--------------------------------------------------------------------------
            | Library Authors
            |--------------------------------------------------------------------------
            |
            | These IDs are stored in the book_author pivot table.
            |--------------------------------------------------------------------------
            */

            'author_ids' => [
                'nullable',
                'array',
            ],

            'author_ids.*' => [
                'integer',

                Rule::exists(
                    'library_authors',
                    'id'
                )->where(
                    fn ($query) => $query->where(
                        'school_id',
                        $schoolId
                    )
                ),
            ],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'title.required' =>
                'Book title is required.',

            'category_id.required' =>
                'Please select a library category.',

            'category_id.exists' =>
                'The selected category is invalid.',

            'publisher_id.exists' =>
                'The selected publisher is invalid.',

            'quantity.required' =>
                'Total quantity is required.',

            'quantity.integer' =>
                'Total quantity must be a whole number.',

            'quantity.min' =>
                'Total quantity cannot be negative.',

            'author_ids.array' =>
                'Selected authors are invalid.',

            'author_ids.*.exists' =>
                'One or more selected authors are invalid.',
        ];
    }
}