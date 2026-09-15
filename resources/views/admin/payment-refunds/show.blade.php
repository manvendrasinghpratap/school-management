@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- ============================================================
         PAGE HEADER
    ============================================================ --}}
    <div class="row">
        <div class="col-12">

            <div class="page-title-box d-flex align-items-center justify-content-between">

                <h4 class="mb-0">
                    Refund Details
                </h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">

                        <li class="breadcrumb-item">
                            <a href="{{ url('/admin/dashboard') }}">
                                Dashboard
                            </a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.payment-refunds.index') }}">
                                Payment Refunds
                            </a>
                        </li>

                        <li class="breadcrumb-item active">
                            Refund #{{ $paymentRefund->id }}
                        </li>

                    </ol>
                </div>

            </div>

        </div>
    </div>


    {{-- ============================================================
         FLASH MESSAGES
    ============================================================ --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show" role="alert">

            <i class="mdi mdi-check-circle-outline me-1"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close">
            </button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show" role="alert">

            <i class="mdi mdi-alert-circle-outline me-1"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close">
            </button>

        </div>

    @endif


    {{-- ============================================================
         VALIDATION ERRORS
    ============================================================ --}}

    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show" role="alert">

            <div class="fw-semibold mb-1">
                <i class="mdi mdi-alert-circle-outline me-1"></i>
                Please correct the following:
            </div>

            <ul class="mb-0 ps-3">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close">
            </button>

        </div>

    @endif


    {{-- ============================================================
         TOP ACTIONS
    ============================================================ --}}
    <div class="row mb-3">

        <div class="col-12">

            <div class="d-flex justify-content-between align-items-center">

                <a
                    href="{{ route('admin.payment-refunds.index') }}"
                    class="btn btn-light"
                >
                    <i class="mdi mdi-arrow-left me-1"></i>
                    Back to Refunds
                </a>


                @php

                    $statusClass = match($paymentRefund->status) {

                        'requested' => 'bg-warning text-dark',

                        'approved' => 'bg-info',

                        'processed' => 'bg-success',

                        'rejected' => 'bg-danger',

                        'cancelled' => 'bg-secondary',

                        default => 'bg-secondary',

                    };

                @endphp


                <span class="badge {{ $statusClass }} fs-6 px-3 py-2">

                    {{ ucfirst($paymentRefund->status) }}

                </span>

            </div>

        </div>

    </div>


    {{-- ============================================================
         REFUND SUMMARY
    ============================================================ --}}
    <div class="row">

        <div class="col-lg-8">

            <div class="card">

                <div class="card-body">

                    <h4 class="card-title mb-4">
                        Refund Request
                    </h4>


                    <div class="row">

                        {{-- Refund ID --}}
                        <div class="col-md-6 mb-3">

                            <label class="text-muted d-block">
                                Refund ID
                            </label>

                            <span class="fw-semibold">
                                #{{ $paymentRefund->id }}
                            </span>

                        </div>


                        {{-- Status --}}
                        <div class="col-md-6 mb-3">

                            <label class="text-muted d-block">
                                Status
                            </label>

                            <span class="badge {{ $statusClass }}">
                                {{ ucfirst($paymentRefund->status) }}
                            </span>

                        </div>


                        {{-- Refund Amount --}}
                        <div class="col-md-6 mb-3">

                            <label class="text-muted d-block">
                                Refund Amount
                            </label>

                            <span class="fw-bold text-danger fs-5">
                                ₹{{ number_format((float) $paymentRefund->amount, 2) }}
                            </span>

                        </div>


                        {{-- Requested At --}}
                        <div class="col-md-6 mb-3">

                            <label class="text-muted d-block">
                                Requested At
                            </label>

                            <span>

                                @if($paymentRefund->requested_at)

                                    {{ $paymentRefund->requested_at->format('d M Y, h:i A') }}

                                @else

                                    —

                                @endif

                            </span>

                        </div>


                        {{-- Original Reason --}}
                        <div class="col-12 mb-3">

                            <label class="text-muted d-block">
                                Reason
                            </label>

                            <div class="border rounded p-3 bg-light">

                                {{ $paymentRefund->reason ?: '—' }}

                            </div>

                        </div>


                        {{-- Notes --}}
                        <div class="col-12 mb-3">

                            <label class="text-muted d-block">
                                Notes
                            </label>

                            <div class="border rounded p-3 bg-light">

                                {!! nl2br(e($paymentRefund->notes ?: '—')) !!}

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================
             STUDENT
        ========================================================= --}}
        <div class="col-lg-4">

            <div class="card">

                <div class="card-body">

                    <h4 class="card-title mb-4">
                        Student
                    </h4>


                    @if($paymentRefund->student)

                        <h5 class="mb-1">

                            {{ trim(
                                $paymentRefund->student->first_name . ' ' .
                                ($paymentRefund->student->middle_name ?? '') . ' ' .
                                $paymentRefund->student->last_name
                            ) }}

                        </h5>


                        @if($paymentRefund->student->student_number)

                            <p class="text-muted mb-3">
                                {{ $paymentRefund->student->student_number }}
                            </p>

                        @endif


                        @if($paymentRefund->student->phone)

                            <div class="mb-2">

                                <i class="mdi mdi-phone-outline me-1"></i>

                                {{ $paymentRefund->student->phone }}

                            </div>

                        @endif

                    @else

                        <span class="text-muted">
                            Student information unavailable.
                        </span>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
         INVOICE & PAYMENT
    ============================================================ --}}
    <div class="row">

        {{-- Invoice --}}
        <div class="col-lg-6">

            <div class="card">

                <div class="card-body">

                    <h4 class="card-title mb-4">
                        Invoice
                    </h4>


                    @if($paymentRefund->payment?->invoice)

                        @php
                            $invoice = $paymentRefund->payment->invoice;
                        @endphp


                        <div class="table-responsive">

                            <table class="table table-borderless mb-0">

                                <tr>

                                    <th width="45%">
                                        Invoice Number
                                    </th>

                                    <td>

                                        <span class="fw-semibold">
                                            {{ $invoice->invoice_number }}
                                        </span>

                                    </td>

                                </tr>


                                <tr>

                                    <th>
                                        Invoice Date
                                    </th>

                                    <td>

                                        {{ $invoice->invoice_date
                                            ? $invoice->invoice_date->format('d M Y')
                                            : '—' }}

                                    </td>

                                </tr>


                                <tr>

                                    <th>
                                        Due Date
                                    </th>

                                    <td>

                                        {{ $invoice->due_date
                                            ? $invoice->due_date->format('d M Y')
                                            : '—' }}

                                    </td>

                                </tr>


                                <tr>

                                    <th>
                                        Total
                                    </th>

                                    <td>

                                        ₹{{ number_format((float) $invoice->total, 2) }}

                                    </td>

                                </tr>


                                <tr>

                                    <th>
                                        Paid
                                    </th>

                                    <td>

                                        ₹{{ number_format((float) $invoice->paid, 2) }}

                                    </td>

                                </tr>


                                <tr>

                                    <th>
                                        Balance
                                    </th>

                                    <td>

                                        ₹{{ number_format((float) $invoice->balance, 2) }}

                                    </td>

                                </tr>


                                <tr>

                                    <th>
                                        Status
                                    </th>

                                    <td>

                                        {{ ucfirst($invoice->status) }}

                                    </td>

                                </tr>

                            </table>

                        </div>

                    @else

                        <div class="text-muted">
                            Invoice information unavailable.
                        </div>

                    @endif

                </div>

            </div>

        </div>


        {{-- Original Payment --}}
        <div class="col-lg-6">

            <div class="card">

                <div class="card-body">

                    <h4 class="card-title mb-4">
                        Original Payment
                    </h4>


                    @if($paymentRefund->payment)

                        @php
                            $payment = $paymentRefund->payment;
                        @endphp


                        <div class="table-responsive">

                            <table class="table table-borderless mb-0">

                                <tr>

                                    <th width="45%">
                                        Payment ID
                                    </th>

                                    <td>
                                        #{{ $payment->id }}
                                    </td>

                                </tr>


                                <tr>

                                    <th>
                                        Payment Amount
                                    </th>

                                    <td>

                                        <span class="fw-semibold">

                                            ₹{{ number_format((float) $payment->amount, 2) }}

                                        </span>

                                    </td>

                                </tr>


                                <tr>

                                    <th>
                                        Payment Method
                                    </th>

                                    <td>

                                        {{ ucwords(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $payment->payment_method
                                            )
                                        ) }}

                                    </td>

                                </tr>


                                <tr>

                                    <th>
                                        Transaction Reference
                                    </th>

                                    <td>

                                        {{ $payment->transaction_reference ?: '—' }}

                                    </td>

                                </tr>


                                <tr>

                                    <th>
                                        Paid At
                                    </th>

                                    <td>

                                        {{ $payment->paid_at
                                            ? $payment->paid_at->format('d M Y, h:i A')
                                            : '—' }}

                                    </td>

                                </tr>


                                <tr>

                                    <th>
                                        Received By
                                    </th>

                                    <td>

                                        {{ $payment->receivedBy?->name ?? '—' }}

                                    </td>

                                </tr>

                            </table>

                        </div>

                    @else

                        <div class="text-muted">
                            Payment information unavailable.
                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
         WORKFLOW HISTORY
    ============================================================ --}}
    <div class="row">

        <div class="col-12">

            <div class="card">

                <div class="card-body">

                    <h4 class="card-title mb-4">
                        Workflow History
                    </h4>


                    <div class="table-responsive">

                        <table class="table table-bordered mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th>
                                        Stage
                                    </th>

                                    <th>
                                        User
                                    </th>

                                    <th>
                                        Date
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                {{-- Requested --}}
                                <tr>

                                    <td>

                                        <span class="badge bg-warning text-dark">
                                            Requested
                                        </span>

                                    </td>

                                    <td>

                                        {{ $paymentRefund->requester?->name ?? '—' }}

                                    </td>

                                    <td>

                                        {{ $paymentRefund->requested_at
                                            ? $paymentRefund->requested_at->format('d M Y, h:i A')
                                            : '—' }}

                                    </td>

                                </tr>


                                {{-- Approved --}}
                                <tr>

                                    <td>

                                        <span class="badge bg-info">
                                            Approved
                                        </span>

                                    </td>

                                    <td>

                                        {{ $paymentRefund->approver?->name ?? '—' }}

                                    </td>

                                    <td>

                                        {{ $paymentRefund->approved_at
                                            ? $paymentRefund->approved_at->format('d M Y, h:i A')
                                            : '—' }}

                                    </td>

                                </tr>


                                {{-- Processed --}}
                                <tr>

                                    <td>

                                        <span class="badge bg-success">
                                            Processed
                                        </span>

                                    </td>

                                    <td>

                                        {{ $paymentRefund->processor?->name ?? '—' }}

                                    </td>

                                    <td>

                                        {{ $paymentRefund->processed_at
                                            ? $paymentRefund->processed_at->format('d M Y, h:i A')
                                            : '—' }}

                                    </td>

                                </tr>


                                {{-- Rejected --}}
                                @if($paymentRefund->status === 'rejected')

                                    <tr>

                                        <td>

                                            <span class="badge bg-danger">
                                                Rejected
                                            </span>

                                        </td>

                                        <td>

                                            {{ $paymentRefund->requester?->name ?? '—' }}

                                        </td>

                                        <td>

                                            {{ $paymentRefund->updated_at
                                                ? $paymentRefund->updated_at->format('d M Y, h:i A')
                                                : '—' }}

                                        </td>

                                    </tr>

                                @endif


                                {{-- Cancelled --}}
                                @if($paymentRefund->status === 'cancelled')

                                    <tr>

                                        <td>

                                            <span class="badge bg-secondary">
                                                Cancelled
                                            </span>

                                        </td>

                                        <td>

                                            {{ $paymentRefund->requester?->name ?? '—' }}

                                        </td>

                                        <td>

                                            {{ $paymentRefund->updated_at
                                                ? $paymentRefund->updated_at->format('d M Y, h:i A')
                                                : '—' }}

                                        </td>

                                    </tr>

                                @endif

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- ================================================================
     REFUND ACTIONS
================================================================ --}}

<div class="card mt-4">

    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">


            {{-- Back --}}
            <a
                href="{{ route('admin.payment-refunds.index') }}"
                class="btn btn-light"
            >

                <i class="bx bx-arrow-back me-1"></i>

                Back to Refunds

            </a>


            <div class="d-flex gap-2 flex-wrap">


                {{-- =================================================
                     REQUESTED
                ================================================== --}}
                @if($paymentRefund->status === 'requested')


                    {{-- Reject Refund --}}
                    @can('payment-refunds.reject')

                        <button
                            type="button"
                            class="btn btn-danger"
                            data-bs-toggle="modal"
                            data-bs-target="#rejectRefundModal"
                        >

                            <i class="bx bx-x-circle me-1"></i>

                            Reject Refund

                        </button>

                    @endcan


                    {{-- Approve Refund --}}
                    @can('payment-refunds.approve')

                        <form
                            method="POST"
                            action="{{ route(
                                'admin.payment-refunds.approve',
                                $paymentRefund->id
                            ) }}"
                            class="d-inline"
                            onsubmit="return confirm(
                                'Are you sure you want to approve this refund request?'
                            );"
                        >

                            @csrf

                            @method('PATCH')

                            <button
                                type="submit"
                                class="btn btn-success"
                            >

                                <i class="bx bx-check-circle me-1"></i>

                                Approve Refund

                            </button>

                        </form>

                    @endcan


                    {{-- Cancel Refund --}}
                    @can('payment-refunds.cancel')

                        <form
                            method="POST"
                            action="{{ route(
                                'admin.payment-refunds.cancel',
                                $paymentRefund->id
                            ) }}"
                            class="d-inline"
                            onsubmit="return confirm(
                                'Are you sure you want to cancel this refund request?'
                            );"
                        >

                            @csrf

                            @method('PATCH')

                            <button
                                type="submit"
                                class="btn btn-secondary"
                            >

                                <i class="bx bx-block me-1"></i>

                                Cancel Request

                            </button>

                        </form>

                    @endcan


                {{-- =================================================
                     APPROVED
                ================================================== --}}
                @elseif($paymentRefund->status === 'approved')


                    {{-- Cancel Refund --}}
                    @can('payment-refunds.cancel')

                        <form
                            method="POST"
                            action="{{ route(
                                'admin.payment-refunds.cancel',
                                $paymentRefund->id
                            ) }}"
                            class="d-inline"
                            onsubmit="return confirm(
                                'Are you sure you want to cancel this approved refund?'
                            );"
                        >

                            @csrf

                            @method('PATCH')

                            <button
                                type="submit"
                                class="btn btn-secondary"
                            >

                                <i class="bx bx-block me-1"></i>

                                Cancel Refund

                            </button>

                        </form>

                    @endcan


                    {{-- Process Refund --}}
                    @can('payment-refunds.process')

                        <form
                            method="POST"
                            action="{{ route(
                                'admin.payment-refunds.process',
                                $paymentRefund->id
                            ) }}"
                            class="d-inline"
                            onsubmit="return confirm(
                                'Are you sure you want to process this refund? This will record the refund financially.'
                            );"
                        >

                            @csrf

                            @method('PATCH')

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >

                                <i class="bx bx-money me-1"></i>

                                Process Refund

                            </button>

                        </form>

                    @endcan


                {{-- =================================================
                     PROCESSED
                ================================================== --}}
                @elseif($paymentRefund->status === 'processed')

                    <span class="badge bg-success p-2">

                        <i class="bx bx-check-double me-1"></i>

                        Refund Processed

                    </span>


                {{-- =================================================
                     REJECTED
                ================================================== --}}
                @elseif($paymentRefund->status === 'rejected')

                    <span class="badge bg-danger p-2">

                        <i class="bx bx-x-circle me-1"></i>

                        Refund Rejected

                    </span>


                {{-- =================================================
                     CANCELLED
                ================================================== --}}
                @elseif($paymentRefund->status === 'cancelled')

                    <span class="badge bg-secondary p-2">

                        <i class="bx bx-block me-1"></i>

                        Refund Cancelled

                    </span>

                @endif


            </div>

        </div>

    </div>

</div>


{{-- ================================================================
     REJECT REFUND MODAL
================================================================ --}}
@can('payment-refunds.reject')

<div
    class="modal fade"
    id="rejectRefundModal"
    tabindex="-1"
    aria-labelledby="rejectRefundModalLabel"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">


            {{-- Modal Header --}}
            <div class="modal-header">

                <h5
                    class="modal-title"
                    id="rejectRefundModalLabel"
                >

                    <i class="bx bx-x-circle text-danger me-1"></i>

                    Reject Refund

                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>

            </div>


            {{-- Modal Form --}}
            <form
                method="POST"
                action="{{ route(
                    'admin.payment-refunds.reject',
                    $paymentRefund->id
                ) }}"
            >

                @csrf

                @method('PATCH')


                <div class="modal-body">


                    {{-- Warning --}}
                    <div class="alert alert-warning">

                        <i class="bx bx-info-circle me-1"></i>

                        Please provide a reason for rejecting this
                        refund request. This information will be retained
                        in the refund record for audit purposes.

                    </div>


                    {{-- Rejection Reason --}}
                    <div class="mb-3">

                        <label
                            for="refund_rejection_reason"
                            class="form-label"
                        >

                            Rejection Reason

                            <span class="text-danger">
                                *
                            </span>

                        </label>


                        <textarea
                            name="reason"
                            id="refund_rejection_reason"
                            class="form-control"
                            rows="4"
                            maxlength="500"
                            required
                            placeholder="Enter the reason for rejecting this refund..."
                        >{{ old('reason') }}</textarea>


                        <div class="form-text">
                            Maximum 500 characters.
                        </div>


                        @error('reason')

                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Additional Notes --}}
                    <div class="mb-0">

                        <label
                            for="refund_rejection_notes"
                            class="form-label"
                        >

                            Additional Notes

                            <span class="text-muted">
                                (Optional)
                            </span>

                        </label>


                        <textarea
                            name="notes"
                            id="refund_rejection_notes"
                            class="form-control"
                            rows="3"
                            maxlength="2000"
                            placeholder="Additional notes, if any..."
                        >{{ old('notes') }}</textarea>


                        <div class="form-text">
                            Maximum 2,000 characters.
                        </div>


                        @error('notes')

                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>


                {{-- Modal Footer --}}
                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal"
                    >

                        Cancel

                    </button>


                    <button
                        type="submit"
                        class="btn btn-danger"
                    >

                        <i class="bx bx-x-circle me-1"></i>

                        Confirm Rejection

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endcan


{{-- ================================================================
     RE-OPEN REJECT MODAL WHEN VALIDATION FAILS
================================================================ --}}
@if($errors->has('reason') || $errors->has('notes'))

<script>

    document.addEventListener('DOMContentLoaded', function () {

        const modalElement = document.getElementById('rejectRefundModal');

        if (modalElement && typeof bootstrap !== 'undefined') {

            const rejectModal = new bootstrap.Modal(modalElement);

            rejectModal.show();

        }

    });

</script>

@endif


@endsection