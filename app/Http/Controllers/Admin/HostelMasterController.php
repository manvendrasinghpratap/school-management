<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hostel;
use App\Models\HostelBed;
use App\Models\HostelRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HostelMasterController extends Controller
{
    private function schoolId(): int { return (int)Auth::user()->school_id; }

    public function beds(HostelRoom $room)
    {
        abort_unless((int)$room->school_id===$this->schoolId(),404);
        $beds=$room->beds()->latest()->paginate(20);
        return view('admin.hostel.beds.index',compact('room','beds'));
    }

    public function storeBed(Request $r, HostelRoom $room)
    {
        abort_unless((int)$room->school_id===$this->schoolId(),404);
        $d=$r->validate(['bed_number'=>'required|string|max:100','status'=>'required|in:available,occupied,maintenance,inactive']);
        $d['school_id']=$this->schoolId(); $d['room_id']=$room->id; HostelBed::create($d);
        return back()->with('success','Bed created.');
    }
}
