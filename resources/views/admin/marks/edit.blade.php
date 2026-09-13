@extends('backend.layout.default')

@section('content')


<div class="container-fluid">

    {{-- Page Title --}}
    <div class="row">
        <div class="col-12">

            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0 font-size-18">
                    Edit Mark
                </h4>

                <div class="page-title-right">

                    <ol class="breadcrumb m-0">

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.marks.index') }}">
                                Marks
                            </a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.marks.show', $mark->id) }}">
                                Mark Details
                            </a>
                        </li>

                        <li class="breadcrumb-item active">
                            Edit Mark
                        </li>

                    </ol>

                </div>

            </div>

        </div>
    </div>


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


    {{-- Mark Information --}}
    <div class="card">

        <div class="card-header">

            <h5 class="card-title mb-0">
                Student & Examination Information
            </h5>

        </div>

        <div class="card-body">

            <div class="row">

                {{-- Student --}}
                <div class="col-md-4 mb-3">

                    <label class="form-label text-muted">
                        Student
                    </label>

                    <div class="fw-semibold">

                        {{ $mark->student?->first_name }}

                        @if($mark->student?->middle_name)
                            {{ $mark->student->middle_name }}
                        @endif

                        {{ $mark->student?->last_name }}

                    </div>

                </div>


                {{-- Student Number --}}
                <div class="col-md-4 mb-3">

                    <label class="form-label text-muted">
                        Student Number
                    </label>

                    <div class="fw-semibold">
                        {{ $mark->student?->student_number ?? '—' }}
                    </div>

                </div>


                {{-- Examination --}}
                <div class="col-md-4 mb-3">

                    <label class="form-label text-muted">
                        Examination
                    </label>

                    <div class="fw-semibold">
                        {{ $mark->examination?->name ?? '—' }}
                    </div>

                </div>


                {{-- Course --}}
                <div class="col-md-4 mb-3">

                    <label class="form-label text-muted">
                        Course
                    </label>

                    <div class="fw-semibold">

                        @if($mark->course)

                            {{ $mark->course->course_code }}
                            —
                            {{ $mark->course->name }}

                        @else

                            —

                        @endif

                    </div>

                </div>


                {{-- Examination Type --}}
                <div class="col-md-4 mb-3">

                    <label class="form-label text-muted">
                        Examination Type
                    </label>

                    <div class="fw-semibold">

                        {{ $mark->examination?->type
                            ? ucwords(
                                str_replace(
                                    '_',
                                    ' ',
                                    $mark->examination->type
                                )
                            )
                            : '—'
                        }}

                    </div>

                </div>


                {{-- Current Status --}}
                <div class="col-md-4 mb-3">

                    <label class="form-label text-muted">
                        Current Status
                    </label>

                    <div>

                        @if($mark->status === 'draft')

                            <span class="badge bg-secondary">
                                Draft
                            </span>

                        @elseif($mark->status === 'submitted')

                            <span class="badge bg-warning">
                                Submitted
                            </span>

                        @elseif($mark->status === 'approved')

                            <span class="badge bg-success">
                                Approved
                            </span>

                        @else

                            <span class="badge bg-dark">
                                {{ ucfirst($mark->status) }}
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Edit Mark --}}
    <div class="card">

        <div class="card-header">

            <h5 class="card-title mb-0">
                Mark Entry
            </h5>

        </div>

        <div class="card-body">

            <form method="POST"
                    action="{{ route('admin.marks.update', $mark->id) }}">

                @csrf
                @method('PUT')


                <div class="row">

                    {{-- Score --}}
                    <div class="col-md-6">

                        <label for="score"
                                class="form-label">

                            Score
                            <span class="text-danger">*</span>

                        </label>

                        <input type="number"
                                id="score"
                                name="score"
                                class="form-control"
                                min="0"
                                step="0.01"
                                value="{{ old('score', $mark->score) }}"
                                required>

                        @error('score')

                            <div class="text-danger mt-1">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Maximum Score --}}
                    <div class="col-md-6">

                        <label for="maximum_score"
                                class="form-label">

                            Maximum Score
                            <span class="text-danger">*</span>

                        </label>

                        <input type="number"
                                id="maximum_score"
                                name="maximum_score"
                                class="form-control"
                                min="0.01"
                                step="0.01"
                                value="{{ old('maximum_score', $mark->maximum_score) }}"
                                required>

                        @error('maximum_score')

                            <div class="text-danger mt-1">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>


                {{-- Preview --}}
                <div class="row mt-4">

                    <div class="col-md-4">

                        <div class="border rounded p-3">

                            <div class="text-muted mb-1">
                                Current Percentage
                            </div>

                            @php

                                $currentScore = (float) $mark->score;
                                $currentMaximum = (float) $mark->maximum_score;

                                $currentPercentage = $currentMaximum > 0
                                    ? round(
                                        ($currentScore / $currentMaximum) * 100,
                                        2
                                    )
                                    : 0;

                            @endphp

                            <h5 id="percentage-preview"
                                class="mb-0">

                                {{ number_format($currentPercentage, 2) }}%

                            </h5>

                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="border rounded p-3">

                            <div class="text-muted mb-1">
                                Current Grade
                            </div>

                            <h5 id="grade-preview"
                                class="mb-0">

                                {{ $mark->grade ?? '—' }}

                            </h5>

                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="border rounded p-3">

                            <div class="text-muted mb-1">
                                Workflow
                            </div>

                            <div class="small">

                                <span class="badge bg-secondary">
                                    Draft
                                </span>

                                <i class="bx bx-right-arrow-alt mx-1"></i>

                                <span class="badge bg-warning">
                                    Submitted
                                </span>

                                <i class="bx bx-right-arrow-alt mx-1"></i>

                                <span class="badge bg-success">
                                    Approved
                                </span>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Warning --}}
                <div class="alert alert-info mt-4 mb-0">

                    <i class="bx bx-info-circle me-1"></i>

                    Updating this mark will recalculate its percentage
                    and grade and keep the mark in
                    <strong>Draft</strong> status.

                </div>


                {{-- Actions --}}
                <div class="mt-4">

                    <button type="submit"
                            class="btn btn-success">

                        <i class="bx bx-save me-1"></i>

                        Update Mark

                    </button>


                    <a href="{{ route(
                        'admin.marks.show',
                        $mark->id
                    ) }}"
                        class="btn btn-light">

                        Cancel

                    </a>

                </div>

            </form>

        </div>

    </div>

</div>



{{-- Percentage / Grade Preview --}}
<script>

document.addEventListener('DOMContentLoaded', function () {

    const scoreInput = document.getElementById('score');
    const maximumInput = document.getElementById('maximum_score');

    const percentagePreview =
        document.getElementById('percentage-preview');

    const gradePreview =
        document.getElementById('grade-preview');


    function calculatePreview() {

        const score = parseFloat(scoreInput.value) || 0;
        const maximum = parseFloat(maximumInput.value) || 0;

        if (maximum <= 0) {

            percentagePreview.textContent = '0.00%';
            gradePreview.textContent = '—';

            return;
        }


        const percentage =
            (score / maximum) * 100;


        percentagePreview.textContent =
            percentage.toFixed(2) + '%';


        let grade = 'F';

        if (percentage >= 80) {
            grade = 'A';
        } else if (percentage >= 70) {
            grade = 'B';
        } else if (percentage >= 60) {
            grade = 'C';
        } else if (percentage >= 50) {
            grade = 'D';
        } else if (percentage >= 40) {
            grade = 'E';
        }


        gradePreview.textContent = grade;

    }


    scoreInput.addEventListener(
        'input',
        calculatePreview
    );

    maximumInput.addEventListener(
        'input',
        calculatePreview
    );

});

</script>

@endsection