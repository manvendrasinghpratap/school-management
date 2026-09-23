<?php
namespace App\Http\Controllers\Admin\Wave4;
use App\Models\Designation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DesignationController extends SchoolScopedController
{
    public function index(Request $request) { $items=Designation::forSchool($this->schoolId())->when($request->filled('search'),fn($q)=>$q->where('name','like','%'.trim($request->search).'%'))->withCount('users')->orderBy('name')->paginate(20)->withQueryString(); return view('admin.wave4.designations.index',compact('items')); }
    public function create() { return view('admin.wave4.designations.form',['item'=>new Designation]); }
    public function store(Request $request) { $data=$request->validate(['name'=>['required','string','max:100',Rule::unique('designations','name')->where(fn($q)=>$q->where('account_id',$this->schoolId())->where('is_deleted',0))],'status'=>['required','boolean']]); $data['account_id']=$this->schoolId(); $data['is_deleted']=false; Designation::create($data); return redirect()->route('admin.designations.index')->with('success','Designation created successfully.'); }
    public function edit(Designation $designation) { $this->ensureSchool($designation,'account_id'); return view('admin.wave4.designations.form',['item'=>$designation]); }
    public function update(Request $request, Designation $designation) { $this->ensureSchool($designation,'account_id'); $data=$request->validate(['name'=>['required','string','max:100',Rule::unique('designations','name')->where(fn($q)=>$q->where('account_id',$this->schoolId())->where('is_deleted',0))->ignore($designation->id)],'status'=>['required','boolean']]); $designation->update($data); return redirect()->route('admin.designations.index')->with('success','Designation updated successfully.'); }
    public function destroy(Designation $designation) { $this->ensureSchool($designation,'account_id'); abort_if($designation->users()->exists(),422,'This designation is assigned to one or more users. Reassign those users first.'); $designation->update(['is_deleted'=>true,'status'=>false]); return redirect()->route('admin.designations.index')->with('success','Designation deleted successfully.'); }
}
