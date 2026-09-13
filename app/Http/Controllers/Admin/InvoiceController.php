<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYears;
use App\Models\Classes;
use App\Models\FeeCategory;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentFee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    /**
     * Display invoice listing.
     */
    public function index(Request $request)
    {
        $schoolId = Auth::user()->school_id;

        $query = Invoice::query()
            ->where('school_id', $schoolId)
            ->with([
                'student',
                'items.feeCategory',
            ]);

        if ($request->filled('invoice_number')) {
            $query->where(
                'invoice_number',
                'like',
                '%' . $request->invoice_number . '%'
            );
        }

        if ($request->filled('student_id')) {
            $query->where(
                'student_id',
                $request->student_id
            );
        }

        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }

        if ($request->filled('invoice_date_from')) {
            $query->whereDate(
                'invoice_date',
                '>=',
                $request->invoice_date_from
            );
        }

        if ($request->filled('invoice_date_to')) {
            $query->whereDate(
                'invoice_date',
                '<=',
                $request->invoice_date_to
            );
        }

        $invoices = $query
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        $students = Student::query()
            ->where('school_id', $schoolId)
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get([
                'id',
                'student_number',
                'first_name',
                'middle_name',
                'last_name',
            ]);

        return view(
            'admin.invoices.index',
            compact(
                'invoices',
                'students'
            )
        );
    }


    /**
     * Show invoice creation form.
     *
     * Current active academic year is selected automatically
     * in the Blade file.
     */
    public function create()
    {
        $schoolId = Auth::user()->school_id;

        $academicYears = AcademicYears::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->orderByDesc('start_date')
            ->get();

        $feeCategories = FeeCategory::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view(
            'admin.invoices.create',
            compact(
                'academicYears',
                'feeCategories'
            )
        );
    }


    /**
     * Return ALL active classes for the current school.
     *
     * IMPORTANT:
     * Academic Year does NOT filter the Class dropdown.
     *
     * The Academic Year is used later when validating
     * the student's enrollment.
     */
    public function filterClasses(Request $request)
    {
        $schoolId = Auth::user()->school_id;

        $request->validate([
            'academic_year_id' => [
                'required',
                'integer',
            ],
        ]);

        /*
         * First verify that the selected academic year
         * belongs to the current school.
         */
        $academicYear = AcademicYears::query()
            ->where(
                'id',
                $request->academic_year_id
            )
            ->where(
                'school_id',
                $schoolId
            )
            ->where(
                'is_active',
                true
            )
            ->first();

        if (! $academicYear) {
            return response()->json([
                'message' => 'Academic year not found.',
            ], 404);
        }

        /*
         * IMPORTANT:
         *
         * Do NOT filter classes by academic year.
         *
         * Classes belong to the school.
         * Student enrollment determines which class
         * a student belongs to in a particular academic year.
         */
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
            ->get([
                'id',
                'name',
                'code',
            ]);

        return response()->json($classes);
    }


    /**
     * Return sections belonging to the selected class.
     */
    public function filterSections(Request $request)
    {
        $schoolId = Auth::user()->school_id;

        $request->validate([
            'class_id' => [
                'required',
                'integer',
            ],
        ]);

        $class = Classes::query()
            ->where(
                'id',
                $request->class_id
            )
            ->where(
                'school_id',
                $schoolId
            )
            ->where(
                'is_active',
                true
            )
            ->first();

        if (! $class) {
            return response()->json([
                'message' => 'Class not found.',
            ], 404);
        }

        $sections = Section::query()
            ->where(
                'class_id',
                $class->id
            )
            ->where(
                'is_active',
                true
            )
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'code',
            ]);

        return response()->json($sections);
    }


    /**
     * Return students for:
     *
     * Academic Year
     *      ↓
     * Class
     *      ↓
     * Section
     *      ↓
     * Student
     */
    public function filterStudents(Request $request)
    {
        $schoolId = Auth::user()->school_id;

        $validated = $request->validate([
            'academic_year_id' => [
                'required',
                'integer',
            ],

            'class_id' => [
                'required',
                'integer',
            ],

            'section_id' => [
                'required',
                'integer',
            ],
        ]);

        /*
         * Verify academic year.
         */
        $academicYear = AcademicYears::query()
            ->where(
                'id',
                $validated['academic_year_id']
            )
            ->where(
                'school_id',
                $schoolId
            )
            ->where(
                'is_active',
                true
            )
            ->first();

        if (! $academicYear) {
            return response()->json([
                'message' => 'Academic year not found.',
            ], 404);
        }

        /*
         * Verify class.
         */
        $class = Classes::query()
            ->where(
                'id',
                $validated['class_id']
            )
            ->where(
                'school_id',
                $schoolId
            )
            ->where(
                'is_active',
                true
            )
            ->first();

        if (! $class) {
            return response()->json([
                'message' => 'Class not found.',
            ], 404);
        }

        /*
         * Verify section belongs to class.
         */
        $section = Section::query()
            ->where(
                'id',
                $validated['section_id']
            )
            ->where(
                'class_id',
                $class->id
            )
            ->where(
                'is_active',
                true
            )
            ->first();

        if (! $section) {
            return response()->json([
                'message' => 'Section not found.',
            ], 404);
        }

        /*
         * Students are filtered by the actual enrollment.
         *
         * This is where Academic Year matters.
         */
        $students = Student::query()
            ->join(
                'student_enrollments',
                'student_enrollments.student_id',
                '=',
                'students.id'
            )
            ->where(
                'students.school_id',
                $schoolId
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
                'students.first_name',
                'students.middle_name',
                'students.last_name',
            ])
            ->distinct()
            ->orderBy('students.first_name')
            ->orderBy('students.last_name')
            ->get();

        return response()->json($students);
    }


    /**
     * Return active Student Fee assignments.
     */
    public function filterStudentFees(Request $request)
    {
        $schoolId = Auth::user()->school_id;

        $validated = $request->validate([
            'student_id' => [
                'required',
                'integer',
            ],
        ]);

        /*
         * Verify student belongs to current school.
         */
        $student = Student::query()
            ->where(
                'id',
                $validated['student_id']
            )
            ->where(
                'school_id',
                $schoolId
            )
            ->first();

        if (! $student) {
            return response()->json([
                'message' => 'Student not found.',
            ], 404);
        }

        /*
         * Get pending/partial fee assignments.
         */
        $studentFees = StudentFee::query()
            ->where(
                'school_id',
                $schoolId
            )
            ->where(
                'student_id',
                $student->id
            )
            ->whereIn(
                'status',
                [
                    'pending',
                    'partial',
                ]
            )
            ->with([
                'feeStructure.feeCategory',
                'feeStructure.academicYear',
                'feeStructure.classModel',
                'feeStructure.term',
                'scholarship',
            ])
            ->orderByDesc('id')
            ->get();

        return response()->json(
            $studentFees->map(
                function ($studentFee) {

                    $assignedAmount =
                        (float) $studentFee->amount;

                    $discount =
                        (float) $studentFee->discount;

                    $netAssigned =
                        max(
                            0,
                            $assignedAmount - $discount
                        );

                    return [
                        'id' =>
                            $studentFee->id,

                        'fee_structure_id' =>
                            $studentFee->fee_structure_id,

                        'fee_category_id' =>
                            optional(
                                $studentFee->feeStructure
                            )->fee_category_id,

                        'fee_category' =>
                            optional(
                                optional(
                                    $studentFee->feeStructure
                                )->feeCategory
                            )->name,

                        'description' =>
                            optional(
                                optional(
                                    $studentFee->feeStructure
                                )->feeCategory
                            )->name,

                        'academic_year' =>
                            optional(
                                optional(
                                    $studentFee->feeStructure
                                )->academicYear
                            )->name,

                        'class' =>
                            optional(
                                optional(
                                    $studentFee->feeStructure
                                )->classModel
                            )->name,

                        'term' =>
                            optional(
                                optional(
                                    $studentFee->feeStructure
                                )->term
                            )->name,

                        'assigned_amount' =>
                            number_format(
                                $assignedAmount,
                                2,
                                '.',
                                ''
                            ),

                        'discount' =>
                            number_format(
                                $discount,
                                2,
                                '.',
                                ''
                            ),

                        'net_assigned' =>
                            number_format(
                                $netAssigned,
                                2,
                                '.',
                                ''
                            ),

                        'status' =>
                            $studentFee->status,
                    ];
                }
            )
        );
    }


    /**
     * Store a new invoice.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $schoolId = $user->school_id;

        $validated = $request->validate([
            'academic_year_id' => [
                'required',
                'integer',
            ],

            'class_id' => [
                'required',
                'integer',
            ],

            'section_id' => [
                'required',
                'integer',
            ],

            'student_id' => [
                'required',
                'integer',
            ],

            'invoice_date' => [
                'required',
                'date',
            ],

            'due_date' => [
                'nullable',
                'date',
                'after_or_equal:invoice_date',
            ],

            'discount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'items' => [
                'required',
                'array',
                'min:1',
            ],

            'items.*.fee_category_id' => [
                'nullable',
                'integer',
            ],

            'items.*.student_fee_id' => [
                'nullable',
                'integer',
            ],

            'items.*.description' => [
                'required',
                'string',
                'max:500',
            ],

            'items.*.quantity' => [
                'required',
                'numeric',
                'gt:0',
            ],

            'items.*.amount' => [
                'required',
                'numeric',
                'gt:0',
            ],
        ]);

        /*
         * Verify Academic Year.
         */
        $academicYear = AcademicYears::query()
            ->where(
                'id',
                $validated['academic_year_id']
            )
            ->where(
                'school_id',
                $schoolId
            )
            ->where(
                'is_active',
                true
            )
            ->first();

        if (! $academicYear) {
            return back()
                ->withInput()
                ->withErrors([
                    'academic_year_id' =>
                        'The selected academic year is invalid.',
                ]);
        }

        /*
         * Verify Class.
         */
        $class = Classes::query()
            ->where(
                'id',
                $validated['class_id']
            )
            ->where(
                'school_id',
                $schoolId
            )
            ->where(
                'is_active',
                true
            )
            ->first();

        if (! $class) {
            return back()
                ->withInput()
                ->withErrors([
                    'class_id' =>
                        'The selected class is invalid.',
                ]);
        }

        /*
         * Verify Section.
         */
        $section = Section::query()
            ->where(
                'id',
                $validated['section_id']
            )
            ->where(
                'class_id',
                $class->id
            )
            ->where(
                'is_active',
                true
            )
            ->first();

        if (! $section) {
            return back()
                ->withInput()
                ->withErrors([
                    'section_id' =>
                        'The selected section is invalid.',
                ]);
        }

        /*
         * Verify Student.
         */
        $student = Student::query()
            ->where(
                'id',
                $validated['student_id']
            )
            ->where(
                'school_id',
                $schoolId
            )
            ->first();

        if (! $student) {
            return back()
                ->withInput()
                ->withErrors([
                    'student_id' =>
                        'The selected student is invalid.',
                ]);
        }

        /*
         * IMPORTANT:
         *
         * The student must actually belong to:
         *
         * Academic Year
         *      ↓
         * Class
         *      ↓
         * Section
         */
        $enrollmentExists =
            DB::table('student_enrollments')
                ->where(
                    'school_id',
                    $schoolId
                )
                ->where(
                    'student_id',
                    $student->id
                )
                ->where(
                    'academic_year_id',
                    $academicYear->id
                )
                ->where(
                    'class_id',
                    $class->id
                )
                ->where(
                    'section_id',
                    $section->id
                )
                ->where(
                    'status',
                    'active'
                )
                ->exists();

        if (! $enrollmentExists) {

            return back()
                ->withInput()
                ->withErrors([
                    'student_id' =>
                        'The selected student does not belong to the selected academic year, class and section.',
                ]);
        }

        $invoiceDiscount =
            (float) (
                $validated['discount'] ?? 0
            );

        DB::beginTransaction();

        try {

            $subtotal = 0;

            $preparedItems = [];

            foreach (
                $validated['items']
                as $item
            ) {

                /*
                 * Validate Fee Category.
                 */
                $feeCategory = null;

                if (
                    ! empty(
                        $item['fee_category_id']
                    )
                ) {

                    $feeCategory =
                        FeeCategory::query()
                            ->where(
                                'id',
                                $item['fee_category_id']
                            )
                            ->where(
                                'school_id',
                                $schoolId
                            )
                            ->where(
                                'is_active',
                                true
                            )
                            ->first();

                    if (! $feeCategory) {

                        throw new \RuntimeException(
                            'One of the selected fee categories is invalid.'
                        );
                    }
                }

                /*
                 * Validate Student Fee Assignment.
                 */
                if (
                    ! empty(
                        $item['student_fee_id']
                    )
                ) {

                    $studentFee =
                        StudentFee::query()
                            ->where(
                                'id',
                                $item['student_fee_id']
                            )
                            ->where(
                                'school_id',
                                $schoolId
                            )
                            ->where(
                                'student_id',
                                $student->id
                            )
                            ->whereIn(
                                'status',
                                [
                                    'pending',
                                    'partial',
                                ]
                            )
                            ->with('feeStructure')
                            ->first();

                    if (! $studentFee) {

                        throw new \RuntimeException(
                            'One of the selected student fee assignments is invalid.'
                        );
                    }

                    if (
                        $feeCategory &&
                        $studentFee->feeStructure &&
                        (int)
                            $studentFee
                                ->feeStructure
                                ->fee_category_id
                        !==
                        (int) $feeCategory->id
                    ) {

                        throw new \RuntimeException(
                            'The selected fee category does not match the student fee assignment.'
                        );
                    }
                }

                $quantity =
                    (float) $item['quantity'];

                $unitAmount =
                    (float) $item['amount'];

                $lineTotal =
                    round(
                        $quantity *
                        $unitAmount,
                        2
                    );

                if ($lineTotal <= 0) {

                    throw new \RuntimeException(
                        'Invoice item amount must be greater than zero.'
                    );
                }

                $subtotal +=
                    $lineTotal;

                $preparedItems[] = [

                    'fee_category_id' =>
                        $item['fee_category_id']
                        ?? null,

                    'description' =>
                        $item['description'],

                    'quantity' =>
                        $quantity,

                    'amount' =>
                        $unitAmount,
                ];
            }

            $subtotal =
                round(
                    $subtotal,
                    2
                );

            /*
             * Invoice discount cannot exceed subtotal.
             */
            if (
                $invoiceDiscount >
                $subtotal
            ) {

                throw new \RuntimeException(
                    'Invoice discount cannot exceed the invoice subtotal.'
                );
            }

            $total =
                round(
                    $subtotal -
                    $invoiceDiscount,
                    2
                );

            /*
             * Generate invoice number.
             */
            $invoiceNumber =
                $this->generateInvoiceNumber();

            /*
             * Create invoice.
             */
            $invoice =
                Invoice::create([
                    'school_id' =>
                        $schoolId,

                    'student_id' =>
                        $student->id,

                    'invoice_number' =>
                        $invoiceNumber,

                    'invoice_date' =>
                        $validated['invoice_date'],

                    'due_date' =>
                        $validated['due_date']
                        ?? null,

                    'subtotal' =>
                        $subtotal,

                    'discount' =>
                        $invoiceDiscount,

                    'total' =>
                        $total,

                    'paid' =>
                        0,

                    'balance' =>
                        $total,

                    'status' =>
                        'unpaid',

                    'created_by' =>
                        $user->id,
                ]);

            /*
             * Create invoice items.
             */
            foreach (
                $preparedItems
                as $item
            ) {

                InvoiceItem::create([
                    'school_id' =>
                        $schoolId,

                    'invoice_id' =>
                        $invoice->id,

                    'fee_category_id' =>
                        $item['fee_category_id'],

                    'description' =>
                        $item['description'],

                    'quantity' =>
                        $item['quantity'],

                    'amount' =>
                        $item['amount'],
                ]);
            }

            DB::commit();

            return redirect()
                ->route(
                    'admin.invoices.show',
                    $invoice
                )
                ->with(
                    'success',
                    'Invoice created successfully.'
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->withErrors([
                    'invoice' =>
                        $e->getMessage(),
                ]);
        }
    }


    /**
     * Display invoice.
     */
    public function show(Invoice $invoice)
    {
        $this->ensureSchoolAccess(
            $invoice
        );

        $invoice->load([
            'student',
            'items.feeCategory',
            'payments',
        ]);

        return view(
            'admin.invoices.show',
            compact('invoice')
        );
    }


    /**
     * Show invoice edit form.
     */
    public function edit(Invoice $invoice)
    {
        $this->ensureSchoolAccess(
            $invoice
        );

        if (
            in_array(
                $invoice->status,
                [
                    'partial',
                    'paid',
                    'cancelled',
                ],
                true
            )
        ) {

            return back()
                ->withErrors([
                    'invoice' =>
                        'This invoice cannot be edited because its status is ' .
                        $invoice->status .
                        '.',
                ]);
        }

        $invoice->load([
            'student',
            'items.feeCategory',
        ]);

        $feeCategories =
            FeeCategory::query()
                ->where(
                    'school_id',
                    Auth::user()->school_id
                )
                ->where(
                    'is_active',
                    true
                )
                ->orderBy('name')
                ->get();

        return view(
            'admin.invoices.edit',
            compact(
                'invoice',
                'feeCategories'
            )
        );
    }


    /**
     * Update invoice.
     */
    public function update(
        Request $request,
        Invoice $invoice
    ) {
        $this->ensureSchoolAccess(
            $invoice
        );

        if (
            in_array(
                $invoice->status,
                [
                    'partial',
                    'paid',
                    'cancelled',
                ],
                true
            )
        ) {

            return back()
                ->withErrors([
                    'invoice' =>
                        'This invoice cannot be edited because its status is ' .
                        $invoice->status .
                        '.',
                ]);
        }

        $schoolId =
            Auth::user()->school_id;

        $validated =
            $request->validate([
                'invoice_date' => [
                    'required',
                    'date',
                ],

                'due_date' => [
                    'nullable',
                    'date',
                    'after_or_equal:invoice_date',
                ],

                'discount' => [
                    'nullable',
                    'numeric',
                    'min:0',
                ],

                'items' => [
                    'required',
                    'array',
                    'min:1',
                ],

                'items.*.fee_category_id' => [
                    'nullable',
                    'integer',
                ],

                'items.*.description' => [
                    'required',
                    'string',
                    'max:500',
                ],

                'items.*.quantity' => [
                    'required',
                    'numeric',
                    'gt:0',
                ],

                'items.*.amount' => [
                    'required',
                    'numeric',
                    'gt:0',
                ],
            ]);

        $discount =
            (float) (
                $validated['discount']
                ?? 0
            );

        DB::beginTransaction();

        try {

            $subtotal = 0;

            $preparedItems = [];

            foreach (
                $validated['items']
                as $item
            ) {

                if (
                    ! empty(
                        $item['fee_category_id']
                    )
                ) {

                    $categoryExists =
                        FeeCategory::query()
                            ->where(
                                'id',
                                $item['fee_category_id']
                            )
                            ->where(
                                'school_id',
                                $schoolId
                            )
                            ->where(
                                'is_active',
                                true
                            )
                            ->exists();

                    if (! $categoryExists) {

                        throw new \RuntimeException(
                            'One of the selected fee categories is invalid.'
                        );
                    }
                }

                $quantity =
                    (float) $item['quantity'];

                $amount =
                    (float) $item['amount'];

                $lineTotal =
                    round(
                        $quantity *
                        $amount,
                        2
                    );

                $subtotal +=
                    $lineTotal;

                $preparedItems[] = [

                    'fee_category_id' =>
                        $item['fee_category_id']
                        ?? null,

                    'description' =>
                        $item['description'],

                    'quantity' =>
                        $quantity,

                    'amount' =>
                        $amount,
                ];
            }

            $subtotal =
                round(
                    $subtotal,
                    2
                );

            if (
                $discount >
                $subtotal
            ) {

                throw new \RuntimeException(
                    'Invoice discount cannot exceed the invoice subtotal.'
                );
            }

            $total =
                round(
                    $subtotal -
                    $discount,
                    2
                );

            /*
             * Only unpaid invoices reach this method,
             * therefore paid must remain zero.
             */
            $invoice->update([
                'invoice_date' =>
                    $validated['invoice_date'],

                'due_date' =>
                    $validated['due_date']
                    ?? null,

                'subtotal' =>
                    $subtotal,

                'discount' =>
                    $discount,

                'total' =>
                    $total,

                'paid' =>
                    0,

                'balance' =>
                    $total,

                'status' =>
                    'unpaid',
            ]);

            /*
             * Soft-delete existing items.
             *
             * We never physically delete financial history.
             */
            $invoice
                ->items()
                ->get()
                ->each(
                    function ($item) {
                        $item->delete();
                    }
                );

            /*
             * Create replacement items.
             */
            foreach (
                $preparedItems
                as $item
            ) {

                InvoiceItem::create([
                    'school_id' =>
                        $schoolId,

                    'invoice_id' =>
                        $invoice->id,

                    'fee_category_id' =>
                        $item['fee_category_id'],

                    'description' =>
                        $item['description'],

                    'quantity' =>
                        $item['quantity'],

                    'amount' =>
                        $item['amount'],
                ]);
            }

            DB::commit();

            return redirect()
                ->route(
                    'admin.invoices.show',
                    $invoice
                )
                ->with(
                    'success',
                    'Invoice updated successfully.'
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->withErrors([
                    'invoice' =>
                        $e->getMessage(),
                ]);
        }
    }


    /**
     * Cancel invoice.
     */
    public function cancel(
        Invoice $invoice
    ) {
        $this->ensureSchoolAccess(
            $invoice
        );

        if (
            $invoice->status !==
            'unpaid'
        ) {

            return back()
                ->withErrors([
                    'invoice' =>
                        'Only unpaid invoices can be cancelled.',
                ]);
        }

        /*
         * Extra safety:
         * Never cancel an invoice with payments.
         */
        if (
            $invoice
                ->payments()
                ->exists()
        ) {

            return back()
                ->withErrors([
                    'invoice' =>
                        'This invoice has payment records and cannot be cancelled.',
                ]);
        }

        $invoice->update([
            'status' =>
                'cancelled',
        ]);

        return back()
            ->with(
                'success',
                'Invoice cancelled successfully.'
            );
    }


    /**
     * Soft-delete invoice.
     */
    public function destroy(
        Invoice $invoice
    ) {
        $this->ensureSchoolAccess(
            $invoice
        );

        if (
            $invoice->status !==
            'unpaid'
        ) {

            return back()
                ->withErrors([
                    'invoice' =>
                        'Only unpaid invoices can be deleted.',
                ]);
        }

        if (
            $invoice
                ->payments()
                ->exists()
        ) {

            return back()
                ->withErrors([
                    'invoice' =>
                        'This invoice has payment records and cannot be deleted.',
                ]);
        }

        DB::transaction(
            function () use ($invoice) {

                /*
                 * Soft-delete items.
                 */
                $invoice
                    ->items()
                    ->get()
                    ->each(
                        function ($item) {
                            $item->delete();
                        }
                    );

                /*
                 * Soft-delete invoice.
                 */
                $invoice->delete();
            }
        );

        return redirect()
            ->route(
                'admin.invoices.index'
            )
            ->with(
                'success',
                'Invoice deleted successfully.'
            );
    }


    /**
     * Generate unique invoice number.
     */
    private function generateInvoiceNumber(): string
    {
        do {

            $number =
                'INV-' .
                now()->format('Y') .
                '-' .
                str_pad(
                    (string) random_int(
                        1,
                        999999
                    ),
                    6,
                    '0',
                    STR_PAD_LEFT
                );

        } while (
            Invoice::withTrashed()
                ->where(
                    'invoice_number',
                    $number
                )
                ->exists()
        );

        return $number;
    }


    /**
     * Ensure invoice belongs to current user's school.
     */
    private function ensureSchoolAccess(
        Invoice $invoice
    ): void {

        abort_unless(
            (int) $invoice->school_id ===
            (int) Auth::user()->school_id,
            403,
            'You are not authorized to access this invoice.'
        );
    }
}