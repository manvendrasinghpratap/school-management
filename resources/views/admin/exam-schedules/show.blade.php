@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Title --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0">Exam Schedule Details</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">

                        <li class="breadcrumb-item">
                            <a href="{{ url('/admin/dashboard') }}">
                                Dashboard
                            </a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.exam-schedules.index') }}">
                                Exam Schedules
                            </a>
                        </li>

                        <li class="breadcrumb-item active">
                            Details
                        </li>

                    </ol>
                </div>

            </div>
        </div>
    </div>


    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show"
             role="alert">

            <i class="ri-check-line me-1"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close">
            </button>

        </div>

    @endif


    {{-- Schedule Details --}}
    <div class="row">

        <div class="col-xl-8 col-lg-10">

            <div class="card">

                <div class="card-header">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h5 class="card-title mb-1">
                                {{ $examSchedule->examination?->name ?? 'Exam Schedule' }}
                            </h5>

                            <p class="text-muted mb-0">
                                Complete examination schedule information
                            </p>

                        </div>

                        <span class="badge bg-primary">
                            Scheduled
                        </span>

                    </div>

                </div>


                <div class="card-body">

                    {{-- Examination --}}
                    <div class="row mb-4">

                        <div class="col-md-6">

                            <h6 class="text-muted mb-2">
                                Examination
                            </h6>

                            <h5 class="mb-0">
                                {{ $examSchedule->examination?->name ?? '—' }}
                            </h5>

                        </div>


                        <div class="col-md-6">

                            <h6 class="text-muted mb-2">
                                Examination Type
                            </h6>

                            <h5 class="mb-0 text-capitalize">

                                {{ str_replace(
                                    '_',
                                    ' ',
                                    $examSchedule->examination?->type ?? '—'
                                ) }}

                            </h5>

                        </div>

                    </div>


                    <hr>


                    {{-- Academic Year / Term --}}
                    <div class="row mb-4">

                        <div class="col-md-6">

                            <h6 class="text-muted mb-2">
                                Academic Year
                            </h6>

                            <p class="mb-0">

                                {{ $examSchedule->examination?->academicYear?->name ?? '—' }}

                            </p>

                        </div>


                        <div class="col-md-6">

                            <h6 class="text-muted mb-2">
                                Term / Semester
                            </h6>

                            <p class="mb-0">

                                {{ $examSchedule->examination?->term?->name ?? '—' }}

                            </p>

                        </div>

                    </div>


                    <hr>


                    {{-- Course --}}
                    <div class="row mb-4">

                        <div class="col-md-6">

                            <h6 class="text-muted mb-2">
                                Course Code
                            </h6>

                            <p class="mb-0">

                                {{ $examSchedule->course?->course_code ?? '—' }}

                            </p>

                        </div>


                        <div class="col-md-6">

                            <h6 class="text-muted mb-2">
                                Course Name
                            </h6>

                            <p class="mb-0">

                                {{ $examSchedule->course?->name ?? '—' }}

                            </p>

                        </div>

                    </div>


                    <hr>


                    {{-- Class / Section --}}
                    <div class="row mb-4">

                        <div class="col-md-6">

                            <h6 class="text-muted mb-2">
                                Class
                            </h6>

                            <p class="mb-0">

                                {{ $examSchedule->classModel?->name ?? '—' }}

                                @if($examSchedule->classModel?->code)

                                    <small class="text-muted">
                                        ({{ $examSchedule->classModel->code }})
                                    </small>

                                @endif

                            </p>

                        </div>


                        <div class="col-md-6">

                            <h6 class="text-muted mb-2">
                                Section
                            </h6>

                            <p class="mb-0">

                                @if($examSchedule->section)

                                    {{ $examSchedule->section->name }}

                                    @if($examSchedule->section->code)

                                        <small class="text-muted">
                                            ({{ $examSchedule->section->code }})
                                        </small>

                                    @endif

                                @else

                                    <span class="badge bg-light text-dark">
                                        All Sections
                                    </span>

                                @endif

                            </p>

                        </div>

                    </div>


                    <hr>


                    {{-- Date / Time --}}
                    <div class="row mb-4">

                        <div class="col-md-4">

                            <h6 class="text-muted mb-2">
                                Exam Date
                            </h6>

                            <p class="mb-0">

                                {{ $examSchedule->exam_date?->format('d M Y') ?? '—' }}

                            </p>

                        </div>


                        <div class="col-md-4">

                            <h6 class="text-muted mb-2">
                                Start Time
                            </h6>

                            <p class="mb-0">

                                @if($examSchedule->start_time)

                                    {{ \Carbon\Carbon::parse(
                                        $examSchedule->start_time
                                    )->format('h:i A') }}

                                @else

                                    —

                                @endif

                            </p>

                        </div>


                        <div class="col-md-4">

                            <h6 class="text-muted mb-2">
                                End Time
                            </h6>

                            <p class="mb-0">

                                @if($examSchedule->end_time)

                                    {{ \Carbon\Carbon::parse(
                                        $examSchedule->end_time
                                    )->format('h:i A') }}

                                @else

                                    —

                                @endif

                            </p>

                        </div>

                    </div>


                    <hr>


                    {{-- Room --}}
                    <div class="row">

                        <div class="col-md-6">

                            <h6 class="text-muted mb-2">
                                Examination Room
                            </h6>

                            <p class="mb-0">

                                {{ $examSchedule->room ?: 'Not Assigned' }}

                            </p>

                        </div>


                        <div class="col-md-6">

                            <h6 class="text-muted mb-2">
                                Schedule ID
                            </h6>

                            <p class="mb-0">

                                #{{ $examSchedule->id }}

                            </p>

                        </div>

                    </div>

                </div>


                {{-- Actions --}}
                <div class="card-footer">

                    <div class="d-flex justify-content-between align-items-center">

                        <a href="{{ route('admin.exam-schedules.index') }}"
                           class="btn btn-light">

                            <i class="ri-arrow-left-line me-1"></i>

                            Back to Schedules

                        </a>


                        <div class="d-flex gap-2">

                            @can('exam-schedules.manage')

                                <a href="{{ route(
                                    'admin.exam-schedules.edit',
                                    $examSchedule
                                ) }}"
                                   class="btn btn-primary">

                                    <i class="ri-pencil-line me-1"></i>

                                    Edit

                                </a>


                                <form method="POST"
                                      action="{{ route(
                                          'admin.exam-schedules.destroy',
                                          $examSchedule
                                      ) }}"
                                      onsubmit="return confirm(
                                          'Are you sure you want to delete this exam schedule?'
                                      );">

                                    @csrf

                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-danger">

                                        <i class="ri-delete-bin-line me-1"></i>

                                        Delete

                                    </button>

                                </form>

                            @endcan

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Summary Card --}}
        <div class="col-xl-4 col-lg-10">

            <div class="card">

                <div class="card-header">

                    <h5 class="card-title mb-0">
                        Schedule Summary
                    </h5>

                </div>


                <div class="card-body">

                    <div class="text-center py-3">

                        <div class="avatar-lg mx-auto mb-3">

                            <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-1">

                                <i class="ri-calendar-event-line"></i>

                            </div>

                        </div>


                        <h5 class="mb-2">
                            {{ $examSchedule->course?->name ?? '—' }}
                        </h5>


                        <p class="text-muted mb-0">

                            {{ $examSchedule->classModel?->name ?? '—' }}

                            @if($examSchedule->section)

                                / {{ $examSchedule->section->name }}

                            @else

                                / All Sections

                            @endif

                        </p>

                    </div>


                    <hr>


                    <div class="d-flex justify-content-between mb-3">

                        <span class="text-muted">
                            Date
                        </span>

                        <strong>

                            {{ $examSchedule->exam_date?->format('d M Y') ?? '—' }}

                        </strong>

                    </div>


                    <div class="d-flex justify-content-between mb-3">

                        <span class="text-muted">
                            Time
                        </span>

                        <strong>

                            @if($examSchedule->start_time)

                                {{ \Carbon\Carbon::parse(
                                    $examSchedule->start_time
                                )->format('h:i A') }}

                                @if($examSchedule->end_time)

                                    -
                                    {{ \Carbon\Carbon::parse(
                                        $examSchedule->end_time
                                    )->format('h:i A') }}

                                @endif

                            @else

                                Not specified

                            @endif

                        </strong>

                    </div>


                    <div class="d-flex justify-content-between">

                        <span class="text-muted">
                            Room
                        </span>

                        <strong>

                            {{ $examSchedule->room ?: 'Not Assigned' }}

                        </strong>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection