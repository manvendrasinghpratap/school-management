<?php
namespace App\Http\Controllers\Admin\Wave4;
use App\Models\Complaints;use App\Models\Student;use Illuminate\Http\Request;use Illuminate\Validation\Rule;
class ComplaintController extends SchoolScopedController
{
 public function index(Request $r){$items=Complaints::where('school_id',$this->schoolId())->with(['student','assignedTo'])->when($r->filled('status'),fn($q)=>$q->where('status',$r->status))->when($r->filled('search'),fn($q)=>$q->where(fn($x)=>$x->where('subject','like','%'.trim($r->search).'%')->orWhere('description','like','%'.trim($r->search).'%')))->latest()->paginate(20)->withQueryString();return view('admin.wave4.complaints.index',compact('items'));}
 public function create(){return view('admin.wave4.complaints.form',['item'=>new Complaints,'students'=>Student::where('school_id',$this->schoolId())->orderBy('first_name')->get(),'users'=>$this->schoolUsers()]);}
 public function store(Request $r){$d=$this->validateData($r);$d['school_id']=$this->schoolId();$d['user_id']=auth()->id();Complaints::create($d);return redirect()->route('admin.complaints.index')->with('success','Complaint created successfully.');}
 public function edit(Complaints $complaint){$this->ensureSchool($complaint);return view('admin.wave4.complaints.form',['item'=>$complaint,'students'=>Student::where('school_id',$this->schoolId())->orderBy('first_name')->get(),'users'=>$this->schoolUsers()]);}
 public function update(Request $r,Complaints $complaint){$this->ensureSchool($complaint);$complaint->update($this->validateData($r));return redirect()->route('admin.complaints.index')->with('success','Complaint updated successfully.');}
 public function destroy(Complaints $complaint){$this->ensureSchool($complaint);$complaint->delete();return back()->with('success','Complaint deleted successfully.');}
 private function validateData(Request $r):array{return $r->validate(['student_id'=>['nullable','integer',Rule::exists('students','id')->where(fn($q)=>$q->where('school_id',$this->schoolId()))],'subject'=>'required|string|max:255','description'=>'required|string|max:10000','priority'=>["required",Rule::in(['low','medium','high','urgent'])],'status'=>['required',Rule::in(['open','in_progress','resolved','closed'])],'assigned_to'=>['nullable','integer',Rule::exists('users','id')->where(fn($q)=>$q->where('school_id',$this->schoolId()))],'resolved_at'=>'nullable|date']);}
}
