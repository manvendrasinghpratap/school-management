<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Classes;
use App\Models\Courses;
use App\Models\School;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Attendance List
    |--------------------------------------------------------------------------
    */

    public function index(Request $request): View
{
    abort_unless(
        auth()->user()?->can('attendance.view'),
        403
    );

    $user = auth()->user();

    abort_unless($user, 403);

    /*
    |--------------------------------------------------------------------------
    | Determine Current School
    |--------------------------------------------------------------------------
    */

    if ($user->hasRole('Super Admin')) {

        $school = School::find($user->school_id);

    } else {

        abort_unless($user->school_id, 403);

        $school = School::findOrFail(
            $user->school_id
        );
    }

    abort_unless($school, 403);

    $schoolId = (int) $school->id;


    /*
    |--------------------------------------------------------------------------
    | Load Filter Data
    |--------------------------------------------------------------------------
    */

    $classes = Classes::query()
        ->where('school_id', $schoolId)
        ->where('is_active', true)
        ->orderBy('name')
        ->get([
            'id',
            'name',
            'code',
        ]);


    $sections = Section::query()
        ->whereHas('class', function ($query) use ($schoolId) {

            $query->where('school_id', $schoolId);

        })
        ->where('is_active', true)
        ->orderBy('name')
        ->get([
            'id',
            'class_id',
            'name',
            'code',
        ]);


    /*
    |--------------------------------------------------------------------------
    | Attendance Query
    |--------------------------------------------------------------------------
    */

    $attendanceQuery = Attendance::query()
        ->where('school_id', $schoolId)
        ->with([
            'student',
            'course',
            'classModel',
            'section',
            'recordedBy',
        ])
        ->orderByDesc('attendance_date')
        ->orderByDesc('id');


    /*
    |--------------------------------------------------------------------------
    | Date Filter
    |--------------------------------------------------------------------------
    */

    if ($request->filled('attendance_date')) {

        $attendanceQuery->whereDate(
            'attendance_date',
            $request->attendance_date
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Class Filter
    |--------------------------------------------------------------------------
    */

    if ($request->filled('class_id')) {

        $attendanceQuery->where(
            'class_id',
            (int) $request->class_id
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Section Filter
    |--------------------------------------------------------------------------
    */

    if ($request->filled('section_id')) {

        $attendanceQuery->where(
            'section_id',
            (int) $request->section_id
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Attendance Type Filter
    |--------------------------------------------------------------------------
    */

    if ($request->attendance_type === 'daily') {

        $attendanceQuery->whereNull(
            'course_id'
        );

    } elseif ($request->attendance_type === 'subject') {

        $attendanceQuery->whereNotNull(
            'course_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Status Filter
    |--------------------------------------------------------------------------
    */

    if ($request->filled('status')) {

        $attendanceQuery->where(
            'status',
            $request->status
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Paginate
    |--------------------------------------------------------------------------
    */

    $attendance = $attendanceQuery
        ->paginate(25)
        ->withQueryString();


    /*
    |--------------------------------------------------------------------------
    | Return View
    |--------------------------------------------------------------------------
    */

    return view(
        'admin.attendance.index',
        compact(
            'school',
            'classes',
            'sections',
            'attendance'
        )
    );
}


    /*
    |--------------------------------------------------------------------------
    | Create Attendance
    |--------------------------------------------------------------------------
    */

    public function create(): View
    {
        abort_unless(
            auth()->user()?->can('attendance.mark'),
            403
        );

        $user = auth()->user();

        abort_unless($user, 403);


        /*
        |--------------------------------------------------------------------------
        | Determine Current School
        |--------------------------------------------------------------------------
        */

        if ($user->hasRole('Super Admin')) {

            $school = School::find($user->school_id);

        } else {

            abort_unless($user->school_id, 403);

            $school = School::findOrFail(
                $user->school_id
            );
        }

        abort_unless($school, 403);

        $schoolId = $school->id;


        /*
        |--------------------------------------------------------------------------
        | Load Active Classes
        |--------------------------------------------------------------------------
        */

        $classes = Classes::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'code',
            ]);


        /*
        |--------------------------------------------------------------------------
        | Load Active Courses
        |--------------------------------------------------------------------------
        */

        $courses = Courses::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'course_code',
            ]);


        /*
        |--------------------------------------------------------------------------
        | Load Active Sections
        |--------------------------------------------------------------------------
        |
        | Only sections belonging to classes within the current
        | school are loaded.
        |
        */

        $sections = Section::query()
            ->whereHas('class', function ($query) use ($schoolId) {

                $query->where('school_id', $schoolId);

            })
            ->where('is_active', true)
            ->orderBy('name')
            ->get([
                'id',
                'class_id',
                'name',
                'code',
            ]);


        /*
        |--------------------------------------------------------------------------
        | Attendance Settings
        |--------------------------------------------------------------------------
        */

        $attendanceMode = $school->setting(
            'attendance_mode',
            'both'
        );

        $attendanceAllowLate = filter_var(
            $school->setting(
                'attendance_allow_late',
                false
            ),
            FILTER_VALIDATE_BOOLEAN
        );

        $attendanceAllowExcused = filter_var(
            $school->setting(
                'attendance_allow_excused',
                false
            ),
            FILTER_VALIDATE_BOOLEAN
        );


        /*
        |--------------------------------------------------------------------------
        | Return Create View
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.attendance.create',
            compact(
                'school',
                'classes',
                'courses',
                'sections',
                'attendanceMode',
                'attendanceAllowLate',
                'attendanceAllowExcused'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Load Students
    |--------------------------------------------------------------------------
    |
    | Step 3:
    |
    | Class + Section
    |       ↓
    | Active Enrollments
    |       ↓
    | Active Students
    |
    */

    public function students(Request $request): JsonResponse
    {
        abort_unless(
            auth()->user()?->can('attendance.mark'),
            403
        );

        $user = auth()->user();

        abort_unless($user, 403);


        /*
        |--------------------------------------------------------------------------
        | Determine Current School
        |--------------------------------------------------------------------------
        */

        if ($user->hasRole('Super Admin')) {

            $school = School::find($user->school_id);

        } else {

            abort_unless($user->school_id, 403);

            $school = School::findOrFail(
                $user->school_id
            );
        }

        abort_unless($school, 403);

        $schoolId = $school->id;


        /*
        |--------------------------------------------------------------------------
        | Validate Request
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'class_id' => [
                'required',
                'integer',
            ],

            'section_id' => [
                'required',
                'integer',
            ],
        ]);


        $classId = (int) $validated['class_id'];

        $sectionId = (int) $validated['section_id'];


        /*
        |--------------------------------------------------------------------------
        | Verify Class Belongs to Current School
        |--------------------------------------------------------------------------
        */

        $classExists = Classes::query()
            ->where('id', $classId)
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->exists();

        abort_unless($classExists, 404);


        /*
        |--------------------------------------------------------------------------
        | Verify Section Belongs to Selected Class
        |--------------------------------------------------------------------------
        */

        $sectionExists = Section::query()
            ->where('id', $sectionId)
            ->where('class_id', $classId)
            ->where('is_active', true)
            ->whereHas('class', function ($query) use ($schoolId) {

                $query->where('school_id', $schoolId);

            })
            ->exists();

        abort_unless($sectionExists, 404);


        /*
        |--------------------------------------------------------------------------
        | Load Active Enrolled Students
        |--------------------------------------------------------------------------
        |
        | We deliberately query Student rather than loading every
        | student in the school.
        |
        | A student must:
        |
        | 1. Belong to the current school
        | 2. Be active
        | 3. Have an active enrollment
        | 4. Be enrolled in the selected class
        | 5. Be enrolled in the selected section
        |
        */

        $students = Student::query()
            ->where('school_id', $schoolId)
            ->where('status', 'active')
            ->whereHas('enrollments', function ($query) use (
                $schoolId,
                $classId,
                $sectionId
            ) {

                $query
                    ->where('school_id', $schoolId)
                    ->where('class_id', $classId)
                    ->where('section_id', $sectionId)
                    ->where('status', 'active');

            })
            ->orderBy('first_name')
            ->orderBy('middle_name')
            ->orderBy('last_name')
            ->get([
                'id',
                'student_number',
                'admission_number',
                'first_name',
                'middle_name',
                'last_name',
            ]);


        /*
        |--------------------------------------------------------------------------
        | Return Student Data
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'success' => true,

            'count' => $students->count(),

            'students' => $students->map(
                function ($student) {

                    return [
                        'id' => $student->id,

                        'student_number' =>
                            $student->student_number,

                        'admission_number' =>
                            $student->admission_number,

                        'first_name' =>
                            $student->first_name,

                        'middle_name' =>
                            $student->middle_name,

                        'last_name' =>
                            $student->last_name,

                        'full_name' =>
                            trim(
                                $student->first_name
                                . ' '
                                . ($student->middle_name ?? '')
                                . ' '
                                . $student->last_name
                            ),
                    ];

                }
            )->values(),
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Store Attendance
    |--------------------------------------------------------------------------
    |
    | Actual attendance saving will be implemented after
    | the student loading step has been tested.
    |
    */

    public function store(Request $request): RedirectResponse
{
    abort_unless(
        auth()->user()?->can('attendance.mark'),
        403
    );

    $user = auth()->user();

    abort_unless($user, 403);


    /*
    |--------------------------------------------------------------------------
    | Determine Current School
    |--------------------------------------------------------------------------
    */

    if ($user->hasRole('Super Admin')) {

        $school = School::find($user->school_id);

    } else {

        abort_unless($user->school_id, 403);

        $school = School::findOrFail(
            $user->school_id
        );
    }

    abort_unless($school, 403);

    $schoolId = (int) $school->id;


    /*
    |--------------------------------------------------------------------------
    | Attendance Settings
    |--------------------------------------------------------------------------
    */

    $attendanceEnabled = filter_var(
        $school->setting(
            'attendance_enabled',
            false
        ),
        FILTER_VALIDATE_BOOLEAN
    );

    abort_unless($attendanceEnabled, 403);


    $attendanceMode = $school->setting(
        'attendance_mode',
        'both'
    );

    $attendanceAllowLate = filter_var(
        $school->setting(
            'attendance_allow_late',
            false
        ),
        FILTER_VALIDATE_BOOLEAN
    );

    $attendanceAllowExcused = filter_var(
        $school->setting(
            'attendance_allow_excused',
            false
        ),
        FILTER_VALIDATE_BOOLEAN
    );


    /*
    |--------------------------------------------------------------------------
    | Validate Main Attendance Fields
    |--------------------------------------------------------------------------
    */

    $validated = $request->validate([

        'attendance_date' => [
            'required',
            'date',
        ],

        'class_id' => [
            'required',
            'integer',
        ],

        'section_id' => [
            'required',
            'integer',
        ],

        'attendance_type' => [
            'required',
            'string',
            'in:daily,subject',
        ],

        'course_id' => [
            'nullable',
            'integer',
        ],

        'attendance' => [
            'required',
            'array',
            'min:1',
        ],

        'attendance.*.student_id' => [
            'required',
            'integer',
        ],

        'attendance.*.status' => [
            'required',
            'string',
            'in:present,absent,late,excused',
        ],

        'attendance.*.remarks' => [
            'nullable',
            'string',
            'max:1000',
        ],

    ]);


    $attendanceDate = $validated['attendance_date'];

    $classId = (int) $validated['class_id'];

    $sectionId = (int) $validated['section_id'];

    $attendanceType = $validated['attendance_type'];

    $courseId = !empty($validated['course_id'])
        ? (int) $validated['course_id']
        : null;


    /*
    |--------------------------------------------------------------------------
    | Validate Attendance Mode
    |--------------------------------------------------------------------------
    */

    if (
        $attendanceMode === 'daily' &&
        $attendanceType !== 'daily'
    ) {

        return back()
            ->withInput()
            ->withErrors([
                'attendance_type' =>
                    'Subject attendance is not enabled for this school.',
            ]);
    }


    if (
        $attendanceMode === 'subject' &&
        $attendanceType !== 'subject'
    ) {

        return back()
            ->withInput()
            ->withErrors([
                'attendance_type' =>
                    'Daily attendance is not enabled for this school.',
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Daily Attendance Cannot Have Course
    |--------------------------------------------------------------------------
    */

    if (
        $attendanceType === 'daily' &&
        $courseId !== null
    ) {

        return back()
            ->withInput()
            ->withErrors([
                'course_id' =>
                    'A course cannot be selected for daily attendance.',
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Subject Attendance Requires Course
    |--------------------------------------------------------------------------
    */

    if (
        $attendanceType === 'subject' &&
        $courseId === null
    ) {

        return back()
            ->withInput()
            ->withErrors([
                'course_id' =>
                    'Please select a subject/course for subject attendance.',
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Validate Class
    |--------------------------------------------------------------------------
    */

    $class = Classes::query()
        ->where('id', $classId)
        ->where('school_id', $schoolId)
        ->where('is_active', true)
        ->first();

    if (!$class) {

        return back()
            ->withInput()
            ->withErrors([
                'class_id' =>
                    'The selected class is invalid.',
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Validate Section
    |--------------------------------------------------------------------------
    */

    $section = Section::query()
        ->where('id', $sectionId)
        ->where('class_id', $classId)
        ->where('is_active', true)
        ->whereHas('class', function ($query) use ($schoolId) {

            $query->where('school_id', $schoolId);

        })
        ->first();

    if (!$section) {

        return back()
            ->withInput()
            ->withErrors([
                'section_id' =>
                    'The selected section does not belong to the selected class.',
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Validate Course
    |--------------------------------------------------------------------------
    */

    if ($attendanceType === 'subject') {

        $course = Courses::query()
            ->where('id', $courseId)
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->first();

        if (!$course) {

            return back()
                ->withInput()
                ->withErrors([
                    'course_id' =>
                        'The selected subject/course is invalid.',
                ]);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Validate Individual Statuses
    |--------------------------------------------------------------------------
    */

    foreach ($validated['attendance'] as $attendanceData) {

        $status = $attendanceData['status'];


        if (
            $status === 'late' &&
            !$attendanceAllowLate
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'attendance' =>
                        'Late attendance is not enabled in Attendance Setup.',
                ]);
        }


        if (
            $status === 'excused' &&
            !$attendanceAllowExcused
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'attendance' =>
                        'Excused attendance is not enabled in Attendance Setup.',
                ]);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Verify Students
    |--------------------------------------------------------------------------
    */

    $studentIds = collect(
        $validated['attendance']
    )
        ->pluck('student_id')
        ->map(fn ($id) => (int) $id)
        ->unique()
        ->values();


    /*
    |--------------------------------------------------------------------------
    | Prevent Duplicate Student IDs
    |--------------------------------------------------------------------------
    */

    if (
        $studentIds->count() !==
        count($validated['attendance'])
    ) {

        return back()
            ->withInput()
            ->withErrors([
                'attendance' =>
                    'A student appears more than once in the attendance list.',
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Verify Every Student
    |--------------------------------------------------------------------------
    |
    | Each student must:
    |
    | - belong to the current school
    | - be active
    | - have an active enrollment
    | - belong to the selected class
    | - belong to the selected section
    |
    */

    $eligibleStudentIds = Student::query()
        ->where('school_id', $schoolId)
        ->where('status', 'active')
        ->whereIn('id', $studentIds)
        ->whereHas('enrollments', function ($query) use (
            $schoolId,
            $classId,
            $sectionId
        ) {

            $query
                ->where('school_id', $schoolId)
                ->where('class_id', $classId)
                ->where('section_id', $sectionId)
                ->where('status', 'active');

        })
        ->pluck('id')
        ->map(fn ($id) => (int) $id);


    /*
    |--------------------------------------------------------------------------
    | Detect Invalid Students
    |--------------------------------------------------------------------------
    */

    $invalidStudentIds = $studentIds
        ->diff($eligibleStudentIds);


    if ($invalidStudentIds->isNotEmpty()) {

        return back()
            ->withInput()
            ->withErrors([
                'attendance' =>
                    'One or more selected students are not actively enrolled in the selected class and section.',
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Database Transaction
    |--------------------------------------------------------------------------
    */

    \Illuminate\Support\Facades\DB::beginTransaction();

    try {

        foreach ($validated['attendance'] as $attendanceData) {

            $studentId = (int) $attendanceData['student_id'];

            $status = $attendanceData['status'];

            $remarks = $attendanceData['remarks'] ?? null;


            /*
            |--------------------------------------------------------------------------
            | Check Duplicate Attendance
            |--------------------------------------------------------------------------
            |
            | Daily:
            | student + date + class + section + course NULL
            |
            | Subject:
            | student + date + class + section + course
            |
            */

            $duplicateQuery = Attendance::query()
                ->where('school_id', $schoolId)
                ->where('student_id', $studentId)
                ->where('class_id', $classId)
                ->where('section_id', $sectionId)
                ->whereDate(
                    'attendance_date',
                    $attendanceDate
                );


            if ($attendanceType === 'daily') {

                $duplicateQuery->whereNull(
                    'course_id'
                );

            } else {

                $duplicateQuery->where(
                    'course_id',
                    $courseId
                );
            }


            if ($duplicateQuery->exists()) {

                \Illuminate\Support\Facades\DB::rollBack();

                return back()
                    ->withInput()
                    ->withErrors([
                        'attendance' =>
                            'Attendance has already been recorded for one or more students for the selected date and attendance type.',
                    ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Create Attendance Record
            |--------------------------------------------------------------------------
            */

            Attendance::create([

                'school_id' =>
                    $schoolId,

                'student_id' =>
                    $studentId,

                'course_id' =>
                    $courseId,

                'class_id' =>
                    $classId,

                'section_id' =>
                    $sectionId,

                'attendance_date' =>
                    $attendanceDate,

                'status' =>
                    $status,

                'recorded_by' =>
                    $user->id,

                'remarks' =>
                    $remarks,

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Commit Transaction
        |--------------------------------------------------------------------------
        */

        \Illuminate\Support\Facades\DB::commit();


    } catch (\Throwable $e) {

        \Illuminate\Support\Facades\DB::rollBack();

        report($e);

        return back()
            ->withInput()
            ->withErrors([
                'attendance' =>
                    'Attendance could not be saved. Please try again.',
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Success
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->route('admin.attendance.index')
        ->with(
            'success',
            'Student attendance recorded successfully.'
        );
}


    /*
    |--------------------------------------------------------------------------
    | Show Attendance
    |--------------------------------------------------------------------------
    */

    public function show(Attendance $attendance): View
    {
        abort_unless(
            auth()->user()?->can('attendance.view'),
            403
        );

        $this->authorizeAttendanceSchool($attendance);

        return view(
            'admin.attendance.show',
            compact('attendance')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Edit Attendance
    |--------------------------------------------------------------------------
    */

    public function edit(Attendance $attendance): View
{
    abort_unless(
        auth()->user()?->can('attendance.update'),
        403
    );

    $user = auth()->user();

    abort_unless($user, 403);

    /*
    |--------------------------------------------------------------------------
    | Determine Current School
    |--------------------------------------------------------------------------
    */

    if ($user->hasRole('Super Admin')) {

        $schoolId = (int) $user->school_id;

    } else {

        abort_unless($user->school_id, 403);

        $schoolId = (int) $user->school_id;
    }

    /*
    |--------------------------------------------------------------------------
    | Prevent Cross-School Access
    |--------------------------------------------------------------------------
    */

    abort_unless(
        (int) $attendance->school_id === $schoolId,
        403
    );

    /*
    |--------------------------------------------------------------------------
    | Load Relationships
    |--------------------------------------------------------------------------
    */

    $attendance->load([
        'student',
        'course',
        'classModel',
        'section',
        'recordedBy',
    ]);

    /*
    |--------------------------------------------------------------------------
    | Attendance Settings
    |--------------------------------------------------------------------------
    */

    $school = School::findOrFail($schoolId);

    $attendanceAllowLate = filter_var(
        $school->setting('attendance_allow_late', true),
        FILTER_VALIDATE_BOOLEAN
    );

    $attendanceAllowExcused = filter_var(
        $school->setting('attendance_allow_excused', true),
        FILTER_VALIDATE_BOOLEAN
    );

    return view(
        'admin.attendance.edit',
        compact(
            'attendance',
            'school',
            'attendanceAllowLate',
            'attendanceAllowExcused'
        )
    );
}


    /*
    |--------------------------------------------------------------------------
    | Update Attendance
    |--------------------------------------------------------------------------
    */

    public function update(
    Request $request,
    Attendance $attendance
): RedirectResponse {
    abort_unless(
        auth()->user()?->can('attendance.update'),
        403
    );

    $user = auth()->user();

    abort_unless($user, 403);

    /*
    |--------------------------------------------------------------------------
    | Determine Current School
    |--------------------------------------------------------------------------
    */

    if ($user->hasRole('Super Admin')) {

        $schoolId = (int) $user->school_id;

    } else {

        abort_unless($user->school_id, 403);

        $schoolId = (int) $user->school_id;
    }

    /*
    |--------------------------------------------------------------------------
    | Prevent Cross-School Access
    |--------------------------------------------------------------------------
    */

    abort_unless(
        (int) $attendance->school_id === $schoolId,
        403
    );

    /*
    |--------------------------------------------------------------------------
    | Attendance Settings
    |--------------------------------------------------------------------------
    */

    $school = School::findOrFail($schoolId);

    $attendanceEnabled = filter_var(
        $school->setting('attendance_enabled', false),
        FILTER_VALIDATE_BOOLEAN
    );

    abort_unless($attendanceEnabled, 403);

    $attendanceAllowLate = filter_var(
        $school->setting('attendance_allow_late', true),
        FILTER_VALIDATE_BOOLEAN
    );

    $attendanceAllowExcused = filter_var(
        $school->setting('attendance_allow_excused', true),
        FILTER_VALIDATE_BOOLEAN
    );

    /*
    |--------------------------------------------------------------------------
    | Validate
    |--------------------------------------------------------------------------
    */

    $validated = $request->validate([
        'status' => [
            'required',
            'in:present,absent,late,excused',
        ],

        'remarks' => [
            'nullable',
            'string',
            'max:1000',
        ],
    ]);

    /*
    |--------------------------------------------------------------------------
    | Respect Attendance Settings
    |--------------------------------------------------------------------------
    */

    if (
        $validated['status'] === 'late'
        && ! $attendanceAllowLate
    ) {
        throw ValidationException::withMessages([
            'status' => [
                'Late attendance is currently disabled.'
            ],
        ]);
    }

    if (
        $validated['status'] === 'excused'
        && ! $attendanceAllowExcused
    ) {
        throw ValidationException::withMessages([
            'status' => [
                'Excused attendance is currently disabled.'
            ],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    $attendance->update([
        'status' => $validated['status'],
        'remarks' => $validated['remarks'] ?? null,
    ]);

    return redirect()
        ->route(
            'admin.attendance.show',
            $attendance
        )
        ->with(
            'success',
            'Attendance record updated successfully.'
        );
}


    /*
    |--------------------------------------------------------------------------
    | Delete Attendance
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Attendance $attendance
    ): RedirectResponse {

        abort_unless(
            auth()->user()?->can('attendance.update'),
            403
        );

        $this->authorizeAttendanceSchool($attendance);

        $attendance->delete();

        return back()->with(
            'success',
            'Attendance record deleted successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Attendance Reports
    |--------------------------------------------------------------------------
    */

    public function reports(Request $request): View
{
    abort_unless(
        auth()->user()?->can('attendance.reports'),
        403
    );

    $user = auth()->user();

    abort_unless($user, 403);

    /*
    |--------------------------------------------------------------------------
    | Determine Current School
    |--------------------------------------------------------------------------
    */

    if ($user->hasRole('Super Admin')) {
        $schoolId = (int) $user->school_id;
    } else {
        abort_unless($user->school_id, 403);

        $schoolId = (int) $user->school_id;
    }

    /*
    |--------------------------------------------------------------------------
    | Filters
    |--------------------------------------------------------------------------
    */

    $dateFrom = $request->input(
        'date_from',
        now()->format('Y-m-d')
    );

    $dateTo = $request->input(
        'date_to',
        now()->format('Y-m-d')
    );

    $classId = $request->input('class_id');
    $sectionId = $request->input('section_id');
    $type = $request->input('type', 'all');
    $status = $request->input('status', 'all');

    /*
    |--------------------------------------------------------------------------
    | Classes
    |--------------------------------------------------------------------------
    */

    $classes = Classes::query()
        ->where('school_id', $schoolId)
        ->orderBy('name')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Sections
    |--------------------------------------------------------------------------
    */

    $sections = Section::query()
    ->join('classes', 'classes.id', '=', 'sections.class_id')
    ->where('classes.school_id', $schoolId)
    ->when(
        $classId,
        fn ($query) =>
            $query->where('sections.class_id', $classId)
    )
    ->select(
        'sections.id',
        'sections.class_id',
        'sections.name',
        'classes.name as class_name'
    )
    ->orderBy('classes.name')
    ->orderBy('sections.name')
    ->get();

    /*
    |--------------------------------------------------------------------------
    | Attendance Query
    |--------------------------------------------------------------------------
    */

    $query = Attendance::query()
        ->where('attendance.school_id', $schoolId)
        ->whereBetween(
            'attendance.attendance_date',
            [$dateFrom, $dateTo]
        )
        ->with([
            'student',
            'course',
            'classModel',
            'section',
            'recordedBy',
        ]);

    /*
    |--------------------------------------------------------------------------
    | Class Filter
    |--------------------------------------------------------------------------
    */

    if ($classId) {
        $query->where(
            'attendance.class_id',
            $classId
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Section Filter
    |--------------------------------------------------------------------------
    */

    if ($sectionId) {
        $query->where(
            'attendance.section_id',
            $sectionId
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Attendance Type Filter
    |--------------------------------------------------------------------------
    */

    if ($type === 'daily') {
        $query->whereNull(
            'attendance.course_id'
        );
    }

    if ($type === 'subject') {
        $query->whereNotNull(
            'attendance.course_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Status Filter
    |--------------------------------------------------------------------------
    */

    if (
        in_array(
            $status,
            [
                'present',
                'absent',
                'late',
                'excused',
            ],
            true
        )
    ) {
        $query->where(
            'attendance.status',
            $status
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Get Records
    |--------------------------------------------------------------------------
    */

    $records = $query
        ->orderBy(
            'attendance.attendance_date',
            'desc'
        )
        ->orderBy(
            'attendance.class_id'
        )
        ->orderBy(
            'attendance.section_id'
        )
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Summary
    |--------------------------------------------------------------------------
    */

    $total = $records->count();

    $present = $records
        ->where('status', 'present')
        ->count();

    $absent = $records
        ->where('status', 'absent')
        ->count();

    $late = $records
        ->where('status', 'late')
        ->count();

    $excused = $records
        ->where('status', 'excused')
        ->count();

    /*
    |--------------------------------------------------------------------------
    | Attendance Percentage
    |--------------------------------------------------------------------------
    |
    | Present + Late are treated as attendance.
    | Excused is excluded from the attendance percentage.
    |
    */

    $countedRecords = $present + $absent + $late;

    $attendancePercentage = $countedRecords > 0
        ? round(
            (($present + $late) / $countedRecords) * 100,
            2
        )
        : 0;

    /*
    |--------------------------------------------------------------------------
    | Student Summary
    |--------------------------------------------------------------------------
    */

    $studentSummary = $records
        ->groupBy('student_id')
        ->map(function ($studentRecords) {

            $student = $studentRecords->first()->student;

            $total = $studentRecords->count();

            $present = $studentRecords
                ->where('status', 'present')
                ->count();

            $absent = $studentRecords
                ->where('status', 'absent')
                ->count();

            $late = $studentRecords
                ->where('status', 'late')
                ->count();

            $excused = $studentRecords
                ->where('status', 'excused')
                ->count();

            $counted = $present + $absent + $late;

            $percentage = $counted > 0
                ? round(
                    (($present + $late) / $counted) * 100,
                    2
                )
                : 0;

            return [
                'student' => $student,
                'total' => $total,
                'present' => $present,
                'absent' => $absent,
                'late' => $late,
                'excused' => $excused,
                'percentage' => $percentage,
            ];
        })
        ->sortBy(function ($summary) {
            return strtolower(
                $summary['student']->last_name
                . ' '
                . $summary['student']->first_name
            );
        })
        ->values();

    /*
    |--------------------------------------------------------------------------
    | Return Report
    |--------------------------------------------------------------------------
    */

    return view(
        'admin.attendance.reports',
        compact(
            'classes',
            'sections',
            'records',
            'studentSummary',
            'dateFrom',
            'dateTo',
            'classId',
            'sectionId',
            'type',
            'status',
            'total',
            'present',
            'absent',
            'late',
            'excused',
            'attendancePercentage'
        )
    );
}


    /*
    |--------------------------------------------------------------------------
    | Attendance School Authorization
    |--------------------------------------------------------------------------
    */

    protected function authorizeAttendanceSchool(
        Attendance $attendance
    ): void {

        $user = auth()->user();

        abort_unless($user, 403);

        if ($user->hasRole('Super Admin')) {
            return;
        }

        abort_unless(
            (int) $user->school_id ===
            (int) $attendance->school_id,
            403
        );
    }
}