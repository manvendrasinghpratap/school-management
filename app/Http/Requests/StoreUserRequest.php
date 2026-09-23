<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check()
            && auth()->user()->school_id !== null;
    }

    public function rules(): array
    {
        $schoolId = auth()->user()?->school_id;

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
                'unique:users,email',
            ],

            'username' => [
                'required',
                'string',
                'max:100',
                'unique:users,username',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],

            'user_type_id' => [
                'nullable',
                'integer',
                Rule::exists('user_types', 'id')
                    ->where(function ($query) {
                        $query->where('status', 1);
                    }),
            ],

            'designation_id' => [
                'nullable',
                'integer',
                Rule::exists('designations', 'id')
                    ->where(function ($query) use ($schoolId) {
                        $query->where('account_id', $schoolId)
                            ->where('is_deleted', 0)
                            ->where('status', 1);
                    }),
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
            'name.required' =>
                'The user name is required.',

            'email.required' =>
                'The email address is required.',

            'email.email' =>
                'Please enter a valid email address.',

            'email.unique' =>
                'This email address is already in use.',

            'username.required' =>
                'The username is required.',

            'username.unique' =>
                'This username is already in use.',

            'password.required' =>
                'The password is required.',

            'password.min' =>
                'The password must be at least 8 characters.',

            'password.confirmed' =>
                'The password confirmation does not match.',

            'avatar.image' =>
                'The avatar must be a valid image.',

            'avatar.max' =>
                'The avatar must not exceed 2MB.',
        ];
    }
}