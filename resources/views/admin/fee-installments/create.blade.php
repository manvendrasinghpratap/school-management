@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Title --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">

                <h4 class="mb-0">Create Fee Installment</h4>

                <a href="{{ route('admin.fee-installments.index') }}"
                   class="btn btn-secondary">
                    <i class="bx bx-arrow-back me-1"></i>
                    Back to Installments
                </a>

            </div>
        </div>
    </div>


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show" role="alert">

            <strong>Please correct the following errors:</strong>

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


    {{-- Create Form --}}
    <div class="card">

        <div class="card-body">

            <h4 class="card-title mb-4">
                Installment Information
            </h4>


            <form method="POST"
                  action="{{ route('admin.fee-installments.store') }}">

                @csrf


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
                                    {{ old('fee_structure_id') == $feeStructure->id ? 'selected' : '' }}>

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
                               value="{{ old('installment_number') }}"
                               min="1"
                               step="1"
                               placeholder="Example: 1"
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
                               value="{{ old('name') }}"
                               maxlength="255"
                               placeholder="Example: First Installment"
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
                               value="{{ old('amount') }}"
                               min="0.01"
                               step="0.01"
                               placeholder="0.00"
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
                               value="{{ old('due_date') }}"
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
                                {{ old('status', 'pending') === 'pending' ? 'selected' : '' }}>
                                Pending
                            </option>

                            <option value="cancelled"
                                {{ old('status') === 'cancelled' ? 'selected' : '' }}>
                                Cancelled
                            </option>

                        </select>

                        <small class="text-muted">
                            New installments normally start as Pending.
                        </small>

                        @error('status')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Fee Structure Amount Information --}}
                    <div class="col-md-12">

                        <div class="alert alert-info mb-0">

                            <i class="bx bx-info-circle me-1"></i>

                            The total amount of installments for a fee structure
                            cannot exceed the fee structure's configured amount.

                        </div>

                    </div>


                    {{-- Buttons --}}
                    <div class="col-12">

                        <div class="d-flex gap-2 mt-3">

                            <button type="submit"
                                    class="btn btn-primary">

                                <i class="bx bx-save me-1"></i>
                                Save Installment

                            </button>


                            <a href="{{ route('admin.fee-installments.index') }}"
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