<?php

namespace App\Http\Requests\Wave2;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransportAssignmentRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check() && (bool)auth()->user()->school_id; }
public function rules(): array {
 return ['route_id'=>'required|integer','stop_id'=>'nullable|integer','student_id'=>'required|integer','academic_year_id'=>'nullable|integer','start_date'=>'required|date','end_date'=>'nullable|date|after_or_equal:start_date','monthly_fee'=>'required|numeric|min:0','notes'=>'nullable|string'];
}
}
