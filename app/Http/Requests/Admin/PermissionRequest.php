<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can($this->isMethod('POST') ? 'permissions.create' : 'permissions.update') ?? false;
    }

    public function rules(): array
    {
        $permissionId = $this->route('permission')?->id;

        return [
            'name' => [
                'required',
                'string',
                'max:125',
                Rule::unique('permissions', 'name')
                    ->where(fn ($q) => $q->where('guard_name', 'web'))
                    ->ignore($permissionId),
            ],
        ];
    }
}
