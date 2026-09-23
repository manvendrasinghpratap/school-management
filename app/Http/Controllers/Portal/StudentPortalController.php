<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Services\PortalDataService;
use Illuminate\Http\Request;

class StudentPortalController extends Controller
{
    public function __construct(private PortalDataService $portal) {}

    public function dashboard(Request $request)
    {
        $student = $this->portal->studentForUser($request->user());
        return view('portal.student.dashboard', $this->portal->studentDashboard($student));
    }

    public function profile(Request $request)
    {
        $student = $this->portal->studentForUser($request->user());
        return view('portal.student.profile', compact('student'));
    }

    public function attendance(Request $request)
    {
        $student = $this->portal->studentForUser($request->user());
        $items = \App\Models\Attendance::with('course')->where('school_id', $request->user()->school_id)->where('student_id', $student->id)->latest('attendance_date')->paginate(30);
        return view('portal.student.attendance', compact('student', 'items'));
    }

    public function timetable(Request $request)
    {
        $student = $this->portal->studentForUser($request->user());
        return view('portal.student.timetable', ['student' => $student] + $this->portal->studentAcademic($student));
    }

    public function courses(Request $request)
    {
        $student = $this->portal->studentForUser($request->user());
        $courses = $this->portal->studentAcademic($student)['courses'];
        return view('portal.student.courses', compact('student', 'courses'));
    }

    public function results(Request $request)
    {
        $student = $this->portal->studentForUser($request->user());
        return view('portal.student.results', ['student' => $student] + $this->portal->studentResults($student));
    }

    public function fees(Request $request)
    {
        $student = $this->portal->studentForUser($request->user());
        return view('portal.student.fees', ['student' => $student] + $this->portal->studentFinance($student));
    }

    public function documents(Request $request)
    {
        $student = $this->portal->studentForUser($request->user());
        return view('portal.student.documents', ['student' => $student, 'documents' => $this->portal->studentDocuments($student)]);
    }
}
