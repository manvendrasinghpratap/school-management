<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreStudentPromotionRequest;
use App\Http\Requests\Admin\UpdateStudentPromotionRequest;
use App\Models\AcademicYears;
use App\Models\Classes;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentEnrollment;
use App\Models\StudentPromotion;
use App\Services\StudentPromotionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class StudentPromotionController extends Controller
{
    protected StudentPromotionService $promotionService;

    public function __construct(
        StudentPromotionService $promotionService
    ) {
        $this->promotionService = $promotionService;
    }

    /**
     * Display promotion records.
     */
    public function index(Request $request): View
    {
        $schoolId = $this->schoolId();

        $query = StudentPromotion::query()
            ->where('school_id', $schoolId)
            ->with([
                'student',
                'fromClass',
                'toClass',
                'fromSection',
                'toSection',
                'fromAcademicYear',
                'toAcademicYear',
                'approvedBy',
                'rejectedBy',
            ]);

        if ($request->filled('search')) {
            $search = trim($request->input('search'));

            $query->whereHas('student', function ($studentQuery) use ($search) {
                $studentQuery->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('middle_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('student_number', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('academic_year_id')) {
            $query->where(
                'from_academic_year_id',
                $request->input('academic_year_id')
            );
        }

        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->input('status')
            );
        }

        $promotions = $query
            ->latest('promotion_date')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        $academicYears = AcademicYears::query()
            ->where('school_id', $schoolId)
            ->orderByDesc('start_date')
            ->get();

        return view(
            'admin.student-promotions.index',
            compact(
                'promotions',
                'academicYears'
            )
        );
    }

    /**
     * Show promotion creation form.
     *
     * Students are NOT loaded here.
     * They are loaded dynamically after:
     * Academic Year → Class → Section.
     */
    public function create(Request $request): View
    {
        $schoolId = $this->schoolId();

        /*
         * Current academic year.
         */
        $currentAcademicYear = AcademicYears::query()
            ->where('school_id', $schoolId)
            ->where('is_current', 1)
            ->where('is_active', 1)
            ->first();

        /*
         * All active academic years for the selector.
         */
        $academicYears = AcademicYears::query()
            ->where('school_id', $schoolId)
            ->where('is_active', 1)
            ->orderByDesc('start_date')
            ->get();

        $nextAcademicYear = null;

        if ($currentAcademicYear) {
            $nextAcademicYear = AcademicYears::query()
                ->where('school_id', $schoolId)
                ->where('is_active', 1)
                ->whereDate('start_date', '>', $currentAcademicYear->start_date)
                ->orderBy('start_date')
                ->first();
        }

        /*
         * Active school classes.
         */
        $classes = Classes::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->with('level')
            ->orderBy('name')
            ->get();

        /*
         * Sections belong to classes, not schools.
         */
        $sections = Section::query()
            ->whereIn('class_id', $classes->pluck('id'))
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        /*
         * Optional student ID from Student Profile.
         *
         * We do not automatically load all students.
         * The Blade JavaScript will use this ID after
         * the appropriate class/section is selected.
         */
        $selectedStudentId = $request->integer('student_id');

        return view(
            'admin.student-promotions.create',
            compact(
                'academicYears',
                'currentAcademicYear',
                'nextAcademicYear',
                'classes',
                'sections',
                'selectedStudentId'
            )
        );
    }

    /**
     * Return students filtered by current academic year,
     * current class and optional current section.
     */
    public function filterStudents(Request $request): JsonResponse
    {
        $schoolId = $this->schoolId();

        $validated = $request->validate([
            'academic_year_id' => [
                'required',
                'integer',
                'exists:academic_years,id',
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
        ]);

        /*
         * Verify academic year belongs to this school.
         */
        $academicYear = AcademicYears::query()
            ->where('id', $validated['academic_year_id'])
            ->where('school_id', $schoolId)
            ->where('is_active', 1)
            ->firstOrFail();

        /*
         * Verify class belongs to this school.
         */
        $class = Classes::query()
            ->where('id', $validated['class_id'])
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->firstOrFail();

        /*
         * Verify section belongs to selected class.
         */
        if (!empty($validated['section_id'])) {
            Section::query()
                ->where('id', $validated['section_id'])
                ->where('class_id', $class->id)
                ->where('is_active', true)
                ->firstOrFail();
        }

        /*
         * ONLY active students enrolled in the selected
         * academic year and selected class/section.
         */
        $students = StudentEnrollment::query()
            ->where('student_enrollments.school_id', $schoolId)
            ->where('student_enrollments.academic_year_id', $academicYear->id)
            ->where('student_enrollments.class_id', $class->id)
            ->where('student_enrollments.status', 'active')
            ->when(
                !empty($validated['section_id']),
                fn ($query) => $query->where(
                    'student_enrollments.section_id',
                    $validated['section_id']
                )
            )
            ->join('students', 'students.id', '=', 'student_enrollments.student_id')
            ->join('classes', 'classes.id', '=', 'student_enrollments.class_id')
            ->leftJoin('sections', 'sections.id', '=', 'student_enrollments.section_id')
            ->where('students.school_id', $schoolId)
            ->where('students.status', 'active')
            ->select([
                'students.id',
                'students.student_number',
                'students.admission_number',
                'students.first_name',
                'students.middle_name',
                'students.last_name',
                'student_enrollments.academic_year_id',
                'student_enrollments.class_id',
                'student_enrollments.section_id',
                'classes.name as class_name',
                'sections.name as section_name',
            ])
            ->orderBy('students.first_name')
            ->orderBy('students.last_name')
            ->get();

        return response()->json([
            'students' => $students,
        ]);
    }

    /**
     * Return sections belonging to a selected class.
     */
    public function filterSections(Request $request): JsonResponse
    {
        $schoolId = $this->schoolId();

        $validated = $request->validate([
            'class_id' => [
                'required',
                'integer',
                'exists:classes,id',
            ],
        ]);

        /*
         * Verify class belongs to current school.
         */
        Classes::query()
            ->where('id', $validated['class_id'])
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->firstOrFail();

        $sections = Section::query()
            ->where(
                'class_id',
                $validated['class_id']
            )
            ->where('is_active', true)
            ->orderBy('name')
            ->get([
                'id',
                'class_id',
                'name',
                'code',
            ]);

        return response()->json([
            'sections' => $sections,
        ]);
    }

    /**
     * Store a promotion request.
     */
    public function store(
        StoreStudentPromotionRequest $request
    ): RedirectResponse {
        try {
            $data = $request->validated();

            /*
             * The service will derive the student's actual
             * source enrollment and will not trust browser
             * supplied source values.
             */
            $promotion = $this->promotionService->create(
                auth()->user(),
                $data
            );

            return redirect()
                ->route(
                    'admin.student-promotions.show',
                    $promotion
                )
                ->with(
                    'success',
                    'Student promotion request created successfully and is awaiting approval.'
                );

        } catch (ValidationException $e) {
            throw $e;

        } catch (\Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to create the student promotion request.'
                );
        }
    }

    /**
     * Display a promotion.
     */
    public function show(
        StudentPromotion $studentPromotion
    ): View {
        $this->ensureSameSchool($studentPromotion);

        $studentPromotion->load([
            'student',
            'fromClass',
            'toClass',
            'fromSection',
            'toSection',
            'fromAcademicYear',
            'toAcademicYear',
            'approvedBy',
        ]);

        $promotion = $studentPromotion;

        return view(
            'admin.student-promotions.show',
            compact('promotion')
        );
    }

    /**
     * Show edit form.
     */
    public function edit(
        StudentPromotion $studentPromotion
    ): View|RedirectResponse {
        $this->ensureSameSchool($studentPromotion);

        /*
         * Approved promotions are historical records.
         */
        if ($studentPromotion->status === 'approved') {
            return redirect()
                ->route(
                    'admin.student-promotions.show',
                    $studentPromotion
                )
                ->with(
                    'error',
                    'An approved promotion cannot be edited.'
                );
        }

        $studentPromotion->load([
            'student',
            'fromClass',
            'toClass',
            'fromSection',
            'toSection',
            'fromAcademicYear',
            'toAcademicYear',
        ]);

        $promotion = $studentPromotion;

        $schoolId = $this->schoolId();

        $academicYears = AcademicYears::query()
            ->where('school_id', $schoolId)
            ->where('is_active', 1)
            ->orderByDesc('start_date')
            ->get();

        $classes = Classes::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->with('level')
            ->orderBy('name')
            ->get();

        $sections = Section::query()
            ->whereIn(
                'class_id',
                $classes->pluck('id')
            )
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view(
            'admin.student-promotions.edit',
            compact(
                'promotion',
                'academicYears',
                'classes',
                'sections'
            )
        );
    }

    /**
     * Update a pending/rejected promotion.
     */
    public function update(
        UpdateStudentPromotionRequest $request,
        StudentPromotion $studentPromotion
    ): RedirectResponse {
        $this->ensureSameSchool($studentPromotion);

        if ($studentPromotion->status === 'approved') {
            return back()->with(
                'error',
                'Approved promotions cannot be edited.'
            );
        }

        try {
            $data = $request->validated();

            $schoolId = $this->schoolId();

            /*
             * Destination class must belong to this school.
             */
            $toClass = Classes::query()
                ->where('school_id', $schoolId)
                ->where('is_active', true)
                ->with('level')
                ->find($data['to_class_id']);

            if (!$toClass) {
                throw ValidationException::withMessages([
                    'to_class_id' => [
                        'The selected destination class is invalid.'
                    ],
                ]);
            }

            /*
             * Destination section is REQUIRED.
             */
            if (empty($data['to_section_id'])) {
                throw ValidationException::withMessages([
                    'to_section_id' => [
                        'Destination section is required.'
                    ],
                ]);
            }

            /*
             * Destination section must belong to
             * destination class.
             */
            $validSection = Section::query()
                ->where('id', $data['to_section_id'])
                ->where('class_id', $toClass->id)
                ->where('is_active', true)
                ->exists();

            if (!$validSection) {
                throw ValidationException::withMessages([
                    'to_section_id' => [
                        'The selected destination section does not belong to the selected destination class.'
                    ],
                ]);
            }

            /*
             * Load original source class.
             */
            $fromClass = Classes::query()
                ->where('school_id', $schoolId)
                ->with('level')
                ->find($studentPromotion->from_class_id);

            if (!$fromClass) {
                throw ValidationException::withMessages([
                    'to_class_id' => [
                        'The current source class is invalid.'
                    ],
                ]);
            }

            /*
             * The destination must be different.
             */
            if ($fromClass->id === $toClass->id) {
                throw ValidationException::withMessages([
                    'to_class_id' => [
                        'The destination class must be different from the current class.'
                    ],
                ]);
            }

            /*
             * Use the same progression rule as the service.
             */
            $this->promotionService->validateClassProgression(
                $fromClass,
                $toClass,
                $schoolId
            );

            $studentPromotion->update([
                'to_class_id' => $toClass->id,
                'to_section_id' => $data['to_section_id'],
                'promotion_date' => $data['promotion_date'],
                'remarks' => $data['remarks'] ?? null,
            ]);

            return redirect()
                ->route(
                    'admin.student-promotions.show',
                    $studentPromotion
                )
                ->with(
                    'success',
                    'Student promotion updated successfully.'
                );

        } catch (ValidationException $e) {
            throw $e;

        } catch (\Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to update the student promotion.'
                );
        }
    }

    /**
     * Approve a pending promotion.
     */
    public function approve(
        StudentPromotion $studentPromotion
    ): RedirectResponse {
        $this->ensureSameSchool($studentPromotion);

        try {
            $this->promotionService->approve(
                auth()->user(),
                $studentPromotion
            );

            return redirect()
                ->route(
                    'admin.student-promotions.show',
                    $studentPromotion
                )
                ->with(
                    'success',
                    'Student promotion approved successfully and the new enrollment has been created.'
                );

        } catch (ValidationException $e) {
            return back()->withErrors(
                $e->errors()
            );

        } catch (\Throwable $e) {
            report($e);

            return back()->with(
                'error',
                'Unable to approve the student promotion.'
            );
        }
    }

    /**
     * Reject a promotion.
     */
    public function reject(
        Request $request,
        StudentPromotion $studentPromotion
    ): RedirectResponse {
        $this->ensureSameSchool($studentPromotion);

        $request->validate([
            'remarks' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        try {
            $this->promotionService->reject(
                auth()->user(),
                $studentPromotion,
                $request->input('remarks')
            );

            return redirect()
                ->route(
                    'admin.student-promotions.show',
                    $studentPromotion
                )
                ->with(
                    'success',
                    'Student promotion rejected successfully.'
                );

        } catch (ValidationException $e) {
            return back()->withErrors(
                $e->errors()
            );

        } catch (\Throwable $e) {
            report($e);

            return back()->with(
                'error',
                'Unable to reject the student promotion.'
            );
        }
    }

    /**
     * Delete a promotion.
     */
    public function destroy(
        StudentPromotion $studentPromotion
    ): RedirectResponse {
        $this->ensureSameSchool($studentPromotion);

        try {
            $this->promotionService->delete(
                auth()->user(),
                $studentPromotion
            );

            return redirect()
                ->route(
                    'admin.student-promotions.index'
                )
                ->with(
                    'success',
                    'Student promotion deleted successfully.'
                );

        } catch (ValidationException $e) {
            return back()->withErrors(
                $e->errors()
            );

        } catch (\Throwable $e) {
            report($e);

            return back()->with(
                'error',
                'Unable to delete the student promotion.'
            );
        }
    }

    /**
     * Get current user's school ID.
     */
    protected function schoolId(): int
    {
        $schoolId = auth()->user()?->school_id;

        if (!$schoolId) {
            abort(
                403,
                'No school is assigned to the current user.'
            );
        }

        return (int) $schoolId;
    }

    /**
     * Ensure promotion belongs to current user's school.
     */
    protected function ensureSameSchool(
        StudentPromotion $studentPromotion
    ): void {
        if (
            (int) $studentPromotion->school_id !==
            $this->schoolId()
        ) {
            abort(
                403,
                'You cannot access a promotion belonging to another school.'
            );
        }
    }
}