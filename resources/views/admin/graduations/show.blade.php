@extends('backend.layout.default')

@section('title', 'Graduation Details')

@section('content')
<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Graduation Details</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                Dashboard
                            </a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.graduations.index') }}">
                                Graduations
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

    {{-- Success Message --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="ri-checkbox-circle-line me-1"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>
        </div>
    @endif

    {{-- Error Message --}}
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="ri-error-warning-line me-1"></i>
            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>
        </div>
    @endif

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <h6 class="alert-heading">
                Please correct the following errors:
            </h6>

            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">

        {{-- Main Graduation Information --}}
        <div class="col-lg-8">

            {{-- Student Card --}}
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0">
                        Student Information
                    </h5>

                    @if ($graduation->status === 'pending')
                        <span class="badge bg-warning-subtle text-warning">
                            Pending
                        </span>
                    @elseif ($graduation->status === 'approved')
                        <span class="badge bg-info-subtle text-info">
                            Approved
                        </span>
                    @elseif ($graduation->status === 'completed')
                        <span class="badge bg-success-subtle text-success">
                            Completed
                        </span>
                    @endif
                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <div class="text-muted small">
                                Student Number
                            </div>

                            <div class="fw-semibold">
                                {{ $graduation->student->student_number ?? '—' }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="text-muted small">
                                Admission Number
                            </div>

                            <div class="fw-semibold">
                                {{ $graduation->student->admission_number ?? '—' }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="text-muted small">
                                Student Name
                            </div>

                            <div class="fw-semibold">
                                {{ trim(
                                    ($graduation->student->first_name ?? '') . ' ' .
                                    ($graduation->student->middle_name ?? '') . ' ' .
                                    ($graduation->student->last_name ?? '')
                                ) }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="text-muted small">
                                Student Status
                            </div>

                            @if (($graduation->student->status ?? null) === 'active')
                                <span class="badge bg-success-subtle text-success">
                                    Active
                                </span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary">
                                    {{ ucfirst($graduation->student->status ?? 'Unknown') }}
                                </span>
                            @endif
                        </div>

                    </div>

                    <div class="mt-2">
                        <a href="{{ route('admin.students.show', $graduation->student) }}"
                           class="btn btn-soft-primary btn-sm">
                            <i class="ri-user-line me-1"></i>
                            View Student
                        </a>
                    </div>

                </div>
            </div>

            {{-- Graduation Information --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        Graduation Information
                    </h5>
                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6 mb-4">
                            <div class="text-muted small mb-1">
                                Academic Year
                            </div>

                            <div class="fw-semibold">
                                {{ $graduation->academicYear->name ?? '—' }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="text-muted small mb-1">
                                Graduation Date
                            </div>

                            <div class="fw-semibold">
                                {{ $graduation->graduation_date?->format('d M Y') ?? '—' }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="text-muted small mb-1">
                                Qualification
                            </div>

                            <div class="fw-semibold">
                                {{ $graduation->qualification ?: 'Not specified' }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="text-muted small mb-1">
                                Status
                            </div>

                            @if ($graduation->status === 'pending')
                                <span class="badge bg-warning-subtle text-warning">
                                    Pending Approval
                                </span>
                            @elseif ($graduation->status === 'approved')
                                <span class="badge bg-info-subtle text-info">
                                    Approved
                                </span>
                            @else
                                <span class="badge bg-success-subtle text-success">
                                    Completed
                                </span>
                            @endif
                        </div>

                    </div>

                    @if ($graduation->remarks)
                        <div class="border-top pt-3">
                            <div class="text-muted small mb-1">
                                Remarks
                            </div>

                            <div>
                                {!! nl2br(e($graduation->remarks)) !!}
                            </div>
                        </div>
                    @endif

                </div>
            </div>

            {{-- Approval Information --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        Approval Information
                    </h5>
                </div>

                <div class="card-body">

                    @if ($graduation->approvedBy)
                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <div class="text-muted small">
                                    Approved By
                                </div>

                                <div class="fw-semibold">
                                    {{ $graduation->approvedBy->name }}
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="text-muted small">
                                    Approval Status
                                </div>

                                <span class="badge bg-success-subtle text-success">
                                    Approved
                                </span>
                            </div>

                        </div>
                    @else
                        <div class="text-muted">
                            <i class="ri-information-line me-1"></i>
                            This graduation has not yet been approved.
                        </div>
                    @endif

                </div>
            </div>

        </div>

        {{-- Actions / Status --}}
        <div class="col-lg-4">

            {{-- Workflow Actions --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        Graduation Workflow
                    </h5>
                </div>

                <div class="card-body">

                    @if ($graduation->status === 'pending')

                        <div class="alert alert-warning">
                            <i class="ri-time-line me-1"></i>
                            This graduation is waiting for approval.
                        </div>

                        @can('graduation.manage')
                            <form method="POST"
                                  action="{{ route('admin.graduations.approve', $graduation) }}"
                                  class="mb-2"
                                  onsubmit="return confirm('Approve this graduation record?');">

                                @csrf
                                @method('PUT')

                                <button type="submit"
                                        class="btn btn-success w-100">
                                    <i class="ri-checkbox-circle-line me-1"></i>
                                    Approve Graduation
                                </button>
                            </form>
                        @endcan

                        @can('graduation.manage')
                            <form method="POST"
                                  action="{{ route('admin.graduations.destroy', $graduation) }}"
                                  onsubmit="return confirm('Delete this pending graduation record?');">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="btn btn-danger w-100">
                                    <i class="ri-delete-bin-line me-1"></i>
                                    Delete Pending Record
                                </button>
                            </form>
                        @endcan

                    @elseif ($graduation->status === 'approved')

                        <div class="alert alert-info">
                            <i class="ri-checkbox-circle-line me-1"></i>
                            This graduation has been approved and is ready for completion.
                        </div>

                        @can('graduation.manage')
                            <form method="POST"
                                  action="{{ route('admin.graduations.complete', $graduation) }}"
                                  onsubmit="return confirm('Mark this graduation as completed?');">

                                @csrf
                                @method('PUT')

                                <button type="submit"
                                        class="btn btn-success w-100">
                                    <i class="ri-award-line me-1"></i>
                                    Mark as Completed
                                </button>
                            </form>
                        @endcan

                    @elseif ($graduation->status === 'completed')

                        <div class="alert alert-success">
                            <i class="ri-award-line me-1"></i>
                            <strong>Graduation Completed</strong>
                            <br>
                            This student's graduation has been finalized.
                        </div>

                        <div class="text-center">
                            <i class="ri-graduation-cap-line display-5 text-success"></i>

                            <p class="text-muted mt-2 mb-0">
                                The graduation record is now part of the student's permanent academic history.
                            </p>
                        </div>

                    @endif

                </div>
            </div>

            {{-- Record Information --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        Record Information
                    </h5>
                </div>

                <div class="card-body">

                    <div class="mb-3">
                        <div class="text-muted small">
                            Graduation ID
                        </div>

                        <div class="fw-semibold">
                            #{{ $graduation->id }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="text-muted small">
                            Created
                        </div>

                        <div>
                            {{ $graduation->created_at?->format('d M Y H:i') ?? '—' }}
                        </div>
                    </div>

                    <div>
                        <div class="text-muted small">
                            Last Updated
                        </div>

                        <div>
                            {{ $graduation->updated_at?->format('d M Y H:i') ?? '—' }}
                        </div>
                    </div>

                </div>
            </div>

            {{-- Navigation --}}
            <div class="card">
                <div class="card-body">

                    <a href="{{ route('admin.graduations.index') }}"
                       class="btn btn-light w-100">
                        <i class="ri-arrow-left-line me-1"></i>
                        Back to Graduations
                    </a>

                </div>
            </div>

        </div>

    </div>

</div>
@endsection