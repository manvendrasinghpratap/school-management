@extends('backend.layout.default')

@section('title', 'Refund Report')

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | Safe filter values
    |--------------------------------------------------------------------------
    | These fall back to the current request so the Blade never fails when
    | a controller variable is not explicitly supplied.
    |--------------------------------------------------------------------------
    */

    $from = $from ?? request('from', now()->startOfMonth()->format('Y-m-d'));
    $to = $to ?? request('to', now()->format('Y-m-d'));

    $status = $status ?? request('status', 'all');

    $paymentMethod = $paymentMethod ?? request('payment_method');

    $studentSearch = $studentSearch ?? request('student');

    $paymentId = $paymentId ?? request('payment_id');

    $reference = $reference ?? request('reference');
@endphp

<div class="container-fluid">

    {{-- ============================================================= --}}
    {{-- Page Header --}}
    {{-- ============================================================= --}}

    <div class="row mb-3">

        <div class="col-12">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h4 class="mb-1">
                        Refund Report
                    </h4>

                    <p class="text-muted mb-0">
                        View and audit payment refund activity.
                    </p>

                </div>

                <div class="d-flex gap-2">

                    {{-- Payment Report --}}
                    <a
                        href="{{ route('admin.finance-reports.payments') }}"
                        class="btn btn-outline-primary"
                    >
                        <i class="bx bx-money me-1"></i>
                        Payment Report
                    </a>

                    {{-- Collection Report --}}
                    <a
                        href="{{ route('admin.finance-reports.collection') }}"
                        class="btn btn-outline-success"
                    >
                        <i class="bx bx-bar-chart-alt-2 me-1"></i>
                        Collection
                    </a>

                    {{-- ================================================= --}}
                    {{-- Export / Navigation Buttons --}}
                    {{-- ================================================= --}}

                    <div class="d-flex gap-2">

    {{-- Excel --}}
    <a
        href="{{ route('admin.finance-reports.refunds.excel', [
            'from' => $from,
            'to' => $to,
            'status' => $status,
            'payment_method' => $paymentMethod,
            'student' => $studentSearch,
            'payment_id' => $paymentId,
            'reference' => $reference,
        ]) }}"
        class="btn btn-success"
    >
        <i class="bx bx-spreadsheet me-1"></i>
        Excel
    </a>


    {{-- PDF --}}
    <a
        href="{{ route('admin.finance-reports.refunds.pdf', [
            'from' => $from,
            'to' => $to,
            'status' => $status,
            'payment_method' => $paymentMethod,
            'student' => $studentSearch,
            'payment_id' => $paymentId,
            'reference' => $reference,
        ]) }}"
        class="btn btn-danger"
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


    {{-- ============================================================= --}}
    {{-- Filters --}}
    {{-- ============================================================= --}}

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
                action="{{ route('admin.finance-reports.refunds') }}"
            >

                <div class="row">

                    {{-- From --}}
                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            Request Date From
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
                            Request Date To
                        </label>

                        <input
                            type="date"
                            name="to"
                            value="{{ $to }}"
                            class="form-control"
                        >

                    </div>


                    {{-- Status --}}
                    <div class="col-md-2 mb-3">

                        <label class="form-label">
                            Status
                        </label>

                        <select
                            name="status"
                            class="form-select"
                        >

                            <option
                                value="all"
                                {{ $status === 'all' ? 'selected' : '' }}
                            >
                                All Statuses
                            </option>

                            <option
                                value="requested"
                                {{ $status === 'requested' ? 'selected' : '' }}
                            >
                                Requested
                            </option>

                            <option
                                value="approved"
                                {{ $status === 'approved' ? 'selected' : '' }}
                            >
                                Approved
                            </option>

                            <option
                                value="processed"
                                {{ $status === 'processed' ? 'selected' : '' }}
                            >
                                Processed
                            </option>

                            <option
                                value="rejected"
                                {{ $status === 'rejected' ? 'selected' : '' }}
                            >
                                Rejected
                            </option>

                            <option
                                value="cancelled"
                                {{ $status === 'cancelled' ? 'selected' : '' }}
                            >
                                Cancelled
                            </option>

                        </select>

                    </div>


                    {{-- Student --}}
                    <div class="col-md-4 mb-3">

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


                    {{-- Payment ID --}}
                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            Payment ID
                        </label>

                        <input
                            type="number"
                            name="payment_id"
                            value="{{ $paymentId }}"
                            class="form-control"
                            min="1"
                            placeholder="e.g. 4"
                        >

                    </div>


                    {{-- Reference --}}
                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            Refund Reference
                        </label>

                        <input
                            type="text"
                            name="reference"
                            value="{{ $reference }}"
                            class="form-control"
                            placeholder="Refund reference"
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
                        href="{{ route('admin.finance-reports.refunds') }}"
                        class="btn btn-outline-secondary"
                    >
                        Reset
                    </a>

                </div>

            </form>

        </div>

    </div>


    {{-- ============================================================= --}}
    {{-- Summary Cards --}}
    {{-- ============================================================= --}}

    <div class="row mb-4">

        {{-- Total Refunds --}}
        <div class="col-xl-3 col-md-6">

            <div class="card">

                <div class="card-body">

                    <p class="text-muted mb-2">
                        Refund Requests
                    </p>

                    <h4 class="mb-0 text-primary">
                        {{ number_format($totalRefunds) }}
                    </h4>

                    <small class="text-muted">
                        Matching refund records
                    </small>

                </div>

            </div>

        </div>


        {{-- Total Amount --}}
        <div class="col-xl-3 col-md-6">

            <div class="card">

                <div class="card-body">

                    <p class="text-muted mb-2">
                        Total Refund Amount
                    </p>

                    <h4 class="mb-0">
                        ₹{{ number_format($totalAmount, 2) }}
                    </h4>

                    <small class="text-muted">
                        All selected statuses
                    </small>

                </div>

            </div>

        </div>


        {{-- Processed --}}
        <div class="col-xl-3 col-md-6">

            <div class="card">

                <div class="card-body">

                    <p class="text-muted mb-2">
                        Processed Refunds
                    </p>

                    <h4 class="mb-0 text-danger">
                        ₹{{ number_format($processedAmount, 2) }}
                    </h4>

                    <small class="text-muted">
                        Reduces effective collection
                    </small>

                </div>

            </div>

        </div>


        {{-- Requested + Approved --}}
        <div class="col-xl-3 col-md-6">

            <div class="card">

                <div class="card-body">

                    <p class="text-muted mb-2">
                        Pending Requests
                    </p>

                    <h4 class="mb-0 text-warning">
                        ₹{{ number_format(
                            $requestedAmount + $approvedAmount,
                            2
                        ) }}
                    </h4>

                    <small class="text-muted">
                        Requested + approved
                    </small>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================= --}}
    {{-- Status Summary --}}
    {{-- ============================================================= --}}

    <div class="row mb-4">

        {{-- Requested --}}
        <div class="col-xl-2 col-md-4 col-6">

            <div class="card">

                <div class="card-body">

                    <p class="text-muted mb-1">
                        Requested
                    </p>

                    <h5 class="mb-0 text-warning">
                        {{ number_format($statusCounts['requested']) }}
                    </h5>

                    <small class="text-muted">
                        ₹{{ number_format($requestedAmount, 2) }}
                    </small>

                </div>

            </div>

        </div>


        {{-- Approved --}}
        <div class="col-xl-2 col-md-4 col-6">

            <div class="card">

                <div class="card-body">

                    <p class="text-muted mb-1">
                        Approved
                    </p>

                    <h5 class="mb-0 text-primary">
                        {{ number_format($statusCounts['approved']) }}
                    </h5>

                    <small class="text-muted">
                        ₹{{ number_format($approvedAmount, 2) }}
                    </small>

                </div>

            </div>

        </div>


        {{-- Processed --}}
        <div class="col-xl-2 col-md-4 col-6">

            <div class="card">

                <div class="card-body">

                    <p class="text-muted mb-1">
                        Processed
                    </p>

                    <h5 class="mb-0 text-danger">
                        {{ number_format($statusCounts['processed']) }}
                    </h5>

                    <small class="text-muted">
                        ₹{{ number_format($processedAmount, 2) }}
                    </small>

                </div>

            </div>

        </div>


        {{-- Rejected --}}
        <div class="col-xl-2 col-md-4 col-6">

            <div class="card">

                <div class="card-body">

                    <p class="text-muted mb-1">
                        Rejected
                    </p>

                    <h5 class="mb-0 text-secondary">
                        {{ number_format($statusCounts['rejected']) }}
                    </h5>

                    <small class="text-muted">
                        ₹{{ number_format($rejectedAmount, 2) }}
                    </small>

                </div>

            </div>

        </div>


        {{-- Cancelled --}}
        <div class="col-xl-2 col-md-4 col-6">

            <div class="card">

                <div class="card-body">

                    <p class="text-muted mb-1">
                        Cancelled
                    </p>

                    <h5 class="mb-0 text-secondary">
                        {{ number_format($statusCounts['cancelled']) }}
                    </h5>

                    <small class="text-muted">
                        ₹{{ number_format($cancelledAmount, 2) }}
                    </small>

                </div>

            </div>

        </div>


        {{-- Effective Impact --}}
        <div class="col-xl-2 col-md-4 col-6">

            <div class="card">

                <div class="card-body">

                    <p class="text-muted mb-1">
                        Collection Impact
                    </p>

                    <h5 class="mb-0 text-danger">
                        ₹{{ number_format($processedAmount, 2) }}
                    </h5>

                    <small class="text-muted">
                        Processed only
                    </small>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================= --}}
    {{-- Refund Transactions --}}
    {{-- ============================================================= --}}

    <div class="card">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="card-title mb-0">

                    <i class="bx bx-undo me-2"></i>

                    Refund Transactions

                </h5>

                <span class="badge bg-primary-subtle text-primary">

                    {{ number_format($refunds->total()) }}

                    records

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>
                                Request Date
                            </th>

                            <th>
                                Student
                            </th>

                            <th>
                                Payment
                            </th>

                            <th>
                                Invoice
                            </th>

                            <th>
                                Amount
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Reference
                            </th>

                            <th>
                                Requested By
                            </th>

                            <th>
                                Approved By
                            </th>

                            <th>
                                Processed By
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($refunds as $refund)

                            <tr>

                                {{-- Request Date --}}
                                <td>

                                    {{ $refund->requested_at?->format('d M Y') ?? '—' }}

                                    @if($refund->requested_at)

                                        <small class="d-block text-muted">

                                            {{ $refund->requested_at->format('h:i A') }}

                                        </small>

                                    @endif

                                </td>


                                {{-- Student --}}
                                <td>

                                    @if($refund->student)

                                        <strong>

                                            {{ $refund->student->first_name }}

                                            {{ $refund->student->middle_name }}

                                            {{ $refund->student->last_name }}

                                        </strong>

                                        <small class="d-block text-muted">

                                            {{ $refund->student->student_number }}

                                        </small>

                                    @else

                                        —

                                    @endif

                                </td>


                                {{-- Payment --}}
                                <td>

                                    @if($refund->payment)

                                        <a
                                            href="{{ route(
                                                'admin.payments.show',
                                                $refund->payment
                                            ) }}"
                                        >
                                            #{{ $refund->payment_id }}
                                        </a>

                                    @else

                                        #{{ $refund->payment_id }}

                                    @endif

                                </td>


                                {{-- Invoice --}}
                                <td>

                                    @if($refund->payment?->invoice)

                                        <a
                                            href="{{ route(
                                                'admin.invoices.show',
                                                $refund->payment->invoice
                                            ) }}"
                                        >
                                            {{ $refund->payment->invoice->invoice_number }}
                                        </a>

                                    @else

                                        —

                                    @endif

                                </td>


                                {{-- Amount --}}
                                <td>

                                    <strong class="text-danger">

                                        ₹{{ number_format(
                                            (float) $refund->amount,
                                            2
                                        ) }}

                                    </strong>

                                </td>


                                {{-- Status --}}
                                <td>

                                    @if($refund->status === 'requested')

                                        <span class="badge bg-warning-subtle text-warning">
                                            Requested
                                        </span>

                                    @elseif($refund->status === 'approved')

                                        <span class="badge bg-primary-subtle text-primary">
                                            Approved
                                        </span>

                                    @elseif($refund->status === 'processed')

                                        <span class="badge bg-success-subtle text-success">
                                            Processed
                                        </span>

                                    @elseif($refund->status === 'rejected')

                                        <span class="badge bg-danger-subtle text-danger">
                                            Rejected
                                        </span>

                                    @elseif($refund->status === 'cancelled')

                                        <span class="badge bg-secondary-subtle text-secondary">
                                            Cancelled
                                        </span>

                                    @else

                                        <span class="badge bg-light text-dark">
                                            {{ ucfirst($refund->status ?? 'Unknown') }}
                                        </span>

                                    @endif

                                </td>


                                {{-- Reference --}}
                                <td>

                                    {{ $refund->reference ?: '—' }}

                                </td>


                                {{-- Requested By --}}
                                <td>

                                    {{ $refund->requester?->name ?? '—' }}

                                </td>


                                {{-- Approved By --}}
                                <td>

                                    @if($refund->approver)

                                        {{ $refund->approver->name }}

                                        @if($refund->approved_at)

                                            <small class="d-block text-muted">

                                                {{ $refund->approved_at->format('d M Y') }}

                                            </small>

                                        @endif

                                    @else

                                        —

                                    @endif

                                </td>


                                {{-- Processed By --}}
                                <td>

                                    @if($refund->processor)

                                        {{ $refund->processor->name }}

                                        @if($refund->processed_at)

                                            <small class="d-block text-muted">

                                                {{ $refund->processed_at->format('d M Y') }}

                                            </small>

                                        @endif

                                    @else

                                        —

                                    @endif

                                </td>

                            </tr>


                            {{-- Reason / Notes --}}
                            @if($refund->reason || $refund->notes)

                                <tr class="bg-light">

                                    <td></td>

                                    <td colspan="9">

                                        @if($refund->reason)

                                            <strong>
                                                Reason:
                                            </strong>

                                            {{ $refund->reason }}

                                        @endif

                                        @if($refund->notes)

                                            <span class="ms-3">

                                                <strong>
                                                    Notes:
                                                </strong>

                                                {{ $refund->notes }}

                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @endif

                        @empty

                            <tr>

                                <td
                                    colspan="10"
                                    class="text-center text-muted py-5"
                                >

                                    <i class="bx bx-undo font-size-32 d-block mb-2"></i>

                                    <h6>
                                        No refund records found.
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
        @if($refunds->hasPages())

            <div class="card-footer">

                {{ $refunds->links() }}

            </div>

        @endif

    </div>

</div>

@endsection