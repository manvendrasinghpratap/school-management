<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\PortalNotification;
use App\Services\PortalDataService;
use Illuminate\Http\Request;

class PortalController extends Controller
{
    public function __construct(private PortalDataService $portal) {}

    public function dashboard(Request $request)
    {
        $user = $request->user();
        if ($user->hasRole('Student')) return response()->json(['status' => true, 'data' => $this->portal->studentDashboard($this->portal->studentForUser($user))]);
        if ($user->hasRole('Parent')) return response()->json(['status' => true, 'data' => $this->portal->guardianDashboard($this->portal->guardianForUser($user))]);
        if ($user->hasRole('Teacher')) return response()->json(['status' => true, 'data' => $this->portal->teacherDashboard($this->portal->staffForUser($user))]);
        return response()->json(['status' => false, 'message' => 'Portal role is not configured.'], 403);
    }

    public function student(Request $request)
    {
        $student = $this->portal->studentForUser($request->user());
        return response()->json(['status' => true, 'data' => ['student' => $student->load('guardians'), 'academic' => $this->portal->studentAcademic($student), 'results' => $this->portal->studentResults($student), 'finance' => $this->portal->studentFinance($student), 'documents' => $this->portal->studentDocuments($student)]]);
    }

    public function parentChildren(Request $request)
    {
        $guardian = $this->portal->guardianForUser($request->user());
        return response()->json(['status' => true, 'data' => $guardian->students()->where('students.school_id', $guardian->school_id)->with('enrollments')->get()]);
    }

    public function parentChild(Request $request, int $student)
    {
        $guardian = $this->portal->guardianForUser($request->user());
        $child = $guardian->students()->where('students.school_id', $guardian->school_id)->whereKey($student)->firstOrFail();
        return response()->json(['status' => true, 'data' => ['student' => $child, 'dashboard' => $this->portal->studentDashboard($child), 'academic' => $this->portal->studentAcademic($child), 'results' => $this->portal->studentResults($child), 'finance' => $this->portal->studentFinance($child)]]);
    }

    public function teacher(Request $request)
    {
        $staff = $this->portal->staffForUser($request->user());
        return response()->json(['status' => true, 'data' => $this->portal->teacherDashboard($staff) + ['students' => $this->portal->teacherStudents($staff)]]);
    }

    public function attendance(Request $request)
    {
        if ($request->user()->hasRole('Student')) {
            $student = $this->portal->studentForUser($request->user());
            $items = Attendance::with('course')->where('school_id', $request->user()->school_id)->where('student_id', $student->id)->latest('attendance_date')->paginate(30);
        } else {
            return response()->json(['status' => false, 'message' => 'Use the student or parent attendance context.'], 422);
        }
        return response()->json(['status' => true, 'data' => $items]);
    }

    public function notifications(Request $request)
    {
        return response()->json(['status' => true, 'data' => PortalNotification::where('user_id', $request->user()->id)->latest()->paginate(30)]);
    }

    public function notificationRead(Request $request, PortalNotification $notification)
    {
        abort_unless($notification->user_id === $request->user()->id, 404);
        $notification->update(['read_at' => now()]);
        return response()->json(['status' => true, 'message' => 'Notification marked as read.']);
    }
}
