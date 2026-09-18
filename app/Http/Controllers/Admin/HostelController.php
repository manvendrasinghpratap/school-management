<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHostelAllocationRequest;
use App\Models\Hostel;
use App\Models\HostelAllocation;
use App\Models\HostelBed;
use App\Models\HostelRoom;
use App\Models\Staff;
use App\Models\Student;
use App\Services\HostelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HostelController extends Controller
{
    private function schoolId(): int { return (int)Auth::user()->school_id; }

    public function index()
    {
        $hostels=Hostel::where('school_id',$this->schoolId())->withCount(['rooms','allocations'])->latest()->paginate(15);
        return view('admin.hostel.hostels.index',compact('hostels'));
    }

    public function create()
    {
        $staff=Staff::where('school_id',$this->schoolId())->orderBy('first_name')->orderBy('last_name')->get();
        return view('admin.hostel.hostels.create',compact('staff'));
    }

    public function store(Request $request)
    {
        $data=$request->validate(['name'=>'required|string|max:255','code'=>'nullable|string|max:100','hostel_type'=>'required|in:boys,girls,mixed,staff','address'=>'nullable|string','warden_staff_id'=>'nullable|integer','capacity'=>'required|integer|min:0','monthly_fee'=>'required|numeric|min:0','status'=>'required|in:active,inactive']);
        $data['school_id']=$this->schoolId(); $data['created_by']=Auth::id();
        Hostel::create($data);
        return redirect()->route('admin.hostel.index')->with('success','Hostel created.');
    }

    public function rooms(Hostel $hostel)
    {
        abort_unless((int)$hostel->school_id===$this->schoolId(),404);
        $rooms=$hostel->rooms()->withCount('beds')->latest()->paginate(20);
        return view('admin.hostel.rooms.index',compact('hostel','rooms'));
    }

    public function storeRoom(Request $request, Hostel $hostel)
    {
        abort_unless((int)$hostel->school_id===$this->schoolId(),404);
        $data=$request->validate(['room_number'=>'required|string|max:100','floor'=>'nullable|string|max:50','room_type'=>'nullable|string|max:100','capacity'=>'required|integer|min:1','monthly_fee'=>'required|numeric|min:0','status'=>'required|in:available,full,maintenance,inactive']);
        $data['school_id']=$this->schoolId(); $data['hostel_id']=$hostel->id;
        HostelRoom::create($data);
        return back()->with('success','Hostel room created.');
    }

    public function allocations()
    {
        $allocations=HostelAllocation::where('school_id',$this->schoolId())->with(['hostel','room','bed','student'])->latest()->paginate(20);
        return view('admin.hostel.allocations.index',compact('allocations'));
    }

    public function createAllocation()
    {
        $sid=$this->schoolId();
        $hostels=Hostel::where('school_id',$sid)->where('status','active')->get();
        $rooms=HostelRoom::where('school_id',$sid)->whereIn('status',['available','full'])->get();
        $beds=HostelBed::where('school_id',$sid)->where('status','available')->get();
        $students=Student::where('school_id',$sid)->orderBy('first_name')->orderBy('last_name')->get();
        return view('admin.hostel.allocations.create',compact('hostels','rooms','beds','students'));
    }

    public function storeAllocation(StoreHostelAllocationRequest $request, HostelService $service)
    {
        $service->allocate($request->validated());
        return redirect()->route('admin.hostel.allocations.index')->with('success','Student allocated to hostel.');
    }

    public function checkout(HostelAllocation $allocation, HostelService $service)
    {
        abort_unless((int)$allocation->school_id===$this->schoolId(),404);
        $service->checkout($allocation,[]);
        return back()->with('success','Student checked out from hostel.');
    }
}
