<?php

namespace App\Services;

use App\Models\AcademicYears;
use App\Models\Courses;
use App\Models\Department;
use App\Models\Section;
use App\Models\Staff;
use App\Models\Student;
use App\Models\Terms;
use App\Models\Classes;
use App\Models\Instructor;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentRefund;
use Illuminate\Support\Facades\Auth;

class DashboardService
{
    public function getDashboardData(): array
    {
        $schoolId = $this->schoolId();

        $currentAcademicYear = AcademicYears::query()
            ->where('school_id', $schoolId)
            ->where('is_current', true)
            ->where('is_active', true)
            ->first();

        $currentTerm = null;

        if ($currentAcademicYear) {
            $currentTerm = Terms::query()
                ->where('school_id', $schoolId)
                ->where('academic_year_id', $currentAcademicYear->id)
                ->where('is_current', true)
                ->where('is_active', true)
                ->first();
        }

        return [
            'statistics' => [
                'students' => Student::query()
                    ->where('school_id', $schoolId)
                    ->count(),

                'active_students' => Student::query()
                    ->where('school_id', $schoolId)
                    ->where('status', 'active')
                    ->count(),

                'staff' => Staff::query()
                    ->where('school_id', $schoolId)
                    ->count(),

                'active_staff' => Staff::query()
                    ->where('school_id', $schoolId)
                    ->where('status', 'active')
                    ->count(),

                'instructors' => Instructor::query()
                    ->whereHas('staff', function ($query) use ($schoolId) {
                        $query->where('school_id', $schoolId);
                    })
                    ->count(),

                'classes' => Classes::query()
                    ->where('school_id', $schoolId)
                    ->count(),

                'sections' => Section::query()
                    ->whereHas('class', function ($query) use ($schoolId) {
                        $query->where('school_id', $schoolId);
                    })
                    ->count(),

                'departments' => Department::query()
                    ->where('school_id', $schoolId)
                    ->count(),

                'courses' => Courses::query()
                    ->where('school_id', $schoolId)
                    ->count(),

                'academic_years' => AcademicYears::query()
                    ->where('school_id', $schoolId)
                    ->count(),

                'terms' => Terms::query()
                    ->where('school_id', $schoolId)
                    ->count(),
            ],

            'currentAcademicYear' => $currentAcademicYear,

            'currentTerm' => $currentTerm,

            'recentStudents' => Student::query()
                ->where('school_id', $schoolId)
                ->latest()
                ->take(5)
                ->get(),

            'recentStaff' => Staff::query()
                ->where('school_id', $schoolId)
                ->latest()
                ->take(5)
                ->get(),

            /*
             * Finance Dashboard
             *
             * Finance information is only loaded when the
             * current user has at least one finance viewing
             * permission.
             */
            'financeDashboard' => $this->getFinanceDashboard($schoolId),
        ];
    }

    /**
     * Build finance dashboard information.
     */
    protected function getFinanceDashboard(int $schoolId): ?array
    {
        $user = Auth::user();

        if (!$user) {
            return null;
        }

        $canViewFinance =
            $user->can('fees.view') ||
            $user->can('fee-structures.view') ||
            $user->can('invoices.view') ||
            $user->can('payments.view') ||
            $user->can('payment-refunds.view');

        if (!$canViewFinance) {
            return null;
        }

        /*
         * ---------------------------------------------------------
         * Invoice totals
         * ---------------------------------------------------------
         */

        $invoiceQuery = Invoice::query()
            ->where('school_id', $schoolId);

        $totalInvoiced = (float) $invoiceQuery->sum('total');

        $invoiceCount = (int) $invoiceQuery->count();

        $paidInvoices = (int) (clone $invoiceQuery)
            ->where('status', 'paid')
            ->count();

        $partialInvoices = (int) (clone $invoiceQuery)
            ->where('status', 'partial')
            ->count();

        $unpaidInvoices = (int) (clone $invoiceQuery)
            ->where(function ($query) {
                $query->where('status', 'unpaid')
                    ->orWhere('status', 'pending');
            })
            ->count();

        /*
         * ---------------------------------------------------------
         * Payment totals
         * ---------------------------------------------------------
         *
         * IMPORTANT:
         * We do NOT treat gross payments as the final collection
         * amount because processed refunds reduce effective
         * collection.
         */

        $grossCollected = (float) Payment::query()
            ->where('school_id', $schoolId)
            ->sum('amount');

        $processedRefunded = (float) PaymentRefund::query()
            ->where('school_id', $schoolId)
            ->where('status', 'processed')
            ->sum('amount');

        $effectiveCollected = max(
            $grossCollected - $processedRefunded,
            0
        );

        /*
         * ---------------------------------------------------------
         * Outstanding
         * ---------------------------------------------------------
         *
         * Invoice balances are maintained by the existing
         * invoice/payment/refund workflow.
         */

        $outstanding = (float) Invoice::query()
            ->where('school_id', $schoolId)
            ->sum('balance');

        /*
         * ---------------------------------------------------------
         * Recent payments
         * ---------------------------------------------------------
         */

        $recentPayments = Payment::query()
            ->where('school_id', $schoolId)
            ->with([
                'invoice.student',
                'receivedBy',
            ])
            ->latest('paid_at')
            ->latest('id')
            ->take(5)
            ->get();

        /*
         * ---------------------------------------------------------
         * Recent refunds
         * ---------------------------------------------------------
         */

        $recentRefunds = PaymentRefund::query()
            ->where('school_id', $schoolId)
            ->with([
                'payment',
                'student',
                'requester',
                'approver',
                'processor',
            ])
            ->latest('id')
            ->take(5)
            ->get();

        /*
         * ---------------------------------------------------------
         * Outstanding invoices
         * ---------------------------------------------------------
         */

        $outstandingInvoices = Invoice::query()
            ->where('school_id', $schoolId)
            ->where('balance', '>', 0)
            ->with('student')
            ->orderByDesc('balance')
            ->take(5)
            ->get();

        /*
         * ---------------------------------------------------------
         * Payment method summary
         * ---------------------------------------------------------
         */

        $paymentMethodSummary = Payment::query()
            ->where('school_id', $schoolId)
            ->selectRaw(
                'payment_method, COUNT(*) as payment_count, SUM(amount) as gross_amount'
            )
            ->groupBy('payment_method')
            ->orderByDesc('gross_amount')
            ->get();

        /*
         * ---------------------------------------------------------
         * Refund summary
         * ---------------------------------------------------------
         */

        $refundSummary = [
            'requested' => (float) PaymentRefund::query()
                ->where('school_id', $schoolId)
                ->where('status', 'requested')
                ->sum('amount'),

            'approved' => (float) PaymentRefund::query()
                ->where('school_id', $schoolId)
                ->where('status', 'approved')
                ->sum('amount'),

            'processed' => $processedRefunded,

            'rejected' => (float) PaymentRefund::query()
                ->where('school_id', $schoolId)
                ->where('status', 'rejected')
                ->sum('amount'),

            'cancelled' => (float) PaymentRefund::query()
                ->where('school_id', $schoolId)
                ->where('status', 'cancelled')
                ->sum('amount'),
        ];

        /*
         * ---------------------------------------------------------
         * Collection percentage
         * ---------------------------------------------------------
         */

        $collectionPercentage = $totalInvoiced > 0
            ? round(
                ($effectiveCollected / $totalInvoiced) * 100,
                2
            )
            : 0;

        $collectionPercentage = min(
            max($collectionPercentage, 0),
            100
        );

        return [
            'total_invoiced' => round($totalInvoiced, 2),

            'gross_collected' => round($grossCollected, 2),

            'total_collected' => round($effectiveCollected, 2),

            'outstanding' => round($outstanding, 2),

            'total_refunded' => round($processedRefunded, 2),

            'invoice_count' => $invoiceCount,

            'paid_invoices' => $paidInvoices,

            'partial_invoices' => $partialInvoices,

            'unpaid_invoices' => $unpaidInvoices,

            'collection_percentage' => $collectionPercentage,

            'recent_payments' => $recentPayments,

            'recent_refunds' => $recentRefunds,

            'outstanding_invoices' => $outstandingInvoices,

            'payment_method_summary' => $paymentMethodSummary,

            'refund_summary' => $refundSummary,
        ];
    }

    /**
     * Resolve the current user's school.
     */
    protected function schoolId(): int
    {
        $schoolId = Auth::user()?->school_id;

        if (!$schoolId) {
            abort(
                403,
                'No school is assigned to this user.'
            );
        }

        return (int) $schoolId;
    }
}