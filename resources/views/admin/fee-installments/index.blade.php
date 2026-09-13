@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Title --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Fee Installments</h4>

                @can('fee-installments.manage')
                    <a href="{{ route('admin.fee-installments.create') }}"
                       class="btn btn-primary">
                        <i class="bx bx-plus me-1"></i>
                        Add Installment
                    </a>
                @endcan
            </div>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bx bx-check-circle me-1"></i>
            {{ session('success') }}
            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bx bx-error-circle me-1"></i>
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
            <strong>Please correct the following:</strong>

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
                  action="{{ route('admin.fee-installments.index') }}">

                <div class="row g-3">

                    {{-- Fee Structure --}}
                    <div class="col-md-4">
                        <label for="fee_structure_id" class="form-label">
                            Fee Structure
                        </label>

                        <select name="fee_structure_id"
                                id="fee_structure_id"
                                class="form-select">

                            <option value="">
                                All Fee Structures
                            </option>

                            @foreach($feeStructures as $feeStructure)
                                <option value="{{ $feeStructure->id }}"
                                    {{ (string)request('fee_structure_id') === (string)$feeStructure->id ? 'selected' : '' }}>

                                    {{ $feeStructure->feeCategory?->name ?? 'N/A' }}

                                    -
                                    {{ $feeStructure->academicYear?->name ?? 'N/A' }}

                                    @if($feeStructure->classModel)
                                        -
                                        {{ $feeStructure->classModel->name }}
                                    @endif

                                    @if($feeStructure->term)
                                        -
                                        {{ $feeStructure->term->name }}
                                    @endif

                                    ({{ number_format((float)$feeStructure->amount, 2) }})

                                </option>
                            @endforeach

                        </select>
                    </div>

                    {{-- Status --}}
                    <div class="col-md-3">
                        <label for="status" class="form-label">
                            Status
                        </label>

                        <select name="status" 
                                id="status" style="width: 50%; left: 5%; top: 70%;" class="form-select">

                            <option value="">All Statuses</option>

                            @foreach([
                                'pending' => 'Pending',
                                'partial' => 'Partial',
                                'paid' => 'Paid',
                                'overdue' => 'Overdue',
                                'cancelled' => 'Cancelled',
                            ] as $value => $label)

                                <option value="{{ $value }}"
                                    {{ request('status') === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>

                            @endforeach

                        </select>
                    </div>

                    {{-- Search --}}
                    <div class="col-md-3">
                        <label for="search" class="form-label">
                            Search
                        </label>

                        <input type="text"
                               name="search"
                               id="search"
                               class="form-control"
                               value="{{ request('search') }}"
                               placeholder="Installment name...">
                    </div>

                    {{-- Buttons --}}
                    <div class="col-md-2 d-flex align-items-end">

                        <div class="d-flex gap-2 w-100">

                            <button type="submit"
                                    class="btn btn-primary w-100">
                                <i class="bx bx-search"></i>
                            </button>

                            <a href="{{ route('admin.fee-installments.index') }}"
                               class="btn btn-light"
                               title="Reset">
                                <i class="bx bx-reset"></i>
                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>
    </div>

    {{-- Installments Table --}}
    <div class="card">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <h4 class="card-title mb-0">
                    Installment List
                </h4>

                <span class="text-muted">
                    Total:
                    {{ $feeInstallments->total() }}
                </span>

            </div>

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-light">

                        <tr>
                            <th width="70">#</th>
                            <th>Fee Structure</th>
                            <th>Installment</th>
                            <th>Name</th>
                            <th>Amount</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th width="180">Actions</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($feeInstallments as $installment)

                            <tr>

                                <td>
                                    {{ $feeInstallments->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    <div>
                                        <strong>
                                            {{ $installment->feeStructure?->feeCategory?->name ?? 'N/A' }}
                                        </strong>
                                    </div>

                                    <small class="text-muted">

                                        {{ $installment->feeStructure?->academicYear?->name ?? 'N/A' }}

                                        @if($installment->feeStructure?->classModel)
                                            |
                                            {{ $installment->feeStructure->classModel->name }}
                                        @endif

                                        @if($installment->feeStructure?->term)
                                            |
                                            {{ $installment->feeStructure->term->name }}
                                        @endif

                                    </small>
                                </td>

                                <td>
                                    <span class="badge bg-info">
                                        #{{ $installment->installment_number }}
                                    </span>
                                </td>

                                <td>
                                    {{ $installment->name }}
                                </td>

                                <td>
                                    <strong>
                                        {{ number_format((float)$installment->amount, 2) }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $installment->due_date?->format('d M Y') ?? '—' }}
                                </td>

                                <td>

                                    @php
                                        $statusClass = match($installment->status) {
                                            'paid' => 'success',
                                            'partial' => 'warning',
                                            'overdue' => 'danger',
                                            'cancelled' => 'secondary',
                                            default => 'info',
                                        };
                                    @endphp

                                    <span class="badge bg-{{ $statusClass }}">
                                        {{ ucfirst($installment->status) }}
                                    </span>

                                </td>

                                <td>

                                    <div class="d-flex gap-1">

                                        {{-- View --}}
                                        @can('fee-installments.view')

                                            <a href="{{ route('admin.fee-installments.show', $installment) }}"
                                               class="btn btn-sm btn-info"
                                               title="View">

                                                <i class="bx bx-show"></i>

                                            </a>

                                        @endcan


                                        {{-- Edit --}}
                                        @can('fee-installments.manage')

                                            <a href="{{ route('admin.fee-installments.edit', $installment) }}"
                                               class="btn btn-sm btn-warning"
                                               title="Edit">

                                                <i class="bx bx-edit"></i>

                                            </a>

                                        @endcan


                                        {{-- Status Toggle --}}
                                        @can('fee-installments.manage')

                                            @if(in_array($installment->status, ['pending', 'cancelled']))

                                                <form method="POST"
                                                      action="{{ route('admin.fee-installments.toggle-status', $installment) }}">

                                                    @csrf
                                                    @method('PATCH')

                                                    <button type="submit"
                                                            class="btn btn-sm {{ $installment->status === 'pending' ? 'btn-secondary' : 'btn-success' }}"
                                                            title="{{ $installment->status === 'pending' ? 'Cancel' : 'Activate' }}"
                                                            onclick="return confirm('Are you sure you want to change this installment status?');">

                                                        @if($installment->status === 'pending')
                                                            <i class="bx bx-block"></i>
                                                        @else
                                                            <i class="bx bx-check"></i>
                                                        @endif

                                                    </button>

                                                </form>

                                            @endif

                                        @endcan


                                        {{-- Delete --}}
                                        @can('fee-installments.manage')

                                            @if(!in_array($installment->status, ['paid', 'partial']))

                                                <form method="POST"
                                                      action="{{ route('admin.fee-installments.destroy', $installment) }}">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                            class="btn btn-sm btn-danger"
                                                            title="Delete"
                                                            onclick="return confirm('Are you sure you want to delete this installment?');">

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

                                <td colspan="8"
                                    class="text-center py-4">

                                    <div class="text-muted">

                                        <i class="bx bx-receipt display-5 d-block mb-2"></i>

                                        No fee installments found.

                                        @can('fee-installments.manage')
                                            <div class="mt-2">
                                                <a href="{{ route('admin.fee-installments.create') }}"
                                                   class="btn btn-sm btn-primary">
                                                    Add First Installment
                                                </a>
                                            </div>
                                        @endcan

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- Pagination --}}
            @if($feeInstallments->hasPages())

                <div class="mt-3">
                    {{ $feeInstallments->withQueryString()->links() }}
                </div>

            @endif

        </div>
    </div>

</div>

@endsection