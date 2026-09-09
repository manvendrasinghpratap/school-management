@extends('backend.layout.default')

@section('content')
<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Timetable Details</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.timetables.index') }}">Timetables</a>
                        </li>
                        <li class="breadcrumb-item active">View</li>
                    </ol>
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

    {{-- Error Messages --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">

        {{-- Main Details --}}
        <div class="col-lg-8">

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Timetable Information</h4>

                    <div class="d-flex gap-2">
                        @can('timetable.manage')
                            <a href="{{ route('admin.timetables.edit', $timetable) }}"
                               class="btn btn-primary btn-sm">
                                <i class="bx bx-edit-alt me-1"></i>
                                Edit
                            </a>
                        @endcan

                        <a href="{{ route('admin.timetables.index') }}"
                           class="btn btn-light btn-sm">
                            <i class="bx bx-arrow-back me-1"></i>
                            Back
                        </a>
                    </div>
                </div>

                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">

                            <tbody>

                                <tr>
                                    <th width="35%">Academic Year</th>
                                    <td>
                                        {{ $timetable->academicYear->name ?? '—' }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>Term</th>
                                    <td>
                                        {{ $timetable->term->name ?? 'All Terms' }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>Course / Subject</th>
                                    <td>
                                        <strong>
                                            {{ $timetable->course->name ?? '—' }}
                                        </strong>

                                        @if(!empty($timetable->course->course_code))
                                            <span class="text-muted ms-2">
                                                ({{ $timetable->course->course_code }})
                                            </span>
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <th>Class</th>
                                    <td>
                                        {{ $timetable->class->name ?? '—' }}

                                        @if(!empty($timetable->class->code))
                                            <span class="text-muted ms-2">
                                                ({{ $timetable->class->code }})
                                            </span>
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <th>Section</th>
                                    <td>
                                        @if($timetable->section)
                                            {{ $timetable->section->name }}

                                            @if(!empty($timetable->section->code))
                                                <span class="text-muted ms-2">
                                                    ({{ $timetable->section->code }})
                                                </span>
                                            @endif
                                        @else
                                            <span class="badge bg-info">
                                                All Sections
                                            </span>
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <th>Teacher / Staff</th>
                                    <td>
                                        @if($timetable->staff)
                                            {{ $timetable->staff->full_name }}
                                        @else
                                            <span class="text-muted">
                                                Not Assigned
                                            </span>
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <th>Day</th>
                                    <td>
                                        <span class="badge bg-primary">
                                            {{ ucfirst($timetable->day_of_week) }}
                                        </span>
                                    </td>
                                </tr>

                                <tr>
                                    <th>Start Time</th>
                                    <td>
                                        {{ \Carbon\Carbon::parse($timetable->start_time)->format('h:i A') }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>End Time</th>
                                    <td>
                                        {{ \Carbon\Carbon::parse($timetable->end_time)->format('h:i A') }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>Duration</th>
                                    <td>
                                        @php
                                            $start = \Carbon\Carbon::parse($timetable->start_time);
                                            $end = \Carbon\Carbon::parse($timetable->end_time);
                                        @endphp

                                        {{ $start->diffInMinutes($end) }} minutes
                                    </td>
                                </tr>

                                <tr>
                                    <th>Room</th>
                                    <td>
                                        {{ $timetable->room ?: 'Not Assigned' }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>Created</th>
                                    <td>
                                        {{ $timetable->created_at?->format('d M Y, h:i A') ?? '—' }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>Last Updated</th>
                                    <td>
                                        {{ $timetable->updated_at?->format('d M Y, h:i A') ?? '—' }}
                                    </td>
                                </tr>

                            </tbody>

                        </table>
                    </div>

                </div>
            </div>

        </div>

        {{-- Schedule Summary --}}
        <div class="col-lg-4">

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Schedule Summary</h4>
                </div>

                <div class="card-body">

                    <div class="text-center mb-4">
                        <div class="avatar-md mx-auto mb-3">
                            <div class="avatar-title bg-primary-subtle text-primary rounded-circle font-size-24">
                                <i class="bx bx-calendar"></i>
                            </div>
                        </div>

                        <h5 class="mb-1">
                            {{ $timetable->course->name ?? 'Subject' }}
                        </h5>

                        <p class="text-muted mb-0">
                            {{ $timetable->class->name ?? 'Class' }}
                        </p>
                    </div>

                    <div class="border-top pt-3">

                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">
                                <i class="bx bx-calendar me-1"></i>
                                Day
                            </span>

                            <strong>
                                {{ ucfirst($timetable->day_of_week) }}
                            </strong>
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">
                                <i class="bx bx-time me-1"></i>
                                Time
                            </span>

                            <strong>
                                {{ \Carbon\Carbon::parse($timetable->start_time)->format('h:i A') }}
                                -
                                {{ \Carbon\Carbon::parse($timetable->end_time)->format('h:i A') }}
                            </strong>
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">
                                <i class="bx bx-door-open me-1"></i>
                                Room
                            </span>

                            <strong>
                                {{ $timetable->room ?: '—' }}
                            </strong>
                        </div>

                        <div class="d-flex justify-content-between">
                            <span class="text-muted">
                                <i class="bx bx-user me-1"></i>
                                Teacher
                            </span>

                            <strong>
                                {{ $timetable->staff->full_name ?? '—' }}
                            </strong>
                        </div>

                    </div>

                </div>
            </div>

            {{-- Actions --}}
            @can('timetable.manage')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Actions</h4>
                    </div>

                    <div class="card-body">

                        <a href="{{ route('admin.timetables.edit', $timetable) }}"
                           class="btn btn-primary w-100 mb-2">
                            <i class="bx bx-edit-alt me-1"></i>
                            Edit Timetable
                        </a>

                        <form action="{{ route('admin.timetables.destroy', $timetable) }}"
                              method="POST"
                              onsubmit="return confirm('Are you sure you want to delete this timetable entry?');">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="btn btn-danger w-100">
                                <i class="bx bx-trash me-1"></i>
                                Delete Timetable
                            </button>
                        </form>

                    </div>
                </div>
            @endcan

        </div>

    </div>

</div>
@endsection