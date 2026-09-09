@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0 font-size-18">
                    Examination Details
                </h4>

                <div class="page-title-right d-flex gap-2">

                    @can('examinations.update')
                        <a href="{{ route('admin.examinations.edit', $examination) }}"
                           class="btn btn-warning">
                            <i class="bx bx-edit me-1"></i>
                            Edit
                        </a>
                    @endcan

                    <a href="{{ route('admin.examinations.index') }}"
                       class="btn btn-light">
                        <i class="bx bx-arrow-back me-1"></i>
                        Back
                    </a>

                </div>

            </div>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show"
             role="alert">

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close">
            </button>

        </div>

    @endif

    {{-- Examination Information --}}
    <div class="card">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <h5 class="card-title mb-0">
                    {{ $examination->name }}
                </h5>

                @php
                    $statusClass = match($examination->status) {
                        'draft' => 'bg-secondary',
                        'scheduled' => 'bg-info',
                        'ongoing' => 'bg-warning',
                        'completed' => 'bg-success',
                        'published' => 'bg-primary',
                        default => 'bg-secondary',
                    };
                @endphp

                <span class="badge {{ $statusClass }} font-size-13">
                    {{ ucfirst($examination->status) }}
                </span>

            </div>

            <div class="row">

                {{-- Examination Name --}}
                <div class="col-md-6 mb-4">

                    <label class="text-muted d-block mb-1">
                        Examination Name
                    </label>

                    <strong>
                        {{ $examination->name }}
                    </strong>

                </div>

                {{-- Examination Type --}}
                <div class="col-md-6 mb-4">

                    <label class="text-muted d-block mb-1">
                        Examination Type
                    </label>

                    <strong>
                        {{ ucwords(str_replace('_', ' ', $examination->type)) }}
                    </strong>

                </div>

                {{-- Academic Year --}}
                <div class="col-md-6 mb-4">

                    <label class="text-muted d-block mb-1">
                        Academic Year
                    </label>

                    <strong>
                        {{ $examination->academicYear?->name
                            ?? $examination->academicYear?->year
                            ?? $examination->academic_year_id }}
                    </strong>

                </div>

                {{-- Term --}}
                <div class="col-md-6 mb-4">

                    <label class="text-muted d-block mb-1">
                        Term / Semester
                    </label>

                    <strong>
                        {{ $examination->term?->name
                            ?? $examination->term?->title
                            ?? ($examination->term_id ?: '—') }}
                    </strong>

                </div>

                {{-- Start Date --}}
                <div class="col-md-6 mb-4">

                    <label class="text-muted d-block mb-1">
                        Start Date
                    </label>

                    <strong>
                        {{ $examination->start_date
                            ? $examination->start_date->format('Y-m-d')
                            : '—' }}
                    </strong>

                </div>

                {{-- End Date --}}
                <div class="col-md-6 mb-4">

                    <label class="text-muted d-block mb-1">
                        End Date
                    </label>

                    <strong>
                        {{ $examination->end_date
                            ? $examination->end_date->format('Y-m-d')
                            : '—' }}
                    </strong>

                </div>

                {{-- Created --}}
                <div class="col-md-6 mb-4">

                    <label class="text-muted d-block mb-1">
                        Created
                    </label>

                    <strong>
                        {{ $examination->created_at
                            ? $examination->created_at->format('Y-m-d H:i')
                            : '—' }}
                    </strong>

                </div>

                {{-- Last Updated --}}
                <div class="col-md-6 mb-4">

                    <label class="text-muted d-block mb-1">
                        Last Updated
                    </label>

                    <strong>
                        {{ $examination->updated_at
                            ? $examination->updated_at->format('Y-m-d H:i')
                            : '—' }}
                    </strong>

                </div>

            </div>

        </div>

    </div>

    {{-- Examination Statistics --}}
    <div class="row">

        {{-- Schedules --}}
        <div class="col-md-4">

            <div class="card">

                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div class="avatar-sm">
                            <span class="avatar-title bg-primary-subtle text-primary rounded">
                                <i class="bx bx-calendar font-size-20"></i>
                            </span>
                        </div>

                        <div class="ms-3">

                            <p class="text-muted mb-1">
                                Exam Schedules
                            </p>

                            <h5 class="mb-0">
                                {{ $examination->examSchedules->count() }}
                            </h5>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- Marks --}}
        <div class="col-md-4">

            <div class="card">

                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div class="avatar-sm">
                            <span class="avatar-title bg-success-subtle text-success rounded">
                                <i class="bx bx-edit font-size-20"></i>
                            </span>
                        </div>

                        <div class="ms-3">

                            <p class="text-muted mb-1">
                                Marks Entered
                            </p>

                            <h5 class="mb-0">
                                {{ $examination->marks->count() }}
                            </h5>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- Results --}}
        <div class="col-md-4">

            <div class="card">

                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div class="avatar-sm">
                            <span class="avatar-title bg-info-subtle text-info rounded">
                                <i class="bx bx-bar-chart-alt-2 font-size-20"></i>
                            </span>
                        </div>

                        <div class="ms-3">

                            <p class="text-muted mb-1">
                                Results
                            </p>

                            <h5 class="mb-0">
                                {{ $examination->results->count() }}
                            </h5>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection