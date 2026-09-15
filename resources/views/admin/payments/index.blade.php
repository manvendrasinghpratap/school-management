@extends('backend.layout.default')

@section('title', 'Payment Management')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row">

        <div class="col-12">

            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0">
                    Payment Management
                </h4>

                <div class="page-title-right">

                    @can('payments.create')

                        <a
                            href="{{ route('admin.payments.create') }}"
                            class="btn btn-primary"
                        >
                            <i class="bx bx-plus me-1"></i>
                            Collect Payment
                        </a>

                    @endcan

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


    {{-- Filters --}}
    <div class="card">

        <div class="card-header">

            <h5 class="card-title mb-0">

                <i class="bx bx-filter-alt me-1"></i>

                Payment Filters

            </h5>

        </div>


        <div class="card-body">

            <form
                method="GET"
                action="{{ route('admin.payments.index') }}"
            >

                <div class="row">

                    {{-- Search --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            value="{{ request('search') }}"
                            placeholder="Invoice number, student, reference..."
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

                            <option
                                value="cash"
                                {{ request('payment_method') === 'cash'
                                    ? 'selected'
                                    : '' }}
                            >
                                Cash
                            </option>

                            <option
                                value="bank_transfer"
                                {{ request('payment_method') === 'bank_transfer'
                                    ? 'selected'
                                    : '' }}
                            >
                                Bank Transfer
                            </option>

                            <option
                                value="card"
                                {{ request('payment_method') === 'card'
                                    ? 'selected'
                                    : '' }}
                            >
                                Card
                            </option>

                            <option
                                value="online"
                                {{ request('payment_method') === 'online'
                                    ? 'selected'
                                    : '' }}
                            >
                                Online
                            </option>

                            <option
                                value="mobile_money"
                                {{ request('payment_method') === 'mobile_money'
                                    ? 'selected'
                                    : '' }}
                            >
                                Mobile Money
                            </option>

                            <option
                                value="other"
                                {{ request('payment_method') === 'other'
                                    ? 'selected'
                                    : '' }}
                            >
                                Other
                            </option>

                        </select>

                    </div>


                    {{-- Date From --}}
                    <div class="col-md-2 mb-3">

                        <label class="form-label">
                            From
                        </label>

                        <input
                            type="date"
                            name="date_from"
                            class="form-control"
                            value="{{ request('date_from') }}"
                        >

                    </div>


                    {{-- Date To --}}
                    <div class="col-md-2 mb-3">

                        <label class="form-label">
                            To
                        </label>

                        <input
                            type="date"
                            name="date_to"
                            class="form-control"
                            value="{{ request('date_to') }}"
                        >

                    </div>


                    {{-- Filter --}}
                    <div class="col-md-1 mb-3 d-flex align-items-end">

                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                            title="Filter"
                        >
                            <i class="bx bx-search"></i>
                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Payment Table --}}
    <div class="card">

        <div class="card-header d-flex align-items-center justify-content-between">

            <h5 class="card-title mb-0">
                Payments
            </h5>

            <span class="badge bg-primary">
                {{ $payments->total() }} Records
            </span>

        </div>


        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-light">

                        <tr>

                            <th>#</th>
                            <th>Payment Date</th>
                            <th>Invoice</th>
                            <th>Student</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Status</th>
                            <th>Reference</th>
                            <th>Received By</th>
                            <th width="140">Action</th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse($payments as $payment)

                        @php

                            /*
                             * Only requested, approved and processed
                             * refunds are considered active.
                             *
                             * Rejected and cancelled refunds do not
                             * block a new refund request.
                             */
                            $activeRefund = $payment->refunds
                                ->whereIn('status', [
                                    'requested',
                                    'approved',
                                    'processed',
                                ])
                                ->sortByDesc('id')
                                ->first();

                            $paymentStatus = $activeRefund?->status;

                            $statusLabels = [
                                'requested' => 'Refund Requested',
                                'approved' => 'Refund Approved',
                                'processed' => 'Refunded',
                            ];

                            $statusClasses = [
                                'requested' => 'bg-warning',
                                'approved' => 'bg-info',
                                'processed' => 'bg-success',
                            ];

                        @endphp


                        <tr>

                            {{-- Number --}}
                            <td>
                                {{ $payments->firstItem() + $loop->index }}
                            </td>


                            {{-- Date --}}
                            <td>
                                {{ optional($payment->paid_at)
                                    ->format('d M Y H:i') }}
                            </td>


                            {{-- Invoice --}}
                            <td>

                                @if($payment->invoice)

                                    <a
                                        href="{{ route(
                                            'admin.invoices.show',
                                            $payment->invoice->id
                                        ) }}"
                                        class="fw-semibold"
                                    >
                                        {{ $payment->invoice->invoice_number }}
                                    </a>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- Student --}}
                            <td>

                                @if($payment->invoice?->student)

                                    <div class="fw-semibold">

                                        {{ $payment->invoice->student->first_name }}

                                        {{ $payment->invoice->student->middle_name }}

                                        {{ $payment->invoice->student->last_name }}

                                    </div>

                                    <small class="text-muted">

                                        {{ $payment->invoice->student->student_number }}

                                    </small>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- Amount --}}
                            <td>

                                <span class="fw-bold">

                                    ₹{{ number_format(
                                        (float) $payment->amount,
                                        2
                                    ) }}

                                </span>

                            </td>


                            {{-- Method --}}
                            <td>

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

                            </td>


                            {{-- Status --}}
                            <td>

                                @if($paymentStatus)

                                    <span
                                        class="badge {{ $statusClasses[$paymentStatus] }}"
                                    >
                                        {{ $statusLabels[$paymentStatus] }}
                                    </span>

                                @else

                                    <span class="badge bg-success">
                                        Recorded
                                    </span>

                                @endif

                            </td>


                            {{-- Reference --}}
                            <td>

                                @if($payment->transaction_reference)

                                    {{ $payment->transaction_reference }}

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- Received By --}}
                            <td>

                                @if($payment->receivedBy)

                                    {{ $payment->receivedBy->name }}

                                @else

                                    <span class="text-muted">
                                        System
                                    </span>

                                @endif

                            </td>


                            {{-- Actions --}}
                            <td>

                                <div class="d-flex gap-1">

                                    {{-- View --}}
                                    <a
                                        href="{{ route(
                                            'admin.payments.show',
                                            $payment->id
                                        ) }}"
                                        class="btn btn-sm btn-info"
                                        title="View Payment"
                                    >
                                        <i class="bx bx-show"></i>
                                    </a>


                                    {{-- Request Refund --}}
                                    @can('payments.reverse')

                                        @if(
                                            !$activeRefund &&
                                            !$payment->trashed()
                                        )

                                            <form
                                                method="POST"
                                                action="{{ route(
                                                    'admin.payments.reverse',
                                                    $payment->id
                                                ) }}"
                                                class="d-inline"
                                                onsubmit="return confirm(
                                                    'Are you sure you want to request a refund for this payment?'
                                                );"
                                            >

                                                @csrf

                                                @method('PATCH')

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-warning"
                                                    title="Request Refund"
                                                >
                                                    <i class="bx bx-undo"></i>
                                                </button>

                                            </form>

                                        @endif

                                    @endcan

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="10"
                                class="text-center py-4"
                            >

                                <div class="text-muted">

                                    <i
                                        class="bx bx-money"
                                        style="font-size: 40px;"
                                    ></i>

                                    <div class="mt-2">
                                        No payments found.
                                    </div>

                                    @can('payments.create')

                                        <a
                                            href="{{ route(
                                                'admin.payments.create'
                                            ) }}"
                                            class="btn btn-primary btn-sm mt-3"
                                        >
                                            <i class="bx bx-plus me-1"></i>
                                            Collect First Payment
                                        </a>

                                    @endcan

                                </div>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>


            {{-- Pagination --}}
            @if($payments->hasPages())

                <div class="mt-3">

                    {{ $payments->links() }}

                </div>

            @endif

        </div>

    </div>

</div>

@endsection