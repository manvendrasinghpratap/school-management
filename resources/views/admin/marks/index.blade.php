@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Title --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Marks</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">
                            Marks
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"></button>
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

    {{-- Filters --}}
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                Filter Marks
            </h5>
        </div>

        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.marks.index') }}">

                <div class="row g-3">

                    {{-- Examination --}}
                    <div class="col-md-3">
                        <label for="examination_id"
                               class="form-label">
                            Examination
                        </label>

                        <select name="examination_id"
                                id="examination_id"
                                class="form-select" >

                            <option value="">
                                All Examinations
                            </option>

                            @foreach($examinations as $examination)
                                <option value="{{ $examination->id }}"
                                    @selected(
                                        request('examination_id') == $examination->id
                                    )>
                                    {{ $examination->name }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    {{-- Course --}}
                    <div class="col-md-3">
                        <label for="course_id"
                               class="form-label">
                            Subject / Course
                        </label>

                        <select name="course_id"
                                id="course_id"
                                class="form-select">

                            <option value="">
                                All Courses
                            </option>

                            @foreach($courses as $course)
                                <option value="{{ $course->id }}"
                                    @selected(
                                        request('course_id') == $course->id
                                    )>
                                    {{ $course->course_code }}
                                    —
                                    {{ $course->name }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    {{-- Student --}}
                    <div class="col-md-3">
                        <label for="student_id"
                               class="form-label">
                            Student
                        </label>

                        <select name="student_id"
                                id="student_id"
                                class="form-select">

                            <option value="">
                                All Students
                            </option>

                            @foreach($students as $student)
                                <option value="{{ $student->id }}"
                                    @selected(
                                        request('student_id') == $student->id
                                    )>
                                    {{ $student->student_number }}
                                    —
                                    {{ $student->full_name }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    {{-- Status --}}
                    <div class="col-md-3">
                        <label for="status"
                               class="form-label">
                            Status
                        </label>

                        <select name="status"
                                id="status"
                                class="form-select" style="top: 68%; left: 9%; width: 95%; >

                            <option value="">
                                All Statuses
                            </option>

                            <option value="draft"
                                @selected(request('status') === 'draft')>
                                Draft
                            </option>

                            <option value="submitted"
                                @selected(request('status') === 'submitted')>
                                Submitted
                            </option>

                            <option value="approved"
                                @selected(request('status') === 'approved')>
                                Approved
                            </option>

                        </select>
                    </div>

                    {{-- Buttons --}}
                    <div class="col-12">

                        <button type="submit"
                                class="btn btn-primary">
                            <i class="bx bx-search-alt me-1"></i>
                            Filter
                        </button>

                        <a href="{{ route('admin.marks.index') }}"
                           class="btn btn-light">
                            Reset
                        </a>

                        @can('marks.enter')
                            <a href="{{ route('admin.marks.create') }}"
                               class="btn btn-success float-end">
                                <i class="bx bx-plus me-1"></i>
                                Enter Marks
                            </a>
                        @endcan

                    </div>

                </div>

            </form>

        </div>
    </div>

    {{-- Marks Table --}}
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                Marks List
            </h5>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-light">

                        <tr>
                            <th>#</th>
                            <th>Student</th>
                            <th>Examination</th>
                            <th>Course</th>
                            <th>Score</th>
                            <th>Percentage</th>
                            <th>Grade</th>
                            <th>Status</th>
                            <th style="width: 180px;">
                                Actions
                            </th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($marks as $mark)

                            @php
                                $percentage = $mark->maximum_score > 0
                                    ? ($mark->score / $mark->maximum_score) * 100
                                    : 0;
                            @endphp

                            <tr>

                                <td>
                                    {{ $marks->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    <strong>
                                        {{ $mark->student->full_name }}
                                    </strong>

                                    <br>

                                    <small class="text-muted">
                                        {{ $mark->student->student_number }}
                                    </small>
                                </td>

                                <td>
                                    {{ $mark->examination->name }}
                                </td>

                                <td>
                                    <strong>
                                        {{ $mark->course->course_code }}
                                    </strong>

                                    <br>

                                    <small class="text-muted">
                                        {{ $mark->course->name }}
                                    </small>
                                </td>

                                <td>
                                    {{ number_format((float) $mark->score, 2) }}
                                    /
                                    {{ number_format((float) $mark->maximum_score, 2) }}
                                </td>

                                <td>
                                    {{ number_format($percentage, 2) }}%
                                </td>

                                <td>
                                    @if($mark->grade)
                                        <span class="fw-semibold">
                                            {{ $mark->grade }}
                                        </span>
                                    @else
                                        <span class="text-muted">
                                            —
                                        </span>
                                    @endif
                                </td>

                                <td>

                                    @if($mark->status === 'draft')

                                        <span class="badge bg-secondary">
                                            Draft
                                        </span>

                                    @elseif($mark->status === 'submitted')

                                        <span class="badge bg-warning text-dark">
                                            Submitted
                                        </span>

                                    @elseif($mark->status === 'approved')

                                        <span class="badge bg-success">
                                            Approved
                                        </span>

                                    @else

                                        <span class="badge bg-light text-dark">
                                            {{ ucfirst($mark->status) }}
                                        </span>

                                    @endif

                                </td>

                                <td>

                                    <a href="{{ route('admin.marks.show', $mark) }}"
                                       class="btn btn-sm btn-info">
                                        View
                                    </a>

                                    @if($mark->status !== 'approved')

                                        @can('marks.update')

                                            <a href="{{ route('admin.marks.edit', $mark) }}"
                                               class="btn btn-sm btn-primary">
                                                Edit
                                            </a>

                                        @endcan

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="9"
                                    class="text-center py-4">

                                    <div class="text-muted">

                                        <i class="bx bx-book-open font-size-24 d-block mb-2"></i>

                                        No marks found.

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- Pagination --}}
            @if($marks->hasPages())

                <div class="mt-3">

                    {{ $marks->links() }}

                </div>

            @endif

        </div>
    </div>

</div>

@endsection