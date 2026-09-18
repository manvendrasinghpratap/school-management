<?php

namespace App\Http\Requests\Wave2;

use Illuminate\Foundation\Http\FormRequest;

class StoreHostelAllocationRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check() && (bool)auth()->user()->school_id; }
public function rules(): array {
 return ['hostel_id'=>'required|integer','room_id'=>'required|integer','bed_id'=>'nullable|integer','student_id'=>'required|integer','academic_year_id'=>'nullable|integer','start_date'=>'required|date','end_date'=>'nullable|date|after_or_equal:start_date','monthly_fee'=>'required|numeric|min:0','status'=>'required|in:allocated,checked_in','notes'=>'nullable|string'];
}
}
