@extends('backend.layout.default')

@section('title', 'Student Promotions')

@section('content')
<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Student Promotions</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">
                            Student Promotions
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistics --}}
    <div class="row">

        <div class="col-md-3">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium mb-2">
                                Total Promotions
                            </p>
                            <h4 class="mb-0">
                                {{ $promotions->total() }}
                            </h4>
                        </div>

                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                                <span class="avatar-title">
                                    <i class="bx bx-transfer-alt font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @php
            $pendingCount = \App\Models\StudentPromotion::where('school_id', auth()->user()->school_id)
                ->where('status', 'pending')
                ->count();

            $approvedCount = \App\Models\StudentPromotion::where('school_id', auth()->user()->school_id)
                ->where('status', 'approved')
                ->count();

            $rejectedCount = \App\Models\StudentPromotion::where('school_id', auth()->user()->school_id)
                ->where('status', 'rejected')
                ->count();
        @endphp

        <div class="col-md-3">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium mb-2">
                                Pending
                            </p>
                            <h4 class="mb-0">
                                {{ $pendingCount }}
                            </h4>
                        </div>

                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-warning">
                                <span class="avatar-title">
                                    <i class="bx bx-time-five font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium mb-2">
                                Approved
                            </p>
                            <h4 class="mb-0">
                                {{ $approvedCount }}
                            </h4>
                        </div>

                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-success">
                                <span class="avatar-title">
                                    <i class="bx bx-check-circle font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium mb-2">
                                Rejected
                            </p>
                            <h4 class="mb-0">
                                {{ $rejectedCount }}
                            </h4>
                        </div>

                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-danger">
                                <span class="avatar-title">
                                    <i class="bx bx-x-circle font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="mdi mdi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="mdi mdi-alert-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
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

    {{-- Main Card --}}
    <div class="card">

        <div class="card-header">
            <div class="row align-items-center">

                <div class="col-md-6">
                    <h4 class="card-title mb-1">
                        Promotion History
                    </h4>

                    <p class="text-muted mb-0">
                        Manage student class promotions and approval status.
                    </p>
                </div>

                <div class="col-md-6 text-md-end mt-3 mt-md-0">

                    @can('promotions.create')
                        <a href="{{ route('admin.student-promotions.create') }}"
                           class="btn btn-primary">
                            <i class="bx bx-plus me-1"></i>
                            Create Promotion
                        </a>
                    @endcan

                </div>

            </div>
        </div>

        <div class="card-body">

            {{-- Filters --}}
            <form method="GET"
                  action="{{ route('admin.student-promotions.index') }}"
                  class="row g-3 mb-4">

                <div class="col-md-4">
                    <label class="form-label">
                        Search Student
                    </label>

                    <input type="text"
                           name="search"
                           class="form-control"
                           value="{{ request('search') }}"
                           placeholder="Name or student number">
                </div>

                <div class="col-md-3">
                    <label class="form-label">
                        Academic Year
                    </label>

                    <select name="academic_year_id"
                            class="form-select">

                        <option value="">
                            All Academic Years
                        </option>

                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}"
                                {{ request('academic_year_id') == $year->id ? 'selected' : '' }}>
                                {{ $year->name }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">
                        Status
                    </label>

                    <select name="status"
                            class="form-select">

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

                    </select>
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <div class="d-flex gap-2 w-100">

                        <button type="submit"
                                class="btn btn-primary flex-grow-1">
                            <i class="bx bx-search"></i>
                            Search
                        </button>

                        <a href="{{ route('admin.student-promotions.index') }}"
                           class="btn btn-light"
                           title="Reset">
                            <i class="bx bx-reset"></i>
                        </a>

                    </div>
                </div>

            </form>

            {{-- Table --}}
            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Student</th>
                        <th>Academic Year</th>
                        <th>From Class</th>
                        <th>To Class</th>
                        <th>Promotion Date</th>
                        <th>Status</th>
                        <th width="180">Actions</th>
                    </tr>
                    </thead>

                    <tbody>

                    @forelse($promotions as $promotion)

                        <tr>

                            <td>
                                {{ $promotions->firstItem() + $loop->index }}
                            </td>

                            <td>
                                <div class="d-flex align-items-center">

                                    @if($promotion->student?->photo)
                                        <img src="{{ asset('storage/' . $promotion->student->photo) }}"
                                             class="rounded-circle me-2"
                                             width="38"
                                             height="38"
                                             alt="Student">
                                    @else
                                        <div class="avatar-xs me-2">
                                            <span class="avatar-title rounded-circle bg-primary">
                                                {{ strtoupper(substr($promotion->student?->first_name ?? 'S', 0, 1)) }}
                                            </span>
                                        </div>
                                    @endif

                                    <div>
                                        <h6 class="mb-0">
                                            {{ $promotion->student?->first_name }}
                                            {{ $promotion->student?->middle_name }}
                                            {{ $promotion->student?->last_name }}
                                        </h6>

                                        <small class="text-muted">
                                            {{ $promotion->student?->student_number }}
                                        </small>
                                    </div>

                                </div>
                            </td>

                            <td>
                                {{ $promotion->academicYear?->name ?? '—' }}
                            </td>

                            <td>
                                {{ $promotion->fromClass?->name ?? '—' }}

                                @if($promotion->fromSection)
                                    <small class="text-muted">
                                        - {{ $promotion->fromSection->name }}
                                    </small>
                                @endif
                            </td>

                            <td>
                                {{ $promotion->toClass?->name ?? '—' }}

                                @if($promotion->toSection)
                                    <small class="text-muted">
                                        - {{ $promotion->toSection->name }}
                                    </small>
                                @endif
                            </td>

                            <td>
                                {{ $promotion->promotion_date?->format('Y-m-d') ?? '—' }}
                            </td>

                            <td>
                                @if($promotion->status === 'pending')
                                    <span class="badge bg-warning">
                                        Pending
                                    </span>
                                @elseif($promotion->status === 'approved')
                                    <span class="badge bg-success">
                                        Approved
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        Rejected
                                    </span>
                                @endif
                            </td>

                            <td>

                                <div class="d-flex gap-1">

                                    @can('promotions.view')
                                        <a href="{{ route('admin.student-promotions.show', $promotion) }}"
                                           class="btn btn-sm btn-outline-primary"
                                           title="View">
                                            <i class="bx bx-show"></i>
                                        </a>
                                    @endcan

                                    @if($promotion->status === 'pending')

                                        @can('promotions.update')
                                            <a href="{{ route('admin.student-promotions.edit', $promotion) }}"
                                               class="btn btn-sm btn-outline-warning"
                                               title="Edit">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                        @endcan

                                        @can('promotions.approve')
                                            <form method="POST"
                                                  action="{{ route('admin.student-promotions.approve', $promotion) }}"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Approve this promotion? This will complete the current enrollment and create the new enrollment.')">
                                                @csrf
                                                @method('PUT')

                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-success"
                                                        title="Approve">
                                                    <i class="bx bx-check"></i>
                                                </button>
                                            </form>
                                        @endcan

                                        @can('promotions.reject')
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Reject"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#rejectPromotionModal{{ $promotion->id }}">
                                                <i class="bx bx-x"></i>
                                            </button>
                                        @endcan

                                    @endif

                                    @if($promotion->status !== 'approved')

                                        @can('promotions.delete')
                                            <form method="POST"
                                                  action="{{ route('admin.student-promotions.destroy', $promotion) }}"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Delete this promotion record?')">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="Delete">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </form>
                                        @endcan

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="8"
                                class="text-center py-5">

                                <div class="mb-3">
                                    <i class="bx bx-transfer-alt font-size-48 text-muted"></i>
                                </div>

                                <h5>No Promotion Records Found</h5>

                                <p class="text-muted">
                                    There are no student promotion records matching your search.
                                </p>

                                @can('promotions.create')
                                    <a href="{{ route('admin.student-promotions.create') }}"
                                       class="btn btn-primary">
                                        <i class="bx bx-plus me-1"></i>
                                        Create First Promotion
                                    </a>
                                @endcan

                            </td>
                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

            {{-- Pagination --}}
            @if($promotions->hasPages())
                <div class="mt-3">
                    {{ $promotions->withQueryString()->links() }}
                </div>
            @endif

        </div>

    </div>

</div>


{{-- Rejection Modals --}}
@foreach($promotions as $promotion)

    @if($promotion->status === 'pending')

        @can('promotions.reject')

            <div class="modal fade"
                 id="rejectPromotionModal{{ $promotion->id }}"
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
                                        data-bs-dismiss="modal"></button>
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
                                        Reason / Remarks
                                    </label>

                                    <textarea name="remarks"
                                              class="form-control"
                                              rows="4"
                                              maxlength="5000"
                                              placeholder="Enter reason for rejection..."></textarea>

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

        @endcan

    @endif

@endforeach

@endsection