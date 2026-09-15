<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">

    <title>Collection Report</title>

    <style>
        @page {
            margin: 25px 20px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #222;
        }

        h1 {
            font-size: 20px;
            margin: 0 0 5px 0;
        }

        h2 {
            font-size: 13px;
            margin: 18px 0 8px 0;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header p {
            margin: 3px 0;
        }

        .summary {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .summary td {
            border: 1px solid #ccc;
            padding: 8px;
            width: 25%;
        }

        .label {
            font-size: 9px;
            color: #666;
        }

        .value {
            font-size: 14px;
            font-weight: bold;
            margin-top: 3px;
        }

        table.report {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        table.report th {
            background: #eeeeee;
            font-weight: bold;
            text-align: left;
        }

        table.report th,
        table.report td {
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

        .refund {
            background: #fff3f3;
        }

        .negative {
            color: #b00020;
        }

        .footer {
            margin-top: 20px;
            font-size: 8px;
            color: #777;
            text-align: center;
        }
    </style>
</head>

<body>

{{-- =========================================================
     HEADER
========================================================= --}}

<div class="header">

    <h1>Collection Report</h1>

    <p>
        Reporting Period:
        <strong>
            {{ \Carbon\Carbon::parse($from)->format('d M Y') }}
            -
            {{ \Carbon\Carbon::parse($to)->format('d M Y') }}
        </strong>
    </p>

</div>


{{-- =========================================================
     SUMMARY
========================================================= --}}

<table class="summary">

    <tr>

        <td>
            <div class="label">Total Invoiced</div>

            <div class="value">
                ₹{{ number_format((float) $totalInvoiced, 2) }}
            </div>
        </td>

        <td>
            <div class="label">Gross Collected</div>

            <div class="value">
                ₹{{ number_format((float) $grossCollected, 2) }}
            </div>
        </td>

        <td>
            <div class="label">Total Refunded</div>

            <div class="value">
                ₹{{ number_format((float) $totalRefunded, 2) }}
            </div>
        </td>

        <td>
            <div class="label">Effective Collection</div>

            <div class="value">
                ₹{{ number_format((float) $effectiveCollection, 2) }}
            </div>
        </td>

    </tr>

    <tr>

        <td>
            <div class="label">Outstanding</div>

            <div class="value">
                ₹{{ number_format((float) $outstanding, 2) }}
            </div>
        </td>

        <td>
            <div class="label">Collection Rate</div>

            <div class="value">
                {{ number_format((float) $collectionPercentage, 2) }}%
            </div>
        </td>

        <td>
            <div class="label">Invoice Count</div>

            <div class="value">
                {{ $invoiceCount }}
            </div>
        </td>

        <td>
            <div class="label">Payment Count</div>

            <div class="value">
                {{ $paymentCount }}
            </div>
        </td>

    </tr>

</table>


{{-- =========================================================
     PAYMENT METHOD SUMMARY
========================================================= --}}

@if(isset($paymentMethods) && $paymentMethods->count())

    <h2>Payment Method Summary</h2>

    <table class="report">

        <thead>

            <tr>
                <th>Payment Method</th>

                <th class="text-right">
                    Transactions
                </th>

                <th class="text-right">
                    Amount
                </th>
            </tr>

        </thead>

        <tbody>

        @foreach($paymentMethods as $method)

            <tr>

                <td>
                    {{ ucfirst(str_replace('_', ' ', $method->payment_method)) }}
                </td>

                <td class="text-right">
                    {{ $method->payment_count }}
                </td>

                <td class="text-right">
                    ₹{{ number_format((float) $method->amount, 2) }}
                </td>

            </tr>

        @endforeach

        </tbody>

    </table>

@endif


{{-- =========================================================
     PAYMENTS
========================================================= --}}

<h2>Payments</h2>

<table class="report">

    <thead>

        <tr>
            <th>#</th>
            <th>Payment Date</th>
            <th>Invoice</th>
            <th>Student</th>
            <th>Payment Method</th>
            <th>Reference</th>
            <th>Received By</th>
            <th class="text-right">Amount</th>
        </tr>

    </thead>

    <tbody>

    @forelse($payments as $index => $payment)

        <tr>

            <td>
                {{ $index + 1 }}
            </td>

            <td>
                {{ optional($payment->paid_at)->format('d M Y H:i') }}
            </td>

            <td>
                {{ $payment->invoice->invoice_number ?? '-' }}
            </td>

            <td>

                @if($payment->invoice && $payment->invoice->student)

                    @php
                        $student = $payment->invoice->student;

                        $studentName = trim(
                            ($student->first_name ?? '') . ' ' .
                            ($student->middle_name ?? '') . ' ' .
                            ($student->last_name ?? '')
                        );
                    @endphp

                    {{ $studentName ?: '-' }}

                @else

                    -

                @endif

            </td>

            <td>
                {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
            </td>

            <td>
                {{ $payment->transaction_reference ?? '-' }}
            </td>

            <td>
                {{ $payment->receivedBy->name ?? '-' }}
            </td>

            <td class="text-right">
                ₹{{ number_format((float) $payment->amount, 2) }}
            </td>

        </tr>

    @empty

        <tr>

            <td colspan="8" class="text-center">
                No payments found for the selected period.
            </td>

        </tr>

    @endforelse

    </tbody>

</table>


{{-- =========================================================
     PROCESSED REFUNDS
========================================================= --}}

<h2>Processed Refunds</h2>

<table class="report">

    <thead>

        <tr>
            <th>#</th>
            <th>Refund Date</th>
            <th>Payment ID</th>
            <th>Student</th>
            <th>Reference</th>
            <th>Processed By</th>
            <th>Reason</th>
            <th class="text-right">Refund Amount</th>
        </tr>

    </thead>

    <tbody>

    @forelse($refunds as $index => $refund)

        <tr class="refund">

            <td>
                {{ $index + 1 }}
            </td>

            <td>
                {{ optional($refund->processed_at)->format('d M Y H:i') }}
            </td>

            <td>
                #{{ $refund->payment_id }}
            </td>

            <td>

                @if($refund->student)

                    @php
                        $student = $refund->student;

                        $studentName = trim(
                            ($student->first_name ?? '') . ' ' .
                            ($student->middle_name ?? '') . ' ' .
                            ($student->last_name ?? '')
                        );
                    @endphp

                    {{ $studentName ?: '-' }}

                @else

                    -

                @endif

            </td>

            <td>
                {{ $refund->reference ?? '-' }}
            </td>

            <td>
                {{ $refund->processor->name ?? '-' }}
            </td>

            <td>
                {{ $refund->reason ?? '-' }}
            </td>

            <td class="text-right negative">
                -₹{{ number_format((float) $refund->amount, 2) }}
            </td>

        </tr>

    @empty

        <tr>

            <td colspan="8" class="text-center">
                No processed refunds found for the selected period.
            </td>

        </tr>

    @endforelse

    </tbody>

</table>


{{-- =========================================================
     FOOTER
========================================================= --}}

<div class="footer">

    Collection Report generated on
    {{ now()->format('d M Y H:i') }}

</div>

</body>
</html>