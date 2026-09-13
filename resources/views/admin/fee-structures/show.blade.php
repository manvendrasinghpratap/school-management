@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Title --}}
    <div class="row">

        <div class="col-12">

            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0">
                    FEE STRUCTURE DETAILS
                </h4>

                <div class="page-title-right">

                    <ol class="breadcrumb m-0">

                        <li class="breadcrumb-item">
                            Finance
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.fee-structures.index') }}">
                                Fee Structures
                            </a>
                        </li>

                        <li class="breadcrumb-item active">
                            Details
                        </li>

                    </ol>

                </div>

            </div>

        </div>

    </div>

    {{-- Alerts --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif

    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif

    <div class="row">

        {{-- Main Details --}}
        <div class="col-lg-8">

            <div class="card">

                <div class="card-header d-flex align-items-center justify-content-between">

                    <div>

                        <h4 class="card-title mb-1">
                            {{ optional($feeStructure->feeCategory)->name ?: 'Fee Structure' }}
                        </h4>

                        <p class="card-title-desc mb-0">
                            Fee structure configuration details
                        </p>

                    </div>

                    @if($feeStructure->is_active)

                        <span class="badge bg-success">
                            Active
                        </span>

                    @else

                        <span class="badge bg-secondary">
                            Inactive
                        </span>

                    @endif

                </div>

                <div class="card-body">

                    <div class="row">

                        {{-- Category --}}
                        <div class="col-md-6 mb-4">

                            <label class="text-muted d-block mb-1">
                                Fee Category
                            </label>

                            <h5 class="mb-0">

                                {{ optional($feeStructure->feeCategory)->name ?: '—' }}

                                @if(optional($feeStructure->feeCategory)->code)

                                    <small class="text-muted">
                                        ({{ $feeStructure->feeCategory->code }})
                                    </small>

                                @endif

                            </h5>

                        </div>

                        {{-- Academic Year --}}
                        <div class="col-md-6 mb-4">

                            <label class="text-muted d-block mb-1">
                                Academic Year
                            </label>

                            <h5 class="mb-0">
                                {{ optional($feeStructure->academicYear)->name ?: '—' }}
                            </h5>

                        </div>

                        {{-- Class --}}
                        <div class="col-md-6 mb-4">

                            <label class="text-muted d-block mb-1">
                                Class
                            </label>

                            <h5 class="mb-0">
                                {{ optional($feeStructure->classModel)->name ?: 'All Classes' }}
                            </h5>

                        </div>

                        {{-- Term --}}
                        <div class="col-md-6 mb-4">

                            <label class="text-muted d-block mb-1">
                                Term
                            </label>

                            <h5 class="mb-0">
                                {{ optional($feeStructure->term)->name ?: 'All Terms' }}
                            </h5>

                        </div>

                        {{-- Amount --}}
                        <div class="col-md-6 mb-4">

                            <label class="text-muted d-block mb-1">
                                Amount
                            </label>

                            <h3 class="mb-0">
                                ₹{{ number_format((float) $feeStructure->amount, 2) }}
                            </h3>

                        </div>

                        {{-- Due Date --}}
                        <div class="col-md-6 mb-4">

                            <label class="text-muted d-block mb-1">
                                Due Date
                            </label>

                            <h5 class="mb-0">

                                @if($feeStructure->due_date)

                                    {{ \Carbon\Carbon::parse($feeStructure->due_date)->format('d M Y') }}

                                @else

                                    No due date specified

                                @endif

                            </h5>

                        </div>

                        {{-- Created --}}
                        <div class="col-md-6">

                            <label class="text-muted d-block mb-1">
                                Created
                            </label>

                            <span>
                                {{ optional($feeStructure->created_at)->format('d M Y, h:i A') }}
                            </span>

                        </div>

                        {{-- Updated --}}
                        <div class="col-md-6">

                            <label class="text-muted d-block mb-1">
                                Last Updated
                            </label>

                            <span>
                                {{ optional($feeStructure->updated_at)->format('d M Y, h:i A') }}
                            </span>

                        </div>

                    </div>

                </div>

                <div class="card-footer">

                    <div class="d-flex gap-2">

                        @can('fee-structures.manage')

                            <a
                                href="{{ route('admin.fee-structures.edit', $feeStructure) }}"
                                class="btn btn-primary"
                            >
                                <i class="bx bx-edit-alt me-1"></i>
                                Edit
                            </a>

                        @endcan

                        <a
                            href="{{ route('admin.fee-structures.index') }}"
                            class="btn btn-light"
                        >
                            <i class="bx bx-arrow-back me-1"></i>
                            Back
                        </a>

                    </div>

                </div>

            </div>

        </div>

        {{-- Installments --}}
        <div class="col-lg-4">

            <div class="card">

                <div class="card-header">

                    <h4 class="card-title mb-1">
                        Installments
                    </h4>

                    <p class="card-title-desc mb-0">
                        Installment schedule for this fee structure
                    </p>

                </div>

                <div class="card-body">

                    @if($feeStructure->installments->count())

                        <div class="table-responsive">

                            <table class="table table-sm table-bordered align-middle mb-0">

                                <thead class="table-light">

                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Amount</th>
                                        <th>Due</th>
                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach($feeStructure->installments as $installment)

                                        <tr>

                                            <td>
                                                {{ $loop->iteration }}
                                            </td>

                                            <td>
                                                {{ $installment->name }}
                                            </td>

                                            <td>
                                                ₹{{ number_format((float) $installment->amount, 2) }}
                                            </td>

                                            <td>

                                                @if($installment->due_date)

                                                    {{ \Carbon\Carbon::parse($installment->due_date)->format('d M Y') }}

                                                @else

                                                    —

                                                @endif

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="text-center py-4">

                            <div class="avatar-md mx-auto mb-3">

                                <span class="avatar-title rounded-circle bg-soft-primary text-primary font-size-24">
                                    <i class="bx bx-calendar"></i>
                                </span>

                            </div>

                            <h6>
                                No installments configured
                            </h6>

                            <p class="text-muted mb-0">
                                Installments can be configured after the basic fee structure is created.
                            </p>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@endsection