@extends('backend.layout.default')

@section('content')


    <div class="container-fluid">

        {{-- Page Title --}}
        <div class="row">
            <div class="col-12">

                <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                    <h4 class="mb-sm-0">
                        Enter Marks
                    </h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">

                            <li class="breadcrumb-item">
                                <a href="{{ url('/admin/dashboard') }}">
                                    Dashboard
                                </a>
                            </li>

                            <li class="breadcrumb-item">
                                Marks
                            </li>

                            <li class="breadcrumb-item active">
                                Enter Marks
                            </li>

                        </ol>
                    </div>

                </div>

            </div>
        </div>


        {{-- Examination Selection --}}
        <div class="card">

            <div class="card-header">
                <h5 class="card-title mb-0">
                    Select Examination
                </h5>
            </div>

            <div class="card-body">

                <form method="GET"
                      action="{{ route('admin.marks.create') }}">

                    <div class="row align-items-end">

                        <div class="col-md-8">

                            <label for="examination_id"
                                   class="form-label">
                                Examination
                                <span class="text-danger">*</span>
                            </label>

                            <select name="examination_id"
                                    id="examination_id"
                                    class="form-select"
                                    required>

                                <option value="">
                                    -- Select Examination --
                                </option>

                                @foreach($examinations as $examination)

                                    <option value="{{ $examination->id }}"
                                        {{ optional($selectedExamination)->id == $examination->id ? 'selected' : '' }}>

                                        {{ $examination->name }}
                                        —
                                        {{ optional($examination->start_date)->format('d M Y') }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                        <div class="col-md-4">

                            <button type="submit"
                                    class="btn btn-primary">

                                <i class="bx bx-calendar"></i>

                                Load Exam Schedules

                            </button>

                            <a href="{{ route('admin.marks.index') }}"
                               class="btn btn-light">

                                Cancel

                            </a>

                        </div>

                    </div>

                </form>

            </div>

        </div>


        {{-- Exam Schedules --}}
        @if($selectedExamination)

            <div class="card">

                <div class="card-header">

                    <h5 class="card-title mb-0">
                        Exam Schedules
                    </h5>

                </div>

                <div class="card-body">

                    @if($schedules->count())

                        <div class="table-responsive">

                            <table class="table table-bordered table-hover align-middle">

                                <thead class="table-light">

                                    <tr>

                                        <th style="width:60px;">
                                            #
                                        </th>

                                        <th>
                                            Course
                                        </th>

                                        <th>
                                            Class
                                        </th>

                                        <th>
                                            Section
                                        </th>

                                        <th>
                                            Exam Date
                                        </th>

                                        <th>
                                            Time
                                        </th>

                                        <th>
                                            Room
                                        </th>

                                        <th style="width:150px;">
                                            Action
                                        </th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach($schedules as $index => $schedule)

                                        <tr
                                            class="{{ $selectedSchedule && $selectedSchedule->id == $schedule->id ? 'table-primary' : '' }}"
                                        >

                                            <td>
                                                {{ $index + 1 }}
                                            </td>

                                            <td>

                                                <strong>
                                                    {{ $schedule->course?->code }}
                                                </strong>

                                                <br>

                                                <small class="text-muted">
                                                    {{ $schedule->course?->name }}
                                                </small>

                                            </td>

                                            <td>
                                                {{ $schedule->classModel?->name ?? '—' }}
                                            </td>

                                            <td>

                                                @if($schedule->section)
                                                    {{ $schedule->section->name }}
                                                @else
                                                    <span class="badge bg-info">
                                                        All Sections
                                                    </span>
                                                @endif

                                            </td>

                                            <td>
                                                {{ \Carbon\Carbon::parse($schedule->exam_date)->format('d M Y') }}
                                            </td>

                                            <td>

                                                {{ $schedule->start_time
                                                    ? \Carbon\Carbon::parse($schedule->start_time)->format('H:i')
                                                    : '—'
                                                }}

                                                -

                                                {{ $schedule->end_time
                                                    ? \Carbon\Carbon::parse($schedule->end_time)->format('H:i')
                                                    : '—'
                                                }}

                                            </td>

                                            <td>
                                                {{ $schedule->room ?? '—' }}
                                            </td>

                                            <td>

                                                <a href="{{ route('admin.marks.create', [
                                                    'examination_id' => $selectedExamination->id,
                                                    'schedule_id' => $schedule->id,
                                                ]) }}"
                                                   class="btn btn-sm btn-success">

                                                    <i class="bx bx-edit"></i>

                                                    Enter Marks

                                                </a>

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="alert alert-warning mb-0">

                            <i class="bx bx-info-circle me-1"></i>

                            No examination schedules have been created
                            for this examination.

                        </div>

                    @endif

                </div>

            </div>

        @endif


        {{-- Selected Schedule --}}
        @if($selectedSchedule)

            <div class="card">

                <div class="card-header">

                    <h5 class="card-title mb-0">
                        Selected Examination Schedule
                    </h5>

                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-3">

                            <div class="text-muted">
                                Examination
                            </div>

                            <strong>
                                {{ $selectedExamination->name }}
                            </strong>

                        </div>

                        <div class="col-md-3">

                            <div class="text-muted">
                                Course
                            </div>

                            <strong>
                                {{ $selectedSchedule->course?->code }}
                                -
                                {{ $selectedSchedule->course?->name }}
                            </strong>

                        </div>

                        <div class="col-md-2">

                            <div class="text-muted">
                                Class
                            </div>

                            <strong>
                                {{ $selectedSchedule->classModel?->name }}
                            </strong>

                        </div>

                        <div class="col-md-2">

                            <div class="text-muted">
                                Section
                            </div>

                            <strong>
                                {{ $selectedSchedule->section?->name ?? 'All Sections' }}
                            </strong>

                        </div>

                        <div class="col-md-2">

                            <div class="text-muted">
                                Date
                            </div>

                            <strong>
                                {{ \Carbon\Carbon::parse($selectedSchedule->exam_date)->format('d M Y') }}
                            </strong>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Eligible Students --}}
            <div class="card">

                <div class="card-header d-flex justify-content-between align-items-center">

                    <h5 class="card-title mb-0">
                        Eligible Students
                    </h5>

                    <span class="badge bg-primary">
                        {{ $eligibleStudents->count() }}
                        Student(s)
                    </span>

                </div>

                <div class="card-body">

                    @if($eligibleStudents->count())

                        <div class="table-responsive">

                            <table class="table table-bordered table-hover align-middle">

                                <thead class="table-light">

                                    <tr>

                                        <th style="width:60px;">
                                            #
                                        </th>

                                        <th>
                                            Student Number
                                        </th>

                                        <th>
                                            Student Name
                                        </th>

                                        <th>
                                            Existing Mark
                                        </th>

                                        <th>
                                            Status
                                        </th>

                                        <th style="width:140px;">
                                            Action
                                        </th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach($eligibleStudents as $index => $student)

                                        <tr>

                                            <td>
                                                {{ $index + 1 }}
                                            </td>

                                            <td>
                                                {{ $student->student_number }}
                                            </td>

                                            <td>

                                                <strong>
                                                    {{ $student->first_name }}
                                                    {{ $student->middle_name }}
                                                    {{ $student->last_name }}
                                                </strong>

                                            </td>

                                            <td>

                                                @if($student->existingMark)

                                                    {{ number_format(
                                                        (float) $student->existingMark->score,
                                                        2
                                                    ) }}

                                                    /

                                                    {{ number_format(
                                                        (float) $student->existingMark->maximum_score,
                                                        2
                                                    ) }}

                                                @else

                                                    —

                                                @endif

                                            </td>

                                            <td>

                                                @if(!$student->existingMark)

                                                    <span class="badge bg-info">
                                                        Not Entered
                                                    </span>

                                                @elseif($student->existingMark->status === 'draft')

                                                    <span class="badge bg-secondary">
                                                        Draft
                                                    </span>

                                                @elseif($student->existingMark->status === 'submitted')

                                                    <span class="badge bg-warning">
                                                        Submitted
                                                    </span>

                                                @elseif($student->existingMark->status === 'approved')

                                                    <span class="badge bg-success">
                                                        Approved
                                                    </span>

                                                @endif

                                            </td>

                                            <td>

                                                @if($student->existingMark)

                                                    <a href="{{ route(
                                                        'admin.marks.show',
                                                        $student->existingMark->id
                                                    ) }}"
                                                       class="btn btn-sm btn-outline-primary">

                                                        <i class="bx bx-show"></i>
                                                        View

                                                    </a>

                                                @else

                                                    <a href="{{ route('admin.marks.create', [
                                                        'examination_id' => $selectedExamination->id,
                                                        'schedule_id' => $selectedSchedule->id,
                                                        'student_id' => $student->id,
                                                    ]) }}"
                                                       class="btn btn-sm btn-primary">

                                                        <i class="bx bx-edit"></i>
                                                        Enter

                                                    </a>

                                                @endif

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="alert alert-warning mb-0">

                            <i class="bx bx-info-circle me-1"></i>

                            <strong>
                                No eligible students found.
                            </strong>

                            <p class="mb-2 mt-2">
                                A student must satisfy all of the following
                                conditions before marks can be entered:
                            </p>

                            <ul class="mb-0">

                                <li>
                                    Student is active.
                                </li>

                                <li>
                                    Active enrollment exists.
                                </li>

                                <li>
                                    Academic year matches the examination.
                                </li>

                                <li>
                                    Term matches the examination.
                                </li>

                                <li>
                                    Class matches the exam schedule.
                                </li>

                                <li>
                                    Section matches the exam schedule.
                                </li>

                                <li>
                                    Student is registered for the scheduled course.
                                </li>

                                <li>
                                    Course registration status is
                                    <strong>Enrolled</strong>.
                                </li>

                            </ul>

                        </div>

                    @endif

                </div>

            </div>


            {{-- Marks Entry Form --}}
            @if($selectedStudent)

                <div class="card">

                    <div class="card-header">

                        <h5 class="card-title mb-0">
                            Enter Student Mark
                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="alert alert-info">

                            <strong>
                                Student:
                            </strong>

                            {{ $selectedStudent->first_name }}
                            {{ $selectedStudent->middle_name }}
                            {{ $selectedStudent->last_name }}

                            &nbsp; | &nbsp;

                            <strong>
                                Student Number:
                            </strong>

                            {{ $selectedStudent->student_number }}

                            &nbsp; | &nbsp;

                            <strong>
                                Course:
                            </strong>

                            {{ $selectedSchedule->course?->name }}

                        </div>


                        @if($existingMark)

                            <div class="alert alert-warning">

                                A mark already exists for this student,
                                examination and course.

                                <a href="{{ route(
                                    'admin.marks.show',
                                    $existingMark->id
                                ) }}"
                                   class="alert-link">

                                    View existing mark

                                </a>

                            </div>

                        @else

                            <form method="POST"
                                  action="{{ route('admin.marks.store') }}">

                                @csrf

                                <input type="hidden"
                                       name="examination_id"
                                       value="{{ $selectedExamination->id }}">

                                <input type="hidden"
                                       name="schedule_id"
                                       value="{{ $selectedSchedule->id }}">

                                <input type="hidden"
                                       name="student_id"
                                       value="{{ $selectedStudent->id }}">


                                <div class="row">

                                    <div class="col-md-6">

                                        <label class="form-label">
                                            Score
                                            <span class="text-danger">*</span>
                                        </label>

                                        <input type="number"
                                               name="score"
                                               class="form-control"
                                               min="0"
                                               step="0.01"
                                               value="{{ old('score') }}"
                                               required>

                                        @error('score')
                                            <div class="text-danger mt-1">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                    </div>


                                    <div class="col-md-6">

                                        <label class="form-label">
                                            Maximum Score
                                            <span class="text-danger">*</span>
                                        </label>

                                        <input type="number"
                                               name="maximum_score"
                                               class="form-control"
                                               min="0.01"
                                               step="0.01"
                                               value="{{ old('maximum_score', 100) }}"
                                               required>

                                        @error('maximum_score')
                                            <div class="text-danger mt-1">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                    </div>

                                </div>


                                <div class="mt-4">

                                    <button type="submit"
                                            class="btn btn-success">

                                        <i class="bx bx-save"></i>

                                        Save Mark as Draft

                                    </button>

                                    <a href="{{ route('admin.marks.create', [
                                        'examination_id' => $selectedExamination->id,
                                        'schedule_id' => $selectedSchedule->id,
                                    ]) }}"
                                       class="btn btn-light">

                                        Back to Students

                                    </a>

                                </div>

                            </form>

                        @endif

                    </div>

                </div>

            @endif

        @endif

    </div>

@endsection