<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Models\Courses;
use App\Models\Department;
use App\Services\CourseService;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function __construct(
        protected CourseService $courseService
    ) {
    }

    public function index(Request $request)
    {
        $schoolId = $this->schoolId();

        $query = Courses::query()
            ->where('school_id', $schoolId)
            ->with('department')
            ->withCount('students');

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('course_code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('department_id')) {
            $query->where(
                'department_id',
                $request->integer('department_id')
            );
        }

        if ($request->filled('status')) {
            $query->where(
                'is_active',
                $request->status === 'active'
            );
        }

        $courses = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        $schoolId = $this->schoolId();

        $departments = Department::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.courses.create', compact('departments'));
    }

    public function store(StoreCourseRequest $request)
    {
        $course = $this->courseService->create(
            $request->validated()
        );

        return redirect()
            ->route('admin.courses.show', $course)
            ->with('success', 'Course created successfully.');
    }

    public function show(Courses $course)
    {
        $this->ensureSameSchool($course);

        $course->load('department');

        return view('admin.courses.show', compact('course'));
    }

    public function edit(Courses $course)
    {
        $this->ensureSameSchool($course);

        $schoolId = $this->schoolId();

        $departments = Department::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view(
            'admin.courses.edit',
            compact('course', 'departments')
        );
    }

    public function update(
        UpdateCourseRequest $request,
        Courses $course
    ) {
        $this->ensureSameSchool($course);

        $course = $this->courseService->update(
            $course,
            $request->validated()
        );

        return redirect()
            ->route('admin.courses.show', $course)
            ->with('success', 'Course updated successfully.');
    }

    public function destroy(Courses $course)
    {
        $this->ensureSameSchool($course);

        $this->courseService->delete($course);

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Course deleted successfully.');
    }

    protected function schoolId(): int
    {
        $schoolId = auth()->user()?->school_id;

        if (! $schoolId) {
            abort(403, 'Your account is not assigned to a school.');
        }

        return (int) $schoolId;
    }

    protected function ensureSameSchool(Courses $course): void
    {
        if ((int) $course->school_id !== $this->schoolId()) {
            abort(403, 'You are not authorized to access this course.');
        }
    }
}