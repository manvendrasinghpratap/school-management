@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Sections / Streams</h4>

                <div class="page-title-right">
                    <a href="{{ route('admin.sections.create') }}"
                       class="btn btn-primary">
                        <i class="bx bx-plus me-1"></i>
                        Add Section
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
                    aria-label="Close">
            </button>
        </div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Filters --}}
    <div class="card">
        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.sections.index') }}">

                <div class="row g-3">

                    {{-- Search --}}
                    <div class="col-md-4">
                        <label class="form-label">
                            Search
                        </label>

                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               class="form-control"
                               placeholder="Section name or code">
                    </div>

                    {{-- Class --}}
                    <div class="col-md-3">
                        <label class="form-label">
                            Class
                        </label>

                        <select name="class_id"
                                class="form-select">

                            <option value="">
                                All Classes
                            </option>

                            @foreach($classes as $class)
                                <option value="{{ $class->id }}"
                                    @selected(request('class_id') == $class->id)>
                                    {{ $class->name }}
                                    @if($class->code)
                                        ({{ $class->code }})
                                    @endif
                                </option>
                            @endforeach

                        </select>
                    </div>

                    {{-- Status --}}
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
                                @selected(request('status') === 'active')>
                                Active
                            </option>

                            <option value="inactive"
                                @selected(request('status') === 'inactive')>
                                Inactive
                            </option>

                        </select>
                    </div>

                    {{-- Buttons --}}
                    <div class="col-md-2 d-flex align-items-end gap-2">

                        <button type="submit"
                                class="btn btn-primary">
                            <i class="bx bx-search me-1"></i>
                            Filter
                        </button>

                        <a href="{{ route('admin.sections.index') }}"
                           class="btn btn-light">
                            Reset
                        </a>

                    </div>

                </div>

            </form>

        </div>
    </div>

    {{-- Sections Table --}}
    <div class="card">

        <div class="card-header">
            <h5 class="card-title mb-0">
                All Sections
            </h5>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            <th width="60">#</th>
                            <th>Section</th>
                            <th>Code</th>
                            <th>Class</th>
                            <th>Capacity</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($sections as $section)

                            <tr>

                                <td>
                                    {{ $sections->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    <strong>
                                        {{ $section->name }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $section->code ?: '—' }}
                                </td>

                                <td>
                                    @if($section->class)
                                        <strong>
                                            {{ $section->class->name }}
                                        </strong>

                                        @if($section->class->code)
                                            <div class="text-muted small">
                                                {{ $section->class->code }}
                                            </div>
                                        @endif
                                    @else
                                        <span class="text-muted">
                                            —
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    {{ $section->capacity ?? 'Unlimited' }}
                                </td>

                                <td>

                                    @if($section->is_active)
                                        <span class="badge bg-success-subtle text-success">
                                            Active
                                        </span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger">
                                            Inactive
                                        </span>
                                    @endif

                                </td>

                                <td>

                                    <div class="d-flex justify-content-end gap-2">

                                        <a href="{{ route('admin.sections.show', $section) }}"
                                           class="btn btn-sm btn-info">
                                            <i class="bx bx-show me-1"></i>
                                            View
                                        </a>

                                        <a href="{{ route('admin.sections.edit', $section) }}"
                                           class="btn btn-sm btn-primary">
                                            <i class="bx bx-edit me-1"></i>
                                            Edit
                                        </a>

                                        <form method="POST"
                                              action="{{ route('admin.sections.destroy', $section) }}"
                                              onsubmit="return confirm('Are you sure you want to delete this section?');">

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
                                    class="text-center py-4">

                                    <div class="text-muted">
                                        <i class="bx bx-layer fs-1 d-block mb-2"></i>

                                        No sections found.
                                    </div>

                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- Pagination --}}
            @if($sections->hasPages())
                <div class="mt-3">
                    {{ $sections->links() }}
                </div>
            @endif

        </div>

    </div>

</div>

@endsection