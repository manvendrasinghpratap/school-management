@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <div>
                    <h4 class="mb-sm-0 font-size-18">
                        Create Timetable
                    </h4>

                    <p class="text-muted mb-0">
                        Create a class timetable entry for the selected academic period.
                    </p>
                </div>

                <div class="page-title-right">
                    <a href="{{ route('admin.timetables.index') }}"
                       class="btn btn-light">
                        <i class="bx bx-arrow-back me-1"></i>
                        Back to Timetable
                    </a>
                </div>

            </div>
        </div>
    </div>

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">

            <div class="fw-semibold mb-1">
                Please correct the following errors:
            </div>

            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"></button>

        </div>
    @endif

    <form method="POST"
          action="{{ route('admin.timetables.store') }}"
          id="timetableForm">

        @csrf

        <div class="row">

            {{-- Main Form --}}
            <div class="col-xl-8">

                {{-- Academic Setup --}}
                <div class="card">

                    <div class="card-header">
                        <h4 class="card-title mb-1">
                            Academic Setup
                        </h4>

                        <p class="card-title-desc mb-0">
                            Select the academic year and term for this timetable.
                        </p>
                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            {{-- Academic Year --}}
                            <div class="col-md-6">

                                <label for="academic_year_id"
                                       class="form-label">
                                    Academic Year
                                    <span class="text-danger">*</span>
                                </label>

                                <select name="academic_year_id"
                                        id="academic_year_id"
                                        class="form-select @error('academic_year_id') is-invalid @enderror"
                                        required>

                                    <option value="">
                                        Select Academic Year
                                    </option>

                                    @foreach($academicYears as $year)

                                        <option value="{{ $year->id }}"
                                            data-is-current="{{ $year->is_current ? '1' : '0' }}"
                                            data-is-active="{{ $year->is_active ? '1' : '0' }}"
                                            @selected(old('academic_year_id') == $year->id)>
                                            {{ $year->name }}
                                        </option>

                                    @endforeach

                                </select>

                                @error('academic_year_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <div class="form-text">
                                    The current academic year is selected automatically.
                                </div>

                            </div>

                            {{-- Term --}}
                            <div class="col-md-6">

                                <label for="term_id"
                                       class="form-label">
                                    Term
                                    <span class="text-muted">
                                        (Optional)
                                    </span>
                                </label>

                                <select name="term_id"
                                        id="term_id"
                                        class="form-select @error('term_id') is-invalid @enderror">

                                    <option value="">
                                        All Terms
                                    </option>

                                    @foreach($terms as $term)

                                        <option value="{{ $term->id }}"
                                                data-academic-year="{{ $term->academic_year_id }}"
                                                data-is-current="{{ $term->is_current ?? 0 }}"
                                            @selected(old('term_id') == $term->id)>
                                            {{ $term->name }}
                                        </option>

                                    @endforeach

                                </select>

                                @error('term_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <div class="form-text">
                                    The current term is selected automatically. Leave blank if this applies to all terms.
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                {{-- Class & Course --}}
                <div class="card">

                    <div class="card-header">
                        <h4 class="card-title mb-1">
                            Class & Subject
                        </h4>

                        <p class="card-title-desc mb-0">
                            Select the subject and class receiving this timetable entry.
                        </p>
                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            {{-- Course --}}
                            <div class="col-md-6">

                                <label for="course_id"
                                       class="form-label">
                                    Course / Subject
                                    <span class="text-danger">*</span>
                                </label>

                                <select name="course_id"
                                        id="course_id"
                                        class="form-select @error('course_id') is-invalid @enderror"
                                        required>

                                    <option value="">
                                        Select Course / Subject
                                    </option>

                                    @foreach($courses as $course)

                                        <option value="{{ $course->id }}"
                                            @selected(old('course_id') == $course->id)>

                                            {{ $course->name }}

                                            @if($course->course_code)
                                                — {{ $course->course_code }}
                                            @endif

                                        </option>

                                    @endforeach

                                </select>

                                @error('course_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            {{-- Class --}}
                            <div class="col-md-6">

                                <label for="class_id"
                                       class="form-label">
                                    Class
                                    <span class="text-danger">*</span>
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
                                            @selected(old('class_id') == $class->id)>

                                            {{ $class->name }}

                                            @if($class->code)
                                                — {{ $class->code }}
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

                            {{-- Section --}}
                            <div class="col-md-6">

                                <label for="section_id"
                                       class="form-label">
                                    Section
                                    <span class="text-muted">
                                        (Optional)
                                    </span>
                                </label>

                                <select name="section_id"
                                        id="section_id"
                                        class="form-select @error('section_id') is-invalid @enderror">

                                    <option value="">
                                        All Sections
                                    </option>

                                    @foreach($classes as $class)

                                        @foreach($class->sections as $section)

                                            <option value="{{ $section->id }}"
                                                    data-class-id="{{ $class->id }}">

                                                {{ $class->name }} — {{ $section->name }}

                                                @if($section->code)
                                                    ({{ $section->code }})
                                                @endif

                                            </option>

                                        @endforeach

                                    @endforeach

                                </select>

                                @error('section_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <div class="form-text">
                                    Leave blank if this applies to all sections of the class.
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                {{-- Teacher --}}
                <div class="card">

                    <div class="card-header">
                        <h4 class="card-title mb-1">
                            Teacher Assignment
                        </h4>

                        <p class="card-title-desc mb-0">
                            Assign the staff member responsible for this lesson.
                        </p>
                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-md-8">

                                <label for="staff_id"
                                       class="form-label">
                                    Teacher / Staff
                                    <span class="text-muted">
                                        (Optional)
                                    </span>
                                </label>

                                <select name="staff_id"
                                        id="staff_id"
                                        class="form-select @error('staff_id') is-invalid @enderror">

                                    <option value="">
                                        Not Assigned
                                    </option>

                                    @foreach($staff as $teacher)

                                        <option value="{{ $teacher->id }}"
                                            @selected(old('staff_id') == $teacher->id)>

                                            {{ $teacher->full_name }}

                                            @if($teacher->staff_number)
                                                — {{ $teacher->staff_number }}
                                            @endif

                                        </option>

                                    @endforeach

                                </select>

                                @error('staff_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                    </div>

                </div>

                {{-- Schedule --}}
                <div class="card">

                    <div class="card-header">
                        <h4 class="card-title mb-1">
                            Schedule
                        </h4>

                        <p class="card-title-desc mb-0">
                            Define the day and time for this lesson.
                        </p>
                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            {{-- Day --}}
                            <div class="col-md-4">

                                <label for="day_of_week"
                                       class="form-label">
                                    Day
                                    <span class="text-danger">*</span>
                                </label>

                                <select name="day_of_week"
                                        id="day_of_week"
                                        class="form-select @error('day_of_week') is-invalid @enderror"
                                        required>

                                    <option value="">
                                        Select Day
                                    </option>

                                    @foreach([
                                        'monday' => 'Monday',
                                        'tuesday' => 'Tuesday',
                                        'wednesday' => 'Wednesday',
                                        'thursday' => 'Thursday',
                                        'friday' => 'Friday',
                                        'saturday' => 'Saturday',
                                        'sunday' => 'Sunday',
                                    ] as $value => $label)

                                        <option value="{{ $value }}"
                                            @selected(old('day_of_week') === $value)>
                                            {{ $label }}
                                        </option>

                                    @endforeach

                                </select>

                                @error('day_of_week')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            {{-- Start --}}
                            <div class="col-md-4">

                                <label for="start_time"
                                       class="form-label">
                                    Start Time
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="time"
                                       name="start_time"
                                       id="start_time"
                                       class="form-control @error('start_time') is-invalid @enderror"
                                       value="{{ old('start_time') }}"
                                       required>

                                @error('start_time')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            {{-- End --}}
                            <div class="col-md-4">

                                <label for="end_time"
                                       class="form-label">
                                    End Time
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="time"
                                       name="end_time"
                                       id="end_time"
                                       class="form-control @error('end_time') is-invalid @enderror"
                                       value="{{ old('end_time') }}"
                                       required>

                                @error('end_time')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                    </div>

                </div>

                {{-- Room --}}
                <div class="card">

                    <div class="card-header">
                        <h4 class="card-title mb-1">
                            Location
                        </h4>

                        <p class="card-title-desc mb-0">
                            Specify where the lesson will take place.
                        </p>
                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-md-8">

                                <label for="room"
                                       class="form-label">
                                    Room
                                    <span class="text-muted">
                                        (Optional)
                                    </span>
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

                    </div>

                </div>

            </div>

            {{-- Sidebar --}}
            <div class="col-xl-4">

                {{-- Information --}}
                <div class="card">

                    <div class="card-header">
                        <h4 class="card-title mb-0">
                            Timetable Rules
                        </h4>
                    </div>

                    <div class="card-body">

                        <div class="d-flex mb-3">

                            <div class="flex-shrink-0 me-3">
                                <i class="bx bx-check-circle text-success font-size-20"></i>
                            </div>

                            <div>
                                <h6 class="mb-1">
                                    Valid Time
                                </h6>

                                <p class="text-muted mb-0 small">
                                    End time must be later than start time.
                                </p>
                            </div>

                        </div>

                        <div class="d-flex mb-3">

                            <div class="flex-shrink-0 me-3">
                                <i class="bx bx-user-x text-danger font-size-20"></i>
                            </div>

                            <div>
                                <h6 class="mb-1">
                                    Teacher Conflicts
                                </h6>

                                <p class="text-muted mb-0 small">
                                    A teacher cannot teach two overlapping lessons.
                                </p>
                            </div>

                        </div>

                        <div class="d-flex mb-3">

                            <div class="flex-shrink-0 me-3">
                                <i class="bx bx-building-house text-warning font-size-20"></i>
                            </div>

                            <div>
                                <h6 class="mb-1">
                                    Room Conflicts
                                </h6>

                                <p class="text-muted mb-0 small">
                                    A room cannot be assigned to overlapping lessons.
                                </p>
                            </div>

                        </div>

                        <div class="d-flex">

                            <div class="flex-shrink-0 me-3">
                                <i class="bx bx-group text-primary font-size-20"></i>
                            </div>

                            <div>
                                <h6 class="mb-1">
                                    Class Conflicts
                                </h6>

                                <p class="text-muted mb-0 small">
                                    A class or section cannot have two overlapping lessons.
                                </p>
                            </div>

                        </div>

                    </div>

                </div>

                {{-- Selected Schedule Preview --}}
                <div class="card">

                    <div class="card-header">
                        <h4 class="card-title mb-0">
                            Schedule Preview
                        </h4>
                    </div>

                    <div class="card-body">

                        <div class="text-center py-4">

                            <div class="mb-3">
                                <i class="bx bx-calendar-event font-size-48 text-muted"></i>
                            </div>

                            <h5 id="preview-course">
                                Select timetable details
                            </h5>

                            <p class="text-muted mb-1"
                               id="preview-class">
                                Class not selected
                            </p>

                            <p class="text-muted mb-1"
                               id="preview-day">
                                Day not selected
                            </p>

                            <p class="text-muted mb-0"
                               id="preview-time">
                                Time not selected
                            </p>

                        </div>

                    </div>

                </div>

                {{-- Actions --}}
                <div class="card">

                    <div class="card-body">

                        <div class="d-grid gap-2">

                            <button type="submit"
                                    class="btn btn-primary"
                                    id="submitButton">

                                <i class="bx bx-save me-1"></i>
                                Create Timetable

                            </button>

                            <a href="{{ route('admin.timetables.index') }}"
                               class="btn btn-light">
                                Cancel
                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection


@push('script')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const academicYearSelect = document.getElementById('academic_year_id');
    const termSelect = document.getElementById('term_id');

    const classSelect = document.getElementById('class_id');
    const sectionSelect = document.getElementById('section_id');

    const courseSelect = document.getElementById('course_id');
    const daySelect = document.getElementById('day_of_week');

    const startTime = document.getElementById('start_time');
    const endTime = document.getElementById('end_time');

    const previewCourse = document.getElementById('preview-course');
    const previewClass = document.getElementById('preview-class');
    const previewDay = document.getElementById('preview-day');
    const previewTime = document.getElementById('preview-time');

    /*
     * Remember the values generated by Laravel after validation failure.
     * These values must be preserved while the JavaScript filters the
     * Academic Year / Term / Class / Section dropdowns.
     */
    const oldAcademicYearId = @json(old('academic_year_id'));
    const oldTermId = @json(old('term_id'));
    const oldClassId = @json(old('class_id'));
    const oldSectionId = @json(old('section_id'));

    /*
     * Keep the original options in memory.
     */
    const originalTermOptions = Array.from(termSelect.options).map(function (option) {
        return option.cloneNode(true);
    });

    const originalSectionOptions = Array.from(sectionSelect.options).map(function (option) {
        return option.cloneNode(true);
    });

    /*
     * ---------------------------------------------------------
     * CURRENT ACADEMIC YEAR
     * ---------------------------------------------------------
     *
     * Priority:
     *
     * 1. Old submitted value after validation failure
     * 2. Current active academic year
     * 3. First available academic year
     */
    function selectCurrentAcademicYear() {

        if (oldAcademicYearId) {
            academicYearSelect.value = String(oldAcademicYearId);

            if (academicYearSelect.value === String(oldAcademicYearId)) {
                return;
            }
        }

        let currentYearOption = Array.from(
            academicYearSelect.options
        ).find(function (option) {

            return option.value &&
                   option.dataset.isCurrent === '1' &&
                   option.dataset.isActive === '1';

        });

        if (!currentYearOption) {

            currentYearOption = Array.from(
                academicYearSelect.options
            ).find(function (option) {

                return option.value &&
                       option.dataset.isCurrent === '1';

            });

        }

        if (!currentYearOption) {

            currentYearOption = Array.from(
                academicYearSelect.options
            ).find(function (option) {

                return option.value;

            });

        }

        if (currentYearOption) {
            academicYearSelect.value = currentYearOption.value;
        }
    }

    /*
     * ---------------------------------------------------------
     * TERM FILTER
     * ---------------------------------------------------------
     */
    function filterTerms(preferredTermId = null) {

        const academicYearId = academicYearSelect.value;

        const previousValue =
            preferredTermId !== null
                ? String(preferredTermId)
                : String(termSelect.value || '');

        termSelect.innerHTML = '';

        const allTermsOption = document.createElement('option');

        allTermsOption.value = '';
        allTermsOption.textContent = 'All Terms';

        termSelect.appendChild(allTermsOption);

        originalTermOptions.forEach(function (option) {

            if (!option.value) {
                return;
            }

            if (
                academicYearId &&
                option.dataset.academicYear === String(academicYearId)
            ) {

                termSelect.appendChild(
                    option.cloneNode(true)
                );

            }

        });

        /*
         * If validation failed, restore the submitted term.
         */
        if (previousValue) {

            const oldOption = Array.from(
                termSelect.options
            ).find(function (option) {

                return option.value === previousValue;

            });

            if (oldOption) {
                termSelect.value = previousValue;
                return;
            }
        }

        /*
         * Otherwise automatically select the current term.
         */
        const currentTermOption = Array.from(
            termSelect.options
        ).find(function (option) {

            return option.value &&
                   option.dataset.isCurrent === '1';

        });

        if (currentTermOption) {
            termSelect.value = currentTermOption.value;
        } else {
            termSelect.value = '';
        }
    }

    /*
     * ---------------------------------------------------------
     * SECTION FILTER
     * ---------------------------------------------------------
     *
     * IMPORTANT:
     * The option value is ALWAYS the actual sections.id.
     *
     * Example:
     *
     * JSS 1 - A -> value="7"
     * JSS 1 - B -> value="8"
     *
     * This prevents the class/section mismatch problem.
     */
    function filterSections(preferredSectionId = null) {

    const classId = String(classSelect.value || '');

    sectionSelect.innerHTML = '';

    const allSectionsOption = document.createElement('option');

    allSectionsOption.value = '';
    allSectionsOption.textContent = 'All Sections';

    sectionSelect.appendChild(allSectionsOption);

    /*
     * No class selected.
     */
    if (!classId) {
        sectionSelect.value = '';
        return;
    }

    /*
     * Add ONLY sections belonging to the selected class.
     */
    originalSectionOptions.forEach(function (option) {

        if (!option.value) {
            return;
        }

        if (String(option.dataset.classId) !== classId) {
            return;
        }

        const clonedOption = option.cloneNode(true);

        /*
         * Always use the actual database section ID.
         */
        clonedOption.value = String(option.value);

        sectionSelect.appendChild(clonedOption);

    });

    /*
     * Only restore a preferred section if that section
     * actually exists in the newly filtered list.
     */
    if (preferredSectionId !== null && preferredSectionId !== '') {

        const preferredValue = String(preferredSectionId);

        const matchingOption = Array.from(
            sectionSelect.options
        ).find(function (option) {

            return String(option.value) === preferredValue;

        });

        if (matchingOption) {
            sectionSelect.value = preferredValue;
            return;
        }
    }

    /*
     * IMPORTANT:
     * Never carry a section from another class.
     */
    sectionSelect.value = '';
}

    /*
     * ---------------------------------------------------------
     * PREVIEW
     * ---------------------------------------------------------
     */
    function updatePreview() {

        const courseText =
            courseSelect.options[
                courseSelect.selectedIndex
            ]?.text || 'Select timetable details';

        const classText =
            classSelect.options[
                classSelect.selectedIndex
            ]?.text || 'Class not selected';

        const sectionText =
            sectionSelect.options[
                sectionSelect.selectedIndex
            ]?.text || '';

        const dayText =
            daySelect.options[
                daySelect.selectedIndex
            ]?.text || 'Day not selected';

        const start = startTime.value;
        const end = endTime.value;

        previewCourse.textContent =
            courseSelect.value
                ? courseText.trim()
                : 'Select timetable details';

        if (classSelect.value) {

            previewClass.textContent =
                sectionSelect.value
                    ? classText.trim() + ' — ' + sectionText.trim()
                    : classText.trim();

        } else {

            previewClass.textContent =
                'Class not selected';

        }

        previewDay.textContent =
            daySelect.value
                ? dayText
                : 'Day not selected';

        if (start && end) {

            previewTime.textContent =
                formatTime(start) + ' - ' + formatTime(end);

        } else {

            previewTime.textContent =
                'Time not selected';

        }
    }

    /*
     * ---------------------------------------------------------
     * FORMAT TIME
     * ---------------------------------------------------------
     */
    function formatTime(value) {

        const parts = value.split(':');

        let hour = parseInt(parts[0], 10);
        const minute = parts[1];

        const suffix = hour >= 12
            ? 'PM'
            : 'AM';

        hour = hour % 12;

        if (hour === 0) {
            hour = 12;
        }

        return hour + ':' + minute + ' ' + suffix;
    }

    /*
     * ---------------------------------------------------------
     * EVENTS
     * ---------------------------------------------------------
     */
    academicYearSelect.addEventListener(
        'change',
        function () {

            filterTerms();

        }
    );

    classSelect.addEventListener(
    'change',
    function () {

        /*
         * A section belongs to a specific class.
         * Whenever the class changes, rebuild the section list
         * and DO NOT carry the previous class's section across.
         */
        filterSections(null);

        updatePreview();

    }
);

    sectionSelect.addEventListener(
        'change',
        function () {

            updatePreview();

        }
    );

    courseSelect.addEventListener(
        'change',
        function () {

            updatePreview();

        }
    );

    daySelect.addEventListener(
        'change',
        function () {

            updatePreview();

        }
    );

    startTime.addEventListener(
        'change',
        function () {

            updatePreview();

        }
    );

    endTime.addEventListener(
        'change',
        function () {

            updatePreview();

        }
    );

    /*
     * ---------------------------------------------------------
     * INITIALIZE FORM
     * ---------------------------------------------------------
     */

    /*
     * 1. Automatically select current Academic Year.
     */
    selectCurrentAcademicYear();

    /*
     * 2. Filter Terms for selected Academic Year.
     *
     *    Old submitted term takes priority.
     *    Otherwise current term is selected.
     */
    filterTerms(oldTermId);

    /*
     * 3. Filter Sections for selected Class.
     *
     *    Old submitted section takes priority.
     */
    filterSections(oldSectionId);

    /*
     * 4. Update preview.
     */
    updatePreview();

});
</script>

@endpush