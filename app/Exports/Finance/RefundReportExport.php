<?php

namespace App\Exports\Finance;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RefundReportExport implements FromArray, WithHeadings, WithStyles
{
    protected Collection $refunds;

    public function __construct(Collection $refunds)
    {
        $this->refunds = $refunds;
    }

    public function array(): array
    {
        return $this->refunds->map(function ($refund) {
            return [
                optional($refund->processed_at ?? $refund->requested_at)->format('Y-m-d H:i:s'),

                $refund->payment?->invoice?->invoice_number ?? '-',

                $refund->student?->first_name
                    ? trim(
                        $refund->student->first_name . ' ' .
                        ($refund->student->middle_name ?? '') . ' ' .
                        ($refund->student->last_name ?? '')
                    )
                    : '-',

                $refund->student?->student_number ?? '-',

                $refund->payment?->payment_method ?? '-',

                $refund->payment?->transaction_reference ?? '-',

                $refund->amount,

                ucfirst($refund->status),

                $refund->reason ?? '-',

                $refund->requester?->name ?? '-',

                $refund->approver?->name ?? '-',

                $refund->processor?->name ?? '-',
            ];
        })->toArray();
    }

    public function headings(): array
    {
        return [
            'Refund Date',
            'Invoice Number',
            'Student',
            'Student Number',
            'Payment Method',
            'Transaction Reference',
            'Refund Amount',
            'Status',
            'Reason',
            'Requested By',
            'Approved By',
            'Processed By',
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