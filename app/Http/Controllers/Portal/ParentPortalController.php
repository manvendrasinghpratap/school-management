<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Services\PortalDataService;
use Illuminate\Http\Request;

class ParentPortalController extends Controller
{
    public function __construct(private PortalDataService $portal) {}

    public function dashboard(Request $request)
    {
        $guardian = $this->portal->guardianForUser($request->user());
        return view('portal.parent.dashboard', $this->portal->guardianDashboard($guardian));
    }

    public function children(Request $request)
    {
        $guardian = $this->portal->guardianForUser($request->user());
        return view('portal.parent.children', ['guardian' => $guardian, 'students' => $guardian->students()->where('students.school_id', $guardian->school_id)->with('enrollments')->get()]);
    }

    public function child(Request $request, int $student)
    {
        $guardian = $this->portal->guardianForUser($request->user());
        $student = $guardian->students()->where('students.school_id', $guardian->school_id)->whereKey($student)->firstOrFail();
        return view('portal.parent.child', ['guardian' => $guardian, 'student' => $student] + $this->portal->studentDashboard($student));
    }

    public function attendance(Request $request, int $student)
    {
        $student = $this->ownedChild($request, $student);
        $items = \App\Models\Attendance::with('course')->where('school_id', $request->user()->school_id)->where('student_id', $student->id)->latest('attendance_date')->paginate(30);
        return view('portal.parent.attendance', compact('student', 'items'));
    }

    public function results(Request $request, int $student)
    {
        $student = $this->ownedChild($request, $student);
        return view('portal.parent.results', ['student' => $student] + $this->portal->studentResults($student));
    }

    public function fees(Request $request, int $student)
    {
        $student = $this->ownedChild($request, $student);
        return view('portal.parent.fees', ['student' => $student] + $this->portal->studentFinance($student));
    }

    private function ownedChild(Request $request, int $studentId)
    {
        $guardian = $this->portal->guardianForUser($request->user());
        return $guardian->students()->where('students.school_id', $guardian->school_id)->whereKey($studentId)->firstOrFail();
    }
}
