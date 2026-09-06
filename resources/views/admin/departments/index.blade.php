@extends('backend.layout.default')

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Departments</h4>

                <div class="page-title-right">
                    <a href="{{ route('admin.departments.create') }}" class="btn btn-primary">
                        <i class="bx bx-plus me-1"></i>
                        Add Department
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Validation / Error Messages --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">

        <div class="card-header">
            <h5 class="card-title mb-0">Department List</h5>
        </div>

        <div class="card-body">

            {{-- Filters --}}
            <form method="GET"
                  action="{{ route('admin.departments.index') }}"
                  class="row g-2 mb-4">

                <div class="col-md-5">
                    <label class="form-label">Search</label>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        class="form-control"
                        placeholder="Search by name, code or description">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Status</label>

                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>

                        <option value="active"
                            @selected(request('status') === 'active')>
                            Active
                        </option>

                        <option value="inactive"
                            @selected(request('status') === 'inactive')>
                            Inactive
                        </option>
                    </select>
                </div>

                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bx bx-search me-1"></i>
                        Search
                    </button>

                    <a href="{{ route('admin.departments.index') }}"
                       class="btn btn-light">
                        Reset
                    </a>
                </div>

            </form>

            {{-- Department Table --}}
            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th width="60">#</th>
                            <th>Department</th>
                            <th>Code</th>
                            <th>Description</th>
                            <th width="120">Classes</th>
                            <th width="120">Status</th>
                            <th width="190">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($departments as $department)

                            <tr>

                                <td>
                                    {{ $departments->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    <strong>{{ $department->name }}</strong>
                                </td>

                                <td>
                                    {{ $department->code ?? '—' }}
                                </td>

                                <td>
                                    {{ $department->description ?? '—' }}
                                </td>

                                <td>
                                    <span class="badge bg-info">
                                        {{ $department->classes_count }}
                                    </span>
                                </td>

                                <td>
                                    @if($department->is_active)
                                        <span class="badge bg-success">
                                            Active
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            Inactive
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <div class="d-flex gap-1">

                                        <a href="{{ route('admin.departments.show', $department) }}"
                                           class="btn btn-sm btn-info">
                                            <i class="bx bx-show"></i>
                                            View
                                        </a>

                                        <a href="{{ route('admin.departments.edit', $department) }}"
                                           class="btn btn-sm btn-warning">
                                            <i class="bx bx-edit"></i>
                                            Edit
                                        </a>

                                        <form method="POST"
                                              action="{{ route('admin.departments.destroy', $department) }}"
                                              onsubmit="return confirm('Are you sure you want to delete this department?');">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-danger">
                                                <i class="bx bx-trash"></i>
                                                Delete
                                            </button>

                                        </form>

                                    </div>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    No departments found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $departments->links() }}
            </div>

        </div>
    </div>

</div>
@endsection