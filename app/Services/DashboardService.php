<?php

namespace App\Services;

use App\Models\AcademicYears;
use App\Models\Attendance;
use App\Models\Classes;
use App\Models\Courses;
use App\Models\Department;
use App\Models\Examination;
use App\Models\Instructor;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentRefund;
use App\Models\Result;
use App\Models\Section;
use App\Models\Staff;
use App\Models\StaffAttendance;
use App\Models\Student;
use App\Models\StudentEnrollment;
use App\Models\Terms;
use Illuminate\Support\Facades\Auth;

class DashboardService
{
    public function getDashboardData(): array
    {
        $schoolId = $this->schoolId();
        $today = now()->toDateString();

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

            'enrollmentDashboard' => $this->getEnrollmentDashboard(
                $schoolId,
                $currentAcademicYear
            ),

            'attendanceDashboard' => $this->getAttendanceDashboard(
                $schoolId,
                $today
            ),

            'examinationDashboard' => $this->getExaminationDashboard(
                $schoolId,
                $currentAcademicYear,
                $currentTerm,
                $today
            ),

            'alertsTasks' => $this->getAlertsTasks(
                $schoolId,
                $currentAcademicYear,
                $currentTerm,
                $today
            ),

            /*
             * Finance Dashboard
             *
             * Finance information is only loaded when the current
             * user has at least one finance viewing permission.
             */
            'financeDashboard' => $this->getFinanceDashboard($schoolId),
        ];
    }

    /**
     * Build current academic-year enrollment information.
     */
    protected function getEnrollmentDashboard(
        int $schoolId,
        ?AcademicYears $currentAcademicYear
    ): ?array {
        $user = Auth::user();

        if (!$user || !$user->can('enrollments.view')) {
            return null;
        }

        $baseQuery = StudentEnrollment::query()
            ->where('school_id', $schoolId)
            ->where('status', 'active');

        if ($currentAcademicYear) {
            $baseQuery->where(
                'academic_year_id',
                $currentAcademicYear->id
            );
        } else {
            $baseQuery->whereRaw('1 = 0');
        }

        $activeEnrollmentCount = (clone $baseQuery)->count();
        $enrolledStudentCount = (clone $baseQuery)
            ->distinct()
            ->count('student_id');

        $studentsWithoutCurrentEnrollment = 0;

        if ($currentAcademicYear) {
            $enrolledStudentIds = (clone $baseQuery)
                ->select('student_id')
                ->distinct()
                ->pluck('student_id');

            $studentsWithoutCurrentEnrollment = Student::query()
                ->where('school_id', $schoolId)
                ->where('status', 'active')
                ->whereNotIn('id', $enrolledStudentIds)
                ->count();
        }

        $recentEnrollments = (clone $baseQuery)
            ->with(['student', 'class', 'section', 'term'])
            ->latest('enrollment_date')
            ->latest('id')
            ->take(5)
            ->get();

        return [
            'active_enrollments' => $activeEnrollmentCount,
            'enrolled_students' => $enrolledStudentCount,
            'students_without_enrollment' => $studentsWithoutCurrentEnrollment,
            'recent_enrollments' => $recentEnrollments,
        ];
    }

    /**
     * Build today's student and staff attendance information.
     */
    protected function getAttendanceDashboard(
        int $schoolId,
        string $today
    ): ?array {
        $user = Auth::user();

        if (!$user) {
            return null;
        }

        $canViewStudentAttendance = $user->can('attendance.view');
        $canViewStaffAttendance = $user->can('staff-attendance.view');

        if (!$canViewStudentAttendance && !$canViewStaffAttendance) {
            return null;
        }

        $student = null;
        $staff = null;

        if ($canViewStudentAttendance) {
            $query = Attendance::query()
                ->where('school_id', $schoolId)
                ->whereDate('attendance_date', $today);

            $total = (clone $query)->count();

            $student = [
                'total' => $total,
                'present' => (clone $query)
                    ->where('status', 'present')
                    ->count(),
                'absent' => (clone $query)
                    ->where('status', 'absent')
                    ->count(),
                'late' => (clone $query)
                    ->where('status', 'late')
                    ->count(),
                'excused' => (clone $query)
                    ->where('status', 'excused')
                    ->count(),
                'rate' => $total > 0
                    ? round(
                        ((clone $query)->where('status', 'present')->count() / $total) * 100,
                        2
                    )
                    : 0,
                'recorded_students' => (clone $query)
                    ->distinct()
                    ->count('student_id'),
            ];
        }

        if ($canViewStaffAttendance) {
            $query = StaffAttendance::query()
                ->where('school_id', $schoolId)
                ->whereDate('attendance_date', $today);

            $total = (clone $query)->count();

            $staff = [
                'total' => $total,
                'present' => (clone $query)
                    ->where('status', 'present')
                    ->count(),
                'absent' => (clone $query)
                    ->where('status', 'absent')
                    ->count(),
                'late' => (clone $query)
                    ->where('status', 'late')
                    ->count(),
                'half_day' => (clone $query)
                    ->where('status', 'half_day')
                    ->count(),
                'leave' => (clone $query)
                    ->where('status', 'leave')
                    ->count(),
                'rate' => $total > 0
                    ? round(
                        ((clone $query)->where('status', 'present')->count() / $total) * 100,
                        2
                    )
                    : 0,
                'recorded_staff' => (clone $query)
                    ->distinct()
                    ->count('staff_id'),
            ];
        }

        return [
            'date' => $today,
            'student' => $student,
            'staff' => $staff,
        ];
    }

    /**
     * Build current academic examination/result information.
     */
    protected function getExaminationDashboard(
        int $schoolId,
        ?AcademicYears $currentAcademicYear,
        $currentTerm,
        string $today
    ): ?array {
        $user = Auth::user();

        if (!$user) {
            return null;
        }

        $canViewExams = $user->can('examinations.view');
        $canViewResults = $user->can('results.view');

        if (!$canViewExams && !$canViewResults) {
            return null;
        }

        $examinationQuery = Examination::query()
            ->where('school_id', $schoolId);

        if ($currentAcademicYear) {
            $examinationQuery->where(
                'academic_year_id',
                $currentAcademicYear->id
            );
        } else {
            $examinationQuery->whereRaw('1 = 0');
        }

        if ($currentTerm) {
            $examinationQuery->where('term_id', $currentTerm->id);
        }

        $todayExams = (clone $examinationQuery)
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->count();

        $upcomingExams = (clone $examinationQuery)
            ->whereDate('start_date', '>=', $today)
            ->count();

        $examinations = (clone $examinationQuery)
            ->orderBy('start_date')
            ->orderBy('id')
            ->take(5)
            ->get();

        $results = null;

        if ($canViewResults) {
            $resultQuery = Result::query()
                ->whereHas('student', function ($query) use ($schoolId) {
                    $query->where('school_id', $schoolId);
                })
                ->whereHas('examination', function ($query) use ($schoolId, $currentAcademicYear, $currentTerm) {
                    $query->where('school_id', $schoolId);

                    if ($currentAcademicYear) {
                        $query->where(
                            'academic_year_id',
                            $currentAcademicYear->id
                        );
                    } else {
                        $query->whereRaw('1 = 0');
                    }

                    if ($currentTerm) {
                        $query->where('term_id', $currentTerm->id);
                    }
                });

            $results = [
                'total' => (clone $resultQuery)->count(),
                'draft' => (clone $resultQuery)
                    ->where('status', 'draft')
                    ->count(),
                'approved' => (clone $resultQuery)
                    ->where('status', 'approved')
                    ->count(),
                'published' => (clone $resultQuery)
                    ->where('status', 'published')
                    ->count(),
                'recent' => (clone $resultQuery)
                    ->with(['student', 'examination'])
                    ->latest('id')
                    ->take(5)
                    ->get(),
            ];
        }

        return [
            'examination_count' => (clone $examinationQuery)->count(),
            'today_count' => $todayExams,
            'upcoming_count' => $upcomingExams,
            'examinations' => $examinations,
            'results' => $results,
        ];
    }

    /**
     * Build actionable dashboard alerts/tasks.
     */
    protected function getAlertsTasks(
        int $schoolId,
        ?AcademicYears $currentAcademicYear,
        $currentTerm,
        string $today
    ): array {
        $user = Auth::user();
        $items = [];

        if (!$user) {
            return $items;
        }

        if ($user->can('attendance.view')) {
            $studentAttendanceCount = Attendance::query()
                ->where('school_id', $schoolId)
                ->whereDate('attendance_date', $today)
                ->count();

            if ($studentAttendanceCount === 0) {
                $items[] = [
                    'type' => 'warning',
                    'icon' => 'bx-calendar-x',
                    'title' => 'Student attendance not recorded',
                    'message' => 'No student attendance records have been recorded for today.',
                    'route' => 'admin.attendance.index',
                    'permission' => 'attendance.view',
                    'action' => 'Open Attendance',
                ];
            }
        }

        if ($user->can('staff-attendance.view')) {
            $staffAttendanceCount = StaffAttendance::query()
                ->where('school_id', $schoolId)
                ->whereDate('attendance_date', $today)
                ->count();

            if ($staffAttendanceCount === 0) {
                $items[] = [
                    'type' => 'warning',
                    'icon' => 'bx-user-x',
                    'title' => 'Staff attendance not recorded',
                    'message' => 'No staff attendance records have been recorded for today.',
                    'route' => 'admin.staff-attendance.index',
                    'permission' => 'staff-attendance.view',
                    'action' => 'Open Staff Attendance',
                ];
            }
        }

        if ($user->can('enrollments.view') && $currentAcademicYear) {
            $enrolledStudentIds = StudentEnrollment::query()
                ->where('school_id', $schoolId)
                ->where('academic_year_id', $currentAcademicYear->id)
                ->where('status', 'active')
                ->distinct()
                ->pluck('student_id');

            $withoutEnrollment = Student::query()
                ->where('school_id', $schoolId)
                ->where('status', 'active')
                ->whereNotIn('id', $enrolledStudentIds)
                ->count();

            if ($withoutEnrollment > 0) {
                $items[] = [
                    'type' => 'info',
                    'icon' => 'bx-user-plus',
                    'title' => 'Students without current enrollment',
                    'message' => number_format($withoutEnrollment)
                        . ' active student'
                        . ($withoutEnrollment === 1 ? '' : 's')
                        . ' do not have an active enrollment in the current academic year.',
                    'route' => 'admin.student-enrollments.index',
                    'permission' => 'enrollments.view',
                    'action' => 'Review Enrollments',
                ];
            }
        }

        if ($user->can('results.view')) {
            $pendingResults = Result::query()
                ->where('status', 'draft')
                ->whereHas('student', function ($query) use ($schoolId) {
                    $query->where('school_id', $schoolId);
                })
                ->whereHas('examination', function ($query) use ($schoolId, $currentAcademicYear, $currentTerm) {
                    $query->where('school_id', $schoolId);

                    if ($currentAcademicYear) {
                        $query->where(
                            'academic_year_id',
                            $currentAcademicYear->id
                        );
                    } else {
                        $query->whereRaw('1 = 0');
                    }

                    if ($currentTerm) {
                        $query->where('term_id', $currentTerm->id);
                    }
                })
                ->count();

            if ($pendingResults > 0) {
                $items[] = [
                    'type' => 'primary',
                    'icon' => 'bx-spreadsheet',
                    'title' => 'Results awaiting review',
                    'message' => number_format($pendingResults)
                        . ' result'
                        . ($pendingResults === 1 ? '' : 's')
                        . ' currently have Draft status.',
                    'route' => 'admin.results.index',
                    'permission' => 'results.view',
                    'action' => 'Review Results',
                ];
            }
        }

        if ($user->can('examinations.view') && $currentAcademicYear) {
            $upcomingExam = Examination::query()
                ->where('school_id', $schoolId)
                ->where('academic_year_id', $currentAcademicYear->id)
                ->when($currentTerm, function ($query) use ($currentTerm) {
                    $query->where('term_id', $currentTerm->id);
                })
                ->whereDate('start_date', '>=', $today)
                ->orderBy('start_date')
                ->first();

            if ($upcomingExam) {
                $startDate = $upcomingExam->start_date?->format('d M Y');

                $items[] = [
                    'type' => 'success',
                    'icon' => 'bx-calendar-event',
                    'title' => 'Upcoming examination',
                    'message' => $upcomingExam->name
                        . ($startDate ? ' starts ' . $startDate . '.' : '.'),
                    'route' => 'admin.examinations.show',
                    'route_parameter' => $upcomingExam,
                    'permission' => 'examinations.view',
                    'action' => 'View Examination',
                ];
            }
        }

        if ($user->can('invoices.view')) {
            $outstandingInvoiceCount = Invoice::query()
                ->where('school_id', $schoolId)
                ->where('balance', '>', 0)
                ->count();

            if ($outstandingInvoiceCount > 0) {
                $items[] = [
                    'type' => 'danger',
                    'icon' => 'bx-receipt',
                    'title' => 'Outstanding invoices',
                    'message' => number_format($outstandingInvoiceCount)
                        . ' invoice'
                        . ($outstandingInvoiceCount === 1 ? '' : 's')
                        . ' have an outstanding balance.',
                    'route' => 'admin.invoices.index',
                    'permission' => 'invoices.view',
                    'action' => 'View Invoices',
                ];
            }
        }

        if ($user->can('payment-refunds.view')) {
            $pendingRefundCount = PaymentRefund::query()
                ->where('school_id', $schoolId)
                ->whereIn('status', ['requested', 'approved'])
                ->count();

            if ($pendingRefundCount > 0) {
                $items[] = [
                    'type' => 'warning',
                    'icon' => 'bx-undo',
                    'title' => 'Refunds require attention',
                    'message' => number_format($pendingRefundCount)
                        . ' refund request'
                        . ($pendingRefundCount === 1 ? '' : 's')
                        . ' are requested or approved.',
                    'route' => 'admin.payment-refunds.index',
                    'permission' => 'payment-refunds.view',
                    'action' => 'Review Refunds',
                ];
            }
        }

        return $items;
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

        $outstanding = (float) Invoice::query()
            ->where('school_id', $schoolId)
            ->sum('balance');

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

        $outstandingInvoices = Invoice::query()
            ->where('school_id', $schoolId)
            ->where('balance', '>', 0)
            ->with('student')
            ->orderByDesc('balance')
            ->take(5)
            ->get();

        $paymentMethodSummary = Payment::query()
            ->where('school_id', $schoolId)
            ->selectRaw(
                'payment_method, COUNT(*) as payment_count, SUM(amount) as gross_amount'
            )
            ->groupBy('payment_method')
            ->orderByDesc('gross_amount')
            ->get();

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
