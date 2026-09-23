<?php
namespace App\Http\Controllers\Admin\Wave4;
use App\Models\DisciplinaryRecords;use App\Models\Student;use Illuminate\Http\Request;use Illuminate\Validation\Rule;
class DisciplineController extends SchoolScopedController
{
 public function index(Request $r){$items=DisciplinaryRecords::where('school_id',$this->schoolId())->with('student')->when($r->filled('status'),fn($q)=>$q->where('status',$r->status))->latest('incident_date')->paginate(20)->withQueryString();return view('admin.wave4.discipline.index',compact('items'));}
 public function create(){return view('admin.wave4.discipline.form',['item'=>new DisciplinaryRecords,'students'=>Student::where('school_id',$this->schoolId())->orderBy('first_name')->get()]);}
 public function store(Request $r){$d=$this->validateData($r);$d['school_id']=$this->schoolId();$d['recorded_by']=auth()->id();DisciplinaryRecords::create($d);return redirect()->route('admin.discipline.index')->with('success','Disciplinary record created successfully.');}
 public function edit(DisciplinaryRecords $discipline){$this->ensureSchool($discipline);return view('admin.wave4.discipline.form',['item'=>$discipline,'students'=>Student::where('school_id',$this->schoolId())->orderBy('first_name')->get()]);}
 public function update(Request $r,DisciplinaryRecords $discipline){$this->ensureSchool($discipline);$discipline->update($this->validateData($r));return redirect()->route('admin.discipline.index')->with('success','Disciplinary record updated successfully.');}
 public function destroy(DisciplinaryRecords $discipline){$this->ensureSchool($discipline);$discipline->delete();return back()->with('success','Disciplinary record deleted successfully.');}
 private function validateData(Request $r):array{return $r->validate(['student_id'=>['required','integer',Rule::exists('students','id')->where(fn($q)=>$q->where('school_id',$this->schoolId()))],'incident_date'=>'required|date','incident_type'=>'nullable|string|max:100','description'=>'required|string|max:10000','action_taken'=>'nullable|string|max:10000','status'=>['required',Rule::in(['open','resolved','closed'])]]);}
}
