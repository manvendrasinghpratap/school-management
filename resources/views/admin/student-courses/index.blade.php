@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Student Course Registration</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript:void(0);">Academic</a>
                        </li>
                        <li class="breadcrumb-item active">
                            Student Course Registration
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- Flash Messages --}}
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

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="mdi mdi-alert-circle-outline me-2"></i>
            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close">
            </button>
        </div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Please correct the following:</strong>

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

    {{-- Main Card --}}
    <div class="card">

        <div class="card-header">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">

                <div>
                    <h4 class="card-title mb-1">
                        Student Course Registrations
                    </h4>

                    <p class="text-muted mb-0">
                        Manage students registered for academic courses.
                    </p>
                </div>

                @can('student-courses.create')
                    <a href="{{ route('admin.student-courses.create') }}"
                       class="btn btn-primary">
                        <i class="mdi mdi-plus me-1"></i>
                        Register Student
                    </a>
                @endcan

            </div>
        </div>

        <div class="card-body">

            {{-- Filters --}}
            <form method="GET"
                  action="{{ route('admin.student-courses.index') }}"
                  class="mb-4">

                <div class="row g-3">

                    {{-- Student --}}
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <label for="student_id" class="form-label">
                            Student
                        </label>

                        <select name="student_id"
                                id="student_id"
                                class="form-select">
                            <option value="">All Students</option>

                            @foreach($students as $student)
                                <option value="{{ $student->id }}"
                                    {{ request('student_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->student_number }}
                                    —
                                    {{ $student->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Course --}}
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <label for="course_id" class="form-label">
                            Course
                        </label>

                        <select name="course_id"
                                id="course_id"
                                class="form-select">
                            <option value="">All Courses</option>

                            @foreach($courses as $course)
                                <option value="{{ $course->id }}"
                                    {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                    {{ $course->course_code }}
                                    —
                                    {{ $course->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Academic Year --}}
                    <div class="col-xl-2 col-lg-4 col-md-6">
                        <label for="academic_year_id" class="form-label">
                            Academic Year
                        </label>

                        <select name="academic_year_id"
                                id="academic_year_id"
                                class="form-select">
                            <option value="">All Years</option>

                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}"
                                    {{ request('academic_year_id') == $year->id ? 'selected' : '' }}>
                                    {{ $year->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Term --}}
                    <div class="col-xl-2 col-lg-4 col-md-6">
                        <label for="term_id" class="form-label">
                            Term
                        </label>

                        <select name="term_id"
                                id="term_id"
                                class="form-select">
                            <option value="">All Terms</option>

                            @foreach($terms as $term)
                                <option value="{{ $term->id }}"
                                    {{ request('term_id') == $term->id ? 'selected' : '' }}>
                                    {{ $term->name }}
                                    @if($term->academicYear)
                                        — {{ $term->academicYear->name }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Status --}}
                    <div class="col-xl-2 col-lg-4 col-md-6">
                        <label for="status" class="form-label">
                            Status
                        </label>

                        <select name="status"
                                id="status"
                                class="form-select w-100"
                                style="min-width: 145px;left: 10%;top: 70%;">
                            <option value="">All Statuses</option>

                            <option value="enrolled"
                                {{ request('status') === 'enrolled' ? 'selected' : '' }}>
                                Enrolled
                            </option>

                            <option value="completed"
                                {{ request('status') === 'completed' ? 'selected' : '' }}>
                                Completed
                            </option>

                            <option value="dropped"
                                {{ request('status') === 'dropped' ? 'selected' : '' }}>
                                Dropped
                            </option>
                        </select>
                    </div>

                </div>

                <div class="row mt-3">

                    <div class="col-12 d-flex flex-wrap gap-2">

                        <button type="submit"
                                class="btn btn-primary">
                            <i class="mdi mdi-filter-outline me-1"></i>
                            Filter
                        </button>

                        <a href="{{ route('admin.student-courses.index') }}"
                           class="btn btn-light">
                            <i class="mdi mdi-refresh me-1"></i>
                            Reset
                        </a>

                    </div>

                </div>

            </form>

            {{-- Summary --}}
            <div class="row mb-3">

                <div class="col-md-4">
                    <div class="alert alert-info mb-0">
                        <i class="mdi mdi-book-open-page-variant me-1"></i>
                        Total Registrations:
                        <strong>{{ $registrations->total() }}</strong>
                    </div>
                </div>

            </div>

            {{-- Registration Table --}}
            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle mb-0">

                    <thead class="table-light">
                        <tr>

                            <th style="width: 60px;">
                                #
                            </th>

                            <th>
                                Student
                            </th>

                            <th>
                                Course
                            </th>

                            <th>
                                Academic Year
                            </th>

                            <th>
                                Term
                            </th>

                            <th>
                                Status
                            </th>

                            <th style="width: 170px;">
                                Actions
                            </th>

                        </tr>
                    </thead>

                    <tbody>

                        @forelse($registrations as $registration)

                            <tr>

                                <td>
                                    {{ $registrations->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    @if($registration->student)
                                        <div class="fw-semibold">
                                            {{ $registration->student->full_name }}
                                        </div>

                                        <div class="text-muted small">
                                            {{ $registration->student->student_number }}
                                        </div>
                                    @else
                                        <span class="text-muted">
                                            Student unavailable
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    @if($registration->course)
                                        <div class="fw-semibold">
                                            {{ $registration->course->name }}
                                        </div>

                                        <div class="text-muted small">
                                            {{ $registration->course->course_code }}
                                        </div>
                                    @else
                                        <span class="text-muted">
                                            Course unavailable
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    {{ $registration->academicYear?->name ?? '—' }}
                                </td>

                                <td>
                                    {{ $registration->term?->name ?? 'All Terms' }}
                                </td>

                                <td>

                                    @if($registration->status === 'enrolled')
                                        <span class="badge bg-success">
                                            Enrolled
                                        </span>

                                    @elseif($registration->status === 'completed')
                                        <span class="badge bg-primary">
                                            Completed
                                        </span>

                                    @elseif($registration->status === 'dropped')
                                        <span class="badge bg-danger">
                                            Dropped
                                        </span>

                                    @else
                                        <span class="badge bg-secondary">
                                            {{ ucfirst($registration->status) }}
                                        </span>
                                    @endif

                                </td>

                                <td>

                                    <div class="d-flex flex-wrap gap-1">

                                        @can('student-courses.view')
                                            <a href="{{ route('admin.student-courses.show', $registration) }}"
                                               class="btn btn-sm btn-outline-primary"
                                               title="View">
                                                <i class="mdi mdi-eye-outline"></i>
                                            </a>
                                        @endcan

                                        @can('student-courses.update')
                                            <a href="{{ route('admin.student-courses.edit', $registration) }}"
                                               class="btn btn-sm btn-outline-secondary"
                                               title="Edit">
                                                <i class="mdi mdi-pencil-outline"></i>
                                            </a>
                                        @endcan

                                        @can('student-courses.delete')
                                            <form method="POST"
                                                  action="{{ route('admin.student-courses.destroy', $registration) }}"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this course registration?');">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="Delete">
                                                    <i class="mdi mdi-delete-outline"></i>
                                                </button>

                                            </form>
                                        @endcan

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7"
                                    class="text-center py-5">

                                    <div class="text-muted">

                                        <i class="mdi mdi-book-open-page-variant-outline display-5 d-block mb-2"></i>

                                        <h5 class="mb-1">
                                            No Course Registrations Found
                                        </h5>

                                        <p class="mb-3">
                                            No students have been registered
                                            for courses yet.
                                        </p>

                                        @can('student-courses.create')
                                            <a href="{{ route('admin.student-courses.create') }}"
                                               class="btn btn-primary">
                                                <i class="mdi mdi-plus me-1"></i>
                                                Register First Student
                                            </a>
                                        @endcan

                                    </div>

                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- Pagination --}}
            @if($registrations->hasPages())
                <div class="mt-4">
                    {{ $registrations->links() }}
                </div>
            @endif

        </div>

    </div>

</div>

@endsection