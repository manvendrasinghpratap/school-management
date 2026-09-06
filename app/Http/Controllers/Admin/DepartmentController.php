<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDepartmentRequest;
use App\Http\Requests\Admin\UpdateDepartmentRequest;
use App\Models\Department;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    public function __construct(
        protected \App\Services\DepartmentService $departmentService
    ) {}

    public function index(Request $request): View
    {
        $schoolId = $this->schoolId();

        $departments = Department::query()
            ->where('school_id', $schoolId)
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search');

                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where(
                    'is_active',
                    $request->string('status') === 'active'
                );
            })
            ->withCount('classes')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.departments.index', compact('departments'));
    }

    public function create(): View
    {
        return view('admin.departments.create');
    }

    public function store(StoreDepartmentRequest $request): RedirectResponse
    {
        $department = $this->departmentService->create(
            $request->validated()
        );

        return redirect()
            ->route('admin.departments.show', $department)
            ->with('success', 'Department created successfully.');
    }

    public function show(Department $department): View
    {
        $this->ensureSameSchool($department);

        $department->load([
            'classes' => function ($query) {
                $query->orderBy('name');
            },
        ]);

        return view('admin.departments.show', compact('department'));
    }

    public function edit(Department $department): View
    {
        $this->ensureSameSchool($department);

        return view('admin.departments.edit', compact('department'));
    }

    public function update(
        UpdateDepartmentRequest $request,
        Department $department
    ): RedirectResponse {
        $this->ensureSameSchool($department);

        $department = $this->departmentService->update(
            $department,
            $request->validated()
        );

        return redirect()
            ->route('admin.departments.show', $department)
            ->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department): RedirectResponse
    {
        $this->ensureSameSchool($department);

        $this->departmentService->delete($department);

        return redirect()
            ->route('admin.departments.index')
            ->with('success', 'Department deleted successfully.');
    }

    protected function schoolId(): int
    {
        $user = auth()->user();

        abort_unless(
            $user,
            403,
            'You must be authenticated.'
        );

        abort_unless(
            $user->school_id,
            403,
            'No school is assigned to the current user.'
        );

        return (int) $user->school_id;
    }

    protected function ensureSameSchool(Department $department): void
    {
        $schoolId = $this->schoolId();

        abort_unless(
            (int) $department->school_id === $schoolId,
            403,
            'You are not authorized to access this department.'
        );
    }
}