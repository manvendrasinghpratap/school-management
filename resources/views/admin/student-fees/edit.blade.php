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
                    Edit Student Fee
                </h4>


                <div class="d-flex gap-2">

                    <a href="{{ route('admin.student-fees.show', $studentFee) }}"
                       class="btn btn-light">

                        <i class="bx bx-arrow-back me-1"></i>

                        Back to Details

                    </a>

                </div>

            </div>

        </div>

    </div>


    {{-- ================================================================
         VALIDATION ERRORS
    ================================================================= --}}
    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="bx bx-error-circle me-1"></i>

            <strong>
                Please correct the following errors:
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


    {{-- ================================================================
         SESSION ERROR
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


    {{-- ================================================================
         SESSION SUCCESS
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
         PROTECTED RECORD
    ================================================================= --}}
    @if(in_array($studentFee->status, ['paid', 'partial'], true))

        <div class="alert alert-warning">

            <i class="bx bx-lock me-1"></i>

            This fee assignment is

            <strong>
                {{ ucfirst($studentFee->status) }}
            </strong>

            and cannot be edited.

        </div>


    @else


        <div class="row">

            {{-- ========================================================
                 MAIN FORM
            ========================================================= --}}
            <div class="col-lg-8">

                <div class="card">

                    <div class="card-header">

                        <h4 class="card-title mb-0">

                            <i class="bx bx-edit me-1"></i>

                            Fee Assignment Details

                        </h4>

                    </div>


                    <div class="card-body">

                        <form method="POST"
                              action="{{ route('admin.student-fees.update', $studentFee) }}"
                              id="studentFeeEditForm">

                            @csrf

                            @method('PUT')


                            {{-- =================================================
                                 CURRENT ACADEMIC PLACEMENT
                            ================================================== --}}
                            <div class="alert alert-light border mb-4">

                                <h6 class="mb-3">

                                    <i class="bx bx-sitemap me-1"></i>

                                    Academic Placement

                                </h6>


                                <div class="row">

                                    <div class="col-md-4">

                                        <small class="text-muted d-block">
                                            Academic Year
                                        </small>


                                        <strong>

                                            {{ $studentFee->feeStructure?->academicYear?->name ?? '—' }}

                                        </strong>

                                    </div>


                                    <div class="col-md-4">

                                        <small class="text-muted d-block">
                                            Class
                                        </small>


                                        <strong>

                                            {{ $studentFee->feeStructure?->classModel?->name ?? '—' }}

                                        </strong>


                                        @if($studentFee->feeStructure?->classModel?->code)

                                            <small class="text-muted">

                                                ({{ $studentFee->feeStructure->classModel->code }})

                                            </small>

                                        @endif

                                    </div>


                                    <div class="col-md-4">

                                        <small class="text-muted d-block">
                                            Term
                                        </small>


                                        <strong>

                                            {{ $studentFee->feeStructure?->term?->name ?? 'All Terms' }}

                                        </strong>

                                    </div>

                                </div>

                            </div>


                            {{-- =================================================
                                 STUDENT
                            ================================================== --}}
                            <div class="mb-3">

                                <label for="student_id"
                                       class="form-label">

                                    Student

                                    <span class="text-danger">
                                        *
                                    </span>

                                </label>


                                <select name="student_id"
                                        id="student_id"
                                        class="form-select @error('student_id') is-invalid @enderror"
                                        required>

                                    <option value="">
                                        Select Student
                                    </option>


                                    @foreach($students as $student)

                                        @php

                                            $studentName = trim(
                                                ($student->first_name ?? '') . ' ' .
                                                ($student->middle_name ?? '') . ' ' .
                                                ($student->last_name ?? '')
                                            );

                                        @endphp


                                        <option value="{{ $student->id }}"
                                            {{ (string) old('student_id', $studentFee->student_id) === (string) $student->id ? 'selected' : '' }}>

                                            {{ $studentName }}

                                            @if($student->student_number)

                                                —
                                                {{ $student->student_number }}

                                            @endif

                                        </option>

                                    @endforeach

                                </select>


                                @error('student_id')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- =================================================
                                 FEE CATEGORY / STRUCTURE
                            ================================================== --}}
                            <div class="mb-3">

                                <label for="fee_structure_id"
                                       class="form-label">

                                    Fee Structure

                                    <span class="text-danger">
                                        *
                                    </span>

                                </label>


                                <select name="fee_structure_id"
                                        id="fee_structure_id"
                                        class="form-select @error('fee_structure_id') is-invalid @enderror"
                                        required>

                                    <option value="">
                                        Select Fee Structure
                                    </option>


                                    @foreach($feeStructures as $feeStructure)

                                        <option value="{{ $feeStructure->id }}"
                                                data-amount="{{ $feeStructure->amount }}"
                                            {{ (string) old('fee_structure_id', $studentFee->fee_structure_id) === (string) $feeStructure->id ? 'selected' : '' }}>

                                            {{ $feeStructure->feeCategory?->name ?? 'N/A' }}

                                            —

                                            {{ $feeStructure->academicYear?->name ?? 'N/A' }}

                                            @if($feeStructure->classModel)

                                                —
                                                {{ $feeStructure->classModel->name }}

                                            @else

                                                —
                                                All Classes

                                            @endif


                                            @if($feeStructure->term)

                                                —
                                                {{ $feeStructure->term->name }}

                                            @else

                                                —
                                                All Terms

                                            @endif


                                            —
                                            ₹{{ number_format((float) $feeStructure->amount, 2) }}

                                        </option>

                                    @endforeach

                                </select>


                                @error('fee_structure_id')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- =================================================
                                 STRUCTURE AMOUNT
                            ================================================== --}}
                            <div class="mb-3">

                                <label for="structure_amount"
                                       class="form-label">

                                    Fee Structure Amount

                                </label>


                                <div class="input-group">

                                    <span class="input-group-text">
                                        ₹
                                    </span>


                                    <input type="text"
                                           id="structure_amount"
                                           class="form-control"
                                           readonly
                                           placeholder="Select fee structure">

                                </div>

                            </div>


                            {{-- =================================================
                                 SCHOLARSHIP
                            ================================================== --}}
                            <div class="mb-3">

                                <label for="scholarship_id"
                                       class="form-label">

                                    Scholarship

                                </label>


                                <select name="scholarship_id"
                                        id="scholarship_id"
                                        class="form-select @error('scholarship_id') is-invalid @enderror">

                                    <option value="">
                                        No Scholarship
                                    </option>


                                    @foreach($scholarships as $scholarship)

                                        <option value="{{ $scholarship->id }}"
                                            {{ (string) old('scholarship_id', $studentFee->scholarship_id) === (string) $scholarship->id ? 'selected' : '' }}>

                                            {{ $scholarship->name }}

                                            —

                                            {{ ucfirst($scholarship->type) }}

                                            —

                                            {{ number_format((float) $scholarship->value, 2) }}

                                        </option>

                                    @endforeach

                                </select>


                                @error('scholarship_id')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- =================================================
                                 ASSIGNED AMOUNT
                            ================================================== --}}
                            <div class="mb-3">

                                <label for="amount"
                                       class="form-label">

                                    Assigned Amount

                                    <span class="text-danger">
                                        *
                                    </span>

                                </label>


                                <div class="input-group">

                                    <span class="input-group-text">
                                        ₹
                                    </span>


                                    <input type="number"
                                           name="amount"
                                           id="amount"
                                           class="form-control @error('amount') is-invalid @enderror"
                                           value="{{ old('amount', $studentFee->amount) }}"
                                           min="0.01"
                                           step="0.01"
                                           required>

                                </div>


                                <small class="text-muted">

                                    The assigned amount cannot exceed the
                                    selected fee structure amount.

                                </small>


                                @error('amount')

                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- =================================================
                                 DISCOUNT
                            ================================================== --}}
                            <div class="mb-3">

                                <label for="discount"
                                       class="form-label">

                                    Discount

                                    <span class="text-danger">
                                        *
                                    </span>

                                </label>


                                <div class="input-group">

                                    <span class="input-group-text">
                                        ₹
                                    </span>


                                    <input type="number"
                                           name="discount"
                                           id="discount"
                                           class="form-control @error('discount') is-invalid @enderror"
                                           value="{{ old('discount', $studentFee->discount ?? 0) }}"
                                           min="0"
                                           step="0.01"
                                           required>

                                </div>


                                <small class="text-muted">

                                    Discount cannot exceed the assigned amount.

                                </small>


                                @error('discount')

                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- =================================================
                                 STATUS
                            ================================================== --}}
                            <div class="mb-4">

                                <label for="status"
                                       class="form-label">

                                    Status

                                    <span class="text-danger">
                                        *
                                    </span>

                                </label>


                                <select name="status"
                                        id="status"
                                        class="form-select @error('status') is-invalid @enderror"
                                        required>

                                    <option value="pending"
                                        {{ old('status', $studentFee->status) === 'pending' ? 'selected' : '' }}>

                                        Pending

                                    </option>


                                    <option value="cancelled"
                                        {{ old('status', $studentFee->status) === 'cancelled' ? 'selected' : '' }}>

                                        Cancelled

                                    </option>

                                </select>


                                @error('status')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- =================================================
                                 PAYMENT SUMMARY
                            ================================================== --}}
                            <div class="mb-4">

                                <label class="form-label">
                                    Payment Summary
                                </label>


                                <div class="alert alert-info mb-0">

                                    <div class="d-flex justify-content-between mb-2">

                                        <span>
                                            Assigned Amount
                                        </span>


                                        <strong>

                                            ₹

                                            <span id="preview_amount">
                                                0.00
                                            </span>

                                        </strong>

                                    </div>


                                    <div class="d-flex justify-content-between mb-2">

                                        <span>
                                            Discount
                                        </span>


                                        <strong class="text-danger">

                                            − ₹

                                            <span id="preview_discount">
                                                0.00
                                            </span>

                                        </strong>

                                    </div>


                                    <hr>


                                    <div class="d-flex justify-content-between">

                                        <strong>
                                            Net Payable
                                        </strong>


                                        <strong class="text-primary fs-5">

                                            ₹

                                            <span id="preview_net">
                                                0.00
                                            </span>

                                        </strong>

                                    </div>

                                </div>

                            </div>


                            {{-- =================================================
                                 BUTTONS
                            ================================================== --}}
                            <div class="d-flex gap-2">

                                <button type="submit"
                                        class="btn btn-primary">

                                    <i class="bx bx-save me-1"></i>

                                    Update Fee

                                </button>


                                <a href="{{ route('admin.student-fees.show', $studentFee) }}"
                                   class="btn btn-light">

                                    Cancel

                                </a>

                            </div>

                        </form>

                    </div>

                </div>

            </div>


            {{-- ========================================================
                 SIDE INFORMATION
            ========================================================= --}}
            <div class="col-lg-4">

                <div class="card">

                    <div class="card-header">

                        <h4 class="card-title mb-0">

                            <i class="bx bx-info-circle me-1"></i>

                            Editing Information

                        </h4>

                    </div>


                    <div class="card-body">

                        <div class="alert alert-light">

                            <h6>
                                Student
                            </h6>

                            <p class="mb-0">

                                The selected student belongs to the current
                                school.

                            </p>

                        </div>


                        <div class="alert alert-light">

                            <h6>
                                Fee Structure
                            </h6>

                            <p class="mb-0">

                                The assigned amount cannot exceed the amount
                                defined by the selected fee structure.

                            </p>

                        </div>


                        <div class="alert alert-light">

                            <h6>
                                Discount
                            </h6>

                            <p class="mb-0">

                                Discount reduces the net payable amount and
                                cannot exceed the assigned amount.

                            </p>

                        </div>


                        <div class="alert alert-warning mb-0">

                            <h6>

                                <i class="bx bx-lock me-1"></i>

                                Financial Protection

                            </h6>

                            <p class="mb-0">

                                Paid and partially paid fee assignments
                                cannot be edited.

                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    @endif

</div>


{{-- ========================================================================
     JAVASCRIPT
============================================================================= --}}
<script>

document.addEventListener('DOMContentLoaded', function () {

    const feeStructureSelect =
        document.getElementById('fee_structure_id');

    const structureAmount =
        document.getElementById('structure_amount');

    const amountInput =
        document.getElementById('amount');

    const discountInput =
        document.getElementById('discount');

    const previewAmount =
        document.getElementById('preview_amount');

    const previewDiscount =
        document.getElementById('preview_discount');

    const previewNet =
        document.getElementById('preview_net');


    /*
    |--------------------------------------------------------------------------
    | Update Structure Amount
    |--------------------------------------------------------------------------
    */

    function updateStructureAmount()
    {
        if (!feeStructureSelect) {
            return;
        }


        const selectedOption =
            feeStructureSelect.options[
                feeStructureSelect.selectedIndex
            ];


        if (
            !selectedOption ||
            !selectedOption.value
        ) {

            if (structureAmount) {
                structureAmount.value = '';
            }

            return;

        }


        const amount =
            parseFloat(
                selectedOption.dataset.amount
            ) || 0;


        if (structureAmount) {

            structureAmount.value =
                amount.toFixed(2);

        }


        updateNetPayable();
    }


    /*
    |--------------------------------------------------------------------------
    | Update Net Payable
    |--------------------------------------------------------------------------
    */

    function updateNetPayable()
    {
        if (!amountInput || !discountInput) {
            return;
        }


        const amount =
            parseFloat(
                amountInput.value
            ) || 0;


        const discount =
            parseFloat(
                discountInput.value
            ) || 0;


        const net =
            Math.max(
                0,
                amount - discount
            );


        if (previewAmount) {

            previewAmount.textContent =
                amount.toFixed(2);

        }


        if (previewDiscount) {

            previewDiscount.textContent =
                discount.toFixed(2);

        }


        if (previewNet) {

            previewNet.textContent =
                net.toFixed(2);

        }
    }


    /*
    |--------------------------------------------------------------------------
    | Events
    |--------------------------------------------------------------------------
    */

    if (feeStructureSelect) {

        feeStructureSelect.addEventListener(
            'change',
            updateStructureAmount
        );

    }


    if (amountInput) {

        amountInput.addEventListener(
            'input',
            updateNetPayable
        );

    }


    if (discountInput) {

        discountInput.addEventListener(
            'input',
            updateNetPayable
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Initial Load
    |--------------------------------------------------------------------------
    */

    updateStructureAmount();

    updateNetPayable();

});

</script>

@endsection