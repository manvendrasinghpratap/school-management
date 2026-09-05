<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStudentDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check()
            && auth()->user()->school_id !== null;
    }

    public function rules(): array
    {
        return [
            'document_type' => [
                'required',
                'string',
                'max:100',
            ],
            'document_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'document' => [
                'required',
                'file',
                'mimes:pdf,jpg,jpeg,png,webp,doc,docx',
                'max:10240',
            ],

            'description' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'document.required' => 'Please select a document to upload.',

            'document.file' => 'The selected document is invalid.',

            'document.mimes' => 'The document must be a PDF, JPG, JPEG, PNG, WEBP, DOC, or DOCX file.',

            'document.max' => 'The document may not be larger than 10 MB.',

            'document_type.required' => 'Please select a document type.',
        ];
    }
}