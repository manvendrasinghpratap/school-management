<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLibraryMemberRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check() && (bool)auth()->user()->school_id; }
public function rules(): array {
 return [
  'holder_type'=>'required|in:student,staff','student_id'=>'nullable|integer','staff_id'=>'nullable|integer',
  'joined_at'=>'required|date','expiry_date'=>'nullable|date|after_or_equal:joined_at','max_books'=>'required|integer|min:1|max:50',
  'status'=>'required|in:active,inactive,suspended,expired','notes'=>'nullable|string',
 ];
}
}
