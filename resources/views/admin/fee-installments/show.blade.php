@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Title --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">

                <h4 class="mb-0">Fee Installment Details</h4>

                <div class="d-flex gap-2">

                    <a href="{{ route('admin.fee-installments.index') }}"
                       class="btn btn-secondary">
                        <i class="bx bx-arrow-back me-1"></i>
                        Back
                    </a>

                    @can('fee-installments.manage')
                        <a href="{{ route('admin.fee-installments.edit', $feeInstallment) }}"
                           class="btn btn-warning">
                            <i class="bx bx-edit me-1"></i>
                            Edit
                        </a>
                    @endcan

                </div>

            </div>
        </div>
    </div>


    {{-- Success Message --}}
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


    {{-- Error Message --}}
    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show"
             role="alert">

            <i class="bx bx-error-circle me-1"></i>

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    <div class="row">

        {{-- Installment Information --}}
        <div class="col-lg-8">

            <div class="card">

                <div class="card-body">

                    <h4 class="card-title mb-4">
                        Installment Information
                    </h4>

                    <div class="table-responsive">

                        <table class="table table-bordered mb-0">

                            <tbody>

                                <tr>
                                    <th width="35%">
                                        Installment Number
                                    </th>

                                    <td>
                                        <span class="badge bg-info">
                                            #{{ $feeInstallment->installment_number }}
                                        </span>
                                    </td>
                                </tr>


                                <tr>
                                    <th>
                                        Installment Name
                                    </th>

                                    <td>
                                        {{ $feeInstallment->name }}
                                    </td>
                                </tr>


                                <tr>
                                    <th>
                                        Amount
                                    </th>

                                    <td>
                                        <strong class="fs-5">
                                            {{ number_format((float)$feeInstallment->amount, 2) }}
                                        </strong>
                                    </td>
                                </tr>


                                <tr>
                                    <th>
                                        Due Date
                                    </th>

                                    <td>
                                        {{ $feeInstallment->due_date?->format('d M Y') ?? '—' }}
                                    </td>
                                </tr>


                                <tr>
                                    <th>
                                        Status
                                    </th>

                                    <td>

                                        @php
                                            $statusClass = match($feeInstallment->status) {
                                                'paid' => 'success',
                                                'partial' => 'warning',
                                                'overdue' => 'danger',
                                                'cancelled' => 'secondary',
                                                default => 'info',
                                            };
                                        @endphp

                                        <span class="badge bg-{{ $statusClass }}">
                                            {{ ucfirst($feeInstallment->status) }}
                                        </span>

                                    </td>
                                </tr>


                                <tr>
                                    <th>
                                        Created
                                    </th>

                                    <td>
                                        {{ $feeInstallment->created_at?->format('d M Y h:i A') ?? '—' }}
                                    </td>
                                </tr>


                                <tr>
                                    <th>
                                        Last Updated
                                    </th>

                                    <td>
                                        {{ $feeInstallment->updated_at?->format('d M Y h:i A') ?? '—' }}
                                    </td>
                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>


        {{-- Fee Structure Information --}}
        <div class="col-lg-4">

            <div class="card">

                <div class="card-body">

                    <h4 class="card-title mb-4">
                        Fee Structure
                    </h4>


                    <div class="mb-3">

                        <label class="text-muted d-block">
                            Fee Category
                        </label>

                        <strong>
                            {{ $feeInstallment->feeStructure?->feeCategory?->name ?? 'N/A' }}
                        </strong>

                    </div>


                    <div class="mb-3">

                        <label class="text-muted d-block">
                            Academic Year
                        </label>

                        <strong>
                            {{ $feeInstallment->feeStructure?->academicYear?->name ?? 'N/A' }}
                        </strong>

                    </div>


                    <div class="mb-3">

                        <label class="text-muted d-block">
                            Class
                        </label>

                        <strong>
                            {{ $feeInstallment->feeStructure?->classModel?->name ?? 'All Classes' }}
                        </strong>

                    </div>


                    <div class="mb-3">

                        <label class="text-muted d-block">
                            Term
                        </label>

                        <strong>
                            {{ $feeInstallment->feeStructure?->term?->name ?? 'All Terms' }}
                        </strong>

                    </div>


                    <div class="mb-3">

                        <label class="text-muted d-block">
                            Fee Structure Amount
                        </label>

                        <strong class="fs-5">
                            {{ number_format((float)($feeInstallment->feeStructure?->amount ?? 0), 2) }}
                        </strong>

                    </div>


                    <div>

                        <label class="text-muted d-block">
                            Installment Amount
                        </label>

                        <strong class="text-primary fs-5">
                            {{ number_format((float)$feeInstallment->amount, 2) }}
                        </strong>

                    </div>

                </div>

            </div>


            {{-- Actions --}}
            @can('fee-installments.manage')

                <div class="card">

                    <div class="card-body">

                        <h5 class="card-title mb-3">
                            Actions
                        </h5>


                        <a href="{{ route('admin.fee-installments.edit', $feeInstallment) }}"
                           class="btn btn-warning w-100 mb-2">

                            <i class="bx bx-edit me-1"></i>
                            Edit Installment

                        </a>


                        @if(in_array($feeInstallment->status, ['pending', 'cancelled']))

                            <form method="POST"
                                  action="{{ route('admin.fee-installments.toggle-status', $feeInstallment) }}">

                                @csrf
                                @method('PATCH')

                                <button type="submit"
                                        class="btn {{ $feeInstallment->status === 'pending' ? 'btn-secondary' : 'btn-success' }} w-100 mb-2"
                                        onclick="return confirm('Are you sure you want to change this installment status?');">

                                    @if($feeInstallment->status === 'pending')
                                        <i class="bx bx-block me-1"></i>
                                        Cancel Installment
                                    @else
                                        <i class="bx bx-check me-1"></i>
                                        Activate Installment
                                    @endif

                                </button>

                            </form>

                        @endif


                        @if(!in_array($feeInstallment->status, ['paid', 'partial']))

                            <form method="POST"
                                  action="{{ route('admin.fee-installments.destroy', $feeInstallment) }}">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="btn btn-danger w-100"
                                        onclick="return confirm('Are you sure you want to delete this installment?');">

                                    <i class="bx bx-trash me-1"></i>
                                    Delete Installment

                                </button>

                            </form>

                        @endif

                    </div>

                </div>

            @endcan

        </div>

    </div>

</div>

@endsection