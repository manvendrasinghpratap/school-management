<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">

    <title>Refund Report</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #222;
        }

        h1 {
            font-size: 18px;
            margin-bottom: 5px;
        }

        h2 {
            font-size: 13px;
            margin-top: 20px;
        }

        .meta {
            margin-bottom: 15px;
            color: #555;
        }

        .summary {
            width: 100%;
            margin-bottom: 20px;
        }

        .summary td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        .summary-title {
            font-weight: bold;
            font-size: 9px;
        }

        .summary-value {
            font-size: 13px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f1f1f1;
            font-weight: bold;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 5px;
            vertical-align: top;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            margin-top: 20px;
            font-size: 8px;
            color: #777;
        }
    </style>
</head>

<body>

    <h1>Refund Report</h1>

    <div class="meta">
        <strong>Report Period:</strong>
        {{ $from }} to {{ $to }}

        @if($status)
            &nbsp; | &nbsp;
            <strong>Status:</strong>
            {{ ucfirst($status) }}
        @endif

        @if($paymentMethod)
            &nbsp; | &nbsp;
            <strong>Payment Method:</strong>
            {{ ucfirst($paymentMethod) }}
        @endif

        @if($studentSearch)
            &nbsp; | &nbsp;
            <strong>Student:</strong>
            {{ $studentSearch }}
        @endif
    </div>

    <table class="summary">
        <tr>
            <td>
                <div class="summary-title">TOTAL REFUNDS</div>
                <div class="summary-value">
                    {{ $totalRefunds }}
                </div>
            </td>

            <td>
                <div class="summary-title">TOTAL AMOUNT</div>
                <div class="summary-value">
                    ₹{{ number_format($totalAmount, 2) }}
                </div>
            </td>

            <td>
                <div class="summary-title">PROCESSED</div>
                <div class="summary-value">
                    ₹{{ number_format($processedAmount, 2) }}
                </div>
            </td>

            <td>
                <div class="summary-title">REQUESTED</div>
                <div class="summary-value">
                    ₹{{ number_format($requestedAmount, 2) }}
                </div>
            </td>

            <td>
                <div class="summary-title">APPROVED</div>
                <div class="summary-value">
                    ₹{{ number_format($approvedAmount, 2) }}
                </div>
            </td>

            <td>
                <div class="summary-title">REJECTED</div>
                <div class="summary-value">
                    ₹{{ number_format($rejectedAmount, 2) }}
                </div>
            </td>

            <td>
                <div class="summary-title">CANCELLED</div>
                <div class="summary-value">
                    ₹{{ number_format($cancelledAmount, 2) }}
                </div>
            </td>
        </tr>
    </table>

    <h2>Refund Transactions</h2>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Invoice</th>
                <th>Student</th>
                <th>Student No.</th>
                <th>Payment Method</th>
                <th>Transaction Reference</th>
                <th class="text-right">Amount</th>
                <th>Status</th>
                <th>Reason</th>
                <th>Requested By</th>
                <th>Approved By</th>
                <th>Processed By</th>
            </tr>
        </thead>

        <tbody>
            @forelse($refunds as $refund)

                @php
                    $studentName = $refund->student
                        ? trim(
                            $refund->student->first_name . ' ' .
                            ($refund->student->middle_name ?? '') . ' ' .
                            ($refund->student->last_name ?? '')
                        )
                        : '-';
                @endphp

                <tr>
                    <td>
                        {{
                            optional(
                                $refund->processed_at
                                ?? $refund->requested_at
                            )->format('Y-m-d H:i')
                        }}
                    </td>

                    <td>
                        {{ $refund->payment?->invoice?->invoice_number ?? '-' }}
                    </td>

                    <td>
                        {{ $studentName }}
                    </td>

                    <td>
                        {{ $refund->student?->student_number ?? '-' }}
                    </td>

                    <td>
                        {{ ucfirst($refund->payment?->payment_method ?? '-') }}
                    </td>

                    <td>
                        {{ $refund->payment?->transaction_reference ?? '-' }}
                    </td>

                    <td class="text-right">
                        ₹{{ number_format((float) $refund->amount, 2) }}
                    </td>

                    <td>
                        {{ ucfirst($refund->status) }}
                    </td>

                    <td>
                        {{ $refund->reason ?? '-' }}
                    </td>

                    <td>
                        {{ $refund->requester?->name ?? '-' }}
                    </td>

                    <td>
                        {{ $refund->approver?->name ?? '-' }}
                    </td>

                    <td>
                        {{ $refund->processor?->name ?? '-' }}
                    </td>
                </tr>

            @empty

                <tr>
                    <td colspan="12" class="text-center">
                        No refund transactions found.
                    </td>
                </tr>

            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Generated on {{ now()->format('Y-m-d H:i:s') }}
    </div>

</body>
</html>