<?php

namespace App\Exports\Finance;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PaymentReportExport implements FromArray, WithHeadings, WithStyles
{
    public function __construct(
        protected Collection $payments,
        protected float $grossPayments,
        protected float $processedRefunds,
        protected float $effectiveCollection,
        protected int $paymentCount,
        protected int $refundCount,
    ) {}

    public function array(): array
    {
        $rows = [];

        foreach ($this->payments as $payment) {

            $student = $payment->invoice?->student;

            $studentName = $student
                ? trim(
                    ($student->first_name ?? '') . ' ' .
                    ($student->middle_name ?? '') . ' ' .
                    ($student->last_name ?? '')
                )
                : '-';

            $rows[] = [
                $payment->paid_at?->format('d M Y H:i') ?? '-',
                $payment->invoice?->invoice_number ?? '-',
                $studentName ?: '-',
                $student->student_number ?? '-',
                ucfirst(
                    str_replace(
                        '_',
                        ' ',
                        $payment->payment_method ?? '-'
                    )
                ),
                $payment->transaction_reference ?? '-',
                (float) $payment->amount,
                $payment->receivedBy?->name ?? '-',
                $payment->notes ?? '-',
            ];
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'Payment Date',
            'Invoice Number',
            'Student',
            'Student Number',
            'Payment Method',
            'Transaction Reference',
            'Amount',
            'Received By',
            'Notes',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->getStyle('A1:I1')
            ->getFont()
            ->setBold(true);

        $sheet->freezePane('A2');

        foreach (range('A', 'I') as $column) {
            $sheet->getColumnDimension($column)
                ->setAutoSize(true);
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