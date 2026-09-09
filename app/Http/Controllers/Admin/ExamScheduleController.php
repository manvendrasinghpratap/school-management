<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\Courses;
use App\Models\Examination;
use App\Models\ExamSchedule;
use App\Models\Section;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ExamScheduleController extends Controller
{
    /**
     * Display a listing of exam schedules.
     */
    public function index(Request $request): View
    {
        abort_unless(
            $request->user()?->can('exam-schedules.view'),
            403
        );

        $schoolId = $this->schoolId();

        $query = ExamSchedule::query()
            ->whereHas('examination', function ($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            })
            ->with([
                'examination',
                'course',
                'classModel',
                'section',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim(
                $request->string('search')->toString()
            );

            $query->where(function ($q) use ($search) {

                $q->whereHas(
                    'examination',
                    function ($examQuery) use ($search) {

                        $examQuery->where(
                            'name',
                            'like',
                            "%{$search}%"
                        );
                    }
                )

                ->orWhereHas(
                    'course',
                    function ($courseQuery) use ($search) {

                        $courseQuery
                            ->where(
                                'name',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'course_code',
                                'like',
                                "%{$search}%"
                            );
                    }
                )

                ->orWhereHas(
                    'classModel',
                    function ($classQuery) use ($search) {

                        $classQuery
                            ->where(
                                'name',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'code',
                                'like',
                                "%{$search}%"
                            );
                    }
                )

                ->orWhereHas(
                    'section',
                    function ($sectionQuery) use ($search) {

                        $sectionQuery
                            ->where(
                                'name',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'code',
                                'like',
                                "%{$search}%"
                            );
                    }
                );
            });
        }


        /*
        |--------------------------------------------------------------------------
        | Examination Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('examination_id')) {

            $query->where(
                'examination_id',
                $request->integer('examination_id')
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Course Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('course_id')) {

            $query->where(
                'course_id',
                $request->integer('course_id')
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Class Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('class_id')) {

            $query->where(
                'class_id',
                $request->integer('class_id')
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Section Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('section_id')) {

            $query->where(
                'section_id',
                $request->integer('section_id')
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Exam Date Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('exam_date')) {

            $query->whereDate(
                'exam_date',
                $request->date('exam_date')
            );
        }


        $schedules = $query
            ->orderBy('exam_date')
            ->orderBy('start_time')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Filter Dropdown Data
        |--------------------------------------------------------------------------
        */

        $examinations = Examination::query()
            ->where('school_id', $schoolId)
            ->orderByDesc('start_date')
            ->orderByDesc('id')
            ->get();

        $courses = Courses::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $classes = Classes::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $sections = Section::query()
            ->whereHas('class', function ($q) use ($schoolId) {

                $q->where('school_id', $schoolId);

            })
            ->where('is_active', true)
            ->with('class')
            ->orderBy('name')
            ->get();


        return view(
            'admin.exam-schedules.index',
            compact(
                'schedules',
                'examinations',
                'courses',
                'classes',
                'sections'
            )
        );
    }


    /**
     * Show the form for creating a new exam schedule.
     */
    public function create(Request $request): View
    {
        abort_unless(
            $request->user()?->can('exam-schedules.manage'),
            403
        );

        $schoolId = $this->schoolId();

        $examinations = Examination::query()
            ->where('school_id', $schoolId)
            ->orderByDesc('start_date')
            ->orderByDesc('id')
            ->get();

        $courses = Courses::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $classes = Classes::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->with([
                'sections' => function ($query) {

                    $query
                        ->where('is_active', true)
                        ->orderBy('name');

                }
            ])
            ->orderBy('name')
            ->get();


        return view(
            'admin.exam-schedules.create',
            compact(
                'examinations',
                'courses',
                'classes'
            )
        );
    }


    /**
     * Store a newly created exam schedule.
     */
    public function store(Request $request): RedirectResponse
    {
        abort_unless(
            $request->user()?->can('exam-schedules.manage'),
            403
        );

        $schoolId = $this->schoolId();


        /*
        |--------------------------------------------------------------------------
        | Basic Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'examination_id' => [
                'required',
                'integer',
                'exists:examinations,id',
            ],

            'course_id' => [
                'required',
                'integer',
                'exists:courses,id',
            ],

            'class_id' => [
                'required',
                'integer',
                'exists:classes,id',
            ],

            'section_id' => [
                'nullable',
                'integer',
                'exists:sections,id',
            ],

            'exam_date' => [
                'required',
                'date',
            ],

            'start_time' => [
                'nullable',
                'date_format:H:i',
            ],

            'end_time' => [
                'nullable',
                'date_format:H:i',
                'after:start_time',
            ],

            'room' => [
                'nullable',
                'string',
                'max:100',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Examination Security
        |--------------------------------------------------------------------------
        */

        $examination = Examination::query()
            ->where('school_id', $schoolId)
            ->find($validated['examination_id']);


        if (!$examination) {

            throw ValidationException::withMessages([
                'examination_id' =>
                    'The selected examination does not belong to your school.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Course Security
        |--------------------------------------------------------------------------
        */

        $course = Courses::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->find($validated['course_id']);


        if (!$course) {

            throw ValidationException::withMessages([
                'course_id' =>
                    'The selected course is invalid or inactive.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Class Security
        |--------------------------------------------------------------------------
        */

        $class = Classes::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->find($validated['class_id']);


        if (!$class) {

            throw ValidationException::withMessages([
                'class_id' =>
                    'The selected class is invalid or inactive.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Section Security
        |--------------------------------------------------------------------------
        */

        $section = null;


        if (!empty($validated['section_id'])) {

            $section = Section::query()
                ->where('class_id', $class->id)
                ->where('is_active', true)
                ->find($validated['section_id']);


            if (!$section) {

                throw ValidationException::withMessages([
                    'section_id' =>
                        'The selected section does not belong to the selected class.',
                ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Examination Date Validation
        |--------------------------------------------------------------------------
        */

        $examDate = $validated['exam_date'];


        if (
            $examination->start_date &&
            $examDate < $examination->start_date->format('Y-m-d')
        ) {

            throw ValidationException::withMessages([
                'exam_date' =>
                    'Exam date cannot be before the examination start date.',
            ]);
        }


        if (
            $examination->end_date &&
            $examDate > $examination->end_date->format('Y-m-d')
        ) {

            throw ValidationException::withMessages([
                'exam_date' =>
                    'Exam date cannot be after the examination end date.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Schedule Conflict Validation
        |--------------------------------------------------------------------------
        |
        | Two schedules cannot overlap when they have:
        |
        | - Same examination
        | - Same class
        | - Same exam date
        | - Same section OR one schedule applies to all sections
        |
        */

        $this->ensureNoScheduleConflict(
            $validated,
            null
        );


        /*
        |--------------------------------------------------------------------------
        | Create Schedule
        |--------------------------------------------------------------------------
        */

        ExamSchedule::create([

            'examination_id' =>
                $examination->id,

            'course_id' =>
                $course->id,

            'class_id' =>
                $class->id,

            'section_id' =>
                $section?->id,

            'exam_date' =>
                $validated['exam_date'],

            'start_time' =>
                $validated['start_time'] ?? null,

            'end_time' =>
                $validated['end_time'] ?? null,

            'room' =>
                $validated['room'] ?? null,
        ]);


        return redirect()
            ->route('admin.exam-schedules.index')
            ->with(
                'success',
                'Exam schedule created successfully.'
            );
    }


    /**
     * Display the specified exam schedule.
     */
    public function show(
        Request $request,
        ExamSchedule $examSchedule
    ): View {

        abort_unless(
            $request->user()?->can('exam-schedules.view'),
            403
        );

        $this->ensureScheduleBelongsToSchool(
            $examSchedule
        );


        $examSchedule->load([
            'examination',
            'course',
            'classModel',
            'section',
        ]);


        return view(
            'admin.exam-schedules.show',
            compact('examSchedule')
        );
    }


    /**
     * Show the form for editing the specified exam schedule.
     */
    public function edit(
        Request $request,
        ExamSchedule $examSchedule
    ): View {

        abort_unless(
            $request->user()?->can('exam-schedules.manage'),
            403
        );

        $this->ensureScheduleBelongsToSchool(
            $examSchedule
        );


        $schoolId = $this->schoolId();


        $examinations = Examination::query()
            ->where('school_id', $schoolId)
            ->orderByDesc('start_date')
            ->orderByDesc('id')
            ->get();


        $courses = Courses::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();


        $classes = Classes::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->with([
                'sections' => function ($query) {

                    $query
                        ->where('is_active', true)
                        ->orderBy('name');

                }
            ])
            ->orderBy('name')
            ->get();


        return view(
            'admin.exam-schedules.edit',
            compact(
                'examSchedule',
                'examinations',
                'courses',
                'classes'
            )
        );
    }


    /**
     * Update the specified exam schedule.
     */
    public function update(
        Request $request,
        ExamSchedule $examSchedule
    ): RedirectResponse {

        abort_unless(
            $request->user()?->can('exam-schedules.manage'),
            403
        );

        $this->ensureScheduleBelongsToSchool(
            $examSchedule
        );


        $schoolId = $this->schoolId();


        /*
        |--------------------------------------------------------------------------
        | Basic Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'examination_id' => [
                'required',
                'integer',
                'exists:examinations,id',
            ],

            'course_id' => [
                'required',
                'integer',
                'exists:courses,id',
            ],

            'class_id' => [
                'required',
                'integer',
                'exists:classes,id',
            ],

            'section_id' => [
                'nullable',
                'integer',
                'exists:sections,id',
            ],

            'exam_date' => [
                'required',
                'date',
            ],

            'start_time' => [
                'nullable',
                'date_format:H:i',
            ],

            'end_time' => [
                'nullable',
                'date_format:H:i',
                'after:start_time',
            ],

            'room' => [
                'nullable',
                'string',
                'max:100',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Examination Security
        |--------------------------------------------------------------------------
        */

        $examination = Examination::query()
            ->where('school_id', $schoolId)
            ->find($validated['examination_id']);


        if (!$examination) {

            throw ValidationException::withMessages([
                'examination_id' =>
                    'The selected examination does not belong to your school.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Course Security
        |--------------------------------------------------------------------------
        */

        $course = Courses::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->find($validated['course_id']);


        if (!$course) {

            throw ValidationException::withMessages([
                'course_id' =>
                    'The selected course is invalid or inactive.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Class Security
        |--------------------------------------------------------------------------
        */

        $class = Classes::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->find($validated['class_id']);


        if (!$class) {

            throw ValidationException::withMessages([
                'class_id' =>
                    'The selected class is invalid or inactive.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Section Security
        |--------------------------------------------------------------------------
        */

        $section = null;


        if (!empty($validated['section_id'])) {

            $section = Section::query()
                ->where('class_id', $class->id)
                ->where('is_active', true)
                ->find($validated['section_id']);


            if (!$section) {

                throw ValidationException::withMessages([
                    'section_id' =>
                        'The selected section does not belong to the selected class.',
                ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Examination Date Validation
        |--------------------------------------------------------------------------
        */

        $examDate = $validated['exam_date'];


        if (
            $examination->start_date &&
            $examDate < $examination->start_date->format('Y-m-d')
        ) {

            throw ValidationException::withMessages([
                'exam_date' =>
                    'Exam date cannot be before the examination start date.',
            ]);
        }


        if (
            $examination->end_date &&
            $examDate > $examination->end_date->format('Y-m-d')
        ) {

            throw ValidationException::withMessages([
                'exam_date' =>
                    'Exam date cannot be after the examination end date.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Schedule Conflict Validation
        |--------------------------------------------------------------------------
        */

        $this->ensureNoScheduleConflict(
            $validated,
            $examSchedule->id
        );


        /*
        |--------------------------------------------------------------------------
        | Update Schedule
        |--------------------------------------------------------------------------
        */

        $examSchedule->update([

            'examination_id' =>
                $examination->id,

            'course_id' =>
                $course->id,

            'class_id' =>
                $class->id,

            'section_id' =>
                $section?->id,

            'exam_date' =>
                $validated['exam_date'],

            'start_time' =>
                $validated['start_time'] ?? null,

            'end_time' =>
                $validated['end_time'] ?? null,

            'room' =>
                $validated['room'] ?? null,
        ]);


        return redirect()
            ->route(
                'admin.exam-schedules.show',
                $examSchedule
            )
            ->with(
                'success',
                'Exam schedule updated successfully.'
            );
    }


    /**
     * Delete the specified exam schedule.
     */
    public function destroy(
        Request $request,
        ExamSchedule $examSchedule
    ): RedirectResponse {

        abort_unless(
            $request->user()?->can('exam-schedules.manage'),
            403
        );

        $this->ensureScheduleBelongsToSchool(
            $examSchedule
        );


        $examSchedule->delete();


        return redirect()
            ->route('admin.exam-schedules.index')
            ->with(
                'success',
                'Exam schedule deleted successfully.'
            );
    }


    /**
     * Prevent overlapping schedules.
     */
    protected function ensureNoScheduleConflict(
        array $validated,
        ?int $ignoreScheduleId = null
    ): void {

        /*
        |--------------------------------------------------------------------------
        | If no start/end time is supplied
        |--------------------------------------------------------------------------
        |
        | We cannot calculate a time overlap.
        |
        | However, we still prevent an exact same
        | examination/class/section/date schedule.
        |
        */

        $query = ExamSchedule::query()
            ->where(
                'examination_id',
                $validated['examination_id']
            )
            ->where(
                'class_id',
                $validated['class_id']
            )
            ->whereDate(
                'exam_date',
                $validated['exam_date']
            );


        /*
        |--------------------------------------------------------------------------
        | Ignore current record during update
        |--------------------------------------------------------------------------
        */

        if ($ignoreScheduleId !== null) {

            $query->where(
                'id',
                '!=',
                $ignoreScheduleId
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Section Conflict Logic
        |--------------------------------------------------------------------------
        |
        | A schedule with section_id = NULL means:
        |
        | "All Sections"
        |
        | Therefore:
        |
        | All Sections conflicts with any section.
        |
        | Section A conflicts with:
        | - Section A
        | - All Sections
        |
        | Section A does NOT conflict with:
        | - Section B
        |
        */

        $sectionId =
            $validated['section_id'] ?? null;


        $query->where(function ($q) use ($sectionId) {

            if ($sectionId === null) {

                $q->whereNull('section_id');

            } else {

                $q->whereNull('section_id')
                    ->orWhere(
                        'section_id',
                        $sectionId
                    );
            }

        });


        /*
        |--------------------------------------------------------------------------
        | Check Existing Records
        |--------------------------------------------------------------------------
        */

        $existingSchedules =
            $query->get([
                'id',
                'start_time',
                'end_time',
            ]);


        /*
        |--------------------------------------------------------------------------
        | No Existing Schedule
        |--------------------------------------------------------------------------
        */

        if ($existingSchedules->isEmpty()) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | New Schedule Times
        |--------------------------------------------------------------------------
        */

        $newStart =
            $validated['start_time'] ?? null;

        $newEnd =
            $validated['end_time'] ?? null;


        /*
        |--------------------------------------------------------------------------
        | If New Schedule Has No Time
        |--------------------------------------------------------------------------
        |
        | Treat it as a full-day/class schedule and prevent
        | another schedule for the same class/section/date.
        |
        */

        if (!$newStart || !$newEnd) {

            throw ValidationException::withMessages([
                'exam_date' =>
                    'A schedule already exists for this examination, class, section and date.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Compare Time Ranges
        |--------------------------------------------------------------------------
        |
        | Two ranges overlap when:
        |
        | existing_start < new_end
        | AND
        | existing_end > new_start
        |
        */

        foreach ($existingSchedules as $existing) {

            /*
            | If existing schedule has no complete time,
            | treat it as conflicting with any timed schedule.
            */

            if (
                !$existing->start_time ||
                !$existing->end_time
            ) {

                throw ValidationException::withMessages([
                    'start_time' =>
                        'This schedule conflicts with an existing schedule for the selected class, section and date.',
                ]);
            }


            $existingStart =
                substr(
                    (string) $existing->start_time,
                    0,
                    5
                );

            $existingEnd =
                substr(
                    (string) $existing->end_time,
                    0,
                    5
                );


            /*
            | Detect overlap.
            */

            if (
                $existingStart < $newEnd &&
                $existingEnd > $newStart
            ) {

                throw ValidationException::withMessages([
                    'start_time' =>
                        'The selected time overlaps with an existing exam schedule for this class, section and date.',
                ]);
            }
        }
    }


    /**
     * Get the current user's school ID.
     */
    protected function schoolId(): int
    {
        $user = Auth::user();

        abort_unless($user, 403);

        abort_unless(
            $user->school_id,
            403
        );

        return (int) $user->school_id;
    }


    /**
     * Ensure the schedule belongs to the current user's school.
     */
    protected function ensureScheduleBelongsToSchool(
        ExamSchedule $examSchedule
    ): void {

        $schoolId = $this->schoolId();


        $examSchedule->loadMissing(
            'examination'
        );


        $belongsToSchool =
            $examSchedule->examination &&
            (int) $examSchedule->examination->school_id ===
            $schoolId;


        abort_unless(
            $belongsToSchool,
            403
        );
    }
}