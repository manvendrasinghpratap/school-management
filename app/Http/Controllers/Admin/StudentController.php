<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreStudentRequest;
use App\Http\Requests\Admin\UpdateStudentRequest;
use App\Models\Guardian;
use App\Models\Student;
use App\Services\StudentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function __construct(
        protected StudentService $studentService
    ) {
    }

    public function index(Request $request): View
    {
        $schoolId = $this->schoolId();

        $students = Student::query()
            ->where('school_id', $schoolId)
            ->with(['guardians'])
            ->when(
                $request->filled('search'),
                function ($query) use ($request) {
                    $search = $request->string('search');

                    $query->where(function ($query) use ($search) {
                        $query
                            ->where(
                                'student_number',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'admission_number',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'first_name',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'middle_name',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'last_name',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'phone',
                                'like',
                                "%{$search}%"
                            );
                    });
                }
            )
            ->when(
                $request->filled('status'),
                fn ($query) => $query->where(
                    'status',
                    $request->string('status')
                )
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view(
            'admin.students.index',
            compact('students')
        );
    }

    public function create(): View
    {
        $schoolId = $this->schoolId();

        $guardians = Guardian::query()
            ->where('school_id', $schoolId)
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        return view(
            'admin.students.create',
            compact('guardians')
        );
    }

    public function store(
        StoreStudentRequest $request
    ): RedirectResponse {
        $student = $this->studentService->create(
            $request->validated(),
            $request->file('photo')
        );

        return redirect()
            ->route('admin.students.show', $student)
            ->with(
                'success',
                'Student registered successfully.'
            );
    }

    public function show(Student $student): View
    {
        $this->ensureSameSchool($student);

        $student->load([
            'guardians',
            'enrollments',
            'promotions',
            'documents',
        ]);

        return view(
            'admin.students.show',
            compact('student')
        );
    }

    public function edit(Student $student): View
    {
        $this->ensureSameSchool($student);

        $student->load('guardians');

        $guardians = Guardian::query()
            ->where('school_id', $this->schoolId())
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        return view(
            'admin.students.edit',
            compact('student', 'guardians')
        );
    }

    public function update(
        UpdateStudentRequest $request,
        Student $student
    ): RedirectResponse {
        $this->ensureSameSchool($student);

        $student = $this->studentService->update(
            $student,
            $request->validated(),
            $request->file('photo')
        );

        return redirect()
            ->route('admin.students.show', $student)
            ->with(
                'success',
                'Student updated successfully.'
            );
    }

    public function destroy(Student $student): RedirectResponse
    {
        $this->ensureSameSchool($student);

        $this->studentService->delete($student);

        return redirect()
            ->route('admin.students.index')
            ->with(
                'success',
                'Student deleted successfully.'
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
        Student $student
    ): void {
        $schoolId = $this->schoolId();

        abort_unless(
            (int) $student->school_id === $schoolId,
            403,
            'You are not authorized to access this student.'
        );
    }
}