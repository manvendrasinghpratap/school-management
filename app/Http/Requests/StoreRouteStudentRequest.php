<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class StoreRouteStudentRequest extends FormRequest {
 public function authorize(){return auth()->check();}
 public function rules(){ $sid=(int)auth()->user()->school_id; return ['route_id'=>['required','integer',Rule::exists('transport_routes','id')->where(fn($q)=>$q->where('school_id',$sid))],'student_id'=>['required','integer',Rule::exists('students','id')->where(fn($q)=>$q->where('school_id',$sid))],'pickup_point'=>['nullable','string','max:255'],'dropoff_point'=>['nullable','string','max:255'],'start_date'=>['nullable','date'],'end_date'=>['nullable','date','after_or_equal:start_date'],'status'=>['required',Rule::in(['active','inactive'])]]; }
}
