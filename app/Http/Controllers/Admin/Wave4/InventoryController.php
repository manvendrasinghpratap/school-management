<?php
namespace App\Http\Controllers\Admin\Wave4;
use App\Models\InventoryItems;use App\Models\Suppliers;use Illuminate\Http\Request;use Illuminate\Validation\Rule;
class InventoryController extends SchoolScopedController
{
 public function index(Request $r){$items=InventoryItems::where('school_id',$this->schoolId())->with('supplier')->when($r->filled('search'),fn($q)=>$q->where(fn($x)=>$x->where('name','like','%'.trim($r->search).'%')->orWhere('item_code','like','%'.trim($r->search).'%')->orWhere('category','like','%'.trim($r->search).'%')))->orderBy('name')->paginate(20)->withQueryString();return view('admin.wave4.inventory.index',compact('items'));}
 public function create(){return view('admin.wave4.inventory.form',['item'=>new InventoryItems,'suppliers'=>Suppliers::where('school_id',$this->schoolId())->orderBy('name')->get()]);}
 public function store(Request $r){$d=$this->validateData($r);$d['school_id']=$this->schoolId();InventoryItems::create($d);return redirect()->route('admin.inventory.index')->with('success','Inventory item created successfully.');}
 public function edit(InventoryItems $inventory){$this->ensureSchool($inventory);return view('admin.wave4.inventory.form',['item'=>$inventory,'suppliers'=>Suppliers::where('school_id',$this->schoolId())->orderBy('name')->get()]);}
 public function update(Request $r,InventoryItems $inventory){$this->ensureSchool($inventory);$inventory->update($this->validateData($r));return redirect()->route('admin.inventory.index')->with('success','Inventory item updated successfully.');}
 public function destroy(InventoryItems $inventory){$this->ensureSchool($inventory);$inventory->delete();return back()->with('success','Inventory item deleted successfully.');}
 private function validateData(Request $r):array{return $r->validate(['supplier_id'=>['nullable','integer',Rule::exists('suppliers','id')->where(fn($q)=>$q->where('school_id',$this->schoolId()))],'name'=>'required|string|max:255','item_code'=>'nullable|string|max:100','category'=>'nullable|string|max:100','quantity'=>'required|numeric|min:0','unit'=>'nullable|string|max:50','reorder_level'=>'required|numeric|min:0','unit_cost'=>'nullable|numeric|min:0','description'=>'nullable|string|max:5000']);}
}
