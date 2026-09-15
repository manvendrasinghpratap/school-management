<!DOCTYPE html>
<html>
<head>

    <meta charset="UTF-8">

    <title>Payment Method Report</title>

    <style>

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #222;
        }

        h2 {
            margin-bottom: 5px;
        }

        .period {
            margin-bottom: 15px;
            color: #555;
        }

        .summary {
            width: 100%;
            margin-bottom: 20px;
        }

        .summary td {
            width: 25%;
            padding: 10px;
            border: 1px solid #ddd;
        }

        .label {
            font-size: 9px;
            color: #666;
            margin-bottom: 4px;
        }

        .value {
            font-size: 15px;
            font-weight: bold;
        }

        table.report {
            width: 100%;
            border-collapse: collapse;
        }

        table.report th,
        table.report td {
            border: 1px solid #ccc;
            padding: 7px;
        }

        table.report th {
            background: #f2f2f2;
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
            color: #777;
        }

    </style>

</head>

<body>

    <h2>Payment Method Report</h2>

    <div class="period">

        Report Period:
        {{ \Carbon\Carbon::parse($from)->format('d M Y') }}
        -
        {{ \Carbon\Carbon::parse($to)->format('d M Y') }}

        @if($paymentMethod !== '')
            <br>
            Payment Method:
            {{ ucwords(str_replace('_', ' ', $paymentMethod)) }}
        @endif

        @if($studentSearch !== '')
            <br>
            Student:
            {{ $studentSearch }}
        @endif

    </div>


    {{-- Summary --}}

    <table class="summary">

        <tr>

            <td>
                <div class="label">
                    Total Payments
                </div>

                <div class="value">
                    {{ number_format($totalPayments) }}
                </div>
            </td>

            <td>
                <div class="label">
                    Gross Collection
                </div>

                <div class="value">
                    ₹{{ number_format($grossCollection, 2) }}
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


    {{-- Payment Method Table --}}

    <table class="report">

        <thead>

            <tr>

                <th>
                    Payment Method
                </th>

                <th class="text-center">
                    Payments
                </th>

                <th class="text-right">
                    Gross Amount
                </th>

                <th class="text-right">
                    Refunded Amount
                </th>

                <th class="text-right">
                    Effective Amount
                </th>

                <th class="text-right">
                    Collection %
                </th>

            </tr>

        </thead>

        <tbody>

            @forelse($methodRows as $row)

                <tr>

                    <td>
                        {{ ucwords(str_replace('_', ' ', $row->payment_method)) }}
                    </td>

                    <td class="text-center">
                        {{ number_format($row->payment_count) }}
                    </td>

                    <td class="text-right">
                        ₹{{ number_format($row->gross_amount, 2) }}
                    </td>

                    <td class="text-right">
                        ₹{{ number_format($row->refunded_amount, 2) }}
                    </td>

                    <td class="text-right">
                        ₹{{ number_format($row->effective_amount, 2) }}
                    </td>

                    <td class="text-right">
                        {{ number_format($row->percentage, 2) }}%
                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="6" class="text-center">
                        No payment records found.
                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>


    <div class="footer">

        Generated:
        {{ now()->format('d M Y H:i:s') }}

    </div>

</body>
</html>