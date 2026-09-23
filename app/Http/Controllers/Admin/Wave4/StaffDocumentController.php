<?php
namespace App\Http\Controllers\Admin\Wave4;
use App\Models\Staff;use App\Models\StaffDocuments;use Illuminate\Http\Request;use Illuminate\Support\Facades\Storage;use Illuminate\Validation\Rule;
class StaffDocumentController extends SchoolScopedController
{
 public function index(Staff $staff){$this->ensureSchool($staff);$documents=StaffDocuments::where('school_id',$this->schoolId())->where('staff_id',$staff->id)->latest()->get();return view('admin.wave4.staff-documents.index',compact('staff','documents'));}
 public function store(Request $r,Staff $staff){$this->ensureSchool($staff);$d=$r->validate(['document_type'=>'required|string|max:100','document_name'=>'nullable|string|max:255','document'=>['required','file','mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx','max:10240']]);$path=$r->file('document')->store('staff/documents','public');StaffDocuments::create(['school_id'=>$this->schoolId(),'staff_id'=>$staff->id,'document_type'=>$d['document_type'],'document_name'=>$d['document_name']??$r->file('document')->getClientOriginalName(),'file_path'=>$path,'uploaded_by'=>auth()->id()]);return back()->with('success','Staff document uploaded successfully.');}
 public function download(Staff $staff,StaffDocuments $document){$this->ensureSchool($staff);abort_unless((int)$document->school_id===$this->schoolId()&&(int)$document->staff_id===(int)$staff->id,404);abort_unless(Storage::disk('public')->exists($document->file_path),404,'Document file not found.');return Storage::disk('public')->download($document->file_path,$document->document_name ?: basename($document->file_path));}
 public function destroy(Staff $staff,StaffDocuments $document){$this->ensureSchool($staff);abort_unless((int)$document->school_id===$this->schoolId()&&(int)$document->staff_id===(int)$staff->id,404);$path=$document->file_path;$document->delete();if($path)Storage::disk('public')->delete($path);return back()->with('success','Staff document deleted successfully.');}
}
