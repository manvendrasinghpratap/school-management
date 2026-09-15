<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentRefund;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exports\Finance\CollectionReportExport;
use App\Exports\Finance\OutstandingFeesReportExport;
use App\Exports\Finance\PaymentReportExport;
use App\Exports\Finance\PaymentMethodReportExport;
use App\Exports\Finance\RefundReportExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class FinanceReportController extends Controller
{
    /**
     * Collection Report
     */
    public function collection(Request $request)
    {
        $user = Auth::user();

        abort_unless(
            $user?->school_id,
            403,
            'No school is assigned to this user.'
        );

        abort_unless(
            $user->can('payments.view'),
            403,
            'You do not have permission to view finance reports.'
        );

        $schoolId = (int) $user->school_id;

        /*
         * ---------------------------------------------------------
         * Date filters
         * ---------------------------------------------------------
         */

        $from = $request->input(
            'from',
            now()->startOfMonth()->format('Y-m-d')
        );

        $to = $request->input(
            'to',
            now()->format('Y-m-d')
        );

        /*
         * Validate dates.
         */
        $validated = $request->validate([
            'from' => [
                'nullable',
                'date',
            ],
            'to' => [
                'nullable',
                'date',
                'after_or_equal:from',
            ],
        ]);

        $from = $validated['from'] ?? $from;
        $to = $validated['to'] ?? $to;

        /*
         * ---------------------------------------------------------
         * Invoice totals
         * ---------------------------------------------------------
         */

        $invoiceQuery = Invoice::query()
            ->where('school_id', $schoolId)
            ->whereDate('invoice_date', '>=', $from)
            ->whereDate('invoice_date', '<=', $to);

        $totalInvoiced = (float) $invoiceQuery->sum('total');

        $invoiceCount = (int) $invoiceQuery->count();

        /*
         * ---------------------------------------------------------
         * Gross payments
         * ---------------------------------------------------------
         */

        $paymentQuery = Payment::query()
            ->where('school_id', $schoolId)
            ->whereDate('paid_at', '>=', $from)
            ->whereDate('paid_at', '<=', $to);

        $grossCollected = (float) $paymentQuery->sum('amount');

        $paymentCount = (int) $paymentQuery->count();

        /*
         * ---------------------------------------------------------
         * Processed refunds
         * ---------------------------------------------------------
         *
         * Only processed refunds reduce effective collection.
         */

        $refundQuery = PaymentRefund::query()
            ->where('school_id', $schoolId)
            ->where('status', 'processed')
            ->whereDate('processed_at', '>=', $from)
            ->whereDate('processed_at', '<=', $to);

        $totalRefunded = (float) $refundQuery->sum('amount');

        $refundCount = (int) $refundQuery->count();

        /*
         * ---------------------------------------------------------
         * Effective collection
         * ---------------------------------------------------------
         */

        $effectiveCollection = max(
            $grossCollected - $totalRefunded,
            0
        );

        /*
         * ---------------------------------------------------------
         * Outstanding
         * ---------------------------------------------------------
         *
         * Outstanding is based on invoices in the selected
         * invoice period.
         */

        $outstanding = (float) (clone $invoiceQuery)
            ->where('balance', '>', 0)
            ->sum('balance');

        /*
         * ---------------------------------------------------------
         * Collection percentage
         * ---------------------------------------------------------
         */

        $collectionPercentage = $totalInvoiced > 0
            ? round(
                ($effectiveCollection / $totalInvoiced) * 100,
                2
            )
            : 0;

        $collectionPercentage = min(
            max($collectionPercentage, 0),
            100
        );

        /*
         * ---------------------------------------------------------
         * Payment method summary
         * ---------------------------------------------------------
         */

        $paymentMethods = (clone $paymentQuery)
            ->selectRaw(
                'payment_method,
                 COUNT(*) as payment_count,
                 SUM(amount) as amount'
            )
            ->groupBy('payment_method')
            ->orderByDesc('amount')
            ->get();

        /*
         * ---------------------------------------------------------
         * Payment records
         * ---------------------------------------------------------
         */

        $payments = (clone $paymentQuery)
            ->with([
                'invoice.student',
                'receivedBy',
            ])
            ->latest('paid_at')
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        /*
         * ---------------------------------------------------------
         * Processed refunds
         * ---------------------------------------------------------
         */

        $refunds = (clone $refundQuery)
            ->with([
                'payment',
                'student',
                'requester',
                'approver',
                'processor',
            ])
            ->latest('processed_at')
            ->latest('id')
            ->take(20)
            ->get();

        return view(
            'admin.finance-reports.collection',
            compact(
                'from',
                'to',
                'totalInvoiced',
                'invoiceCount',
                'grossCollected',
                'paymentCount',
                'totalRefunded',
                'refundCount',
                'effectiveCollection',
                'outstanding',
                'collectionPercentage',
                'paymentMethods',
                'payments',
                'refunds'
            )
        );
    }

    /**
 * Outstanding Fees Report
 */
public function outstanding(Request $request)
{
    $user = Auth::user();

    abort_unless(
        $user?->school_id,
        403,
        'No school is assigned to this user.'
    );

    abort_unless(
        $user->can('invoices.view'),
        403,
        'You do not have permission to view outstanding fee reports.'
    );

    $schoolId = (int) $user->school_id;

    /*
     * ---------------------------------------------------------
     * Filters
     * ---------------------------------------------------------
     */

    $validated = $request->validate([
        'from' => [
            'nullable',
            'date',
        ],

        'to' => [
            'nullable',
            'date',
            'after_or_equal:from',
        ],

        'status' => [
            'nullable',
            'string',
            'in:all,unpaid,partial,pending',
        ],

        'student' => [
            'nullable',
            'string',
            'max:100',
        ],

        'overdue' => [
            'nullable',
            'boolean',
        ],
    ]);

    $from = $validated['from'] ?? null;
    $to = $validated['to'] ?? null;

    $status = $validated['status'] ?? 'all';

    $studentSearch = trim(
        $validated['student'] ?? ''
    );

    $overdue = $request->boolean('overdue');

    /*
     * ---------------------------------------------------------
     * Base query
     * ---------------------------------------------------------
     */

    $invoiceQuery = Invoice::query()
        ->where('school_id', $schoolId)
        ->where('balance', '>', 0)
        ->with('student');

    /*
     * ---------------------------------------------------------
     * Invoice date filter
     * ---------------------------------------------------------
     */

    if ($from) {
        $invoiceQuery->whereDate(
            'invoice_date',
            '>=',
            $from
        );
    }

    if ($to) {
        $invoiceQuery->whereDate(
            'invoice_date',
            '<=',
            $to
        );
    }

    /*
     * ---------------------------------------------------------
     * Status filter
     * ---------------------------------------------------------
     */

    if ($status !== 'all') {
        $invoiceQuery->where(
            'status',
            $status
        );
    }

    /*
     * ---------------------------------------------------------
     * Student search
     * ---------------------------------------------------------
     */

    if ($studentSearch !== '') {

        $invoiceQuery->whereHas(
            'student',
            function ($query) use ($studentSearch) {

                $query->where(function ($q) use ($studentSearch) {

                    $q->where(
                        'first_name',
                        'like',
                        '%' . $studentSearch . '%'
                    )
                    ->orWhere(
                        'middle_name',
                        'like',
                        '%' . $studentSearch . '%'
                    )
                    ->orWhere(
                        'last_name',
                        'like',
                        '%' . $studentSearch . '%'
                    )
                    ->orWhere(
                        'student_number',
                        'like',
                        '%' . $studentSearch . '%'
                    );

                });

            }
        );
    }

    /*
     * ---------------------------------------------------------
     * Overdue filter
     * ---------------------------------------------------------
     *
     * An invoice is overdue when:
     *
     * balance > 0
     * AND
     * due_date < today
     */

    if ($overdue) {

        $invoiceQuery
            ->whereNotNull('due_date')
            ->whereDate(
                'due_date',
                '<',
                now()->toDateString()
            );

    }

    /*
     * ---------------------------------------------------------
     * Summary
     * ---------------------------------------------------------
     *
     * Clone the filtered query so the summary and table
     * always use exactly the same filters.
     */

    $totalInvoices = (clone $invoiceQuery)
        ->count();

    $totalInvoiced = (float) (clone $invoiceQuery)
        ->sum('total');

    $totalPaid = (float) (clone $invoiceQuery)
        ->sum('paid');

    $totalOutstanding = (float) (clone $invoiceQuery)
        ->sum('balance');

    /*
     * ---------------------------------------------------------
     * Overdue summary
     * ---------------------------------------------------------
     */

    $overdueQuery = (clone $invoiceQuery)
        ->whereNotNull('due_date')
        ->whereDate(
            'due_date',
            '<',
            now()->toDateString()
        );

    $overdueInvoices = (int) $overdueQuery->count();

    $overdueAmount = (float) $overdueQuery
        ->sum('balance');

    /*
     * ---------------------------------------------------------
     * Paginated results
     * ---------------------------------------------------------
     */

    $invoices = $invoiceQuery
        ->orderByRaw(
            'CASE WHEN due_date IS NULL THEN 1 ELSE 0 END'
        )
        ->orderBy('due_date')
        ->orderByDesc('balance')
        ->paginate(20)
        ->withQueryString();

    return view(
        'admin.finance-reports.outstanding',
        compact(
            'from',
            'to',
            'status',
            'studentSearch',
            'overdue',
            'totalInvoices',
            'totalInvoiced',
            'totalPaid',
            'totalOutstanding',
            'overdueInvoices',
            'overdueAmount',
            'invoices'
        )
    );
}

/**
 * Outstanding Fees Report - PDF Export
 */
public function outstandingPdf(Request $request)
{
    $user = Auth::user();

    abort_unless(
        $user?->school_id,
        403,
        'No school is assigned to this user.'
    );

    abort_unless(
        $user->can('invoices.view'),
        403,
        'You do not have permission to export outstanding fee reports.'
    );

    $validated = $request->validate([
        'from' => [
            'nullable',
            'date',
        ],

        'to' => [
            'nullable',
            'date',
            'after_or_equal:from',
        ],

        'status' => [
            'nullable',
            'string',
            'in:all,unpaid,partial,pending',
        ],

        'student' => [
            'nullable',
            'string',
            'max:100',
        ],

        'overdue' => [
            'nullable',
            'boolean',
        ],
    ]);

    $from = $validated['from'] ?? null;
    $to = $validated['to'] ?? null;
    $status = $validated['status'] ?? 'all';

    $studentSearch = trim(
        $validated['student'] ?? ''
    );

    $overdue = $request->boolean('overdue');

    $schoolId = (int) $user->school_id;

    /*
     * ---------------------------------------------------------
     * Base Query
     * ---------------------------------------------------------
     */

    $invoiceQuery = Invoice::query()
        ->where('school_id', $schoolId)
        ->where('balance', '>', 0)
        ->with('student');

    /*
     * ---------------------------------------------------------
     * Invoice Date Filter
     * ---------------------------------------------------------
     */

    if ($from) {
        $invoiceQuery->whereDate(
            'invoice_date',
            '>=',
            $from
        );
    }

    if ($to) {
        $invoiceQuery->whereDate(
            'invoice_date',
            '<=',
            $to
        );
    }

    /*
     * ---------------------------------------------------------
     * Status Filter
     * ---------------------------------------------------------
     */

    if ($status !== 'all') {
        $invoiceQuery->where(
            'status',
            $status
        );
    }

    /*
     * ---------------------------------------------------------
     * Student Search
     * ---------------------------------------------------------
     */

    if ($studentSearch !== '') {

        $invoiceQuery->whereHas(
            'student',
            function ($query) use ($studentSearch) {

                $query->where(function ($q) use ($studentSearch) {

                    $q->where(
                        'first_name',
                        'like',
                        '%' . $studentSearch . '%'
                    )
                    ->orWhere(
                        'middle_name',
                        'like',
                        '%' . $studentSearch . '%'
                    )
                    ->orWhere(
                        'last_name',
                        'like',
                        '%' . $studentSearch . '%'
                    )
                    ->orWhere(
                        'student_number',
                        'like',
                        '%' . $studentSearch . '%'
                    );

                });
            }
        );
    }

    /*
     * ---------------------------------------------------------
     * Overdue Filter
     * ---------------------------------------------------------
     */

    if ($overdue) {

        $invoiceQuery
            ->whereNotNull('due_date')
            ->whereDate(
                'due_date',
                '<',
                now()->toDateString()
            );
    }

    /*
     * ---------------------------------------------------------
     * Summary
     * ---------------------------------------------------------
     */

    $totalInvoices = (int) (clone $invoiceQuery)
        ->count();

    $totalInvoiced = (float) (clone $invoiceQuery)
        ->sum('total');

    $totalPaid = (float) (clone $invoiceQuery)
        ->sum('paid');

    $totalOutstanding = (float) (clone $invoiceQuery)
        ->sum('balance');

    /*
     * ---------------------------------------------------------
     * Overdue Summary
     * ---------------------------------------------------------
     */

    $overdueQuery = (clone $invoiceQuery)
        ->whereNotNull('due_date')
        ->whereDate(
            'due_date',
            '<',
            now()->toDateString()
        );

    $overdueInvoices = (int) $overdueQuery
        ->count();

    $overdueAmount = (float) $overdueQuery
        ->sum('balance');

    /*
     * ---------------------------------------------------------
     * All Outstanding Invoices
     * ---------------------------------------------------------
     */

    $invoices = $invoiceQuery
        ->orderByRaw(
            'CASE WHEN due_date IS NULL THEN 1 ELSE 0 END'
        )
        ->orderBy('due_date')
        ->orderByDesc('balance')
        ->get();

    /*
     * ---------------------------------------------------------
     * Generate PDF
     * ---------------------------------------------------------
     */

    $pdf = Pdf::loadView(
        'admin.finance-reports.pdf.outstanding',
        compact(
            'from',
            'to',
            'status',
            'studentSearch',
            'overdue',
            'totalInvoices',
            'totalInvoiced',
            'totalPaid',
            'totalOutstanding',
            'overdueInvoices',
            'overdueAmount',
            'invoices'
        )
    );

    $pdf->setPaper(
        'a4',
        'landscape'
    );

    return $pdf->download(
        'outstanding-fees-report-' .
        ($from ?? 'all') .
        '-to-' .
        ($to ?? 'all') .
        '.pdf'
    );
}

/**
 * Outstanding Fees Report - Excel Export
 */
public function outstandingExcel(Request $request)
{
    $user = Auth::user();

    abort_unless(
        $user?->school_id,
        403,
        'No school is assigned to this user.'
    );

    abort_unless(
        $user->can('invoices.view'),
        403,
        'You do not have permission to export outstanding fee reports.'
    );

    $validated = $request->validate([
        'from' => [
            'nullable',
            'date',
        ],

        'to' => [
            'nullable',
            'date',
            'after_or_equal:from',
        ],

        'status' => [
            'nullable',
            'string',
            'in:all,unpaid,partial,pending',
        ],

        'student' => [
            'nullable',
            'string',
            'max:100',
        ],

        'overdue' => [
            'nullable',
            'boolean',
        ],
    ]);

    $from = $validated['from'] ?? null;
    $to = $validated['to'] ?? null;

    $status = $validated['status'] ?? 'all';

    $studentSearch = trim(
        $validated['student'] ?? ''
    );

    $overdue = $request->boolean('overdue');

    $schoolId = (int) $user->school_id;

    /*
     * ---------------------------------------------------------
     * Base query
     * ---------------------------------------------------------
     */

    $invoiceQuery = Invoice::query()
        ->where('school_id', $schoolId)
        ->where('balance', '>', 0)
        ->with('student');

    /*
     * Invoice date filter
     */

    if ($from) {
        $invoiceQuery->whereDate(
            'invoice_date',
            '>=',
            $from
        );
    }

    if ($to) {
        $invoiceQuery->whereDate(
            'invoice_date',
            '<=',
            $to
        );
    }

    /*
     * Status filter
     */

    if ($status !== 'all') {
        $invoiceQuery->where(
            'status',
            $status
        );
    }

    /*
     * Student search
     */

    if ($studentSearch !== '') {

        $invoiceQuery->whereHas(
            'student',
            function ($query) use ($studentSearch) {

                $query->where(function ($q) use ($studentSearch) {

                    $q->where(
                        'first_name',
                        'like',
                        '%' . $studentSearch . '%'
                    )
                    ->orWhere(
                        'middle_name',
                        'like',
                        '%' . $studentSearch . '%'
                    )
                    ->orWhere(
                        'last_name',
                        'like',
                        '%' . $studentSearch . '%'
                    )
                    ->orWhere(
                        'student_number',
                        'like',
                        '%' . $studentSearch . '%'
                    );

                });
            }
        );
    }

    /*
     * Overdue filter
     */

    if ($overdue) {

        $invoiceQuery
            ->whereNotNull('due_date')
            ->whereDate(
                'due_date',
                '<',
                now()->toDateString()
            );
    }

    /*
     * Summary
     */

    $totalInvoices = (int) (clone $invoiceQuery)
        ->count();

    $totalInvoiced = (float) (clone $invoiceQuery)
        ->sum('total');

    $totalPaid = (float) (clone $invoiceQuery)
        ->sum('paid');

    $totalOutstanding = (float) (clone $invoiceQuery)
        ->sum('balance');

    /*
     * Overdue summary
     */

    $overdueQuery = (clone $invoiceQuery)
        ->whereNotNull('due_date')
        ->whereDate(
            'due_date',
            '<',
            now()->toDateString()
        );

    $overdueInvoices = (int) $overdueQuery->count();

    $overdueAmount = (float) $overdueQuery
        ->sum('balance');

    /*
     * All records for Excel.
     */

    $invoices = $invoiceQuery
        ->orderByRaw(
            'CASE WHEN due_date IS NULL THEN 1 ELSE 0 END'
        )
        ->orderBy('due_date')
        ->orderByDesc('balance')
        ->get();

    return Excel::download(
        new OutstandingFeesReportExport(
            $invoices,
            $totalInvoiced,
            $totalPaid,
            $totalOutstanding,
            $totalInvoices,
            $overdueInvoices,
            $overdueAmount
        ),
        'outstanding-fees-report-' .
        ($from ?? 'all') .
        '-to-' .
        ($to ?? 'all') .
        '.xlsx'
    );
}

/**
 * Payment Report
 */
public function payments(Request $request)
{
    $user = Auth::user();

    abort_unless(
        $user?->school_id,
        403,
        'No school is assigned to this user.'
    );

    abort_unless(
        $user->can('payments.view'),
        403,
        'You do not have permission to view payment reports.'
    );

    $schoolId = (int) $user->school_id;

    /*
     * ---------------------------------------------------------
     * Filters
     * ---------------------------------------------------------
     */

    $validated = $request->validate([
        'from' => [
            'nullable',
            'date',
        ],

        'to' => [
            'nullable',
            'date',
            'after_or_equal:from',
        ],

        'payment_method' => [
            'nullable',
            'string',
            'max:100',
        ],

        'student' => [
            'nullable',
            'string',
            'max:100',
        ],
    ]);

    /*
     * Default period = current month.
     */
    $from = $validated['from']
        ?? now()->startOfMonth()->format('Y-m-d');

    $to = $validated['to']
        ?? now()->format('Y-m-d');

    $paymentMethod = trim(
        $validated['payment_method'] ?? ''
    );

    $studentSearch = trim(
        $validated['student'] ?? ''
    );

    /*
     * ---------------------------------------------------------
     * Base payment query
     * ---------------------------------------------------------
     *
     * Soft-deleted payments are automatically excluded by
     * Eloquent.
     */

    $paymentQuery = Payment::query()
        ->where('school_id', $schoolId)
        ->whereDate('paid_at', '>=', $from)
        ->whereDate('paid_at', '<=', $to)
        ->with([
            'invoice.student',
            'receivedBy',
        ]);

    /*
     * ---------------------------------------------------------
     * Payment method filter
     * ---------------------------------------------------------
     */

    if ($paymentMethod !== '') {
        $paymentQuery->where(
            'payment_method',
            $paymentMethod
        );
    }

    /*
     * ---------------------------------------------------------
     * Student search
     * ---------------------------------------------------------
     */

    if ($studentSearch !== '') {

        $paymentQuery->whereHas(
            'invoice.student',
            function ($query) use ($studentSearch) {

                $query->where(function ($q) use ($studentSearch) {

                    $q->where(
                        'first_name',
                        'like',
                        '%' . $studentSearch . '%'
                    )
                    ->orWhere(
                        'middle_name',
                        'like',
                        '%' . $studentSearch . '%'
                    )
                    ->orWhere(
                        'last_name',
                        'like',
                        '%' . $studentSearch . '%'
                    )
                    ->orWhere(
                        'student_number',
                        'like',
                        '%' . $studentSearch . '%'
                    );

                });

            }
        );
    }

    /*
     * ---------------------------------------------------------
     * Payment summary
     * ---------------------------------------------------------
     */

    $paymentCount = (int) (clone $paymentQuery)
        ->count();

    $grossPayments = (float) (clone $paymentQuery)
        ->sum('amount');

    /*
     * ---------------------------------------------------------
     * Processed refunds
     * ---------------------------------------------------------
     *
     * Refunds are filtered by their actual processed date.
     */

    $refundQuery = PaymentRefund::query()
        ->where('school_id', $schoolId)
        ->where('status', 'processed')
        ->whereDate(
            'processed_at',
            '>=',
            $from
        )
        ->whereDate(
            'processed_at',
            '<=',
            $to
        );

    $processedRefunds = (float) $refundQuery
        ->sum('amount');

    $refundCount = (int) $refundQuery
        ->count();

    /*
     * ---------------------------------------------------------
     * Effective collection
     * ---------------------------------------------------------
     */

    $effectiveCollection = max(
        $grossPayments - $processedRefunds,
        0
    );

    /*
     * ---------------------------------------------------------
     * Payment method summary
     * ---------------------------------------------------------
     */

    $paymentMethods = (clone $paymentQuery)
        ->selectRaw(
            'payment_method,
             COUNT(*) as payment_count,
             SUM(amount) as amount'
        )
        ->groupBy('payment_method')
        ->orderByDesc('amount')
        ->get();

    /*
     * ---------------------------------------------------------
     * Payments
     * ---------------------------------------------------------
     */

    $payments = (clone $paymentQuery)
        ->latest('paid_at')
        ->latest('id')
        ->paginate(25)
        ->withQueryString();

    /*
     * ---------------------------------------------------------
     * Return view
     * ---------------------------------------------------------
     */

    return view(
        'admin.finance-reports.payments',
        compact(
            'from',
            'to',
            'paymentMethod',
            'studentSearch',
            'paymentCount',
            'grossPayments',
            'processedRefunds',
            'effectiveCollection',
            'refundCount',
            'paymentMethods',
            'payments'
        )
    );
}

/**
 * Refund Report
 */
public function refunds(Request $request)
{
    $user = Auth::user();

    abort_unless(
        $user?->school_id,
        403,
        'No school is assigned to this user.'
    );

    abort_unless(
        $user->can('payment-refunds.view'),
        403,
        'You do not have permission to view refund reports.'
    );

    $schoolId = (int) $user->school_id;

    /*
     * ---------------------------------------------------------
     * Filters
     * ---------------------------------------------------------
     *
     * The report period is based on requested_at so that
     * requested, approved, rejected, cancelled and processed
     * refunds can all be reported consistently.
     */

    $validated = $request->validate([
        'from' => [
            'nullable',
            'date',
        ],

        'to' => [
            'nullable',
            'date',
            'after_or_equal:from',
        ],

        'status' => [
            'nullable',
            'string',
            'in:all,requested,approved,rejected,processed,cancelled',
        ],

        'student' => [
            'nullable',
            'string',
            'max:100',
        ],

        'payment_id' => [
            'nullable',
            'integer',
            'min:1',
        ],

        'reference' => [
            'nullable',
            'string',
            'max:100',
        ],
    ]);

    /*
     * Default period = current month.
     */
    $from = $validated['from']
        ?? now()->startOfMonth()->format('Y-m-d');

    $to = $validated['to']
        ?? now()->format('Y-m-d');

    $status = $validated['status'] ?? 'all';

    $studentSearch = trim(
        $validated['student'] ?? ''
    );

    $paymentId = $validated['payment_id'] ?? null;

    $reference = trim(
        $validated['reference'] ?? ''
    );

    /*
     * ---------------------------------------------------------
     * Base query
     * ---------------------------------------------------------
     */

    $refundQuery = PaymentRefund::query()
        ->where('school_id', $schoolId)
        ->whereDate(
            'requested_at',
            '>=',
            $from
        )
        ->whereDate(
            'requested_at',
            '<=',
            $to
        )
        ->with([
            'payment.invoice',
            'student',
            'requester',
            'approver',
            'processor',
        ]);

    /*
     * ---------------------------------------------------------
     * Status filter
     * ---------------------------------------------------------
     */

    if ($status !== 'all') {
        $refundQuery->where(
            'status',
            $status
        );
    }

    /*
     * ---------------------------------------------------------
     * Student search
     * ---------------------------------------------------------
     */

    if ($studentSearch !== '') {

        $refundQuery->whereHas(
            'student',
            function ($query) use ($studentSearch) {

                $query->where(function ($q) use ($studentSearch) {

                    $q->where(
                        'first_name',
                        'like',
                        '%' . $studentSearch . '%'
                    )
                    ->orWhere(
                        'middle_name',
                        'like',
                        '%' . $studentSearch . '%'
                    )
                    ->orWhere(
                        'last_name',
                        'like',
                        '%' . $studentSearch . '%'
                    )
                    ->orWhere(
                        'student_number',
                        'like',
                        '%' . $studentSearch . '%'
                    );

                });

            }
        );
    }

    /*
     * ---------------------------------------------------------
     * Payment filter
     * ---------------------------------------------------------
     */

    if ($paymentId) {

        $refundQuery->where(
            'payment_id',
            $paymentId
        );

    }

    /*
     * ---------------------------------------------------------
     * Reference filter
     * ---------------------------------------------------------
     */

    if ($reference !== '') {

        $refundQuery->where(
            'reference',
            'like',
            '%' . $reference . '%'
        );

    }

    /*
     * ---------------------------------------------------------
     * Summary
     * ---------------------------------------------------------
     */

    $totalRefunds = (int) (clone $refundQuery)
        ->count();

    $totalAmount = (float) (clone $refundQuery)
        ->sum('amount');

    $requestedAmount = (float) (clone $refundQuery)
        ->where('status', 'requested')
        ->sum('amount');

    $approvedAmount = (float) (clone $refundQuery)
        ->where('status', 'approved')
        ->sum('amount');

    $processedAmount = (float) (clone $refundQuery)
        ->where('status', 'processed')
        ->sum('amount');

    $rejectedAmount = (float) (clone $refundQuery)
        ->where('status', 'rejected')
        ->sum('amount');

    $cancelledAmount = (float) (clone $refundQuery)
        ->where('status', 'cancelled')
        ->sum('amount');

    /*
     * ---------------------------------------------------------
     * Status counts
     * ---------------------------------------------------------
     */

    $statusCounts = [
        'requested' => (int) (clone $refundQuery)
            ->where('status', 'requested')
            ->count(),

        'approved' => (int) (clone $refundQuery)
            ->where('status', 'approved')
            ->count(),

        'processed' => (int) (clone $refundQuery)
            ->where('status', 'processed')
            ->count(),

        'rejected' => (int) (clone $refundQuery)
            ->where('status', 'rejected')
            ->count(),

        'cancelled' => (int) (clone $refundQuery)
            ->where('status', 'cancelled')
            ->count(),
    ];

    /*
     * ---------------------------------------------------------
     * Paginated refunds
     * ---------------------------------------------------------
     */

    $refunds = (clone $refundQuery)
        ->latest('requested_at')
        ->latest('id')
        ->paginate(25)
        ->withQueryString();

    return view(
        'admin.finance-reports.refunds',
        compact(
            'from',
            'to',
            'status',
            'studentSearch',
            'paymentId',
            'reference',
            'totalRefunds',
            'totalAmount',
            'requestedAmount',
            'approvedAmount',
            'processedAmount',
            'rejectedAmount',
            'cancelledAmount',
            'statusCounts',
            'refunds'
        )
    );
}
/**
 * Payment Method Report
 */
public function paymentMethods(Request $request)
{
    $user = Auth::user();

    abort_unless(
        $user?->school_id,
        403,
        'No school is assigned to this user.'
    );

    abort_unless(
        $user->can('payments.view'),
        403,
        'You do not have permission to view payment method reports.'
    );

    $schoolId = (int) $user->school_id;

    /*
     * ---------------------------------------------------------
     * Filters
     * ---------------------------------------------------------
     */

    $validated = $request->validate([
        'from' => [
            'nullable',
            'date',
        ],

        'to' => [
            'nullable',
            'date',
            'after_or_equal:from',
        ],

        'payment_method' => [
            'nullable',
            'string',
            'max:100',
        ],

        'student' => [
            'nullable',
            'string',
            'max:100',
        ],
    ]);

    /*
     * Default period = current month.
     */
    $from = $validated['from']
        ?? now()->startOfMonth()->format('Y-m-d');

    $to = $validated['to']
        ?? now()->format('Y-m-d');

    $paymentMethod = trim(
        $validated['payment_method'] ?? ''
    );

    $studentSearch = trim(
        $validated['student'] ?? ''
    );

    /*
     * ---------------------------------------------------------
     * Base payment query
     * ---------------------------------------------------------
     */

    $paymentQuery = Payment::query()
        ->where('school_id', $schoolId)
        ->whereDate(
            'paid_at',
            '>=',
            $from
        )
        ->whereDate(
            'paid_at',
            '<=',
            $to
        );

    /*
     * ---------------------------------------------------------
     * Payment method filter
     * ---------------------------------------------------------
     */

    if ($paymentMethod !== '') {

        $paymentQuery->where(
            'payment_method',
            $paymentMethod
        );
    }

    /*
     * ---------------------------------------------------------
     * Student filter
     * ---------------------------------------------------------
     */

    if ($studentSearch !== '') {

        $paymentQuery->whereHas(
            'invoice.student',
            function ($query) use ($studentSearch) {

                $query->where(function ($q) use ($studentSearch) {

                    $q->where(
                        'first_name',
                        'like',
                        '%' . $studentSearch . '%'
                    )
                    ->orWhere(
                        'middle_name',
                        'like',
                        '%' . $studentSearch . '%'
                    )
                    ->orWhere(
                        'last_name',
                        'like',
                        '%' . $studentSearch . '%'
                    )
                    ->orWhere(
                        'student_number',
                        'like',
                        '%' . $studentSearch . '%'
                    );

                });

            }
        );
    }

    /*
     * ---------------------------------------------------------
     * Overall payment totals
     * ---------------------------------------------------------
     */

    $totalPayments = (int) (clone $paymentQuery)
        ->count();

    $grossCollection = (float) (clone $paymentQuery)
        ->sum('amount');

    /*
     * ---------------------------------------------------------
     * Processed refunds
     * ---------------------------------------------------------
     *
     * Refunds are linked to the original payment, allowing
     * us to determine which payment method was refunded.
     *
     * Refund date is based on processed_at.
     */

    $refundQuery = PaymentRefund::query()
        ->where('school_id', $schoolId)
        ->where('status', 'processed')
        ->whereDate(
            'processed_at',
            '>=',
            $from
        )
        ->whereDate(
            'processed_at',
            '<=',
            $to
        )
        ->whereHas(
            'payment',
            function ($query) use ($paymentMethod, $studentSearch) {

                if ($paymentMethod !== '') {

                    $query->where(
                        'payment_method',
                        $paymentMethod
                    );
                }

                if ($studentSearch !== '') {

                    $query->whereHas(
                        'invoice.student',
                        function ($studentQuery) use ($studentSearch) {

                            $studentQuery->where(function ($q) use ($studentSearch) {

                                $q->where(
                                    'first_name',
                                    'like',
                                    '%' . $studentSearch . '%'
                                )
                                ->orWhere(
                                    'middle_name',
                                    'like',
                                    '%' . $studentSearch . '%'
                                )
                                ->orWhere(
                                    'last_name',
                                    'like',
                                    '%' . $studentSearch . '%'
                                )
                                ->orWhere(
                                    'student_number',
                                    'like',
                                    '%' . $studentSearch . '%'
                                );

                            });

                        }
                    );
                }
            }
        );

    $processedRefunds = (float) $refundQuery
        ->sum('amount');

    $refundCount = (int) $refundQuery
        ->count();

    /*
     * ---------------------------------------------------------
     * Effective collection
     * ---------------------------------------------------------
     */

    $effectiveCollection = max(
        $grossCollection - $processedRefunds,
        0
    );

    /*
     * ---------------------------------------------------------
     * Overall collection percentage
     * ---------------------------------------------------------
     *
     * This percentage is based on the gross payment collection
     * within the selected report period.
     */

    $collectionPercentage = $grossCollection > 0
        ? round(
            ($effectiveCollection / $grossCollection) * 100,
            2
        )
        : 0;

    /*
     * ---------------------------------------------------------
     * Payment method summary
     * ---------------------------------------------------------
     */

    $methodSummary = (clone $paymentQuery)
        ->selectRaw(
            'payment_method,
             COUNT(*) as payment_count,
             SUM(amount) as gross_amount'
        )
        ->groupBy('payment_method')
        ->orderByDesc('gross_amount')
        ->get();

    /*
     * ---------------------------------------------------------
     * Refund amounts by payment method
     * ---------------------------------------------------------
     *
     * Build a keyed collection:
     *
     * [
     *     'cash' => 49.00,
     *     'bank_transfer' => 2000.00,
     * ]
     */

    $refundByMethod = (clone $refundQuery)
        ->with('payment')
        ->get()
        ->groupBy(function ($refund) {

            return (string) (
                $refund->payment?->payment_method
                ?? 'other'
            );

        })
        ->map(function ($refunds) {

            return round(
                (float) $refunds->sum('amount'),
                2
            );

        });

    /*
     * ---------------------------------------------------------
     * Build final method rows
     * ---------------------------------------------------------
     */

    $methodRows = $methodSummary->map(function ($method) use (
        $refundByMethod
    ) {

        $methodName = (string) (
            $method->payment_method
            ?? 'other'
        );

        $grossAmount = round(
            (float) $method->gross_amount,
            2
        );

        $refundedAmount = round(
            (float) (
                $refundByMethod->get(
                    $methodName,
                    0
                )
            ),
            2
        );

        $effectiveAmount = max(
            $grossAmount - $refundedAmount,
            0
        );

        $percentage = $grossAmount > 0
            ? round(
                ($effectiveAmount / $grossAmount) * 100,
                2
            )
            : 0;

        return (object) [
            'payment_method' => $methodName,
            'payment_count' => (int) $method->payment_count,
            'gross_amount' => $grossAmount,
            'refunded_amount' => $refundedAmount,
            'effective_amount' => round(
                $effectiveAmount,
                2
            ),
            'percentage' => $percentage,
        ];
    });

    /*
     * ---------------------------------------------------------
     * Transaction details
     * ---------------------------------------------------------
     */

    $payments = (clone $paymentQuery)
        ->with([
            'invoice.student',
            'receivedBy',
        ])
        ->latest('paid_at')
        ->latest('id')
        ->paginate(25)
        ->withQueryString();

    /*
     * ---------------------------------------------------------
     * Return view
     * ---------------------------------------------------------
     */

    return view(
        'admin.finance-reports.payment-methods',
        compact(
            'from',
            'to',
            'paymentMethod',
            'studentSearch',
            'totalPayments',
            'grossCollection',
            'processedRefunds',
            'refundCount',
            'effectiveCollection',
            'collectionPercentage',
            'methodRows',
            'payments'
        )
    );
}

/**
 * Export Collection Report to Excel.
 */
public function collectionExcel(Request $request)
{
    $user = Auth::user();

    abort_unless(
        $user?->school_id,
        403,
        'No school is assigned to this user.'
    );

    abort_unless(
        $user->can('payments.view'),
        403,
        'You do not have permission to export finance reports.'
    );

    $validated = $request->validate([
        'from' => [
            'nullable',
            'date',
        ],
        'to' => [
            'nullable',
            'date',
            'after_or_equal:from',
        ],
    ]);

    $from = $validated['from']
        ?? now()->startOfMonth()->format('Y-m-d');

    $to = $validated['to']
        ?? now()->format('Y-m-d');

    $filename = 'collection-report-' . $from . '-to-' . $to . '.xlsx';

    return Excel::download(
        new CollectionReportExport(
            (int) $user->school_id,
            $from,
            $to
        ),
        $filename
    );
}

/**
 * Export Collection Report to PDF.
 */
public function collectionPdf(Request $request)
{
    $user = Auth::user();

    abort_unless(
        $user?->school_id,
        403,
        'No school is assigned to this user.'
    );

    abort_unless(
        $user->can('payments.view'),
        403,
        'You do not have permission to export finance reports.'
    );

    $validated = $request->validate([
        'from' => [
            'nullable',
            'date',
        ],
        'to' => [
            'nullable',
            'date',
            'after_or_equal:from',
        ],
    ]);

    $from = $validated['from']
        ?? now()->startOfMonth()->format('Y-m-d');

    $to = $validated['to']
        ?? now()->format('Y-m-d');

    $schoolId = (int) $user->school_id;

    /*
     * Invoice totals
     */
    $invoiceQuery = Invoice::query()
        ->where('school_id', $schoolId)
        ->whereDate('invoice_date', '>=', $from)
        ->whereDate('invoice_date', '<=', $to);

    $totalInvoiced = (float) $invoiceQuery->sum('total');

    $invoiceCount = (int) $invoiceQuery->count();

    /*
     * Gross payments
     */
    $paymentQuery = Payment::query()
        ->where('school_id', $schoolId)
        ->whereDate('paid_at', '>=', $from)
        ->whereDate('paid_at', '<=', $to);

    $grossCollected = (float) $paymentQuery->sum('amount');

    $paymentCount = (int) $paymentQuery->count();

    /*
     * Processed refunds
     */
    $refundQuery = PaymentRefund::query()
        ->where('school_id', $schoolId)
        ->where('status', 'processed')
        ->whereDate('processed_at', '>=', $from)
        ->whereDate('processed_at', '<=', $to);

    $totalRefunded = (float) $refundQuery->sum('amount');

    $refundCount = (int) $refundQuery->count();

    /*
     * Effective collection
     */
    $effectiveCollection = max(
        $grossCollected - $totalRefunded,
        0
    );

    /*
     * Outstanding
     */
    $outstanding = (float) (clone $invoiceQuery)
        ->where('balance', '>', 0)
        ->sum('balance');

    /*
     * Collection percentage
     */
    $collectionPercentage = $totalInvoiced > 0
        ? round(
            ($effectiveCollection / $totalInvoiced) * 100,
            2
        )
        : 0;

    $collectionPercentage = min(
        max($collectionPercentage, 0),
        100
    );

    /*
     * Payment method summary
     */
    $paymentMethods = (clone $paymentQuery)
        ->selectRaw(
            'payment_method,
             COUNT(*) as payment_count,
             SUM(amount) as amount'
        )
        ->groupBy('payment_method')
        ->orderByDesc('amount')
        ->get();

    /*
     * Payment transactions
     *
     * PDF exports all matching records, not only the
     * current browser pagination page.
     */
    $payments = (clone $paymentQuery)
        ->with([
            'invoice.student',
            'receivedBy',
        ])
        ->latest('paid_at')
        ->latest('id')
        ->get();

    /*
     * Processed refunds
     */
    $refunds = (clone $refundQuery)
        ->with([
            'payment',
            'student',
            'requester',
            'approver',
            'processor',
        ])
        ->latest('processed_at')
        ->latest('id')
        ->get();

    $pdf = Pdf::loadView(
        'admin.finance-reports.pdf.collection',
        compact(
            'from',
            'to',
            'totalInvoiced',
            'invoiceCount',
            'grossCollected',
            'paymentCount',
            'totalRefunded',
            'refundCount',
            'effectiveCollection',
            'outstanding',
            'collectionPercentage',
            'paymentMethods',
            'payments',
            'refunds'
        )
    );

    $pdf->setPaper('a4', 'landscape');

    return $pdf->download(
        'collection-report-' . $from . '-to-' . $to . '.pdf'
    );
}


/**
 * Payment Report - Excel Export
 */
public function paymentExcel(Request $request)
{
    $user = Auth::user();

    abort_unless(
        $user?->school_id,
        403,
        'No school is assigned to this user.'
    );

    abort_unless(
        $user->can('payments.view'),
        403,
        'You do not have permission to export payment reports.'
    );

    $validated = $request->validate([
        'from' => [
            'nullable',
            'date',
        ],

        'to' => [
            'nullable',
            'date',
            'after_or_equal:from',
        ],

        'payment_method' => [
            'nullable',
            'string',
            'max:100',
        ],

        'student' => [
            'nullable',
            'string',
            'max:100',
        ],
    ]);

    $from = $validated['from']
        ?? now()->startOfMonth()->format('Y-m-d');

    $to = $validated['to']
        ?? now()->format('Y-m-d');

    $paymentMethod = trim(
        $validated['payment_method'] ?? ''
    );

    $studentSearch = trim(
        $validated['student'] ?? ''
    );

    $schoolId = (int) $user->school_id;

    /*
     * ---------------------------------------------------------
     * Payment Query
     * ---------------------------------------------------------
     */

    $paymentQuery = Payment::query()
        ->where('school_id', $schoolId)
        ->whereDate('paid_at', '>=', $from)
        ->whereDate('paid_at', '<=', $to);

    /*
     * Payment Method
     */

    if ($paymentMethod !== '') {
        $paymentQuery->where(
            'payment_method',
            $paymentMethod
        );
    }

    /*
     * Student Search
     */

    if ($studentSearch !== '') {

        $paymentQuery->whereHas(
            'invoice.student',
            function ($query) use ($studentSearch) {

                $query->where(function ($q) use ($studentSearch) {

                    $q->where(
                        'first_name',
                        'like',
                        '%' . $studentSearch . '%'
                    )
                    ->orWhere(
                        'middle_name',
                        'like',
                        '%' . $studentSearch . '%'
                    )
                    ->orWhere(
                        'last_name',
                        'like',
                        '%' . $studentSearch . '%'
                    )
                    ->orWhere(
                        'student_number',
                        'like',
                        '%' . $studentSearch . '%'
                    );

                });
            }
        );
    }

    /*
     * ---------------------------------------------------------
     * Summary
     * ---------------------------------------------------------
     */

    $paymentCount = (int) (clone $paymentQuery)
        ->count();

    $grossPayments = (float) (clone $paymentQuery)
        ->sum('amount');

    /*
     * ---------------------------------------------------------
     * Processed Refunds
     * ---------------------------------------------------------
     */

    $refundQuery = PaymentRefund::query()
        ->where('school_id', $schoolId)
        ->where('status', 'processed')
        ->whereDate('processed_at', '>=', $from)
        ->whereDate('processed_at', '<=', $to)
        ->whereHas(
            'payment',
            function ($query) use (
                $paymentMethod,
                $studentSearch
            ) {

                if ($paymentMethod !== '') {
                    $query->where(
                        'payment_method',
                        $paymentMethod
                    );
                }

                if ($studentSearch !== '') {

                    $query->whereHas(
                        'invoice.student',
                        function ($studentQuery) use (
                            $studentSearch
                        ) {

                            $studentQuery->where(
                                function ($q) use (
                                    $studentSearch
                                ) {

                                    $q->where(
                                        'first_name',
                                        'like',
                                        '%' . $studentSearch . '%'
                                    )
                                    ->orWhere(
                                        'middle_name',
                                        'like',
                                        '%' . $studentSearch . '%'
                                    )
                                    ->orWhere(
                                        'last_name',
                                        'like',
                                        '%' . $studentSearch . '%'
                                    )
                                    ->orWhere(
                                        'student_number',
                                        'like',
                                        '%' . $studentSearch . '%'
                                    );

                                }
                            );
                        }
                    );
                }
            }
        );

    $processedRefunds = (float) $refundQuery
        ->sum('amount');

    $refundCount = (int) $refundQuery
        ->count();

    $effectiveCollection = max(
        $grossPayments - $processedRefunds,
        0
    );

    /*
     * ---------------------------------------------------------
     * Payments
     * ---------------------------------------------------------
     */

    $payments = (clone $paymentQuery)
        ->with([
            'invoice.student',
            'receivedBy',
        ])
        ->latest('paid_at')
        ->latest('id')
        ->get();

    return Excel::download(
        new PaymentReportExport(
            $payments,
            $grossPayments,
            $processedRefunds,
            $effectiveCollection,
            $paymentCount,
            $refundCount
        ),
        'payment-report-' .
        $from .
        '-to-' .
        $to .
        '.xlsx'
    );
}

/**
 * Payment Report - PDF Export
 */
public function paymentPdf(Request $request)
{
    $user = Auth::user();

    abort_unless(
        $user?->school_id,
        403,
        'No school is assigned to this user.'
    );

    abort_unless(
        $user->can('payments.view'),
        403,
        'You do not have permission to export payment reports.'
    );

    $validated = $request->validate([
        'from' => [
            'nullable',
            'date',
        ],

        'to' => [
            'nullable',
            'date',
            'after_or_equal:from',
        ],

        'payment_method' => [
            'nullable',
            'string',
            'max:100',
        ],

        'student' => [
            'nullable',
            'string',
            'max:100',
        ],
    ]);

    $from = $validated['from']
        ?? now()->startOfMonth()->format('Y-m-d');

    $to = $validated['to']
        ?? now()->format('Y-m-d');

    $paymentMethod = trim(
        $validated['payment_method'] ?? ''
    );

    $studentSearch = trim(
        $validated['student'] ?? ''
    );

    $schoolId = (int) $user->school_id;

    /*
     * ---------------------------------------------------------
     * Payment Query
     * ---------------------------------------------------------
     */

    $paymentQuery = Payment::query()
        ->where('school_id', $schoolId)
        ->whereDate('paid_at', '>=', $from)
        ->whereDate('paid_at', '<=', $to);

    if ($paymentMethod !== '') {
        $paymentQuery->where(
            'payment_method',
            $paymentMethod
        );
    }

    if ($studentSearch !== '') {

        $paymentQuery->whereHas(
            'invoice.student',
            function ($query) use ($studentSearch) {

                $query->where(function ($q) use ($studentSearch) {

                    $q->where(
                        'first_name',
                        'like',
                        '%' . $studentSearch . '%'
                    )
                    ->orWhere(
                        'middle_name',
                        'like',
                        '%' . $studentSearch . '%'
                    )
                    ->orWhere(
                        'last_name',
                        'like',
                        '%' . $studentSearch . '%'
                    )
                    ->orWhere(
                        'student_number',
                        'like',
                        '%' . $studentSearch . '%'
                    );

                });
            }
        );
    }

    /*
     * ---------------------------------------------------------
     * Summary
     * ---------------------------------------------------------
     */

    $paymentCount = (int) (clone $paymentQuery)
        ->count();

    $grossPayments = (float) (clone $paymentQuery)
        ->sum('amount');

    /*
     * ---------------------------------------------------------
     * Processed Refunds
     * ---------------------------------------------------------
     */

    $refundQuery = PaymentRefund::query()
        ->where('school_id', $schoolId)
        ->where('status', 'processed')
        ->whereDate('processed_at', '>=', $from)
        ->whereDate('processed_at', '<=', $to)
        ->whereHas(
            'payment',
            function ($query) use (
                $paymentMethod,
                $studentSearch
            ) {

                if ($paymentMethod !== '') {
                    $query->where(
                        'payment_method',
                        $paymentMethod
                    );
                }

                if ($studentSearch !== '') {

                    $query->whereHas(
                        'invoice.student',
                        function ($studentQuery) use (
                            $studentSearch
                        ) {

                            $studentQuery->where(
                                function ($q) use (
                                    $studentSearch
                                ) {

                                    $q->where(
                                        'first_name',
                                        'like',
                                        '%' . $studentSearch . '%'
                                    )
                                    ->orWhere(
                                        'middle_name',
                                        'like',
                                        '%' . $studentSearch . '%'
                                    )
                                    ->orWhere(
                                        'last_name',
                                        'like',
                                        '%' . $studentSearch . '%'
                                    )
                                    ->orWhere(
                                        'student_number',
                                        'like',
                                        '%' . $studentSearch . '%'
                                    );

                                }
                            );
                        }
                    );
                }
            }
        );

    $processedRefunds = (float) $refundQuery
        ->sum('amount');

    $refundCount = (int) $refundQuery
        ->count();

    $effectiveCollection = max(
        $grossPayments - $processedRefunds,
        0
    );

    /*
     * ---------------------------------------------------------
     * Payment Method Summary
     * ---------------------------------------------------------
     */

    $paymentMethods = (clone $paymentQuery)
        ->selectRaw(
            'payment_method,
             COUNT(*) as payment_count,
             SUM(amount) as amount'
        )
        ->groupBy('payment_method')
        ->orderByDesc('amount')
        ->get();

    /*
     * ---------------------------------------------------------
     * Payment Records
     * ---------------------------------------------------------
     */

    $payments = (clone $paymentQuery)
        ->with([
            'invoice.student',
            'receivedBy',
        ])
        ->latest('paid_at')
        ->latest('id')
        ->get();

    /*
     * ---------------------------------------------------------
     * PDF
     * ---------------------------------------------------------
     */

    $pdf = Pdf::loadView(
        'admin.finance-reports.pdf.payments',
        compact(
            'from',
            'to',
            'paymentMethod',
            'studentSearch',
            'paymentCount',
            'grossPayments',
            'processedRefunds',
            'effectiveCollection',
            'refundCount',
            'paymentMethods',
            'payments'
        )
    );

    $pdf->setPaper(
        'a4',
        'landscape'
    );

    return $pdf->download(
        'payment-report-' .
        $from .
        '-to-' .
        $to .
        '.pdf'
    );
}

/**
 * Payment Method Report - Excel Export
 */
public function paymentMethodsExcel(Request $request)
{
    $user = Auth::user();

    abort_unless(
        $user?->school_id,
        403,
        'No school is assigned to this user.'
    );

    abort_unless(
        $user->can('payments.view'),
        403,
        'You do not have permission to export payment method reports.'
    );

    $schoolId = (int) $user->school_id;

    $validated = $request->validate([
        'from' => [
            'nullable',
            'date',
        ],

        'to' => [
            'nullable',
            'date',
            'after_or_equal:from',
        ],

        'payment_method' => [
            'nullable',
            'string',
            'max:100',
        ],

        'student' => [
            'nullable',
            'string',
            'max:100',
        ],
    ]);

    $from = $validated['from']
        ?? now()->startOfMonth()->format('Y-m-d');

    $to = $validated['to']
        ?? now()->format('Y-m-d');

    $paymentMethod = trim(
        $validated['payment_method'] ?? ''
    );

    $studentSearch = trim(
        $validated['student'] ?? ''
    );

    /*
     * ---------------------------------------------------------
     * Payment Query
     * ---------------------------------------------------------
     */

    $paymentQuery = Payment::query()
        ->where('school_id', $schoolId)
        ->whereDate('paid_at', '>=', $from)
        ->whereDate('paid_at', '<=', $to);

    /*
     * Payment Method Filter
     */

    if ($paymentMethod !== '') {
        $paymentQuery->where(
            'payment_method',
            $paymentMethod
        );
    }

    /*
     * Student Filter
     */

    if ($studentSearch !== '') {

        $paymentQuery->whereHas(
            'invoice.student',
            function ($query) use ($studentSearch) {

                $query->where(function ($q) use ($studentSearch) {

                    $q->where(
                        'first_name',
                        'like',
                        '%' . $studentSearch . '%'
                    )

                    ->orWhere(
                        'middle_name',
                        'like',
                        '%' . $studentSearch . '%'
                    )

                    ->orWhere(
                        'last_name',
                        'like',
                        '%' . $studentSearch . '%'
                    )

                    ->orWhere(
                        'student_number',
                        'like',
                        '%' . $studentSearch . '%'
                    );

                });

            }
        );
    }

    /*
     * ---------------------------------------------------------
     * Payment Method Summary
     * ---------------------------------------------------------
     */

    $methodSummary = (clone $paymentQuery)
        ->selectRaw(
            'payment_method,
             COUNT(*) as payment_count,
             SUM(amount) as gross_amount'
        )
        ->groupBy('payment_method')
        ->orderByDesc('gross_amount')
        ->get();

    /*
     * ---------------------------------------------------------
     * Refund Query
     * ---------------------------------------------------------
     */

    $refundQuery = PaymentRefund::query()
        ->where('school_id', $schoolId)
        ->where('status', 'processed')
        ->whereDate(
            'processed_at',
            '>=',
            $from
        )
        ->whereDate(
            'processed_at',
            '<=',
            $to
        )
        ->whereHas(
            'payment',
            function ($query) use (
                $paymentMethod,
                $studentSearch
            ) {

                if ($paymentMethod !== '') {

                    $query->where(
                        'payment_method',
                        $paymentMethod
                    );
                }

                if ($studentSearch !== '') {

                    $query->whereHas(
                        'invoice.student',
                        function ($studentQuery) use (
                            $studentSearch
                        ) {

                            $studentQuery->where(
                                function ($q) use (
                                    $studentSearch
                                ) {

                                    $q->where(
                                        'first_name',
                                        'like',
                                        '%' . $studentSearch . '%'
                                    )

                                    ->orWhere(
                                        'middle_name',
                                        'like',
                                        '%' . $studentSearch . '%'
                                    )

                                    ->orWhere(
                                        'last_name',
                                        'like',
                                        '%' . $studentSearch . '%'
                                    )

                                    ->orWhere(
                                        'student_number',
                                        'like',
                                        '%' . $studentSearch . '%'
                                    );

                                }
                            );
                        }
                    );
                }
            }
        );

    /*
     * ---------------------------------------------------------
     * Refund Amounts By Payment Method
     * ---------------------------------------------------------
     */

    $refundByMethod = (clone $refundQuery)
        ->with('payment')
        ->get()
        ->groupBy(function ($refund) {

            return (string) (
                $refund->payment?->payment_method
                ?? 'other'
            );

        })
        ->map(function ($refunds) {

            return round(
                (float) $refunds->sum('amount'),
                2
            );

        });

    /*
     * ---------------------------------------------------------
     * Build Final Rows
     * ---------------------------------------------------------
     */

    $methodRows = $methodSummary->map(function ($method) use (
        $refundByMethod
    ) {

        $methodName = (string) (
            $method->payment_method
            ?? 'other'
        );

        $grossAmount = round(
            (float) $method->gross_amount,
            2
        );

        $refundedAmount = round(
            (float) (
                $refundByMethod->get(
                    $methodName,
                    0
                )
            ),
            2
        );

        $effectiveAmount = max(
            $grossAmount - $refundedAmount,
            0
        );

        $percentage = $grossAmount > 0
            ? round(
                ($effectiveAmount / $grossAmount) * 100,
                2
            )
            : 0;

        return (object) [
            'payment_method' => $methodName,
            'payment_count' => (int) $method->payment_count,
            'gross_amount' => $grossAmount,
            'refunded_amount' => $refundedAmount,
            'effective_amount' => round(
                $effectiveAmount,
                2
            ),
            'percentage' => $percentage,
        ];
    });

    return Excel::download(
        new PaymentMethodReportExport($methodRows),
        'payment-method-report-' . $from . '-to-' . $to . '.xlsx'
    );
}


/**
 * Payment Method Report - PDF Export
 */
public function paymentMethodsPdf(Request $request)
{
    $user = Auth::user();

    abort_unless(
        $user?->school_id,
        403,
        'No school is assigned to this user.'
    );

    abort_unless(
        $user->can('payments.view'),
        403,
        'You do not have permission to export payment method reports.'
    );

    $schoolId = (int) $user->school_id;

    $validated = $request->validate([
        'from' => [
            'nullable',
            'date',
        ],

        'to' => [
            'nullable',
            'date',
            'after_or_equal:from',
        ],

        'payment_method' => [
            'nullable',
            'string',
            'max:100',
        ],

        'student' => [
            'nullable',
            'string',
            'max:100',
        ],
    ]);

    $from = $validated['from']
        ?? now()->startOfMonth()->format('Y-m-d');

    $to = $validated['to']
        ?? now()->format('Y-m-d');

    $paymentMethod = trim(
        $validated['payment_method'] ?? ''
    );

    $studentSearch = trim(
        $validated['student'] ?? ''
    );

    /*
     * ---------------------------------------------------------
     * Payment Query
     * ---------------------------------------------------------
     */

    $paymentQuery = Payment::query()
        ->where('school_id', $schoolId)
        ->whereDate('paid_at', '>=', $from)
        ->whereDate('paid_at', '<=', $to);

    if ($paymentMethod !== '') {
        $paymentQuery->where(
            'payment_method',
            $paymentMethod
        );
    }

    if ($studentSearch !== '') {

        $paymentQuery->whereHas(
            'invoice.student',
            function ($query) use ($studentSearch) {

                $query->where(function ($q) use ($studentSearch) {

                    $q->where(
                        'first_name',
                        'like',
                        '%' . $studentSearch . '%'
                    )

                    ->orWhere(
                        'middle_name',
                        'like',
                        '%' . $studentSearch . '%'
                    )

                    ->orWhere(
                        'last_name',
                        'like',
                        '%' . $studentSearch . '%'
                    )

                    ->orWhere(
                        'student_number',
                        'like',
                        '%' . $studentSearch . '%'
                    );

                });

            }
        );
    }

    /*
     * ---------------------------------------------------------
     * Summary
     * ---------------------------------------------------------
     */

    $totalPayments = (int) (clone $paymentQuery)
        ->count();

    $grossCollection = (float) (clone $paymentQuery)
        ->sum('amount');

    /*
     * ---------------------------------------------------------
     * Refund Query
     * ---------------------------------------------------------
     */

    $refundQuery = PaymentRefund::query()
        ->where('school_id', $schoolId)
        ->where('status', 'processed')
        ->whereDate(
            'processed_at',
            '>=',
            $from
        )
        ->whereDate(
            'processed_at',
            '<=',
            $to
        )
        ->whereHas(
            'payment',
            function ($query) use (
                $paymentMethod,
                $studentSearch
            ) {

                if ($paymentMethod !== '') {

                    $query->where(
                        'payment_method',
                        $paymentMethod
                    );
                }

                if ($studentSearch !== '') {

                    $query->whereHas(
                        'invoice.student',
                        function ($studentQuery) use (
                            $studentSearch
                        ) {

                            $studentQuery->where(
                                function ($q) use (
                                    $studentSearch
                                ) {

                                    $q->where(
                                        'first_name',
                                        'like',
                                        '%' . $studentSearch . '%'
                                    )

                                    ->orWhere(
                                        'middle_name',
                                        'like',
                                        '%' . $studentSearch . '%'
                                    )

                                    ->orWhere(
                                        'last_name',
                                        'like',
                                        '%' . $studentSearch . '%'
                                    )

                                    ->orWhere(
                                        'student_number',
                                        'like',
                                        '%' . $studentSearch . '%'
                                    );

                                }
                            );
                        }
                    );
                }
            }
        );

    $processedRefunds = (float) $refundQuery
        ->sum('amount');

    $refundCount = (int) $refundQuery
        ->count();

    $effectiveCollection = max(
        $grossCollection - $processedRefunds,
        0
    );

    $collectionPercentage = $grossCollection > 0
        ? round(
            ($effectiveCollection / $grossCollection) * 100,
            2
        )
        : 0;

    /*
     * ---------------------------------------------------------
     * Payment Method Summary
     * ---------------------------------------------------------
     */

    $methodSummary = (clone $paymentQuery)
        ->selectRaw(
            'payment_method,
             COUNT(*) as payment_count,
             SUM(amount) as gross_amount'
        )
        ->groupBy('payment_method')
        ->orderByDesc('gross_amount')
        ->get();

    /*
     * ---------------------------------------------------------
     * Refund By Method
     * ---------------------------------------------------------
     */

    $refundByMethod = (clone $refundQuery)
        ->with('payment')
        ->get()
        ->groupBy(function ($refund) {

            return (string) (
                $refund->payment?->payment_method
                ?? 'other'
            );

        })
        ->map(function ($refunds) {

            return round(
                (float) $refunds->sum('amount'),
                2
            );

        });

    /*
     * ---------------------------------------------------------
     * Final Method Rows
     * ---------------------------------------------------------
     */

    $methodRows = $methodSummary->map(function ($method) use (
        $refundByMethod
    ) {

        $methodName = (string) (
            $method->payment_method
            ?? 'other'
        );

        $grossAmount = round(
            (float) $method->gross_amount,
            2
        );

        $refundedAmount = round(
            (float) (
                $refundByMethod->get(
                    $methodName,
                    0
                )
            ),
            2
        );

        $effectiveAmount = max(
            $grossAmount - $refundedAmount,
            0
        );

        $percentage = $grossAmount > 0
            ? round(
                ($effectiveAmount / $grossAmount) * 100,
                2
            )
            : 0;

        return (object) [
            'payment_method' => $methodName,
            'payment_count' => (int) $method->payment_count,
            'gross_amount' => $grossAmount,
            'refunded_amount' => $refundedAmount,
            'effective_amount' => round(
                $effectiveAmount,
                2
            ),
            'percentage' => $percentage,
        ];
    });

    return Pdf::loadView(
        'admin.finance-reports.pdf.payment-methods',
        compact(
            'from',
            'to',
            'paymentMethod',
            'studentSearch',
            'totalPayments',
            'grossCollection',
            'processedRefunds',
            'refundCount',
            'effectiveCollection',
            'collectionPercentage',
            'methodRows'
        )
    )
        ->setPaper('a4', 'landscape')
        ->download(
            'payment-method-report-' . $from . '-to-' . $to . '.pdf'
        );
}

/**
 * Refund Report - Excel Export
 */

/**
 * Refund Report - Excel Export
 */
public function refundsExcel(Request $request)
{
    $user = Auth::user();

    abort_unless(
        $user?->school_id,
        403,
        'No school is assigned to this user.'
    );

    abort_unless(
        $user->can('payment-refunds.view'),
        403,
        'You do not have permission to export refund reports.'
    );

    $schoolId = (int) $user->school_id;

    /*
    |--------------------------------------------------------------------------
    | Filters
    |--------------------------------------------------------------------------
    */

    $validated = $request->validate([
        'from' => [
            'nullable',
            'date',
        ],

        'to' => [
            'nullable',
            'date',
            'after_or_equal:from',
        ],

        'status' => [
            'nullable',
            'string',
            'in:all,requested,approved,rejected,processed,cancelled',
        ],

        'payment_method' => [
            'nullable',
            'string',
            'max:100',
        ],

        'student' => [
            'nullable',
            'string',
            'max:100',
        ],

        'payment_id' => [
            'nullable',
            'integer',
            'min:1',
        ],

        'reference' => [
            'nullable',
            'string',
            'max:100',
        ],
    ]);

    /*
    |--------------------------------------------------------------------------
    | Default dates
    |--------------------------------------------------------------------------
    */

    $from = $validated['from']
        ?? now()->startOfMonth()->format('Y-m-d');

    $to = $validated['to']
        ?? now()->format('Y-m-d');

    $status = $validated['status'] ?? 'all';

    $paymentMethod = trim(
        $validated['payment_method'] ?? ''
    );

    $studentSearch = trim(
        $validated['student'] ?? ''
    );

    $paymentId = $validated['payment_id'] ?? null;

    $reference = trim(
        $validated['reference'] ?? ''
    );

    /*
    |--------------------------------------------------------------------------
    | Base Refund Query
    |--------------------------------------------------------------------------
    */

    $refundQuery = PaymentRefund::query()
        ->where('school_id', $schoolId)
        ->whereDate(
            'requested_at',
            '>=',
            $from
        )
        ->whereDate(
            'requested_at',
            '<=',
            $to
        )
        ->with([
            'payment.invoice',
            'student',
            'requester',
            'approver',
            'processor',
        ]);

    /*
    |--------------------------------------------------------------------------
    | Status
    |--------------------------------------------------------------------------
    */

    if ($status !== 'all') {

        $refundQuery->where(
            'status',
            $status
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Payment Method
    |--------------------------------------------------------------------------
    */

    if ($paymentMethod !== '') {

        $refundQuery->whereHas(
            'payment',
            function ($query) use ($paymentMethod) {

                $query->where(
                    'payment_method',
                    $paymentMethod
                );

            }
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Student Search
    |--------------------------------------------------------------------------
    */

    if ($studentSearch !== '') {

        $refundQuery->whereHas(
            'student',
            function ($query) use ($studentSearch) {

                $query->where(function ($q) use ($studentSearch) {

                    $q->where(
                        'first_name',
                        'like',
                        '%' . $studentSearch . '%'
                    )

                    ->orWhere(
                        'middle_name',
                        'like',
                        '%' . $studentSearch . '%'
                    )

                    ->orWhere(
                        'last_name',
                        'like',
                        '%' . $studentSearch . '%'
                    )

                    ->orWhere(
                        'student_number',
                        'like',
                        '%' . $studentSearch . '%'
                    );

                });

            }
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Payment ID
    |--------------------------------------------------------------------------
    */

    if ($paymentId) {

        $refundQuery->where(
            'payment_id',
            $paymentId
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Refund Reference
    |--------------------------------------------------------------------------
    */

    if ($reference !== '') {

        $refundQuery->where(
            'reference',
            'like',
            '%' . $reference . '%'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Get Refunds
    |--------------------------------------------------------------------------
    */

    $refunds = $refundQuery
        ->latest('requested_at')
        ->latest('id')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Excel Download
    |--------------------------------------------------------------------------
    */

    return Excel::download(
        new RefundReportExport($refunds),
        "refund-report-{$from}-to-{$to}.xlsx"
    );
}


/**
 * Refund Report - PDF Export
 */
public function refundsPdf(Request $request)
{
    $user = Auth::user();

    abort_unless(
        $user?->school_id,
        403,
        'No school is assigned to this user.'
    );

    abort_unless(
        $user->can('payment-refunds.view'),
        403,
        'You do not have permission to export refund reports.'
    );

    $schoolId = (int) $user->school_id;

    /*
    |--------------------------------------------------------------------------
    | Filters
    |--------------------------------------------------------------------------
    */

    $validated = $request->validate([
        'from' => [
            'nullable',
            'date',
        ],

        'to' => [
            'nullable',
            'date',
            'after_or_equal:from',
        ],

        'status' => [
            'nullable',
            'string',
            'in:all,requested,approved,rejected,processed,cancelled',
        ],

        'payment_method' => [
            'nullable',
            'string',
            'max:100',
        ],

        'student' => [
            'nullable',
            'string',
            'max:100',
        ],

        'payment_id' => [
            'nullable',
            'integer',
            'min:1',
        ],

        'reference' => [
            'nullable',
            'string',
            'max:100',
        ],
    ]);

    /*
    |--------------------------------------------------------------------------
    | Default dates
    |--------------------------------------------------------------------------
    */

    $from = $validated['from']
        ?? now()->startOfMonth()->format('Y-m-d');

    $to = $validated['to']
        ?? now()->format('Y-m-d');

    $status = $validated['status'] ?? 'all';

    $paymentMethod = trim(
        $validated['payment_method'] ?? ''
    );

    $studentSearch = trim(
        $validated['student'] ?? ''
    );

    $paymentId = $validated['payment_id'] ?? null;

    $reference = trim(
        $validated['reference'] ?? ''
    );

    /*
    |--------------------------------------------------------------------------
    | Base Refund Query
    |--------------------------------------------------------------------------
    */

    $refundQuery = PaymentRefund::query()
        ->where('school_id', $schoolId)
        ->whereDate(
            'requested_at',
            '>=',
            $from
        )
        ->whereDate(
            'requested_at',
            '<=',
            $to
        )
        ->with([
            'payment.invoice',
            'student',
            'requester',
            'approver',
            'processor',
        ]);

    /*
    |--------------------------------------------------------------------------
    | Status
    |--------------------------------------------------------------------------
    */

    if ($status !== 'all') {

        $refundQuery->where(
            'status',
            $status
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Payment Method
    |--------------------------------------------------------------------------
    */

    if ($paymentMethod !== '') {

        $refundQuery->whereHas(
            'payment',
            function ($query) use ($paymentMethod) {

                $query->where(
                    'payment_method',
                    $paymentMethod
                );

            }
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Student Search
    |--------------------------------------------------------------------------
    */

    if ($studentSearch !== '') {

        $refundQuery->whereHas(
            'student',
            function ($query) use ($studentSearch) {

                $query->where(function ($q) use ($studentSearch) {

                    $q->where(
                        'first_name',
                        'like',
                        '%' . $studentSearch . '%'
                    )

                    ->orWhere(
                        'middle_name',
                        'like',
                        '%' . $studentSearch . '%'
                    )

                    ->orWhere(
                        'last_name',
                        'like',
                        '%' . $studentSearch . '%'
                    )

                    ->orWhere(
                        'student_number',
                        'like',
                        '%' . $studentSearch . '%'
                    );

                });

            }
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Payment ID
    |--------------------------------------------------------------------------
    */

    if ($paymentId) {

        $refundQuery->where(
            'payment_id',
            $paymentId
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Refund Reference
    |--------------------------------------------------------------------------
    */

    if ($reference !== '') {

        $refundQuery->where(
            'reference',
            'like',
            '%' . $reference . '%'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Get Refunds
    |--------------------------------------------------------------------------
    */

    $refunds = $refundQuery
        ->latest('requested_at')
        ->latest('id')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Summary
    |--------------------------------------------------------------------------
    */

    $totalRefunds = (int) $refunds->count();

    $totalAmount = round(
        (float) $refunds->sum('amount'),
        2
    );

    $requestedAmount = round(
        (float) $refunds
            ->where('status', 'requested')
            ->sum('amount'),
        2
    );

    $approvedAmount = round(
        (float) $refunds
            ->where('status', 'approved')
            ->sum('amount'),
        2
    );

    $processedAmount = round(
        (float) $refunds
            ->where('status', 'processed')
            ->sum('amount'),
        2
    );

    $rejectedAmount = round(
        (float) $refunds
            ->where('status', 'rejected')
            ->sum('amount'),
        2
    );

    $cancelledAmount = round(
        (float) $refunds
            ->where('status', 'cancelled')
            ->sum('amount'),
        2
    );

    /*
    |--------------------------------------------------------------------------
    | Status Counts
    |--------------------------------------------------------------------------
    */

    $statusCounts = [
        'requested' => $refunds
            ->where('status', 'requested')
            ->count(),

        'approved' => $refunds
            ->where('status', 'approved')
            ->count(),

        'processed' => $refunds
            ->where('status', 'processed')
            ->count(),

        'rejected' => $refunds
            ->where('status', 'rejected')
            ->count(),

        'cancelled' => $refunds
            ->where('status', 'cancelled')
            ->count(),
    ];

    /*
    |--------------------------------------------------------------------------
    | Generate PDF
    |--------------------------------------------------------------------------
    */

    $pdf = Pdf::loadView(
        'admin.finance-reports.pdf.refunds',
        compact(
            'from',
            'to',
            'status',
            'paymentMethod',
            'studentSearch',
            'paymentId',
            'reference',
            'refunds',
            'totalRefunds',
            'totalAmount',
            'requestedAmount',
            'approvedAmount',
            'processedAmount',
            'rejectedAmount',
            'cancelledAmount',
            'statusCounts'
        )
    );

    /*
    |--------------------------------------------------------------------------
    | PDF Download
    |--------------------------------------------------------------------------
    */

    return $pdf
        ->setPaper('a4', 'landscape')
        ->download(
            "refund-report-{$from}-to-{$to}.pdf"
        );
}

}