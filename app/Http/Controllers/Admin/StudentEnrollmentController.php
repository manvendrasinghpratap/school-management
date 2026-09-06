<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreStudentEnrollmentRequest;
use App\Http\Requests\Admin\UpdateStudentEnrollmentRequest;
use App\Models\AcademicYears;
use App\Models\Classes;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentEnrollment;
use App\Models\Terms;
use App\Services\StudentEnrollmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentEnrollmentController extends Controller
{
    protected StudentEnrollmentService $enrollmentService;

    public function __construct(StudentEnrollmentService $enrollmentService)
    {
        $this->enrollmentService = $enrollmentService;
    }

    /**
     * Display enrollment list.
     */
    public function index(Request $request): View
    {
        $schoolId = $this->schoolId();

        $query = StudentEnrollment::query()
            ->with([
                'student',
                'academicYear',
                'term',
                'class',
                'section',
            ])
            ->where('school_id', $schoolId);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where(
                    'enrollment_number',
                    'like',
                    "%{$search}%"
                );

                $q->orWhereHas('student', function ($studentQuery) use ($search) {

                    $studentQuery->where(function ($student) use ($search) {

                        $student->where(
                            'student_number',
                            'like',
                            "%{$search}%"
                        );

                        $student->orWhere(
                            'first_name',
                            'like',
                            "%{$search}%"
                        );

                        $student->orWhere(
                            'middle_name',
                            'like',
                            "%{$search}%"
                        );

                        $student->orWhere(
                            'last_name',
                            'like',
                            "%{$search}%"
                        );
                    });
                });
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Academic Year Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('academic_year_id')) {

            $academicYearId = (int) $request->academic_year_id;

            $query->where(
                'academic_year_id',
                $academicYearId
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Term Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('term_id')) {

            $termId = (int) $request->term_id;

            $query->where(
                'term_id',
                $termId
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }

        $enrollments = $query
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Filter Data
        |--------------------------------------------------------------------------
        */

        $academicYears = AcademicYears::query()
            ->where('school_id', $schoolId)
            ->orderByDesc('start_date')
            ->orderByDesc('id')
            ->get();

        $terms = Terms::query()
            ->where('school_id', $schoolId)
            ->orderBy('id')
            ->get();

        return view(
            'admin.student-enrollments.index',
            compact(
                'enrollments',
                'academicYears',
                'terms'
            )
        );
    }

    /**
     * Show create enrollment form.
     */
    public function create(): View
    {
        $schoolId = $this->schoolId();

        $students = Student::query()
            ->where('school_id', $schoolId)
            ->where('status', 'active')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        $academicYears = AcademicYears::query()
            ->where('school_id', $schoolId)
            ->orderByDesc('start_date')
            ->orderByDesc('id')
            ->get();

        $terms = Terms::query()
            ->where('school_id', $schoolId)
            ->orderBy('id')
            ->get();

        $classes = Classes::query()
            ->where('school_id', $schoolId)
            ->orderBy('name')
            ->get();

        $sections = Section::query()
            ->whereHas('class', function ($query) use ($schoolId) {
                $query->where('school_id', $schoolId);
            })
            ->orderBy('name')
            ->get();

        return view(
            'admin.student-enrollments.create',
            compact(
                'students',
                'academicYears',
                'terms',
                'classes',
                'sections'
            )
        );
    }

    /**
     * Store a new enrollment.
     */
    public function store(
        StoreStudentEnrollmentRequest $request
    ): RedirectResponse {

        try {

            $enrollment = $this->enrollmentService->create(
                $request->validated()
            );

            return redirect()
                ->route(
                    'admin.student-enrollments.show',
                    $enrollment
                )
                ->with(
                    'success',
                    'Student enrollment created successfully.'
                );

        } catch (\Illuminate\Validation\ValidationException $e) {

            throw $e;

        } catch (\Throwable $e) {

            report($e);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to create the student enrollment. Please try again.'
                );
        }
    }

    /**
     * Display enrollment details.
     */
    public function show(
        StudentEnrollment $enrollment
    ): View {

        $this->ensureSameSchool($enrollment);

        $enrollment->load([
            'student',
            'academicYear',
            'term',
            'class',
            'section',
            'createdBy',
            'updatedBy',
        ]);

        return view(
            'admin.student-enrollments.show',
            compact('enrollment')
        );
    }

    /**
     * Show edit enrollment form.
     */
    public function edit(
        StudentEnrollment $enrollment
    ): View {

        $this->ensureSameSchool($enrollment);

        $schoolId = $this->schoolId();

        $enrollment->load([
            'student',
            'academicYear',
            'term',
            'class',
            'section',
        ]);

        $academicYears = AcademicYears::query()
            ->where('school_id', $schoolId)
            ->orderByDesc('start_date')
            ->orderByDesc('id')
            ->get();

        $terms = Terms::query()
            ->where('school_id', $schoolId)
            ->orderBy('id')
            ->get();

        $classes = Classes::query()
            ->where('school_id', $schoolId)
            ->orderBy('name')
            ->get();

        $sections = Section::query()
    ->whereHas('class', function ($query) use ($schoolId) {
        $query->where('school_id', $schoolId);
    })
    ->orderBy('name')
    ->get();

        return view(
            'admin.student-enrollments.edit',
            compact(
                'enrollment',
                'academicYears',
                'terms',
                'classes',
                'sections'
            )
        );
    }

    /**
     * Update enrollment.
     */
    public function update(
        UpdateStudentEnrollmentRequest $request,
        StudentEnrollment $enrollment
    ): RedirectResponse {

        $this->ensureSameSchool($enrollment);

        try {

            $this->enrollmentService->update(
                $enrollment,
                $request->validated()
            );

            return redirect()
                ->route(
                    'admin.student-enrollments.show',
                    $enrollment
                )
                ->with(
                    'success',
                    'Student enrollment updated successfully.'
                );

        } catch (\Illuminate\Validation\ValidationException $e) {

            throw $e;

        } catch (\Throwable $e) {

            report($e);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to update the student enrollment. Please try again.'
                );
        }
    }

    /**
     * Soft-delete enrollment.
     */
    public function destroy(
        StudentEnrollment $enrollment
    ): RedirectResponse {

        $this->ensureSameSchool($enrollment);

        try {

            $this->enrollmentService->delete(
                $enrollment
            );

            return redirect()
                ->route('admin.student-enrollments.index')
                ->with(
                    'success',
                    'Student enrollment deleted successfully.'
                );

        } catch (\Throwable $e) {

            report($e);

            return back()
                ->with(
                    'error',
                    'Unable to delete the student enrollment.'
                );
        }
    }

    /**
     * Restore a soft-deleted enrollment.
     */
    public function restore(
        StudentEnrollment $enrollment
    ): RedirectResponse {

        $this->ensureSameSchool($enrollment);

        try {

            $this->enrollmentService->restore(
                $enrollment
            );

            return redirect()
                ->route(
                    'admin.student-enrollments.show',
                    $enrollment
                )
                ->with(
                    'success',
                    'Student enrollment restored successfully.'
                );

        } catch (\Throwable $e) {

            report($e);

            return back()
                ->with(
                    'error',
                    'Unable to restore the student enrollment.'
                );
        }
    }

    /**
     * Get current user's school.
     */
    protected function schoolId(): int
    {
        $schoolId = auth()->user()?->school_id;

        abort_unless(
            $schoolId,
            403,
            'No school is assigned to the current user.'
        );

        return (int) $schoolId;
    }

    /**
     * Ensure enrollment belongs to current user's school.
     */
    protected function ensureSameSchool(
        StudentEnrollment $enrollment
    ): void {

        abort_unless(
            (int) $enrollment->school_id === $this->schoolId(),
            403,
            'You are not authorized to access this enrollment.'
        );
    }
}