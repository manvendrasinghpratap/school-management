@extends('backend.layout.default')

@section('title', 'Promotion Details')

@section('content')
<div class="container-fluid">

    {{-- Header --}}
    <div class="row">

        <div class="col-12">

            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0 font-size-18">
                    Promotion Details
                </h4>

                <div class="page-title-right">

                    <ol class="breadcrumb m-0">

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                Dashboard
                            </a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.student-promotions.index') }}">
                                Promotions
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


    {{-- Flash Messages --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bx bx-check-circle me-2"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="bx bx-error-circle me-2"></i>

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    <div class="row">

        {{-- Student --}}
        <div class="col-lg-4">

            <div class="card">

                <div class="card-body text-center">

                    @if($promotion->student?->photo)

                        <img src="{{ asset('storage/' . $promotion->student->photo) }}"
                             class="rounded-circle avatar-lg mb-3"
                             alt="Student">

                    @else

                        <div class="avatar-lg mx-auto mb-3">

                            <span class="avatar-title rounded-circle bg-primary font-size-24">

                                {{ strtoupper(
                                    substr($promotion->student?->first_name ?? 'S', 0, 1)
                                    .
                                    substr($promotion->student?->last_name ?? 'T', 0, 1)
                                ) }}

                            </span>

                        </div>

                    @endif


                    <h5 class="mb-1">

                        {{ $promotion->student?->first_name }}
                        {{ $promotion->student?->middle_name }}
                        {{ $promotion->student?->last_name }}

                    </h5>

                    <p class="text-muted mb-2">

                        {{ $promotion->student?->student_number }}

                    </p>


                    @if($promotion->status === 'pending')

                        <span class="badge bg-warning font-size-13">
                            Pending
                        </span>

                    @elseif($promotion->status === 'approved')

                        <span class="badge bg-success font-size-13">
                            Approved
                        </span>

                    @else

                        <span class="badge bg-danger font-size-13">
                            Rejected
                        </span>

                    @endif

                </div>

            </div>


            {{-- Student Details --}}
            <div class="card">

                <div class="card-header">

                    <h5 class="card-title mb-0">
                        Student Information
                    </h5>

                </div>

                <div class="card-body">

                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Student Number
                        </small>

                        <strong>
                            {{ $promotion->student?->student_number ?? '—' }}
                        </strong>

                    </div>


                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Admission Number
                        </small>

                        <strong>
                            {{ $promotion->student?->admission_number ?? '—' }}
                        </strong>

                    </div>


                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Gender
                        </small>

                        <strong>
                            {{ ucfirst($promotion->student?->gender ?? '—') }}
                        </strong>

                    </div>


                    <div>

                        <small class="text-muted d-block">
                            Status
                        </small>

                        <strong>
                            {{ ucfirst($promotion->student?->status ?? '—') }}
                        </strong>

                    </div>

                </div>

            </div>

        </div>


        {{-- Promotion --}}
        <div class="col-lg-8">

            <div class="card">

                <div class="card-header">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h4 class="card-title mb-1">
                                Promotion Information
                            </h4>

                            <p class="text-muted mb-0">
                                Promotion #{{ $promotion->id }}
                            </p>

                        </div>


                        <div>

                            @if($promotion->status === 'pending')

                                <span class="badge bg-warning font-size-13 px-3 py-2">
                                    Pending Approval
                                </span>

                            @elseif($promotion->status === 'approved')

                                <span class="badge bg-success font-size-13 px-3 py-2">
                                    Approved
                                </span>

                            @else

                                <span class="badge bg-danger font-size-13 px-3 py-2">
                                    Rejected
                                </span>

                            @endif

                        </div>

                    </div>

                </div>


                <div class="card-body">

                    {{-- Academic Year --}}
                    <div class="row mb-4">

                        <div class="col-md-6">

                            <div class="border rounded p-3">

                                <small class="text-muted d-block mb-1">
                                    Academic Year
                                </small>

                                <h5 class="mb-0">
                                    {{ $promotion->academicYear?->name ?? '—' }}
                                </h5>

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="border rounded p-3">

                                <small class="text-muted d-block mb-1">
                                    Promotion Date
                                </small>

                                <h5 class="mb-0">
                                    {{ $promotion->promotion_date?->format('Y-m-d') ?? '—' }}
                                </h5>

                            </div>

                        </div>

                    </div>


                    {{-- Class Movement --}}
                    <div class="row align-items-center">

                        <div class="col-md-5">

                            <div class="card border shadow-none">

                                <div class="card-body text-center">

                                    <span class="badge bg-secondary mb-3">
                                        CURRENT CLASS
                                    </span>

                                    <h4 class="mb-2">
                                        {{ $promotion->fromClass?->name ?? '—' }}
                                    </h4>

                                    @if($promotion->fromSection)

                                        <p class="text-muted mb-0">
                                            Section:
                                            <strong>
                                                {{ $promotion->fromSection->name }}
                                            </strong>
                                        </p>

                                    @endif

                                </div>

                            </div>

                        </div>


                        <div class="col-md-2 text-center">

                            <i class="bx bx-right-arrow-alt font-size-30 text-primary"></i>

                        </div>


                        <div class="col-md-5">

                            <div class="card border border-success shadow-none">

                                <div class="card-body text-center">

                                    <span class="badge bg-success mb-3">
                                        DESTINATION CLASS
                                    </span>

                                    <h4 class="mb-2">
                                        {{ $promotion->toClass?->name ?? '—' }}
                                    </h4>

                                    @if($promotion->toSection)

                                        <p class="text-muted mb-0">
                                            Section:
                                            <strong>
                                                {{ $promotion->toSection->name }}
                                            </strong>
                                        </p>

                                    @endif

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- Remarks --}}
                    @if($promotion->remarks)

                        <div class="mt-4">

                            <h5 class="font-size-15">
                                Remarks
                            </h5>

                            <div class="alert alert-light border">

                                {!! nl2br(e($promotion->remarks)) !!}

                            </div>

                        </div>

                    @endif


                    {{-- Approval --}}
                    @if($promotion->approvedBy)

                        <div class="mt-4">

                            <div class="alert alert-success">

                                <div class="d-flex">

                                    <i class="bx bx-check-circle font-size-20 me-2"></i>

                                    <div>

                                        <strong>
                                            Reviewed By
                                        </strong>

                                        <br>

                                        {{ $promotion->approvedBy->name }}

                                    </div>

                                </div>

                            </div>

                        </div>

                    @endif

                </div>

            </div>


            {{-- Workflow Actions --}}
            @if($promotion->status === 'pending')

                <div class="card">

                    <div class="card-header">

                        <h5 class="card-title mb-0">
                            Promotion Approval
                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="alert alert-info">

                            <i class="bx bx-info-circle me-2"></i>

                            Approving this promotion will:

                            <ul class="mb-0 mt-2">

                                <li>
                                    Complete the student's current enrollment.
                                </li>

                                <li>
                                    Create a new enrollment for the destination class.
                                </li>

                                <li>
                                    Preserve the student's previous enrollment history.
                                </li>

                                <li>
                                    Mark this promotion as approved.
                                </li>

                            </ul>

                        </div>


                        <div class="d-flex justify-content-end gap-2">

                            @can('promotions.reject')

                                <button type="button"
                                        class="btn btn-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#rejectPromotionModal">

                                    <i class="bx bx-x me-1"></i>

                                    Reject Promotion

                                </button>

                            @endcan


                            @can('promotions.approve')

                                <form method="POST"
                                      action="{{ route('admin.student-promotions.approve', $promotion) }}"
                                      onsubmit="return confirm('Are you sure you want to approve this promotion? This action will create a new enrollment for the destination class.')">

                                    @csrf
                                    @method('PUT')

                                    <button type="submit"
                                            class="btn btn-success">

                                        <i class="bx bx-check me-1"></i>

                                        Approve Promotion

                                    </button>

                                </form>

                            @endcan

                        </div>

                    </div>

                </div>

            @endif


            {{-- Rejection Information --}}
            @if($promotion->status === 'rejected')

                <div class="card">

                    <div class="card-body">

                        <div class="alert alert-danger mb-0">

                            <i class="bx bx-x-circle me-2"></i>

                            This promotion has been rejected.

                            @if($promotion->remarks)

                                <hr>

                                <strong>Reason:</strong>

                                <div class="mt-2">
                                    {!! nl2br(e($promotion->remarks)) !!}
                                </div>

                            @endif

                        </div>

                    </div>

                </div>

            @endif


            {{-- Actions --}}
            <div class="card">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <a href="{{ route('admin.student-promotions.index') }}"
                           class="btn btn-light">

                            <i class="bx bx-arrow-back me-1"></i>

                            Back to Promotions

                        </a>


                        <div class="d-flex gap-2">

                            @if($promotion->status === 'pending')

                                @can('promotions.update')

                                    <a href="{{ route('admin.student-promotions.edit', $promotion) }}"
                                       class="btn btn-warning">

                                        <i class="bx bx-edit me-1"></i>

                                        Edit

                                    </a>

                                @endcan

                            @endif


                            @if($promotion->status !== 'approved')

                                @can('promotions.delete')

                                    <form method="POST"
                                          action="{{ route('admin.student-promotions.destroy', $promotion) }}"
                                          onsubmit="return confirm('Delete this promotion record?')">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-danger">

                                            <i class="bx bx-trash me-1"></i>

                                            Delete

                                        </button>

                                    </form>

                                @endcan

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- Reject Modal --}}
@can('promotions.reject')

    @if($promotion->status === 'pending')

        <div class="modal fade"
             id="rejectPromotionModal"
             tabindex="-1"
             aria-hidden="true">

            <div class="modal-dialog">

                <div class="modal-content">

                    <form method="POST"
                          action="{{ route('admin.student-promotions.reject', $promotion) }}">

                        @csrf
                        @method('PUT')

                        <div class="modal-header">

                            <h5 class="modal-title">
                                Reject Promotion
                            </h5>

                            <button type="button"
                                    class="btn-close"
                                    data-bs-dismiss="modal">
                            </button>

                        </div>

                        <div class="modal-body">

                            <p>
                                You are rejecting the promotion for
                                <strong>
                                    {{ $promotion->student?->first_name }}
                                    {{ $promotion->student?->last_name }}
                                </strong>.
                            </p>

                            <div class="mb-3">

                                <label class="form-label">
                                    Reason for Rejection
                                    <span class="text-danger">*</span>
                                </label>

                                <textarea name="remarks"
                                          class="form-control"
                                          rows="5"
                                          maxlength="5000"
                                          required
                                          placeholder="Enter the reason for rejecting this promotion...">{{ old('remarks') }}</textarea>

                            </div>

                        </div>

                        <div class="modal-footer">

                            <button type="button"
                                    class="btn btn-light"
                                    data-bs-dismiss="modal">

                                Cancel

                            </button>

                            <button type="submit"
                                    class="btn btn-danger">

                                <i class="bx bx-x me-1"></i>

                                Reject Promotion

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    @endif

@endcan

@endsection