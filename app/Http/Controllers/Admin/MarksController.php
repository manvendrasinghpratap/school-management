<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\Examination;
use App\Models\ExamSchedule;
use App\Models\Mark;
use App\Models\Student;
use App\Models\StudentEnrollment;
use App\Models\Courses;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use App\Models\Grade;


class MarksController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index(Request $request): View
    {
        $user = Auth::user();

        $query = Mark::query()
            ->with([
                'student',
                'examination',
                'course',
                'enteredBy',
                'approvedBy',
            ])
            ->whereHas('examination', function ($q) use ($user) {
                $q->where('school_id', $user->school_id);
            });

        if ($request->filled('examination_id')) {
            $query->where(
                'examination_id',
                $request->integer('examination_id')
            );
        }

        if ($request->filled('course_id')) {
            $query->where(
                'course_id',
                $request->integer('course_id')
            );
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('student_id')) {
            $query->where(
                'student_id',
                $request->integer('student_id')
            );
        }

        $marks = $query
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        $examinations = Examination::query()
            ->where('school_id', $user->school_id)
            ->orderByDesc('start_date')
            ->orderByDesc('id')
            ->get();

        $courses = Courses::query()
            ->where('school_id', $user->school_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $students = Student::query()
            ->where('school_id', $user->school_id)
            ->where('status', 'active')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        return view('admin.marks.index', compact(
            'marks',
            'examinations',
            'courses',
            'students'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create(Request $request): View
{
    $user = Auth::user();

    $examinations = Examination::query()
        ->where('school_id', $user->school_id)
        ->whereIn('status', [
            'scheduled',
            'ongoing',
            'completed',
        ])
        ->orderByDesc('start_date')
        ->orderByDesc('id')
        ->get();

    $selectedExamination = null;
    $schedules = collect();
    $selectedSchedule = null;
    $eligibleStudents = collect();
    $selectedStudent = null;
    $existingMark = null;

    /*
    |--------------------------------------------------------------------------
    | Examination selected
    |--------------------------------------------------------------------------
    */

    if ($request->filled('examination_id')) {

        $selectedExamination = Examination::query()
            ->where('school_id', $user->school_id)
            ->findOrFail(
                $request->integer('examination_id')
            );

        $schedules = $selectedExamination->examSchedules()
            ->with([
                'course',
                'classModel',
                'section',
            ])
            ->whereNull('deleted_at')
            ->orderBy('exam_date')
            ->orderBy('start_time')
            ->orderBy('id')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Schedule selected
    |--------------------------------------------------------------------------
    */

    if (
        $selectedExamination &&
        $request->filled('schedule_id')
    ) {

        $selectedSchedule = $schedules->firstWhere(
            'id',
            $request->integer('schedule_id')
        );

        if (!$selectedSchedule) {
            abort(
                404,
                'The selected examination schedule was not found.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Validate schedule section
        |--------------------------------------------------------------------------
        */

        if ($selectedSchedule->section_id) {

            $sectionClassId = $selectedSchedule
                ->section
                ?->class_id;

            if (
                (int) $sectionClassId !==
                (int) $selectedSchedule->class_id
            ) {
                abort(
                    422,
                    'The selected examination schedule has an invalid section.'
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Find eligible students
        |--------------------------------------------------------------------------
        */

        $eligibleStudents = Student::query()
            ->where('students.school_id', $user->school_id)
            ->where('students.status', 'active')

            /*
            |--------------------------------------------------------------------------
            | Active enrollment
            |--------------------------------------------------------------------------
            */

            ->whereHas('enrollments', function ($query) use (
                $selectedExamination,
                $selectedSchedule,
                $user
            ) {

                $query
                    ->where(
                        'student_enrollments.school_id',
                        $user->school_id
                    )
                    ->where(
                        'student_enrollments.status',
                        'active'
                    )
                    ->where(
                        'student_enrollments.academic_year_id',
                        $selectedExamination->academic_year_id
                    )
                    ->where(
                        'student_enrollments.class_id',
                        $selectedSchedule->class_id
                    );

                /*
                |--------------------------------------------------------------------------
                | Term
                |--------------------------------------------------------------------------
                */

                if ($selectedExamination->term_id !== null) {

                    $query->where(
                        'student_enrollments.term_id',
                        $selectedExamination->term_id
                    );

                } else {

                    $query->whereNull(
                        'student_enrollments.term_id'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Section
                |--------------------------------------------------------------------------
                */

                if ($selectedSchedule->section_id !== null) {

                    $query->where(
                        'student_enrollments.section_id',
                        $selectedSchedule->section_id
                    );
                }
            })

            /*
            |--------------------------------------------------------------------------
            | Course registration
            |--------------------------------------------------------------------------
            */

            ->whereExists(function ($query) use (
                $selectedExamination,
                $selectedSchedule
            ) {

                $query
                    ->select(DB::raw(1))
                    ->from('student_courses')

                    ->whereColumn(
                        'student_courses.student_id',
                        'students.id'
                    )

                    ->where(
                        'student_courses.course_id',
                        $selectedSchedule->course_id
                    )

                    ->where(
                        'student_courses.academic_year_id',
                        $selectedExamination->academic_year_id
                    )

                    ->where(
                        'student_courses.status',
                        'enrolled'
                    );

                if ($selectedExamination->term_id !== null) {

                    $query->where(
                        'student_courses.term_id',
                        $selectedExamination->term_id
                    );

                } else {

                    $query->whereNull(
                        'student_courses.term_id'
                    );
                }
            })

            ->orderBy('students.first_name')
            ->orderBy('students.last_name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Existing marks
        |--------------------------------------------------------------------------
        */

        $existingMarks = Mark::query()
            ->where(
                'examination_id',
                $selectedExamination->id
            )
            ->where(
                'course_id',
                $selectedSchedule->course_id
            )
            ->whereIn(
                'student_id',
                $eligibleStudents->pluck('id')
            )
            ->get()
            ->keyBy('student_id');

        /*
        |--------------------------------------------------------------------------
        | Attach existing mark to each student
        |--------------------------------------------------------------------------
        */

        $eligibleStudents->each(function ($student) use ($existingMarks) {

            $student->existingMark = $existingMarks->get(
                $student->id
            );
        });

        /*
        |--------------------------------------------------------------------------
        | Selected student
        |--------------------------------------------------------------------------
        */

        if ($request->filled('student_id')) {

            $selectedStudent = $eligibleStudents->firstWhere(
                'id',
                $request->integer('student_id')
            );

            if (!$selectedStudent) {

                throw ValidationException::withMessages([
                    'student_id' =>
                        'The selected student is not eligible for this examination schedule.',
                ]);
            }

            $existingMark = $existingMarks->get(
                $selectedStudent->id
            );
        }
    }

    return view(
        'admin.marks.create',
        compact(
            'examinations',
            'selectedExamination',
            'schedules',
            'selectedSchedule',
            'eligibleStudents',
            'selectedStudent',
            'existingMark'
        )
    );
}

    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */

    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'examination_id' => [
                'required',
                'integer',
                'exists:examinations,id',
            ],
            'schedule_id' => [
                'required',
                'integer',
                'exists:exam_schedules,id',
            ],
            'student_id' => [
                'required',
                'integer',
                'exists:students,id',
            ],
            'score' => [
                'required',
                'numeric',
                'min:0',
            ],
            'maximum_score' => [
                'required',
                'numeric',
                'gt:0',
            ],
        ]);

        $examination = Examination::query()
            ->where('school_id', $user->school_id)
            ->findOrFail($validated['examination_id']);

        $schedule = ExamSchedule::query()
            ->with([
                'course',
                'classModel',
                'section',
            ])
            ->where('examination_id', $examination->id)
            ->whereNull('deleted_at')
            ->findOrFail($validated['schedule_id']);

        $student = Student::query()
            ->where('school_id', $user->school_id)
            ->where('status', 'active')
            ->findOrFail($validated['student_id']);

        $this->ensureStudentIsEligible(
            $student,
            $examination,
            $schedule
        );

        if ((float) $validated['score'] >
            (float) $validated['maximum_score']) {
            throw ValidationException::withMessages([
                'score' => 'The score cannot be greater than the maximum score.',
            ]);
        }

        $existingMark = Mark::query()
            ->where('student_id', $student->id)
            ->where('examination_id', $examination->id)
            ->where('course_id', $schedule->course_id)
            ->first();

        if ($existingMark) {
            throw ValidationException::withMessages([
                'student_id' => 'A mark already exists for this student, examination and course.',
            ]);
        }

        $percentage = $this->calculatePercentage(
            (float) $validated['score'],
            (float) $validated['maximum_score']
        );

        DB::transaction(function () use (
            $student,
            $examination,
            $schedule,
            $validated,
            $user,
            $percentage
        ) {
            Mark::create([
                'student_id' => $student->id,
                'examination_id' => $examination->id,
                'course_id' => $schedule->course_id,
                'score' => $validated['score'],
                'maximum_score' => $validated['maximum_score'],
                'grade' => $this->calculateGrade($percentage),
                'status' => 'draft',
                'entered_by' => $user->id,
                'approved_by' => null,
            ]);
        });

        return redirect()
            ->route('admin.marks.index')
            ->with('success', 'Mark saved successfully as draft.');
    }

    /*
    |--------------------------------------------------------------------------
    | Show
    |--------------------------------------------------------------------------
    */

    public function show(Mark $mark): View
    {
        $user = Auth::user();

        $mark->load([
            'student',
            'examination',
            'course',
            'enteredBy',
            'approvedBy',
        ]);

        $this->ensureMarkBelongsToSchool($mark, $user);

        return view('admin.marks.show', compact('mark'));
    }

    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit(Mark $mark): View
    {
        $user = Auth::user();

        $mark->load([
            'student',
            'examination',
            'course',
        ]);

        $this->ensureMarkBelongsToSchool($mark, $user);

        if ($mark->status === 'approved') {
            abort(
                403,
                'Approved marks cannot be edited.'
            );
        }

        if ($mark->status === 'submitted') {
            abort(
                403,
                'Submitted marks cannot be edited until they are returned to draft.'
            );
        }

        return view('admin.marks.edit', compact('mark'));
    }

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Mark $mark
    ): RedirectResponse {
        $user = Auth::user();

        $this->ensureMarkBelongsToSchool($mark, $user);

        if ($mark->status === 'approved') {
            abort(
                403,
                'Approved marks cannot be edited.'
            );
        }

        if ($mark->status === 'submitted') {
            abort(
                403,
                'Submitted marks cannot be edited.'
            );
        }

        $validated = $request->validate([
            'score' => [
                'required',
                'numeric',
                'min:0',
            ],
            'maximum_score' => [
                'required',
                'numeric',
                'gt:0',
            ],
        ]);

        if ((float) $validated['score'] >
            (float) $validated['maximum_score']) {
            throw ValidationException::withMessages([
                'score' => 'The score cannot be greater than the maximum score.',
            ]);
        }

        $percentage = $this->calculatePercentage(
            (float) $validated['score'],
            (float) $validated['maximum_score']
        );

        $mark->update([
            'score' => $validated['score'],
            'maximum_score' => $validated['maximum_score'],
            'grade' => $this->calculateGrade($percentage),
            'status' => 'draft',
            'approved_by' => null,
        ]);

        return redirect()
            ->route('admin.marks.show', $mark)
            ->with('success', 'Mark updated successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Submit
    |--------------------------------------------------------------------------
    */

    public function submit(Mark $mark): RedirectResponse
    {
        $user = Auth::user();

        $this->ensureMarkBelongsToSchool($mark, $user);

        if ($mark->status !== 'draft') {
            throw ValidationException::withMessages([
                'status' => 'Only draft marks can be submitted.',
            ]);
        }

        $mark->update([
            'status' => 'submitted',
        ]);

        return redirect()
            ->route('admin.marks.show', $mark)
            ->with('success', 'Mark submitted for approval.');
    }

    /*
    |--------------------------------------------------------------------------
    | Approve
    |--------------------------------------------------------------------------
    */

    public function approve(Mark $mark): RedirectResponse
    {
        $user = Auth::user();

        $this->ensureMarkBelongsToSchool($mark, $user);

        if ($mark->status !== 'submitted') {
            throw ValidationException::withMessages([
                'status' => 'Only submitted marks can be approved.',
            ]);
        }

        $mark->update([
            'status' => 'approved',
            'approved_by' => $user->id,
        ]);

        return redirect()
            ->route('admin.marks.show', $mark)
            ->with('success', 'Mark approved successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Student Eligibility
    |--------------------------------------------------------------------------
    */

    private function ensureStudentIsEligible(
        Student $student,
        Examination $examination,
        ExamSchedule $schedule
    ): void {
        if ($schedule->examination_id !== $examination->id) {
            throw ValidationException::withMessages([
                'schedule_id' => 'The selected schedule does not belong to the selected examination.',
            ]);
        }

        if (
            !$schedule->course ||
            $schedule->course->school_id !== $examination->school_id
        ) {
            throw ValidationException::withMessages([
                'schedule_id' => 'The selected schedule has an invalid course.',
            ]);
        }

        if (
            !$schedule->classModel ||
            $schedule->classModel->school_id !== $examination->school_id
        ) {
            throw ValidationException::withMessages([
                'schedule_id' => 'The selected schedule has an invalid class.',
            ]);
        }

        $enrollmentQuery = StudentEnrollment::query()
            ->where('student_id', $student->id)
            ->where('academic_year_id', $examination->academic_year_id)
            ->where('class_id', $schedule->class_id)
            ->where('status', 'active');

        if ($examination->term_id !== null) {
            $enrollmentQuery->where(
                'term_id',
                $examination->term_id
            );
        } else {
            $enrollmentQuery->whereNull('term_id');
        }

        if ($schedule->section_id !== null) {
            $enrollmentQuery->where(
                'section_id',
                $schedule->section_id
            );
        }

        $enrollmentExists = $enrollmentQuery->exists();

        if (!$enrollmentExists) {
            throw ValidationException::withMessages([
                'student_id' => 'The selected student is not actively enrolled in the scheduled class, section, academic year and term.',
            ]);
        }

        $courseQuery = DB::table('student_courses')
            ->where('student_id', $student->id)
            ->where('course_id', $schedule->course_id)
            ->where('academic_year_id', $examination->academic_year_id)
            ->where('status', 'enrolled');

        if ($examination->term_id !== null) {
            $courseQuery->where(
                'term_id',
                $examination->term_id
            );
        } else {
            $courseQuery->whereNull('term_id');
        }

        if (!$courseQuery->exists()) {
            throw ValidationException::withMessages([
                'student_id' => 'The selected student is not registered for this course in the examination academic year and term.',
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | School Isolation
    |--------------------------------------------------------------------------
    */

    private function ensureMarkBelongsToSchool(
        Mark $mark,
        $user
    ): void {
        $mark->loadMissing('examination');

        if (
            !$mark->examination ||
            $mark->examination->school_id !== $user->school_id
        ) {
            abort(
                404,
                'Mark not found.'
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Percentage
    |--------------------------------------------------------------------------
    */

    private function calculatePercentage(
        float $score,
        float $maximumScore
    ): float {
        if ($maximumScore <= 0) {
            return 0;
        }

        return round(
            ($score / $maximumScore) * 100,
            2
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Grade
    |--------------------------------------------------------------------------
    */

    private function calculateGrade(float $percentage): string
    {
        $grade = Grade::query()
            ->where('school_id', Auth::user()->school_id)
            ->where('minimum_score', '<=', $percentage)
            ->where('maximum_score', '>=', $percentage)
            ->orderBy('minimum_score', 'desc')
            ->first();

        if (!$grade) {
            throw ValidationException::withMessages([
                'score' => 'No grading scale is configured for this score percentage.',
            ]);
        }

        return $grade->code;
    }

    private function eligibleStudentsForSchedule(
    Examination $examination,
    ExamSchedule $schedule,
    int $schoolId
) {
    // Make sure the schedule's section belongs to the schedule's class.
    if ($schedule->section_id) {
        $sectionClassId = $schedule->section?->class_id;

        if ((int) $sectionClassId !== (int) $schedule->class_id) {
            abort(422, 'The selected examination schedule has an invalid section.');
        }
    }

    return Student::query()
        ->where('students.school_id', $schoolId)
        ->where('students.status', 'active')

        // Student must have an active enrollment matching
        // the examination and schedule.
        ->whereHas('enrollments', function ($query) use (
            $examination,
            $schedule,
            $schoolId
        ) {
            $query
                ->where('student_enrollments.school_id', $schoolId)
                ->where('student_enrollments.status', 'active')
                ->where(
                    'student_enrollments.academic_year_id',
                    $examination->academic_year_id
                )
                ->where(
                    'student_enrollments.class_id',
                    $schedule->class_id
                );

            if ($examination->term_id === null) {
                $query->whereNull('student_enrollments.term_id');
            } else {
                $query->where(
                    'student_enrollments.term_id',
                    $examination->term_id
                );
            }

            // A schedule with a section applies only to that section.
            // NULL section means All Sections.
            if ($schedule->section_id !== null) {
                $query->where(
                    'student_enrollments.section_id',
                    $schedule->section_id
                );
            }
        })

        // Student must also be registered for the scheduled course.
        ->whereExists(function ($query) use ($examination, $schedule) {
            $query
                ->select(DB::raw(1))
                ->from('student_courses')
                ->whereColumn(
                    'student_courses.student_id',
                    'students.id'
                )
                ->where(
                    'student_courses.course_id',
                    $schedule->course_id
                )
                ->where(
                    'student_courses.academic_year_id',
                    $examination->academic_year_id
                )
                ->where(
                    'student_courses.status',
                    'enrolled'
                );

            if ($examination->term_id === null) {
                $query->whereNull('student_courses.term_id');
            } else {
                $query->where(
                    'student_courses.term_id',
                    $examination->term_id
                );
            }
        })

        ->orderBy('students.first_name')
        ->orderBy('students.last_name')
        ->get();
}
}