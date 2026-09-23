<?php
namespace App\Http\Controllers\Admin\Wave4;
use App\Models\Suppliers;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class SupplierController extends SchoolScopedController
{
    public function index(Request $request){$items=Suppliers::where('school_id',$this->schoolId())->when($request->filled('search'),fn($q)=>$q->where(fn($x)=>$x->where('name','like','%'.trim($request->search).'%')->orWhere('contact_person','like','%'.trim($request->search).'%')->orWhere('phone','like','%'.trim($request->search).'%')))->orderBy('name')->paginate(20)->withQueryString();return view('admin.wave4.suppliers.index',compact('items'));}
    public function create(){return view('admin.wave4.suppliers.form',['item'=>new Suppliers]);}
    public function store(Request $r){$data=$this->validateData($r);$data['school_id']=$this->schoolId();Suppliers::create($data);return redirect()->route('admin.suppliers.index')->with('success','Supplier created successfully.');}
    public function edit(Suppliers $supplier){$this->ensureSchool($supplier);return view('admin.wave4.suppliers.form',['item'=>$supplier]);}
    public function update(Request $r,Suppliers $supplier){$this->ensureSchool($supplier);$supplier->update($this->validateData($r));return redirect()->route('admin.suppliers.index')->with('success','Supplier updated successfully.');}
    public function destroy(Suppliers $supplier){$this->ensureSchool($supplier);abort_if($supplier->inventoryItems()->exists()||$supplier->assets()->exists(),422,'Supplier is referenced by inventory or assets.');$supplier->delete();return back()->with('success','Supplier deleted successfully.');}
    private function validateData(Request $r):array{return $r->validate(['name'=>'required|string|max:255','contact_person'=>'nullable|string|max:255','phone'=>'nullable|string|max:50','email'=>'nullable|email|max:255','address'=>'nullable|string|max:5000']);}
}
