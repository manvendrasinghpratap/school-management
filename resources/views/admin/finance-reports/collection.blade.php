@extends('backend.layout.default')

@section('title', 'Collection Report')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row mb-3">
        <div class="col-12">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h4 class="mb-1">Collection Report</h4>

                    <p class="text-muted mb-0">
                        Finance collection summary and payment details.
                    </p>
                </div>

                <div class="d-flex gap-2">

    <a href="{{ route('admin.finance-reports.collection.excel', [
        'from' => $from,
        'to' => $to,
    ]) }}"
       class="btn btn-success">
        <i class="bx bx-spreadsheet me-1"></i>
        Excel
    </a>

    <a href="{{ route('admin.finance-reports.collection.pdf', [
        'from' => $from,
        'to' => $to,
    ]) }}"
       class="btn btn-danger"
       target="_blank">
        <i class="bx bx-file me-1"></i>
        PDF
    </a>

    <a href="{{ route('admin.dashboard') }}"
       class="btn btn-outline-secondary">
        <i class="bx bx-arrow-back me-1"></i>
        Dashboard
    </a>

</div>

            </div>

        </div>
    </div>


    {{-- Date Filter --}}
    <div class="card mb-4">

        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="bx bx-filter-alt me-2"></i>
                Report Filters
            </h5>
        </div>

        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.finance-reports.collection') }}">

                <div class="row align-items-end">

                    <div class="col-md-4">

                        <label class="form-label">
                            From Date
                        </label>

                        <input
                            type="date"
                            name="from"
                            value="{{ $from }}"
                            class="form-control"
                            required
                        >

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            To Date
                        </label>

                        <input
                            type="date"
                            name="to"
                            value="{{ $to }}"
                            class="form-control"
                            required
                        >

                    </div>


                    <div class="col-md-4">

                        <button
                            type="submit"
                            class="btn btn-primary me-2">

                            <i class="bx bx-search me-1"></i>
                            Generate Report

                        </button>

                        <a
                            href="{{ route('admin.finance-reports.collection') }}"
                            class="btn btn-outline-secondary">

                            Reset

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Summary Cards --}}
    <div class="row mb-4">

        {{-- Total Invoiced --}}
        <div class="col-xl-3 col-md-6">

            <div class="card">

                <div class="card-body">

                    <p class="text-muted mb-2">
                        Total Invoiced
                    </p>

                    <h4 class="mb-0">
                        ₹{{ number_format($totalInvoiced, 2) }}
                    </h4>

                    <small class="text-muted">
                        {{ number_format($invoiceCount) }}
                        invoice{{ $invoiceCount == 1 ? '' : 's' }}
                    </small>

                </div>

            </div>

        </div>


        {{-- Gross Collection --}}
        <div class="col-xl-3 col-md-6">

            <div class="card">

                <div class="card-body">

                    <p class="text-muted mb-2">
                        Gross Collection
                    </p>

                    <h4 class="mb-0 text-success">
                        ₹{{ number_format($grossCollected, 2) }}
                    </h4>

                    <small class="text-muted">
                        {{ number_format($paymentCount) }}
                        payment{{ $paymentCount == 1 ? '' : 's' }}
                    </small>

                </div>

            </div>

        </div>


        {{-- Refunds --}}
        <div class="col-xl-3 col-md-6">

            <div class="card">

                <div class="card-body">

                    <p class="text-muted mb-2">
                        Processed Refunds
                    </p>

                    <h4 class="mb-0 text-danger">
                        ₹{{ number_format($totalRefunded, 2) }}
                    </h4>

                    <small class="text-muted">
                        {{ number_format($refundCount) }}
                        refund{{ $refundCount == 1 ? '' : 's' }}
                    </small>

                </div>

            </div>

        </div>


        {{-- Effective Collection --}}
        <div class="col-xl-3 col-md-6">

            <div class="card">

                <div class="card-body">

                    <p class="text-muted mb-2">
                        Effective Collection
                    </p>

                    <h4 class="mb-0 text-primary">
                        ₹{{ number_format($effectiveCollection, 2) }}
                    </h4>

                    <small class="text-muted">
                        After processed refunds
                    </small>

                </div>

            </div>

        </div>

    </div>


    {{-- Second Summary Row --}}
    <div class="row mb-4">

        <div class="col-xl-4 col-md-6">

            <div class="card">

                <div class="card-body">

                    <p class="text-muted mb-2">
                        Outstanding
                    </p>

                    <h4 class="mb-0 text-warning">
                        ₹{{ number_format($outstanding, 2) }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-xl-4 col-md-6">

            <div class="card">

                <div class="card-body">

                    <p class="text-muted mb-2">
                        Collection Rate
                    </p>

                    <h4 class="mb-0 text-success">
                        {{ number_format($collectionPercentage, 2) }}%
                    </h4>

                    <div class="progress mt-2"
                         style="height: 6px;">

                        <div
                            class="progress-bar"
                            style="width: {{ $collectionPercentage }}%">
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-4 col-md-12">

            <div class="card">

                <div class="card-body">

                    <p class="text-muted mb-2">
                        Reporting Period
                    </p>

                    <h6 class="mb-0">
                        {{ \Carbon\Carbon::parse($from)->format('d M Y') }}
                        -
                        {{ \Carbon\Carbon::parse($to)->format('d M Y') }}
                    </h6>

                </div>

            </div>

        </div>

    </div>


    {{-- Payment Method Summary --}}
    <div class="row mb-4">

        <div class="col-12">

            <div class="card">

                <div class="card-header">

                    <h5 class="card-title mb-0">
                        <i class="bx bx-wallet me-2"></i>
                        Payment Method Summary
                    </h5>

                </div>

                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover mb-0">

                            <thead class="table-light">

                                <tr>
                                    <th>Payment Method</th>
                                    <th>Payments</th>
                                    <th class="text-end">Amount</th>
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
                                                    $method->payment_method ?? 'Other'
                                                )
                                            ) }}
                                        </td>

                                        <td>
                                            {{ number_format($method->payment_count) }}
                                        </td>

                                        <td class="text-end">

                                            <strong>
                                                ₹{{ number_format(
                                                    (float) $method->amount,
                                                    2
                                                ) }}
                                            </strong>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="3"
                                            class="text-center text-muted py-4">
                                            No payment data found.
                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Payments --}}
    <div class="card mb-4">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="card-title mb-0">
                    <i class="bx bx-money me-2"></i>
                    Payments
                </h5>

                <span class="badge bg-primary-subtle text-primary">
                    {{ $payments->total() }}
                    records
                </span>

            </div>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>
                            <th>Date</th>
                            <th>Student</th>
                            <th>Invoice</th>
                            <th>Method</th>
                            <th>Reference</th>
                            <th>Received By</th>
                            <th class="text-end">Amount</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($payments as $payment)

                            <tr>

                                <td>
                                    {{ $payment->paid_at?->format('d M Y') ?? '—' }}
                                </td>

                                <td>

                                    @if($payment->invoice?->student)

                                        <strong>
                                            {{ $payment->invoice->student->first_name }}
                                            {{ $payment->invoice->student->last_name }}
                                        </strong>

                                        <small class="d-block text-muted">
                                            {{ $payment->invoice->student->student_number }}
                                        </small>

                                    @else
                                        —
                                    @endif

                                </td>

                                <td>

                                    @if($payment->invoice)

                                        <a href="{{ route(
                                            'admin.invoices.show',
                                            $payment->invoice
                                        ) }}">
                                            {{ $payment->invoice->invoice_number }}
                                        </a>

                                    @else
                                        —
                                    @endif

                                </td>

                                <td>

                                    <span class="badge bg-light text-dark">

                                        {{ ucfirst(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $payment->payment_method ?? 'N/A'
                                            )
                                        ) }}

                                    </span>

                                </td>

                                <td>
                                    {{ $payment->transaction_reference ?: '—' }}
                                </td>

                                <td>
                                    {{ $payment->receivedBy?->name ?? '—' }}
                                </td>

                                <td class="text-end">

                                    <strong class="text-success">
                                        ₹{{ number_format(
                                            (float) $payment->amount,
                                            2
                                        ) }}
                                    </strong>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7"
                                    class="text-center text-muted py-5">

                                    <i class="bx bx-money font-size-24 d-block mb-2"></i>

                                    No payments found for the selected period.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        @if($payments->hasPages())

            <div class="card-footer">

                {{ $payments->links() }}

            </div>

        @endif

    </div>


    {{-- Processed Refunds --}}
    <div class="card">

        <div class="card-header">

            <h5 class="card-title mb-0">
                <i class="bx bx-undo me-2"></i>
                Processed Refunds
            </h5>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>
                            <th>Date</th>
                            <th>Student</th>
                            <th>Payment</th>
                            <th>Reason</th>
                            <th class="text-end">Amount</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($refunds as $refund)

                            <tr>

                                <td>
                                    {{ $refund->processed_at?->format('d M Y') ?? '—' }}
                                </td>

                                <td>

                                    @if($refund->student)

                                        {{ $refund->student->first_name }}
                                        {{ $refund->student->last_name }}

                                    @else
                                        —
                                    @endif

                                </td>

                                <td>
                                    #{{ $refund->payment_id }}
                                </td>

                                <td>
                                    {{ $refund->reason ?: '—' }}
                                </td>

                                <td class="text-end">

                                    <strong class="text-danger">
                                        ₹{{ number_format(
                                            (float) $refund->amount,
                                            2
                                        ) }}
                                    </strong>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="5"
                                    class="text-center text-muted py-5">

                                    No processed refunds found
                                    for the selected period.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection