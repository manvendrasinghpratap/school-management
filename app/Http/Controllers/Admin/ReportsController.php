<?php

namespace App\Http\Controllers\Admin;

use App\Exports\GenericReportExport;
use App\Http\Controllers\Controller;
use App\Models\AcademicYears;
use App\Models\Attendance;
use App\Models\Classes;
use App\Models\Examination;
use App\Models\Result;
use App\Models\Section;
use App\Models\Staff;
use App\Models\StaffAttendance;
use App\Models\Student;
use App\Models\StudentEnrollment;
use App\Models\Terms;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportsController extends Controller
{
    /**
     * Get authenticated user's school ID.
     */
    protected function schoolId(): int
    {
        $user = Auth::user();

        abort_unless(
            $user?->school_id,
            403,
            'No school is assigned to this user.'
        );

        return (int) $user->school_id;
    }

    /**
     * Reports dashboard.
     */
    public function index()
    {
        $schoolId = $this->schoolId();

        $currentAcademicYear = AcademicYears::query()
            ->where('school_id', $schoolId)
            ->where('is_current', true)
            ->where('is_active', true)
            ->first();

        $currentTerm = $currentAcademicYear
            ? Terms::query()
                ->where('school_id', $schoolId)
                ->where(
                    'academic_year_id',
                    $currentAcademicYear->id
                )
                ->where('is_current', true)
                ->where('is_active', true)
                ->first()
            : null;

        $statistics = [
            'students' => Student::where(
                'school_id',
                $schoolId
            )->count(),

            'active_students' => Student::where(
                'school_id',
                $schoolId
            )
                ->where('status', 'active')
                ->count(),

            'staff' => Staff::where(
                'school_id',
                $schoolId
            )->count(),

            'classes' => Classes::where(
                'school_id',
                $schoolId
            )->count(),

            'sections' => Section::whereHas(
                'class',
                fn ($q) => $q->where(
                    'school_id',
                    $schoolId
                )
            )->count(),

            'attendance_records' => Attendance::where(
                'school_id',
                $schoolId
            )->count(),

            'examinations' => Examination::where(
                'school_id',
                $schoolId
            )->count(),
        ];

        /*
         * Student gender summary.
         */
        $genderSummary = Student::query()
            ->where('school_id', $schoolId)
            ->select(
                'gender',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('gender')
            ->orderByDesc('total')
            ->get();

        /*
         * Attendance summary.
         */
        $attendanceSummary = Attendance::query()
            ->where('school_id', $schoolId)
            ->select(
                'status',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('status')
            ->orderByDesc('total')
            ->get();

        /*
         * Grade summary.
         */
        $gradeSummary = Result::query()
            ->whereHas(
                'student',
                fn ($q) => $q->where(
                    'school_id',
                    $schoolId
                )
            )
            ->select(
                'grade',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('grade')
            ->orderByDesc('total')
            ->get();

        return view(
            'admin.reports.index',
            compact(
                'statistics',
                'currentAcademicYear',
                'currentTerm',
                'genderSummary',
                'attendanceSummary',
                'gradeSummary'
            )
        );
    }

    /**
     * Student report.
     */
    public function students(Request $request)
    {
        $schoolId = $this->schoolId();

        $validated = $request->validate([
            'status' => [
                'nullable',
                'in:active,inactive,graduated,transferred,withdrawn'
            ],

            'gender' => [
                'nullable',
                'in:male,female,other'
            ],

            'search' => [
                'nullable',
                'string',
                'max:100'
            ],
        ]);

        $query = Student::query()
            ->where(
                'school_id',
                $schoolId
            );

        if (!empty($validated['status'])) {
            $query->where(
                'status',
                $validated['status']
            );
        }

        if (!empty($validated['gender'])) {
            $query->where(
                'gender',
                $validated['gender']
            );
        }

        if (!empty($validated['search'])) {
            $search = trim(
                $validated['search']
            );

            $query->where(
                function ($q) use ($search) {
                    $q->where(
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
                    );
                }
            );
        }

        $students = $query
            ->latest('id')
            ->paginate(25)
            ->withQueryString();

        return view(
            'admin.reports.students',
            compact(
                'students',
                'validated'
            )
        );
    }

    /**
     * Enrollment report.
     */
    public function enrollment(Request $request)
    {
        $schoolId = $this->schoolId();

        $validated = $request->validate([
            'academic_year_id' => [
                'nullable',
                'integer'
            ],

            'class_id' => [
                'nullable',
                'integer'
            ],

            'section_id' => [
                'nullable',
                'integer'
            ],

            'status' => [
                'nullable',
                'in:active,completed,transferred,withdrawn'
            ],
        ]);

        $query = StudentEnrollment::query()
            ->where(
                'school_id',
                $schoolId
            )
            ->with([
                'student',
                'academicYear',
                'term',
                'class',
                'section'
            ]);

        if (!empty($validated['academic_year_id'])) {
            $query->where(
                'academic_year_id',
                $validated['academic_year_id']
            );
        }

        if (!empty($validated['class_id'])) {
            $query->where(
                'class_id',
                $validated['class_id']
            );
        }

        if (!empty($validated['section_id'])) {
            $query->where(
                'section_id',
                $validated['section_id']
            );
        }

        if (!empty($validated['status'])) {
            $query->where(
                'status',
                $validated['status']
            );
        }

        $enrollments = $query
            ->latest('enrollment_date')
            ->latest('id')
            ->paginate(25)
            ->withQueryString();

        $academicYears = AcademicYears::query()
            ->where(
                'school_id',
                $schoolId
            )
            ->orderByDesc('start_date')
            ->get();

        $classes = Classes::query()
            ->where(
                'school_id',
                $schoolId
            )
            ->where(
                'is_active',
                true
            )
            ->orderBy('name')
            ->get();

        $sections = Section::query()
            ->whereIn(
                'class_id',
                $classes->pluck('id')
            )
            ->where(
                'is_active',
                true
            )
            ->orderBy('name')
            ->get();

        return view(
            'admin.reports.enrollment',
            compact(
                'enrollments',
                'academicYears',
                'classes',
                'sections',
                'validated'
            )
        );
    }

    /**
     * Academic performance report.
     */
    public function academicPerformance(Request $request)
    {
        $schoolId = $this->schoolId();

        $validated = $request->validate([
            'academic_year_id' => [
                'nullable',
                'integer'
            ],

            'examination_id' => [
                'nullable',
                'integer'
            ],

            'status' => [
                'nullable',
                'in:draft,approved,published'
            ],
        ]);

        $query = Result::query()
            ->whereHas(
                'student',
                fn ($q) => $q->where(
                    'school_id',
                    $schoolId
                )
            )
            ->whereHas(
                'examination',
                function ($q) use (
                    $schoolId,
                    $validated
                ) {
                    $q->where(
                        'school_id',
                        $schoolId
                    );

                    if (!empty(
                        $validated['academic_year_id']
                    )) {
                        $q->where(
                            'academic_year_id',
                            $validated['academic_year_id']
                        );
                    }
                }
            )
            ->with([
                'student',
                'examination.academicYear',
                'examination.term'
            ]);

        if (!empty(
            $validated['examination_id']
        )) {
            $query->where(
                'examination_id',
                $validated['examination_id']
            );
        }

        if (!empty($validated['status'])) {
            $query->where(
                'status',
                $validated['status']
            );
        }

        $results = $query
            ->orderByDesc('average')
            ->orderBy('position')
            ->paginate(25)
            ->withQueryString();

        $academicYears = AcademicYears::query()
            ->where(
                'school_id',
                $schoolId
            )
            ->orderByDesc('start_date')
            ->get();

        $examinations = Examination::query()
            ->where(
                'school_id',
                $schoolId
            )
            ->with([
                'academicYear',
                'term'
            ])
            ->latest('start_date')
            ->latest('id')
            ->get();

        $summaryQuery = Result::query()
            ->whereHas(
                'student',
                fn ($q) => $q->where(
                    'school_id',
                    $schoolId
                )
            )
            ->whereHas(
                'examination',
                function ($q) use (
                    $schoolId,
                    $validated
                ) {
                    $q->where(
                        'school_id',
                        $schoolId
                    );

                    if (!empty(
                        $validated['academic_year_id']
                    )) {
                        $q->where(
                            'academic_year_id',
                            $validated['academic_year_id']
                        );
                    }

                    if (!empty(
                        $validated['examination_id']
                    )) {
                        $q->where(
                            'id',
                            $validated['examination_id']
                        );
                    }
                }
            );

        $performanceSummary = [
            'count' => (clone $summaryQuery)
                ->count(),

            'average' => round(
                (float) (
                    (clone $summaryQuery)
                        ->avg('average')
                    ?? 0
                ),
                2
            ),

            'highest' => round(
                (float) (
                    (clone $summaryQuery)
                        ->max('average')
                    ?? 0
                ),
                2
            ),

            'lowest' => round(
                (float) (
                    (clone $summaryQuery)
                        ->min('average')
                    ?? 0
                ),
                2
            ),
        ];

        $gradeDistribution = (clone $summaryQuery)
            ->select(
                'grade',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('grade')
            ->orderByDesc('total')
            ->get();

        return view(
            'admin.reports.academic-performance',
            compact(
                'results',
                'academicYears',
                'examinations',
                'performanceSummary',
                'gradeDistribution',
                'validated'
            )
        );
    }

    /**
     * Student attendance report.
     *
     * IMPORTANT:
     * Attendance model uses classModel().
     */
    public function attendance(Request $request)
    {
        $schoolId = $this->schoolId();

        $validated = $request->validate([
            'from' => [
                'nullable',
                'date'
            ],

            'to' => [
                'nullable',
                'date',
                'after_or_equal:from'
            ],

            'class_id' => [
                'nullable',
                'integer'
            ],

            'section_id' => [
                'nullable',
                'integer'
            ],

            'status' => [
                'nullable',
                'in:present,absent,late,excused'
            ],
        ]);

        $from = $validated['from']
            ?? now()
                ->startOfMonth()
                ->format('Y-m-d');

        $to = $validated['to']
            ?? now()->format('Y-m-d');

        /*
         * Attendance relationship is classModel.
         */
        $query = Attendance::query()
            ->where(
                'school_id',
                $schoolId
            )
            ->whereDate(
                'attendance_date',
                '>=',
                $from
            )
            ->whereDate(
                'attendance_date',
                '<=',
                $to
            )
            ->with([
                'student',
                'classModel',
                'section',
                'course'
            ]);

        if (!empty($validated['class_id'])) {
            $query->where(
                'class_id',
                $validated['class_id']
            );
        }

        if (!empty(
            $validated['section_id']
        )) {
            $query->where(
                'section_id',
                $validated['section_id']
            );
        }

        if (!empty($validated['status'])) {
            $query->where(
                'status',
                $validated['status']
            );
        }

        $records = $query
            ->latest('attendance_date')
            ->latest('id')
            ->paginate(25)
            ->withQueryString();

        /*
         * Attendance summary.
         *
         * This variable is intentionally called
         * $summary because the Blade view expects
         * $summary.
         */
        $summary = Attendance::query()
            ->where(
                'school_id',
                $schoolId
            )
            ->whereDate(
                'attendance_date',
                '>=',
                $from
            )
            ->whereDate(
                'attendance_date',
                '<=',
                $to
            )
            ->when(
                !empty($validated['class_id']),
                fn ($q) => $q->where(
                    'class_id',
                    $validated['class_id']
                )
            )
            ->when(
                !empty($validated['section_id']),
                fn ($q) => $q->where(
                    'section_id',
                    $validated['section_id']
                )
            )
            ->select(
                'status',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('status')
            ->orderByDesc('total')
            ->get();

        $classes = Classes::query()
            ->where(
                'school_id',
                $schoolId
            )
            ->where(
                'is_active',
                true
            )
            ->orderBy('name')
            ->get();

        $sections = Section::query()
            ->whereIn(
                'class_id',
                $classes->pluck('id')
            )
            ->where(
                'is_active',
                true
            )
            ->orderBy('name')
            ->get();

        return view(
            'admin.reports.attendance',
            compact(
                'records',
                'summary',
                'classes',
                'sections',
                'from',
                'to',
                'validated'
            )
        );
    }

    /**
     * Staff attendance report.
     */
    public function staff(Request $request)
    {
        $schoolId = $this->schoolId();

        $validated = $request->validate([
            'from' => [
                'nullable',
                'date'
            ],

            'to' => [
                'nullable',
                'date',
                'after_or_equal:from'
            ],

            'status' => [
                'nullable',
                'in:present,absent,late,half_day,leave'
            ],

            'search' => [
                'nullable',
                'string',
                'max:100'
            ],
        ]);

        $from = $validated['from']
            ?? now()
                ->startOfMonth()
                ->format('Y-m-d');

        $to = $validated['to']
            ?? now()->format('Y-m-d');

        $query = StaffAttendance::query()
            ->where(
                'school_id',
                $schoolId
            )
            ->whereDate(
                'attendance_date',
                '>=',
                $from
            )
            ->whereDate(
                'attendance_date',
                '<=',
                $to
            )
            ->with('staff');

        if (!empty($validated['status'])) {
            $query->where(
                'status',
                $validated['status']
            );
        }

        if (!empty($validated['search'])) {
            $search = trim(
                $validated['search']
            );

            $query->whereHas(
                'staff',
                function ($q) use ($search) {
                    $q->where(
                        'staff_number',
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
                    );
                }
            );
        }

        $records = $query
            ->latest('attendance_date')
            ->latest('id')
            ->paginate(25)
            ->withQueryString();

        $summary = StaffAttendance::query()
            ->where(
                'school_id',
                $schoolId
            )
            ->whereDate(
                'attendance_date',
                '>=',
                $from
            )
            ->whereDate(
                'attendance_date',
                '<=',
                $to
            )
            ->select(
                'status',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('status')
            ->orderByDesc('total')
            ->get();

        return view(
            'admin.reports.staff',
            compact(
                'records',
                'summary',
                'from',
                'to',
                'validated'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Excel Exports
    |--------------------------------------------------------------------------
    */

    /**
     * Students Excel export.
     */
    public function studentsExcel(Request $request)
    {
        $schoolId = $this->schoolId();

        $rows = $this->studentRows(
            $request,
            $schoolId
        );

        return Excel::download(
            new GenericReportExport(
                [
                    'Student Number',
                    'Admission Number',
                    'Student Name',
                    'Gender',
                    'Date of Birth',
                    'Phone',
                    'Admission Date',
                    'Status'
                ],
                $rows
            ),
            'students-report.xlsx'
        );
    }

    /**
     * Enrollment Excel export.
     */
    public function enrollmentExcel(Request $request)
    {
        $schoolId = $this->schoolId();

        $rows = $this->enrollmentRows(
            $request,
            $schoolId
        );

        return Excel::download(
            new GenericReportExport(
                [
                    'Enrollment Number',
                    'Student',
                    'Academic Year',
                    'Term',
                    'Class',
                    'Section',
                    'Enrollment Date',
                    'Status'
                ],
                $rows
            ),
            'enrollment-report.xlsx'
        );
    }

    /**
     * Academic performance Excel export.
     */
    public function academicPerformanceExcel(
        Request $request
    ) {
        $schoolId = $this->schoolId();

        $rows = $this->academicRows(
            $request,
            $schoolId
        );

        return Excel::download(
            new GenericReportExport(
                [
                    'Student',
                    'Examination',
                    'Academic Year',
                    'Term',
                    'Total Score',
                    'Average',
                    'Grade',
                    'Position',
                    'Status'
                ],
                $rows
            ),
            'academic-performance-report.xlsx'
        );
    }

    /**
     * Attendance Excel export.
     */
    public function attendanceExcel(
        Request $request
    ) {
        $schoolId = $this->schoolId();

        $rows = $this->attendanceRows(
            $request,
            $schoolId
        );

        return Excel::download(
            new GenericReportExport(
                [
                    'Date',
                    'Student',
                    'Class',
                    'Section',
                    'Course',
                    'Status',
                    'Remarks'
                ],
                $rows
            ),
            'attendance-report.xlsx'
        );
    }

    /**
     * Staff attendance Excel export.
     */
    public function staffExcel(
        Request $request
    ) {
        $schoolId = $this->schoolId();

        $rows = $this->staffRows(
            $request,
            $schoolId
        );

        return Excel::download(
            new GenericReportExport(
                [
                    'Date',
                    'Staff Number',
                    'Staff',
                    'Status',
                    'Check In',
                    'Check Out',
                    'Remarks'
                ],
                $rows
            ),
            'staff-attendance-report.xlsx'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PDF Exports
    |--------------------------------------------------------------------------
    */

    /**
     * Students PDF export.
     */
    public function studentsPdf(
        Request $request
    ) {
        $data = [
            'title' => 'Student Report',
            'rows' => $this->studentRows(
                $request,
                $this->schoolId()
            ),
        ];

        return Pdf::loadView(
            'admin.reports.pdf.table',
            $data
        )
            ->setPaper(
                'a4',
                'landscape'
            )
            ->download(
                'students-report.pdf'
            );
    }

    /**
     * Enrollment PDF export.
     */
    public function enrollmentPdf(
        Request $request
    ) {
        $data = [
            'title' => 'Enrollment Report',
            'rows' => $this->enrollmentRows(
                $request,
                $this->schoolId()
            ),
        ];

        return Pdf::loadView(
            'admin.reports.pdf.table',
            $data
        )
            ->setPaper(
                'a4',
                'landscape'
            )
            ->download(
                'enrollment-report.pdf'
            );
    }

    /**
     * Academic performance PDF export.
     */
    public function academicPerformancePdf(
        Request $request
    ) {
        $data = [
            'title' => 'Academic Performance Report',
            'rows' => $this->academicRows(
                $request,
                $this->schoolId()
            ),
        ];

        return Pdf::loadView(
            'admin.reports.pdf.table',
            $data
        )
            ->setPaper(
                'a4',
                'landscape'
            )
            ->download(
                'academic-performance-report.pdf'
            );
    }

    /**
     * Attendance PDF export.
     */
    public function attendancePdf(
        Request $request
    ) {
        $data = [
            'title' => 'Attendance Report',
            'rows' => $this->attendanceRows(
                $request,
                $this->schoolId()
            ),
        ];

        return Pdf::loadView(
            'admin.reports.pdf.table',
            $data
        )
            ->setPaper(
                'a4',
                'landscape'
            )
            ->download(
                'attendance-report.pdf'
            );
    }

    /**
     * Staff attendance PDF export.
     */
    public function staffPdf(
        Request $request
    ) {
        $data = [
            'title' => 'Staff Attendance Report',
            'rows' => $this->staffRows(
                $request,
                $this->schoolId()
            ),
        ];

        return Pdf::loadView(
            'admin.reports.pdf.table',
            $data
        )
            ->setPaper(
                'a4',
                'landscape'
            )
            ->download(
                'staff-attendance-report.pdf'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Export Row Builders
    |--------------------------------------------------------------------------
    */

    /**
     * Student export rows.
     */
    protected function studentRows(
        Request $request,
        int $schoolId
    ): array {
        $query = Student::query()
            ->where(
                'school_id',
                $schoolId
            );

        $this->applyStudentFilters(
            $query,
            $request
        );

        return $query
            ->latest('id')
            ->get()
            ->map(
                fn ($s) => [
                    $s->student_number,

                    $s->admission_number,

                    trim(
                        "{$s->first_name} {$s->middle_name} {$s->last_name}"
                    ),

                    ucfirst(
                        $s->gender ?? '-'
                    ),

                    optional(
                        $s->date_of_birth
                    )->format('Y-m-d'),

                    $s->phone,

                    optional(
                        $s->admission_date
                    )->format('Y-m-d'),

                    ucfirst(
                        $s->status
                    ),
                ]
            )
            ->all();
    }

    /**
     * Enrollment export rows.
     *
     * StudentEnrollment DOES use class().
     */
    protected function enrollmentRows(
        Request $request,
        int $schoolId
    ): array {
        $query = StudentEnrollment::query()
            ->where(
                'school_id',
                $schoolId
            )
            ->with([
                'student',
                'academicYear',
                'term',
                'class',
                'section'
            ]);

        $this->applyEnrollmentFilters(
            $query,
            $request
        );

        return $query
            ->latest('enrollment_date')
            ->latest('id')
            ->get()
            ->map(
                fn ($e) => [
                    $e->enrollment_number,

                    $this->studentName(
                        $e->student
                    ),

                    $e->academicYear?->name,

                    $e->term?->name,

                    $e->class?->name,

                    $e->section?->name,

                    optional(
                        $e->enrollment_date
                    )->format('Y-m-d'),

                    ucfirst(
                        $e->status
                    ),
                ]
            )
            ->all();
    }

    /**
     * Academic performance export rows.
     */
    protected function academicRows(
        Request $request,
        int $schoolId
    ): array {
        $query = Result::query()
            ->whereHas(
                'student',
                fn ($q) => $q->where(
                    'school_id',
                    $schoolId
                )
            )
            ->whereHas(
                'examination',
                function ($q) use (
                    $schoolId
                ) {
                    $q->where(
                        'school_id',
                        $schoolId
                    );
                }
            )
            ->with([
                'student',
                'examination.academicYear',
                'examination.term'
            ]);

        $this->applyAcademicFilters(
            $query,
            $request
        );

        return $query
            ->orderByDesc('average')
            ->orderBy('position')
            ->get()
            ->map(
                fn ($r) => [
                    $this->studentName(
                        $r->student
                    ),

                    $r->examination?->name,

                    $r->examination?->academicYear?->name,

                    $r->examination?->term?->name,

                    $r->total_score,

                    $r->average,

                    $r->grade,

                    $r->position,

                    ucfirst(
                        $r->status
                    ),
                ]
            )
            ->all();
    }

    /**
     * Attendance export rows.
     *
     * IMPORTANT:
     * Attendance uses classModel().
     */
    protected function attendanceRows(
        Request $request,
        int $schoolId
    ): array {
        [$from, $to] = $this->dateRange(
            $request
        );

        $query = Attendance::query()
            ->where(
                'school_id',
                $schoolId
            )
            ->whereDate(
                'attendance_date',
                '>=',
                $from
            )
            ->whereDate(
                'attendance_date',
                '<=',
                $to
            )
            ->with([
                'student',
                'classModel',
                'section',
                'course'
            ]);

        $this->applyAttendanceFilters(
            $query,
            $request
        );

        return $query
            ->latest('attendance_date')
            ->latest('id')
            ->get()
            ->map(
                fn ($a) => [
                    optional(
                        $a->attendance_date
                    )->format('Y-m-d'),

                    $this->studentName(
                        $a->student
                    ),

                    $a->classModel?->name,

                    $a->section?->name,

                    $a->course?->name,

                    ucfirst(
                        $a->status
                    ),

                    $a->remarks,
                ]
            )
            ->all();
    }

    /**
     * Staff attendance export rows.
     */
    protected function staffRows(
        Request $request,
        int $schoolId
    ): array {
        [$from, $to] = $this->dateRange(
            $request
        );

        $query = StaffAttendance::query()
            ->where(
                'school_id',
                $schoolId
            )
            ->whereDate(
                'attendance_date',
                '>=',
                $from
            )
            ->whereDate(
                'attendance_date',
                '<=',
                $to
            )
            ->with('staff');

        $this->applyStaffFilters(
            $query,
            $request
        );

        return $query
            ->latest('attendance_date')
            ->latest('id')
            ->get()
            ->map(
                fn ($a) => [
                    optional(
                        $a->attendance_date
                    )->format('Y-m-d'),

                    $a->staff?->staff_number,

                    $this->staffName(
                        $a->staff
                    ),

                    ucfirst(
                        $a->status
                    ),

                    $a->check_in,

                    $a->check_out,

                    $a->remarks,
                ]
            )
            ->all();
    }

    /*
    |--------------------------------------------------------------------------
    | Filters
    |--------------------------------------------------------------------------
    */

    /**
     * Student filters.
     */
    protected function applyStudentFilters(
        $query,
        Request $request
    ): void {
        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }

        if ($request->filled('gender')) {
            $query->where(
                'gender',
                $request->gender
            );
        }

        if ($request->filled('search')) {
            $search = trim(
                $request->search
            );

            $query->where(
                function ($q) use ($search) {
                    $q->where(
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
                    );
                }
            );
        }
    }

    /**
     * Enrollment filters.
     */
    protected function applyEnrollmentFilters(
        $query,
        Request $request
    ): void {
        foreach (
            [
                'academic_year_id',
                'class_id',
                'section_id',
                'status'
            ] as $field
        ) {
            if ($request->filled($field)) {
                $query->where(
                    $field,
                    $request->input($field)
                );
            }
        }
    }

    /**
     * Academic performance filters.
     */
    protected function applyAcademicFilters(
        $query,
        Request $request
    ): void {
        if (
            $request->filled(
                'academic_year_id'
            )
        ) {
            $query->whereHas(
                'examination',
                fn ($q) => $q->where(
                    'academic_year_id',
                    $request->academic_year_id
                )
            );
        }

        if (
            $request->filled(
                'examination_id'
            )
        ) {
            $query->where(
                'examination_id',
                $request->examination_id
            );
        }

        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }
    }

    /**
     * Attendance filters.
     */
    protected function applyAttendanceFilters(
        $query,
        Request $request
    ): void {
        foreach (
            [
                'class_id',
                'section_id',
                'status'
            ] as $field
        ) {
            if ($request->filled($field)) {
                $query->where(
                    $field,
                    $request->input($field)
                );
            }
        }
    }

    /**
     * Staff attendance filters.
     */
    protected function applyStaffFilters(
        $query,
        Request $request
    ): void {
        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }

        if ($request->filled('search')) {
            $search = trim(
                $request->search
            );

            $query->whereHas(
                'staff',
                function ($q) use ($search) {
                    $q->where(
                        'staff_number',
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
                    );
                }
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Get report date range.
     */
    protected function dateRange(
        Request $request
    ): array {
        $from = $request->input(
            'from',
            now()
                ->startOfMonth()
                ->format('Y-m-d')
        );

        $to = $request->input(
            'to',
            now()->format('Y-m-d')
        );

        return [
            $from,
            $to
        ];
    }

    /**
     * Format student name.
     */
    protected function studentName(
        $student
    ): string {
        return $student
            ? trim(
                "{$student->first_name} {$student->middle_name} {$student->last_name}"
            )
            : '-';
    }

    /**
     * Format staff name.
     */
    protected function staffName(
        $staff
    ): string {
        return $staff
            ? trim(
                "{$staff->first_name} {$staff->middle_name} {$staff->last_name}"
            )
            : '-';
    }
}