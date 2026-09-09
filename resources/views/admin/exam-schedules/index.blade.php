@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Title --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Exam Schedules</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ url('/admin/dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">
                            Exam Schedules
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ri-check-line me-1"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    @endif

    {{-- Validation / Error Message --}}
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
                    aria-label="Close"></button>
        </div>
    @endif

    {{-- Header --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">

                        <div>
                            <h5 class="card-title mb-1">Exam Schedule Management</h5>
                            <p class="text-muted mb-0">
                                Manage examination dates, courses, classes, sections, times and rooms.
                            </p>
                        </div>

                        @can('exam-schedules.manage')
                            <a href="{{ route('admin.exam-schedules.create') }}"
                               class="btn btn-primary">
                                <i class="ri-add-line align-middle me-1"></i>
                                Add Exam Schedule
                            </a>
                        @endcan

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card">
        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.exam-schedules.index') }}">

                <div class="row g-3">

                    {{-- Search --}}
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <label class="form-label">
                            Search
                        </label>

                        <input type="text"
                               name="search"
                               class="form-control"
                               value="{{ request('search') }}"
                               placeholder="Exam, course, class or section">
                    </div>

                    {{-- Examination --}}
                    <div class="col-xl-2 col-lg-4 col-md-6">
                        <label class="form-label">
                            Examination
                        </label>

                        <select name="examination_id"
                                class="form-select">
                            <option value="">All Examinations</option>

                            @foreach($examinations as $examination)
                                <option value="{{ $examination->id }}"
                                    {{ (string)request('examination_id') === (string)$examination->id ? 'selected' : '' }}>
                                    {{ $examination->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Course --}}
                    <div class="col-xl-2 col-lg-4 col-md-6">
                        <label class="form-label">
                            Course
                        </label>

                        <select name="course_id"
                                class="form-select">
                            <option value="">All Courses</option>

                            @foreach($courses as $course)
                                <option value="{{ $course->id }}"
                                    {{ (string)request('course_id') === (string)$course->id ? 'selected' : '' }}>
                                    {{ $course->course_code }} - {{ $course->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Class --}}
                    <div class="col-xl-2 col-lg-4 col-md-6">
                        <label class="form-label">
                            Class
                        </label>

                        <select name="class_id"
                                class="form-select">
                            <option value="">All Classes</option>

                            @foreach($classes as $class)
                                <option value="{{ $class->id }}"
                                    {{ (string)request('class_id') === (string)$class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Section --}}
                    <div class="col-xl-2 col-lg-4 col-md-6">
                        <label class="form-label">
                            Section
                        </label>

                        <select name="section_id"
                                class="form-select">
                            <option value="">All Sections</option>

                            @foreach($sections as $section)
                                <option value="{{ $section->id }}"
                                    {{ (string)request('section_id') === (string)$section->id ? 'selected' : '' }}>
                                    {{ $section->class?->name }} -
                                    {{ $section->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Exam Date --}}
                    <div class="col-xl-2 col-lg-4 col-md-6">
                        <label class="form-label">
                            Exam Date
                        </label>

                        <input type="date"
                               name="exam_date"
                               class="form-control"
                               value="{{ request('exam_date') }}">
                    </div>

                    {{-- Buttons --}}
                    <div class="col-xl-3 col-lg-4 col-md-6 d-flex align-items-end gap-2">

                        <button type="submit"
                                class="btn btn-primary">
                            <i class="ri-search-line me-1"></i>
                            Filter
                        </button>

                        <a href="{{ route('admin.exam-schedules.index') }}"
                           class="btn btn-light">
                            <i class="ri-refresh-line me-1"></i>
                            Reset
                        </a>

                    </div>

                </div>

            </form>

        </div>
    </div>

    {{-- Schedule Table --}}
    <div class="card">
        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Examination</th>
                            <th>Course</th>
                            <th>Class</th>
                            <th>Section</th>
                            <th>Exam Date</th>
                            <th>Time</th>
                            <th>Room</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($schedules as $schedule)

                            <tr>

                                <td>
                                    {{ $schedules->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    <strong>
                                        {{ $schedule->examination?->name ?? '—' }}
                                    </strong>
                                </td>

                                <td>
                                    @if($schedule->course)
                                        <div>
                                            <strong>
                                                {{ $schedule->course->course_code }}
                                            </strong>
                                        </div>

                                        <small class="text-muted">
                                            {{ $schedule->course->name }}
                                        </small>
                                    @else
                                        —
                                    @endif
                                </td>

                                <td>
                                    {{ $schedule->classModel?->name ?? '—' }}
                                </td>

                                <td>
                                    @if($schedule->section)
                                        {{ $schedule->section->name }}
                                    @else
                                        <span class="badge bg-light text-dark">
                                            All Sections
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    {{ $schedule->exam_date?->format('d M Y') ?? '—' }}
                                </td>

                                <td>
                                    @if($schedule->start_time)
                                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }}

                                        @if($schedule->end_time)
                                            -
                                            {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}
                                        @endif
                                    @else
                                        —
                                    @endif
                                </td>

                                <td>
                                    {{ $schedule->room ?: '—' }}
                                </td>

                                <td class="text-end">

                                    <div class="d-inline-flex gap-1">

                                        @can('exam-schedules.view')
                                            <a href="{{ route('admin.exam-schedules.show', $schedule) }}"
                                               class="btn btn-sm btn-soft-info"
                                               title="View">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                        @endcan

                                        @can('exam-schedules.manage')
                                            <a href="{{ route('admin.exam-schedules.edit', $schedule) }}"
                                               class="btn btn-sm btn-soft-primary"
                                               title="Edit">
                                                <i class="ri-pencil-line"></i>
                                            </a>

                                            <form method="POST"
                                                  action="{{ route('admin.exam-schedules.destroy', $schedule) }}"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this exam schedule?');">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-sm btn-soft-danger"
                                                        title="Delete">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>

                                            </form>
                                        @endcan

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="9"
                                    class="text-center py-5">

                                    <div class="text-muted">

                                        <i class="ri-calendar-schedule-line fs-1 d-block mb-2"></i>

                                        <h5>No Exam Schedules Found</h5>

                                        <p class="mb-3">
                                            There are currently no exam schedules to display.
                                        </p>

                                        @can('exam-schedules.manage')
                                            <a href="{{ route('admin.exam-schedules.create') }}"
                                               class="btn btn-primary">
                                                <i class="ri-add-line me-1"></i>
                                                Create First Schedule
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
            @if($schedules->hasPages())
                <div class="mt-3">
                    {{ $schedules->links() }}
                </div>
            @endif

        </div>
    </div>

</div>

@endsection