@extends('backend.layout.default')

@section('title', 'Attendance Report')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1">Student Attendance Report</h4>
            <p class="text-muted mb-0">
                Attendance records by date, class, section and status.
            </p>
        </div>

        <div class="d-flex gap-2">
            <a
                class="btn btn-outline-danger"
                href="{{ route('admin.reports.attendance.pdf', request()->query()) }}"
            >
                PDF
            </a>

            <a
                class="btn btn-outline-success"
                href="{{ route('admin.reports.attendance.excel', request()->query()) }}"
            >
                Excel
            </a>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2">

                <div class="col-md-2">
                    <input
                        type="date"
                        class="form-control"
                        name="from"
                        value="{{ request('from', $from) }}"
                    >
                </div>

                <div class="col-md-2">
                    <input
                        type="date"
                        class="form-control"
                        name="to"
                        value="{{ request('to', $to) }}"
                    >
                </div>

                <div class="col-md-2">
                    <select class="form-select" name="class_id">
                        <option value="">All classes</option>

                        @foreach($classes as $class)
                            <option
                                value="{{ $class->id }}"
                                @selected((string) request('class_id') === (string) $class->id)
                            >
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <select class="form-select" name="section_id">
                        <option value="">All sections</option>

                        @foreach($sections as $section)
                            <option
                                value="{{ $section->id }}"
                                @selected((string) request('section_id') === (string) $section->id)
                            >
                                {{ $section->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <select class="form-select" name="status">
                        <option value="">All statuses</option>

                        @foreach(['present', 'absent', 'late', 'excused'] as $v)
                            <option
                                value="{{ $v }}"
                                @selected(request('status') === $v)
                            >
                                {{ ucfirst($v) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-1">
                    <button class="btn btn-primary w-100">
                        Go
                    </button>
                </div>

                <div class="col-md-1">
                    <a
                        href="{{ route('admin.reports.attendance') }}"
                        class="btn btn-light w-100"
                    >
                        ×
                    </a>
                </div>

            </form>
        </div>
    </div>

    {{-- Summary --}}
    <div class="row g-3 mb-3">
        @foreach($summary as $s)
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body d-flex justify-content-between">
                        <span>{{ ucfirst($s->status) }}</span>
                        <strong>{{ number_format($s->total) }}</strong>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Attendance Records --}}
    <div class="card">
        <div class="card-body table-responsive">

            <table class="table table-hover">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Student</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Course</th>
                        <th>Status</th>
                        <th>Remarks</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($records as $a)

                        <tr>

                            <td>
                                {{ $records->firstItem() + $loop->index }}
                            </td>

                            <td>
                                {{ optional($a->attendance_date)->format('d M Y') }}
                            </td>

                            <td>
                                {{ trim("{$a->student->first_name} {$a->student->middle_name} {$a->student->last_name}") }}
                            </td>

                            <td>
                                {{ $a->classModel?->name ?? '-' }}
                            </td>

                            <td>
                                {{ $a->section?->name ?? '-' }}
                            </td>

                            <td>
                                {{ $a->course?->name ?? '-' }}
                            </td>

                            <td>
                                {{ ucfirst($a->status) }}
                            </td>

                            <td>
                                {{ $a->remarks ?? '-' }}
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                No attendance records found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

            {{ $records->links() }}

        </div>
    </div>

</div>
@endsection