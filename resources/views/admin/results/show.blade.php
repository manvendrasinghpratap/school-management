@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Title --}}
    <div class="row">
        <div class="col-12">

            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0">
                    RESULT DETAILS
                </h4>

                <div class="page-title-right">

                    <ol class="breadcrumb m-0">

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.results.index') }}">
                                Results
                            </a>
                        </li>

                        <li class="breadcrumb-item active">
                            Result Details
                        </li>

                    </ol>

                </div>

            </div>

        </div>
    </div>


    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Error Messages --}}
    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show">

            <strong>
                Please correct the following errors:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Student / Examination Information --}}
    <div class="card">

        <div class="card-header d-flex align-items-center justify-content-between">

            <div>

                <h5 class="card-title mb-1">
                    Examination Result
                </h5>

                <p class="text-muted mb-0">
                    Result summary and subject marks.
                </p>

            </div>

            <a href="{{ route('admin.results.index') }}"
               class="btn btn-secondary">

                <i class="ri-arrow-left-line align-middle me-1"></i>

                Back to Results

            </a>

        </div>


        <div class="card-body">

            @php

                $student = $result->student;

                $studentName = $student
                    ? trim(
                        ($student->first_name ?? '') . ' ' .
                        ($student->middle_name ?? '') . ' ' .
                        ($student->last_name ?? '')
                    )
                    : '—';

            @endphp


            <div class="row g-4">

                {{-- Student --}}
                <div class="col-md-4">

                    <div class="text-muted mb-1">
                        Student
                    </div>

                    <h5 class="mb-0">
                        {{ $studentName }}
                    </h5>

                    @if($student?->student_number)

                        <small class="text-muted">
                            {{ $student->student_number }}
                        </small>

                    @endif

                </div>


                {{-- Examination --}}
                <div class="col-md-4">

                    <div class="text-muted mb-1">
                        Examination
                    </div>

                    <h5 class="mb-0">
                        {{ $result->examination?->name ?? '—' }}
                    </h5>

                </div>


                {{-- Status --}}
                <div class="col-md-4">

                    <div class="text-muted mb-1">
                        Status
                    </div>

                    @if($result->status === 'draft')

                        <span class="badge bg-warning text-dark fs-6">
                            Draft
                        </span>

                    @elseif($result->status === 'approved')

                        <span class="badge bg-success fs-6">
                            Approved
                        </span>

                    @elseif($result->status === 'published')

                        <span class="badge bg-info fs-6">
                            Published
                        </span>

                    @else

                        <span class="badge bg-secondary fs-6">
                            {{ ucfirst($result->status ?? 'Unknown') }}
                        </span>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- Result Summary --}}
    <div class="row">

        {{-- Total Score --}}
        <div class="col-md-4">

            <div class="card">

                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div class="flex-grow-1">

                            <p class="text-muted mb-1">
                                Total Score
                            </p>

                            <h4 class="mb-0">
                                {{ number_format((float) $result->total_score, 2) }}
                            </h4>

                        </div>

                        <div class="avatar-sm">

                            <span class="avatar-title bg-primary-subtle text-primary rounded fs-3">

                                <i class="ri-bar-chart-line"></i>

                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Average --}}
        <div class="col-md-4">

            <div class="card">

                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div class="flex-grow-1">

                            <p class="text-muted mb-1">
                                Average
                            </p>

                            <h4 class="mb-0">
                                {{ number_format((float) $result->average, 2) }}%
                            </h4>

                        </div>

                        <div class="avatar-sm">

                            <span class="avatar-title bg-success-subtle text-success rounded fs-3">

                                <i class="ri-percent-line"></i>

                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Overall Grade --}}
        <div class="col-md-4">

            <div class="card">

                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div class="flex-grow-1">

                            <p class="text-muted mb-1">
                                Overall Grade
                            </p>

                            <h4 class="mb-0">
                                {{ $result->grade ?? '—' }}
                            </h4>

                        </div>

                        <div class="avatar-sm">

                            <span class="avatar-title bg-info-subtle text-info rounded fs-3">

                                <i class="ri-award-line"></i>

                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Subject Marks --}}
    <div class="card">

        <div class="card-header">

            <h5 class="card-title mb-1">
                Subject Marks
            </h5>

            <p class="text-muted mb-0">
                Approved subject marks used to calculate this result.
            </p>

        </div>


        <div class="card-body">

            @php

                $marks = \App\Models\Mark::query()
                    ->with('course')
                    ->where('student_id', $result->student_id)
                    ->where('examination_id', $result->examination_id)
                    ->where('status', 'approved')
                    ->orderBy('course_id')
                    ->get();

            @endphp


            @if($marks->count())

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>
                                    #
                                </th>

                                <th>
                                    Subject
                                </th>

                                <th>
                                    Score
                                </th>

                                <th>
                                    Maximum Score
                                </th>

                                <th>
                                    Percentage
                                </th>

                                <th>
                                    Grade
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($marks as $mark)

                                @php

                                    $score = (float) $mark->score;

                                    $maximum = (float) $mark->maximum_score;

                                    $percentage = $maximum > 0
                                        ? ($score / $maximum) * 100
                                        : 0;

                                @endphp


                                <tr>

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>

                                    <td>

                                        {{ $mark->course?->name
                                            ?? $mark->course?->course_name
                                            ?? '—'
                                        }}

                                    </td>

                                    <td>
                                        {{ number_format($score, 2) }}
                                    </td>

                                    <td>
                                        {{ number_format($maximum, 2) }}
                                    </td>

                                    <td>
                                        {{ number_format($percentage, 2) }}%
                                    </td>

                                    <td>

                                        <span class="badge bg-primary">

                                            {{ $mark->grade ?? '—' }}

                                        </span>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-4">

                    <p class="text-muted mb-0">
                        No approved subject marks were found.
                    </p>

                </div>

            @endif

        </div>

    </div>


    {{-- Workflow Information --}}
    <div class="card">

        <div class="card-header">

            <h5 class="card-title mb-0">
                Workflow Information
            </h5>

        </div>


        <div class="card-body">

            <div class="row">

                {{-- Position --}}
                <div class="col-md-4">

                    <div class="text-muted mb-1">
                        Position
                    </div>

                    <strong>
                        {{ $result->position ?? 'Not calculated' }}
                    </strong>

                </div>


                {{-- Approved By --}}
                <div class="col-md-4">

                    <div class="text-muted mb-1">
                        Approved By
                    </div>

                    <strong>
                        {{ $result->approvedBy?->name ?? 'Not approved' }}
                    </strong>

                </div>


                {{-- Published By --}}
                <div class="col-md-4">

                    <div class="text-muted mb-1">
                        Published By
                    </div>

                    <strong>
                        {{ $result->publishedBy?->name ?? 'Not published' }}
                    </strong>

                </div>

            </div>

        </div>

    </div>


    {{-- Actions --}}
    <div class="card">

        <div class="card-body">

            <div class="d-flex gap-2 align-items-center">

                {{-- Approve Result --}}
                @can('results.approve')

                    @if($result->status === 'draft')

                        <form method="POST"
                              action="{{ route('admin.results.approve', $result) }}">

                            @csrf

                            <button type="submit"
                                    class="btn btn-success"
                                    onclick="return confirm('Are you sure you want to approve this result?');">

                                <i class="ri-check-line align-middle me-1"></i>

                                Approve Result

                            </button>

                        </form>

                    @endif

                @endcan


                {{-- Publish Result --}}
                @can('results.publish')

                    @if($result->status === 'approved')

                        <form method="POST"
                              action="{{ route('admin.results.publish', $result) }}">

                            @csrf

                            <button type="submit"
                                    class="btn btn-primary"
                                    onclick="return confirm('Are you sure you want to publish this result?');">

                                <i class="ri-send-plane-line align-middle me-1"></i>

                                Publish Result

                            </button>

                        </form>

                    @endif

                @endcan


                {{-- No action available --}}
                @if(
                    ($result->status === 'approved' && !auth()->user()->can('results.publish')) ||
                    ($result->status === 'draft' && !auth()->user()->can('results.approve'))
                )

                    <span class="text-muted">
                        No action available for your account.
                    </span>

                @endif

            </div>

        </div>

    </div>

</div>

@endsection