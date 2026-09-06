<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreClassRequest;
use App\Http\Requests\Admin\UpdateClassRequest;
use App\Models\Classes;
use App\Models\Department;
use App\Models\Level;
use App\Services\ClassService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClassController extends Controller
{
    public function __construct(
        protected ClassService $classService
    ) {
    }

    public function index(Request $request): View
    {
        $schoolId = $this->schoolId();

        $classes = Classes::query()
            ->where('school_id', $schoolId)
            ->with(['department', 'level'])
            ->when(
                $request->filled('search'),
                function ($query) use ($request) {
                    $search = $request->string('search');

                    $query->where(function ($query) use ($search) {
                        $query
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('code', 'like', "%{$search}%")
                            ->orWhere(
                                'description',
                                'like',
                                "%{$search}%"
                            );
                    });
                }
            )
            ->when(
                $request->filled('department_id'),
                fn ($query) => $query->where(
                    'department_id',
                    $request->integer('department_id')
                )
            )
            ->when(
                $request->filled('level_id'),
                fn ($query) => $query->where(
                    'level_id',
                    $request->integer('level_id')
                )
            )
            ->when(
                $request->filled('status'),
                fn ($query) => $query->where(
                    'is_active',
                    $request->string('status') === 'active'
                )
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $departments = Department::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $levels = Level::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view(
            'admin.classes.index',
            compact(
                'classes',
                'departments',
                'levels'
            )
        );
    }

    public function create(): View
    {
        $schoolId = $this->schoolId();

        $departments = Department::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $levels = Level::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view(
            'admin.classes.create',
            compact(
                'departments',
                'levels'
            )
        );
    }

    public function store(
        StoreClassRequest $request
    ): RedirectResponse {
        $class = $this->classService->create(
            $request->validated()
        );

        return redirect()
            ->route('admin.classes.show', $class)
            ->with(
                'success',
                'Class created successfully.'
            );
    }

    public function show(Classes $class): View
    {
        $this->ensureSameSchool($class);

        $class->load([
            'department',
            'level',
        ]);

        return view(
            'admin.classes.show',
            compact('class')
        );
    }

    public function edit(Classes $class): View
    {
        $this->ensureSameSchool($class);

        $schoolId = $this->schoolId();

        $departments = Department::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $levels = Level::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view(
            'admin.classes.edit',
            compact(
                'class',
                'departments',
                'levels'
            )
        );
    }

    public function update(
        UpdateClassRequest $request,
        Classes $class
    ): RedirectResponse {
        $this->ensureSameSchool($class);

        $class = $this->classService->update(
            $class,
            $request->validated()
        );

        return redirect()
            ->route('admin.classes.show', $class)
            ->with(
                'success',
                'Class updated successfully.'
            );
    }

    public function destroy(
        Classes $class
    ): RedirectResponse {
        $this->ensureSameSchool($class);

        $this->classService->delete($class);

        return redirect()
            ->route('admin.classes.index')
            ->with(
                'success',
                'Class deleted successfully.'
            );
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

    protected function ensureSameSchool(
        Classes $class
    ): void {
        $schoolId = $this->schoolId();

        abort_unless(
            (int) $class->school_id === $schoolId,
            403,
            'You are not authorized to access this class.'
        );
    }
}