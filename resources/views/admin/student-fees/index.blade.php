@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Title --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">

                <h4 class="mb-0">Student Fees</h4>

                @can('fees.manage')
                    <a href="{{ route('admin.student-fees.create') }}"
                       class="btn btn-primary">
                        <i class="bx bx-plus me-1"></i>
                        Assign Fee
                    </a>
                @endcan

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


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show"
             role="alert">

            <strong>Please correct the following:</strong>

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


    {{-- Filters --}}
    <div class="card">

        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.student-fees.index') }}">

                <div class="row g-3">

                    {{-- Student --}}
                    <div class="col-md-3">

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

                                <option value="{{ $student->id }}"
                                    {{ (string)request('student_id') === (string)$student->id ? 'selected' : '' }}>

                                    {{ $student->full_name }}

                                    @if($student->student_number)
                                        ({{ $student->student_number }})
                                    @endif

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Fee Structure --}}
                    <div class="col-md-4">

                        <label for="fee_structure_id"
                               class="form-label">
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
                                    @else
                                        - All Terms
                                    @endif

                                    |
                                    {{ number_format((float)$feeStructure->amount, 2) }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Status --}}
                    <div class="col-md-2">

                        <label for="status"
                               class="form-label">
                            Status
                        </label>

                        <select name="status"
                                id="status"
                                class="form-select">

                            <option value="">
                                All
                            </option>

                            <option value="pending"
                                {{ request('status') === 'pending' ? 'selected' : '' }}>
                                Pending
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


                    {{-- Search --}}
                    <div class="col-md-2">

                        <label for="search"
                               class="form-label">
                            Search
                        </label>

                        <input type="text"
                               name="search"
                               id="search"
                               class="form-control"
                               value="{{ request('search') }}"
                               placeholder="Student name/number">

                    </div>


                    {{-- Buttons --}}
                    <div class="col-md-1 d-flex align-items-end">

                        <div class="d-flex gap-1">

                            <button type="submit"
                                    class="btn btn-primary"
                                    title="Search">

                                <i class="bx bx-search"></i>

                            </button>

                            <a href="{{ route('admin.student-fees.index') }}"
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


    {{-- Student Fees Table --}}
    <div class="card">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <h4 class="card-title mb-0">
                    Fee Assignments
                </h4>

                <span class="text-muted">
                    Total: {{ $studentFees->total() }}
                </span>

            </div>


            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-light">

                        <tr>

                            <th width="60">#</th>

                            <th>Student</th>

                            <th>Fee Structure</th>

                            <th>Amount</th>

                            <th>Discount</th>

                            <th>Net Payable</th>

                            <th>Scholarship</th>

                            <th>Status</th>

                            <th width="170">Actions</th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($studentFees as $studentFee)

                            @php
                                $amount = (float)$studentFee->amount;
                                $discount = (float)$studentFee->discount;
                                $netPayable = max(0, $amount - $discount);

                                $statusClass = match($studentFee->status) {
                                    'paid' => 'success',
                                    'partial' => 'warning',
                                    'cancelled' => 'secondary',
                                    default => 'info',
                                };
                            @endphp


                            <tr>

                                {{-- Number --}}
                                <td>
                                    {{ $studentFees->firstItem() + $loop->index }}
                                </td>


                                {{-- Student --}}
                                <td>

                                    <strong>
                                        {{ $studentFee->student?->full_name ?? 'N/A' }}
                                    </strong>

                                    @if($studentFee->student?->student_number)

                                        <small class="text-muted d-block">
                                            {{ $studentFee->student->student_number }}
                                        </small>

                                    @endif

                                </td>


                                {{-- Fee Structure --}}
                                <td>

                                    <strong>
                                        {{ $studentFee->feeStructure?->feeCategory?->name ?? 'N/A' }}
                                    </strong>

                                    <small class="text-muted d-block">

                                        {{ $studentFee->feeStructure?->academicYear?->name ?? 'N/A' }}

                                        @if($studentFee->feeStructure?->classModel)
                                            |
                                            {{ $studentFee->feeStructure->classModel->name }}
                                        @endif

                                        @if($studentFee->feeStructure?->term)
                                            |
                                            {{ $studentFee->feeStructure->term->name }}
                                        @else
                                            |
                                            All Terms
                                        @endif

                                    </small>

                                </td>


                                {{-- Amount --}}
                                <td>

                                    <strong>
                                        {{ number_format($amount, 2) }}
                                    </strong>

                                </td>


                                {{-- Discount --}}
                                <td>

                                    @if($discount > 0)

                                        <span class="text-success">
                                            {{ number_format($discount, 2) }}
                                        </span>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- Net Payable --}}
                                <td>

                                    <strong class="text-primary">
                                        {{ number_format($netPayable, 2) }}
                                    </strong>

                                </td>


                                {{-- Scholarship --}}
                                <td>

                                    @if($studentFee->scholarship)

                                        <span class="badge bg-success">
                                            {{ $studentFee->scholarship->name }}
                                        </span>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- Status --}}
                                <td>

                                    <span class="badge bg-{{ $statusClass }}">
                                        {{ ucfirst($studentFee->status) }}
                                    </span>

                                </td>


                                {{-- Actions --}}
                                <td>

                                    <div class="d-flex gap-1">

                                        @can('fees.view')

                                            <a href="{{ route('admin.student-fees.show', $studentFee) }}"
                                               class="btn btn-sm btn-info"
                                               title="View">

                                                <i class="bx bx-show"></i>

                                            </a>

                                        @endcan


                                        @can('fees.manage')

                                            @if(!in_array($studentFee->status, ['paid', 'partial']))

                                                <a href="{{ route('admin.student-fees.edit', $studentFee) }}"
                                                   class="btn btn-sm btn-warning"
                                                   title="Edit">

                                                    <i class="bx bx-edit"></i>

                                                </a>

                                            @endif

                                        @endcan


                                        @can('fees.manage')

                                            @if(!in_array($studentFee->status, ['paid', 'partial', 'cancelled']))

                                                <form method="POST"
                                                      action="{{ route('admin.student-fees.cancel', $studentFee) }}">

                                                    @csrf
                                                    @method('PATCH')

                                                    <button type="submit"
                                                            class="btn btn-sm btn-secondary"
                                                            title="Cancel"
                                                            onclick="return confirm('Are you sure you want to cancel this student fee?');">

                                                        <i class="bx bx-block"></i>

                                                    </button>

                                                </form>

                                            @endif

                                        @endcan


                                        @can('fees.manage')

                                            @if(!in_array($studentFee->status, ['paid', 'partial']))

                                                <form method="POST"
                                                      action="{{ route('admin.student-fees.destroy', $studentFee) }}">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                            class="btn btn-sm btn-danger"
                                                            title="Delete"
                                                            onclick="return confirm('Are you sure you want to delete this student fee?');">

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

                                <td colspan="9"
                                    class="text-center py-5">

                                    <div class="text-muted">

                                        <i class="bx bx-receipt display-5 d-block mb-2"></i>

                                        <h5>No Student Fees Found</h5>

                                        <p class="mb-2">
                                            No fee assignments have been created yet.
                                        </p>

                                        @can('fees.manage')

                                            <a href="{{ route('admin.student-fees.create') }}"
                                               class="btn btn-primary btn-sm">

                                                <i class="bx bx-plus me-1"></i>
                                                Assign First Fee

                                            </a>

                                        @endcan

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- Pagination --}}
            @if($studentFees->hasPages())

                <div class="mt-3">

                    {{ $studentFees->withQueryString()->links() }}

                </div>

            @endif

        </div>

    </div>

</div>

@endsection