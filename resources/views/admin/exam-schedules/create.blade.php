@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Title --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0">Create Exam Schedule</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">

                        <li class="breadcrumb-item">
                            <a href="{{ url('/admin/dashboard') }}">
                                Dashboard
                            </a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.exam-schedules.index') }}">
                                Exam Schedules
                            </a>
                        </li>

                        <li class="breadcrumb-item active">
                            Create
                        </li>

                    </ol>
                </div>

            </div>
        </div>
    </div>


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show" role="alert">

            <strong>Please correct the following errors:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close">
            </button>

        </div>

    @endif


    {{-- Create Form --}}
    <div class="row">

        <div class="col-xl-10 col-lg-12">

            <div class="card">

                {{-- Card Header --}}
                <div class="card-header">

                    <h5 class="card-title mb-1">
                        Exam Schedule Information
                    </h5>

                    <p class="text-muted mb-0">
                        Assign a course to an examination, class and optional section.
                    </p>

                </div>


                {{-- Card Body --}}
                <div class="card-body">

                    <form method="POST"
                          action="{{ route('admin.exam-schedules.store') }}">

                        @csrf


                        <div class="row g-3">


                            {{-- ========================================================= --}}
                            {{-- Examination --}}
                            {{-- ========================================================= --}}

                            <div class="col-md-6">

                                <label for="examination_id"
                                       class="form-label">

                                    Examination

                                    <span class="text-danger">
                                        *
                                    </span>

                                </label>


                                <select name="examination_id"
                                        id="examination_id"
                                        class="form-select @error('examination_id') is-invalid @enderror"
                                        required>

                                    <option value="">
                                        Select Examination
                                    </option>


                                    @foreach($examinations as $examination)

                                        <option value="{{ $examination->id }}"
                                                data-start-date="{{ $examination->start_date?->format('Y-m-d') }}"
                                                data-end-date="{{ $examination->end_date?->format('Y-m-d') }}"
                                            {{ old('examination_id') == $examination->id ? 'selected' : '' }}>

                                            {{ $examination->name }}

                                            @if($examination->start_date)

                                                —
                                                {{ $examination->start_date->format('d M Y') }}

                                                @if($examination->end_date)

                                                    to
                                                    {{ $examination->end_date->format('d M Y') }}

                                                @endif

                                            @endif

                                        </option>

                                    @endforeach

                                </select>


                                @error('examination_id')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror


                                <small id="exam-date-help"
                                       class="text-muted d-block mt-1">

                                    Select an examination first.

                                </small>

                            </div>


                            {{-- ========================================================= --}}
                            {{-- Course --}}
                            {{-- ========================================================= --}}

                            <div class="col-md-6">

                                <label for="course_id"
                                       class="form-label">

                                    Course

                                    <span class="text-danger">
                                        *
                                    </span>

                                </label>


                                <select name="course_id"
                                        id="course_id"
                                        class="form-select @error('course_id') is-invalid @enderror"
                                        required>

                                    <option value="">
                                        Select Course
                                    </option>


                                    @foreach($courses as $course)

                                        <option value="{{ $course->id }}"
                                            {{ old('course_id') == $course->id ? 'selected' : '' }}>

                                            {{ $course->course_code }}
                                            —
                                            {{ $course->name }}

                                        </option>

                                    @endforeach

                                </select>


                                @error('course_id')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- ========================================================= --}}
                            {{-- Class --}}
                            {{-- ========================================================= --}}

                            <div class="col-md-6">

                                <label for="class_id"
                                       class="form-label">

                                    Class

                                    <span class="text-danger">
                                        *
                                    </span>

                                </label>


                                <select name="class_id"
                                        id="class_id"
                                        class="form-select @error('class_id') is-invalid @enderror"
                                        required>

                                    <option value="">
                                        Select Class
                                    </option>


                                    @foreach($classes as $class)

                                        <option value="{{ $class->id }}"
                                            {{ old('class_id') == $class->id ? 'selected' : '' }}>

                                            {{ $class->name }}

                                            @if($class->code)

                                                ({{ $class->code }})

                                            @endif

                                        </option>

                                    @endforeach

                                </select>


                                @error('class_id')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- ========================================================= --}}
                            {{-- Section --}}
                            {{-- ========================================================= --}}

                            <div class="col-md-6">

                                <label for="section_id"
                                       class="form-label">

                                    Section

                                </label>


                                <select name="section_id"
                                        id="section_id"
                                        class="form-select @error('section_id') is-invalid @enderror">

                                    <option value="">
                                        All Sections
                                    </option>

                                </select>


                                @error('section_id')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror


                                <small class="text-muted">

                                    Leave as "All Sections" if this schedule applies
                                    to the whole class.

                                </small>

                            </div>


                            {{-- ========================================================= --}}
                            {{-- Exam Date --}}
                            {{-- ========================================================= --}}

                            <div class="col-md-4">

                                <label for="exam_date"
                                       class="form-label">

                                    Exam Date

                                    <span class="text-danger">
                                        *
                                    </span>

                                </label>


                                <input type="date"
                                       name="exam_date"
                                       id="exam_date"
                                       class="form-control @error('exam_date') is-invalid @enderror"
                                       value="{{ old('exam_date') }}"
                                       required>


                                @error('exam_date')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- ========================================================= --}}
                            {{-- Start Time --}}
                            {{-- ========================================================= --}}

                            <div class="col-md-4">

                                <label for="start_time"
                                       class="form-label">

                                    Start Time

                                </label>


                                <input type="time"
                                       name="start_time"
                                       id="start_time"
                                       class="form-control @error('start_time') is-invalid @enderror"
                                       value="{{ old('start_time') }}">


                                @error('start_time')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- ========================================================= --}}
                            {{-- End Time --}}
                            {{-- ========================================================= --}}

                            <div class="col-md-4">

                                <label for="end_time"
                                       class="form-label">

                                    End Time

                                </label>


                                <input type="time"
                                       name="end_time"
                                       id="end_time"
                                       class="form-control @error('end_time') is-invalid @enderror"
                                       value="{{ old('end_time') }}">


                                @error('end_time')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- ========================================================= --}}
                            {{-- Room --}}
                            {{-- ========================================================= --}}

                            <div class="col-md-6">

                                <label for="room"
                                       class="form-label">

                                    Examination Room

                                </label>


                                <input type="text"
                                       name="room"
                                       id="room"
                                       class="form-control @error('room') is-invalid @enderror"
                                       value="{{ old('room') }}"
                                       maxlength="100"
                                       placeholder="e.g. Room 101">


                                @error('room')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>

                        </div>


                        {{-- ========================================================= --}}
                        {{-- Information --}}
                        {{-- ========================================================= --}}

                        <div class="alert alert-info mt-4 mb-0">

                            <div class="d-flex align-items-start">

                                <div class="me-2">

                                    <i class="ri-information-line fs-4"></i>

                                </div>


                                <div>

                                    <strong>
                                        Schedule Information
                                    </strong>


                                    <p class="mb-0 mt-1">

                                        The exam date must fall within the selected
                                        examination's start and end dates when those
                                        dates are defined.

                                    </p>

                                </div>

                            </div>

                        </div>


                        {{-- ========================================================= --}}
                        {{-- Buttons --}}
                        {{-- ========================================================= --}}

                        <div class="d-flex justify-content-end gap-2 mt-4">

                            <a href="{{ route('admin.exam-schedules.index') }}"
                               class="btn btn-light">

                                <i class="ri-arrow-left-line me-1"></i>

                                Cancel

                            </a>


                            <button type="submit"
                                    class="btn btn-primary">

                                <i class="ri-save-line me-1"></i>

                                Save Exam Schedule

                            </button>

                        </div>


                    </form>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- ========================================================================== --}}
{{-- JavaScript --}}
{{-- ========================================================================== --}}

<script>

document.addEventListener('DOMContentLoaded', function () {


    /*
    |--------------------------------------------------------------------------
    | Elements
    |--------------------------------------------------------------------------
    */

    const classSelect =
        document.getElementById('class_id');

    const sectionSelect =
        document.getElementById('section_id');

    const examinationSelect =
        document.getElementById('examination_id');

    const examDateInput =
        document.getElementById('exam_date');

    const examDateHelp =
        document.getElementById('exam-date-help');


    /*
    |--------------------------------------------------------------------------
    | Class -> Sections Data
    |--------------------------------------------------------------------------
    |
    | Build a simple JavaScript object from the classes and their sections.
    |
    */

    const classSections = {};


    @foreach($classes as $class)

        classSections[{{ $class->id }}] = [];


        @foreach($class->sections->where('is_active', true) as $section)

            classSections[{{ $class->id }}].push({

                id: {{ $section->id }},

                name: @json($section->name),

                code: @json($section->code)

            });

        @endforeach

    @endforeach


    /*
    |--------------------------------------------------------------------------
    | Old Section ID
    |--------------------------------------------------------------------------
    |
    | This is used when Laravel redirects back after a validation error.
    |
    */

    const oldSectionId =
        @json(old('section_id'));


    /*
    |--------------------------------------------------------------------------
    | Load Sections
    |--------------------------------------------------------------------------
    */

    function loadSections(selectedSectionId = null)
    {

        const classId =
            classSelect.value;


        /*
        | Reset the dropdown.
        */

        sectionSelect.innerHTML = '';


        /*
        | Default option.
        */

        const allOption =
            document.createElement('option');

        allOption.value = '';

        allOption.textContent =
            'All Sections';

        sectionSelect.appendChild(allOption);


        /*
        | No class selected.
        */

        if (!classId)
        {

            sectionSelect.disabled = true;

            return;

        }


        /*
        | Find sections for selected class.
        */

        const sections =
            classSections[classId] || [];


        /*
        | No sections exist for this class.
        */

        if (sections.length === 0)
        {

            sectionSelect.disabled = true;

            return;

        }


        /*
        | Enable section dropdown.
        */

        sectionSelect.disabled = false;


        /*
        | Populate sections.
        */

        sections.forEach(function (section)
        {

            const option =
                document.createElement('option');


            option.value =
                section.id;


            /*
            | Display section code when available.
            */

            if (section.code)
            {

                option.textContent =
                    section.name +
                    ' (' +
                    section.code +
                    ')';

            }
            else
            {

                option.textContent =
                    section.name;

            }


            /*
            | Restore old selected section.
            */

            if (
                selectedSectionId !== null &&
                String(selectedSectionId) ===
                String(section.id)
            )
            {

                option.selected = true;

            }


            sectionSelect.appendChild(option);

        });

    }


    /*
    |--------------------------------------------------------------------------
    | Examination Date Range
    |--------------------------------------------------------------------------
    */

    function updateExamDateHelp()
    {

        const selectedOption =
            examinationSelect.options[
                examinationSelect.selectedIndex
            ];


        /*
        | No examination selected.
        */

        if (
            !selectedOption ||
            !selectedOption.value
        )
        {

            examDateHelp.textContent =
                'Select an examination first.';


            examDateInput.removeAttribute('min');

            examDateInput.removeAttribute('max');

            return;

        }


        /*
        | Get examination date range.
        */

        const startDate =
            selectedOption.dataset.startDate || '';


        const endDate =
            selectedOption.dataset.endDate || '';


        /*
        | Set minimum date.
        */

        if (startDate)
        {

            examDateInput.setAttribute(
                'min',
                startDate
            );

        }
        else
        {

            examDateInput.removeAttribute('min');

        }


        /*
        | Set maximum date.
        */

        if (endDate)
        {

            examDateInput.setAttribute(
                'max',
                endDate
            );

        }
        else
        {

            examDateInput.removeAttribute('max');

        }


        /*
        | Display date range information.
        */

        if (
            startDate &&
            endDate
        )
        {

            examDateHelp.textContent =
                'Valid examination dates: ' +
                startDate +
                ' to ' +
                endDate +
                '.';

        }
        else if (startDate)
        {

            examDateHelp.textContent =
                'Exam date cannot be before ' +
                startDate +
                '.';

        }
        else if (endDate)
        {

            examDateHelp.textContent =
                'Exam date cannot be after ' +
                endDate +
                '.';

        }
        else
        {

            examDateHelp.textContent =
                'No examination date range has been defined.';

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Class Change
    |--------------------------------------------------------------------------
    */

    classSelect.addEventListener(
        'change',
        function ()
        {

            /*
            | Reset the section whenever class changes.
            |
            | This prevents a section belonging to the previous
            | class from remaining selected.
            */

            loadSections(null);

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Examination Change
    |--------------------------------------------------------------------------
    */

    examinationSelect.addEventListener(
        'change',
        function ()
        {

            updateExamDateHelp();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Initial Page Setup
    |--------------------------------------------------------------------------
    */

    loadSections(oldSectionId);

    updateExamDateHelp();


});

</script>

@endsection