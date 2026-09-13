@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- ================================================================
        PAGE TITLE
    ================================================================= --}}
    <div class="row">
        <div class="col-12">

            <div class="page-title-box d-flex align-items-center justify-content-between">

                <h4 class="mb-0">
                    Student Fee Details
                </h4>

                <div class="d-flex gap-2">

                    @can('fees.manage')

                        @if(!in_array($studentFee->status, ['paid', 'partial'], true))

                            <a href="{{ route('admin.student-fees.edit', $studentFee) }}"
                               class="btn btn-warning">

                                <i class="bx bx-edit me-1"></i>

                                Edit

                            </a>

                        @endif

                    @endcan


                    <a href="{{ route('admin.student-fees.index') }}"
                       class="btn btn-light">

                        <i class="bx bx-arrow-back me-1"></i>

                        Back

                    </a>

                </div>

            </div>

        </div>
    </div>


    {{-- ================================================================
        SUCCESS
    ================================================================= --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bx bx-check-circle me-1"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- ================================================================
        ERROR
    ================================================================= --}}
    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="bx bx-error-circle me-1"></i>

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    <div class="row">

        {{-- ============================================================
             STUDENT INFORMATION
        ============================================================= --}}
        <div class="col-lg-6">

            <div class="card">

                <div class="card-header">

                    <h4 class="card-title mb-0">

                        <i class="bx bx-user me-1"></i>

                        Student Information

                    </h4>

                </div>


                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-bordered mb-0">

                            <tbody>

                                <tr>

                                    <th width="40%">
                                        Student Name
                                    </th>

                                    <td>

                                        <strong>

                                            {{ $studentFee->student?->full_name
                                                ?? trim(
                                                    ($studentFee->student?->first_name ?? '') . ' ' .
                                                    ($studentFee->student?->middle_name ?? '') . ' ' .
                                                    ($studentFee->student?->last_name ?? '')
                                                )
                                            }}

                                        </strong>

                                    </td>

                                </tr>


                                <tr>

                                    <th>
                                        Student Number
                                    </th>

                                    <td>

                                        {{ $studentFee->student?->student_number ?? '—' }}

                                    </td>

                                </tr>


                                <tr>

                                    <th>
                                        Admission Number
                                    </th>

                                    <td>

                                        {{ $studentFee->student?->admission_number ?? '—' }}

                                    </td>

                                </tr>


                                <tr>

                                    <th>
                                        Student Status
                                    </th>

                                    <td>

                                        @if($studentFee->student?->status === 'active')

                                            <span class="badge bg-success">
                                                Active
                                            </span>

                                        @else

                                            <span class="badge bg-secondary">
                                                {{ ucfirst($studentFee->student?->status ?? 'Unknown') }}
                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>


        {{-- ============================================================
             ACADEMIC INFORMATION
        ============================================================= --}}
        <div class="col-lg-6">

            <div class="card">

                <div class="card-header">

                    <h4 class="card-title mb-0">

                        <i class="bx bx-book-open me-1"></i>

                        Academic Information

                    </h4>

                </div>


                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-bordered mb-0">

                            <tbody>

                                <tr>

                                    <th width="40%">
                                        Academic Year
                                    </th>

                                    <td>

                                        {{ $studentFee->feeStructure?->academicYear?->name ?? '—' }}

                                    </td>

                                </tr>


                                <tr>

                                    <th>
                                        Class
                                    </th>

                                    <td>

                                        {{ $studentFee->feeStructure?->classModel?->name ?? '—' }}

                                        @if($studentFee->feeStructure?->classModel?->code)

                                            <small class="text-muted">

                                                ({{ $studentFee->feeStructure->classModel->code }})

                                            </small>

                                        @endif

                                    </td>

                                </tr>


                                <tr>

                                    <th>
                                        Term
                                    </th>

                                    <td>

                                        @if($studentFee->feeStructure?->term)

                                            {{ $studentFee->feeStructure->term->name }}

                                        @else

                                            <span class="text-muted">
                                                All Terms
                                            </span>

                                        @endif

                                    </td>

                                </tr>


                                <tr>

                                    <th>
                                        Fee Category
                                    </th>

                                    <td>

                                        <strong>

                                            {{ $studentFee->feeStructure?->feeCategory?->name ?? '—' }}

                                        </strong>

                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ================================================================
        FEE DETAILS
    ================================================================= --}}
    <div class="row">

        <div class="col-12">

            <div class="card">

                <div class="card-header">

                    <h4 class="card-title mb-0">

                        <i class="bx bx-money me-1"></i>

                        Fee Assignment

                    </h4>

                </div>


                <div class="card-body">

                    <div class="row">

                        {{-- Fee Structure --}}

                        <div class="col-md-4 mb-3">

                            <label class="text-muted d-block">
                                Fee Structure
                            </label>

                            <strong>

                                {{ $studentFee->feeStructure?->feeCategory?->name ?? '—' }}

                            </strong>

                        </div>


                        {{-- Assigned Amount --}}

                        <div class="col-md-4 mb-3">

                            <label class="text-muted d-block">
                                Assigned Amount
                            </label>

                            <strong class="fs-5">

                                ₹{{ number_format((float) $studentFee->amount, 2) }}

                            </strong>

                        </div>


                        {{-- Discount --}}

                        <div class="col-md-4 mb-3">

                            <label class="text-muted d-block">
                                Discount
                            </label>

                            <strong class="text-danger fs-5">

                                ₹{{ number_format((float) $studentFee->discount, 2) }}

                            </strong>

                        </div>


                        {{-- Net Payable --}}

                        <div class="col-md-4 mb-3">

                            <label class="text-muted d-block">
                                Net Payable
                            </label>

                            <strong class="text-primary fs-4">

                                ₹{{ number_format(
                                    max(
                                        0,
                                        (float) $studentFee->amount -
                                        (float) $studentFee->discount
                                    ),
                                    2
                                ) }}

                            </strong>

                        </div>


                        {{-- Scholarship --}}

                        <div class="col-md-4 mb-3">

                            <label class="text-muted d-block">
                                Scholarship
                            </label>

                            @if($studentFee->scholarship)

                                <strong>

                                    {{ $studentFee->scholarship->name }}

                                </strong>

                            @else

                                <span class="text-muted">
                                    —
                                </span>

                            @endif

                        </div>


                        {{-- Status --}}

                        <div class="col-md-4 mb-3">

                            <label class="text-muted d-block">
                                Status
                            </label>


                            @switch($studentFee->status)

                                @case('pending')

                                    <span class="badge bg-info">
                                        Pending
                                    </span>

                                    @break

                                @case('partial')

                                    <span class="badge bg-warning">
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
                                        {{ ucfirst($studentFee->status) }}
                                    </span>

                            @endswitch

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ================================================================
        RECORD INFORMATION
    ================================================================= --}}
    <div class="row">

        <div class="col-12">

            <div class="card">

                <div class="card-header">

                    <h4 class="card-title mb-0">

                        <i class="bx bx-info-circle me-1"></i>

                        Record Information

                    </h4>

                </div>


                <div class="card-body">

                    <div class="row">

                        <div class="col-md-4">

                            <small class="text-muted d-block">
                                Assignment ID
                            </small>

                            <strong>
                                #{{ $studentFee->id }}
                            </strong>

                        </div>


                        <div class="col-md-4">

                            <small class="text-muted d-block">
                                Created
                            </small>

                            <strong>

                                {{ $studentFee->created_at
                                    ? $studentFee->created_at->format('d M Y, h:i A')
                                    : '—'
                                }}

                            </strong>

                        </div>


                        <div class="col-md-4">

                            <small class="text-muted d-block">
                                Last Updated
                            </small>

                            <strong>

                                {{ $studentFee->updated_at
                                    ? $studentFee->updated_at->format('d M Y, h:i A')
                                    : '—'
                                }}

                            </strong>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ================================================================
        ACTIONS
    ================================================================= --}}
    @can('fees.manage')

        @if(!in_array($studentFee->status, ['paid', 'partial'], true))

            <div class="row">

                <div class="col-12">

                    <div class="card">

                        <div class="card-body">

                            <div class="d-flex justify-content-between align-items-center">

                                <div>

                                    <strong>
                                        Manage Fee Assignment
                                    </strong>

                                    <div class="text-muted small">
                                        Pending assignments can be edited,
                                        cancelled or deleted.
                                    </div>

                                </div>


                                <div class="d-flex gap-2">

                                    @if($studentFee->status !== 'cancelled')

                                        <form method="POST"
                                              action="{{ route('admin.student-fees.cancel', $studentFee) }}"
                                              onsubmit="return confirm('Are you sure you want to cancel this fee assignment?');">

                                            @csrf

                                            @method('PATCH')

                                            <button type="submit"
                                                    class="btn btn-warning">

                                                <i class="bx bx-block me-1"></i>

                                                Cancel Fee

                                            </button>

                                        </form>

                                    @endif


                                    <form method="POST"
                                          action="{{ route('admin.student-fees.destroy', $studentFee) }}"
                                          onsubmit="return confirm('Are you sure you want to delete this fee assignment?');">

                                        @csrf

                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-danger">

                                            <i class="bx bx-trash me-1"></i>

                                            Delete

                                        </button>

                                    </form>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        @endif

    @endcan

</div>

@endsection