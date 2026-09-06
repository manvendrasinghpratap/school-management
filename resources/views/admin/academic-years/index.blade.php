@extends('backend.layout.default')

@section('title', 'Academic Years')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Academic Years</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ url('/admin/dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">
                            Academic Years
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="mdi mdi-check-circle-outline me-2"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close">
            </button>
        </div>
    @endif

    {{-- Validation / Error Messages --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Please check the following:</strong>

            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close">
            </button>
        </div>
    @endif

    {{-- Statistics --}}
    <div class="row">

        <div class="col-xl-4 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">

                        <div class="avatar-sm">
                            <span class="avatar-title rounded-circle bg-primary-subtle text-primary font-size-20">
                                <i class="bx bx-calendar"></i>
                            </span>
                        </div>

                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1">
                                Total Academic Years
                            </p>

                            <h4 class="mb-0">
                                {{ $academicYears->total() }}
                            </h4>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">

                        <div class="avatar-sm">
                            <span class="avatar-title rounded-circle bg-success-subtle text-success font-size-20">
                                <i class="bx bx-check-circle"></i>
                            </span>
                        </div>

                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1">
                                Current Academic Year
                            </p>

                            <h4 class="mb-0">
                                {{ $academicYears->getCollection()->where('is_current', true)->count() }}
                            </h4>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">

                        <div class="avatar-sm">
                            <span class="avatar-title rounded-circle bg-info-subtle text-info font-size-20">
                                <i class="bx bx-list-ul"></i>
                            </span>
                        </div>

                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1">
                                Terms Configured
                            </p>

                            <h4 class="mb-0">
                                {{ $academicYears->sum('terms_count') }}
                            </h4>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Academic Years --}}
    <div class="card">

        <div class="card-body">

            {{-- Toolbar --}}
            <div class="row mb-3">

                <div class="col-md-6">
                    <h4 class="card-title mb-1">
                        Academic Year List
                    </h4>

                    <p class="text-muted mb-0">
                        Manage academic years for your school.
                    </p>
                </div>

                <div class="col-md-6 text-md-end mt-3 mt-md-0">

                    <a href="{{ route('admin.academic-years.create') }}"
                       class="btn btn-primary">
                        <i class="bx bx-plus me-1"></i>
                        Add Academic Year
                    </a>

                </div>

            </div>

            {{-- Search & Filters --}}
            <form method="GET"
                  action="{{ route('admin.academic-years.index') }}"
                  class="row g-2 mb-4">

                <div class="col-md-5">
                    <label class="form-label">
                        Search
                    </label>

                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bx bx-search"></i>
                        </span>

                        <input type="text"
                               name="search"
                               class="form-control"
                               placeholder="Search academic year..."
                               value="{{ request('search') }}">
                    </div>
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

                        <option value="active"
                            {{ request('status') === 'active' ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="inactive"
                            {{ request('status') === 'inactive' ? 'selected' : '' }}>
                            Inactive
                        </option>

                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">
                        Current
                    </label>

                    <select name="current"
                            class="form-select">

                        <option value="">
                            All
                        </option>

                        <option value="yes"
                            {{ request('current') === 'yes' ? 'selected' : '' }}>
                            Current
                        </option>

                        <option value="no"
                            {{ request('current') === 'no' ? 'selected' : '' }}>
                            Not Current
                        </option>

                    </select>
                </div>

                <div class="col-md-2 d-flex align-items-end">

                    <div class="d-flex gap-2 w-100">

                        <button type="submit"
                                class="btn btn-primary flex-grow-1">
                            <i class="bx bx-filter-alt me-1"></i>
                            Filter
                        </button>

                        <a href="{{ route('admin.academic-years.index') }}"
                           class="btn btn-light"
                           title="Reset filters">
                            <i class="bx bx-reset"></i>
                        </a>

                    </div>

                </div>

            </form>

            {{-- Table --}}
            <div class="table-responsive">

                <table class="table align-middle table-nowrap mb-0">

                    <thead class="table-light">

                        <tr>
                            <th>#</th>
                            <th>Academic Year</th>
                            <th>Period</th>
                            <th>Terms</th>
                            <th>Current</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($academicYears as $academicYear)

                            <tr>

                                <td>
                                    {{ $academicYears->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    <div class="d-flex align-items-center">

                                        <div class="avatar-xs me-2">
                                            <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                                <i class="bx bx-calendar"></i>
                                            </span>
                                        </div>

                                        <div>
                                            <h5 class="font-size-14 mb-0">
                                                <a href="{{ route('admin.academic-years.show', $academicYear) }}"
                                                   class="text-dark">
                                                    {{ $academicYear->name }}
                                                </a>
                                            </h5>

                                            @if($academicYear->is_current)
                                                <small class="text-success">
                                                    <i class="bx bx-check-circle"></i>
                                                    Current academic year
                                                </small>
                                            @endif
                                        </div>

                                    </div>
                                </td>

                                <td>
                                    <div>
                                        <i class="bx bx-calendar-event text-muted me-1"></i>
                                        {{ $academicYear->start_date?->format('d M Y') }}
                                    </div>

                                    <div class="text-muted font-size-12">
                                        to
                                        {{ $academicYear->end_date?->format('d M Y') }}
                                    </div>
                                </td>

                                <td>
                                    <span class="badge bg-info-subtle text-info">
                                        {{ $academicYear->terms_count }}
                                        {{ $academicYear->terms_count == 1 ? 'Term' : 'Terms' }}
                                    </span>
                                </td>

                                <td>

                                    @if($academicYear->is_current)

                                        <span class="badge bg-success">
                                            <i class="bx bx-check me-1"></i>
                                            Current
                                        </span>

                                    @else

                                        <span class="badge bg-light text-muted">
                                            Not Current
                                        </span>

                                    @endif

                                </td>

                                <td>

                                    @if($academicYear->is_active)

                                        <span class="badge bg-success-subtle text-success">
                                            Active
                                        </span>

                                    @else

                                        <span class="badge bg-danger-subtle text-danger">
                                            Inactive
                                        </span>

                                    @endif

                                </td>
                                <td class="text-end">

                                    <div class="d-flex justify-content-end gap-1">

    {{-- View --}}
    <a href="{{ route('admin.academic-years.show', $academicYear) }}"
       class="btn btn-sm btn-info"
       title="View Academic Year">

        <i class="bx bx-show-alt"></i>

    </a>

    {{-- Edit --}}
    <a href="{{ route('admin.academic-years.edit', $academicYear) }}"
       class="btn btn-sm btn-primary"
       title="Edit Academic Year">

        <i class="bx bx-edit-alt"></i>

    </a>

    {{-- Delete --}}
    <form method="POST"
          action="{{ route('admin.academic-years.destroy', $academicYear) }}"
          class="d-inline">

        @csrf
        @method('DELETE')

        <button type="submit"
                class="btn btn-sm btn-danger"
                title="Delete Academic Year"
                onclick="return confirm('Are you sure you want to delete {{ $academicYear->name }}?')">

            <i class="bx bx-trash"></i>

        </button>

    </form>

</div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7"
                                    class="text-center py-5">

                                    <div class="avatar-lg mx-auto mb-3">
                                        <span class="avatar-title rounded-circle bg-light text-primary font-size-30">
                                            <i class="bx bx-calendar"></i>
                                        </span>
                                    </div>

                                    <h5>
                                        No Academic Years Found
                                    </h5>

                                    <p class="text-muted mb-3">
                                        No academic years match your current search or filters.
                                    </p>

                                    <a href="{{ route('admin.academic-years.create') }}"
                                       class="btn btn-primary">

                                        <i class="bx bx-plus me-1"></i>
                                        Create Academic Year

                                    </a>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- Pagination --}}
            @if($academicYears->hasPages())

                <div class="mt-4">
                    {{ $academicYears->links() }}
                </div>

            @endif

        </div>

    </div>

</div>

@endsection