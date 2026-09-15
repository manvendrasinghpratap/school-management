@extends('backend.layout.default')

@section('title', 'Payment Method Report')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row mb-3">

        <div class="col-12">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h4 class="mb-1">
                        Payment Method Report
                    </h4>

                    <p class="text-muted mb-0">
                        Compare collections by payment method.
                    </p>

                </div>

                <div class="d-flex gap-2">

                    <a
                        href="{{ route('admin.finance-reports.payments') }}"
                        class="btn btn-outline-primary"
                    >
                        <i class="bx bx-money me-1"></i>
                        Payment Report
                    </a>

                    <a
                        href="{{ route('admin.finance-reports.refunds') }}"
                        class="btn btn-outline-danger"
                    >
                        <i class="bx bx-undo me-1"></i>
                        Refund Report
                    </a>

                   <div class="d-flex gap-2">

    {{-- Excel --}}
    <a
        href="{{ route('admin.finance-reports.payment-methods.excel', [
            'from' => $from,
            'to' => $to,
            'payment_method' => $paymentMethod,
            'student' => $studentSearch,
        ]) }}"
        class="btn btn-success"
    >
        <i class="bx bx-spreadsheet me-1"></i>
        Excel
    </a>


    {{-- PDF --}}
    <a
        href="{{ route('admin.finance-reports.payment-methods.pdf', [
            'from' => $from,
            'to' => $to,
            'payment_method' => $paymentMethod,
            'student' => $studentSearch,
        ]) }}"
        class="btn btn-danger"
        target="_blank"
    >
        <i class="bx bx-file me-1"></i>
        PDF
    </a>


    {{-- Dashboard --}}
    <a
        href="{{ route('admin.dashboard') }}"
        class="btn btn-outline-secondary"
    >
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
                action="{{ route('admin.finance-reports.payment-methods') }}"
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

                            @foreach($methodRows as $method)

                                <option
                                    value="{{ $method->payment_method }}"
                                    {{ $paymentMethod === $method->payment_method ? 'selected' : '' }}
                                >
                                    {{ ucfirst(
                                        str_replace(
                                            '_',
                                            ' ',
                                            $method->payment_method
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
                        href="{{ route('admin.finance-reports.payment-methods') }}"
                        class="btn btn-outline-secondary"
                    >
                        Reset
                    </a>

                </div>

            </form>

        </div>

    </div>


    {{-- Overall Summary --}}
    <div class="row mb-4">

        {{-- Transactions --}}
        <div class="col-xl-3 col-md-6">

            <div class="card">

                <div class="card-body">

                    <p class="text-muted mb-2">
                        Transactions
                    </p>

                    <h4 class="mb-0 text-primary">
                        {{ number_format($totalPayments) }}
                    </h4>

                    <small class="text-muted">
                        Matching payments
                    </small>

                </div>

            </div>

        </div>


        {{-- Gross --}}
        <div class="col-xl-3 col-md-6">

            <div class="card">

                <div class="card-body">

                    <p class="text-muted mb-2">
                        Gross Collection
                    </p>

                    <h4 class="mb-0 text-success">
                        ₹{{ number_format($grossCollection, 2) }}
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
                        refund{{ $refundCount == 1 ? '' : 's' }}
                    </small>

                </div>

            </div>

        </div>


        {{-- Effective --}}
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


    {{-- Collection Rate --}}
    <div class="row mb-4">

        <div class="col-xl-4">

            <div class="card">

                <div class="card-body">

                    <p class="text-muted mb-2">
                        Effective Collection Rate
                    </p>

                    <h4 class="mb-0 text-success">
                        {{ number_format($collectionPercentage, 2) }}%
                    </h4>

                    <div
                        class="progress mt-3"
                        style="height: 6px;"
                    >

                        <div
                            class="progress-bar"
                            role="progressbar"
                            style="width: {{ $collectionPercentage }}%"
                            aria-valuenow="{{ $collectionPercentage }}"
                            aria-valuemin="0"
                            aria-valuemax="100"
                        ></div>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-xl-8">

            <div class="card">

                <div class="card-body">

                    <p class="text-muted mb-2">
                        Reporting Period
                    </p>

                    <h6 class="mb-0">

                        {{ \Carbon\Carbon::parse($from)->format('d M Y') }}

                        <span class="text-muted mx-2">
                            to
                        </span>

                        {{ \Carbon\Carbon::parse($to)->format('d M Y') }}

                    </h6>

                </div>

            </div>

        </div>

    </div>


    {{-- Method Breakdown --}}
    <div class="card mb-4">

        <div class="card-header">

            <h5 class="card-title mb-0">

                <i class="bx bx-wallet me-2"></i>
                Collection by Payment Method

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
                                Transactions
                            </th>

                            <th class="text-end">
                                Gross Collection
                            </th>

                            <th class="text-end">
                                Refunds
                            </th>

                            <th class="text-end">
                                Effective Collection
                            </th>

                            <th class="text-end">
                                Retained %
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($methodRows as $method)

                            <tr>

                                {{-- Method --}}
                                <td>

                                    <div class="d-flex align-items-center">

                                        <div class="avatar-sm">

                                            <span
                                                class="avatar-title rounded-circle bg-primary-subtle text-primary"
                                            >
                                                <i class="bx bx-credit-card"></i>
                                            </span>

                                        </div>

                                        <div class="ms-2">

                                            <strong>

                                                {{ ucfirst(
                                                    str_replace(
                                                        '_',
                                                        ' ',
                                                        $method->payment_method
                                                    )
                                                ) }}

                                            </strong>

                                        </div>

                                    </div>

                                </td>


                                {{-- Transactions --}}
                                <td>

                                    {{ number_format(
                                        $method->payment_count
                                    ) }}

                                </td>


                                {{-- Gross --}}
                                <td class="text-end">

                                    <strong class="text-success">

                                        ₹{{ number_format(
                                            $method->gross_amount,
                                            2
                                        ) }}

                                    </strong>

                                </td>


                                {{-- Refund --}}
                                <td class="text-end">

                                    @if($method->refunded_amount > 0)

                                        <strong class="text-danger">

                                            ₹{{ number_format(
                                                $method->refunded_amount,
                                                2
                                            ) }}

                                        </strong>

                                    @else

                                        <span class="text-muted">
                                            ₹0.00
                                        </span>

                                    @endif

                                </td>


                                {{-- Effective --}}
                                <td class="text-end">

                                    <strong class="text-primary">

                                        ₹{{ number_format(
                                            $method->effective_amount,
                                            2
                                        ) }}

                                    </strong>

                                </td>


                                {{-- Percentage --}}
                                <td class="text-end">

                                    <span
                                        class="badge bg-success-subtle text-success"
                                    >

                                        {{ number_format(
                                            $method->percentage,
                                            2
                                        ) }}%

                                    </span>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="6"
                                    class="text-center text-muted py-5"
                                >

                                    <i class="bx bx-wallet font-size-32 d-block mb-2"></i>

                                    <h6>
                                        No payment data found.
                                    </h6>

                                    <small>
                                        Try changing your date range or filters.
                                    </small>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>


                    @if($methodRows->isNotEmpty())

                        <tfoot class="table-light">

                            <tr>

                                <th>
                                    Total
                                </th>

                                <th>
                                    {{ number_format($totalPayments) }}
                                </th>

                                <th class="text-end">

                                    ₹{{ number_format(
                                        $grossCollection,
                                        2
                                    ) }}

                                </th>

                                <th class="text-end">

                                    <span class="text-danger">

                                        ₹{{ number_format(
                                            $processedRefunds,
                                            2
                                        ) }}

                                    </span>

                                </th>

                                <th class="text-end">

                                    <span class="text-primary">

                                        ₹{{ number_format(
                                            $effectiveCollection,
                                            2
                                        ) }}

                                    </span>

                                </th>

                                <th class="text-end">

                                    {{ number_format(
                                        $collectionPercentage,
                                        2
                                    ) }}%

                                </th>

                            </tr>

                        </tfoot>

                    @endif

                </table>

            </div>

        </div>

    </div>


    {{-- Payment Transactions --}}
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
                            <th>Payment Method</th>
                            <th>Reference</th>
                            <th>Received By</th>
                            <th class="text-end">Amount</th>

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

                                        <a
                                            href="{{ route(
                                                'admin.invoices.show',
                                                $payment->invoice
                                            ) }}"
                                        >

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
                                        No payment transactions found.
                                    </h6>

                                    <small>
                                        Try changing your filters.
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