@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Staff Management</h4>

                <div class="page-title-right">
                    <a href="{{ route('admin.staff.create') }}" class="btn btn-primary">
                        <i class="mdi mdi-plus me-1"></i>
                        Add Staff
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

    {{-- Filters --}}
    <div class="card">
        <div class="card-body">

            <form method="GET" action="{{ route('admin.staff.index') }}">

                <div class="row g-3">

                    {{-- Search --}}
                    <div class="col-md-4">
                        <label class="form-label">Search</label>
                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            value="{{ request('search') }}"
                            placeholder="Staff number, name, phone..."
                        >
                    </div>

                    {{-- Department --}}
                    <div class="col-md-3">
                        <label class="form-label">Department</label>
                        <select name="department_id" class="form-select">
                            <option value="">All Departments</option>

                            @foreach($departments as $department)
                                <option
                                    value="{{ $department->id }}"
                                    @selected(request('department_id') == $department->id)
                                >
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Staff Type --}}
                    <div class="col-md-2">
                        <label class="form-label">Staff Type</label>
                        <input
                            type="text"
                            name="staff_type"
                            class="form-control"
                            value="{{ request('staff_type') }}"
                            placeholder="Teacher"
                        >
                    </div>

                    {{-- Status --}}
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="active" @selected(request('status') === 'active')>
                                Active
                            </option>
                            <option value="inactive" @selected(request('status') === 'inactive')>
                                Inactive
                            </option>
                            <option value="terminated" @selected(request('status') === 'terminated')>
                                Terminated
                            </option>
                        </select>
                    </div>

                    {{-- Filter Button --}}
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="mdi mdi-filter-outline"></i>
                        </button>
                    </div>

                </div>

            </form>

        </div>
    </div>

    {{-- Staff Table --}}
    <div class="card">
        <div class="card-body">

            <div class="d-flex align-items-center justify-content-between mb-3">
                <h4 class="card-title mb-0">Staff Members</h4>

                <span class="text-muted">
                    {{ $staff->total() }} staff member{{ $staff->total() === 1 ? '' : 's' }}
                </span>
            </div>

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            <th style="width: 60px;">#</th>
                            <th>Staff</th>
                            <th>Staff Number</th>
                            <th>Department</th>
                            <th>Staff Type</th>
                            <th>Instructor</th>
                            <th>Status</th>
                            <th style="width: 150px;">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($staff as $member)

                            <tr>

                                {{-- Number --}}
                                <td>
                                    {{ $staff->firstItem() + $loop->index }}
                                </td>

                                {{-- Staff --}}
                                <td>
                                    <div class="d-flex align-items-center">

                                        @if($member->photo)
                                            <img
                                                src="{{ asset('storage/' . $member->photo) }}"
                                                alt="{{ $member->full_name }}"
                                                class="rounded-circle me-2"
                                                width="40"
                                                height="40"
                                                style="object-fit: cover;"
                                            >
                                        @else
                                            <div
                                                class="avatar-sm me-2"
                                            >
                                                <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                                    {{ strtoupper(substr($member->first_name, 0, 1)) }}
                                                </span>
                                            </div>
                                        @endif

                                        <div>
                                            <a
                                                href="{{ route('admin.staff.show', $member) }}"
                                                class="text-body fw-semibold"
                                            >
                                                {{ $member->full_name }}
                                            </a>

                                            @if($member->phone)
                                                <div class="text-muted small">
                                                    {{ $member->phone }}
                                                </div>
                                            @endif
                                        </div>

                                    </div>
                                </td>

                                {{-- Staff Number --}}
                                <td>
                                    <span class="fw-semibold">
                                        {{ $member->staff_number }}
                                    </span>
                                </td>

                                {{-- Department --}}
                                <td>
                                    {{ $member->department?->name ?? '—' }}
                                </td>

                                {{-- Staff Type --}}
                                <td>
                                    {{ $member->staff_type ?? '—' }}
                                </td>

                                {{-- Instructor --}}
                                <td>
                                    @if($member->instructor)
                                        <span class="badge bg-success">
                                            Instructor
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            Staff Only
                                        </span>
                                    @endif
                                </td>

                                {{-- Status --}}
                                <td>
                                    @if($member->status === 'active')
                                        <span class="badge bg-success">
                                            Active
                                        </span>
                                    @elseif($member->status === 'inactive')
                                        <span class="badge bg-warning text-dark">
                                            Inactive
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            Terminated
                                        </span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td>

                                    <div class="d-flex gap-1">

                                        <a
                                            href="{{ route('admin.staff.show', $member) }}"
                                            class="btn btn-sm btn-outline-primary"
                                            title="View"
                                        >
                                            <i class="mdi mdi-eye"></i>
                                        </a>

                                        <a
                                            href="{{ route('admin.staff.edit', $member) }}"
                                            class="btn btn-sm btn-outline-secondary"
                                            title="Edit"
                                        >
                                            <i class="mdi mdi-pencil"></i>
                                        </a>

                                        <form
                                            method="POST"
                                            action="{{ route('admin.staff.destroy', $member) }}"
                                            onsubmit="return confirm('Are you sure you want to delete this staff member?');"
                                            class="d-inline"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                                title="Delete"
                                            >
                                                <i class="mdi mdi-delete"></i>
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="8" class="text-center py-5">

                                    <div class="text-muted">

                                        <i class="mdi mdi-account-group-outline display-4"></i>

                                        <h5 class="mt-3">
                                            No staff members found
                                        </h5>

                                        <p class="mb-3">
                                            Start by adding your first staff member.
                                        </p>

                                        <a
                                            href="{{ route('admin.staff.create') }}"
                                            class="btn btn-primary"
                                        >
                                            <i class="mdi mdi-plus me-1"></i>
                                            Add Staff
                                        </a>

                                    </div>

                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- Pagination --}}
            @if($staff->hasPages())
                <div class="mt-4">
                    {{ $staff->links() }}
                </div>
            @endif

        </div>
    </div>

</div>

@endsection