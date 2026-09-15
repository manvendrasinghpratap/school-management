<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYears;
use App\Models\Classes;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentAllocation;
use App\Models\PaymentRefund;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PaymentController extends Controller
{
    /**
     * Payment listing.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        abort_unless(
            $user->school_id,
            403,
            'No school is assigned to the current user.'
        );

        $schoolId = $user->school_id;

        $query = Payment::query()
            ->where('payments.school_id', $schoolId)
            ->with([
                'invoice.student',
                'receivedBy',
                'refunds',
            ]);

        /*
         * Search by:
         * - transaction reference
         * - invoice number
         * - student number
         * - student name
         */
        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where(
                    'payments.transaction_reference',
                    'like',
                    "%{$search}%"
                )

                ->orWhereHas('invoice', function ($invoiceQuery) use ($search) {

                    $invoiceQuery
                        ->where(
                            'invoice_number',
                            'like',
                            "%{$search}%"
                        )

                        ->orWhereHas('student', function ($studentQuery) use ($search) {

                            $studentQuery
                                ->where(
                                    'student_number',
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

                        });

                });

            });
        }

        /*
         * Payment method filter.
         */
        if ($request->filled('payment_method')) {

            $query->where(
                'payments.payment_method',
                $request->payment_method
            );
        }

        /*
         * Date from.
         */
        if ($request->filled('date_from')) {

            $query->whereDate(
                'payments.paid_at',
                '>=',
                $request->date_from
            );
        }

        /*
         * Date to.
         */
        if ($request->filled('date_to')) {

            $query->whereDate(
                'payments.paid_at',
                '<=',
                $request->date_to
            );
        }

        $payments = $query
            ->latest('payments.paid_at')
            ->latest('payments.id')
            ->paginate(20)
            ->withQueryString();

        return view(
            'admin.payments.index',
            compact('payments')
        );
    }


    /**
     * Show payment collection form.
     */
    public function create(Request $request)
    {
        $user = Auth::user();

        abort_unless(
            $user->school_id,
            403,
            'No school is assigned to the current user.'
        );

        /*
         * The Create Blade uses the AJAX hierarchy:
         *
         * Academic Year
         *      ↓
         * Class
         *      ↓
         * Section
         *      ↓
         * Student
         *      ↓
         * Invoice
         *
         * Therefore invoices are loaded dynamically.
         */

        return view('admin.payments.create');
    }


    /**
     * Academic Year → Class
     */
    public function filterClasses(Request $request)
    {
        $user = Auth::user();

        abort_unless(
            $user->school_id,
            403,
            'No school is assigned to the current user.'
        );

        $validated = $request->validate([
            'academic_year_id' => [
                'required',
                'integer',
            ],
        ]);

        $schoolId = $user->school_id;

        /*
         * Verify academic year belongs to current school.
         */
        $academicYear = AcademicYears::query()
            ->where('id', $validated['academic_year_id'])
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->firstOrFail();

        /*
         * Classes are determined through active
         * student enrollments for the selected
         * academic year.
         */
        $classes = Classes::query()
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
            ->where(
                'classes.school_id',
                $schoolId
            )
            ->where(
                'classes.is_active',
                true
            )
            ->select([
                'classes.id',
                'classes.name',
                'classes.code',
            ])
            ->distinct()
            ->orderBy('classes.name')
            ->get();

        return response()->json($classes);
    }


    /**
     * Class → Section
     */
    public function filterSections(Request $request)
    {
        $user = Auth::user();

        abort_unless(
            $user->school_id,
            403,
            'No school is assigned to the current user.'
        );

        $validated = $request->validate([
            'class_id' => [
                'required',
                'integer',
            ],
        ]);

        $schoolId = $user->school_id;

        /*
         * Verify class belongs to current school.
         */
        $class = Classes::query()
            ->where('id', $validated['class_id'])
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->firstOrFail();

        /*
         * Sections do not have school_id.
         * They are scoped through their class.
         */
        $sections = Section::query()
            ->where('class_id', $class->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'code',
            ]);

        return response()->json($sections);
    }


    /**
     * Academic Year + Class + Section → Student
     */
    public function filterStudents(Request $request)
    {
        $user = Auth::user();

        abort_unless(
            $user->school_id,
            403,
            'No school is assigned to the current user.'
        );

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

        $schoolId = $user->school_id;

        /*
         * Verify academic year.
         */
        $academicYear = AcademicYears::query()
            ->where('id', $validated['academic_year_id'])
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->firstOrFail();

        /*
         * Verify class.
         */
        $class = Classes::query()
            ->where('id', $validated['class_id'])
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->firstOrFail();

        /*
         * Verify section belongs to selected class.
         */
        $section = Section::query()
            ->where('id', $validated['section_id'])
            ->where('class_id', $class->id)
            ->where('is_active', true)
            ->firstOrFail();

        /*
         * Find students through active enrollments.
         */
        $students = Student::query()
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
            ->where(
                'students.school_id',
                $schoolId
            )
            ->select([
                'students.id',
                'students.first_name',
                'students.middle_name',
                'students.last_name',
                'students.student_number',
            ])
            ->distinct()
            ->orderBy('students.first_name')
            ->orderBy('students.last_name')
            ->get();

        return response()->json($students);
    }


    /**
     * Student → Outstanding Invoices
     */
    public function filterInvoices(Request $request)
    {
        $user = Auth::user();

        abort_unless(
            $user->school_id,
            403,
            'No school is assigned to the current user.'
        );

        $validated = $request->validate([
            'student_id' => [
                'required',
                'integer',
            ],
        ]);

        $schoolId = $user->school_id;

        /*
         * Verify student belongs to current school.
         */
        $student = Student::query()
            ->where('id', $validated['student_id'])
            ->where('school_id', $schoolId)
            ->firstOrFail();

        /*
         * Only invoices with outstanding balances
         * are available for payment.
         */
        $invoices = Invoice::query()
            ->where('school_id', $schoolId)
            ->where('student_id', $student->id)
            ->whereNull('deleted_at')
            ->where('status', '!=', 'cancelled')
            ->where('balance', '>', 0)
            ->orderByDesc('invoice_date')
            ->orderByDesc('id')
            ->get([
                'id',
                'invoice_number',
                'invoice_date',
                'due_date',
                'subtotal',
                'discount',
                'total',
                'paid',
                'balance',
                'status',
            ]);

        return response()->json($invoices);
    }


    /**
     * Store a payment.
     *
     * Payment records are treated as immutable
     * financial records.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        abort_unless(
            $user->school_id,
            403,
            'No school is assigned to the current user.'
        );

        $schoolId = $user->school_id;

        $validated = $request->validate([

            'invoice_id' => [
                'required',
                'integer',
            ],

            'amount' => [
                'required',
                'numeric',
                'gt:0',
            ],

            'payment_method' => [
                'required',
                Rule::in([
                    'cash',
                    'bank_transfer',
                    'card',
                    'online',
                    'mobile_money',
                    'other',
                ]),
            ],

            'transaction_reference' => [
                'nullable',
                'string',
                'max:255',
            ],

            'paid_at' => [
                'required',
                'date',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:2000',
            ],

        ]);

        DB::transaction(function () use (
            $validated,
            $schoolId,
            $user
        ) {

            /*
             * Lock the invoice so two simultaneous
             * payment requests cannot overpay it.
             */
            $invoice = Invoice::query()
                ->where('school_id', $schoolId)
                ->whereNull('deleted_at')
                ->where('id', $validated['invoice_id'])
                ->lockForUpdate()
                ->firstOrFail();

            /*
             * Cancelled invoices cannot receive payment.
             */
            if ($invoice->status === 'cancelled') {

                abort(
                    422,
                    'Cancelled invoices cannot receive payments.'
                );
            }

            /*
             * Invoice must have outstanding balance.
             */
            $currentBalance = round(
                (float) $invoice->balance,
                2
            );

            if ($currentBalance <= 0) {

                abort(
                    422,
                    'This invoice has no outstanding balance.'
                );
            }

            $paymentAmount = round(
                (float) $validated['amount'],
                2
            );

            /*
             * Prevent overpayment.
             */
            if ($paymentAmount > $currentBalance) {

                abort(
                    422,
                    'Payment amount cannot exceed the invoice balance of ' .
                    number_format($currentBalance, 2) .
                    '.'
                );
            }

            /*
             * Create payment.
             */
            $payment = Payment::create([

                'school_id' => $schoolId,

                'invoice_id' => $invoice->id,

                'amount' => $paymentAmount,

                'payment_method' =>
                    $validated['payment_method'],

                'transaction_reference' =>
                    $validated['transaction_reference'] ?? null,

                'paid_at' =>
                    $validated['paid_at'],

                'received_by' =>
                    $user->id,

                'notes' =>
                    $validated['notes'] ?? null,

            ]);

            /*
             * Create invoice-level allocation.
             *
             * invoice_item_id remains NULL because
             * this payment is applied to the invoice
             * as a whole.
             */
            PaymentAllocation::create([

                'school_id' => $schoolId,

                'payment_id' => $payment->id,

                'invoice_id' => $invoice->id,

                'invoice_item_id' => null,

                'amount' => $paymentAmount,

                'allocated_at' => now(),

                'allocated_by' => $user->id,

            ]);

            /*
             * Recalculate invoice financial state from the
             * immutable payment/refund history.
             *
             * Gross paid = all active payments.
             * Processed refunds reduce the effective/net paid amount.
             */
            $grossPaid = Payment::query()
                ->where('school_id', $schoolId)
                ->where('invoice_id', $invoice->id)
                ->sum('amount');

            $processedRefunds = PaymentRefund::query()
                ->where('school_id', $schoolId)
                ->whereHas('payment', function ($paymentQuery) use ($invoice) {
                    $paymentQuery
                        ->where('school_id', $invoice->school_id)
                        ->where('invoice_id', $invoice->id);
                })
                ->where('status', 'processed')
                ->sum('amount');

            $grossPaid = round((float) $grossPaid, 2);
            $processedRefunds = round((float) $processedRefunds, 2);

            $paid = max(
                round($grossPaid - $processedRefunds, 2),
                0
            );

            $total = round((float) $invoice->total, 2);

            $balance = max(
                round($total - $paid, 2),
                0
            );

            /*
             * Determine invoice status.
             */
            if ($paid <= 0) {
                $status = 'unpaid';
            } elseif ($balance <= 0) {
                $status = 'paid';
            } else {
                $status = 'partial';
            }

            /*
             * Update invoice financial totals.
             */
            $invoice->update([

                'paid' => $paid,

                'balance' => $balance,

                'status' => $status,

            ]);
        });

        return redirect()
            ->route('admin.payments.index')
            ->with(
                'success',
                'Payment recorded successfully.'
            );
    }


    /**
     * Show payment details.
     */
    /**
 * Show payment details.
 */
public function show(Payment $payment)
{
    $user = Auth::user();

    abort_unless(
        $user->school_id,
        403,
        'No school is assigned to the current user.'
    );

    /*
     * School isolation.
     */
    abort_unless(
        (int) $payment->school_id ===
        (int) $user->school_id,
        404
    );

    /*
     * Load all information required by the
     * Payment Details screen.
     */
    $payment->load([
        'invoice.student',
        'allocations.invoiceItem',
        'receivedBy',
        'refunds.requester',
        'refunds.approver',
        'refunds.processor',
    ]);

    return view(
        'admin.payments.show',
        compact('payment')
    );
}


    /**
 * Request payment refund.
 *
 * The original payment is NEVER edited or deleted.
 * A separate payment_refunds record is created.
 *
 * This method only creates the refund request.
 * Approval and processing are handled by the dedicated
 * PaymentRefundController workflow.
 */
    public function reverse(Request $request, Payment $payment)
    {
        $user = Auth::user();

        abort_unless(
            $user->school_id,
            403,
            'No school is assigned to the current user.'
        );

        $schoolId = (int) $user->school_id;

        /*
         * School isolation.
         */
        abort_unless(
            (int) $payment->school_id === $schoolId,
            404
        );

        /*
         * A soft-deleted payment must not be reversed.
         */
        if ($payment->deleted_at !== null) {
            return back()->with(
                'error',
                'This payment is no longer active and cannot be reversed.'
            );
        }

        /*
         * Lock the payment and its invoice so two simultaneous
         * reversal requests cannot reserve the same amount.
         */
        try {
            DB::transaction(function () use (
                $request,
                $payment,
                $user,
                $schoolId
            ) {
                $lockedPayment = Payment::query()
                    ->where('school_id', $schoolId)
                    ->whereNull('deleted_at')
                    ->whereKey($payment->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $invoice = Invoice::query()
                    ->where('school_id', $schoolId)
                    ->whereKey($lockedPayment->invoice_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($lockedPayment->deleted_at !== null) {
                    abort(
                        422,
                        'This payment is no longer active and cannot be reversed.'
                    );
                }

                if ((float) $lockedPayment->amount <= 0) {
                    abort(
                        422,
                        'This payment cannot be reversed.'
                    );
                }

                /*
                 * Calculate all amounts already reserved or processed
                 * against this payment.
                 */
                $existingRefundAmount = PaymentRefund::query()
                    ->where('school_id', $schoolId)
                    ->where('payment_id', $lockedPayment->id)
                    ->whereIn('status', [
                        'requested',
                        'approved',
                        'processed',
                    ])
                    ->lockForUpdate()
                    ->sum('amount');

                $existingRefundAmount = round(
                    (float) $existingRefundAmount,
                    2
                );

                $paymentAmount = round(
                    (float) $lockedPayment->amount,
                    2
                );

                $remainingReversible = round(
                    $paymentAmount - $existingRefundAmount,
                    2
                );

                if ($remainingReversible <= 0) {
                    abort(
                        422,
                        'This payment has already been fully reversed or has a pending reversal.'
                    );
                }

                $reason = trim(
                    (string) $request->input(
                        'reason',
                        'Payment reversal requested.'
                    )
                );

                if ($reason === '') {
                    $reason = 'Payment reversal requested.';
                }

                /*
                 * Create the refund request.
                 */
                PaymentRefund::create([
                    'school_id' => $schoolId,
                    'payment_id' => $lockedPayment->id,
                    'student_id' => $invoice->student_id,
                    'amount' => $remainingReversible,
                    'reason' => $reason,
                    'status' => 'requested',
                    'requested_by' => $user->id,
                    'requested_at' => now(),
                    'notes' => $request->input('notes'),
                ]);
            });
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            throw $e;
        }

        return back()->with(
            'success',
            'Payment refund request created successfully.'
        );
    }
}
