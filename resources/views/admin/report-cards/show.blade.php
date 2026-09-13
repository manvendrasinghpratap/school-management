@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Title --}}
    <div class="row">
        <div class="col-12">

            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0">
                    Report Card Details
                </h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">

                        <li class="breadcrumb-item">
                            <a href="javascript:void(0);">
                                Examinations
                            </a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.report-cards.index') }}">
                                Report Cards
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


    {{-- Flash Messages --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show" role="alert">

            <i class="ri-checkbox-circle-line me-1"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close">
            </button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show" role="alert">

            <i class="ri-error-warning-line me-1"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close">
            </button>

        </div>

    @endif


    {{-- Report Card Header --}}
    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">

            <div>
                <h5 class="card-title mb-1">
                    Report Card
                </h5>

                <p class="text-muted mb-0">
                    Student examination report
                </p>
            </div>

            <div class="d-flex gap-2">

                <a
                    href="{{ route('admin.report-cards.index') }}"
                    class="btn btn-light"
                >
                    <i class="ri-arrow-left-line me-1"></i>
                    Back
                </a>

                @if($reportCard->file_path)

                    @can('report-cards.view')

                        <a
                            href="{{ route(
                                'admin.report-cards.pdf',
                                $reportCard
                            ) }}"
                            class="btn btn-danger"
                            target="_blank"
                        >
                            <i class="ri-file-pdf-line me-1"></i>
                            View PDF
                        </a>

                    @endcan

                @endif

            </div>

        </div>


        <div class="card-body">

            {{-- Student Information --}}
            <div class="row mb-4">

                <div class="col-12">
                    <h5 class="mb-3">
                        Student Information
                    </h5>
                </div>


                <div class="col-md-3 mb-3">

                    <label class="text-muted d-block">
                        Student Name
                    </label>

                    <strong>
                        {{ trim(
                            ($reportCard->student->first_name ?? '') . ' ' .
                            ($reportCard->student->middle_name ?? '') . ' ' .
                            ($reportCard->student->last_name ?? '')
                        ) ?: '—' }}
                    </strong>

                </div>


                <div class="col-md-3 mb-3">

                    <label class="text-muted d-block">
                        Student Number
                    </label>

                    <strong>
                        {{ $reportCard->student->student_number ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-3 mb-3">

                    <label class="text-muted d-block">
                        Admission Number
                    </label>

                    <strong>
                        {{ $reportCard->student->admission_number ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-3 mb-3">

                    <label class="text-muted d-block">
                        Gender
                    </label>

                    <strong>
                        {{ ucfirst($reportCard->student->gender ?? '—') }}
                    </strong>

                </div>

            </div>


            <hr>


            {{-- Examination Information --}}
            <div class="row mb-4">

                <div class="col-12">
                    <h5 class="mb-3">
                        Examination Information
                    </h5>
                </div>


                <div class="col-md-4 mb-3">

                    <label class="text-muted d-block">
                        Examination
                    </label>

                    <strong>
                        {{ $reportCard->examination->name ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-4 mb-3">

                    <label class="text-muted d-block">
                        Examination Type
                    </label>

                    <strong>
                        {{ ucwords(
                            str_replace(
                                '_',
                                ' ',
                                $reportCard->examination->type ?? '—'
                            )
                        ) }}
                    </strong>

                </div>


                <div class="col-md-4 mb-3">

                    <label class="text-muted d-block">
                        Examination Status
                    </label>

                    <strong>
                        {{ ucfirst(
                            $reportCard->examination->status ?? '—'
                        ) }}
                    </strong>

                </div>


                <div class="col-md-4 mb-3">

                    <label class="text-muted d-block">
                        Start Date
                    </label>

                    <strong>
                        @if($reportCard->examination?->start_date)
                            {{ \Carbon\Carbon::parse(
                                $reportCard->examination->start_date
                            )->format('d M Y') }}
                        @else
                            —
                        @endif
                    </strong>

                </div>


                <div class="col-md-4 mb-3">

                    <label class="text-muted d-block">
                        End Date
                    </label>

                    <strong>
                        @if($reportCard->examination?->end_date)
                            {{ \Carbon\Carbon::parse(
                                $reportCard->examination->end_date
                            )->format('d M Y') }}
                        @else
                            —
                        @endif
                    </strong>

                </div>

            </div>


            <hr>


            {{-- Result Summary --}}
            <div class="row mb-4">

                <div class="col-12">
                    <h5 class="mb-3">
                        Result Summary
                    </h5>
                </div>


                @if($result)

                    <div class="col-md-3 mb-3">

                        <label class="text-muted d-block">
                            Total Score
                        </label>

                        <h5 class="mb-0">
                            {{ number_format(
                                (float) $result->total_score,
                                2
                            ) }}
                        </h5>

                    </div>


                    <div class="col-md-3 mb-3">

                        <label class="text-muted d-block">
                            Average
                        </label>

                        <h5 class="mb-0">
                            {{ number_format(
                                (float) $result->average,
                                2
                            ) }}
                        </h5>

                    </div>


                    <div class="col-md-3 mb-3">

                        <label class="text-muted d-block">
                            Overall Grade
                        </label>

                        <h5 class="mb-0">
                            {{ $result->grade ?? '—' }}
                        </h5>

                    </div>


                    <div class="col-md-3 mb-3">

                        <label class="text-muted d-block">
                            Position
                        </label>

                        <h5 class="mb-0">

                            @if($result->position)
                                {{ $result->position }}
                            @else
                                —
                            @endif

                        </h5>

                    </div>

                @else

                    <div class="col-12">

                        <div class="alert alert-warning mb-0">

                            <i class="ri-alert-line me-1"></i>

                            No result was found for this student and examination.

                        </div>

                    </div>

                @endif

            </div>


            <hr>


            {{-- Report Card Status --}}
            <div class="row">

                <div class="col-md-3 mb-3">

                    <label class="text-muted d-block">
                        Report Card Status
                    </label>

                    @if($reportCard->status === 'published')

                        <span class="badge bg-success">
                            <i class="ri-checkbox-circle-line me-1"></i>
                            Published
                        </span>

                    @else

                        <span class="badge bg-warning text-dark">
                            <i class="ri-file-line me-1"></i>
                            Generated
                        </span>

                    @endif

                </div>


                <div class="col-md-3 mb-3">

                    <label class="text-muted d-block">
                        Generated By
                    </label>

                    <strong>
                        {{ $reportCard->generatedBy->name ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-3 mb-3">

                    <label class="text-muted d-block">
                        Generated At
                    </label>

                    <strong>
                        {{ $reportCard->created_at?->format('d M Y H:i') ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-3 mb-3">

                    <label class="text-muted d-block">
                        Published At
                    </label>

                    <strong>
                        {{ $reportCard->published_at?->format('d M Y H:i') ?? '—' }}
                    </strong>

                </div>

            </div>


            {{-- Publish --}}
            @if(
                $reportCard->status === 'generated' &&
                $reportCard->file_path
            )

                @can('report-cards.generate')

                    <hr>

                    <div class="d-flex justify-content-end">

                        <form
                            method="POST"
                            action="{{ route(
                                'admin.report-cards.publish',
                                $reportCard
                            ) }}"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="btn btn-success"
                                onclick="return confirm('Are you sure you want to publish this report card?');"
                            >
                                <i class="ri-send-plane-line me-1"></i>
                                Publish Report Card
                            </button>

                        </form>

                    </div>

                @endcan

            @endif

        </div>

    </div>

</div>

@endsection