@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Title --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">

                <h4 class="mb-0">Edit Fee Installment</h4>

                <div class="d-flex gap-2">

                    <a href="{{ route('admin.fee-installments.show', $feeInstallment) }}"
                       class="btn btn-info">
                        <i class="bx bx-show me-1"></i>
                        View
                    </a>

                    <a href="{{ route('admin.fee-installments.index') }}"
                       class="btn btn-secondary">
                        <i class="bx bx-arrow-back me-1"></i>
                        Back
                    </a>

                </div>

            </div>
        </div>
    </div>


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show"
             role="alert">

            <strong>Please correct the following errors:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


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


    <div class="card">

        <div class="card-body">

            <h4 class="card-title mb-4">
                Installment Information
            </h4>


            <form method="POST"
                  action="{{ route('admin.fee-installments.update', $feeInstallment) }}">

                @csrf
                @method('PUT')


                <div class="row g-3">

                    {{-- Fee Structure --}}
                    <div class="col-md-8">

                        <label for="fee_structure_id"
                               class="form-label">

                            Fee Structure
                            <span class="text-danger">*</span>

                        </label>

                        <select name="fee_structure_id"
                                id="fee_structure_id"
                                class="form-select @error('fee_structure_id') is-invalid @enderror"
                                required>

                            <option value="">
                                -- Select Fee Structure --
                            </option>

                            @foreach($feeStructures as $feeStructure)

                                <option value="{{ $feeStructure->id }}"
                                    {{ old('fee_structure_id', $feeInstallment->fee_structure_id) == $feeStructure->id ? 'selected' : '' }}>

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
                                    @else
                                        - All Terms
                                    @endif

                                    |
                                    Amount:
                                    {{ number_format((float)$feeStructure->amount, 2) }}

                                </option>

                            @endforeach

                        </select>

                        @error('fee_structure_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Installment Number --}}
                    <div class="col-md-4">

                        <label for="installment_number"
                               class="form-label">

                            Installment Number
                            <span class="text-danger">*</span>

                        </label>

                        <input type="number"
                               name="installment_number"
                               id="installment_number"
                               class="form-control @error('installment_number') is-invalid @enderror"
                               value="{{ old('installment_number', $feeInstallment->installment_number) }}"
                               min="1"
                               step="1"
                               required>

                        @error('installment_number')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Installment Name --}}
                    <div class="col-md-8">

                        <label for="name"
                               class="form-label">

                            Installment Name
                            <span class="text-danger">*</span>

                        </label>

                        <input type="text"
                               name="name"
                               id="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $feeInstallment->name) }}"
                               maxlength="255"
                               required>

                        @error('name')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Amount --}}
                    <div class="col-md-4">

                        <label for="amount"
                               class="form-label">

                            Amount
                            <span class="text-danger">*</span>

                        </label>

                        <input type="number"
                               name="amount"
                               id="amount"
                               class="form-control @error('amount') is-invalid @enderror"
                               value="{{ old('amount', $feeInstallment->amount) }}"
                               min="0.01"
                               step="0.01"
                               required>

                        @error('amount')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Due Date --}}
                    <div class="col-md-4">

                        <label for="due_date"
                               class="form-label">

                            Due Date
                            <span class="text-danger">*</span>

                        </label>

                        <input type="date"
                               name="due_date"
                               id="due_date"
                               class="form-control @error('due_date') is-invalid @enderror"
                               value="{{ old('due_date', optional($feeInstallment->due_date)->format('Y-m-d')) }}"
                               required>

                        @error('due_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Status --}}
                    <div class="col-md-4">

                        <label for="status"
                               class="form-label">

                            Status
                            <span class="text-danger">*</span>

                        </label>

                        <select name="status"
                                id="status"
                                class="form-select @error('status') is-invalid @enderror"
                                required>

                            <option value="pending"
                                {{ old('status', $feeInstallment->status) === 'pending' ? 'selected' : '' }}>
                                Pending
                            </option>

                            <option value="partial"
                                {{ old('status', $feeInstallment->status) === 'partial' ? 'selected' : '' }}>
                                Partial
                            </option>

                            <option value="paid"
                                {{ old('status', $feeInstallment->status) === 'paid' ? 'selected' : '' }}>
                                Paid
                            </option>

                            <option value="overdue"
                                {{ old('status', $feeInstallment->status) === 'overdue' ? 'selected' : '' }}>
                                Overdue
                            </option>

                            <option value="cancelled"
                                {{ old('status', $feeInstallment->status) === 'cancelled' ? 'selected' : '' }}>
                                Cancelled
                            </option>

                        </select>

                        @error('status')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Financial Protection Notice --}}
                    <div class="col-12">

                        <div class="alert alert-info mb-0">

                            <i class="bx bx-info-circle me-1"></i>

                            The total amount of installments for a fee structure
                            cannot exceed the fee structure's configured amount.

                        </div>

                    </div>


                    {{-- Current Record --}}
                    <div class="col-12">

                        <div class="border rounded p-3 mt-2">

                            <div class="row">

                                <div class="col-md-4">

                                    <small class="text-muted d-block">
                                        Current Installment
                                    </small>

                                    <strong>
                                        #{{ $feeInstallment->installment_number }}
                                    </strong>

                                </div>

                                <div class="col-md-4">

                                    <small class="text-muted d-block">
                                        Current Amount
                                    </small>

                                    <strong>
                                        {{ number_format((float)$feeInstallment->amount, 2) }}
                                    </strong>

                                </div>

                                <div class="col-md-4">

                                    <small class="text-muted d-block">
                                        Current Status
                                    </small>

                                    <strong>
                                        {{ ucfirst($feeInstallment->status) }}
                                    </strong>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- Buttons --}}
                    <div class="col-12">

                        <div class="d-flex gap-2 mt-3">

                            <button type="submit"
                                    class="btn btn-primary">

                                <i class="bx bx-save me-1"></i>
                                Update Installment

                            </button>


                            <a href="{{ route('admin.fee-installments.show', $feeInstallment) }}"
                               class="btn btn-light">

                                Cancel

                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection