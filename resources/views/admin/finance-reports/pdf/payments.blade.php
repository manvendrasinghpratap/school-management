<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">

    <title>Payment Report</title>

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

        .muted {
            color: #666;
        }

        .summary {
            width: 100%;
            margin-top: 15px;
            margin-bottom: 20px;
        }

        .summary td {
            width: 25%;
            padding: 8px;
            border: 1px solid #ddd;
        }

        .label {
            font-size: 9px;
            color: #666;
        }

        .value {
            font-size: 13px;
            font-weight: bold;
            margin-top: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 5px;
            vertical-align: top;
        }

        th {
            background: #f1f1f1;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            margin-top: 20px;
            font-size: 9px;
            color: #666;
        }
    </style>
</head>

<body>

    <h1>Payment Report</h1>

    <div class="muted">
        Report Period:
        {{ \Carbon\Carbon::parse($from)->format('d M Y') }}
        -
        {{ \Carbon\Carbon::parse($to)->format('d M Y') }}
    </div>

    @if($paymentMethod !== '')
        <div class="muted">
            Payment Method:
            {{ ucfirst(str_replace('_', ' ', $paymentMethod)) }}
        </div>
    @endif

    @if($studentSearch !== '')
        <div class="muted">
            Student Search:
            {{ $studentSearch }}
        </div>
    @endif


    {{-- Summary --}}
    <table class="summary">

        <tr>

            <td>
                <div class="label">
                    Total Payments
                </div>

                <div class="value">
                    {{ number_format($paymentCount) }}
                </div>
            </td>

            <td>
                <div class="label">
                    Gross Payments
                </div>

                <div class="value">
                    ₹{{ number_format($grossPayments, 2) }}
                </div>
            </td>

            <td>
                <div class="label">
                    Processed Refunds
                </div>

                <div class="value">
                    ₹{{ number_format($processedRefunds, 2) }}
                </div>
            </td>

            <td>
                <div class="label">
                    Effective Collection
                </div>

                <div class="value">
                    ₹{{ number_format($effectiveCollection, 2) }}
                </div>
            </td>

        </tr>

    </table>


    {{-- Payment Methods --}}
    <h2>
        Payment Methods
    </h2>

    <table>

        <thead>
            <tr>
                <th>Payment Method</th>
                <th class="text-center">Payments</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>

        <tbody>

            @forelse($paymentMethods as $method)

                <tr>

                    <td>
                        {{ ucfirst(
                            str_replace(
                                '_',
                                ' ',
                                $method->payment_method ?? '-'
                            )
                        ) }}
                    </td>

                    <td class="text-center">
                        {{ number_format($method->payment_count) }}
                    </td>

                    <td class="text-right">
                        ₹{{ number_format(
                            (float) $method->amount,
                            2
                        ) }}
                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="3" class="text-center">
                        No payment methods found.
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>


    {{-- Payment Transactions --}}
    <h2>
        Payment Transactions
    </h2>

    <table>

        <thead>

            <tr>
                <th>Date</th>
                <th>Invoice</th>
                <th>Student</th>
                <th>Student No.</th>
                <th>Method</th>
                <th>Reference</th>
                <th class="text-right">Amount</th>
                <th>Received By</th>
            </tr>

        </thead>

        <tbody>

            @forelse($payments as $payment)

                @php
                    $student = $payment->invoice?->student;

                    $studentName = $student
                        ? trim(
                            ($student->first_name ?? '') . ' ' .
                            ($student->middle_name ?? '') . ' ' .
                            ($student->last_name ?? '')
                        )
                        : '-';
                @endphp

                <tr>

                    <td>
                        {{ $payment->paid_at?->format('d M Y H:i') ?? '-' }}
                    </td>

                    <td>
                        {{ $payment->invoice?->invoice_number ?? '-' }}
                    </td>

                    <td>
                        {{ $studentName ?: '-' }}
                    </td>

                    <td>
                        {{ $student->student_number ?? '-' }}
                    </td>

                    <td>
                        {{ ucfirst(
                            str_replace(
                                '_',
                                ' ',
                                $payment->payment_method ?? '-'
                            )
                        ) }}
                    </td>

                    <td>
                        {{ $payment->transaction_reference ?? '-' }}
                    </td>

                    <td class="text-right">
                        ₹{{ number_format(
                            (float) $payment->amount,
                            2
                        ) }}
                    </td>

                    <td>
                        {{ $payment->receivedBy?->name ?? '-' }}
                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="8" class="text-center">
                        No payment transactions found.
                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>


    <div class="footer">
        Generated on {{ now()->format('d M Y H:i') }}
    </div>

</body>
</html>