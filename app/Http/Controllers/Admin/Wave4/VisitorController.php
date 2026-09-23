<?php
namespace App\Http\Controllers\Admin\Wave4;
use App\Models\Visitors;use Illuminate\Http\Request;use Illuminate\Validation\Rule;
class VisitorController extends SchoolScopedController
{
 public function index(Request $r){$items=Visitors::where('school_id',$this->schoolId())->with('createdBy')->when($r->filled('search'),fn($q)=>$q->where(fn($x)=>$x->where('visitor_name','like','%'.trim($r->search).'%')->orWhere('phone','like','%'.trim($r->search).'%')->orWhere('purpose','like','%'.trim($r->search).'%')))->latest('check_in')->paginate(20)->withQueryString();return view('admin.wave4.visitors.index',compact('items'));}
 public function create(){return view('admin.wave4.visitors.form',['item'=>new Visitors]);}
 public function store(Request $r){$d=$this->validateData($r);$d['school_id']=$this->schoolId();$d['created_by']=auth()->id();Visitors::create($d);return redirect()->route('admin.visitors.index')->with('success','Visitor record created successfully.');}
 public function edit(Visitors $visitor){$this->ensureSchool($visitor);return view('admin.wave4.visitors.form',['item'=>$visitor]);}
 public function update(Request $r,Visitors $visitor){$this->ensureSchool($visitor);$visitor->update($this->validateData($r));return redirect()->route('admin.visitors.index')->with('success','Visitor record updated successfully.');}
 public function destroy(Visitors $visitor){$this->ensureSchool($visitor);$visitor->delete();return back()->with('success','Visitor record deleted successfully.');}
 private function validateData(Request $r):array{return $r->validate(['visitor_name'=>'required|string|max:255','phone'=>'nullable|string|max:50','purpose'=>'nullable|string|max:255','person_to_visit'=>'nullable|string|max:255','check_in'=>'required|date','check_out'=>'nullable|date|after_or_equal:check_in','identification_type'=>'nullable|string|max:100','identification_number'=>'nullable|string|max:255']);}
}
