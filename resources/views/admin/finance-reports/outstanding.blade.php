@extends('backend.layout.default')

@section('title', 'Outstanding Fees Report')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row mb-3">
        <div class="col-12">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h4 class="mb-1">
                        Outstanding Fees Report
                    </h4>

                    <p class="text-muted mb-0">
                        View unpaid and partially paid invoices and outstanding balances.
                    </p>
                </div>

                <div class="d-flex gap-2">

                    <a href="{{ route('admin.finance-reports.collection') }}"
                       class="btn btn-outline-primary">

                        <i class="bx bx-money me-1"></i>
                        Collection Report

                    </a>

                    <div class="d-flex gap-2">

    {{-- Excel Export --}}
    <a href="{{ route('admin.finance-reports.outstanding.excel', [
        'from' => $from,
        'to' => $to,
        'status' => $status,
        'student' => $studentSearch,
        'overdue' => $overdue ? 1 : 0,
    ]) }}"
       class="btn btn-success">

        <i class="bx bx-spreadsheet me-1"></i>
        Excel

    </a>


    {{-- PDF Export --}}
    <a href="{{ route('admin.finance-reports.outstanding.pdf', [
        'from' => $from,
        'to' => $to,
        'status' => $status,
        'student' => $studentSearch,
        'overdue' => $overdue ? 1 : 0,
    ]) }}"
       class="btn btn-danger"
       target="_blank">

        <i class="bx bx-file me-1"></i>
        PDF

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

            <form method="GET"
                  action="{{ route('admin.finance-reports.outstanding') }}">

                <div class="row">

                    {{-- From --}}
                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            Invoice Date From
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
                            Invoice Date To
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

                        <select name="status"
                                class="form-select">

                            <option value="all"
                                {{ $status === 'all' ? 'selected' : '' }}>
                                All Outstanding
                            </option>

                            <option value="unpaid"
                                {{ $status === 'unpaid' ? 'selected' : '' }}>
                                Unpaid
                            </option>

                            <option value="partial"
                                {{ $status === 'partial' ? 'selected' : '' }}>
                                Partial
                            </option>

                            <option value="pending"
                                {{ $status === 'pending' ? 'selected' : '' }}>
                                Pending
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

                </div>


                <div class="row align-items-center">

                    <div class="col-md-6">

                        <div class="form-check">

                            <input
                                type="checkbox"
                                name="overdue"
                                value="1"
                                class="form-check-input"
                                id="overdue"
                                {{ $overdue ? 'checked' : '' }}
                            >

                            <label
                                class="form-check-label"
                                for="overdue">

                                Show overdue invoices only

                            </label>

                        </div>

                    </div>


                    <div class="col-md-6 text-md-end mt-3 mt-md-0">

                        <button
                            type="submit"
                            class="btn btn-primary me-2">

                            <i class="bx bx-search me-1"></i>
                            Generate Report

                        </button>

                        <a
                            href="{{ route('admin.finance-reports.outstanding') }}"
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

        {{-- Invoices --}}
        <div class="col-xl-3 col-md-6">

            <div class="card">

                <div class="card-body">

                    <p class="text-muted mb-2">
                        Outstanding Invoices
                    </p>

                    <h4 class="mb-0 text-primary">
                        {{ number_format($totalInvoices) }}
                    </h4>

                    <small class="text-muted">
                        Matching invoices
                    </small>

                </div>

            </div>

        </div>


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
                        Invoice value
                    </small>

                </div>

            </div>

        </div>


        {{-- Total Paid --}}
        <div class="col-xl-3 col-md-6">

            <div class="card">

                <div class="card-body">

                    <p class="text-muted mb-2">
                        Total Paid
                    </p>

                    <h4 class="mb-0 text-success">
                        ₹{{ number_format($totalPaid, 2) }}
                    </h4>

                    <small class="text-muted">
                        Paid against invoices
                    </small>

                </div>

            </div>

        </div>


        {{-- Outstanding --}}
        <div class="col-xl-3 col-md-6">

            <div class="card">

                <div class="card-body">

                    <p class="text-muted mb-2">
                        Total Outstanding
                    </p>

                    <h4 class="mb-0 text-danger">
                        ₹{{ number_format($totalOutstanding, 2) }}
                    </h4>

                    <small class="text-muted">
                        Current balance
                    </small>

                </div>

            </div>

        </div>

    </div>


    {{-- Overdue Summary --}}
    <div class="row mb-4">

        <div class="col-xl-6">

            <div class="card border-danger">

                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div class="avatar-sm">

                            <span class="avatar-title rounded bg-danger-subtle text-danger font-size-20">

                                <i class="bx bx-error-circle"></i>

                            </span>

                        </div>

                        <div class="ms-3">

                            <p class="text-muted mb-1">
                                Overdue Invoices
                            </p>

                            <h5 class="mb-0 text-danger">
                                {{ number_format($overdueInvoices) }}
                            </h5>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-6">

            <div class="card border-warning">

                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div class="avatar-sm">

                            <span class="avatar-title rounded bg-warning-subtle text-warning font-size-20">

                                <i class="bx bx-time-five"></i>

                            </span>

                        </div>

                        <div class="ms-3">

                            <p class="text-muted mb-1">
                                Overdue Amount
                            </p>

                            <h5 class="mb-0 text-warning">
                                ₹{{ number_format($overdueAmount, 2) }}
                            </h5>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Invoice Table --}}
    <div class="card">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="card-title mb-0">

                    <i class="bx bx-receipt me-2"></i>
                    Outstanding Invoices

                </h5>

                <span class="badge bg-primary-subtle text-primary">

                    {{ number_format($invoices->total()) }}
                    records

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>Invoice</th>
                            <th>Student</th>
                            <th>Invoice Date</th>
                            <th>Due Date</th>
                            <th class="text-end">Total</th>
                            <th class="text-end">Paid</th>
                            <th class="text-end">Balance</th>
                            <th>Status</th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($invoices as $invoice)

                            <tr>

                                {{-- Invoice --}}
                                <td>

                                    <a href="{{ route(
                                        'admin.invoices.show',
                                        $invoice
                                    ) }}">

                                        {{ $invoice->invoice_number }}

                                    </a>

                                </td>


                                {{-- Student --}}
                                <td>

                                    @if($invoice->student)

                                        <strong>

                                            {{ $invoice->student->first_name }}

                                            {{ $invoice->student->middle_name }}

                                            {{ $invoice->student->last_name }}

                                        </strong>

                                        <small class="d-block text-muted">

                                            {{ $invoice->student->student_number }}

                                        </small>

                                    @else

                                        —

                                    @endif

                                </td>


                                {{-- Invoice Date --}}
                                <td>

                                    {{ $invoice->invoice_date?->format('d M Y') ?? '—' }}

                                </td>


                                {{-- Due Date --}}
                                <td>

                                    @if($invoice->due_date)

                                        @if(
                                            $invoice->balance > 0 &&
                                            $invoice->due_date->isPast()
                                        )

                                            <span class="text-danger">

                                                {{ $invoice->due_date->format('d M Y') }}

                                                <small class="d-block">
                                                    Overdue
                                                </small>

                                            </span>

                                        @else

                                            {{ $invoice->due_date->format('d M Y') }}

                                        @endif

                                    @else

                                        —

                                    @endif

                                </td>


                                {{-- Total --}}
                                <td class="text-end">

                                    ₹{{ number_format(
                                        (float) $invoice->total,
                                        2
                                    ) }}

                                </td>


                                {{-- Paid --}}
                                <td class="text-end">

                                    <span class="text-success">

                                        ₹{{ number_format(
                                            (float) $invoice->paid,
                                            2
                                        ) }}

                                    </span>

                                </td>


                                {{-- Balance --}}
                                <td class="text-end">

                                    <strong class="text-danger">

                                        ₹{{ number_format(
                                            (float) $invoice->balance,
                                            2
                                        ) }}

                                    </strong>

                                </td>


                                {{-- Status --}}
                                <td>

                                    @if($invoice->status === 'partial')

                                        <span class="badge bg-warning-subtle text-warning">
                                            Partial
                                        </span>

                                    @elseif($invoice->status === 'unpaid')

                                        <span class="badge bg-danger-subtle text-danger">
                                            Unpaid
                                        </span>

                                    @elseif($invoice->status === 'pending')

                                        <span class="badge bg-secondary-subtle text-secondary">
                                            Pending
                                        </span>

                                    @else

                                        <span class="badge bg-light text-dark">
                                            {{ ucfirst($invoice->status ?? 'Unknown') }}
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="8"
                                    class="text-center text-muted py-5">

                                    <i class="bx bx-check-circle font-size-32 d-block mb-2"></i>

                                    <h6>
                                        No outstanding invoices found.
                                    </h6>

                                    <small>
                                        Try changing your filters or date range.
                                    </small>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- Pagination --}}
        @if($invoices->hasPages())

            <div class="card-footer">

                {{ $invoices->links() }}

            </div>

        @endif

    </div>

</div>

@endsection