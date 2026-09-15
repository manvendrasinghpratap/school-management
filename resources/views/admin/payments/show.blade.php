@extends('backend.layout.default')

@section('title', 'Payment Details')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="row">

        <div class="col-12">

            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0">
                    Payment Details
                </h4>

                <div class="page-title-right">

                    <a
                        href="{{ route('admin.payments.index') }}"
                        class="btn btn-secondary"
                    >
                        <i class="bx bx-arrow-back me-1"></i>
                        Back to Payments
                    </a>

                </div>

            </div>

        </div>

    </div>


    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bx bx-check-circle me-1"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- Error Message --}}
    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="bx bx-error-circle me-1"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    @php

        /*
         * Sort refund history newest first.
         */
        $refunds = $payment->refunds
            ->sortByDesc('id');


        /*
         * Active refund statuses.
         *
         * These statuses prevent another refund request.
         */
        $activeRefund = $refunds
            ->whereIn('status', [
                'requested',
                'approved',
                'processed',
            ])
            ->first();


        /*
         * Processed refund total.
         */
        $processedRefundAmount = $refunds
            ->where('status', 'processed')
            ->sum('amount');


        /*
         * Active/reserved refund total.
         */
        $activeRefundAmount = $refunds
            ->whereIn('status', [
                'requested',
                'approved',
                'processed',
            ])
            ->sum('amount');


        /*
         * Remaining refundable amount.
         */
        $remainingRefundable = max(
            (float) $payment->amount
            - (float) $activeRefundAmount,
            0
        );


        /*
         * Current active refund status.
         */
        $refundStatus = $activeRefund?->status;


        $refundStatusLabels = [
            'requested' => 'Refund Requested',
            'approved'  => 'Refund Approved',
            'processed' => 'Refunded',
        ];


        $refundStatusClasses = [
            'requested' => 'bg-warning',
            'approved'  => 'bg-info',
            'processed' => 'bg-success',
        ];

    @endphp


    {{-- Payment Information --}}
    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">

            <h5 class="card-title mb-0">

                <i class="bx bx-money me-1"></i>

                Payment Information

            </h5>


            {{-- Payment / Refund Status --}}
            @if($refundStatus)

                <span
                    class="badge {{ $refundStatusClasses[$refundStatus] ?? 'bg-secondary' }}"
                >

                    {{ $refundStatusLabels[$refundStatus]
                        ?? ucfirst($refundStatus) }}

                </span>

            @else

                <span class="badge bg-success">
                    Recorded Payment
                </span>

            @endif

        </div>


        <div class="card-body">

            <div class="row">

                {{-- Payment ID --}}
                <div class="col-md-3 mb-4">

                    <small class="text-muted d-block">
                        Payment ID
                    </small>

                    <strong>
                        #{{ $payment->id }}
                    </strong>

                </div>


                {{-- Original Payment --}}
                <div class="col-md-3 mb-4">

                    <small class="text-muted d-block">
                        Original Payment
                    </small>

                    <strong class="fs-5">

                        ₹{{ number_format(
                            (float) $payment->amount,
                            2
                        ) }}

                    </strong>

                </div>


                {{-- Refunded Amount --}}
                <div class="col-md-3 mb-4">

                    <small class="text-muted d-block">
                        Refunded Amount
                    </small>

                    <strong class="fs-5">

                        ₹{{ number_format(
                            (float) $processedRefundAmount,
                            2
                        ) }}

                    </strong>

                </div>


                {{-- Net Payment --}}
                <div class="col-md-3 mb-4">

                    <small class="text-muted d-block">
                        Net Payment
                    </small>

                    <strong class="fs-5">

                        ₹{{ number_format(
                            max(
                                (float) $payment->amount
                                - (float) $processedRefundAmount,
                                0
                            ),
                            2
                        ) }}

                    </strong>

                </div>


                {{-- Payment Method --}}
                <div class="col-md-3 mb-4">

                    <small class="text-muted d-block">
                        Payment Method
                    </small>

                    @php

                        $methodLabels = [
                            'cash' => 'Cash',
                            'bank_transfer' => 'Bank Transfer',
                            'card' => 'Card',
                            'online' => 'Online',
                            'mobile_money' => 'Mobile Money',
                            'other' => 'Other',
                        ];

                    @endphp

                    <span class="badge bg-info">

                        {{ $methodLabels[$payment->payment_method]
                            ?? ucfirst($payment->payment_method) }}

                    </span>

                </div>


                {{-- Paid At --}}
                <div class="col-md-3 mb-4">

                    <small class="text-muted d-block">
                        Paid At
                    </small>

                    <strong>

                        {{ optional($payment->paid_at)
                            ->format('d M Y H:i') }}

                    </strong>

                </div>


                {{-- Transaction Reference --}}
                <div class="col-md-3 mb-4">

                    <small class="text-muted d-block">
                        Transaction Reference
                    </small>

                    <strong>

                        {{ $payment->transaction_reference ?: '—' }}

                    </strong>

                </div>


                {{-- Received By --}}
                <div class="col-md-3 mb-4">

                    <small class="text-muted d-block">
                        Received By
                    </small>

                    <strong>

                        {{ $payment->receivedBy?->name ?? 'System' }}

                    </strong>

                </div>


                {{-- Notes --}}
                <div class="col-md-12 mb-2">

                    <small class="text-muted d-block">
                        Notes
                    </small>

                    <div class="border rounded p-3 bg-light">

                        {{ $payment->notes ?: 'No notes provided.' }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Refund History --}}
    @if($refunds->count())

        <div class="card">

            <div class="card-header">

                <h5 class="card-title mb-0">

                    <i class="bx bx-undo me-1"></i>

                    Refund History

                </h5>

            </div>


            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle">

                        <thead class="table-light">

                            <tr>

                                <th>#</th>
                                <th>Refund Amount</th>
                                <th>Status</th>
                                <th>Requested By</th>
                                <th>Requested At</th>
                                <th>Approved By</th>
                                <th>Processed By</th>

                            </tr>

                        </thead>


                        <tbody>

                        @foreach($refunds as $refund)

                            @php

                                $refundLabels = [
                                    'requested' => 'Requested',
                                    'approved' => 'Approved',
                                    'processed' => 'Processed',
                                    'rejected' => 'Rejected',
                                    'cancelled' => 'Cancelled',
                                ];


                                $refundClasses = [
                                    'requested' => 'bg-warning',
                                    'approved' => 'bg-info',
                                    'processed' => 'bg-success',
                                    'rejected' => 'bg-danger',
                                    'cancelled' => 'bg-secondary',
                                ];

                            @endphp


                            <tr>

                                <td>
                                    #{{ $refund->id }}
                                </td>


                                <td>

                                    <strong>

                                        ₹{{ number_format(
                                            (float) $refund->amount,
                                            2
                                        ) }}

                                    </strong>

                                </td>


                                <td>

                                    <span
                                        class="badge {{ $refundClasses[$refund->status] ?? 'bg-secondary' }}"
                                    >

                                        {{ $refundLabels[$refund->status]
                                            ?? ucfirst($refund->status) }}

                                    </span>

                                </td>


                                <td>

                                    {{ $refund->requester?->name ?? '—' }}

                                </td>


                                <td>

                                    {{ optional($refund->requested_at)
                                        ->format('d M Y H:i') }}

                                </td>


                                <td>

                                    {{ $refund->approver?->name ?? '—' }}

                                </td>


                                <td>

                                    {{ $refund->processor?->name ?? '—' }}

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    @endif


    {{-- Invoice Information --}}
    <div class="card">

        <div class="card-header">

            <h5 class="card-title mb-0">

                <i class="bx bx-receipt me-1"></i>

                Invoice Information

            </h5>

        </div>


        <div class="card-body">

            @if($payment->invoice)

                <div class="row">

                    {{-- Invoice Number --}}
                    <div class="col-md-3 mb-3">

                        <small class="text-muted d-block">
                            Invoice Number
                        </small>

                        <a
                            href="{{ route(
                                'admin.invoices.show',
                                $payment->invoice->id
                            ) }}"
                            class="fw-bold"
                        >

                            {{ $payment->invoice->invoice_number }}

                        </a>

                    </div>


                    {{-- Invoice Date --}}
                    <div class="col-md-3 mb-3">

                        <small class="text-muted d-block">
                            Invoice Date
                        </small>

                        <strong>

                            {{ optional($payment->invoice->invoice_date)
                                ->format('d M Y') }}

                        </strong>

                    </div>


                    {{-- Due Date --}}
                    <div class="col-md-3 mb-3">

                        <small class="text-muted d-block">
                            Due Date
                        </small>

                        <strong>

                            {{ optional($payment->invoice->due_date)
                                ->format('d M Y') ?: '—' }}

                        </strong>

                    </div>


                    {{-- Invoice Status --}}
                    <div class="col-md-3 mb-3">

                        <small class="text-muted d-block">
                            Status
                        </small>


                        @if($payment->invoice->status === 'paid')

                            <span class="badge bg-success">
                                Paid
                            </span>

                        @elseif($payment->invoice->status === 'partial')

                            <span class="badge bg-warning">
                                Partial
                            </span>

                        @elseif($payment->invoice->status === 'cancelled')

                            <span class="badge bg-danger">
                                Cancelled
                            </span>

                        @else

                            <span class="badge bg-secondary">
                                Unpaid
                            </span>

                        @endif

                    </div>


                    {{-- Invoice Total --}}
                    <div class="col-md-3 mb-3">

                        <small class="text-muted d-block">
                            Invoice Total
                        </small>

                        <strong>

                            ₹{{ number_format(
                                (float) $payment->invoice->total,
                                2
                            ) }}

                        </strong>

                    </div>


                    {{-- Total Paid --}}
                    <div class="col-md-3 mb-3">

                        <small class="text-muted d-block">
                            Total Paid
                        </small>

                        <strong>

                            ₹{{ number_format(
                                (float) $payment->invoice->paid,
                                2
                            ) }}

                        </strong>

                    </div>


                    {{-- Balance --}}
                    <div class="col-md-3 mb-3">

                        <small class="text-muted d-block">
                            Outstanding Balance
                        </small>

                        <strong>

                            ₹{{ number_format(
                                (float) $payment->invoice->balance,
                                2
                            ) }}

                        </strong>

                    </div>

                </div>

            @else

                <div class="alert alert-warning mb-0">

                    The invoice associated with this payment
                    is no longer available.

                </div>

            @endif

        </div>

    </div>


    {{-- Payment Allocation --}}
    <div class="card">

        <div class="card-header">

            <h5 class="card-title mb-0">

                <i class="bx bx-transfer me-1"></i>

                Payment Allocation

            </h5>

        </div>


        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered align-middle">

                    <thead class="table-light">

                        <tr>

                            <th>#</th>
                            <th>Invoice Item</th>
                            <th>Allocated Amount</th>
                            <th>Allocated At</th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse($payment->allocations as $allocation)

                        <tr>

                            <td>
                                {{ $loop->iteration }}
                            </td>


                            <td>

                                @if($allocation->invoiceItem)

                                    {{ $allocation->invoiceItem->description }}

                                @else

                                    <span class="text-muted">
                                        Invoice-level allocation
                                    </span>

                                @endif

                            </td>


                            <td>

                                <strong>

                                    ₹{{ number_format(
                                        (float) $allocation->amount,
                                        2
                                    ) }}

                                </strong>

                            </td>


                            <td>

                                {{ optional($allocation->allocated_at)
                                    ->format('d M Y H:i') }}

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="4"
                                class="text-center text-muted"
                            >

                                No payment allocation found.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- Financial Record Notice --}}
    <div class="alert alert-info">

        <i class="bx bx-info-circle me-1"></i>

        <strong>Financial Record:</strong>

        This payment is an immutable financial record.
        It should not be edited or deleted directly.

        @if($refundStatus === 'requested')

            A refund request is currently awaiting approval.

        @elseif($refundStatus === 'approved')

            The refund has been approved and is awaiting processing.

        @elseif($refundStatus === 'processed')

            This payment has been refunded. The original payment
            record remains available for audit purposes.

        @else

            Refunds and corrections are handled through the
            payment refund workflow.

        @endif

    </div>


    {{-- Actions --}}
    <div class="card">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                {{-- Back --}}
                <a
                    href="{{ route('admin.payments.index') }}"
                    class="btn btn-light"
                >

                    <i class="bx bx-arrow-back me-1"></i>

                    Back

                </a>


                <div class="d-flex gap-2">


                    {{-- Refund Requested --}}
                    @if($refundStatus === 'requested')

                        <a
                            href="{{ route(
                                'admin.payment-refunds.show',
                                $activeRefund->id
                            ) }}"
                            class="btn btn-warning"
                        >

                            <i class="bx bx-time-five me-1"></i>

                            Refund Requested

                        </a>


                    {{-- Refund Approved --}}
                    @elseif($refundStatus === 'approved')

                        <a
                            href="{{ route(
                                'admin.payment-refunds.show',
                                $activeRefund->id
                            ) }}"
                            class="btn btn-info"
                        >

                            <i class="bx bx-check me-1"></i>

                            Refund Approved

                        </a>


                    {{-- Refund Processed --}}
                    @elseif($refundStatus === 'processed')

                        <a
                            href="{{ route(
                                'admin.payment-refunds.show',
                                $activeRefund->id
                            ) }}"
                            class="btn btn-success"
                        >

                            <i class="bx bx-check-double me-1"></i>

                            Refunded

                        </a>


                    {{-- No Active Refund --}}
                    @else

                        @can('payments.reverse')

                            @if(
                                !$payment->trashed() &&
                                $remainingRefundable > 0
                            )

                                <form
                                    method="POST"
                                    action="{{ route(
                                        'admin.payments.reverse',
                                        $payment->id
                                    ) }}"
                                    onsubmit="return confirm(
                                        'Are you sure you want to request a refund for this payment?'
                                    );"
                                >

                                    @csrf

                                    @method('PATCH')

                                    <button
                                        type="submit"
                                        class="btn btn-warning"
                                    >

                                        <i class="bx bx-undo me-1"></i>

                                        Request Refund

                                    </button>

                                </form>

                            @endif

                        @endcan

                    @endif

                </div>

            </div>

        </div>

    </div>


</div>

@endsection