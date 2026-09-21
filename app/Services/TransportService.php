<?php
namespace App\Services;

use App\Models\RouteStudent;
use App\Models\Student;
use App\Models\TransportDriver;
use App\Models\TransportFee;
use App\Models\TransportRoute;
use App\Models\TransportStop;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Model;

class TransportService
{
    private function sid(): int { abort_unless(auth()->user()?->school_id,403,'No school is assigned to the current user.'); return (int)auth()->user()->school_id; }
    public function saveDriver(array $data, ?TransportDriver $driver=null): TransportDriver { $sid=$this->sid(); return DB::transaction(function()use($data,$driver,$sid){ if($driver && (int)$driver->school_id!==$sid)abort(404); $data['school_id']=$sid; return $driver?$this->fillSave($driver,$data):TransportDriver::create($data); }); }
    public function saveVehicle(array $data, ?Vehicle $vehicle=null): Vehicle { $sid=$this->sid(); if($vehicle && (int)$vehicle->school_id!==$sid)abort(404); $data['school_id']=$sid; return $vehicle?$this->fillSave($vehicle,$data):Vehicle::create($data); }
    public function saveRoute(array $data, ?TransportRoute $route=null): TransportRoute { $sid=$this->sid(); return DB::transaction(function()use($data,$route,$sid){ if($route && (int)$route->school_id!==$sid)abort(404); if(!empty($data['driver_id']))TransportDriver::where('id',$data['driver_id'])->where('school_id',$sid)->firstOrFail(); if(!empty($data['vehicle_id']))Vehicle::where('id',$data['vehicle_id'])->where('school_id',$sid)->firstOrFail(); $data['school_id']=$sid;$data['created_by']=auth()->id(); return $route?$this->fillSave($route,$data):TransportRoute::create($data); }); }
    public function saveStop(array $data, ?TransportStop $stop=null): TransportStop { $sid=$this->sid(); $route=TransportRoute::where('id',$data['route_id'])->where('school_id',$sid)->firstOrFail(); if($stop && (int)$stop->school_id!==$sid)abort(404); $duplicate=TransportStop::where('route_id',$route->id)->where('sequence_no',$data['sequence_no'])->when($stop,fn($q)=>$q->where('id','!=',$stop->id))->exists(); if($duplicate)throw ValidationException::withMessages(['sequence_no'=>'This sequence number is already used on this route.']); $data['school_id']=$sid; return $stop?$this->fillSave($stop,$data):TransportStop::create($data); }
    public function saveAssignment(array $data, ?RouteStudent $assignment=null): RouteStudent { $sid=$this->sid(); return DB::transaction(function()use($data,$assignment,$sid){ $route=TransportRoute::where('id',$data['route_id'])->where('school_id',$sid)->firstOrFail(); $student=Student::where('id',$data['student_id'])->where('school_id',$sid)->firstOrFail(); if($assignment && (int)$assignment->school_id!==$sid)abort(404); $exists=RouteStudent::where('route_id',$route->id)->where('student_id',$student->id)->when($assignment,fn($q)=>$q->where('id','!=',$assignment->id))->exists(); if($exists)throw ValidationException::withMessages(['student_id'=>'This student is already assigned to this route.']); $data['school_id']=$sid; return $assignment?$this->fillSave($assignment,$data):RouteStudent::create($data); }); }
    public function saveFee(array $data, ?TransportFee $fee=null): TransportFee { $sid=$this->sid(); $assignment=RouteStudent::where('id',$data['route_student_id'])->where('school_id',$sid)->firstOrFail(); if($fee && (int)$fee->school_id!==$sid)abort(404); $data['school_id']=$sid; return $fee?$this->fillSave($fee,$data):TransportFee::create($data); }
    public function delete(Model $model): void { if((int)$model->school_id!==$this->sid())abort(404); $model->delete(); }
    private function fillSave($model,array $data){$model->fill($data);$model->save();return $model;}
}
