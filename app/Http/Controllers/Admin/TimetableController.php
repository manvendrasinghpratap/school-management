<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYears;
use App\Models\Classes;
use App\Models\Courses;
use App\Models\Section;
use App\Models\Staff;
use App\Models\Terms;
use App\Models\Timetable;
use App\Services\TimetableService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class TimetableController extends Controller
{
    public function __construct(
        protected TimetableService $timetableService
    ) {
    }

    /**
     * Display timetable entries.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        abort_unless(
            $user->school_id,
            403,
            'No school is assigned to the current user.'
        );

        $query = Timetable::query()
            ->where('school_id', $user->school_id)
            ->with([
                'course',
                'class',
                'section',
                'staff',
                'academicYear',
                'term',
            ])
            ->orderByRaw("
                FIELD(
                    day_of_week,
                    'monday',
                    'tuesday',
                    'wednesday',
                    'thursday',
                    'friday',
                    'saturday',
                    'sunday'
                )
            ")
            ->orderBy('start_time')
            ->orderBy('id');

        if ($request->filled('academic_year_id')) {
            $query->where(
                'academic_year_id',
                $request->academic_year_id
            );
        }

        if ($request->filled('term_id')) {
            $query->where(
                'term_id',
                $request->term_id
            );
        }

        if ($request->filled('class_id')) {
            $query->where(
                'class_id',
                $request->class_id
            );
        }

        if ($request->filled('section_id')) {
            $query->where(
                'section_id',
                $request->section_id
            );
        }

        if ($request->filled('staff_id')) {
            $query->where(
                'staff_id',
                $request->staff_id
            );
        }

        if ($request->filled('day_of_week')) {
            $query->where(
                'day_of_week',
                $request->day_of_week
            );
        }

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('room', 'like', "%{$search}%")
                    ->orWhereHas('course', function ($courseQuery) use ($search) {
                        $courseQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere(
                                'course_code',
                                'like',
                                "%{$search}%"
                            );
                    })
                    ->orWhereHas('class', function ($classQuery) use ($search) {
                        $classQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere(
                                'code',
                                'like',
                                "%{$search}%"
                            );
                    })
                    ->orWhereHas('section', function ($sectionQuery) use ($search) {
                        $sectionQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere(
                                'code',
                                'like',
                                "%{$search}%"
                            );
                    })
                    ->orWhereHas('staff', function ($staffQuery) use ($search) {
                        $staffQuery->where(function ($staffQ) use ($search) {
                            $staffQ
                                ->where(
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
                                    'staff_number',
                                    'like',
                                    "%{$search}%"
                                );
                        });
                    });
            });
        }

        $timetables = $query
            ->paginate(20)
            ->withQueryString();

        $academicYears = AcademicYears::query()
            ->where('school_id', $user->school_id)
            ->where('is_active', true)
            ->whereNull('deleted_at')
            ->orderByDesc('start_date')
            ->get();

        $classes = Classes::query()
            ->where('school_id', $user->school_id)
            ->where('is_active', true)
            ->whereNull('deleted_at')
            ->orderBy('name')
            ->get();

        $staff = Staff::query()
            ->where('school_id', $user->school_id)
            ->where('status', 'active')
            ->whereNull('deleted_at')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        return view(
            'admin.timetables.index',
            compact(
                'timetables',
                'academicYears',
                'classes',
                'staff'
            )
        );
    }

    /**
     * Show create form.
     */
    public function create()
    {
        $user = Auth::user();

        abort_unless(
            $user->school_id,
            403,
            'No school is assigned to the current user.'
        );

        $data = $this->formData($user->school_id);

        return view(
            'admin.timetables.create',
            $data
        );
    }

    /**
     * Store a timetable entry.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => [
                'required',
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

            'staff_id' => [
                'nullable',
                'integer',
            ],

            'academic_year_id' => [
                'required',
                'integer',
            ],

            'term_id' => [
                'nullable',
                'integer',
            ],

            'day_of_week' => [
                'required',
                'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            ],

            'start_time' => [
                'required',
                'date_format:H:i',
            ],

            'end_time' => [
                'required',
                'date_format:H:i',
            ],

            'room' => [
                'nullable',
                'string',
                'max:100',
            ],
        ]);

        $timetable = $this->timetableService->create(
            Auth::user(),
            $validated
        );

        return redirect()
            ->route('admin.timetables.show', $timetable)
            ->with(
                'success',
                'Timetable entry created successfully.'
            );
    }

    /**
     * Display a timetable entry.
     */
    public function show(Timetable $timetable)
    {
        $user = Auth::user();

        abort_unless(
            $user->school_id,
            403,
            'No school is assigned to the current user.'
        );

        abort_unless(
            (int) $timetable->school_id ===
            (int) $user->school_id,
            403,
            'The selected timetable entry is invalid.'
        );

        $timetable->load([
            'school',
            'course',
            'class',
            'section',
            'staff',
            'academicYear',
            'term',
        ]);

        return view(
            'admin.timetables.show',
            compact('timetable')
        );
    }

    /**
     * Show edit form.
     */
    public function edit(Timetable $timetable)
    {
        $user = Auth::user();

        abort_unless(
            $user->school_id,
            403,
            'No school is assigned to the current user.'
        );

        abort_unless(
            (int) $timetable->school_id ===
            (int) $user->school_id,
            403,
            'The selected timetable entry is invalid.'
        );

        $data = $this->formData(
            $user->school_id,
            $timetable
        );

        $data['timetable'] = $timetable;

        return view(
            'admin.timetables.edit',
            $data
        );
    }

    /**
     * Update a timetable entry.
     */
    public function update(
        Request $request,
        Timetable $timetable
    ) {
        $validated = $request->validate([
            'course_id' => [
                'required',
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
            'staff_id' => [
                'nullable',
                'integer',
            ],
            'academic_year_id' => [
                'required',
                'integer',
            ],
            'term_id' => [
                'nullable',
                'integer',
            ],
            'day_of_week' => [
                'required',
                'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            ],
            'start_time' => [
                'required',
                'date_format:H:i',
            ],
            'end_time' => [
                'required',
                'date_format:H:i',
            ],
            'room' => [
                'nullable',
                'string',
                'max:100',
            ],
        ]);

        try {
            $timetable = $this->timetableService->update(
                Auth::user(),
                $timetable,
                $validated
            );

            return redirect()
                ->route('admin.timetables.show', $timetable)
                ->with(
                    'success',
                    'Timetable entry updated successfully.'
                );
        } catch (ValidationException $e) {
            throw $e;
        }
    }

    /**
     * Soft-delete a timetable entry.
     */
    public function destroy(Timetable $timetable)
    {
        try {
            $this->timetableService->delete(
                Auth::user(),
                $timetable
            );

            return redirect()
                ->route('admin.timetables.index')
                ->with(
                    'success',
                    'Timetable entry deleted successfully.'
                );
        } catch (ValidationException $e) {
            throw $e;
        }
    }

    /**
     * Restore a deleted timetable entry.
     */
    public function restore(int $timetable)
    {
        try {
            $restored = $this->timetableService->restore(
                Auth::user(),
                $timetable
            );

            return redirect()
                ->route('admin.timetables.show', $restored)
                ->with(
                    'success',
                    'Timetable entry restored successfully.'
                );
        } catch (ValidationException $e) {
            throw $e;
        }
    }

    /**
     * Load data used by create/edit forms.
     */
    protected function formData(
        int $schoolId,
        ?Timetable $timetable = null
    ): array {
        $academicYears = AcademicYears::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->whereNull('deleted_at')
            ->orderByDesc('start_date')
            ->get();

        $courses = Courses::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->whereNull('deleted_at')
            ->with('department')
            ->orderBy('name')
            ->get();

        $classes = Classes::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->whereNull('deleted_at')
            ->with([
                'sections' => function ($query) {
                    $query
                        ->where('is_active', true)
                        ->whereNull('deleted_at')
                        ->orderBy('name');
                },
            ])
            ->orderBy('name')
            ->get();

        $staff = Staff::query()
            ->where('school_id', $schoolId)
            ->where('status', 'active')
            ->whereNull('deleted_at')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        $terms = Terms::query()
            ->where('school_id', $schoolId)
            ->whereNull('deleted_at')
            ->orderBy('academic_year_id')
            ->orderBy('start_date')
            ->get();

        return [
            'academicYears' => $academicYears,
            'courses' => $courses,
            'classes' => $classes,
            'staff' => $staff,
            'terms' => $terms,
        ];
    }
}