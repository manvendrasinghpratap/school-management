@extends('backend.layout.default')

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-12">

            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Student Enrollments</h4>

                @can('enrollments.create')
                    <a href="{{ route('admin.student-enrollments.create') }}"
                       class="btn btn-primary">
                        <i class="mdi mdi-plus me-1"></i>
                        Add Enrollment
                    </a>
                @endcan
            </div>

        </div>
    </div>

    {{-- Success --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Error --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
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

    <div class="card">
        <div class="card-body">

            {{-- Filters --}}
            <form method="GET"
                  action="{{ route('admin.student-enrollments.index') }}"
                  class="row g-3 mb-4">

                <div class="col-md-4">
                    <label class="form-label">Search</label>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           class="form-control"
                           placeholder="Enrollment number or student name">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Academic Year</label>
                    <select name="academic_year_id" class="form-select">
                        <option value="">All Years</option>

                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}"
                                {{ request('academic_year_id') == $year->id ? 'selected' : '' }}>
                                {{ $year->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Term</label>
                    <select name="term_id" class="form-select">
                        <option value="">All Terms</option>

                        @foreach($terms as $term)
                            <option value="{{ $term->id }}"
                                {{ request('term_id') == $term->id ? 'selected' : '' }}>
                                {{ $term->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>
                            Active
                        </option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>
                            Completed
                        </option>
                        <option value="transferred" {{ request('status') === 'transferred' ? 'selected' : '' }}>
                            Transferred
                        </option>
                        <option value="withdrawn" {{ request('status') === 'withdrawn' ? 'selected' : '' }}>
                            Withdrawn
                        </option>
                    </select>
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="mdi mdi-filter-outline me-1"></i>
                        Filter
                    </button>

                    <a href="{{ route('admin.student-enrollments.index') }}"
                       class="btn btn-light">
                        Reset
                    </a>
                </div>

            </form>

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Enrollment No.</th>
                            <th>Student</th>
                            <th>Academic Year</th>
                            <th>Term</th>
                            <th>Class</th>
                            <th>Section</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                    @forelse($enrollments as $enrollment)

                        <tr>

                            <td>
                                {{ $enrollments->firstItem() + $loop->index }}
                            </td>

                            <td>
                                <strong>
                                    {{ $enrollment->enrollment_number }}
                                </strong>
                            </td>

                            <td>
                                @if($enrollment->student)
                                    {{ $enrollment->student->first_name }}
                                    {{ $enrollment->student->middle_name }}
                                    {{ $enrollment->student->last_name }}
                                @else
                                    <span class="text-muted">
                                        Student unavailable
                                    </span>
                                @endif
                            </td>

                            <td>
                                {{ $enrollment->academicYear?->name ?? '—' }}
                            </td>

                            <td>
                                {{ $enrollment->term?->name ?? '—' }}
                            </td>

                            <td>
                                {{ $enrollment->class?->name ?? '—' }}
                            </td>

                            <td>
                                {{ $enrollment->section?->name ?? '—' }}
                            </td>

                            <td>
                                {{ $enrollment->enrollment_date?->format('Y-m-d') ?? '—' }}
                            </td>

                            <td>
                                @php
                                    $statusClass = match($enrollment->status) {
                                        'active' => 'bg-success',
                                        'completed' => 'bg-primary',
                                        'transferred' => 'bg-warning text-dark',
                                        'withdrawn' => 'bg-danger',
                                        default => 'bg-secondary',
                                    };
                                @endphp

                                <span class="badge {{ $statusClass }}">
                                    {{ ucfirst($enrollment->status) }}
                                </span>
                            </td>

                            <td>

                                <div class="d-flex gap-1">

                                    @can('enrollments.view')
                                        <a href="{{ route('admin.student-enrollments.show', $enrollment) }}"
                                           class="btn btn-sm btn-soft-info"
                                           title="View">
                                            <i class="mdi mdi-eye"></i>
                                        </a>
                                    @endcan

                                    @can('enrollments.update')
                                        <a href="{{ route('admin.student-enrollments.edit', $enrollment) }}"
                                           class="btn btn-sm btn-soft-primary"
                                           title="Edit">
                                            <i class="mdi mdi-pencil"></i>
                                        </a>
                                    @endcan

                                    @can('enrollments.delete')
                                        <form method="POST"
                                              action="{{ route('admin.student-enrollments.destroy', $enrollment) }}"
                                              onsubmit="return confirm('Delete this enrollment?');">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-soft-danger"
                                                    title="Delete">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </form>
                                    @endcan

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="10" class="text-center py-5">

                                <div class="mb-3">
                                    <i class="mdi mdi-school-outline"
                                       style="font-size:48px;"></i>
                                </div>

                                <h5>No enrollments found</h5>

                                <p class="text-muted">
                                    There are no student enrollments matching your criteria.
                                </p>

                                @can('enrollments.create')
                                    <a href="{{ route('admin.student-enrollments.create') }}"
                                       class="btn btn-primary">
                                        <i class="mdi mdi-plus me-1"></i>
                                        Add First Enrollment
                                    </a>
                                @endcan

                            </td>
                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

            @if($enrollments->hasPages())
                <div class="mt-3">
                    {{ $enrollments->withQueryString()->links() }}
                </div>
            @endif

        </div>
    </div>

</div>
@endsection