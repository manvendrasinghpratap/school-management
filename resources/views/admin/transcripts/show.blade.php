@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Title --}}
    <div class="row">
        <div class="col-12">

            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0">
                    TRANSCRIPT
                </h4>

                <div class="page-title-right">

                    <ol class="breadcrumb m-0">

                        <li class="breadcrumb-item">
                            <a href="javascript:void(0);">
                                Examination
                            </a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.transcripts.index') }}">
                                Transcripts
                            </a>
                        </li>

                        <li class="breadcrumb-item active">
                            View Transcript
                        </li>

                    </ol>

                </div>

            </div>

        </div>
    </div>


    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="ri-checkbox-circle-line me-1"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Error Message --}}
    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="ri-error-warning-line me-1"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Transcript Details --}}
    <div class="card">

        <div class="card-header">

            <div class="d-flex align-items-center justify-content-between">

                <div>

                    <h5 class="card-title mb-1">
                        Student Transcript
                    </h5>

                    <p class="text-muted mb-0">
                        Official academic transcript record.
                    </p>

                </div>


                <div class="d-flex gap-2">

                    <a
                        href="{{ route('admin.transcripts.index') }}"
                        class="btn btn-light"
                    >

                        <i class="ri-arrow-left-line align-middle me-1"></i>

                        Back

                    </a>


                    @if($transcript->file_path)

                        <a
                            href="{{ route(
                                'admin.transcripts.pdf',
                                $transcript
                            ) }}"
                            class="btn btn-danger"
                            target="_blank"
                        >

                            <i class="ri-file-pdf-line align-middle me-1"></i>

                            View PDF

                        </a>

                    @endif

                </div>

            </div>

        </div>


        <div class="card-body">

            {{-- Student Information --}}
            <div class="mb-4">

                <h5 class="mb-3">
                    Student Information
                </h5>

                @php

                    $student = $transcript->student;

                    $studentName = $student
                        ? trim(
                            ($student->first_name ?? '') . ' ' .
                            ($student->middle_name ?? '') . ' ' .
                            ($student->last_name ?? '')
                        )
                        : '—';

                @endphp


                <div class="row g-4">

                    {{-- Student Name --}}
                    <div class="col-lg-3 col-md-6">

                        <div class="text-muted small mb-1">
                            Student Name
                        </div>

                        <div class="fw-semibold">
                            {{ $studentName ?: '—' }}
                        </div>

                    </div>


                    {{-- Student Number --}}
                    <div class="col-lg-3 col-md-6">

                        <div class="text-muted small mb-1">
                            Student Number
                        </div>

                        <div class="fw-semibold">
                            {{ $student?->student_number ?? '—' }}
                        </div>

                    </div>


                    {{-- Admission Number --}}
                    <div class="col-lg-3 col-md-6">

                        <div class="text-muted small mb-1">
                            Admission Number
                        </div>

                        <div class="fw-semibold">
                            {{ $student?->admission_number ?? '—' }}
                        </div>

                    </div>


                    {{-- Gender --}}
                    <div class="col-lg-3 col-md-6">

                        <div class="text-muted small mb-1">
                            Gender
                        </div>

                        <div class="fw-semibold">
                            {{ $student?->gender ?? '—' }}
                        </div>

                    </div>

                </div>

            </div>


            <hr>


            {{-- Transcript Information --}}
            <div class="mt-4">

                <h5 class="mb-3">
                    Transcript Information
                </h5>


                <div class="row g-4">

                    {{-- Transcript ID --}}
                    <div class="col-lg-3 col-md-6">

                        <div class="text-muted small mb-1">
                            Transcript ID
                        </div>

                        <div class="fw-semibold">
                            #{{ $transcript->id }}
                        </div>

                    </div>


                    {{-- Generated By --}}
                    <div class="col-lg-3 col-md-6">

                        <div class="text-muted small mb-1">
                            Generated By
                        </div>

                        <div class="fw-semibold">

                            @if($transcript->generatedBy)

                                {{ trim(
                                    ($transcript->generatedBy->first_name ?? '') . ' ' .
                                    ($transcript->generatedBy->last_name ?? '')
                                ) ?: $transcript->generatedBy->name }}

                            @else

                                —

                            @endif

                        </div>

                    </div>


                    {{-- Generated At --}}
                    <div class="col-lg-3 col-md-6">

                        <div class="text-muted small mb-1">
                            Generated At
                        </div>

                        <div class="fw-semibold">

                            @if($transcript->generated_at)

                                {{ $transcript->generated_at->format('d M Y H:i') }}

                            @else

                                —

                            @endif

                        </div>

                    </div>


                    {{-- File --}}
                    <div class="col-lg-3 col-md-6">

                        <div class="text-muted small mb-1">
                            PDF File
                        </div>

                        @if($transcript->file_path)

                            <span class="badge bg-success">
                                Available
                            </span>

                        @else

                            <span class="badge bg-warning text-dark">
                                Not Available
                            </span>

                        @endif

                    </div>

                </div>

            </div>


            <hr>


            {{-- School Information --}}
            <div class="mt-4">

                <h5 class="mb-3">
                    School Information
                </h5>


                <div class="row g-4">

                    {{-- School Name --}}
                    <div class="col-lg-4 col-md-6">

                        <div class="text-muted small mb-1">
                            School
                        </div>

                        <div class="fw-semibold">
                            {{ $transcript->school?->name ?? '—' }}
                        </div>

                    </div>


                    {{-- School Address --}}
                    <div class="col-lg-4 col-md-6">

                        <div class="text-muted small mb-1">
                            Address
                        </div>

                        <div class="fw-semibold">
                            {{ $transcript->school?->address ?? '—' }}
                        </div>

                    </div>


                    {{-- School Contact --}}
                    <div class="col-lg-4 col-md-6">

                        <div class="text-muted small mb-1">
                            Contact
                        </div>

                        <div class="fw-semibold">

                            @if($transcript->school?->phone)

                                {{ $transcript->school->phone }}

                            @elseif($transcript->school?->email)

                                {{ $transcript->school->email }}

                            @else

                                —

                            @endif

                        </div>

                    </div>

                </div>

            </div>


            <hr>


            {{-- Actions --}}
            <div class="mt-4">

                <div class="d-flex gap-2 flex-wrap">

                    @if($transcript->file_path)

                        <a
                            href="{{ route(
                                'admin.transcripts.pdf',
                                $transcript
                            ) }}"
                            class="btn btn-danger"
                            target="_blank"
                        >

                            <i class="ri-file-pdf-line align-middle me-1"></i>

                            View PDF

                        </a>

                    @endif


                    @can('transcripts.generate')

                        <form
                            method="POST"
                            action="{{ route(
                                'admin.transcripts.destroy',
                                $transcript
                            ) }}"
                            class="d-inline"
                        >

                            @csrf

                            @method('DELETE')

                            <button
                                type="submit"
                                class="btn btn-outline-danger"
                                onclick="return confirm('Are you sure you want to delete this transcript?');"
                            >

                                <i class="ri-delete-bin-line align-middle me-1"></i>

                                Delete Transcript

                            </button>

                        </form>

                    @endcan


                    <a
                        href="{{ route('admin.transcripts.index') }}"
                        class="btn btn-light"
                    >

                        <i class="ri-arrow-left-line align-middle me-1"></i>

                        Back to Transcripts

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection