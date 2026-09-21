<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class StoreTransportRouteRequest extends FormRequest {
 public function authorize(){return auth()->check();}
 public function rules(){ $sid=(int)auth()->user()->school_id; $id=$this->route('transportRoute')?->id; return ['vehicle_id'=>['nullable','integer',Rule::exists('vehicles','id')->where(fn($q)=>$q->where('school_id',$sid))],'driver_id'=>['nullable','integer',Rule::exists('transport_drivers','id')->where(fn($q)=>$q->where('school_id',$sid))],'name'=>['required','string','max:255'],'code'=>['nullable','string','max:100',Rule::unique('transport_routes','code')->where(fn($q)=>$q->where('school_id',$sid))->ignore($id)],'start_point'=>['nullable','string','max:255'],'end_point'=>['nullable','string','max:255'],'departure_time'=>['nullable','date_format:H:i'],'arrival_time'=>['nullable','date_format:H:i'],'monthly_fee'=>['required','numeric','min:0','max:9999999999.99'],'status'=>['required',Rule::in(['active','inactive'])],'notes'=>['nullable','string','max:5000']]; }
}
