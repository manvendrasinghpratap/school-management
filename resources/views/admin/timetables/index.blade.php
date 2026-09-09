@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <div>
                    <h4 class="mb-sm-0 font-size-18">Timetable</h4>

                    <p class="text-muted mb-0">
                        Manage class, subject, teacher and room schedules.
                    </p>
                </div>

                @can('timetable.manage')
                    <div class="page-title-right">
                        <a href="{{ route('admin.timetables.create') }}"
                           class="btn btn-primary">
                            <i class="bx bx-plus me-1"></i>
                            Add Timetable
                        </a>
                    </div>
                @endcan

            </div>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bx bx-check-circle me-1"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    @endif

    {{-- Error Message --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bx bx-error-circle me-1"></i>
            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <div class="fw-semibold mb-1">
                Please correct the following errors:
            </div>

            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Statistics --}}
    <div class="row">

        <div class="col-xl-3 col-md-6">
            <div class="card mini-stats-wid">
                <div class="card-body">

                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium mb-2">
                                Total Entries
                            </p>

                            <h4 class="mb-0">
                                {{ $timetables->total() }}
                            </h4>
                        </div>

                        <div class="flex-shrink-0 align-self-center">
                            <div class="avatar-sm rounded-circle bg-primary">
                                <span class="avatar-title rounded-circle bg-primary">
                                    <i class="bx bx-calendar font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card mini-stats-wid">
                <div class="card-body">

                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium mb-2">
                                Classes
                            </p>

                            <h4 class="mb-0">
                                {{ $classes->count() }}
                            </h4>
                        </div>

                        <div class="flex-shrink-0 align-self-center">
                            <div class="avatar-sm rounded-circle bg-info">
                                <span class="avatar-title rounded-circle bg-info">
                                    <i class="bx bx-building-house font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card mini-stats-wid">
                <div class="card-body">

                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium mb-2">
                                Teachers
                            </p>

                            <h4 class="mb-0">
                                {{ $staff->count() }}
                            </h4>
                        </div>

                        <div class="flex-shrink-0 align-self-center">
                            <div class="avatar-sm rounded-circle bg-success">
                                <span class="avatar-title rounded-circle bg-success">
                                    <i class="bx bx-user-voice font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card mini-stats-wid">
                <div class="card-body">

                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium mb-2">
                                Academic Years
                            </p>

                            <h4 class="mb-0">
                                {{ $academicYears->count() }}
                            </h4>
                        </div>

                        <div class="flex-shrink-0 align-self-center">
                            <div class="avatar-sm rounded-circle bg-warning">
                                <span class="avatar-title rounded-circle bg-warning">
                                    <i class="bx bx-book-open font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

    {{-- Filters --}}
    <div class="card">
        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.timetables.index') }}">

                <div class="row g-3">

                    {{-- Search --}}
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">
                            Search
                        </label>

                        <input type="text"
                               name="search"
                               class="form-control"
                               value="{{ request('search') }}"
                               placeholder="Course, class, teacher, room...">
                    </div>

                    {{-- Academic Year --}}
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label">
                            Academic Year
                        </label>

                        <select name="academic_year_id"
                                class="form-select">

                            <option value="">
                                All Years
                            </option>

                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}"
                                    @selected((string)request('academic_year_id') === (string)$year->id)>
                                    {{ $year->name }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    {{-- Class --}}
                    <div class="col-lg-2 col-md-6">
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
                                    @selected((string)request('class_id') === (string)$class->id)>
                                    {{ $class->name }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    {{-- Staff --}}
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label">
                            Teacher
                        </label>

                        <select name="staff_id"
                                class="form-select">

                            <option value="">
                                All Teachers
                            </option>

                            @foreach($staff as $teacher)
                                <option value="{{ $teacher->id }}"
                                    @selected((string)request('staff_id') === (string)$teacher->id)>
                                    {{ $teacher->full_name }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    {{-- Day --}}
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label">
                            Day
                        </label>

                        <select name="day_of_week"
                                class="form-select">

                            <option value="">
                                All Days
                            </option>

                            @foreach([
                                'monday' => 'Monday',
                                'tuesday' => 'Tuesday',
                                'wednesday' => 'Wednesday',
                                'thursday' => 'Thursday',
                                'friday' => 'Friday',
                                'saturday' => 'Saturday',
                                'sunday' => 'Sunday',
                            ] as $value => $label)

                                <option value="{{ $value }}"
                                    @selected(request('day_of_week') === $value)>
                                    {{ $label }}
                                </option>

                            @endforeach

                        </select>
                    </div>

                    {{-- Buttons --}}
                    <div class="col-lg-1 col-md-6 d-flex align-items-end">

                        <div class="d-flex gap-1 w-100">

                            <button type="submit"
                                    class="btn btn-primary w-100"
                                    title="Filter">
                                <i class="bx bx-filter-alt"></i>
                            </button>

                            <a href="{{ route('admin.timetables.index') }}"
                               class="btn btn-light"
                               title="Reset">
                                <i class="bx bx-reset"></i>
                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>
    </div>

    {{-- Timetable Table --}}
    <div class="card">

        <div class="card-header">
            <h4 class="card-title mb-1">
                Timetable Entries
            </h4>

            <p class="card-title-desc mb-0">
                View and manage scheduled classes.
            </p>
        </div>

        <div class="card-body">

            @if($timetables->count())

                <div class="table-responsive">

                    <table class="table align-middle table-nowrap mb-0">

                        <thead class="table-light">

                            <tr>
                                <th>#</th>
                                <th>Day / Time</th>
                                <th>Course</th>
                                <th>Class</th>
                                <th>Section</th>
                                <th>Teacher</th>
                                <th>Room</th>
                                <th>Academic Year</th>
                                <th class="text-end">Actions</th>
                            </tr>

                        </thead>

                        <tbody>

                            @foreach($timetables as $item)

                                <tr>

                                    <td>
                                        {{ $timetables->firstItem() + $loop->index }}
                                    </td>

                                    {{-- Day / Time --}}
                                    <td>

                                        <div class="fw-semibold text-capitalize">
                                            {{ $item->day_of_week }}
                                        </div>

                                        <div class="text-muted small">
                                            {{ \Carbon\Carbon::parse($item->start_time)->format('h:i A') }}
                                            -
                                            {{ \Carbon\Carbon::parse($item->end_time)->format('h:i A') }}
                                        </div>

                                    </td>

                                    {{-- Course --}}
                                    <td>

                                        @if($item->course)
                                            <div class="fw-semibold">
                                                {{ $item->course->name }}
                                            </div>

                                            @if($item->course->course_code)
                                                <div class="text-muted small">
                                                    {{ $item->course->course_code }}
                                                </div>
                                            @endif
                                        @else
                                            <span class="text-muted">
                                                —
                                            </span>
                                        @endif

                                    </td>

                                    {{-- Class --}}
                                    <td>
                                        {{ $item->class?->name ?? '—' }}
                                    </td>

                                    {{-- Section --}}
                                    <td>
                                        {{ $item->section?->name ?? 'All Sections' }}
                                    </td>

                                    {{-- Teacher --}}
                                    <td>

                                        @if($item->staff)

                                            <div class="fw-semibold">
                                                {{ $item->staff->full_name }}
                                            </div>

                                            @if($item->staff->staff_number)
                                                <div class="text-muted small">
                                                    {{ $item->staff->staff_number }}
                                                </div>
                                            @endif

                                        @else
                                            <span class="text-muted">
                                                Not assigned
                                            </span>
                                        @endif

                                    </td>

                                    {{-- Room --}}
                                    <td>
                                        {{ $item->room ?: '—' }}
                                    </td>

                                    {{-- Academic Year --}}
                                    <td>
                                        {{ $item->academicYear?->name ?? '—' }}

                                        @if($item->term)
                                            <div class="text-muted small">
                                                {{ $item->term->name }}
                                            </div>
                                        @endif
                                    </td>

                                    {{-- Actions --}}
                                    <td class="text-end">

                                        <div class="d-inline-flex gap-1">

                                            @can('timetable.view')
                                                <a href="{{ route('admin.timetables.show', $item) }}"
                                                   class="btn btn-sm btn-soft-primary"
                                                   title="View Timetable">
                                                    <i class="bx bx-show-alt"></i>
                                                </a>
                                            @endcan

                                            @can('timetable.manage')
                                                <a href="{{ route('admin.timetables.edit', $item) }}"
                                                   class="btn btn-sm btn-soft-info"
                                                   title="Edit Timetable">
                                                    <i class="bx bx-edit-alt"></i>
                                                </a>

                                                <form method="POST"
                                                      action="{{ route('admin.timetables.destroy', $item) }}"
                                                      class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this timetable entry?');">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                            class="btn btn-sm btn-soft-danger"
                                                            title="Delete Timetable">
                                                        <i class="bx bx-trash"></i>
                                                    </button>

                                                </form>
                                            @endcan

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

                {{-- Pagination --}}
                <div class="mt-3">
                    {{ $timetables->links() }}
                </div>

            @else

                <div class="text-center py-5">

                    <div class="mb-3">
                        <i class="bx bx-calendar-x font-size-48 text-muted"></i>
                    </div>

                    <h5>No timetable entries found</h5>

                    <p class="text-muted mb-4">
                        No timetable entries match your current filters.
                    </p>

                    @can('timetable.manage')
                        <a href="{{ route('admin.timetables.create') }}"
                           class="btn btn-primary">
                            <i class="bx bx-plus me-1"></i>
                            Add First Timetable
                        </a>
                    @endcan

                </div>

            @endif

        </div>

    </div>

</div>

@endsection