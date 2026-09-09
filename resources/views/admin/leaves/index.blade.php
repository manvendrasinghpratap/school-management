@extends('backend.layout.default')

@section('content')
<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Leave Management</h4>

                <div class="page-title-right">
                    @can('leaves.create')
                        <a href="{{ route('admin.leaves.create') }}"
                           class="btn btn-primary">
                            <i class="mdi mdi-plus me-1"></i>
                            New Leave Request
                        </a>
                    @endcan
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

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Please correct the following:</strong>

            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Filters --}}
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="mdi mdi-filter-outline me-1"></i>
                Filter Leave Requests
            </h5>
        </div>

        <div class="card-body">
            <form method="GET"
                  action="{{ route('admin.leaves.index') }}">

                <div class="row g-3">

                    {{-- Staff --}}
                    <div class="col-md-4">
                        <label for="staff_id" class="form-label">
                            Staff
                        </label>

                        <select name="staff_id"
                                id="staff_id"
                                class="form-select">

                            <option value="">
                                All Staff
                            </option>

                            @foreach($staff as $member)
                                <option value="{{ $member->id }}"
                                    {{ (string)request('staff_id') === (string)$member->id ? 'selected' : '' }}>
                                    {{ $member->first_name }}
                                    {{ $member->middle_name }}
                                    {{ $member->last_name }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    {{-- Leave Type --}}
                    <div class="col-md-4">
                        <label for="leave_type" class="form-label">
                            Leave Type
                        </label>

                        <input type="text"
                               name="leave_type"
                               id="leave_type"
                               class="form-control"
                               value="{{ request('leave_type') }}"
                               placeholder="e.g. Annual, Sick, Casual">
                    </div>

                    {{-- Status --}}
                    <div class="col-md-4">
                        <label for="status" class="form-label">
                            Status
                        </label>

                        <select name="status"
                                id="status"
                                class="form-select w-100"
        style="min-width: 100%;left: 6%;top: 71%;">

                            <option value="">
                                All Statuses
                            </option>

                            <option value="pending"
                                {{ request('status') === 'pending' ? 'selected' : '' }}>
                                Pending
                            </option>

                            <option value="approved"
                                {{ request('status') === 'approved' ? 'selected' : '' }}>
                                Approved
                            </option>

                            <option value="rejected"
                                {{ request('status') === 'rejected' ? 'selected' : '' }}>
                                Rejected
                            </option>

                            <option value="cancelled"
                                {{ request('status') === 'cancelled' ? 'selected' : '' }}>
                                Cancelled
                            </option>

                        </select>
                    </div>

                    {{-- Buttons --}}
                    <div class="col-12">
                        <button type="submit"
                                class="btn btn-primary">
                            <i class="mdi mdi-filter me-1"></i>
                            Apply Filters
                        </button>

                        <a href="{{ route('admin.leaves.index') }}"
                           class="btn btn-light">
                            <i class="mdi mdi-refresh me-1"></i>
                            Reset
                        </a>
                    </div>

                </div>

            </form>
        </div>
    </div>

    {{-- Leave Requests --}}
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                Leave Requests
            </h5>
        </div>

        <div class="card-body">

            @if($leaves->count())

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0">

                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Staff</th>
                                <th>Leave Type</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Days</th>
                                <th>Status</th>
                                <th>Approved By</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                        @foreach($leaves as $leave)

                            @php
                                $days = $leave->start_date->diffInDays(
                                    $leave->end_date
                                ) + 1;
                            @endphp

                            <tr>

                                <td>
                                    {{ $leaves->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    <strong>
                                        {{ $leave->staff->first_name ?? '' }}
                                        {{ $leave->staff->middle_name ?? '' }}
                                        {{ $leave->staff->last_name ?? '' }}
                                    </strong>

                                    @if($leave->staff?->employee_number)
                                        <div class="text-muted small">
                                            {{ $leave->staff->employee_number }}
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    {{ $leave->leave_type }}
                                </td>

                                <td>
                                    {{ $leave->start_date?->format('Y-m-d') }}
                                </td>

                                <td>
                                    {{ $leave->end_date?->format('Y-m-d') }}
                                </td>

                                <td>
                                    {{ $days }}
                                </td>

                                <td>
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
                                </td>

                                <td>
                                    {{ $leave->approvedBy->name ?? '—' }}
                                </td>

                                <td class="text-center">

                                    <div class="d-flex justify-content-center gap-1">

                                        {{-- View --}}
                                        @can('leaves.view')
                                            <a href="{{ route('admin.leaves.show', $leave) }}"
                                               class="btn btn-sm btn-info"
                                               title="View">
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                        @endcan

                                        {{-- Edit --}}
                                        @can('leaves.update')
                                            @if($leave->status === 'pending')
                                                <a href="{{ route('admin.leaves.edit', $leave) }}"
                                                   class="btn btn-sm btn-warning"
                                                   title="Edit">
                                                    <i class="mdi mdi-pencil"></i>
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
                                                            class="btn btn-sm btn-success"
                                                            title="Approve"
                                                            onclick="return confirm('Approve this leave request?');">
                                                        <i class="mdi mdi-check"></i>
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
                                                            class="btn btn-sm btn-danger"
                                                            title="Reject"
                                                            onclick="return confirm('Reject this leave request?');">
                                                        <i class="mdi mdi-close"></i>
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
                                                            class="btn btn-sm btn-secondary"
                                                            title="Cancel"
                                                            onclick="return confirm('Cancel this leave request?');">
                                                        <i class="mdi mdi-cancel"></i>
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
                                                        class="btn btn-sm btn-danger"
                                                        title="Delete"
                                                        onclick="return confirm('Delete this leave request?');">
                                                    <i class="mdi mdi-delete"></i>
                                                </button>
                                            </form>
                                        @endcan

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-3">
                    {{ $leaves->links() }}
                </div>

            @else

                <div class="text-center py-5">

                    <div class="mb-3">
                        <i class="mdi mdi-calendar-remove-outline"
                           style="font-size: 48px;"></i>
                    </div>

                    <h5>No Leave Requests Found</h5>

                    <p class="text-muted mb-3">
                        There are no leave requests matching the selected filters.
                    </p>

                    @can('leaves.create')
                        <a href="{{ route('admin.leaves.create') }}"
                           class="btn btn-primary">
                            <i class="mdi mdi-plus me-1"></i>
                            Create Leave Request
                        </a>
                    @endcan

                </div>

            @endif

        </div>
    </div>

</div>
@endsection