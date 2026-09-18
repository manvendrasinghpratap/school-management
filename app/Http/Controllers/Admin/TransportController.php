<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransportAssignmentRequest;
use App\Models\RouteStudent;
use App\Models\Student;
use App\Models\TransportDriver;
use App\Models\TransportRoute;
use App\Models\TransportStop;
use App\Models\Vehicle;
use App\Services\TransportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransportController extends Controller
{
    private function schoolId(): int { return (int)Auth::user()->school_id; }

    public function index()
    {
        $routes=TransportRoute::where('school_id',$this->schoolId())->with(['vehicle','driver','stops'])->latest()->paginate(15);
        return view('admin.transport.routes.index',compact('routes'));
    }

    public function createRoute()
    {
        $sid=$this->schoolId();
        $vehicles=Vehicle::where('school_id',$sid)->where('status','active')->get();
        $drivers=TransportDriver::where('school_id',$sid)->where('status','active')->get();
        return view('admin.transport.routes.create',compact('vehicles','drivers'));
    }

    public function storeRoute(Request $request)
    {
        $data=$request->validate(['name'=>'required|string|max:255','code'=>'nullable|string|max:100','vehicle_id'=>'nullable|integer','driver_id'=>'nullable|integer','start_point'=>'nullable|string|max:255','end_point'=>'nullable|string|max:255','departure_time'=>'nullable','arrival_time'=>'nullable','monthly_fee'=>'required|numeric|min:0','status'=>'required|in:active,inactive','notes'=>'nullable|string']);
        $data['school_id']=$this->schoolId(); $data['created_by']=Auth::id();
        TransportRoute::create($data);
        return redirect()->route('admin.transport.routes.index')->with('success','Transport route created.');
    }

    public function assignments()
    {
        $assignments=RouteStudent::where('school_id',$this->schoolId())->with(['route','stop','student'])->latest()->paginate(20);
        return view('admin.transport.assignments.index',compact('assignments'));
    }

    public function createAssignment()
    {
        $sid=$this->schoolId();
        $routes=TransportRoute::where('school_id',$sid)->where('status','active')->with('stops')->get();
        $students=Student::where('school_id',$sid)->orderBy('first_name')->orderBy('last_name')->get();
        return view('admin.transport.assignments.create',compact('routes','students'));
    }

    public function storeAssignment(StoreTransportAssignmentRequest $request, TransportService $service)
    {
        $service->assignStudent($request->validated());
        return redirect()->route('admin.transport.assignments.index')->with('success','Student assigned to transport route.');
    }

    public function destroyAssignment(RouteStudent $assignment)
    {
        abort_unless((int)$assignment->school_id===$this->schoolId(),404);
        $assignment->delete();
        return back()->with('success','Transport assignment deleted.');
    }
}
