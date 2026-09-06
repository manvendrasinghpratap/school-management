<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->school_id !== null;
    }

    public function rules(): array
    {
        $user = $this->route('user');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($user?->id),
            ],

            'username' => [
                'required',
                'string',
                'max:100',
                Rule::unique('users', 'username')
                    ->ignore($user?->id),
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],

            'user_type_id' => [
                'nullable',
                'integer',
                Rule::exists('user_types', 'id')
                    ->where(
                        fn ($query) => $query->where('status', 1)
                    ),
            ],

            'designation_id' => [
                'nullable',
                'integer',
                Rule::exists('designations', 'id')
                    ->where(
                        fn ($query) => $query
                            ->where('status', 1)
                            ->where('is_deleted', 0)
                    ),
            ],

            /*
             * Spatie roles.
             *
             * The form submits role NAMES.
             */
            'roles' => [
                'nullable',
                'array',
            ],

            'roles.*' => [
                'string',
                Rule::exists('roles', 'name')
                    ->where(
                        fn ($query) => $query->where('guard_name', 'web')
                    ),
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],

            'is_staff' => [
                'nullable',
                'boolean',
            ],

            'status' => [
                'nullable',
                'integer',
            ],

            'timezone' => [
                'nullable',
                'string',
                'max:100',
            ],

            'avatar' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'roles.array' => 'The roles field must be an array.',

            'roles.*.exists' =>
                'One or more selected roles are invalid.',
        ];
    }
}