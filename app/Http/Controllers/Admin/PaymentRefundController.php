<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinanceMovement;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentRefund;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PaymentRefundController extends Controller
{
    /**
     * List payment refund requests.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        abort_unless(
            $user->school_id,
            403,
            'No school is assigned to the current user.'
        );

        $schoolId = (int) $user->school_id;

        $query = PaymentRefund::query()
            ->where('payment_refunds.school_id', $schoolId)
            ->with([
                'payment.invoice',
                'student',
                'requester',
                'approver',
                'processor',
            ]);

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('payment_refunds.reason', 'like', "%{$search}%")
                    ->orWhere('payment_refunds.reference', 'like', "%{$search}%")
                    ->orWhereHas('payment', function ($paymentQuery) use ($search) {
                        $paymentQuery
                            ->where('transaction_reference', 'like', "%{$search}%")
                            ->orWhereHas('invoice', function ($invoiceQuery) use ($search) {
                                $invoiceQuery->where(
                                    'invoice_number',
                                    'like',
                                    "%{$search}%"
                                );
                            });
                    })
                    ->orWhereHas('student', function ($studentQuery) use ($search) {
                        $studentQuery
                            ->where('student_number', 'like', "%{$search}%")
                            ->orWhere('first_name', 'like', "%{$search}%")
                            ->orWhere('middle_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where(
                'payment_refunds.status',
                $request->status
            );
        }

        if ($request->filled('date_from')) {
            $query->whereDate(
                'payment_refunds.requested_at',
                '>=',
                $request->date_from
            );
        }

        if ($request->filled('date_to')) {
            $query->whereDate(
                'payment_refunds.requested_at',
                '<=',
                $request->date_to
            );
        }

        $refunds = $query
            ->latest('payment_refunds.requested_at')
            ->latest('payment_refunds.id')
            ->paginate(20)
            ->withQueryString();

        return view(
            'admin.payment-refunds.index',
            compact('refunds')
        );
    }

    /**
     * Show refund request details.
     */
    public function show(PaymentRefund $paymentRefund)
    {
        $user = Auth::user();

        abort_unless(
            $user->school_id,
            403,
            'No school is assigned to the current user.'
        );

        abort_unless(
            (int) $paymentRefund->school_id === (int) $user->school_id,
            404
        );

        $paymentRefund->load([
            'payment.invoice.student',
            'student',
            'requester',
            'approver',
            'processor',
        ]);

        return view(
            'admin.payment-refunds.show',
            compact('paymentRefund')
        );
    }

    /**
     * Approve a requested refund.
     */
    public function approve(Request $request, PaymentRefund $paymentRefund)
    {
        $user = Auth::user();

        abort_unless(
            $user->school_id,
            403,
            'No school is assigned to the current user.'
        );

        $schoolId = (int) $user->school_id;

        $validated = $request->validate([
            'notes' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        DB::transaction(function () use (
            $paymentRefund,
            $user,
            $schoolId,
            $validated
        ) {
            $refund = PaymentRefund::query()
                ->where('school_id', $schoolId)
                ->whereKey($paymentRefund->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($refund->status !== 'requested') {
                abort(
                    422,
                    'Only requested refunds can be approved.'
                );
            }

            $payment = Payment::query()
                ->where('school_id', $schoolId)
                ->whereKey($refund->payment_id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($payment->trashed()) {
                abort(
                    422,
                    'The original payment is no longer active.'
                );
            }

            $available = $this->remainingRefundableAmount(
                $payment,
                $refund->id,
                $schoolId
            );

            if ((float) $refund->amount > $available) {
                abort(
                    422,
                    'The refund amount exceeds the remaining refundable amount of ' .
                    number_format($available, 2) .
                    '.'
                );
            }

            $refund->update([
                'status' => 'approved',
                'approved_by' => $user->id,
                'approved_at' => now(),
                'notes' => $validated['notes'] ?? $refund->notes,
            ]);
        });

        return back()->with(
            'success',
            'Payment refund approved successfully.'
        );
    }

    /**
     * Reject a requested refund.
     */
    public function reject(Request $request, PaymentRefund $paymentRefund)
{
    $user = Auth::user();

    abort_unless(
        $user->school_id,
        403,
        'No school is assigned to the current user.'
    );

    $schoolId = (int) $user->school_id;

    $validated = $request->validate([
        'reason' => [
            'required',
            'string',
            'max:500',
        ],

        'notes' => [
            'nullable',
            'string',
            'max:2000',
        ],
    ]);

    DB::transaction(function () use (
        $paymentRefund,
        $user,
        $schoolId,
        $validated
    ) {
        $refund = PaymentRefund::query()
            ->where('school_id', $schoolId)
            ->whereKey($paymentRefund->id)
            ->lockForUpdate()
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Only requested refunds can be rejected
        |--------------------------------------------------------------------------
        */
        if ($refund->status !== 'requested') {
            abort(
                422,
                'Only requested refunds can be rejected.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Build audit notes
        |--------------------------------------------------------------------------
        */
        $rejectionNotes = 'Rejection reason: ' . trim(
            $validated['reason']
        );

        if (!empty($validated['notes'])) {
            $rejectionNotes .= "\nAdditional notes: " . trim(
                $validated['notes']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Preserve any existing notes
        |--------------------------------------------------------------------------
        */
        $existingNotes = trim((string) $refund->notes);

        if ($existingNotes !== '') {
            $rejectionNotes .= "\nPrevious notes: " . $existingNotes;
        }

        /*
        |--------------------------------------------------------------------------
        | Reject refund
        |--------------------------------------------------------------------------
        */
        $refund->update([
            'status' => 'rejected',
            'notes' => $rejectionNotes,
        ]);
    });

    return back()->with(
        'success',
        'Payment refund rejected successfully.'
    );
}

    /**
     * Process an approved refund.
     *
     * This is the point at which the financial effect occurs.
     */
    public function process(Request $request, PaymentRefund $paymentRefund)
    {
        $user = Auth::user();

        abort_unless(
            $user->school_id,
            403,
            'No school is assigned to the current user.'
        );

        $schoolId = (int) $user->school_id;

        $validated = $request->validate([
            'reference' => [
                'nullable',
                'string',
                'max:255',
            ],
            'notes' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        DB::transaction(function () use (
            $paymentRefund,
            $user,
            $schoolId,
            $validated
        ) {
            /*
             * Lock refund first, then payment, then invoice.
             * The same order is used consistently to reduce
             * concurrent-processing problems.
             */
            $refund = PaymentRefund::query()
                ->where('school_id', $schoolId)
                ->whereKey($paymentRefund->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($refund->status !== 'approved') {
                abort(
                    422,
                    'Only approved refunds can be processed.'
                );
            }

            $payment = Payment::query()
                ->where('school_id', $schoolId)
                ->whereKey($refund->payment_id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($payment->trashed()) {
                abort(
                    422,
                    'The original payment is no longer active.'
                );
            }

            $invoice = Invoice::query()
                ->where('school_id', $schoolId)
                ->whereKey($payment->invoice_id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($invoice->trashed()) {
                abort(
                    422,
                    'The invoice is no longer active.'
                );
            }

            $available = $this->remainingRefundableAmount(
                $payment,
                $refund->id,
                $schoolId
            );

            $refundAmount = round(
                (float) $refund->amount,
                2
            );

            if ($refundAmount <= 0) {
                abort(
                    422,
                    'Refund amount must be greater than zero.'
                );
            }

            if ($refundAmount > $available) {
                abort(
                    422,
                    'The refund amount exceeds the remaining refundable amount of ' .
                    number_format($available, 2) .
                    '.'
                );
            }

            /*
             * Capture the invoice balance before the refund.
             */
            $previousBalance = round(
                (float) $invoice->balance,
                2
            );

            /*
             * Mark the refund as processed.
             * The original payment remains unchanged.
             */
            $refund->update([
                'status' => 'processed',
                'processed_by' => $user->id,
                'processed_at' => now(),
                'reference' => $validated['reference'] ?? null,
                'notes' => $validated['notes'] ?? $refund->notes,
            ]);

            /*
             * Recalculate invoice financial state from history.
             *
             * Net paid = gross active payments
             *             - processed refunds.
             */
            $grossPaid = Payment::query()
                ->where('school_id', $schoolId)
                ->where('invoice_id', $invoice->id)
                ->whereNull('deleted_at')
                ->sum('amount');

            $processedRefunds = PaymentRefund::query()
                ->where('school_id', $schoolId)
                ->where('status', 'processed')
                ->whereHas('payment', function ($paymentQuery) use (
                    $schoolId,
                    $invoice
                ) {
                    $paymentQuery
                        ->where('school_id', $schoolId)
                        ->where('invoice_id', $invoice->id)
                        ->whereNull('deleted_at');
                })
                ->sum('amount');

            $grossPaid = round((float) $grossPaid, 2);
            $processedRefunds = round((float) $processedRefunds, 2);

            $paid = max(
                round($grossPaid - $processedRefunds, 2),
                0
            );

            $total = round(
                (float) $invoice->total,
                2
            );

            $balance = max(
                round($total - $paid, 2),
                0
            );

            if ($paid <= 0) {
                $status = 'unpaid';
            } elseif ($balance <= 0) {
                $status = 'paid';
            } else {
                $status = 'partial';
            }

            $invoice->update([
                'paid' => $paid,
                'balance' => $balance,
                'status' => $status,
            ]);

            /*
             * Record the financial correction in the immutable
             * finance movement/audit trail.
             *
             * Refund is a debit because money leaves the school
             * and the student's effective paid amount decreases.
             */
            FinanceMovement::create([
                'school_id' => $schoolId,
                'student_id' => $invoice->student_id,
                'invoice_id' => $invoice->id,
                'payment_id' => $payment->id,
                'movement_type' => 'payment_refund',
                'reference_type' => 'payment_refund',
                'reference_id' => $refund->id,
                'amount' => $refundAmount,
                'direction' => 'debit',
                'previous_balance' => $previousBalance,
                'new_balance' => $balance,
                'performed_by' => $user->id,
                'reason' => $refund->reason,
                'notes' => $validated['notes'] ?? null,
            ]);
        });

        return back()->with(
            'success',
            'Payment refund processed successfully.'
        );
    }

    /**
     * Cancel a requested refund.
     *
     * A processed refund cannot be cancelled.
     */
    public function cancel(Request $request, PaymentRefund $paymentRefund)
    {
        $user = Auth::user();

        abort_unless(
            $user->school_id,
            403,
            'No school is assigned to the current user.'
        );

        $schoolId = (int) $user->school_id;

        $validated = $request->validate([
            'notes' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        DB::transaction(function () use (
            $paymentRefund,
            $schoolId,
            $validated
        ) {
            $refund = PaymentRefund::query()
                ->where('school_id', $schoolId)
                ->whereKey($paymentRefund->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (!in_array($refund->status, ['requested', 'approved'], true)) {
                abort(
                    422,
                    'Only requested or approved refunds can be cancelled.'
                );
            }

            $refund->update([
                'status' => 'cancelled',
                'notes' => $validated['notes'] ?? $refund->notes,
            ]);
        });

        return back()->with(
            'success',
            'Payment refund cancelled successfully.'
        );
    }

    /**
     * Calculate the amount still available for refunding
     * on a payment.
     *
     * The current refund is excluded so it can be validated
     * during approval/processing.
     */
    private function remainingRefundableAmount(
        Payment $payment,
        int $currentRefundId,
        int $schoolId
    ): float {
        $paymentAmount = round(
            (float) $payment->amount,
            2
        );

        $otherRefunds = PaymentRefund::query()
            ->where('school_id', $schoolId)
            ->where('payment_id', $payment->id)
            ->whereKeyNot($currentRefundId)
            ->whereIn('status', [
                'requested',
                'approved',
                'processed',
            ])
            ->sum('amount');

        $otherRefunds = round(
            (float) $otherRefunds,
            2
        );

        return max(
            round($paymentAmount - $otherRefunds, 2),
            0
        );
    }
}
