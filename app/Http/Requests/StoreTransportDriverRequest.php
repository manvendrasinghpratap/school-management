<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class StoreTransportDriverRequest extends FormRequest {
 public function authorize(){return auth()->check();}
 public function rules(){ $sid=(int)auth()->user()->school_id; $id=$this->route('driver')?->id; return ['staff_id'=>['nullable','integer',Rule::exists('staff','id')->where(fn($q)=>$q->where('school_id',$sid))],'name'=>['required','string','max:255'],'license_number'=>['nullable','string','max:100',Rule::unique('transport_drivers','license_number')->where(fn($q)=>$q->where('school_id',$sid))->ignore($id)],'license_expiry'=>['nullable','date'],'phone'=>['nullable','string','max:50'],'address'=>['nullable','string','max:5000'],'status'=>['required',Rule::in(['active','inactive','suspended'])]]; }
}
