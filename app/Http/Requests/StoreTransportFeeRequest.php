<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class StoreTransportFeeRequest extends FormRequest {
 public function authorize(){return auth()->check();}
 public function rules(){ $sid=(int)auth()->user()->school_id; return ['route_student_id'=>['required','integer',Rule::exists('route_students','id')->where(fn($q)=>$q->where('school_id',$sid))],'fee_month'=>['required','date'],'amount'=>['required','numeric','min:0','max:9999999999.99'],'status'=>['required',Rule::in(['pending','invoiced','paid','waived'])],'invoice_id'=>['nullable','integer',Rule::exists('invoices','id')->where(fn($q)=>$q->where('school_id',$sid))],'notes'=>['nullable','string','max:5000']]; }
}
