@extends('backend.layout.default')

@section('title', 'Instructors')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <div>
                    <h4 class="mb-sm-0">Instructors</h4>
                    <p class="text-muted mb-0">
                        Manage teaching staff and instructor profiles.
                    </p>
                </div>

                <div class="page-title-right">
                    <a href="{{ route('admin.instructors.create') }}"
                       class="btn btn-primary">
                        <i class="bx bx-plus me-1"></i>
                        Add Instructor
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bx bx-check-circle me-1"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bx bx-error-circle me-1"></i>
            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Filters --}}
    <div class="card">
        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.instructors.index') }}">

                <div class="row g-3 align-items-end">

                    <div class="col-md-5">
                        <label class="form-label">
                            Search Instructor
                        </label>

                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bx bx-search"></i>
                            </span>

                            <input type="text"
                                   name="search"
                                   class="form-control"
                                   value="{{ request('search') }}"
                                   placeholder="Name, staff number or phone">
                        </div>
                    </div>

                    <div class="col-md-5">
                        <label class="form-label">
                            Specialization
                        </label>

                        <input type="text"
                               name="specialization"
                               class="form-control"
                               value="{{ request('specialization') }}"
                               placeholder="e.g. Mathematics, Physics">
                    </div>

                    <div class="col-md-2 d-grid">
                        <button type="submit"
                                class="btn btn-outline-primary">
                            <i class="bx bx-filter-alt me-1"></i>
                            Filter
                        </button>
                    </div>

                </div>

            </form>

        </div>
    </div>

    {{-- Instructor List --}}
    <div class="card">

        <div class="card-header">
            <div class="d-flex align-items-center justify-content-between">

                <div>
                    <h5 class="card-title mb-1">
                        Instructor Profiles
                    </h5>

                    <p class="text-muted mb-0">
                        Teaching staff registered as instructors.
                    </p>
                </div>

                <span class="badge bg-primary-subtle text-primary">
                    {{ $instructors->total() }} Total
                </span>

            </div>
        </div>

        <div class="card-body">

            @if($instructors->count())

                <div class="table-responsive">

                    <table class="table align-middle table-hover">

                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Instructor</th>
                                <th>Staff Number</th>
                                <th>Department</th>
                                <th>Specialization</th>
                                <th>Qualification</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($instructors as $instructor)

                                @php
                                    $staff = $instructor->staff;
                                @endphp

                                <tr>

                                    <td>
                                        {{ $instructors->firstItem() + $loop->index }}
                                    </td>

                                    <td>
                                        <div class="d-flex align-items-center">

                                            @if($staff?->photo)
                                                <img src="{{ asset('storage/' . $staff->photo) }}"
                                                     alt="{{ $staff->full_name }}"
                                                     class="rounded-circle avatar-sm me-2">
                                            @else
                                                <div class="avatar-sm me-2">
                                                    <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                                        {{ strtoupper(substr($staff?->first_name ?? 'I', 0, 1)) }}
                                                    </span>
                                                </div>
                                            @endif

                                            <div>
                                                <h6 class="mb-0">
                                                    {{ $staff?->full_name ?? 'N/A' }}
                                                </h6>

                                                @if($staff?->phone)
                                                    <small class="text-muted">
                                                        {{ $staff->phone }}
                                                    </small>
                                                @endif
                                            </div>

                                        </div>
                                    </td>

                                    <td>
                                        <span class="fw-medium">
                                            {{ $staff?->staff_number ?? 'N/A' }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ $staff?->department?->name ?? '—' }}
                                    </td>

                                    <td>
                                        @if($instructor->specialization)
                                            <span class="badge bg-info-subtle text-info">
                                                {{ $instructor->specialization }}
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>

                                    <td>
                                        {{ $instructor->qualification ?: '—' }}
                                    </td>

                                   <td class="text-end">
    <div class="d-flex justify-content-end gap-2">

        {{-- View --}}
        <a href="{{ route('admin.instructors.show', $instructor) }}"
           class="btn btn-sm btn-info">
            <i class="bx bx-show me-1"></i> View
        </a>

        {{-- Edit --}}
        <a href="{{ route('admin.instructors.edit', $instructor) }}"
           class="btn btn-sm btn-primary">
            <i class="bx bx-edit-alt me-1"></i> Edit
        </a>

        {{-- Delete --}}
        <form method="POST"
              action="{{ route('admin.instructors.destroy', $instructor) }}"
              class="d-inline"
              onsubmit="return confirm('Remove this instructor profile? The staff member will not be deleted.');">
            @csrf
            @method('DELETE')

            <button type="submit" class="btn btn-sm btn-danger">
                <i class="bx bx-trash me-1"></i> Delete
            </button>
        </form>

    </div>
</td>
                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

                {{-- Pagination --}}
                @if($instructors->hasPages())
                    <div class="mt-3">
                        {{ $instructors->links() }}
                    </div>
                @endif

            @else

                <div class="text-center py-5">

                    <div class="avatar-lg mx-auto mb-4">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-1">
                            <i class="bx bx-chalkboard"></i>
                        </div>
                    </div>

                    <h5>No instructor profiles found</h5>

                    <p class="text-muted mb-4">
                        No instructor profiles match your current search.
                    </p>

                    <a href="{{ route('admin.instructors.create') }}"
                       class="btn btn-primary">
                        <i class="bx bx-plus me-1"></i>
                        Add First Instructor
                    </a>

                </div>

            @endif

        </div>
    </div>

</div>

@endsection