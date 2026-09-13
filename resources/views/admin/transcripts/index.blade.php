@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Title --}}
    <div class="row">
        <div class="col-12">

            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0">
                    TRANSCRIPTS
                </h4>

                <div class="page-title-right">

                    <ol class="breadcrumb m-0">

                        <li class="breadcrumb-item">
                            <a href="javascript:void(0);">
                                Examination
                            </a>
                        </li>

                        <li class="breadcrumb-item active">
                            Transcripts
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


    {{-- Info Message --}}
    @if(session('info'))

        <div class="alert alert-info alert-dismissible fade show">

            <i class="ri-information-line me-1"></i>

            {{ session('info') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Validation Errors --}}
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

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Generate Transcript --}}
    @can('transcripts.generate')

        <div class="card">

            <div class="card-header">

                <h5 class="card-title mb-1">
                    Generate Transcript
                </h5>

                <p class="text-muted mb-0">
                    Generate an official academic transcript using the student's published academic results.
                </p>

            </div>


            <div class="card-body">

                <form
                    method="POST"
                    action="{{ route('admin.transcripts.generate') }}"
                >

                    @csrf

                    <div class="row g-3 align-items-end">

                        {{-- Student --}}
                        <div class="col-lg-8 col-md-8">

                            <label
                                for="generate_student_id"
                                class="form-label"
                            >
                                Student <span class="text-danger">*</span>
                            </label>

                            <select
                                name="student_id"
                                id="generate_student_id"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    Select Student
                                </option>

                                @foreach($students as $student)

                                    @php

                                        $studentName = trim(
                                            ($student->first_name ?? '') . ' ' .
                                            ($student->middle_name ?? '') . ' ' .
                                            ($student->last_name ?? '')
                                        );

                                    @endphp

                                    <option
                                        value="{{ $student->id }}"
                                        @selected(
                                            old('student_id') == $student->id
                                        )
                                    >

                                        {{ $studentName }}

                                        @if($student->student_number)

                                            — {{ $student->student_number }}

                                        @endif

                                    </option>

                                @endforeach

                            </select>

                            <div class="form-text">
                                Only published academic results will be included in the transcript.
                            </div>

                        </div>


                        {{-- Generate Button --}}
                        <div class="col-lg-4 col-md-4">

                            <button
                                type="submit"
                                class="btn btn-primary w-100"
                                onclick="return confirm('Generate an official transcript for this student?');"
                            >

                                <i class="ri-file-text-line align-middle me-1"></i>

                                Generate Transcript

                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </div>

    @endcan


    {{-- Transcript Filters --}}
    <div class="card">

        <div class="card-header">

            <h5 class="card-title mb-1">
                Transcript Filters
            </h5>

            <p class="text-muted mb-0">
                Search previously generated transcripts.
            </p>

        </div>


        <div class="card-body">

            <form
                method="GET"
                action="{{ route('admin.transcripts.index') }}"
            >

                <div class="row g-3 align-items-end">

                    {{-- Student --}}
                    <div class="col-lg-4 col-md-6">

                        <label
                            for="student_id"
                            class="form-label"
                        >
                            Student
                        </label>

                        <select
                            name="student_id"
                            id="student_id"
                            class="form-select w-100"
                        >

                            <option value="">
                                All Students
                            </option>

                            @foreach($students as $student)

                                @php

                                    $studentName = trim(
                                        ($student->first_name ?? '') . ' ' .
                                        ($student->middle_name ?? '') . ' ' .
                                        ($student->last_name ?? '')
                                    );

                                @endphp

                                <option
                                    value="{{ $student->id }}"
                                    @selected(
                                        (string) request('student_id') ===
                                        (string) $student->id
                                    )
                                >

                                    {{ $studentName }}

                                    @if($student->student_number)

                                        — {{ $student->student_number }}

                                    @endif

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Generated From --}}
                    <div class="col-lg-2 col-md-6">

                        <label
                            for="generated_from"
                            class="form-label"
                        >
                            Generated From
                        </label>

                        <input
                            type="date"
                            name="generated_from"
                            id="generated_from"
                            class="form-control"
                            value="{{ request('generated_from') }}"
                        >

                    </div>


                    {{-- Generated To --}}
                    <div class="col-lg-2 col-md-6">

                        <label
                            for="generated_to"
                            class="form-label"
                        >
                            Generated To
                        </label>

                        <input
                            type="date"
                            name="generated_to"
                            id="generated_to"
                            class="form-control"
                            value="{{ request('generated_to') }}"
                        >

                    </div>


                    {{-- Filter --}}
                    <div class="col-lg-2 col-md-6">

                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                        >

                            <i class="ri-filter-3-line align-middle me-1"></i>

                            Filter

                        </button>

                    </div>


                    {{-- Reset --}}
                    <div class="col-lg-2 col-md-6">

                        <a
                            href="{{ route('admin.transcripts.index') }}"
                            class="btn btn-light w-100"
                        >

                            <i class="ri-refresh-line align-middle me-1"></i>

                            Reset

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Transcript Records --}}
    <div class="card">

        <div class="card-header">

            <h5 class="card-title mb-1">
                Generated Transcripts
            </h5>

            <p class="text-muted mb-0">
                Official academic transcripts generated for students.
            </p>

        </div>


        <div class="card-body">

            @if($transcripts->count())

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th style="width: 60px;">
                                    #
                                </th>

                                <th>
                                    Student
                                </th>

                                <th>
                                    Generated By
                                </th>

                                <th>
                                    Generated At
                                </th>

                                <th style="width: 250px;">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($transcripts as $transcript)

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


                                <tr>

                                    {{-- Number --}}
                                    <td>

                                        {{ $transcripts->firstItem() + $loop->index }}

                                    </td>


                                    {{-- Student --}}
                                    <td>

                                        <strong>
                                            {{ $studentName }}
                                        </strong>

                                        @if($student?->student_number)

                                            <div class="text-muted small">
                                                {{ $student->student_number }}
                                            </div>

                                        @endif

                                    </td>


                                    {{-- Generated By --}}
                                    <td>

                                        @if($transcript->generatedBy)

                                            {{ trim(
                                                ($transcript->generatedBy->first_name ?? '') . ' ' .
                                                ($transcript->generatedBy->last_name ?? '')
                                            ) ?: $transcript->generatedBy->name }}

                                        @else

                                            —

                                        @endif

                                    </td>


                                    {{-- Generated At --}}
                                    <td>

                                        @if($transcript->generated_at)

                                            {{ $transcript->generated_at->format('d M Y H:i') }}

                                        @else

                                            —

                                        @endif

                                    </td>


                                    {{-- Actions --}}
                                    <td>

                                        <div class="d-flex gap-1 flex-wrap">

                                            {{-- View --}}
                                            <a
                                                href="{{ route(
                                                    'admin.transcripts.show',
                                                    $transcript
                                                ) }}"
                                                class="btn btn-sm btn-outline-primary"
                                                title="View Transcript"
                                            >

                                                <i class="ri-eye-line align-middle me-1"></i>

                                                View

                                            </a>


                                            {{-- PDF --}}
                                            @if($transcript->file_path)

                                                <a
                                                    href="{{ route(
                                                        'admin.transcripts.pdf',
                                                        $transcript
                                                    ) }}"
                                                    class="btn btn-sm btn-outline-danger"
                                                    target="_blank"
                                                    title="View PDF"
                                                >

                                                    <i class="ri-file-pdf-line align-middle me-1"></i>

                                                    PDF

                                                </a>

                                            @endif


                                            {{-- Delete --}}
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
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="Delete Transcript"
                                                        onclick="return confirm('Are you sure you want to delete this transcript?');"
                                                    >

                                                        <i class="ri-delete-bin-line align-middle"></i>

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
                @if($transcripts->hasPages())

                    <div class="mt-3">

                        {{ $transcripts->links() }}

                    </div>

                @endif


            @else

                <div class="text-center py-5">

                    <div class="mb-3">

                        <i class="ri-file-text-line display-5 text-muted"></i>

                    </div>

                    <h5>
                        No Transcripts Found
                    </h5>

                    <p class="text-muted mb-0">
                        No generated transcripts match the selected filters.
                    </p>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection