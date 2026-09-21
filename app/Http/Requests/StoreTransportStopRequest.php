<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class StoreTransportStopRequest extends FormRequest {
 public function authorize(){return auth()->check();}
 public function rules(){ $sid=(int)auth()->user()->school_id; return ['route_id'=>['required','integer',Rule::exists('transport_routes','id')->where(fn($q)=>$q->where('school_id',$sid))],'name'=>['required','string','max:255'],'sequence_no'=>['required','integer','min:1','max:10000'],'pickup_time'=>['nullable','date_format:H:i'],'dropoff_time'=>['nullable','date_format:H:i'],'address'=>['nullable','string','max:5000'],'monthly_fee'=>['nullable','numeric','min:0','max:9999999999.99']]; }
}
