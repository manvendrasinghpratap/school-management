<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransportDriver;
use App\Models\TransportRoute;
use App\Models\TransportStop;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransportMasterController extends Controller
{
    private function schoolId(): int { return (int)Auth::user()->school_id; }

    public function drivers(){ $items=TransportDriver::where('school_id',$this->schoolId())->latest()->paginate(20); return view('admin.transport.master.index',['items'=>$items,'title'=>'Transport Drivers','routePrefix'=>'admin.transport.drivers']); }
    public function storeDriver(Request $r){ $d=$r->validate(['name'=>'required|string|max:255','license_number'=>'nullable|string|max:100','license_expiry'=>'nullable|date','phone'=>'nullable|string|max:50','address'=>'nullable|string','status'=>'required|in:active,inactive,suspended']); $d['school_id']=$this->schoolId(); TransportDriver::create($d); return back()->with('success','Driver created.'); }

    public function vehicles(){ $items=Vehicle::where('school_id',$this->schoolId())->latest()->paginate(20); return view('admin.transport.master.index',['items'=>$items,'title'=>'Vehicles','routePrefix'=>'admin.transport.vehicles']); }
    public function storeVehicle(Request $r){ $d=$r->validate(['registration_number'=>'required|string|max:100','vehicle_number'=>'nullable|string|max:100','vehicle_type'=>'nullable|string|max:100','make'=>'nullable|string|max:100','model'=>'nullable|string|max:100','year'=>'nullable|integer|min:1900|max:2100','capacity'=>'nullable|integer|min:1','driver_id'=>'nullable|integer','insurance_expiry'=>'nullable|date','fitness_expiry'=>'nullable|date','status'=>'required|in:active,inactive,maintenance,retired','notes'=>'nullable|string']); $d['school_id']=$this->schoolId(); $d['created_by']=Auth::id(); Vehicle::create($d); return back()->with('success','Vehicle created.'); }

    public function stops(TransportRoute $route){ abort_unless((int)$route->school_id===$this->schoolId(),404); $stops=$route->stops()->paginate(20); return view('admin.transport.stops.index',compact('route','stops')); }
    public function storeStop(Request $r, TransportRoute $route){ abort_unless((int)$route->school_id===$this->schoolId(),404); $d=$r->validate(['name'=>'required|string|max:255','sequence_no'=>'required|integer|min:1','pickup_time'=>'nullable','dropoff_time'=>'nullable','address'=>'nullable|string','monthly_fee'=>'nullable|numeric|min:0']); $d['school_id']=$this->schoolId(); $d['route_id']=$route->id; TransportStop::create($d); return back()->with('success','Stop created.'); }
}
