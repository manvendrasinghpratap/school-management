<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYears;
use App\Models\Classes;
use App\Models\FeeStructure;
use App\Models\Scholarships;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentFee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StudentFeeController extends Controller
{
    /**
     * Display student fee assignments.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user || !$user->school_id) {
            abort(403, 'No school is assigned to the current user.');
        }

        $schoolId = $user->school_id;

        $query = StudentFee::with([
            'student',
            'feeStructure.feeCategory',
            'feeStructure.academicYear',
            'feeStructure.classModel',
            'feeStructure.term',
            'scholarship',
        ])
            ->where('school_id', $schoolId);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->whereHas('student', function ($studentQuery) use ($search, $schoolId) {
                $studentQuery
                    ->where('school_id', $schoolId)
                    ->where(function ($q) use ($search) {
                        $q->where('first_name', 'like', '%' . $search . '%')
                            ->orWhere('middle_name', 'like', '%' . $search . '%')
                            ->orWhere('last_name', 'like', '%' . $search . '%')
                            ->orWhere('student_number', 'like', '%' . $search . '%')
                            ->orWhere('admission_number', 'like', '%' . $search . '%');
                    });
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Fee Structure Filter
        |--------------------------------------------------------------------------
        */
        if ($request->filled('fee_structure_id')) {
            $query->where('fee_structure_id', $request->fee_structure_id);
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        /*
        |--------------------------------------------------------------------------
        | Student Filter
        |--------------------------------------------------------------------------
        */
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        $studentFees = $query
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Filter Data
        |--------------------------------------------------------------------------
        */
        $students = Student::where('school_id', $schoolId)
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        $feeStructures = FeeStructure::with([
            'feeCategory',
            'academicYear',
            'classModel',
            'term',
        ])
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->orderByDesc('id')
            ->get();

        return view('admin.student-fees.index', compact(
            'studentFees',
            'students',
            'feeStructures'
        ));
    }

    /**
     * Show create form.
     */
    public function create()
{
    $user = Auth::user();

    abort_unless(
        $user && $user->school_id,
        403,
        'No school is assigned to the current user.'
    );

    $schoolId = $user->school_id;

    /*
    |--------------------------------------------------------------------------
    | Academic Years
    |--------------------------------------------------------------------------
    */

    $academicYears = AcademicYears::query()
        ->where('school_id', $schoolId)
        ->where('is_active', true)
        ->orderByDesc('start_date')
        ->get();


    /*
    |--------------------------------------------------------------------------
    | Fee Structures
    |--------------------------------------------------------------------------
    */

    $feeStructures = FeeStructure::query()
        ->where('school_id', $schoolId)
        ->where('is_active', true)
        ->with([
            'feeCategory',
            'academicYear',
            'classModel',
            'term',
        ])
        ->orderByDesc('academic_year_id')
        ->get();


    /*
    |--------------------------------------------------------------------------
    | Scholarships
    |--------------------------------------------------------------------------
    */

    $scholarships = Scholarships::query()
        ->where('school_id', $schoolId)
        ->where('is_active', true)
        ->orderBy('name')
        ->get();


    return view(
        'admin.student-fees.create',
        compact(
            'academicYears',
            'feeStructures',
            'scholarships'
        )
    );
}

    public function filterClasses(Request $request)
    {
    $user = Auth::user();

    abort_unless(
        $user->school_id,
        403,
        'No school is assigned to the current user.'
    );

    $schoolId = $user->school_id;

    $validated = $request->validate([
        'academic_year_id' => [
            'required',
            'integer',
            'exists:academic_years,id',
        ],
    ]);

    $academicYear = AcademicYears::query()
        ->where('school_id', $schoolId)
        ->where('is_active', true)
        ->findOrFail($validated['academic_year_id']);

    $classes = Classes::query()
        ->where('classes.school_id', $schoolId)
        ->where('classes.is_active', true)
        ->join(
            'student_enrollments',
            'student_enrollments.class_id',
            '=',
            'classes.id'
        )
        ->where(
            'student_enrollments.school_id',
            $schoolId
        )
        ->where(
            'student_enrollments.academic_year_id',
            $academicYear->id
        )
        ->where(
            'student_enrollments.status',
            'active'
        )
        ->select([
            'classes.id',
            'classes.name',
            'classes.code',
        ])
        ->distinct()
        ->orderBy('classes.name')
        ->get();

    return response()->json([
        'classes' => $classes,
    ]);
    }

    public function filterSections(Request $request)
    {
    $user = Auth::user();

    abort_unless(
        $user->school_id,
        403,
        'No school is assigned to the current user.'
    );

    $schoolId = $user->school_id;

    $validated = $request->validate([
        'class_id' => [
            'required',
            'integer',
            'exists:classes,id',
        ],
    ]);

    /*
    |--------------------------------------------------------------------------
    | Verify Class Belongs To Current School
    |--------------------------------------------------------------------------
    */

    $class = Classes::query()
        ->where('school_id', $schoolId)
        ->where('is_active', true)
        ->findOrFail($validated['class_id']);


    /*
    |--------------------------------------------------------------------------
    | Sections Do NOT Have school_id
    |--------------------------------------------------------------------------
    |
    | sections.class_id is the correct relationship.
    |
    */

    $sections = Section::query()
        ->where('class_id', $class->id)
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
    
    public function filterStudents(Request $request)
    {
    $user = Auth::user();

    abort_unless(
        $user->school_id,
        403,
        'No school is assigned to the current user.'
    );

    $schoolId = $user->school_id;

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
            'required',
            'integer',
            'exists:sections,id',
        ],
    ]);


    /*
    |--------------------------------------------------------------------------
    | Verify Academic Year
    |--------------------------------------------------------------------------
    */

    $academicYear = AcademicYears::query()
        ->where('school_id', $schoolId)
        ->where('is_active', true)
        ->findOrFail(
            $validated['academic_year_id']
        );


    /*
    |--------------------------------------------------------------------------
    | Verify Class
    |--------------------------------------------------------------------------
    */

    $class = Classes::query()
        ->where('school_id', $schoolId)
        ->where('is_active', true)
        ->findOrFail(
            $validated['class_id']
        );


    /*
    |--------------------------------------------------------------------------
    | Verify Section Belongs To Class
    |--------------------------------------------------------------------------
    */

    $section = Section::query()
        ->where('id', $validated['section_id'])
        ->where('class_id', $class->id)
        ->where('is_active', true)
        ->firstOrFail();


    /*
    |--------------------------------------------------------------------------
    | Get Students
    |--------------------------------------------------------------------------
    |
    | Student placement comes from student_enrollments.
    |
    | Academic Year → Class → Section → Student
    |
    */

    $students = Student::query()
        ->where(
            'students.school_id',
            $schoolId
        )
        ->where(
            'students.status',
            'active'
        )
        ->join(
            'student_enrollments',
            'student_enrollments.student_id',
            '=',
            'students.id'
        )
        ->where(
            'student_enrollments.school_id',
            $schoolId
        )
        ->where(
            'student_enrollments.academic_year_id',
            $academicYear->id
        )
        ->where(
            'student_enrollments.class_id',
            $class->id
        )
        ->where(
            'student_enrollments.section_id',
            $section->id
        )
        ->where(
            'student_enrollments.status',
            'active'
        )
        ->select([
            'students.id',
            'students.student_number',
            'students.admission_number',
            'students.first_name',
            'students.middle_name',
            'students.last_name',
        ])
        ->orderBy('students.first_name')
        ->orderBy('students.last_name')
        ->get();


    return response()->json([
        'students' => $students,
    ]);
    }

    public function filterFeeStructures(Request $request)
    {
    $user = Auth::user();

    abort_unless(
        $user->school_id,
        403,
        'No school is assigned to the current user.'
    );

    $schoolId = $user->school_id;

    $validated = $request->validate([
        'class_id' => [
            'required',
            'integer',
            'exists:classes,id',
        ],

        'academic_year_id' => [
            'required',
            'integer',
            'exists:academic_years,id',
        ],

        'section_id' => [
            'nullable',
            'integer',
            'exists:sections,id',
        ],
    ]);

    $query = FeeStructure::query()
        ->where('fee_structures.school_id', $schoolId)
        ->where('fee_structures.is_active', true)

        ->with([
            'feeCategory',
            'academicYear',
            'classModel',
            'term',
        ])

        ->orderByDesc('fee_structures.academic_year_id');

    /*
    |--------------------------------------------------------------------------
    | Base Class + Academic Year
    |--------------------------------------------------------------------------
    |
    | Every fee structure belongs to exactly one class and one academic year.
    |
    */

    $query->where('fee_structures.class_id', $validated['class_id'])
        ->where('fee_structures.academic_year_id', $validated['academic_year_id']);

    /*
    |--------------------------------------------------------------------------
    | Section Filter (If Provided)
    |--------------------------------------------------------------------------
    */

    if (!empty($validated['section_id'])) {
        $query->where('fee_structures.section_id', $validated['section_id']);
    } else {
        /*
        |----------------------------------------------------------------------
        | NULL section_id = Fee applies to the entire class
        |----------------------------------------------------------------------
        */
        $query->whereNull('fee_structures.section_id');
    }

    $feeStructures = $query->get();

    return response()->json([
        'fee_structures' => $feeStructures,
    ]);
}

    /**
     * Calculate the actual monetary discount from a scholarship.
     *
     * Scholarship value is the rule/value; StudentFee.discount stores the
     * actual monetary discount applied to this assignment.
     */
    private function calculateScholarshipDiscount(?Scholarships $scholarship, float $amount): float
    {
        if (!$scholarship || $amount <= 0) {
            return 0.00;
        }

        $value = round((float) $scholarship->value, 2);

        if ($scholarship->type === 'percentage') {
            $value = min(max($value, 0), 100);

            return round($amount * ($value / 100), 2);
        }

        if ($scholarship->type === 'fixed') {
            return min(round(max($value, 0), 2), $amount);
        }

        return 0.00;
    }

    /**
     * Store a student fee assignment.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user || !$user->school_id) {
            abort(403, 'No school is assigned to the current user.');
        }

        $schoolId = $user->school_id;

        $validated = $request->validate([
            'student_id' => [
                'required',
                'integer',
                Rule::exists('students', 'id')
                    ->where(fn ($query) => $query->where('school_id', $schoolId)),
            ],

            'fee_structure_id' => [
                'required',
                'integer',
                Rule::exists('fee_structures', 'id')
                    ->where(fn ($query) => $query
                        ->where('school_id', $schoolId)
                        ->where('is_active', true)),
            ],

            'scholarship_id' => [
                'nullable',
                'integer',
                Rule::exists('scholarships', 'id')
                    ->where(fn ($query) => $query
                        ->where('school_id', $schoolId)
                        ->where('is_active', true)),
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'discount' => [
                'nullable',
                'numeric',
                'min:0',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Student Verification
        |--------------------------------------------------------------------------
        */
        $student = Student::where('school_id', $schoolId)
            ->where('id', $validated['student_id'])
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Fee Structure Verification
        |--------------------------------------------------------------------------
        */
        $feeStructure = FeeStructure::where('school_id', $schoolId)
            ->where('id', $validated['fee_structure_id'])
            ->where('is_active', true)
            ->firstOrFail();

        $scholarship = null;

        if (!empty($validated['scholarship_id'])) {
            $scholarship = Scholarships::query()
                ->where('school_id', $schoolId)
                ->where('id', $validated['scholarship_id'])
                ->where('is_active', true)
                ->firstOrFail();
        }

        /*
        |--------------------------------------------------------------------------
        | Amount Validation
        |--------------------------------------------------------------------------
        */
        $amount = round((float) $validated['amount'], 2);

        // With a scholarship, the server calculates the discount from the
        // selected scholarship rule. Without one, preserve the existing
        // manual discount functionality.
        $discount = $scholarship
            ? $this->calculateScholarshipDiscount($scholarship, $amount)
            : round((float) ($validated['discount'] ?? 0), 2);

        if ($amount <= 0) {
            return back()
                ->withInput()
                ->withErrors([
                    'amount' => 'The fee amount must be greater than zero.',
                ]);
        }

        if ($discount < 0) {
            return back()
                ->withInput()
                ->withErrors([
                    'discount' => 'The discount cannot be negative.',
                ]);
        }

        if ($discount > $amount) {
            return back()
                ->withInput()
                ->withErrors([
                    'discount' => 'The discount cannot exceed the assigned fee amount.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Prevent Assignment Above Fee Structure
        |--------------------------------------------------------------------------
        |
        | The normal assigned amount cannot exceed the parent fee structure.
        |
        */
        if ($amount > (float) $feeStructure->amount) {
            return back()
                ->withInput()
                ->withErrors([
                    'amount' => sprintf(
                        'The assigned amount cannot exceed the fee structure amount of %0.2f.',
                        (float) $feeStructure->amount
                    ),
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Active Assignment
        |--------------------------------------------------------------------------
        */
        $duplicate = StudentFee::where('school_id', $schoolId)
            ->where('student_id', $student->id)
            ->where('fee_structure_id', $feeStructure->id)
            ->whereNull('deleted_at')
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($duplicate) {
            return back()
                ->withInput()
                ->withErrors([
                    'fee_structure_id' =>
                        'This fee structure is already assigned to the selected student.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Create Student Fee
        |--------------------------------------------------------------------------
        */
        StudentFee::create([
            'school_id' => $schoolId,
            'student_id' => $student->id,
            'fee_structure_id' => $feeStructure->id,
            'scholarship_id' => $validated['scholarship_id'] ?? null,
            'amount' => $amount,
            'discount' => $discount,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('admin.student-fees.index')
            ->with('success', 'Student fee assigned successfully.');
    }

    /**
     * Display a student fee assignment.
     */
    public function show(StudentFee $studentFee)
    {
        $user = Auth::user();

        if (!$user || !$user->school_id) {
            abort(403, 'No school is assigned to the current user.');
        }

        if ($studentFee->school_id !== $user->school_id) {
            abort(403, 'You are not authorized to access this student fee.');
        }

        $studentFee->load([
            'student',
            'feeStructure.feeCategory',
            'feeStructure.academicYear',
            'feeStructure.classModel',
            'feeStructure.term',
            'scholarship',
        ]);

        return view('admin.student-fees.show', compact('studentFee'));
    }

    /**
     * Show edit form.
     */
    public function edit(StudentFee $studentFee)
    {
    $user = Auth::user();

    abort_unless(
        $user && $user->school_id,
        403,
        'No school is assigned to the current user.'
    );

    $schoolId = $user->school_id;


    /*
    |--------------------------------------------------------------------------
    | School Isolation
    |--------------------------------------------------------------------------
    */

    if ((int) $studentFee->school_id !== (int) $schoolId) {

        abort(
            403,
            'You are not authorized to edit this student fee.'
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Financial Protection
    |--------------------------------------------------------------------------
    |
    | Paid and partially paid records must not be edited.
    |
    */

    if (
        in_array(
            $studentFee->status,
            ['paid', 'partial'],
            true
        )
    ) {

        return redirect()
            ->route(
                'admin.student-fees.show',
                $studentFee
            )
            ->with(
                'error',
                'Paid or partially paid student fees cannot be edited.'
            );

    }


    /*
    |--------------------------------------------------------------------------
    | Load Current Relationships
    |--------------------------------------------------------------------------
    */

    $studentFee->load([
        'student',
        'feeStructure.feeCategory',
        'feeStructure.academicYear',
        'feeStructure.classModel',
        'feeStructure.term',
        'scholarship',
    ]);


    /*
    |--------------------------------------------------------------------------
    | Students
    |--------------------------------------------------------------------------
    |
    | Only active students belonging to the current school.
    |
    */

    $students = Student::query()
        ->where('school_id', $schoolId)
        ->where('status', 'active')
        ->orderBy('first_name')
        ->orderBy('last_name')
        ->get();


    /*
    |--------------------------------------------------------------------------
    | Fee Structures
    |--------------------------------------------------------------------------
    */

    $feeStructures = FeeStructure::query()
        ->where('school_id', $schoolId)
        ->where('is_active', true)
        ->with([
            'feeCategory',
            'academicYear',
            'classModel',
            'term',
        ])
        ->orderByDesc('academic_year_id')
        ->orderByDesc('id')
        ->get();


    /*
    |--------------------------------------------------------------------------
    | Scholarships
    |--------------------------------------------------------------------------
    */

    $scholarships = Scholarships::query()
        ->where('school_id', $schoolId)
        ->where('is_active', true)
        ->orderBy('name')
        ->get();


    /*
    |--------------------------------------------------------------------------
    | Return Edit View
    |--------------------------------------------------------------------------
    */

    return view(
        'admin.student-fees.edit',
        compact(
            'studentFee',
            'students',
            'feeStructures',
            'scholarships'
        )
    );
    }

    /**
     * Update a student fee assignment.
     */
    public function update(Request $request, StudentFee $studentFee)
    {
        $user = Auth::user();

        if (!$user || !$user->school_id) {
            abort(403, 'No school is assigned to the current user.');
        }

        if ($studentFee->school_id !== $user->school_id) {
            abort(403, 'You are not authorized to update this student fee.');
        }

        /*
        |--------------------------------------------------------------------------
        | Financial Protection
        |--------------------------------------------------------------------------
        */
        if (in_array($studentFee->status, ['paid', 'partial'], true)) {
            return back()
                ->with('error', 'Paid or partially paid student fees cannot be edited.');
        }

        $schoolId = $user->school_id;

        $validated = $request->validate([
            'student_id' => [
                'required',
                'integer',
                Rule::exists('students', 'id')
                    ->where(fn ($query) => $query->where('school_id', $schoolId)),
            ],

            'fee_structure_id' => [
                'required',
                'integer',
                Rule::exists('fee_structures', 'id')
                    ->where(fn ($query) => $query
                        ->where('school_id', $schoolId)
                        ->where('is_active', true)),
            ],

            'scholarship_id' => [
                'nullable',
                'integer',
                Rule::exists('scholarships', 'id')
                    ->where(fn ($query) => $query
                        ->where('school_id', $schoolId)
                        ->where('is_active', true)),
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'discount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'status' => [
                'required',
                Rule::in([
                    'pending',
                    'cancelled',
                ]),
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Verify Related Records
        |--------------------------------------------------------------------------
        */
        $student = Student::where('school_id', $schoolId)
            ->where('id', $validated['student_id'])
            ->firstOrFail();

        $feeStructure = FeeStructure::where('school_id', $schoolId)
            ->where('id', $validated['fee_structure_id'])
            ->where('is_active', true)
            ->firstOrFail();

        $scholarship = null;

        if (!empty($validated['scholarship_id'])) {
            $scholarshipQuery = Scholarships::query()
                ->where('school_id', $schoolId)
                ->where('id', $validated['scholarship_id']);

            // Allow an existing inactive scholarship to remain attached to an
            // un-paid historical assignment, but do not allow a new inactive
            // scholarship to be selected.
            if ((int) $validated['scholarship_id'] !== (int) $studentFee->scholarship_id) {
                $scholarshipQuery->where('is_active', true);
            }

            $scholarship = $scholarshipQuery->firstOrFail();
        }

        /*
        |--------------------------------------------------------------------------
        | Amount / Discount
        |--------------------------------------------------------------------------
        */
        $amount = round((float) $validated['amount'], 2);

        $discount = $scholarship
            ? $this->calculateScholarshipDiscount($scholarship, $amount)
            : round((float) ($validated['discount'] ?? 0), 2);

        if ($discount > $amount) {
            return back()
                ->withInput()
                ->withErrors([
                    'discount' => 'The discount cannot exceed the assigned fee amount.',
                ]);
        }

        if ($amount > (float) $feeStructure->amount) {
            return back()
                ->withInput()
                ->withErrors([
                    'amount' => sprintf(
                        'The assigned amount cannot exceed the fee structure amount of %0.2f.',
                        (float) $feeStructure->amount
                    ),
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Assignment
        |--------------------------------------------------------------------------
        */
        $duplicate = StudentFee::where('school_id', $schoolId)
            ->where('student_id', $student->id)
            ->where('fee_structure_id', $feeStructure->id)
            ->where('id', '!=', $studentFee->id)
            ->whereNull('deleted_at')
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($duplicate) {
            return back()
                ->withInput()
                ->withErrors([
                    'fee_structure_id' =>
                        'This fee structure is already assigned to the selected student.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */
        $studentFee->update([
            'student_id' => $student->id,
            'fee_structure_id' => $feeStructure->id,
            'scholarship_id' => $validated['scholarship_id'] ?? null,
            'amount' => $amount,
            'discount' => $discount,
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('admin.student-fees.show', $studentFee)
            ->with('success', 'Student fee updated successfully.');
    }

    /**
     * Cancel a student fee assignment.
     */
    public function cancel(StudentFee $studentFee)
    {
        $user = Auth::user();

        if (!$user || !$user->school_id) {
            abort(403, 'No school is assigned to the current user.');
        }

        if ($studentFee->school_id !== $user->school_id) {
            abort(403, 'You are not authorized to cancel this student fee.');
        }

        if (in_array($studentFee->status, ['paid', 'partial'], true)) {
            return back()
                ->with('error', 'Paid or partially paid student fees cannot be cancelled.');
        }

        $studentFee->update([
            'status' => 'cancelled',
        ]);

        return back()
            ->with('success', 'Student fee cancelled successfully.');
    }

    /**
     * Soft delete a student fee assignment.
     */
    public function destroy(StudentFee $studentFee)
    {
        $user = Auth::user();

        if (!$user || !$user->school_id) {
            abort(403, 'No school is assigned to the current user.');
        }

        if ($studentFee->school_id !== $user->school_id) {
            abort(403, 'You are not authorized to delete this student fee.');
        }

        if (in_array($studentFee->status, ['paid', 'partial'], true)) {
            return back()
                ->with('error', 'Paid or partially paid student fees cannot be deleted.');
        }

        $studentFee->delete();

        return redirect()
            ->route('admin.student-fees.index')
            ->with('success', 'Student fee deleted successfully.');
    }
}