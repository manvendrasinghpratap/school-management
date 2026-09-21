<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class StoreVehicleRequest extends FormRequest {
 public function authorize(){return auth()->check();}
 public function rules(){ $id=$this->route('vehicle')?->id; return ['vehicle_number'=>['required','string','max:100',Rule::unique('vehicles','vehicle_number')->ignore($id)],'registration_number'=>['required','string','max:100',Rule::unique('vehicles','registration_number')->ignore($id)],'vehicle_type'=>['nullable','string','max:100'],'capacity'=>['nullable','integer','min:1','max:1000'],'driver_name'=>['nullable','string','max:255'],'driver_phone'=>['nullable','string','max:50'],'status'=>['required',Rule::in(['active','inactive','maintenance'])]]; }
}
