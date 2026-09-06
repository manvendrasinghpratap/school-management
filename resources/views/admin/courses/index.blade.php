@extends('backend.layout.default')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Subjects / Courses</h4>
            <p class="text-muted mb-0">
                Manage subjects and courses offered by the school.
            </p>
        </div>

        <a href="{{ route('admin.courses.create') }}" class="btn btn-primary">
            <i class="mdi mdi-plus"></i>
            Add Subject / Course
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.courses.index') }}"
                  class="row g-3 mb-4">

                <div class="col-md-4">
                    <label class="form-label">Search</label>
                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        value="{{ request('search') }}"
                        placeholder="Code, name or description">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Department</label>
                    <select name="department_id" class="form-select">
                        <option value="">All Departments</option>

                        @foreach(\App\Models\Department::where('school_id', auth()->user()->school_id)->orderBy('name')->get() as $department)
                            <option
                                value="{{ $department->id }}"
                                @selected(request('department_id') == $department->id)
                            >
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="active" @selected(request('status') === 'active')>
                            Active
                        </option>
                        <option value="inactive" @selected(request('status') === 'inactive')>
                            Inactive
                        </option>
                    </select>
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <div class="d-flex gap-2 w-100">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="mdi mdi-magnify"></i>
                            Search
                        </button>

                        <a
                            href="{{ route('admin.courses.index') }}"
                            class="btn btn-light"
                            title="Clear filters"
                        >
                            <i class="mdi mdi-refresh"></i>
                        </a>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Code</th>
                            <th>Subject / Course</th>
                            <th>Department</th>
                            <th>Credits</th>
                            <th>Compulsory</th>
                            <th>Status</th>
                            <th>Students</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($courses as $course)
                            <tr>
                                <td>{{ $courses->firstItem() + $loop->index }}</td>

                                <td>
                                    <span class="fw-semibold">
                                        {{ $course->course_code }}
                                    </span>
                                </td>

                                <td>
                                    <a
                                        href="{{ route('admin.courses.show', $course) }}"
                                        class="fw-semibold"
                                    >
                                        {{ $course->name }}
                                    </a>

                                    @if($course->description)
                                        <div class="small text-muted">
                                            {{ \Illuminate\Support\Str::limit($course->description, 60) }}
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    {{ $course->department?->name ?? '—' }}
                                </td>

                                <td>
                                    {{ $course->credit_hours ?? '—' }}
                                </td>

                                <td>
                                    @if($course->is_compulsory)
                                        <span class="badge bg-success">
                                            Yes
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            No
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    @if($course->is_active)
                                        <span class="badge bg-success">
                                            Active
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            Inactive
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    {{ $course->students_count }}
                                </td>

                                <td class="text-end">
                                    <div class="btn-group">

                                        <a
                                            href="{{ route('admin.courses.show', $course) }}"
                                            class="btn btn-sm btn-outline-primary"
                                            title="View"
                                        >
                                            <i class="mdi mdi-eye"></i>
                                        </a>

                                        <a
                                            href="{{ route('admin.courses.edit', $course) }}"
                                            class="btn btn-sm btn-outline-warning"
                                            title="Edit"
                                        >
                                            <i class="mdi mdi-pencil"></i>
                                        </a>

                                        <form
                                            action="{{ route('admin.courses.destroy', $course) }}"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this subject/course?');"
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
                                <td colspan="9" class="text-center py-5">
                                    <div class="text-muted">
                                        No subjects/courses found.
                                    </div>

                                    <a
                                        href="{{ route('admin.courses.create') }}"
                                        class="btn btn-primary mt-3"
                                    >
                                        Add First Subject / Course
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($courses->hasPages())
                <div class="mt-3">
                    {{ $courses->links() }}
                </div>
            @endif

        </div>
    </div>

</div>
@endsection