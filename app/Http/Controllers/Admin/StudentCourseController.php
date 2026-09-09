<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudentCourseRequest;
use App\Http\Requests\UpdateStudentCourseRequest;
use App\Models\AcademicYears;
use App\Models\Classes;
use App\Models\Courses;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentCourse;
use App\Models\StudentEnrollment;
use App\Models\Terms;
use App\Services\StudentCourseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentCourseController extends Controller
{
    public function __construct(
        protected StudentCourseService $studentCourseService
    ) {
    }

    public function index(Request $request): View
    {
        $schoolId = $this->schoolId($request->user());

        $registrations = StudentCourse::query()
            ->with([
                'student',
                'course',
                'academicYear',
                'term',
            ])
            ->whereHas('student', function ($query) use ($schoolId) {
                $query->where('school_id', $schoolId);
            })
            ->whereHas('course', function ($query) use ($schoolId) {
                $query->where('school_id', $schoolId);
            })
            ->whereHas('academicYear', function ($query) use ($schoolId) {
                $query->where('school_id', $schoolId);
            });

        if ($request->filled('student_id')) {
            $registrations->where(
                'student_id',
                $request->integer('student_id')
            );
        }

        if ($request->filled('course_id')) {
            $registrations->where(
                'course_id',
                $request->integer('course_id')
            );
        }

        if ($request->filled('academic_year_id')) {
            $registrations->where(
                'academic_year_id',
                $request->integer('academic_year_id')
            );
        }

        if ($request->filled('term_id')) {
            $registrations->where(
                'term_id',
                $request->integer('term_id')
            );
        }

        if ($request->filled('status')) {
            $registrations->where(
                'status',
                $request->string('status')
            );
        }

        $registrations = $registrations
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        $students = Student::query()
            ->where('school_id', $schoolId)
            ->where('status', 'active')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        $courses = Courses::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $academicYears = AcademicYears::query()
            ->where('school_id', $schoolId)
            ->orderByDesc('id')
            ->get();

        $terms = Terms::query()
            ->where('school_id', $schoolId)
            ->with('academicYear')
            ->orderByDesc('id')
            ->get();

        return view(
            'admin.student-courses.index',
            compact(
                'registrations',
                'students',
                'courses',
                'academicYears',
                'terms'
            )
        );
    }

    public function create(Request $request): View
    {
        $schoolId = $this->schoolId($request->user());

        $academicYears = AcademicYears::query()
            ->where('school_id', $schoolId)
            ->orderByDesc('id')
            ->get();

        $courses = Courses::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view(
            'admin.student-courses.create',
            compact(
                'academicYears',
                'courses'
            )
        );
    }

    public function store(
        StoreStudentCourseRequest $request
    ): RedirectResponse {
        $schoolId = $this->schoolId($request->user());

        $registration = $this->studentCourseService->create(
            $schoolId,
            $request->validated(),
            $request->user()->id
        );

        return redirect()
            ->route(
                'admin.student-courses.show',
                $registration
            )
            ->with(
                'success',
                'Student course registration created successfully.'
            );
    }

    public function show(
        Request $request,
        StudentCourse $studentCourse
    ): View {
        $schoolId = $this->schoolId($request->user());

        $this->studentCourseService
            ->ensureRegistrationBelongsToSchool(
                $studentCourse,
                $schoolId
            );

        $studentCourse->load([
            'student',
            'course.department',
            'academicYear',
            'term',
        ]);

        $enrollment = StudentEnrollment::query()
            ->with([
                'class',
                'section',
            ])
            ->where('school_id', $schoolId)
            ->where('student_id', $studentCourse->student_id)
            ->where(
                'academic_year_id',
                $studentCourse->academic_year_id
            )
            ->when(
                $studentCourse->term_id !== null,
                fn ($query) => $query->where(
                    'term_id',
                    $studentCourse->term_id
                ),
                fn ($query) => $query->whereNull('term_id')
            )
            ->where('status', 'active')
            ->latest('id')
            ->first();

        return view(
            'admin.student-courses.show',
            compact(
                'studentCourse',
                'enrollment'
            )
        );
    }

    public function edit(StudentCourse $studentCourse): View
    {
    $schoolId = $this->schoolId(auth()->user());

    /*
    |--------------------------------------------------------------------------
    | Security
    |--------------------------------------------------------------------------
    */

    $studentCourse->load([
        'student',
        'course',
        'academicYear',
        'term',
    ]);

    // Student must belong to the current school.
    if (
        !$studentCourse->student ||
        (int) $studentCourse->student->school_id !== $schoolId
    ) {
        abort(
            403,
            'You cannot edit a student course registration belonging to another school.'
        );
    }

    // Course must belong to the current school.
    if (
        !$studentCourse->course ||
        (int) $studentCourse->course->school_id !== $schoolId
    ) {
        abort(
            403,
            'You cannot edit a course belonging to another school.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Academic Years
    |--------------------------------------------------------------------------
    */

    $academicYears = AcademicYears::query()
        ->where('school_id', $schoolId)
        ->where('is_active', true)
        ->orderByDesc('start_date')
        ->orderByDesc('id')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Terms
    |--------------------------------------------------------------------------
    |
    | Terms are loaded for the registration's current academic year.
    |
    */

    $terms = Terms::query()
        ->where('school_id', $schoolId)
        ->where(
            'academic_year_id',
            $studentCourse->academic_year_id
        )
        ->where('is_active', true)
        ->orderBy('term_number')
        ->orderBy('id')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Classes
    |--------------------------------------------------------------------------
    */

    $classes = Classes::query()
        ->where('school_id', $schoolId)
        ->where('is_active', true)
        ->with('level')
        ->orderBy('name')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Current Student Enrollment
    |--------------------------------------------------------------------------
    |
    | A student course registration does not contain class_id or section_id.
    | Therefore the edit screen derives the student's class/section from
    | the student's active enrollment for the same academic year/term.
    |
    */

    $currentEnrollment = StudentEnrollment::query()
        ->where('school_id', $schoolId)
        ->where('student_id', $studentCourse->student_id)
        ->where(
            'academic_year_id',
            $studentCourse->academic_year_id
        )
        ->where('status', 'active')
        ->when(
            $studentCourse->term_id !== null,
            function ($query) use ($studentCourse) {
                $query->where(
                    'term_id',
                    $studentCourse->term_id
                );
            }
        )
        ->with([
            'academicYear',
            'term',
            'class.level',
            'section',
        ])
        ->latest('enrollment_date')
        ->latest('id')
        ->first();

    /*
    |--------------------------------------------------------------------------
    | Determine Current Class
    |--------------------------------------------------------------------------
    */

    $selectedClassId = $currentEnrollment?->class_id;

    /*
    | If a term-specific enrollment could not be found, use the latest
    | active enrollment for the same academic year.
    */

    if (!$selectedClassId) {
        $selectedClassId = StudentEnrollment::query()
            ->where('school_id', $schoolId)
            ->where('student_id', $studentCourse->student_id)
            ->where(
                'academic_year_id',
                $studentCourse->academic_year_id
            )
            ->where('status', 'active')
            ->latest('enrollment_date')
            ->latest('id')
            ->value('class_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Sections
    |--------------------------------------------------------------------------
    */

    $sections = collect();

    if ($selectedClassId) {
        $sections = Section::query()
            ->where('class_id', $selectedClassId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Students
    |--------------------------------------------------------------------------
    |
    | Only the currently registered student is loaded initially.
    | JavaScript will reload the student list when the academic placement
    | changes.
    |
    */

    $students = Student::query()
        ->where('school_id', $schoolId)
        ->where('status', 'active')
        ->whereKey($studentCourse->student_id)
        ->orderBy('first_name')
        ->orderBy('last_name')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Courses
    |--------------------------------------------------------------------------
    */

    $courses = Courses::query()
        ->where('school_id', $schoolId)
        ->where('is_active', true)
        ->orderBy('name')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Return View
    |--------------------------------------------------------------------------
    */

    return view(
        'admin.student-courses.edit',
        compact(
            'studentCourse',
            'academicYears',
            'terms',
            'classes',
            'sections',
            'students',
            'courses',
            'currentEnrollment',
            'selectedClassId'
        )
    );
    }

    public function update(
        UpdateStudentCourseRequest $request,
        StudentCourse $studentCourse
    ): RedirectResponse {
        $schoolId = $this->schoolId($request->user());

        $this->studentCourseService
            ->ensureRegistrationBelongsToSchool(
                $studentCourse,
                $schoolId
            );

        $this->studentCourseService->update(
            $studentCourse,
            $schoolId,
            $request->validated(),
            $request->user()->id
        );

        return redirect()
            ->route(
                'admin.student-courses.show',
                $studentCourse
            )
            ->with(
                'success',
                'Student course registration updated successfully.'
            );
    }

    public function destroy(
        Request $request,
        StudentCourse $studentCourse
    ): RedirectResponse {
        $schoolId = $this->schoolId($request->user());

        $this->studentCourseService
            ->ensureRegistrationBelongsToSchool(
                $studentCourse,
                $schoolId
            );

        $this->studentCourseService->delete(
            $studentCourse,
            $schoolId
        );

        return redirect()
            ->route('admin.student-courses.index')
            ->with(
                'success',
                'Student course registration deleted successfully.'
            );
    }

    /**
     * Terms belonging to an academic year.
     */
    public function terms(
        Request $request,
        AcademicYears $academicYear
    ): JsonResponse {
        $schoolId = $this->schoolId($request->user());

        abort_unless(
            $academicYear->school_id === $schoolId,
            403
        );

        $terms = Terms::query()
            ->where('school_id', $schoolId)
            ->where(
                'academic_year_id',
                $academicYear->id
            )
            ->orderBy('id')
            ->get([
                'id',
                'name',
                'academic_year_id',
            ]);

        return response()->json($terms);
    }

    /**
     * Classes available to the school.
     */
    public function classes(
        Request $request,
        AcademicYears $academicYear
    ): JsonResponse {
        $schoolId = $this->schoolId($request->user());

        abort_unless(
            $academicYear->school_id === $schoolId,
            403
        );

        $classes = Classes::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'code',
            ]);

        return response()->json($classes);
    }

    /**
     * Sections belonging to a class.
     */
    public function sections(
        Request $request,
        Classes $class
    ): JsonResponse {
        $schoolId = $this->schoolId($request->user());

        abort_unless(
            $class->school_id === $schoolId,
            403
        );

        $sections = Section::query()
            ->where('class_id', $class->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'code',
                'class_id',
            ]);

        return response()->json($sections);
    }

    /**
     * Students matching academic enrollment filters.
     */
    public function students(
        Request $request
    ): JsonResponse {
        $schoolId = $this->schoolId($request->user());

        $validated = $request->validate([
            'academic_year_id' => [
                'required',
                'integer',
            ],

            'term_id' => [
                'nullable',
                'integer',
            ],

            'class_id' => [
                'required',
                'integer',
            ],

            'section_id' => [
                'nullable',
                'integer',
            ],
        ]);

        $query = Student::query()
            ->where('students.school_id', $schoolId)
            ->where('students.status', 'active')
            ->whereHas('enrollments', function ($enrollment) use (
                $schoolId,
                $validated
            ) {
                $enrollment
                    ->where('school_id', $schoolId)
                    ->where(
                        'academic_year_id',
                        $validated['academic_year_id']
                    )
                    ->where(
                        'class_id',
                        $validated['class_id']
                    )
                    ->where('status', 'active')
                    ->whereNull('deleted_at');

                if (
                    array_key_exists('term_id', $validated) &&
                    $validated['term_id'] !== null
                ) {
                    $enrollment->where(
                        'term_id',
                        $validated['term_id']
                    );
                } else {
                    $enrollment->whereNull('term_id');
                }

                if (
                    array_key_exists('section_id', $validated) &&
                    $validated['section_id'] !== null
                ) {
                    $enrollment->where(
                        'section_id',
                        $validated['section_id']
                    );
                } else {
                    $enrollment->whereNull('section_id');
                }
            })
            ->orderBy('first_name')
            ->orderBy('last_name');

        $students = $query
            ->get([
                'id',
                'student_number',
                'first_name',
                'middle_name',
                'last_name',
            ])
            ->map(function ($student) {
                return [
                    'id' => $student->id,
                    'student_number' => $student->student_number,
                    'name' => $student->full_name,
                ];
            });

        return response()->json($students);
    }

    protected function schoolId($user): int
    {
        $schoolId = $user?->school_id;

        if (!$schoolId) {
            abort(
                403,
                'No school is assigned to the current user.'
            );
        }

        return (int) $schoolId;
    }
}