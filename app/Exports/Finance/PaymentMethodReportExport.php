<?php

namespace App\Exports\Finance;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PaymentMethodReportExport implements FromArray, WithHeadings, WithStyles
{
    protected Collection $methodRows;

    public function __construct(Collection $methodRows)
    {
        $this->methodRows = $methodRows;
    }

    public function array(): array
    {
        return $this->methodRows->map(function ($row) {
            return [
                $row->payment_method,
                $row->payment_count,
                $row->gross_amount,
                $row->refunded_amount,
                $row->effective_amount,
                $row->percentage . '%',
            ];
        })->toArray();
    }

    public function headings(): array
    {
        return [
            'Payment Method',
            'Payments',
            'Gross Amount',
            'Refunded Amount',
            'Effective Amount',
            'Collection %',
        ];
    }

    public function styles(Worksheet $sheet): ?array
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                ],
            ],
        ];
    }
}