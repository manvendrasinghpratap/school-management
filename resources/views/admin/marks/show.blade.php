@extends('backend.layout.default')

@section('content')


<div class="container-fluid">

    {{-- Page Title --}}
    <div class="row">
        <div class="col-12">

            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0 font-size-18">
                    Mark Details
                </h4>

                <div class="page-title-right">

                    <ol class="breadcrumb m-0">

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.marks.index') }}">
                                Marks
                            </a>
                        </li>

                        <li class="breadcrumb-item active">
                            Mark Details
                        </li>

                    </ol>

                </div>

            </div>

        </div>
    </div>


    {{-- Flash Messages --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bx bx-check-circle me-1"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Student / Examination Information --}}
    <div class="card">

        <div class="card-header">

            <h5 class="card-title mb-0">
                Student & Examination Information
            </h5>

        </div>

        <div class="card-body">

            <div class="row">

                {{-- Student --}}
                <div class="col-md-4 mb-4">

                    <div class="text-muted mb-1">
                        Student
                    </div>

                    <h5 class="mb-0">

                        {{ $mark->student?->first_name }}

                        @if($mark->student?->middle_name)
                            {{ $mark->student->middle_name }}
                        @endif

                        {{ $mark->student?->last_name }}

                    </h5>

                </div>


                {{-- Student Number --}}
                <div class="col-md-4 mb-4">

                    <div class="text-muted mb-1">
                        Student Number
                    </div>

                    <strong>
                        {{ $mark->student?->student_number ?? '—' }}
                    </strong>

                </div>


                {{-- Examination --}}
                <div class="col-md-4 mb-4">

                    <div class="text-muted mb-1">
                        Examination
                    </div>

                    <strong>
                        {{ $mark->examination?->name ?? '—' }}
                    </strong>

                </div>


                {{-- Course --}}
                <div class="col-md-4 mb-4">

                    <div class="text-muted mb-1">
                        Course
                    </div>

                    <strong>

                        @if($mark->course)

                            {{ $mark->course->course_code }}

                            —

                            {{ $mark->course->name }}

                        @else

                            —

                        @endif

                    </strong>

                </div>


                {{-- Examination Type --}}
                <div class="col-md-4 mb-4">

                    <div class="text-muted mb-1">
                        Examination Type
                    </div>

                    <strong>
                        {{ $mark->examination?->type
                            ? ucwords(str_replace('_', ' ', $mark->examination->type))
                            : '—'
                        }}
                    </strong>

                </div>


                {{-- Examination Status --}}
                <div class="col-md-4 mb-4">

                    <div class="text-muted mb-1">
                        Examination Status
                    </div>

                    @if($mark->examination?->status === 'scheduled')

                        <span class="badge bg-info">
                            Scheduled
                        </span>

                    @elseif($mark->examination?->status === 'ongoing')

                        <span class="badge bg-warning">
                            Ongoing
                        </span>

                    @elseif($mark->examination?->status === 'completed')

                        <span class="badge bg-success">
                            Completed
                        </span>

                    @else

                        <span class="badge bg-secondary">
                            {{ ucfirst($mark->examination?->status ?? 'Unknown') }}
                        </span>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- Mark Information --}}
    <div class="card">

        <div class="card-header">

            <h5 class="card-title mb-0">
                Mark Information
            </h5>

        </div>

        <div class="card-body">

            @php

                $score = (float) $mark->score;
                $maximumScore = (float) $mark->maximum_score;

                $percentage = $maximumScore > 0
                    ? round(($score / $maximumScore) * 100, 2)
                    : 0;

            @endphp

            <div class="row">

                {{-- Score --}}
                <div class="col-md-3 mb-4">

                    <div class="text-muted mb-1">
                        Score
                    </div>

                    <h4 class="mb-0">

                        {{ number_format($score, 2) }}

                        <small class="text-muted">
                            /
                            {{ number_format($maximumScore, 2) }}
                        </small>

                    </h4>

                </div>


                {{-- Percentage --}}
                <div class="col-md-3 mb-4">

                    <div class="text-muted mb-1">
                        Percentage
                    </div>

                    <h4 class="mb-0">

                        {{ number_format($percentage, 2) }}%

                    </h4>

                </div>


                {{-- Grade --}}
                <div class="col-md-3 mb-4">

                    <div class="text-muted mb-1">
                        Grade
                    </div>

                    <h4 class="mb-0">

                        @if($mark->grade)

                            <span class="badge bg-primary fs-6">
                                {{ $mark->grade }}
                            </span>

                        @else

                            —

                        @endif

                    </h4>

                </div>


                {{-- Status --}}
                <div class="col-md-3 mb-4">

                    <div class="text-muted mb-1">
                        Status
                    </div>

                    @if($mark->status === 'draft')

                        <span class="badge bg-secondary fs-6">
                            Draft
                        </span>

                    @elseif($mark->status === 'submitted')

                        <span class="badge bg-warning fs-6">
                            Submitted
                        </span>

                    @elseif($mark->status === 'approved')

                        <span class="badge bg-success fs-6">
                            Approved
                        </span>

                    @else

                        <span class="badge bg-dark fs-6">
                            {{ ucfirst($mark->status) }}
                        </span>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- Audit Information --}}
    <div class="card">

        <div class="card-header">

            <h5 class="card-title mb-0">
                Audit Information
            </h5>

        </div>

        <div class="card-body">

            <div class="row">

                {{-- Entered By --}}
                <div class="col-md-4 mb-3">

                    <div class="text-muted mb-1">
                        Entered By
                    </div>

                    <strong>

                        @if($mark->enteredBy)

                            {{ $mark->enteredBy->name }}

                            @if($mark->enteredBy->email)
                                <br>
                                <small class="text-muted">
                                    {{ $mark->enteredBy->email }}
                                </small>
                            @endif

                        @else

                            —

                        @endif

                    </strong>

                </div>


                {{-- Approved By --}}
                <div class="col-md-4 mb-3">

                    <div class="text-muted mb-1">
                        Approved By
                    </div>

                    <strong>

                        @if($mark->approvedBy)

                            {{ $mark->approvedBy->name }}

                            @if($mark->approvedBy->email)
                                <br>
                                <small class="text-muted">
                                    {{ $mark->approvedBy->email }}
                                </small>
                            @endif

                        @else

                            —

                        @endif

                    </strong>

                </div>


                {{-- Created --}}
                <div class="col-md-4 mb-3">

                    <div class="text-muted mb-1">
                        Created At
                    </div>

                    <strong>

                        {{ $mark->created_at
                            ? $mark->created_at->format('d M Y H:i')
                            : '—'
                        }}

                    </strong>

                </div>


                {{-- Updated --}}
                <div class="col-md-4 mb-3">

                    <div class="text-muted mb-1">
                        Last Updated
                    </div>

                    <strong>

                        {{ $mark->updated_at
                            ? $mark->updated_at->format('d M Y H:i')
                            : '—'
                        }}

                    </strong>

                </div>

            </div>

        </div>

    </div>


    {{-- Actions --}}
    <div class="card">

        <div class="card-body">

            <div class="d-flex flex-wrap gap-2">

                {{-- Back --}}
                <a href="{{ route('admin.marks.index') }}"
                    class="btn btn-light">

                    <i class="bx bx-arrow-back me-1"></i>

                    Back to Marks

                </a>


                {{-- Edit Draft --}}
                @if(
                    $mark->status === 'draft' &&
                    auth()->user()->can('marks.update')
                )

                    <a href="{{ route(
                        'admin.marks.edit',
                        $mark->id
                    ) }}"
                        class="btn btn-primary">

                        <i class="bx bx-edit me-1"></i>

                        Edit Mark

                    </a>

                @endif


                {{-- Submit Draft --}}
                @if(
                    $mark->status === 'draft' &&
                    auth()->user()->can('marks.enter')
                )

                    <form method="POST"
                            action="{{ route(
                                'admin.marks.submit',
                                $mark->id
                            ) }}"
                            class="d-inline">

                        @csrf

                        <button type="submit"
                                class="btn btn-warning"
                                onclick="return confirm(
                                    'Submit this mark for approval?'
                                )">

                            <i class="bx bx-send me-1"></i>

                            Submit for Approval

                        </button>

                    </form>

                @endif


                {{-- Approve --}}
                @if(
                    $mark->status === 'submitted' &&
                    auth()->user()->can('marks.approve')
                )

                    <form method="POST"
                            action="{{ route(
                                'admin.marks.approve',
                                $mark->id
                            ) }}"
                            class="d-inline">

                        @csrf

                        <button type="submit"
                                class="btn btn-success"
                                onclick="return confirm(
                                    'Approve this mark?'
                                )">

                            <i class="bx bx-check-circle me-1"></i>

                            Approve Mark

                        </button>

                    </form>

                @endif

            </div>

        </div>

    </div>

</div>

@endsection