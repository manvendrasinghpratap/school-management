@extends('backend.layout.default')

@section('content')
<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Graduations</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Graduations</li>
                    </ol>
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

    {{-- Statistics --}}
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-primary-subtle text-primary rounded">
                                <i class="mdi mdi-school fs-4"></i>
                            </span>
                        </div>

                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1">Total Graduations</p>
                            <h4 class="mb-0">{{ $graduations->total() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-warning-subtle text-warning rounded">
                                <i class="mdi mdi-clock-outline fs-4"></i>
                            </span>
                        </div>

                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1">Pending</p>
                            <h4 class="mb-0">
                                {{ $graduations->where('status', 'pending')->count() }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-info-subtle text-info rounded">
                                <i class="mdi mdi-check-circle-outline fs-4"></i>
                            </span>
                        </div>

                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1">Approved</p>
                            <h4 class="mb-0">
                                {{ $graduations->where('status', 'approved')->count() }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-success-subtle text-success rounded">
                                <i class="mdi mdi-school-outline fs-4"></i>
                            </span>
                        </div>

                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1">Completed</p>
                            <h4 class="mb-0">
                                {{ $graduations->where('status', 'completed')->count() }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Card --}}
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="card-title mb-0">Graduation Records</h5>
                </div>

                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                    @can('graduation.manage')
                        <a href="{{ route('admin.graduations.create') }}"
                           class="btn btn-primary">
                            <i class="mdi mdi-plus me-1"></i>
                            Add Graduation
                        </a>
                    @endcan
                </div>
            </div>
        </div>

        <div class="card-body">

            {{-- Filters --}}
            <form method="GET" action="{{ route('admin.graduations.index') }}" class="mb-4">
                <div class="row g-3">

                    <div class="col-md-5">
                        <label class="form-label">Search Student</label>
                        <input type="text"
                               name="search"
                               class="form-control"
                               value="{{ request('search') }}"
                               placeholder="Name, student number or admission number">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Academic Year</label>
                        <select name="academic_year_id" class="form-select">
                            <option value="">All Academic Years</option>

                            @foreach($academicYears as $academicYear)
                                <option value="{{ $academicYear->id }}"
                                    {{ request('academic_year_id') == $academicYear->id ? 'selected' : '' }}>
                                    {{ $academicYear->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>
                                Pending
                            </option>
                            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>
                                Approved
                            </option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>
                                Completed
                            </option>
                        </select>
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        <div class="d-flex gap-2 w-100">
                            <button type="submit" class="btn btn-primary flex-grow-1">
                                <i class="mdi mdi-filter-outline me-1"></i>
                                Filter
                            </button>

                            <a href="{{ route('admin.graduations.index') }}"
                               class="btn btn-light"
                               title="Reset">
                                <i class="mdi mdi-refresh"></i>
                            </a>
                        </div>
                    </div>

                </div>
            </form>

            {{-- Graduation Table --}}
            <div class="table-responsive">
                <table class="table align-middle table-nowrap mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Student</th>
                            <th>Student Number</th>
                            <th>Academic Year</th>
                            <th>Graduation Date</th>
                            <th>Qualification</th>
                            <th>Status</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($graduations as $graduation)

                            <tr>
                                <td>
                                    {{ $loop->iteration + (($graduations->currentPage() - 1) * $graduations->perPage()) }}
                                </td>

                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-xs me-2">
                                            <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                                {{ strtoupper(substr($graduation->student->first_name ?? 'S', 0, 1)) }}
                                            </span>
                                        </div>

                                        <div>
                                            <h6 class="mb-0">
                                                {{ trim(($graduation->student->first_name ?? '') . ' ' .
                                                        ($graduation->student->middle_name ?? '') . ' ' .
                                                        ($graduation->student->last_name ?? '')) }}
                                            </h6>

                                            @if($graduation->student)
                                                <small class="text-muted">
                                                    {{ $graduation->student->admission_number ?? '—' }}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    {{ $graduation->student->student_number ?? '—' }}
                                </td>

                                <td>
                                    {{ $graduation->academicYear->name ?? '—' }}
                                </td>

                                <td>
                                    {{ $graduation->graduation_date
                                        ? $graduation->graduation_date->format('d M Y')
                                        : '—' }}
                                </td>

                                <td>
                                    {{ $graduation->qualification ?? '—' }}
                                </td>

                                <td>
                                    @if($graduation->status === 'pending')
                                        <span class="badge bg-warning-subtle text-warning">
                                            Pending
                                        </span>
                                    @elseif($graduation->status === 'approved')
                                        <span class="badge bg-info-subtle text-info">
                                            Approved
                                        </span>
                                    @elseif($graduation->status === 'completed')
                                        <span class="badge bg-success-subtle text-success">
                                            Completed
                                        </span>
                                    @endif
                                </td>

                                <td class="text-end">
                                    <a href="{{ route('admin.graduations.show', $graduation) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="mdi mdi-eye-outline me-1"></i>
                                        View
                                    </a>
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="avatar-lg mx-auto mb-3">
                                        <span class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                            <i class="mdi mdi-school-outline fs-1"></i>
                                        </span>
                                    </div>

                                    <h5>No Graduation Records Found</h5>

                                    <p class="text-muted mb-3">
                                        There are currently no graduation records for this school.
                                    </p>

                                    @can('graduation.manage')
                                        <a href="{{ route('admin.graduations.create') }}"
                                           class="btn btn-primary">
                                            <i class="mdi mdi-plus me-1"></i>
                                            Add First Graduation
                                        </a>
                                    @endcan
                                </td>
                            </tr>

                        @endforelse

                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($graduations->hasPages())
                <div class="mt-4">
                    {{ $graduations->links() }}
                </div>
            @endif

        </div>
    </div>

</div>
@endsection