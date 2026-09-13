@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- ============================================================
         PAGE HEADER
    ============================================================ --}}
    <div class="row">
        <div class="col-12">

            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0 font-size-18">
                    Invoice Management
                </h4>

                <div class="page-title-right">

                    @can('invoices.manage')
                        <a href="{{ route('admin.invoices.create') }}"
                           class="btn btn-primary">
                            <i class="bx bx-plus me-1"></i>
                            Create Invoice
                        </a>
                    @endcan

                </div>

            </div>

        </div>
    </div>


    {{-- ============================================================
         SUCCESS MESSAGE
    ============================================================ --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show"
             role="alert">

            <i class="bx bx-check-circle me-1"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- ============================================================
         ERROR MESSAGES
    ============================================================ --}}
    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show"
             role="alert">

            <strong>
                Please correct the following:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- ============================================================
         SUMMARY CARDS
    ============================================================ --}}
    <div class="row">

        <div class="col-xl-3 col-md-6">

            <div class="card">
                <div class="card-body">

                    <div class="d-flex">

                        <div class="flex-grow-1">

                            <p class="text-muted mb-2">
                                Total Invoices
                            </p>

                            <h4 class="mb-0">
                                {{ $invoices->total() }}
                            </h4>

                        </div>

                        <div class="avatar-sm">

                            <span class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-20">
                                <i class="bx bx-receipt"></i>
                            </span>

                        </div>

                    </div>

                </div>
            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card">
                <div class="card-body">

                    <div class="d-flex">

                        <div class="flex-grow-1">

                            <p class="text-muted mb-2">
                                Unpaid
                            </p>

                            <h4 class="mb-0">

                                ₹{{ number_format(
                                    $invoices->getCollection()
                                        ->where('status', 'unpaid')
                                        ->sum('balance'),
                                    2
                                ) }}

                            </h4>

                        </div>

                        <div class="avatar-sm">

                            <span class="avatar-title rounded-circle bg-warning bg-soft text-warning font-size-20">
                                <i class="bx bx-time"></i>
                            </span>

                        </div>

                    </div>

                </div>
            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card">
                <div class="card-body">

                    <div class="d-flex">

                        <div class="flex-grow-1">

                            <p class="text-muted mb-2">
                                Partial
                            </p>

                            <h4 class="mb-0">

                                ₹{{ number_format(
                                    $invoices->getCollection()
                                        ->where('status', 'partial')
                                        ->sum('balance'),
                                    2
                                ) }}

                            </h4>

                        </div>

                        <div class="avatar-sm">

                            <span class="avatar-title rounded-circle bg-info bg-soft text-info font-size-20">
                                <i class="bx bx-adjust"></i>
                            </span>

                        </div>

                    </div>

                </div>
            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card">
                <div class="card-body">

                    <div class="d-flex">

                        <div class="flex-grow-1">

                            <p class="text-muted mb-2">
                                Paid
                            </p>

                            <h4 class="mb-0">

                                ₹{{ number_format(
                                    $invoices->getCollection()
                                        ->where('status', 'paid')
                                        ->sum('total'),
                                    2
                                ) }}

                            </h4>

                        </div>

                        <div class="avatar-sm">

                            <span class="avatar-title rounded-circle bg-success bg-soft text-success font-size-20">
                                <i class="bx bx-check-circle"></i>
                            </span>

                        </div>

                    </div>

                </div>
            </div>

        </div>

    </div>


    {{-- ============================================================
         FILTERS
    ============================================================ --}}
    <div class="card">

        <div class="card-header">

            <h5 class="card-title mb-0">
                <i class="bx bx-filter-alt me-1"></i>
                Invoice Filters
            </h5>

        </div>


        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.invoices.index') }}">

                <div class="row">

                    {{-- Invoice Number --}}
                    <div class="col-md-3">

                        <div class="mb-3">

                            <label for="invoice_number"
                                   class="form-label">

                                Invoice Number

                            </label>

                            <input type="text"
                                   name="invoice_number"
                                   id="invoice_number"
                                   class="form-control"
                                   value="{{ request('invoice_number') }}"
                                   placeholder="Search invoice number">

                        </div>

                    </div>


                    {{-- Student --}}
                    <div class="col-md-3">

                        <div class="mb-3">

                            <label for="student_id"
                                   class="form-label">

                                Student

                            </label>

                            <select name="student_id"
                                    id="student_id"
                                    class="form-select">

                                <option value="">
                                    All Students
                                </option>

                                @foreach($students as $student)

                                    <option
                                        value="{{ $student->id }}"
                                        {{ request('student_id') == $student->id ? 'selected' : '' }}
                                    >

                                        {{ trim(
                                            $student->first_name . ' ' .
                                            ($student->middle_name ?? '') . ' ' .
                                            $student->last_name
                                        ) }}

                                        —
                                        {{ $student->student_number }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                    </div>


                    {{-- Status --}}
                    <div class="col-md-2">

                        <div class="mb-3">

                            <label for="status"
                                   class="form-label">

                                Status

                            </label>

                            <select name="status"
                                    id="status"
                                    class="form-select">

                                <option value="">
                                    All Status
                                </option>

                                <option value="unpaid"
                                    {{ request('status') === 'unpaid' ? 'selected' : '' }}>
                                    Unpaid
                                </option>

                                <option value="partial"
                                    {{ request('status') === 'partial' ? 'selected' : '' }}>
                                    Partial
                                </option>

                                <option value="paid"
                                    {{ request('status') === 'paid' ? 'selected' : '' }}>
                                    Paid
                                </option>

                                <option value="cancelled"
                                    {{ request('status') === 'cancelled' ? 'selected' : '' }}>
                                    Cancelled
                                </option>

                            </select>

                        </div>

                    </div>


                    {{-- From Date --}}
                    <div class="col-md-2">

                        <div class="mb-3">

                            <label for="invoice_date_from"
                                   class="form-label">

                                From Date

                            </label>

                            <input type="date"
                                   name="invoice_date_from"
                                   id="invoice_date_from"
                                   class="form-control"
                                   value="{{ request('invoice_date_from') }}">

                        </div>

                    </div>


                    {{-- To Date --}}
                    <div class="col-md-2">

                        <div class="mb-3">

                            <label for="invoice_date_to"
                                   class="form-label">

                                To Date

                            </label>

                            <input type="date"
                                   name="invoice_date_to"
                                   id="invoice_date_to"
                                   class="form-control"
                                   value="{{ request('invoice_date_to') }}">

                        </div>

                    </div>

                </div>


                <div class="d-flex gap-2">

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="bx bx-search me-1"></i>
                        Filter

                    </button>


                    <a href="{{ route('admin.invoices.index') }}"
                       class="btn btn-light">

                        <i class="bx bx-reset me-1"></i>
                        Reset

                    </a>

                </div>

            </form>

        </div>

    </div>


    {{-- ============================================================
         INVOICE TABLE
    ============================================================ --}}
    <div class="card">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="card-title mb-0">
                    Invoices
                </h5>

                <span class="text-muted">
                    {{ $invoices->total() }} record(s)
                </span>

            </div>

        </div>


        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-light">

                        <tr>

                            <th>
                                #
                            </th>

                            <th>
                                Invoice Number
                            </th>

                            <th>
                                Student
                            </th>

                            <th>
                                Invoice Date
                            </th>

                            <th>
                                Due Date
                            </th>

                            <th>
                                Items
                            </th>

                            <th class="text-end">
                                Total
                            </th>

                            <th class="text-end">
                                Paid
                            </th>

                            <th class="text-end">
                                Balance
                            </th>

                            <th>
                                Status
                            </th>

                            <th style="width: 150px;">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($invoices as $invoice)

                            <tr>

                                {{-- Number --}}
                                <td>
                                    {{ $loop->iteration + (($invoices->currentPage() - 1) * $invoices->perPage()) }}
                                </td>


                                {{-- Invoice Number --}}
                                <td>

                                    <a href="{{ route('admin.invoices.show', $invoice) }}"
                                       class="fw-semibold text-primary">

                                        {{ $invoice->invoice_number }}

                                    </a>

                                </td>


                                {{-- Student --}}
                                <td>

                                    @if($invoice->student)

                                        <div>

                                            <strong>
                                                {{ trim(
                                                    $invoice->student->first_name . ' ' .
                                                    ($invoice->student->middle_name ?? '') . ' ' .
                                                    $invoice->student->last_name
                                                ) }}
                                            </strong>

                                        </div>

                                        <small class="text-muted">
                                            {{ $invoice->student->student_number }}
                                        </small>

                                    @else

                                        <span class="text-muted">
                                            Student unavailable
                                        </span>

                                    @endif

                                </td>


                                {{-- Invoice Date --}}
                                <td>

                                    {{ optional($invoice->invoice_date)->format('d M Y') }}

                                </td>


                                {{-- Due Date --}}
                                <td>

                                    @if($invoice->due_date)

                                        {{ $invoice->due_date->format('d M Y') }}

                                        @if(
                                            $invoice->status === 'unpaid' &&
                                            $invoice->due_date->isPast()
                                        )

                                            <br>

                                            <span class="badge bg-danger">
                                                Overdue
                                            </span>

                                        @endif

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- Items --}}
                                <td>

                                    <span class="badge bg-light text-dark">

                                        {{ $invoice->items->count() }}

                                        {{ $invoice->items->count() === 1 ? 'Item' : 'Items' }}

                                    </span>

                                </td>


                                {{-- Total --}}
                                <td class="text-end">

                                    ₹{{ number_format(
                                        (float) $invoice->total,
                                        2
                                    ) }}

                                </td>


                                {{-- Paid --}}
                                <td class="text-end text-success">

                                    ₹{{ number_format(
                                        (float) $invoice->paid,
                                        2
                                    ) }}

                                </td>


                                {{-- Balance --}}
                                <td class="text-end">

                                    @if((float) $invoice->balance > 0)

                                        <span class="text-danger fw-semibold">

                                            ₹{{ number_format(
                                                (float) $invoice->balance,
                                                2
                                            ) }}

                                        </span>

                                    @else

                                        <span class="text-success fw-semibold">

                                            ₹0.00

                                        </span>

                                    @endif

                                </td>


                                {{-- Status --}}
                                <td>

                                    @switch($invoice->status)

                                        @case('unpaid')

                                            <span class="badge bg-warning">
                                                Unpaid
                                            </span>

                                            @break

                                        @case('partial')

                                            <span class="badge bg-info">
                                                Partial
                                            </span>

                                            @break

                                        @case('paid')

                                            <span class="badge bg-success">
                                                Paid
                                            </span>

                                            @break

                                        @case('cancelled')

                                            <span class="badge bg-danger">
                                                Cancelled
                                            </span>

                                            @break

                                        @default

                                            <span class="badge bg-secondary">
                                                {{ ucfirst($invoice->status) }}
                                            </span>

                                    @endswitch

                                </td>


                                {{-- Actions --}}
                                <td>

                                    <div class="d-flex gap-1">

                                        {{-- View --}}
                                        @can('invoices.view')

                                            <a href="{{ route('admin.invoices.show', $invoice) }}"
                                               class="btn btn-sm btn-outline-primary"
                                               title="View">

                                                <i class="bx bx-show"></i>

                                            </a>

                                        @endcan


                                        {{-- Edit --}}
                                        @can('invoices.manage')

                                            @if($invoice->status === 'unpaid')

                                                <a href="{{ route('admin.invoices.edit', $invoice) }}"
                                                   class="btn btn-sm btn-outline-warning"
                                                   title="Edit">

                                                    <i class="bx bx-edit"></i>

                                                </a>

                                            @endif

                                        @endcan


                                        {{-- Cancel --}}
                                        @can('invoices.manage')

                                            @if($invoice->status === 'unpaid')

                                                <form method="POST"
                                                      action="{{ route('admin.invoices.cancel', $invoice) }}"
                                                      class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to cancel this invoice?');">

                                                    @csrf
                                                    @method('PATCH')

                                                    <button type="submit"
                                                            class="btn btn-sm btn-outline-danger"
                                                            title="Cancel">

                                                        <i class="bx bx-x-circle"></i>

                                                    </button>

                                                </form>

                                            @endif

                                        @endcan


                                        {{-- Delete --}}
                                        @can('invoices.manage')

                                            @if($invoice->status === 'unpaid')

                                                <form method="POST"
                                                      action="{{ route('admin.invoices.destroy', $invoice) }}"
                                                      class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this invoice? This action will soft-delete the invoice and its items. Continue?');">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                            class="btn btn-sm btn-outline-danger"
                                                            title="Delete">

                                                        <i class="bx bx-trash"></i>

                                                    </button>

                                                </form>

                                            @endif

                                        @endcan

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="11"
                                    class="text-center py-5">

                                    <div class="text-muted">

                                        <i class="bx bx-receipt"
                                           style="font-size: 40px;">
                                        </i>

                                        <h5 class="mt-3">
                                            No invoices found
                                        </h5>

                                        <p class="mb-3">
                                            There are no invoices matching the selected filters.
                                        </p>

                                        @can('invoices.manage')

                                            <a href="{{ route('admin.invoices.create') }}"
                                               class="btn btn-primary">

                                                <i class="bx bx-plus me-1"></i>
                                                Create First Invoice

                                            </a>

                                        @endcan

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- ====================================================
                 PAGINATION
            ===================================================== --}}

            @if($invoices->hasPages())

                <div class="mt-3">

                    {{ $invoices->links() }}

                </div>

            @endif

        </div>

    </div>

</div>

@endsection