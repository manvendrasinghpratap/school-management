@extends('backend.layout.default')

@section('title', 'Classes')

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-12">

            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Classes</h4>

                <div class="page-title-right">
                    <a href="{{ route('admin.classes.create') }}"
                       class="btn btn-primary">
                        <i class="bx bx-plus me-1"></i>
                        Add Class
                    </a>
                </div>
            </div>

        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    @endif

    <div class="card">

        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.classes.index') }}"
                  class="row g-3 mb-4">

                <div class="col-md-4">
                    <label class="form-label">Search</label>

                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           class="form-control"
                           placeholder="Search class name or code">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Department</label>

                    <select name="department_id"
                            class="form-select">

                        <option value="">All Departments</option>

                        @foreach($departments as $department)
                            <option value="{{ $department->id }}"
                                @selected(request('department_id') == $department->id)>
                                {{ $department->name }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Level</label>

                    <select name="level_id"
                            class="form-select">

                        <option value="">All Levels</option>

                        @foreach($levels as $level)
                            <option value="{{ $level->id }}"
                                @selected(request('level_id') == $level->id)>
                                {{ $level->name }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Status</label>

                    <select name="status"
                            class="form-select">

                        <option value="">All</option>

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

                <div class="col-12">
                    <button type="submit"
                            class="btn btn-primary">
                        <i class="bx bx-search me-1"></i>
                        Filter
                    </button>

                    <a href="{{ route('admin.classes.index') }}"
                       class="btn btn-light">
                        Reset
                    </a>
                </div>

            </form>

            <div class="table-responsive">

                <table class="table align-middle table-nowrap mb-0">

                    <thead class="table-light">

                        <tr>
                            <th>#</th>
                            <th>Class</th>
                            <th>Code</th>
                            <th>Department</th>
                            <th>Level</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($classes as $class)

                            <tr>

                                <td>
                                    {{ $classes->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    <a href="{{ route('admin.classes.show', $class) }}"
                                       class="fw-semibold text-primary">
                                        {{ $class->name }}
                                    </a>
                                </td>

                                <td>
                                    {{ $class->code ?: '—' }}
                                </td>

                                <td>
                                    {{ $class->department?->name ?: '—' }}
                                </td>

                                <td>
                                    {{ $class->level?->name ?: '—' }}
                                </td>

                                <td>
                                    @if($class->is_active)
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

                                    <div class="d-flex justify-content-end gap-2">

    <a href="{{ route('admin.classes.show', $class) }}"
       class="btn btn-sm btn-info">
        <i class="bx bx-show me-1"></i>
        View
    </a>

    <a href="{{ route('admin.classes.edit', $class) }}"
       class="btn btn-sm btn-primary">
        <i class="bx bx-edit me-1"></i>
        Edit
    </a>

    <form method="POST"
          action="{{ route('admin.classes.destroy', $class) }}"
          onsubmit="return confirm('Are you sure you want to delete this class?');">
        @csrf
        @method('DELETE')

        <button type="submit"
                class="btn btn-sm btn-danger">
            <i class="bx bx-trash me-1"></i>
            Delete
        </button>
    </form>

</div>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7"
                                    class="text-center py-5">

                                    <div class="text-muted">
                                        <i class="bx bx-book-open font-size-24 d-block mb-2"></i>

                                        No classes found.
                                    </div>

                                    <a href="{{ route('admin.classes.create') }}"
                                       class="btn btn-primary mt-3">
                                        Add Your First Class
                                    </a>

                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            @if($classes->hasPages())
                <div class="mt-4">
                    {{ $classes->links() }}
                </div>
            @endif

        </div>

    </div>

</div>

@endsection