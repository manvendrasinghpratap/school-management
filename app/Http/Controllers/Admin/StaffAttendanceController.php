<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\StaffAttendance;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;



class StaffAttendanceController extends Controller
{
    public function index(Request $request): View
{
    abort_unless(
        auth()->user()?->can('staff-attendance.view'),
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

    $date = $request->input('date');
    $staffId = $request->input('staff_id');
    $status = $request->input('status');

    /*
    |--------------------------------------------------------------------------
    | Staff List
    |--------------------------------------------------------------------------
    */

    $staff = Staff::query()
        ->where('school_id', $schoolId)
        ->orderBy('first_name')
        ->orderBy('last_name')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Attendance Query
    |--------------------------------------------------------------------------
    */

    $query = StaffAttendance::query()
        ->where('school_id', $schoolId)
        ->with([
            'staff',
            'recordedBy',
        ]);

    if ($date) {
        $query->where(
            'attendance_date',
            $date
        );
    }

    if ($staffId) {
        $query->where(
            'staff_id',
            $staffId
        );
    }

    if (
        in_array(
            $status,
            [
                'present',
                'absent',
                'late',
                'half_day',
                'leave',
            ],
            true
        )
    ) {
        $query->where(
            'status',
            $status
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Results
    |--------------------------------------------------------------------------
    */

    $attendanceRecords = $query
        ->orderBy(
            'attendance_date',
            'desc'
        )
        ->orderBy(
            'staff_id'
        )
        ->paginate(25)
        ->withQueryString();

    return view(
        'admin.staff-attendance.index',
        compact(
            'attendanceRecords',
            'staff',
            'date',
            'staffId',
            'status'
        )
    );
}

    public function create(): View
{
    abort_unless(
        auth()->user()?->can('staff-attendance.mark'),
        403
    );

    $user = auth()->user();

    abort_unless($user, 403);

    if ($user->hasRole('Super Admin')) {
        $schoolId = (int) $user->school_id;
    } else {
        abort_unless($user->school_id, 403);

        $schoolId = (int) $user->school_id;
    }

    $staff = Staff::query()
        ->where('school_id', $schoolId)
        ->orderBy('first_name')
        ->orderBy('last_name')
        ->get();

    return view(
        'admin.staff-attendance.create',
        compact('staff')
    );
}

    public function store(Request $request): RedirectResponse
{
    abort_unless(
        auth()->user()?->can('staff-attendance.mark'),
        403
    );

    $user = auth()->user();

    abort_unless($user, 403);

    if ($user->hasRole('Super Admin')) {
        $schoolId = (int) $user->school_id;
    } else {
        abort_unless($user->school_id, 403);

        $schoolId = (int) $user->school_id;
    }

    $validated = $request->validate([
        'staff_id' => [
            'required',
            'integer',
        ],

        'attendance_date' => [
            'required',
            'date',
        ],

        'status' => [
            'required',
            'in:present,absent,late,half_day,leave',
        ],

        'check_in' => [
            'nullable',
            'date_format:H:i',
        ],

        'check_out' => [
            'nullable',
            'date_format:H:i',
            'after_or_equal:check_in',
        ],

        'remarks' => [
            'nullable',
            'string',
            'max:1000',
        ],
    ]);

    /*
    |--------------------------------------------------------------------------
    | Verify Staff Belongs to Current School
    |--------------------------------------------------------------------------
    */

    $staff = Staff::query()
        ->where('id', $validated['staff_id'])
        ->where('school_id', $schoolId)
        ->first();

    if (!$staff) {
        throw ValidationException::withMessages([
            'staff_id' => [
                'The selected staff member does not belong to this school.',
            ],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Prevent Duplicate Attendance
    |--------------------------------------------------------------------------
    */

    $alreadyExists = StaffAttendance::query()
        ->where('school_id', $schoolId)
        ->where('staff_id', $validated['staff_id'])
        ->where(
            'attendance_date',
            $validated['attendance_date']
        )
        ->exists();

    if ($alreadyExists) {
        throw ValidationException::withMessages([
            'attendance_date' => [
                'Attendance has already been recorded for this staff member for the selected date.',
            ],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Validate Check-In / Check-Out Logic
    |--------------------------------------------------------------------------
    */

    if (
        $validated['status'] === 'present'
        && empty($validated['check_in'])
    ) {
        throw ValidationException::withMessages([
            'check_in' => [
                'Check-in time is required when status is Present.',
            ],
        ]);
    }

    if (
        $validated['status'] === 'late'
        && empty($validated['check_in'])
    ) {
        throw ValidationException::withMessages([
            'check_in' => [
                'Check-in time is required when status is Late.',
            ],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Create Attendance
    |--------------------------------------------------------------------------
    */

    StaffAttendance::create([
        'school_id' => $schoolId,
        'staff_id' => $staff->id,
        'attendance_date' => $validated['attendance_date'],
        'status' => $validated['status'],
        'check_in' => $validated['check_in'] ?? null,
        'check_out' => $validated['check_out'] ?? null,
        'recorded_by' => $user->id,
        'remarks' => $validated['remarks'] ?? null,
    ]);

    return redirect()
        ->route('admin.staff-attendance.index')
        ->with(
            'success',
            'Staff attendance recorded successfully.'
        );
}

    public function show(
    StaffAttendance $staffAttendance
    ): View {
    abort_unless(
    auth()->user()?->can('staff-attendance.view'),
    403
    );

    $user = auth()->user();

    abort_unless($user, 403);

    if ($user->hasRole('Super Admin')) {
    $schoolId = (int) $user->school_id;
    } else {
    abort_unless($user->school_id, 403);

    $schoolId = (int) $user->school_id;
    }

    /*
    |--------------------------------------------------------------------------
    | School Security
    |--------------------------------------------------------------------------
    */

    abort_unless(
    (int) $staffAttendance->school_id === $schoolId,
    403
    );

    /*
    |--------------------------------------------------------------------------
    | Load Relationships
    |--------------------------------------------------------------------------
    */

    $staffAttendance->load([
    'staff',
    'recordedBy',
    ]);

    return view(
    'admin.staff-attendance.show',
    compact('staffAttendance')
    );
    }

    public function edit(
    StaffAttendance $staffAttendance
): View {
    abort_unless(
        auth()->user()?->can('staff-attendance.update'),
        403
    );

    $user = auth()->user();

    abort_unless($user, 403);

    if ($user->hasRole('Super Admin')) {
        $schoolId = (int) $user->school_id;
    } else {
        abort_unless($user->school_id, 403);

        $schoolId = (int) $user->school_id;
    }

    /*
    |--------------------------------------------------------------------------
    | School Security
    |--------------------------------------------------------------------------
    */

    abort_unless(
        (int) $staffAttendance->school_id === $schoolId,
        403
    );

    /*
    |--------------------------------------------------------------------------
    | Load Relationships
    |--------------------------------------------------------------------------
    */

    $staffAttendance->load([
        'staff',
        'recordedBy',
    ]);

    return view(
        'admin.staff-attendance.edit',
        compact('staffAttendance')
    );
}

    public function update(
    Request $request,
    StaffAttendance $staffAttendance
): RedirectResponse {
    abort_unless(
        auth()->user()?->can('staff-attendance.update'),
        403
    );

    $user = auth()->user();

    abort_unless($user, 403);

    if ($user->hasRole('Super Admin')) {
        $schoolId = (int) $user->school_id;
    } else {
        abort_unless($user->school_id, 403);

        $schoolId = (int) $user->school_id;
    }

    /*
    |--------------------------------------------------------------------------
    | School Security
    |--------------------------------------------------------------------------
    */

    abort_unless(
        (int) $staffAttendance->school_id === $schoolId,
        403
    );

    /*
    |--------------------------------------------------------------------------
    | Validate
    |--------------------------------------------------------------------------
    */

    $validated = $request->validate([
        'status' => [
            'required',
            'in:present,absent,late,half_day,leave',
        ],

        'check_in' => [
            'nullable',
            'date_format:H:i',
        ],

        'check_out' => [
            'nullable',
            'date_format:H:i',
            'after_or_equal:check_in',
        ],

        'remarks' => [
            'nullable',
            'string',
            'max:1000',
        ],
    ]);

    /*
    |--------------------------------------------------------------------------
    | Check-In Requirements
    |--------------------------------------------------------------------------
    */

    if (
        in_array(
            $validated['status'],
            ['present', 'late'],
            true
        )
        && empty($validated['check_in'])
    ) {
        throw ValidationException::withMessages([
            'check_in' => [
                'Check-in time is required when status is Present or Late.',
            ],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Update Attendance
    |--------------------------------------------------------------------------
    */

    $staffAttendance->update([
        'status' => $validated['status'],
        'check_in' => $validated['check_in'] ?? null,
        'check_out' => $validated['check_out'] ?? null,
        'remarks' => $validated['remarks'] ?? null,
    ]);

    return redirect()
        ->route(
            'admin.staff-attendance.show',
            $staffAttendance
        )
        ->with(
            'success',
            'Staff attendance updated successfully.'
        );
}

    public function destroy(
    StaffAttendance $staffAttendance
): RedirectResponse {
    abort_unless(
        auth()->user()?->can('staff-attendance.update'),
        403
    );

    $user = auth()->user();

    abort_unless($user, 403);

    if ($user->hasRole('Super Admin')) {
        $schoolId = (int) $user->school_id;
    } else {
        abort_unless($user->school_id, 403);

        $schoolId = (int) $user->school_id;
    }

    /*
    |--------------------------------------------------------------------------
    | School Security
    |--------------------------------------------------------------------------
    */

    abort_unless(
        (int) $staffAttendance->school_id === $schoolId,
        403
    );

    /*
    |--------------------------------------------------------------------------
    | Soft Delete
    |--------------------------------------------------------------------------
    */

    $staffAttendance->delete();

    return redirect()
        ->route('admin.staff-attendance.index')
        ->with(
            'success',
            'Staff attendance deleted successfully.'
        );
}

    public function reports(Request $request): View
{
    abort_unless(
        $request->user()?->can('staff-attendance.reports'),
        403
    );

    /*
    |--------------------------------------------------------------------------
    | Determine Current School
    |--------------------------------------------------------------------------
    */

    $user = auth()->user();

    abort_unless($user, 403);

    if ($user->hasRole('Super Admin')) {
        $schoolId = (int) $user->school_id;
    } else {
        abort_unless($user->school_id, 403);

        $schoolId = (int) $user->school_id;
    }


    /*
    |--------------------------------------------------------------------------
    | Staff List
    |--------------------------------------------------------------------------
    */

    $staff = Staff::query()
        ->where('school_id', $schoolId)
        ->orderBy('first_name')
        ->orderBy('last_name')
        ->get();


    /*
    |--------------------------------------------------------------------------
    | Attendance Query
    |--------------------------------------------------------------------------
    */

    $query = StaffAttendance::query()
        ->where('school_id', $schoolId)
        ->with([
            'staff',
            'recordedBy',
        ]);


    /*
    |--------------------------------------------------------------------------
    | Staff Filter
    |--------------------------------------------------------------------------
    */

    if ($request->filled('staff_id')) {

        $query->where(
            'staff_id',
            $request->integer('staff_id')
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Status Filter
    |--------------------------------------------------------------------------
    */

    if ($request->filled('status')) {

        $status = $request->string('status')->toString();

        if (
            in_array(
                $status,
                [
                    'present',
                    'absent',
                    'late',
                    'half_day',
                    'leave',
                ],
                true
            )
        ) {

            $query->where(
                'status',
                $status
            );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Start Date Filter
    |--------------------------------------------------------------------------
    */

    if ($request->filled('start_date')) {

        $query->whereDate(
            'attendance_date',
            '>=',
            $request->date('start_date')
        );

    }


    /*
    |--------------------------------------------------------------------------
    | End Date Filter
    |--------------------------------------------------------------------------
    */

    if ($request->filled('end_date')) {

        $query->whereDate(
            'attendance_date',
            '<=',
            $request->date('end_date')
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Get Attendance Records
    |--------------------------------------------------------------------------
    */

    $attendance = $query
        ->orderByDesc('attendance_date')
        ->orderBy('staff_id')
        ->get();


    /*
    |--------------------------------------------------------------------------
    | Summary
    |--------------------------------------------------------------------------
    */

    $summary = [
        'total' => $attendance->count(),

        'present' => $attendance
            ->where('status', 'present')
            ->count(),

        'absent' => $attendance
            ->where('status', 'absent')
            ->count(),

        'late' => $attendance
            ->where('status', 'late')
            ->count(),

        'half_day' => $attendance
            ->where('status', 'half_day')
            ->count(),

        'leave' => $attendance
            ->where('status', 'leave')
            ->count(),
    ];


    /*
    |--------------------------------------------------------------------------
    | Attendance Rate
    |--------------------------------------------------------------------------
    |
    | Present, Late and Half Day count as attended records.
    |
    */

    $attendedRecords =
        $summary['present'] +
        $summary['late'] +
        $summary['half_day'];


    $attendanceRate = $summary['total'] > 0
        ? round(
            ($attendedRecords / $summary['total']) * 100,
            2
        )
        : 0;


    /*
    |--------------------------------------------------------------------------
    | Return Report View
    |--------------------------------------------------------------------------
    */

    return view(
        'admin.staff-attendance.reports',
        [
            'attendance' => $attendance,
            'staff' => $staff,
            'summary' => $summary,
            'attendanceRate' => $attendanceRate,
        ]
    );
}
}