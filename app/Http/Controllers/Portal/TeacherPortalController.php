<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\CourseAssignment;
use App\Models\Mark;
use App\Models\Student;
use App\Services\PortalDataService;
use Illuminate\Http\Request;

class TeacherPortalController extends Controller
{
    public function __construct(private PortalDataService $portal) {}

    public function dashboard(Request $request)
    {
        $staff = $this->portal->staffForUser($request->user());
        return view('portal.teacher.dashboard', $this->portal->teacherDashboard($staff));
    }

    public function profile(Request $request)
    {
        $staff = $this->portal->staffForUser($request->user());
        return view('portal.teacher.profile', compact('staff'));
    }

    public function courses(Request $request)
    {
        $staff = $this->portal->staffForUser($request->user());
        $assignments = $this->portal->teacherDashboard($staff)['assignments'];
        return view('portal.teacher.courses', compact('staff', 'assignments'));
    }

    public function students(Request $request)
    {
        $staff = $this->portal->staffForUser($request->user());
        return view('portal.teacher.students', ['staff' => $staff, 'students' => $this->portal->teacherStudents($staff)]);
    }

    public function attendance(Request $request)
    {
        $staff = $this->portal->staffForUser($request->user());
        $studentIds = $this->portal->teacherStudents($staff)->pluck('id');
        $items = Attendance::with('student')->where('school_id', $staff->school_id)->whereIn('student_id', $studentIds)->latest('attendance_date')->paginate(40);
        return view('portal.teacher.attendance', compact('staff', 'items'));
    }

    public function marks(Request $request)
    {
        $staff = $this->portal->staffForUser($request->user());
        $studentIds = $this->portal->teacherStudents($staff)->pluck('id');
        $items = Mark::with(['student', 'course', 'examination'])->whereIn('student_id', $studentIds)->latest()->paginate(40);
        return view('portal.teacher.marks', compact('staff', 'items'));
    }

    public function timetable(Request $request)
    {
        $staff = $this->portal->staffForUser($request->user());
        return view('portal.teacher.timetable', ['staff' => $staff, 'items' => $this->portal->teacherDashboard($staff)['timetable']]);
    }
}
