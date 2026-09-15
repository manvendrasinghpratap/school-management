<?php

namespace App\Exports\Finance;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OutstandingFeesReportExport implements FromArray, WithHeadings, WithStyles
{
    public function __construct(
        protected Collection $invoices,
        protected float $totalInvoiced,
        protected float $totalPaid,
        protected float $totalOutstanding,
        protected int $totalInvoices,
        protected int $overdueInvoices,
        protected float $overdueAmount,
    ) {
    }

    public function array(): array
    {
        $rows = [];

        foreach ($this->invoices as $invoice) {

            $student = $invoice->student;

            $studentName = $student
                ? trim(
                    ($student->first_name ?? '') . ' ' .
                    ($student->middle_name ?? '') . ' ' .
                    ($student->last_name ?? '')
                )
                : '-';

            $isOverdue = $invoice->due_date
                && $invoice->due_date->lt(now()->startOfDay())
                && (float) $invoice->balance > 0;

            $rows[] = [
                $invoice->invoice_number ?? '-',
                $studentName ?: '-',
                $student->student_number ?? '-',
                $invoice->invoice_date?->format('d M Y') ?? '-',
                $invoice->due_date?->format('d M Y') ?? '-',
                (float) $invoice->total,
                (float) $invoice->paid,
                (float) $invoice->balance,
                ucfirst($invoice->status ?? '-'),
                $isOverdue ? 'Yes' : 'No',
            ];
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'Invoice Number',
            'Student',
            'Student Number',
            'Invoice Date',
            'Due Date',
            'Total',
            'Paid',
            'Outstanding',
            'Status',
            'Overdue',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->getStyle('A1:J1')->getFont()->setBold(true);

        $sheet->freezePane('A2');

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