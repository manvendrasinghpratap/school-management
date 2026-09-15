@extends('backend.layout.default')

@section('title', 'Payment Report')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row mb-3">

        <div class="col-12">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h4 class="mb-1">
                        Payment Report
                    </h4>

                    <p class="text-muted mb-0">
                        View payments collected during the selected period.
                    </p>
                </div>

                <div class="d-flex gap-2">

                    <a href="{{ route('admin.finance-reports.collection') }}"
                       class="btn btn-outline-primary">

                        <i class="bx bx-money me-1"></i>
                        Collection Report

                    </a>

                    <a href="{{ route('admin.finance-reports.outstanding') }}"
                       class="btn btn-outline-warning">

                        <i class="bx bx-time-five me-1"></i>
                        Outstanding

                    </a>

                    <div class="d-flex gap-2">

    {{-- Excel Export --}}
    <a href="{{ route('admin.finance-reports.payments.excel', [
        'from' => $from,
        'to' => $to,
        'payment_method' => $paymentMethod,
        'student' => $studentSearch,
    ]) }}"
       class="btn btn-success">

        <i class="bx bx-spreadsheet me-1"></i>
        Excel

    </a>


    {{-- PDF Export --}}
    <a href="{{ route('admin.finance-reports.payments.pdf', [
        'from' => $from,
        'to' => $to,
        'payment_method' => $paymentMethod,
        'student' => $studentSearch,
    ]) }}"
       class="btn btn-danger"
       target="_blank">

        <i class="bx bx-file me-1"></i>
        PDF

    </a>


    {{-- Dashboard --}}
    <a href="{{ route('admin.dashboard') }}"
       class="btn btn-outline-secondary">

        <i class="bx bx-arrow-back me-1"></i>
        Dashboard

    </a>

</div>

                </div>

            </div>

        </div>

    </div>


    {{-- Filters --}}
    <div class="card mb-4">

        <div class="card-header">

            <h5 class="card-title mb-0">

                <i class="bx bx-filter-alt me-2"></i>
                Report Filters

            </h5>

        </div>

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('admin.finance-reports.payments') }}"
            >

                <div class="row">

                    {{-- From --}}
                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            From Date
                        </label>

                        <input
                            type="date"
                            name="from"
                            value="{{ $from }}"
                            class="form-control"
                        >

                    </div>


                    {{-- To --}}
                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            To Date
                        </label>

                        <input
                            type="date"
                            name="to"
                            value="{{ $to }}"
                            class="form-control"
                        >

                    </div>


                    {{-- Payment Method --}}
                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            Payment Method
                        </label>

                        <select
                            name="payment_method"
                            class="form-select"
                        >

                            <option value="">
                                All Methods
                            </option>

                            @foreach($paymentMethods as $method)

                                @php
                                    $methodValue = (string) $method->payment_method;
                                @endphp

                                <option
                                    value="{{ $methodValue }}"
                                    {{ $paymentMethod === $methodValue ? 'selected' : '' }}
                                >
                                    {{ ucfirst(
                                        str_replace(
                                            '_',
                                            ' ',
                                            $methodValue ?: 'Other'
                                        )
                                    ) }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Student --}}
                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            Student
                        </label>

                        <input
                            type="text"
                            name="student"
                            value="{{ $studentSearch }}"
                            class="form-control"
                            placeholder="Name or student number"
                        >

                    </div>

                </div>


                <div class="text-end">

                    <button
                        type="submit"
                        class="btn btn-primary me-2"
                    >

                        <i class="bx bx-search me-1"></i>
                        Generate Report

                    </button>

                    <a
                        href="{{ route('admin.finance-reports.payments') }}"
                        class="btn btn-outline-secondary"
                    >
                        Reset
                    </a>

                </div>

            </form>

        </div>

    </div>


    {{-- Summary --}}
    <div class="row mb-4">

        {{-- Payment Count --}}
        <div class="col-xl-3 col-md-6">

            <div class="card">

                <div class="card-body">

                    <p class="text-muted mb-2">
                        Payments
                    </p>

                    <h4 class="mb-0 text-primary">
                        {{ number_format($paymentCount) }}
                    </h4>

                    <small class="text-muted">
                        Matching payments
                    </small>

                </div>

            </div>

        </div>


        {{-- Gross Payments --}}
        <div class="col-xl-3 col-md-6">

            <div class="card">

                <div class="card-body">

                    <p class="text-muted mb-2">
                        Gross Payments
                    </p>

                    <h4 class="mb-0 text-success">
                        ₹{{ number_format($grossPayments, 2) }}
                    </h4>

                    <small class="text-muted">
                        Before refunds
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
                        ₹{{ number_format($processedRefunds, 2) }}
                    </h4>

                    <small class="text-muted">
                        {{ number_format($refundCount) }}
                        processed refund{{ $refundCount == 1 ? '' : 's' }}
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


    {{-- Payment Method Summary --}}
    <div class="card mb-4">

        <div class="card-header">

            <h5 class="card-title mb-0">

                <i class="bx bx-wallet me-2"></i>
                Payment Method Summary

            </h5>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>
                                Payment Method
                            </th>

                            <th>
                                Payments
                            </th>

                            <th class="text-end">
                                Amount
                            </th>

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

                                <td
                                    colspan="3"
                                    class="text-center text-muted py-4"
                                >

                                    No payment data found.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- Payments Table --}}
    <div class="card">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="card-title mb-0">

                    <i class="bx bx-money me-2"></i>
                    Payment Transactions

                </h5>

                <span class="badge bg-primary-subtle text-primary">

                    {{ number_format($payments->total()) }}
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

                            <th class="text-end">
                                Amount
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($payments as $payment)

                            <tr>

                                {{-- Date --}}
                                <td>

                                    {{ $payment->paid_at?->format('d M Y') ?? '—' }}

                                    @if($payment->paid_at)

                                        <small class="d-block text-muted">
                                            {{ $payment->paid_at->format('h:i A') }}
                                        </small>

                                    @endif

                                </td>


                                {{-- Student --}}
                                <td>

                                    @if($payment->invoice?->student)

                                        <strong>

                                            {{ $payment->invoice->student->first_name }}

                                            {{ $payment->invoice->student->middle_name }}

                                            {{ $payment->invoice->student->last_name }}

                                        </strong>

                                        <small class="d-block text-muted">

                                            {{ $payment->invoice->student->student_number }}

                                        </small>

                                    @else

                                        —

                                    @endif

                                </td>


                                {{-- Invoice --}}
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


                                {{-- Method --}}
                                <td>

                                    <span class="badge bg-light text-dark">

                                        {{ ucfirst(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $payment->payment_method ?? 'Other'
                                            )
                                        ) }}

                                    </span>

                                </td>


                                {{-- Reference --}}
                                <td>

                                    {{ $payment->transaction_reference ?: '—' }}

                                </td>


                                {{-- Received By --}}
                                <td>

                                    {{ $payment->receivedBy?->name ?? '—' }}

                                </td>


                                {{-- Amount --}}
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

                                <td
                                    colspan="7"
                                    class="text-center text-muted py-5"
                                >

                                    <i class="bx bx-money font-size-32 d-block mb-2"></i>

                                    <h6>
                                        No payments found.
                                    </h6>

                                    <small>
                                        Try changing your date range or filters.
                                    </small>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- Pagination --}}
        @if($payments->hasPages())

            <div class="card-footer">

                {{ $payments->links() }}

            </div>

        @endif

    </div>

</div>

@endsection