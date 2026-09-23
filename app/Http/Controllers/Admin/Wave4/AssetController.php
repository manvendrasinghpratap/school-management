<?php
namespace App\Http\Controllers\Admin\Wave4;
use App\Models\Assets;use App\Models\Suppliers;use Illuminate\Http\Request;use Illuminate\Validation\Rule;
class AssetController extends SchoolScopedController
{
 public function index(Request $r){$items=Assets::where('school_id',$this->schoolId())->with(['supplier','assignedTo'])->when($r->filled('search'),fn($q)=>$q->where(fn($x)=>$x->where('asset_code','like','%'.trim($r->search).'%')->orWhere('name','like','%'.trim($r->search).'%')->orWhere('category','like','%'.trim($r->search).'%')))->orderBy('name')->paginate(20)->withQueryString();return view('admin.wave4.assets.index',compact('items'));}
 public function create(){return view('admin.wave4.assets.form',['item'=>new Assets,'suppliers'=>Suppliers::where('school_id',$this->schoolId())->orderBy('name')->get(),'users'=>$this->schoolUsers()]);}
 public function store(Request $r){$d=$this->validateData($r);$d['school_id']=$this->schoolId();Assets::create($d);return redirect()->route('admin.assets.index')->with('success','Asset created successfully.');}
 public function edit(Assets $asset){$this->ensureSchool($asset);return view('admin.wave4.assets.form',['item'=>$asset,'suppliers'=>Suppliers::where('school_id',$this->schoolId())->orderBy('name')->get(),'users'=>$this->schoolUsers()]);}
 public function update(Request $r,Assets $asset){$this->ensureSchool($asset);$asset->update($this->validateData($r));return redirect()->route('admin.assets.index')->with('success','Asset updated successfully.');}
 public function destroy(Assets $asset){$this->ensureSchool($asset);$asset->delete();return back()->with('success','Asset deleted successfully.');}
 private function validateData(Request $r):array{return $r->validate(['supplier_id'=>['nullable','integer',Rule::exists('suppliers','id')->where(fn($q)=>$q->where('school_id',$this->schoolId()))],'asset_code'=>'required|string|max:100','name'=>'required|string|max:255','category'=>'nullable|string|max:100','purchase_date'=>'nullable|date','purchase_cost'=>'nullable|numeric|min:0','location'=>'nullable|string|max:255','condition_status'=>['required',Rule::in(['new','good','fair','damaged','disposed'])],'assigned_to'=>['nullable','integer',Rule::exists('users','id')->where(fn($q)=>$q->where('school_id',$this->schoolId()))],'description'=>'nullable|string|max:5000']);}
}
