@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0 font-size-18">
                    Invoice Details
                </h4>

                <div class="page-title-right d-flex gap-2">

                    @can('invoices.manage')
                        @if ($invoice->status !== 'cancelled')
                            <a href="{{ route('admin.invoices.edit', $invoice) }}"
                               class="btn btn-primary">
                                <i class="bx bx-edit me-1"></i>
                                Edit Invoice
                            </a>
                        @endif
                    @endcan

                    <a href="{{ route('admin.invoices.index') }}"
                       class="btn btn-secondary">
                        <i class="bx bx-arrow-back me-1"></i>
                        Back to Invoices
                    </a>

                </div>

            </div>
        </div>
    </div>

    {{-- Success Message --}}
    @if (session('success'))
        <div class="alert alert-success">
            <i class="bx bx-check-circle me-1"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- Error Message --}}
    @if (session('error'))
        <div class="alert alert-danger">
            <i class="bx bx-error-circle me-1"></i>
            {{ session('error') }}
        </div>
    @endif


    {{-- Invoice Summary --}}
    <div class="row">

        {{-- Invoice Information --}}
        <div class="col-lg-8">

            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-receipt me-1"></i>
                        Invoice Information
                    </h5>
                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-4 mb-3">
                            <label class="text-muted">Invoice Number</label>
                            <div class="fw-semibold">
                                {{ $invoice->invoice_number }}
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="text-muted">Invoice Date</label>
                            <div class="fw-semibold">
                                {{ optional($invoice->invoice_date)->format('d M Y') }}
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="text-muted">Due Date</label>
                            <div class="fw-semibold">
                                {{ $invoice->due_date ? $invoice->due_date->format('d M Y') : '—' }}
                            </div>
                        </div>

                    </div>

                    <hr>

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="text-muted">Student</label>

                            <div class="fw-semibold">
                                {{ $invoice->student?->first_name }}
                                {{ $invoice->student?->middle_name }}
                                {{ $invoice->student?->last_name }}
                            </div>

                            @if ($invoice->student?->student_number)
                                <small class="text-muted">
                                    {{ $invoice->student->student_number }}
                                </small>
                            @endif
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="text-muted">Student ID</label>

                            <div class="fw-semibold">
                                {{ $invoice->student_id }}
                            </div>
                        </div>

                    </div>

                </div>

            </div>


            {{-- Invoice Items --}}
            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-list-ul me-1"></i>
                        Invoice Items
                    </h5>
                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-bordered align-middle">

                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Fee Category</th>
                                    <th>Description</th>
                                    <th class="text-end">Quantity</th>
                                    <th class="text-end">Amount</th>
                                    <th class="text-end">Line Total</th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse ($invoice->items as $index => $item)

                                    <tr>

                                        <td>
                                            {{ $index + 1 }}
                                        </td>

                                        <td>
                                            {{ $item->feeCategory?->name ?? '—' }}
                                        </td>

                                        <td>
                                            {{ $item->description }}
                                        </td>

                                        <td class="text-end">
                                            {{ number_format((float) $item->quantity, 2) }}
                                        </td>

                                        <td class="text-end">
                                            ₹{{ number_format((float) $item->amount, 2) }}
                                        </td>

                                        <td class="text-end fw-semibold">
                                            ₹{{ number_format(
                                                (float) $item->quantity * (float) $item->amount,
                                                2
                                            ) }}
                                        </td>

                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="6"
                                            class="text-center text-muted py-4">
                                            No invoice items found.
                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                            <tfoot>

                                <tr>
                                    <th colspan="5" class="text-end">
                                        Subtotal
                                    </th>

                                    <th class="text-end">
                                        ₹{{ number_format((float) $invoice->subtotal, 2) }}
                                    </th>
                                </tr>

                                <tr>
                                    <th colspan="5" class="text-end">
                                        Discount
                                    </th>

                                    <th class="text-end">
                                        ₹{{ number_format((float) $invoice->discount, 2) }}
                                    </th>
                                </tr>

                                <tr>
                                    <th colspan="5" class="text-end">
                                        <strong>Total</strong>
                                    </th>

                                    <th class="text-end">
                                        <strong>
                                            ₹{{ number_format((float) $invoice->total, 2) }}
                                        </strong>
                                    </th>
                                </tr>

                            </tfoot>

                        </table>

                    </div>

                </div>

            </div>

        </div>


        {{-- Financial Summary --}}
        <div class="col-lg-4">

            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-money me-1"></i>
                        Financial Summary
                    </h5>
                </div>

                <div class="card-body">

                    <div class="d-flex justify-content-between mb-3">
                        <span>Total</span>
                        <strong>
                            ₹{{ number_format((float) $invoice->total, 2) }}
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span>Paid</span>
                        <strong>
                            ₹{{ number_format((float) $invoice->paid, 2) }}
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span>Balance</span>
                        <strong>
                            ₹{{ number_format((float) $invoice->balance, 2) }}
                        </strong>
                    </div>

                    <hr>

                    <div class="text-center">

                        @switch($invoice->status)

                            @case('paid')
                                <span class="badge bg-success fs-6">
                                    Paid
                                </span>
                                @break

                            @case('partial')
                                <span class="badge bg-warning text-dark fs-6">
                                    Partial
                                </span>
                                @break

                            @case('cancelled')
                                <span class="badge bg-danger fs-6">
                                    Cancelled
                                </span>
                                @break

                            @default
                                <span class="badge bg-danger fs-6">
                                    Unpaid
                                </span>

                        @endswitch

                    </div>

                </div>

            </div>


            {{-- Payment Information --}}
            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-credit-card me-1"></i>
                        Payments
                    </h5>
                </div>

                <div class="card-body">

                    @if ($invoice->payments->count())

                        <div class="table-responsive">

                            <table class="table table-sm table-bordered">

                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Method</th>
                                        <th class="text-end">Amount</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @foreach ($invoice->payments as $payment)

                                        <tr>

                                            <td>
                                                {{ optional($payment->paid_at)->format('d M Y') }}
                                            </td>

                                            <td>
                                                {{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}
                                            </td>

                                            <td class="text-end">
                                                ₹{{ number_format((float) $payment->amount, 2) }}
                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <p class="text-muted mb-0">
                            No payments recorded yet.
                        </p>

                    @endif

                </div>

            </div>


            {{-- Invoice Actions --}}
            @can('invoices.manage')

                @if ($invoice->status !== 'cancelled')

                    <div class="card">

                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bx bx-cog me-1"></i>
                                Actions
                            </h5>
                        </div>

                        <div class="card-body">

                            <form method="POST"
                                  action="{{ route('admin.invoices.cancel', $invoice) }}"
                                  onsubmit="return confirm('Are you sure you want to cancel this invoice?');">

                                @csrf
                                @method('PATCH')

                                <button type="submit"
                                        class="btn btn-danger w-100">
                                    <i class="bx bx-x-circle me-1"></i>
                                    Cancel Invoice
                                </button>

                            </form>

                        </div>

                    </div>

                @endif

            @endcan

        </div>

    </div>

</div>

@endsection