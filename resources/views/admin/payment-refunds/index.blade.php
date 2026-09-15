@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Payment Refunds</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ url('/admin/dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">
                            Payment Refunds
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="mdi mdi-check-circle-outline me-1"></i>
            {{ session('success') }}
            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="mdi mdi-alert-circle-outline me-1"></i>
            {{ session('error') }}
            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Please check the following:</strong>

            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    @endif

    {{-- Filters --}}
    <div class="card">
        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.payment-refunds.index') }}">

                <div class="row g-3">

                    {{-- Search --}}
                    <div class="col-md-4">
                        <label class="form-label">
                            Search
                        </label>

                        <input type="text"
                               name="search"
                               class="form-control"
                               value="{{ request('search') }}"
                               placeholder="Student, invoice, transaction reference...">
                    </div>

                    {{-- Status --}}
                    <div class="col-md-2">
                        <label class="form-label">
                            Status
                        </label>

                        <select name="status"
                                class="form-select">

                            <option value="">
                                All Statuses
                            </option>

                            @foreach([
                                'requested' => 'Requested',
                                'approved' => 'Approved',
                                'processed' => 'Processed',
                                'rejected' => 'Rejected',
                                'cancelled' => 'Cancelled',
                            ] as $value => $label)

                                <option value="{{ $value }}"
                                    {{ request('status') === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>

                            @endforeach

                        </select>
                    </div>

                    {{-- From Date --}}
                    <div class="col-md-2">
                        <label class="form-label">
                            From Date
                        </label>

                        <input type="date"
                               name="date_from"
                               class="form-control"
                               value="{{ request('date_from') }}">
                    </div>

                    {{-- To Date --}}
                    <div class="col-md-2">
                        <label class="form-label">
                            To Date
                        </label>

                        <input type="date"
                               name="date_to"
                               class="form-control"
                               value="{{ request('date_to') }}">
                    </div>

                    {{-- Buttons --}}
                    <div class="col-md-2 d-flex align-items-end">

                        <div class="d-flex gap-2 w-100">

                            <button type="submit"
                                    class="btn btn-primary w-100">
                                <i class="mdi mdi-filter-outline me-1"></i>
                                Filter
                            </button>

                            <a href="{{ route('admin.payment-refunds.index') }}"
                               class="btn btn-light"
                               title="Clear filters">
                                <i class="mdi mdi-refresh"></i>
                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>
    </div>

    {{-- Refunds Table --}}
    <div class="card">
        <div class="card-body">

            <div class="d-flex align-items-center justify-content-between mb-3">

                <div>
                    <h4 class="card-title mb-1">
                        Refund Requests
                    </h4>

                    <p class="text-muted mb-0">
                        Review and manage payment reversal requests.
                    </p>
                </div>

                <div>
                    <span class="badge bg-secondary">
                        {{ $refunds->total() }} Total
                    </span>
                </div>

            </div>

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>
                            <th width="70">#</th>
                            <th>Student</th>
                            <th>Invoice</th>
                            <th>Payment</th>
                            <th>Refund Amount</th>
                            <th>Status</th>
                            <th>Requested By</th>
                            <th>Requested At</th>
                            <th width="100">Action</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($refunds as $refund)

                            @php
                                $statusClass = match($refund->status) {
                                    'requested' => 'bg-warning text-dark',
                                    'approved' => 'bg-info',
                                    'processed' => 'bg-success',
                                    'rejected' => 'bg-danger',
                                    'cancelled' => 'bg-secondary',
                                    default => 'bg-secondary',
                                };
                            @endphp

                            <tr>

                                {{-- Number --}}
                                <td>
                                    {{ $refunds->firstItem() + $loop->index }}
                                </td>

                                {{-- Student --}}
                                <td>

                                    @if($refund->student)

                                        <div class="fw-semibold">
                                            {{ trim(
                                                $refund->student->first_name . ' ' .
                                                ($refund->student->middle_name ?? '') . ' ' .
                                                $refund->student->last_name
                                            ) }}
                                        </div>

                                        @if($refund->student->student_number)
                                            <small class="text-muted">
                                                {{ $refund->student->student_number }}
                                            </small>
                                        @endif

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>

                                {{-- Invoice --}}
                                <td>

                                    @if($refund->payment?->invoice)

                                        <span class="fw-semibold">
                                            {{ $refund->payment->invoice->invoice_number }}
                                        </span>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>

                                {{-- Payment --}}
                                <td>

                                    @if($refund->payment)

                                        <div>
                                            <span class="fw-semibold">
                                                ₹{{ number_format((float) $refund->payment->amount, 2) }}
                                            </span>
                                        </div>

                                        @if($refund->payment->transaction_reference)

                                            <small class="text-muted">
                                                Ref:
                                                {{ $refund->payment->transaction_reference }}
                                            </small>

                                        @endif

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>

                                {{-- Refund Amount --}}
                                <td>

                                    <span class="fw-semibold text-danger">
                                        ₹{{ number_format((float) $refund->amount, 2) }}
                                    </span>

                                </td>

                                {{-- Status --}}
                                <td>

                                    <span class="badge {{ $statusClass }}">
                                        {{ ucfirst($refund->status) }}
                                    </span>

                                </td>

                                {{-- Requested By --}}
                                <td>

                                    @if($refund->requester)

                                        {{ $refund->requester->name }}

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>

                                {{-- Requested At --}}
                                <td>

                                    @if($refund->requested_at)

                                        {{ $refund->requested_at->format('d M Y, h:i A') }}

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>

                                {{-- Action --}}
                                <td>

                                    <a href="{{ route(
                                        'admin.payment-refunds.show',
                                        $refund
                                    ) }}"
                                       class="btn btn-sm btn-primary"
                                       title="View refund">

                                        <i class="mdi mdi-eye-outline"></i>

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="9"
                                    class="text-center py-5">

                                    <div class="text-muted">

                                        <i class="mdi mdi-cash-refund mdi-36px d-block mb-2"></i>

                                        <h5 class="mb-1">
                                            No refund requests found
                                        </h5>

                                        <p class="mb-0">
                                            Payment reversal requests will appear here.
                                        </p>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- Pagination --}}
            @if($refunds->hasPages())

                <div class="mt-3">

                    {{ $refunds->links() }}

                </div>

            @endif

        </div>
    </div>

</div>

@endsection