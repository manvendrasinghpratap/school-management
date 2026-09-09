@extends('backend.layout.default')

@section('content')
<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Leave Request Details</h4>

                <div class="page-title-right">
                    <a href="{{ route('admin.leaves.index') }}"
                       class="btn btn-light">
                        <i class="mdi mdi-arrow-left me-1"></i>
                        Back to Leave Requests
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    @endif

    {{-- Leave Details --}}
    <div class="row">

        <div class="col-xl-8 col-lg-10">

            <div class="card">

                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-calendar-text-outline me-1"></i>
                        Leave Request Details
                    </h5>

                    {{-- Status --}}
                    @switch($leave->status)

                        @case('pending')
                            <span class="badge bg-warning text-dark">
                                Pending
                            </span>
                            @break

                        @case('approved')
                            <span class="badge bg-success">
                                Approved
                            </span>
                            @break

                        @case('rejected')
                            <span class="badge bg-danger">
                                Rejected
                            </span>
                            @break

                        @case('cancelled')
                            <span class="badge bg-secondary">
                                Cancelled
                            </span>
                            @break

                        @default
                            <span class="badge bg-light text-dark">
                                {{ ucfirst($leave->status) }}
                            </span>

                    @endswitch
                </div>

                <div class="card-body">

                    {{-- Staff Information --}}
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="mdi mdi-account-outline me-1"></i>
                            Staff Information
                        </h6>

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">
                                    Staff Name
                                </label>

                                <div class="fw-semibold">
                                    {{ $leave->staff->first_name ?? '' }}
                                    {{ $leave->staff->middle_name ?? '' }}
                                    {{ $leave->staff->last_name ?? '' }}
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">
                                    Employee Number
                                </label>

                                <div class="fw-semibold">
                                    {{ $leave->staff->employee_number ?? '—' }}
                                </div>
                            </div>

                        </div>
                    </div>

                    <hr>

                    {{-- Leave Information --}}
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="mdi mdi-calendar-outline me-1"></i>
                            Leave Information
                        </h6>

                        <div class="row">

                            <div class="col-md-4 mb-3">
                                <label class="text-muted small">
                                    Leave Type
                                </label>

                                <div class="fw-semibold">
                                    {{ $leave->leave_type }}
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="text-muted small">
                                    Start Date
                                </label>

                                <div class="fw-semibold">
                                    {{ $leave->start_date?->format('Y-m-d') }}
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="text-muted small">
                                    End Date
                                </label>

                                <div class="fw-semibold">
                                    {{ $leave->end_date?->format('Y-m-d') }}
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="text-muted small">
                                    Number of Days
                                </label>

                                <div class="fw-semibold">
                                    {{ $leave->start_date->diffInDays($leave->end_date) + 1 }}
                                </div>
                            </div>

                            <div class="col-md-8 mb-3">
                                <label class="text-muted small">
                                    Status
                                </label>

                                <div>
                                    @switch($leave->status)

                                        @case('pending')
                                            <span class="badge bg-warning text-dark">
                                                Pending
                                            </span>
                                            @break

                                        @case('approved')
                                            <span class="badge bg-success">
                                                Approved
                                            </span>
                                            @break

                                        @case('rejected')
                                            <span class="badge bg-danger">
                                                Rejected
                                            </span>
                                            @break

                                        @case('cancelled')
                                            <span class="badge bg-secondary">
                                                Cancelled
                                            </span>
                                            @break

                                    @endswitch
                                </div>
                            </div>

                        </div>
                    </div>

                    <hr>

                    {{-- Reason --}}
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="mdi mdi-text-box-outline me-1"></i>
                            Reason
                        </h6>

                        <div class="border rounded p-3 bg-light">
                            @if($leave->reason)
                                {!! nl2br(e($leave->reason)) !!}
                            @else
                                <span class="text-muted">
                                    No reason provided.
                                </span>
                            @endif
                        </div>
                    </div>

                    <hr>

                    {{-- Approval Information --}}
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="mdi mdi-account-check-outline me-1"></i>
                            Approval Information
                        </h6>

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">
                                    Approved / Rejected By
                                </label>

                                <div class="fw-semibold">
                                    {{ $leave->approvedBy->name ?? '—' }}
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">
                                    School ID
                                </label>

                                <div class="fw-semibold">
                                    {{ $leave->school_id }}
                                </div>
                            </div>

                        </div>
                    </div>

                    <hr>

                    {{-- Record Information --}}
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="mdi mdi-information-outline me-1"></i>
                            Record Information
                        </h6>

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">
                                    Created At
                                </label>

                                <div>
                                    {{ $leave->created_at?->format('Y-m-d H:i:s') ?? '—' }}
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">
                                    Last Updated
                                </label>

                                <div>
                                    {{ $leave->updated_at?->format('Y-m-d H:i:s') ?? '—' }}
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="d-flex flex-wrap gap-2">

                        {{-- Edit --}}
                        @can('leaves.update')
                            @if($leave->status === 'pending')
                                <a href="{{ route('admin.leaves.edit', $leave) }}"
                                   class="btn btn-warning">
                                    <i class="mdi mdi-pencil me-1"></i>
                                    Edit
                                </a>
                            @endif
                        @endcan

                        {{-- Approve --}}
                        @can('leaves.approve')
                            @if($leave->status === 'pending')
                                <form method="POST"
                                      action="{{ route('admin.leaves.approve', $leave) }}"
                                      class="d-inline">
                                    @csrf

                                    <button type="submit"
                                            class="btn btn-success"
                                            onclick="return confirm('Approve this leave request?');">
                                        <i class="mdi mdi-check me-1"></i>
                                        Approve
                                    </button>
                                </form>
                            @endif
                        @endcan

                        {{-- Reject --}}
                        @can('leaves.reject')
                            @if($leave->status === 'pending')
                                <form method="POST"
                                      action="{{ route('admin.leaves.reject', $leave) }}"
                                      class="d-inline">
                                    @csrf

                                    <button type="submit"
                                            class="btn btn-danger"
                                            onclick="return confirm('Reject this leave request?');">
                                        <i class="mdi mdi-close me-1"></i>
                                        Reject
                                    </button>
                                </form>
                            @endif
                        @endcan

                        {{-- Cancel --}}
                        @can('leaves.update')
                            @if(in_array($leave->status, ['pending', 'approved'], true))
                                <form method="POST"
                                      action="{{ route('admin.leaves.cancel', $leave) }}"
                                      class="d-inline">
                                    @csrf

                                    <button type="submit"
                                            class="btn btn-secondary"
                                            onclick="return confirm('Cancel this leave request?');">
                                        <i class="mdi mdi-cancel me-1"></i>
                                        Cancel
                                    </button>
                                </form>
                            @endif
                        @endcan

                        {{-- Delete --}}
                        @can('leaves.delete')
                            <form method="POST"
                                  action="{{ route('admin.leaves.destroy', $leave) }}"
                                  class="d-inline">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="btn btn-danger"
                                        onclick="return confirm('Delete this leave request?');">
                                    <i class="mdi mdi-delete me-1"></i>
                                    Delete
                                </button>
                            </form>
                        @endcan

                        {{-- Back --}}
                        <a href="{{ route('admin.leaves.index') }}"
                           class="btn btn-light">
                            <i class="mdi mdi-arrow-left me-1"></i>
                            Back
                        </a>

                    </div>

                </div>
            </div>

        </div>
    </div>

</div>
@endsection