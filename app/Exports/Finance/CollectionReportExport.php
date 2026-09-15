<?php

namespace App\Exports\Finance;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentRefund;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CollectionReportExport implements FromArray, WithHeadings, WithStyles
{
    public function __construct(
        protected int $schoolId,
        protected string $from,
        protected string $to,
    ) {
    }

    public function headings(): array
    {
        return [
            'Date',
            'Type',
            'Student',
            'Student Number',
            'Invoice',
            'Payment Method',
            'Reference',
            'Received By',
            'Reason',
            'Amount',
        ];
    }

    public function array(): array
    {
        $rows = [];

        /*
         * ---------------------------------------------------------
         * Payments
         * ---------------------------------------------------------
         */

        $payments = Payment::query()
            ->where('school_id', $this->schoolId)
            ->whereDate('paid_at', '>=', $this->from)
            ->whereDate('paid_at', '<=', $this->to)
            ->with([
                'invoice.student',
                'receivedBy',
            ])
            ->latest('paid_at')
            ->latest('id')
            ->get();

        foreach ($payments as $payment) {
            $student = $payment->invoice?->student;

            $rows[] = [
                $payment->paid_at?->format('Y-m-d'),
                'Payment',
                $student
                    ? trim($student->first_name . ' ' . $student->last_name)
                    : '',
                $student?->student_number ?? '',
                $payment->invoice?->invoice_number ?? '',
                $this->formatPaymentMethod($payment->payment_method),
                $payment->transaction_reference ?? '',
                $payment->receivedBy?->name ?? '',
                '',
                (float) $payment->amount,
            ];
        }

        /*
         * ---------------------------------------------------------
         * Processed refunds
         * ---------------------------------------------------------
         *
         * Same accounting rule as the existing report:
         * only processed refunds affect effective collection.
         */

        $refunds = PaymentRefund::query()
            ->where('school_id', $this->schoolId)
            ->where('status', 'processed')
            ->whereDate('processed_at', '>=', $this->from)
            ->whereDate('processed_at', '<=', $this->to)
            ->with([
                'payment.invoice.student',
                'student',
                'processor',
            ])
            ->latest('processed_at')
            ->latest('id')
            ->get();

        foreach ($refunds as $refund) {
            $student = $refund->student
                ?? $refund->payment?->invoice?->student;

            $rows[] = [
                $refund->processed_at?->format('Y-m-d'),
                'Processed Refund',
                $student
                    ? trim($student->first_name . ' ' . $student->last_name)
                    : '',
                $student?->student_number ?? '',
                $refund->payment?->invoice?->invoice_number ?? '',
                $this->formatPaymentMethod(
                    $refund->payment?->payment_method
                ),
                $refund->reference ?? '',
                $refund->processor?->name ?? '',
                $refund->reason ?? '',
                -(float) $refund->amount,
            ];
        }

        /*
         * Sort combined transactions newest first.
         */

        usort($rows, function (array $a, array $b) {
            return strcmp($b[0] ?? '', $a[0] ?? '');
        });

        return $rows;
    }

    protected function formatPaymentMethod(?string $method): string
    {
        if (!$method) {
            return '';
        }

        return ucfirst(
            str_replace('_', ' ', $method)
        );
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->freezePane('A2');

        $sheet->getStyle('A1:J1')->getFont()->setBold(true);

        $sheet->getStyle('J:J')
            ->getNumberFormat()
            ->setFormatCode('#,##0.00');

        foreach (range('A', 'J') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        return [
            1 => [
                'font' => [
                    'bold' => true,
                ],
            ],
        ];
    }
}