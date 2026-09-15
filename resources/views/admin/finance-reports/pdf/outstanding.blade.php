<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">

    <title>Outstanding Fees Report</title>

    <style>
        @page {
            margin: 25px 20px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #222;
        }

        h1 {
            font-size: 20px;
            margin: 0 0 5px;
        }

        h2 {
            font-size: 13px;
            margin: 18px 0 8px;
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
            font-size: 8px;
            color: #666;
        }

        .value {
            font-size: 13px;
            font-weight: bold;
            margin-top: 3px;
        }

        table.report {
            width: 100%;
            border-collapse: collapse;
        }

        table.report th {
            background: #eeeeee;
            font-weight: bold;
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

        .overdue {
            font-weight: bold;
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

<div class="header">

    <h1>Outstanding Fees Report</h1>

    <p>
        @if($from || $to)

            Reporting Period:
            <strong>
                {{ $from ? \Carbon\Carbon::parse($from)->format('d M Y') : 'Beginning' }}
                -
                {{ $to ? \Carbon\Carbon::parse($to)->format('d M Y') : 'Present' }}
            </strong>

        @else

            <strong>All Invoice Dates</strong>

        @endif
    </p>

    @if($status !== 'all')
        <p>
            Status:
            <strong>{{ ucfirst($status) }}</strong>
        </p>
    @endif

    @if($studentSearch !== '')
        <p>
            Student Search:
            <strong>{{ $studentSearch }}</strong>
        </p>
    @endif

    @if($overdue)
        <p>
            <strong>Overdue invoices only</strong>
        </p>
    @endif

</div>


{{-- SUMMARY --}}

<table class="summary">

    <tr>

        <td>
            <div class="label">Outstanding Invoices</div>

            <div class="value">
                {{ $totalInvoices }}
            </div>
        </td>

        <td>
            <div class="label">Total Invoiced</div>

            <div class="value">
                ₹{{ number_format((float) $totalInvoiced, 2) }}
            </div>
        </td>

        <td>
            <div class="label">Total Paid</div>

            <div class="value">
                ₹{{ number_format((float) $totalPaid, 2) }}
            </div>
        </td>

        <td>
            <div class="label">Total Outstanding</div>

            <div class="value">
                ₹{{ number_format((float) $totalOutstanding, 2) }}
            </div>
        </td>

    </tr>

    <tr>

        <td>
            <div class="label">Overdue Invoices</div>

            <div class="value">
                {{ $overdueInvoices }}
            </div>
        </td>

        <td>
            <div class="label">Overdue Amount</div>

            <div class="value">
                ₹{{ number_format((float) $overdueAmount, 2) }}
            </div>
        </td>

        <td colspan="2">
            <div class="label">Report Filter</div>

            <div class="value">
                {{ $status === 'all' ? 'All Outstanding' : ucfirst($status) }}

                @if($overdue)
                    / Overdue
                @endif
            </div>
        </td>

    </tr>

</table>


{{-- OUTSTANDING INVOICES --}}

<h2>Outstanding Invoices</h2>

<table class="report">

    <thead>

        <tr>
            <th>#</th>
            <th>Invoice</th>
            <th>Student</th>
            <th>Student No.</th>
            <th>Invoice Date</th>
            <th>Due Date</th>
            <th class="text-right">Total</th>
            <th class="text-right">Paid</th>
            <th class="text-right">Outstanding</th>
            <th>Status</th>
            <th>Overdue</th>
        </tr>

    </thead>

    <tbody>

    @forelse($invoices as $index => $invoice)

        @php

            $student = $invoice->student;

            $studentName = $student
                ? trim(
                    ($student->first_name ?? '') . ' ' .
                    ($student->middle_name ?? '') . ' ' .
                    ($student->last_name ?? '')
                )
                : '-';

            $isOverdue =
                $invoice->due_date &&
                $invoice->due_date->lt(now()->startOfDay()) &&
                (float) $invoice->balance > 0;

        @endphp

        <tr>

            <td>
                {{ $index + 1 }}
            </td>

            <td>
                {{ $invoice->invoice_number ?? '-' }}
            </td>

            <td>
                {{ $studentName ?: '-' }}
            </td>

            <td>
                {{ $student->student_number ?? '-' }}
            </td>

            <td>
                {{ $invoice->invoice_date?->format('d M Y') ?? '-' }}
            </td>

            <td>
                {{ $invoice->due_date?->format('d M Y') ?? '-' }}
            </td>

            <td class="text-right">
                ₹{{ number_format((float) $invoice->total, 2) }}
            </td>

            <td class="text-right">
                ₹{{ number_format((float) $invoice->paid, 2) }}
            </td>

            <td class="text-right">
                ₹{{ number_format((float) $invoice->balance, 2) }}
            </td>

            <td>
                {{ ucfirst($invoice->status ?? '-') }}
            </td>

            <td class="{{ $isOverdue ? 'overdue' : '' }}">
                {{ $isOverdue ? 'Yes' : 'No' }}
            </td>

        </tr>

    @empty

        <tr>

            <td colspan="11" class="text-center">
                No outstanding invoices found for the selected filters.
            </td>

        </tr>

    @endforelse

    </tbody>

</table>


<div class="footer">
    Outstanding Fees Report generated on
    {{ now()->format('d M Y H:i') }}
</div>

</body>
</html>