@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Title --}}
    <div class="row">
        <div class="col-12">

            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0">
                    GRADE CALCULATION
                </h4>

                <div class="page-title-right">

                    <ol class="breadcrumb m-0">

                        <li class="breadcrumb-item">
                            <a href="javascript:void(0);">
                                Examination
                            </a>
                        </li>

                        <li class="breadcrumb-item active">
                            Grade Calculation
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


    {{-- Validation / Error Messages --}}
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


    {{-- Examination Selection --}}
    <div class="card">

        <div class="card-header">

            <h5 class="card-title mb-1">
                Calculate Examination Results
            </h5>

            <p class="text-muted mb-0">
                Calculate student results using approved subject marks
                and the configured grading scale.
            </p>

        </div>

        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.grade-calculation.index') }}">

                <div class="row align-items-end">

                    <div class="col-md-8">

                        <label for="examination_id"
                               class="form-label">
                            Examination
                        </label>

                        <select name="examination_id"
                                id="examination_id"
                                class="form-select"
                                required>

                            <option value="">
                                Select Examination
                            </option>

                            @foreach($examinations as $examination)

                                <option value="{{ $examination->id }}"
                                    {{ optional($selectedExamination)->id == $examination->id ? 'selected' : '' }}>

                                    {{ $examination->name }}

                                    @if($examination->type)

                                        —
                                        {{ ucwords(str_replace('_', ' ', $examination->type)) }}

                                    @endif

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4">

                        <button type="submit"
                                class="btn btn-primary">

                            <i class="ri-search-line align-middle me-1"></i>

                            Load Examination

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>


    @if($selectedExamination)

        {{-- Selected Examination --}}
        <div class="card">

            <div class="card-header">

                <h5 class="card-title mb-0">
                    Selected Examination
                </h5>

            </div>

            <div class="card-body">

                <div class="row">

                    {{-- Examination --}}
                    <div class="col-md-3">

                        <div class="text-muted">
                            Examination
                        </div>

                        <strong>
                            {{ $selectedExamination->name }}
                        </strong>

                    </div>


                    {{-- Type --}}
                    <div class="col-md-3">

                        <div class="text-muted">
                            Type
                        </div>

                        <strong>

                            {{ $selectedExamination->type
                                ? ucwords(
                                    str_replace(
                                        '_',
                                        ' ',
                                        $selectedExamination->type
                                    )
                                )
                                : '—'
                            }}

                        </strong>

                    </div>


                    {{-- Start Date --}}
                    <div class="col-md-3">

                        <div class="text-muted">
                            Start Date
                        </div>

                        <strong>

                            {{ $selectedExamination->start_date
                                ? \Carbon\Carbon::parse(
                                    $selectedExamination->start_date
                                )->format('d M Y')
                                : '—'
                            }}

                        </strong>

                    </div>


                    {{-- End Date --}}
                    <div class="col-md-3">

                        <div class="text-muted">
                            End Date
                        </div>

                        <strong>

                            {{ $selectedExamination->end_date
                                ? \Carbon\Carbon::parse(
                                    $selectedExamination->end_date
                                )->format('d M Y')
                                : '—'
                            }}

                        </strong>

                    </div>

                </div>

            </div>

        </div>


        {{-- Student Calculation Status --}}
        <div class="card">

            <div class="card-header d-flex align-items-center justify-content-between">

                <div>

                    <h5 class="card-title mb-1">
                        Student Calculation Status
                    </h5>

                    <p class="text-muted mb-0">
                        A student's result can only be calculated
                        after all subject marks have been approved.
                    </p>

                </div>


                {{-- Calculate Results --}}
                @can('results.calculate')

                    <form method="POST"
                          action="{{ route('admin.grade-calculation.calculate') }}">

                        @csrf

                        <input type="hidden"
                               name="examination_id"
                               value="{{ $selectedExamination->id }}">


                        <button type="submit"
                                class="btn btn-success"
                                onclick="return confirm('Calculate results for this examination?');">

                            <i class="ri-calculator-line align-middle me-1"></i>

                            Calculate Results

                        </button>

                    </form>

                @endcan

            </div>


            <div class="card-body">

                @if($students->count())

                    <div class="table-responsive">

                        <table class="table table-bordered align-middle mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th>
                                        #
                                    </th>

                                    <th>
                                        Student
                                    </th>

                                    <th>
                                        Total Marks
                                    </th>

                                    <th>
                                        Approved Marks
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach($students as $student)

                                    @php

                                        $totalMarks = (int) $student->total_marks;

                                        $approvedMarks = (int) $student->approved_marks;

                                        $allMarksApproved =
                                            $totalMarks > 0 &&
                                            $approvedMarks === $totalMarks;

                                    @endphp


                                    <tr>

                                        {{-- Number --}}
                                        <td>
                                            {{ $loop->iteration }}
                                        </td>


                                        {{-- Student --}}
                                        <td>

                                            <strong>

                                                {{ $student->student->full_name
                                                    ?? trim(
                                                        ($student->student->first_name ?? '') . ' ' .
                                                        ($student->student->middle_name ?? '') . ' ' .
                                                        ($student->student->last_name ?? '')
                                                    )
                                                }}

                                            </strong>

                                            <div class="text-muted small">

                                                {{ $student->student->student_number ?? '—' }}

                                            </div>

                                        </td>


                                        {{-- Total Marks --}}
                                        <td>

                                            <strong>
                                                {{ $totalMarks }}
                                            </strong>

                                        </td>


                                        {{-- Approved Marks --}}
                                        <td>

                                            <strong>
                                                {{ $approvedMarks }}
                                            </strong>

                                        </td>


                                        {{-- Status --}}
                                        <td>

                                            @if($allMarksApproved)

                                                <span class="badge bg-success">
                                                    Ready
                                                </span>

                                            @else

                                                <span class="badge bg-warning text-dark">
                                                    Waiting for Approval
                                                </span>

                                            @endif

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                @else

                    <div class="text-center py-5">

                        <div class="mb-3">

                            <i class="ri-file-list-3-line display-5 text-muted"></i>

                        </div>

                        <h5>
                            No Marks Found
                        </h5>

                        <p class="text-muted mb-0">
                            No marks have been entered for this examination yet.
                        </p>

                    </div>

                @endif

            </div>

        </div>

    @endif

</div>

@endsection