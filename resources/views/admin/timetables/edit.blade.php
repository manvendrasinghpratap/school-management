@extends('backend.layout.default')

@section('content')
<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Edit Timetable</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.timetables.index') }}">Timetables</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.timetables.show', $timetable) }}">View</a>
                        </li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Please correct the following:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('admin.timetables.update', $timetable) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">

            {{-- Main Form --}}
            <div class="col-lg-8">

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Timetable Information</h4>
                    </div>

                    <div class="card-body">

                        <div class="row">

                            {{-- Academic Year --}}
                            <div class="col-md-6 mb-3">
                                <label for="academic_year_id" class="form-label">
                                    Academic Year <span class="text-danger">*</span>
                                </label>

                                <select name="academic_year_id"
                                        id="academic_year_id"
                                        class="form-select @error('academic_year_id') is-invalid @enderror"
                                        required>

                                    <option value="">Select Academic Year</option>

                                    @foreach($academicYears as $year)
                                        <option value="{{ $year->id }}"
                                                data-is-current="{{ $year->is_current ? '1' : '0' }}"
                                                data-is-active="{{ $year->is_active ? '1' : '0' }}"
                                            {{ (string) old('academic_year_id', $timetable->academic_year_id) === (string) $year->id ? 'selected' : '' }}>
                                            {{ $year->name }}
                                            @if($year->is_current)
                                                (Current)
                                            @endif
                                        </option>
                                    @endforeach

                                </select>

                                @error('academic_year_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Term --}}
                            <div class="col-md-6 mb-3">
                                <label for="term_id" class="form-label">
                                    Term
                                </label>

                                <select name="term_id"
                                        id="term_id"
                                        class="form-select @error('term_id') is-invalid @enderror">

                                    <option value="">All Terms</option>

                                    @foreach($terms as $term)
                                        <option value="{{ $term->id }}"
                                                data-academic-year="{{ $term->academic_year_id }}"
                                                data-is-current="{{ $term->is_current ? '1' : '0' }}"
                                            {{ (string) old('term_id', $timetable->term_id) === (string) $term->id ? 'selected' : '' }}>
                                            {{ $term->name }}
                                            @if($term->is_current)
                                                (Current)
                                            @endif
                                        </option>
                                    @endforeach

                                </select>

                                @error('term_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Course --}}
                            <div class="col-md-6 mb-3">
                                <label for="course_id" class="form-label">
                                    Course / Subject <span class="text-danger">*</span>
                                </label>

                                <select name="course_id"
                                        id="course_id"
                                        class="form-select @error('course_id') is-invalid @enderror"
                                        required>

                                    <option value="">Select Course / Subject</option>

                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}"
                                            {{ (string) old('course_id', $timetable->course_id) === (string) $course->id ? 'selected' : '' }}>
                                            {{ $course->name }}
                                            @if(!empty($course->course_code))
                                                ({{ $course->course_code }})
                                            @endif
                                        </option>
                                    @endforeach

                                </select>

                                @error('course_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Class --}}
                            <div class="col-md-6 mb-3">
                                <label for="class_id" class="form-label">
                                    Class <span class="text-danger">*</span>
                                </label>

                                <select name="class_id"
                                        id="class_id"
                                        class="form-select @error('class_id') is-invalid @enderror"
                                        required>

                                    <option value="">Select Class</option>

                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}"
                                            {{ (string) old('class_id', $timetable->class_id) === (string) $class->id ? 'selected' : '' }}>
                                            {{ $class->name }}
                                            @if(!empty($class->code))
                                                ({{ $class->code }})
                                            @endif
                                        </option>
                                    @endforeach

                                </select>

                                @error('class_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Section --}}
                            <div class="col-md-6 mb-3">
                                <label for="section_id" class="form-label">
                                    Section
                                </label>

                                <select name="section_id"
                                        id="section_id"
                                        class="form-select @error('section_id') is-invalid @enderror">

                                    <option value="">All Sections</option>

                                    @foreach($classes as $class)
                                        @foreach($class->sections as $section)
                                            <option value="{{ $section->id }}"
                                                    data-class-id="{{ $class->id }}"
                                                {{ (string) old('section_id', $timetable->section_id) === (string) $section->id ? 'selected' : '' }}>
                                                {{ $class->name }} - {{ $section->name }}
                                                @if(!empty($section->code))
                                                    ({{ $section->code }})
                                                @endif
                                            </option>
                                        @endforeach
                                    @endforeach

                                </select>

                                @error('section_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <small class="text-muted">
                                    Leave blank to create a timetable for all sections of the selected class.
                                </small>
                            </div>

                            {{-- Staff --}}
                            <div class="col-md-6 mb-3">
                                <label for="staff_id" class="form-label">
                                    Teacher / Staff
                                </label>

                                <select name="staff_id"
                                        id="staff_id"
                                        class="form-select @error('staff_id') is-invalid @enderror">

                                    <option value="">Select Teacher / Staff</option>

                                    @foreach($staff as $member)
                                        <option value="{{ $member->id }}"
                                            {{ (string) old('staff_id', $timetable->staff_id) === (string) $member->id ? 'selected' : '' }}>
                                            {{ $member->full_name }}
                                            @if(!empty($member->staff_number))
                                                ({{ $member->staff_number }})
                                            @endif
                                        </option>
                                    @endforeach

                                </select>

                                @error('staff_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Day --}}
                            <div class="col-md-6 mb-3">
                                <label for="day_of_week" class="form-label">
                                    Day <span class="text-danger">*</span>
                                </label>

                                <select name="day_of_week"
                                        id="day_of_week"
                                        class="form-select @error('day_of_week') is-invalid @enderror"
                                        required>

                                    @php
                                        $selectedDay = old('day_of_week', $timetable->day_of_week);
                                    @endphp

                                    <option value="">Select Day</option>
                                    <option value="monday" {{ $selectedDay === 'monday' ? 'selected' : '' }}>Monday</option>
                                    <option value="tuesday" {{ $selectedDay === 'tuesday' ? 'selected' : '' }}>Tuesday</option>
                                    <option value="wednesday" {{ $selectedDay === 'wednesday' ? 'selected' : '' }}>Wednesday</option>
                                    <option value="thursday" {{ $selectedDay === 'thursday' ? 'selected' : '' }}>Thursday</option>
                                    <option value="friday" {{ $selectedDay === 'friday' ? 'selected' : '' }}>Friday</option>
                                    <option value="saturday" {{ $selectedDay === 'saturday' ? 'selected' : '' }}>Saturday</option>
                                    <option value="sunday" {{ $selectedDay === 'sunday' ? 'selected' : '' }}>Sunday</option>

                                </select>

                                @error('day_of_week')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Start Time --}}
                            <div class="col-md-6 mb-3">
                                <label for="start_time" class="form-label">
                                    Start Time <span class="text-danger">*</span>
                                </label>

                                <input type="time"
                                       name="start_time"
                                       id="start_time"
                                       class="form-control @error('start_time') is-invalid @enderror"
                                       value="{{ old('start_time', \Carbon\Carbon::parse($timetable->start_time)->format('H:i')) }}"
                                       required>

                                @error('start_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- End Time --}}
                            <div class="col-md-6 mb-3">
                                <label for="end_time" class="form-label">
                                    End Time <span class="text-danger">*</span>
                                </label>

                                <input type="time"
                                       name="end_time"
                                       id="end_time"
                                       class="form-control @error('end_time') is-invalid @enderror"
                                       value="{{ old('end_time', \Carbon\Carbon::parse($timetable->end_time)->format('H:i')) }}"
                                       required>

                                @error('end_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Room --}}
                            <div class="col-md-6 mb-3">
                                <label for="room" class="form-label">
                                    Room
                                </label>

                                <input type="text"
                                       name="room"
                                       id="room"
                                       class="form-control @error('room') is-invalid @enderror"
                                       value="{{ old('room', $timetable->room) }}"
                                       maxlength="100"
                                       placeholder="e.g. Room 101">

                                @error('room')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="card">
                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <a href="{{ route('admin.timetables.show', $timetable) }}"
                               class="btn btn-light">
                                <i class="bx bx-arrow-back me-1"></i>
                                Cancel
                            </a>

                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save me-1"></i>
                                Update Timetable
                            </button>

                        </div>

                    </div>
                </div>

            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Current Schedule</h4>
                    </div>

                    <div class="card-body">

                        <div class="text-center mb-4">
                            <div class="avatar-md mx-auto mb-3">
                                <div class="avatar-title bg-primary-subtle text-primary rounded-circle font-size-24">
                                    <i class="bx bx-calendar-edit"></i>
                                </div>
                            </div>

                            <h5 class="mb-1">
                                {{ $timetable->course->name ?? 'Subject' }}
                            </h5>

                            <p class="text-muted mb-0">
                                {{ $timetable->class->name ?? 'Class' }}
                            </p>
                        </div>

                        <div class="border-top pt-3">

                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Day</span>
                                <strong>
                                    {{ ucfirst($timetable->day_of_week) }}
                                </strong>
                            </div>

                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Time</span>
                                <strong>
                                    {{ \Carbon\Carbon::parse($timetable->start_time)->format('h:i A') }}
                                    -
                                    {{ \Carbon\Carbon::parse($timetable->end_time)->format('h:i A') }}
                                </strong>
                            </div>

                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Section</span>
                                <strong>
                                    {{ $timetable->section->name ?? 'All Sections' }}
                                </strong>
                            </div>

                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Room</span>
                                <strong>
                                    {{ $timetable->room ?: 'Not Assigned' }}
                                </strong>
                            </div>

                        </div>

                    </div>
                </div>

                {{-- Rules --}}
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">
                            <i class="bx bx-info-circle me-1"></i>
                            Timetable Rules
                        </h4>
                    </div>

                    <div class="card-body">
                        <ul class="mb-0 ps-3">
                            <li class="mb-2">
                                Start time must be before end time.
                            </li>
                            <li class="mb-2">
                                A class/section cannot have overlapping lessons.
                            </li>
                            <li class="mb-2">
                                A teacher cannot be scheduled in two places at the same time.
                            </li>
                            <li class="mb-2">
                                A room cannot be assigned to overlapping lessons.
                            </li>
                            <li>
                                Section must belong to the selected class.
                            </li>
                        </ul>
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

    /*
     * Store the original section options so filtering
     * never uses a stale/previously filtered list.
     */
    const originalSectionOptions = Array.from(
        sectionSelect.options
    ).map(function (option) {
        return option.cloneNode(true);
    });

    /*
     * Store the original term options.
     */
    const originalTermOptions = Array.from(
        termSelect.options
    ).map(function (option) {
        return option.cloneNode(true);
    });


    /*
     * Filter Terms according to Academic Year.
     */
    function filterTerms(preferredTermId = null) {

        const academicYearId = academicYearSelect.value;

        const currentTermValue = preferredTermId !== null
            ? String(preferredTermId)
            : String(termSelect.value || '');

        termSelect.innerHTML = '';

        const allTermsOption = document.createElement('option');
        allTermsOption.value = '';
        allTermsOption.textContent = 'All Terms';
        termSelect.appendChild(allTermsOption);

        if (!academicYearId) {
            return;
        }

        let matchedCurrentTerm = false;

        originalTermOptions.forEach(function (originalOption) {

            if (!originalOption.value) {
                return;
            }

            const optionAcademicYear =
                originalOption.getAttribute('data-academic-year');

            if (String(optionAcademicYear) !== String(academicYearId)) {
                return;
            }

            const option = originalOption.cloneNode(true);

            termSelect.appendChild(option);

            if (String(option.value) === currentTermValue) {
                option.selected = true;
                matchedCurrentTerm = true;
            }
        });

        /*
         * If the previous term doesn't belong to the selected
         * academic year, use the current term for that year.
         */
        if (!matchedCurrentTerm) {

            const currentOption = Array.from(
                termSelect.options
            ).find(function (option) {
                return option.getAttribute('data-is-current') === '1';
            });

            if (currentOption) {
                currentOption.selected = true;
            } else {
                termSelect.value = '';
            }
        }
    }


    /*
     * Filter Sections according to Class.
     *
     * IMPORTANT:
     * This always rebuilds the list from the original options.
     * It prevents the stale-section problem we encountered
     * during timetable creation.
     */
    function filterSections(preferredSectionId = null) {

        const classId = classSelect.value;

        sectionSelect.innerHTML = '';

        const allSectionsOption = document.createElement('option');
        allSectionsOption.value = '';
        allSectionsOption.textContent = 'All Sections';
        sectionSelect.appendChild(allSectionsOption);

        if (!classId) {
            return;
        }

        let matchedSection = false;

        originalSectionOptions.forEach(function (originalOption) {

            if (!originalOption.value) {
                return;
            }

            const optionClassId =
                originalOption.getAttribute('data-class-id');

            if (String(optionClassId) !== String(classId)) {
                return;
            }

            const option = originalOption.cloneNode(true);

            sectionSelect.appendChild(option);

            if (
                preferredSectionId !== null &&
                String(option.value) === String(preferredSectionId)
            ) {
                option.selected = true;
                matchedSection = true;
            }
        });

        /*
         * If selected section does not belong to the selected
         * class, reset it to All Sections.
         */
        if (!matchedSection) {
            sectionSelect.value = '';
        }
    }


    /*
     * Initial Academic Year setup.
     *
     * On edit, preserve the timetable's existing academic year.
     * If no value exists, use the current academic year.
     */
    function initializeAcademicYear() {

        if (academicYearSelect.value) {
            filterTerms(termSelect.value);
            return;
        }

        const currentYearOption = Array.from(
            academicYearSelect.options
        ).find(function (option) {
            return option.getAttribute('data-is-current') === '1';
        });

        if (currentYearOption) {
            currentYearOption.selected = true;
            filterTerms(null);
        }
    }


    /*
     * Academic Year changed.
     */
    academicYearSelect.addEventListener('change', function () {

        /*
         * When the academic year changes, the old term may no
         * longer belong to it, so filterTerms will reset it.
         */
        filterTerms(null);

    });


    /*
     * Class changed.
     *
     * IMPORTANT:
     * Never carry the previous section into another class.
     */
    classSelect.addEventListener('change', function () {

        filterSections(null);

    });


    /*
     * Initial page setup.
     */
    filterSections(sectionSelect.value);
    initializeAcademicYear();

});
</script>
@endpush