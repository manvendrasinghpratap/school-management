<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStaffRequest;
use App\Http\Requests\UpdateStaffRequest;
use App\Models\Department;
use App\Models\Staff;
use App\Models\User;
use App\Services\StaffService;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function __construct(
        protected StaffService $staffService
    ) {
    }

    public function index(Request $request)
    {
        $schoolId = $this->schoolId();

        $query = Staff::query()
            ->where('school_id', $schoolId)
            ->with([
                'department',
                'instructor',
            ]);

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('staff_number', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('middle_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('staff_type', 'like', "%{$search}%");
            });
        }

        if ($request->filled('department_id')) {
            $query->where(
                'department_id',
                $request->integer('department_id')
            );
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('staff_type')) {
            $query->where(
                'staff_type',
                'like',
                '%' . trim($request->staff_type) . '%'
            );
        }

        $staff = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $departments = Department::query()
            ->where('school_id', $schoolId)
            ->orderBy('name')
            ->get();

        return view(
            'admin.staff.index',
            compact('staff', 'departments')
        );
    }

    public function create()
    {
        $schoolId = $this->schoolId();

        $departments = Department::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $users = User::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->whereDoesntHave('staff')
            ->orderBy('name')
            ->get();

        return view(
            'admin.staff.create',
            compact('departments', 'users')
        );
    }

    public function store(StoreStaffRequest $request)
    {
        $staff = $this->staffService->create(
            $request->validated(),
            $request->file('photo')
        );

        return redirect()
            ->route('admin.staff.show', $staff)
            ->with('success', 'Staff member created successfully.');
    }

    public function show(Staff $staff)
    {
        $this->ensureSameSchool($staff);

        $staff->load([
            'department',
            'user',
            'instructor',
        ]);

        return view(
            'admin.staff.show',
            compact('staff')
        );
    }

    public function edit(Staff $staff)
    {
        $this->ensureSameSchool($staff);

        $schoolId = $this->schoolId();

        $departments = Department::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $users = User::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->where(function ($query) use ($staff) {
                $query->whereDoesntHave('staff')
                    ->orWhereHas('staff', function ($q) use ($staff) {
                        $q->where('id', $staff->id);
                    });
            })
            ->orderBy('name')
            ->get();

        return view(
            'admin.staff.edit',
            compact('staff', 'departments', 'users')
        );
    }

    public function update(
        UpdateStaffRequest $request,
        Staff $staff
    ) {
        $this->ensureSameSchool($staff);

        $staff = $this->staffService->update(
            $staff,
            $request->validated(),
            $request->file('photo')
        );

        return redirect()
            ->route('admin.staff.show', $staff)
            ->with('success', 'Staff member updated successfully.');
    }

    public function destroy(Staff $staff)
    {
        $this->ensureSameSchool($staff);

        $this->staffService->delete($staff);

        return redirect()
            ->route('admin.staff.index')
            ->with('success', 'Staff member deleted successfully.');
    }

    protected function schoolId(): int
    {
        $schoolId = auth()->user()?->school_id;

        if (! $schoolId) {
            abort(
                403,
                'Your account is not assigned to a school.'
            );
        }

        return (int) $schoolId;
    }

    protected function ensureSameSchool(Staff $staff): void
    {
        if ((int) $staff->school_id !== $this->schoolId()) {
            abort(
                403,
                'You are not authorized to access this staff member.'
            );
        }
    }
}