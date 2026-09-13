@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Title --}}
    <div class="row">
        <div class="col-12">

            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0">
                    RESULTS
                </h4>

                <div class="page-title-right">

                    <ol class="breadcrumb m-0">

                        <li class="breadcrumb-item">
                            <a href="javascript:void(0);">
                                Examination
                            </a>
                        </li>

                        <li class="breadcrumb-item active">
                            Results
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


    {{-- Result Filters --}}
    <div class="card">

        <div class="card-header">

            <h5 class="card-title mb-1">
                Result Filters
            </h5>

            <p class="text-muted mb-0">
                Filter examination results by examination, student or status.
            </p>

        </div>


        <div class="card-body">

            <form
                method="GET"
                action="{{ route('admin.results.index') }}"
            >

                <div class="row g-3 align-items-end">

                    {{-- Examination --}}
                    <div class="col-lg-4 col-md-6">

                        <label
                            for="examination_id"
                            class="form-label"
                        >
                            Examination
                        </label>

                        <select
                            name="examination_id"
                            id="examination_id"
                            class="form-select w-100"
                        >

                            <option value="">
                                All Examinations
                            </option>

                            @foreach($examinations as $examination)

                                <option
                                    value="{{ $examination->id }}"
                                    @selected(
                                        (string) request('examination_id') ===
                                        (string) $examination->id
                                    )
                                >

                                    {{ $examination->name }}

                                    @if($examination->type)

                                        — {{ ucwords(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $examination->type
                                            )
                                        ) }}

                                    @endif

                                </option>

                            @endforeach

                        </select>

                    </div>


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


                    {{-- Status --}}
                    <div class="col-lg-2 col-md-6">

                        <label
                            for="status"
                            class="form-label"
                        >
                            Status
                        </label>

                        <select
                            name="status"
                            id="status"
                            class="form-select w-100"
                            style="min-width: 180px;"
                        >

                            <option value="">
                                All Statuses
                            </option>

                            <option
                                value="draft"
                                @selected(request('status') === 'draft')
                            >
                                Draft
                            </option>

                            <option
                                value="approved"
                                @selected(request('status') === 'approved')
                            >
                                Approved
                            </option>

                            <option
                                value="published"
                                @selected(request('status') === 'published')
                            >
                                Published
                            </option>

                        </select>

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

                </div>

            </form>

        </div>

    </div>


    {{-- Examination Results --}}
    <div class="card">

        <div class="card-header">

            <h5 class="card-title mb-1">
                Examination Results
            </h5>

            <p class="text-muted mb-0">
                Review calculated student results.
            </p>

        </div>


        <div class="card-body">

            @if($results->count())

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th style="width: 50px;">
                                    #
                                </th>

                                <th>
                                    Student
                                </th>

                                <th>
                                    Examination
                                </th>

                                <th>
                                    Total Score
                                </th>

                                <th>
                                    Average
                                </th>

                                <th>
                                    Grade
                                </th>

                                <th>
                                    Position
                                </th>

                                <th>
                                    Status
                                </th>

                                <th style="width: 220px;">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($results as $result)

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


                                <tr>

                                    {{-- Number --}}
                                    <td>

                                        {{ $results->firstItem() + $loop->index }}

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


                                    {{-- Examination --}}
                                    <td>

                                        <strong>
                                            {{ $result->examination?->name ?? '—' }}
                                        </strong>

                                        @if($result->examination?->type)

                                            <div class="text-muted small">

                                                {{ ucwords(
                                                    str_replace(
                                                        '_',
                                                        ' ',
                                                        $result->examination->type
                                                    )
                                                ) }}

                                            </div>

                                        @endif

                                    </td>


                                    {{-- Total Score --}}
                                    <td>

                                        <strong>
                                            {{ number_format(
                                                (float) $result->total_score,
                                                2
                                            ) }}
                                        </strong>

                                    </td>


                                    {{-- Average --}}
                                    <td>

                                        <strong>
                                            {{ number_format(
                                                (float) $result->average,
                                                2
                                            ) }}%
                                        </strong>

                                    </td>


                                    {{-- Grade --}}
                                    <td>

                                        @if($result->grade)

                                            <span class="badge bg-primary">
                                                {{ $result->grade }}
                                            </span>

                                        @else

                                            —

                                        @endif

                                    </td>


                                    {{-- Position --}}
                                    <td>

                                        @if($result->position)

                                            {{ $result->position }}

                                        @else

                                            —

                                        @endif

                                    </td>


                                    {{-- Status --}}
                                    <td>

                                        @if($result->status === 'draft')

                                            <span class="badge bg-warning text-dark">
                                                Draft
                                            </span>

                                        @elseif($result->status === 'approved')

                                            <span class="badge bg-success">
                                                Approved
                                            </span>

                                        @elseif($result->status === 'published')

                                            <span class="badge bg-info">
                                                Published
                                            </span>

                                        @else

                                            <span class="badge bg-secondary">

                                                {{ ucfirst(
                                                    $result->status ?? 'Unknown'
                                                ) }}

                                            </span>

                                        @endif

                                    </td>


                                    {{-- Actions --}}
                                    <td>

                                        <div class="d-flex gap-1 flex-wrap">

                                            {{-- View Result --}}
                                            <a
                                                href="{{ route(
                                                    'admin.results.show',
                                                    $result
                                                ) }}"
                                                class="btn btn-sm btn-outline-primary"
                                                title="View Result"
                                            >

                                                <i class="ri-eye-line align-middle me-1"></i>

                                                View

                                            </a>


                                            {{-- Generate Report Card --}}
                                            @can('report-cards.generate')

                                                @if($result->status === 'published')

                                                    <form
                                                        method="POST"
                                                        action="{{ route(
                                                            'admin.report-cards.generate',
                                                            $result
                                                        ) }}"
                                                        class="d-inline"
                                                    >

                                                        @csrf

                                                        <button
                                                            type="submit"
                                                            class="btn btn-sm btn-outline-danger"
                                                            title="Generate Report Card"
                                                            onclick="return confirm('Generate a report card for this published result?');"
                                                        >

                                                            <i class="ri-file-pdf-line align-middle me-1"></i>

                                                            Report Card

                                                        </button>

                                                    </form>

                                                @endif

                                            @endcan

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- Pagination --}}
                @if($results->hasPages())

                    <div class="mt-3">

                        {{ $results->links() }}

                    </div>

                @endif


            @else

                <div class="text-center py-5">

                    <div class="mb-3">

                        <i class="ri-file-list-3-line display-5 text-muted"></i>

                    </div>

                    <h5>
                        No Results Found
                    </h5>

                    <p class="text-muted mb-0">
                        No examination results match the selected filters.
                    </p>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection