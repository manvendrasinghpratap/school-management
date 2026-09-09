<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Models\Staff;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LeaveController extends Controller
{
    /**
     * Display leave requests.
     */
    public function index(Request $request): View
    {
        abort_unless(
            $request->user()?->can('leaves.view'),
            403
        );

        $schoolId = $this->schoolId();

        $staff = Staff::query()
            ->where('school_id', $schoolId)
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        $leaves = Leave::query()
            ->where('school_id', $schoolId)
            ->with([
                'staff',
                'approvedBy',
            ])
            ->when(
                $request->filled('staff_id'),
                fn (Builder $query) =>
                    $query->where(
                        'staff_id',
                        $request->integer('staff_id')
                    )
            )
            ->when(
                $request->filled('status'),
                fn (Builder $query) =>
                    $query->where(
                        'status',
                        $request->string('status')->toString()
                    )
            )
            ->when(
                $request->filled('leave_type'),
                fn (Builder $query) =>
                    $query->where(
                        'leave_type',
                        $request->string('leave_type')->toString()
                    )
            )
            ->orderByDesc('start_date')
            ->orderByDesc('id')
            ->paginate(25)
            ->withQueryString();

        return view(
            'admin.leaves.index',
            compact(
                'leaves',
                'staff'
            )
        );
    }

    /**
     * Show create form.
     */
    public function create(Request $request): View
    {
        abort_unless(
            $request->user()?->can('leaves.create'),
            403
        );

        $schoolId = $this->schoolId();

        $staff = Staff::query()
            ->where('school_id', $schoolId)
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        return view(
            'admin.leaves.create',
            compact('staff')
        );
    }

    /**
     * Store a leave request.
     */
    public function store(Request $request): RedirectResponse
    {
        abort_unless(
            $request->user()?->can('leaves.create'),
            403
        );

        $schoolId = $this->schoolId();

        $validated = $request->validate([
            'staff_id' => [
                'required',
                'integer',
                'exists:staff,id',
            ],

            'leave_type' => [
                'required',
                'string',
                'max:100',
            ],

            'start_date' => [
                'required',
                'date',
            ],

            'end_date' => [
                'required',
                'date',
                'after_or_equal:start_date',
            ],

            'reason' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        $this->ensureStaffBelongsToSchool(
            (int) $validated['staff_id'],
            $schoolId
        );

        $this->ensureNoOverlappingLeave(
            (int) $validated['staff_id'],
            $validated['start_date'],
            $validated['end_date']
        );

        Leave::create([
            'school_id' => $schoolId,
            'staff_id' => $validated['staff_id'],
            'leave_type' => $validated['leave_type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'reason' => $validated['reason'] ?? null,
            'status' => 'pending',
            'approved_by' => null,
        ]);

        return redirect()
            ->route('admin.leaves.index')
            ->with(
                'success',
                'Leave request submitted successfully.'
            );
    }

    /**
     * Display a leave request.
     */
    public function show(
        Request $request,
        Leave $leave
    ): View {
        abort_unless(
            $request->user()?->can('leaves.view'),
            403
        );

        $this->ensureLeaveBelongsToSchool($leave);

        $leave->load([
            'staff',
            'approvedBy',
        ]);

        return view(
            'admin.leaves.show',
            compact('leave')
        );
    }

    /**
     * Show edit form.
     */
    public function edit(
        Request $request,
        Leave $leave
    ): View {
        abort_unless(
            $request->user()?->can('leaves.update'),
            403
        );

        $this->ensureLeaveBelongsToSchool($leave);

        if ($leave->status !== 'pending') {
            abort(
                422,
                'Only pending leave requests can be edited.'
            );
        }

        $staff = Staff::query()
            ->where('school_id', $this->schoolId())
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        return view(
            'admin.leaves.edit',
            compact(
                'leave',
                'staff'
            )
        );
    }

    /**
     * Update a pending leave request.
     */
    public function update(
        Request $request,
        Leave $leave
    ): RedirectResponse {
        abort_unless(
            $request->user()?->can('leaves.update'),
            403
        );

        $this->ensureLeaveBelongsToSchool($leave);

        if ($leave->status !== 'pending') {
            abort(
                422,
                'Only pending leave requests can be edited.'
            );
        }

        $validated = $request->validate([
            'staff_id' => [
                'required',
                'integer',
                'exists:staff,id',
            ],

            'leave_type' => [
                'required',
                'string',
                'max:100',
            ],

            'start_date' => [
                'required',
                'date',
            ],

            'end_date' => [
                'required',
                'date',
                'after_or_equal:start_date',
            ],

            'reason' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        $this->ensureStaffBelongsToSchool(
            (int) $validated['staff_id'],
            $this->schoolId()
        );

        $this->ensureNoOverlappingLeave(
            (int) $validated['staff_id'],
            $validated['start_date'],
            $validated['end_date'],
            $leave->id
        );

        $leave->update([
            'staff_id' => $validated['staff_id'],
            'leave_type' => $validated['leave_type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'reason' => $validated['reason'] ?? null,
        ]);

        return redirect()
            ->route('admin.leaves.show', $leave)
            ->with(
                'success',
                'Leave request updated successfully.'
            );
    }

    /**
     * Approve a leave request.
     */
    public function approve(
        Request $request,
        Leave $leave
    ): RedirectResponse {
        abort_unless(
            $request->user()?->can('leaves.approve'),
            403
        );

        $this->ensureLeaveBelongsToSchool($leave);

        if ($leave->status !== 'pending') {
            throw ValidationException::withMessages([
                'status' =>
                    'Only pending leave requests can be approved.',
            ]);
        }

        $leave->update([
            'status' => 'approved',
            'approved_by' => $request->user()->id,
        ]);

        return redirect()
            ->route('admin.leaves.show', $leave)
            ->with(
                'success',
                'Leave request approved successfully.'
            );
    }

    /**
     * Reject a leave request.
     */
    public function reject(
        Request $request,
        Leave $leave
    ): RedirectResponse {
        abort_unless(
            $request->user()?->can('leaves.reject'),
            403
        );

        $this->ensureLeaveBelongsToSchool($leave);

        if ($leave->status !== 'pending') {
            throw ValidationException::withMessages([
                'status' =>
                    'Only pending leave requests can be rejected.',
            ]);
        }

        $leave->update([
            'status' => 'rejected',
            'approved_by' => $request->user()->id,
        ]);

        return redirect()
            ->route('admin.leaves.show', $leave)
            ->with(
                'success',
                'Leave request rejected successfully.'
            );
    }

    /**
     * Cancel a pending or approved leave request.
     */
    public function cancel(
        Request $request,
        Leave $leave
    ): RedirectResponse {
        abort_unless(
            $request->user()?->can('leaves.update'),
            403
        );

        $this->ensureLeaveBelongsToSchool($leave);

        if (!in_array(
            $leave->status,
            ['pending', 'approved'],
            true
        )) {
            throw ValidationException::withMessages([
                'status' =>
                    'This leave request cannot be cancelled.',
            ]);
        }

        $leave->update([
            'status' => 'cancelled',
        ]);

        return redirect()
            ->route('admin.leaves.show', $leave)
            ->with(
                'success',
                'Leave request cancelled successfully.'
            );
    }

    /**
     * Soft delete a leave request.
     */
    public function destroy(
        Request $request,
        Leave $leave
    ): RedirectResponse {
        abort_unless(
            $request->user()?->can('leaves.delete'),
            403
        );

        $this->ensureLeaveBelongsToSchool($leave);

        $leave->delete();

        return redirect()
            ->route('admin.leaves.index')
            ->with(
                'success',
                'Leave request deleted successfully.'
            );
    }

    /**
     * Display leave reports.
     */
    public function reports(Request $request): View
    {
        abort_unless(
            $request->user()?->can('leaves.reports'),
            403
        );

        $schoolId = $this->schoolId();

        /*
         * Validate report filters.
         */
        $validated = $request->validate([
            'staff_id' => [
                'nullable',
                'integer',
                Rule::exists('staff', 'id')
                    ->where(
                        fn ($query) =>
                            $query->where('school_id', $schoolId)
                    ),
            ],

            'status' => [
                'nullable',
                Rule::in([
                    'pending',
                    'approved',
                    'rejected',
                    'cancelled',
                ]),
            ],

            'start_date' => [
                'nullable',
                'date',
            ],

            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],
        ]);

        /*
         * Staff list for filter dropdown.
         */
        $staff = Staff::query()
            ->where('school_id', $schoolId)
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        /*
         * Base report query.
         */
        $query = Leave::query()
            ->where('school_id', $schoolId)
            ->with([
                'staff',
                'approvedBy',
            ]);

        /*
         * Staff filter.
         */
        if (!empty($validated['staff_id'])) {
            $query->where(
                'staff_id',
                $validated['staff_id']
            );
        }

        /*
         * Status filter.
         */
        if (!empty($validated['status'])) {
            $query->where(
                'status',
                $validated['status']
            );
        }

        /*
         * Date range filter.
         *
         * A leave request is included when it overlaps
         * the selected report period.
         */
        if (!empty($validated['start_date'])) {
            $query->whereDate(
                'end_date',
                '>=',
                $validated['start_date']
            );
        }

        if (!empty($validated['end_date'])) {
            $query->whereDate(
                'start_date',
                '<=',
                $validated['end_date']
            );
        }

        /*
         * Get report records.
         */
        $leaves = $query
            ->orderByDesc('start_date')
            ->orderByDesc('id')
            ->get();

        /*
         * Calculate summary counts.
         */
        $summary = [
            'total' => $leaves->count(),

            'pending' => $leaves
                ->where('status', 'pending')
                ->count(),

            'approved' => $leaves
                ->where('status', 'approved')
                ->count(),

            'rejected' => $leaves
                ->where('status', 'rejected')
                ->count(),

            'cancelled' => $leaves
                ->where('status', 'cancelled')
                ->count(),

            'total_days' => $leaves->sum(
                function ($leave) {
                    return $leave->start_date->diffInDays(
                        $leave->end_date
                    ) + 1;
                }
            ),
        ];

        return view(
            'admin.leaves.reports',
            compact(
                'leaves',
                'staff',
                'summary'
            )
        );
    }

    /**
     * Get authenticated user's school ID.
     */
    protected function schoolId(): int
    {
        $user = auth()->user();

        abort_unless($user, 403);

        abort_unless(
            $user->school_id,
            403
        );

        return (int) $user->school_id;
    }

    /**
     * Ensure staff belongs to the current school.
     */
    protected function ensureStaffBelongsToSchool(
        int $staffId,
        int $schoolId
    ): void {
        abort_unless(
            Staff::query()
                ->where('id', $staffId)
                ->where('school_id', $schoolId)
                ->exists(),
            422
        );
    }

    /**
     * Ensure leave belongs to current school.
     */
    protected function ensureLeaveBelongsToSchool(
        Leave $leave
    ): void {
        abort_unless(
            (int) $leave->school_id === $this->schoolId(),
            403
        );
    }

    /**
     * Prevent overlapping active leave requests.
     *
     * Pending and approved requests block overlapping dates.
     * Rejected and cancelled requests do not.
     */
    protected function ensureNoOverlappingLeave(
        int $staffId,
        string $startDate,
        string $endDate,
        ?int $ignoreLeaveId = null
    ): void {
        $query = Leave::query()
            ->where('staff_id', $staffId)
            ->whereIn('status', [
                'pending',
                'approved',
            ])
            ->whereDate(
                'start_date',
                '<=',
                $endDate
            )
            ->whereDate(
                'end_date',
                '>=',
                $startDate
            );

        if ($ignoreLeaveId !== null) {
            $query->where(
                'id',
                '!=',
                $ignoreLeaveId
            );
        }

        if ($query->exists()) {
            throw ValidationException::withMessages([
                'start_date' =>
                    'This staff member already has an overlapping leave request for the selected dates.',
            ]);
        }
    }
}