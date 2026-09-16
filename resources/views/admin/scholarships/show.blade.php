@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- ================================================================
         PAGE TITLE
    ================================================================= --}}
    <div class="row">
        <div class="col-12">

            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-0">
                    Scholarship Details
                </h4>

                <div class="d-flex gap-2">

                    @can('scholarships.manage')
                        <a href="{{ route('admin.scholarships.edit', $scholarship) }}"
                           class="btn btn-primary">
                            <i class="bx bx-edit me-1"></i>
                            Edit Scholarship
                        </a>
                    @endcan

                    <a href="{{ route('admin.scholarships.index') }}"
                       class="btn btn-light">
                        <i class="bx bx-arrow-back me-1"></i>
                        Back
                    </a>

                </div>

            </div>

        </div>
    </div>


    {{-- ================================================================
         SESSION MESSAGES
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
             SCHOLARSHIP INFORMATION
        ============================================================= --}}
        <div class="col-lg-5">

            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-award me-1"></i>
                        Scholarship Information
                    </h5>
                </div>

                <div class="card-body">

                    <dl class="row mb-0">

                        {{-- Name --}}
                        <dt class="col-sm-5">
                            Name
                        </dt>

                        <dd class="col-sm-7">
                            {{ $scholarship->name }}
                        </dd>


                        {{-- Type --}}
                        <dt class="col-sm-5">
                            Type
                        </dt>

                        <dd class="col-sm-7">
                            {{ ucfirst($scholarship->type) }}
                        </dd>


                        {{-- Value --}}
                        <dt class="col-sm-5">
                            Value
                        </dt>

                        <dd class="col-sm-7 fw-semibold">

                            @if($scholarship->type === 'percentage')
                                {{ number_format((float) $scholarship->value, 2) }}%
                            @else
                                ₹{{ number_format((float) $scholarship->value, 2) }}
                            @endif

                        </dd>


                        {{-- Status --}}
                        <dt class="col-sm-5">
                            Status
                        </dt>

                        <dd class="col-sm-7">

                            @if($scholarship->is_active)

                                <span class="badge bg-success">
                                    Active
                                </span>

                            @else

                                <span class="badge bg-secondary">
                                    Inactive
                                </span>

                            @endif

                        </dd>


                        {{-- Assigned Fees --}}
                        <dt class="col-sm-5">
                            Assigned Fees
                        </dt>

                        <dd class="col-sm-7">
                            {{ $scholarship->studentFees->count() }}
                        </dd>


                        {{-- Description --}}
                        <dt class="col-sm-5">
                            Description
                        </dt>

                        <dd class="col-sm-7">
                            {{ $scholarship->description ?: '—' }}
                        </dd>

                    </dl>


                    {{-- Actions --}}
                    <div class="mt-4">

                        @can('scholarships.manage')

                            <a href="{{ route('admin.scholarships.edit', $scholarship) }}"
                               class="btn btn-primary">
                                <i class="bx bx-edit me-1"></i>
                                Edit
                            </a>

                        @endcan

                        <a href="{{ route('admin.scholarships.index') }}"
                           class="btn btn-light">
                            <i class="bx bx-arrow-back me-1"></i>
                            Back
                        </a>

                    </div>

                </div>

            </div>

        </div>


        {{-- ============================================================
             STUDENT FEE ASSIGNMENTS
        ============================================================= --}}
        <div class="col-lg-7">

            <div class="card">

                <div class="card-header d-flex align-items-center justify-content-between">

                    <h5 class="card-title mb-0">
                        <i class="bx bx-receipt me-1"></i>
                        Student Fee Assignments
                    </h5>

                    <span class="badge bg-light text-dark">
                        {{ $scholarship->studentFees->count() }}
                    </span>

                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-sm table-hover align-middle">

                            <thead class="table-light">

                                <tr>
                                    <th>#</th>
                                    <th>Student</th>
                                    <th>Fee Structure</th>
                                    <th>Amount</th>
                                    <th>Discount</th>
                                    <th>Net Payable</th>
                                    <th>Status</th>
                                </tr>

                            </thead>

                            <tbody>

                                @forelse($scholarship->studentFees as $fee)

                                    <tr>

                                        {{-- Number --}}
                                        <td>
                                            {{ $loop->iteration }}
                                        </td>


                                        {{-- Student --}}
                                        <td>

                                            <div class="fw-semibold">
                                                {{ $fee->student?->first_name }}
                                                {{ $fee->student?->middle_name }}
                                                {{ $fee->student?->last_name }}
                                            </div>

                                            @if($fee->student?->student_number)

                                                <div class="small text-muted">
                                                    {{ $fee->student->student_number }}
                                                </div>

                                            @endif

                                        </td>


                                        {{-- Fee Structure --}}
                                        <td>

                                            @if($fee->feeStructure)

                                                <div class="fw-semibold">
                                                    Fee Structure #{{ $fee->feeStructure->id }}
                                                </div>

                                                @if($fee->feeStructure->feeCategory)

                                                    <div class="small text-muted">
                                                        {{ $fee->feeStructure->feeCategory->name }}
                                                    </div>

                                                @endif

                                            @else

                                                —

                                            @endif

                                        </td>


                                        {{-- Amount --}}
                                        <td>
                                            ₹{{ number_format((float) $fee->amount, 2) }}
                                        </td>


                                        {{-- Discount --}}
                                        <td class="text-danger">
                                            − ₹{{ number_format((float) $fee->discount, 2) }}
                                        </td>


                                        {{-- Net Payable --}}
                                        <td class="fw-semibold text-primary">

                                            ₹{{ number_format(
                                                max(
                                                    0,
                                                    (float) $fee->amount - (float) $fee->discount
                                                ),
                                                2
                                            ) }}

                                        </td>


                                        {{-- Status --}}
                                        <td>

                                            @if($fee->status === 'paid')

                                                <span class="badge bg-success">
                                                    Paid
                                                </span>

                                            @elseif($fee->status === 'partial')

                                                <span class="badge bg-info">
                                                    Partial
                                                </span>

                                            @elseif($fee->status === 'cancelled')

                                                <span class="badge bg-secondary">
                                                    Cancelled
                                                </span>

                                            @else

                                                <span class="badge bg-warning">
                                                    Pending
                                                </span>

                                            @endif

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="7"
                                            class="text-center text-muted py-4">

                                            <i class="bx bx-info-circle fs-4 d-block mb-2"></i>

                                            No student fee assignments have been made
                                            using this scholarship yet.

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection