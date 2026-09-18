@extends('backend.layout.default')

@section('title', 'Edit Library Member')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>
            <h4 class="mb-1">Edit Library Member</h4>

            <p class="text-muted mb-0">
                Update the library membership information.
            </p>
        </div>

        <a href="{{ route('admin.library.members.index') }}"
           class="btn btn-light">

            <i class="bx bx-arrow-back me-1"></i>
            Back to Members

        </a>

    </div>


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show"
             role="alert">

            <strong>Please correct the following:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>

        </div>

    @endif


    <form method="POST"
          action="{{ route('admin.library.members.update', $member) }}"
          id="libraryMemberForm">

        @csrf
        @method('PUT')


        <div class="card">

            <div class="card-header">

                <h5 class="card-title mb-0">
                    Member Information
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    {{-- Member Number --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Member Number
                        </label>

                        <input type="text"
                               class="form-control"
                               value="{{ $member->member_number }}"
                               readonly>

                    </div>


                    {{-- Holder Type --}}
                    <div class="col-md-4">

                        <label for="holder_type"
                               class="form-label">

                            Holder Type
                            <span class="text-danger">*</span>

                        </label>

                        <select name="holder_type"
                                id="holder_type"
                                class="form-select @error('holder_type') is-invalid @enderror"
                                required>

                            <option value="student"
                                @selected(
                                    old('holder_type', $holderType) === 'student'
                                )>
                                Student
                            </option>

                            <option value="staff"
                                @selected(
                                    old('holder_type', $holderType) === 'staff'
                                )>
                                Staff
                            </option>

                        </select>

                        @error('holder_type')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Current holder --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Current Holder
                        </label>

                        <input type="text"
                               class="form-control"
                               value="{{ $member->student
                                    ? trim(
                                        $member->student->first_name . ' ' .
                                        ($member->student->middle_name ?? '') . ' ' .
                                        $member->student->last_name
                                    )
                                    : ($member->staff
                                        ? trim(
                                            $member->staff->first_name . ' ' .
                                            ($member->staff->middle_name ?? '') . ' ' .
                                            $member->staff->last_name
                                        )
                                        : '—') }}"
                               readonly>

                    </div>


                    {{-- =====================================================
                         STUDENT ACADEMIC PLACEMENT
                    ====================================================== --}}
                    <div class="col-12"
                         id="student_box">

                        <div class="border rounded p-3">

                            <div class="mb-3">

                                <h6 class="mb-1">
                                    Student Academic Placement
                                </h6>

                                <small class="text-muted">
                                    Select the student's active academic year,
                                    class and section to load eligible students.
                                </small>

                            </div>


                            <div class="row g-3">


                                {{-- Academic Year --}}
                                <div class="col-md-4">

                                    <label for="academic_year_id"
                                           class="form-label">

                                        Academic Year
                                        <span class="text-danger">*</span>

                                    </label>

                                    <select name="academic_year_id"
                                            id="academic_year_id"
                                            class="form-select @error('academic_year_id') is-invalid @enderror">

                                        <option value="">
                                            Select Academic Year
                                        </option>

                                        @foreach($academicYears as $academicYear)

                                            @php
                                                $selectedAcademicYear =
                                                    old(
                                                        'academic_year_id',
                                                        optional($studentEnrollment)->academic_year_id
                                                    );
                                            @endphp

                                            <option value="{{ $academicYear->id }}"
                                                @selected(
                                                    (string) $selectedAcademicYear ===
                                                    (string) $academicYear->id
                                                )>

                                                {{ $academicYear->name }}

                                            </option>

                                        @endforeach

                                    </select>

                                    @error('academic_year_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>


                                {{-- Class --}}
                                <div class="col-md-4">

                                    <label for="class_id"
                                           class="form-label">

                                        Class
                                        <span class="text-danger">*</span>

                                    </label>

                                    <select name="class_id"
                                            id="class_id"
                                            class="form-select @error('class_id') is-invalid @enderror"
                                            disabled>

                                        <option value="">
                                            Select Academic Year First
                                        </option>

                                    </select>

                                    @error('class_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                    <div id="class_loading"
                                         class="form-text d-none">
                                        Loading classes...
                                    </div>

                                </div>


                                {{-- Section --}}
                                <div class="col-md-4">

                                    <label for="section_id"
                                           class="form-label">

                                        Section
                                        <span class="text-danger">*</span>

                                    </label>

                                    <select name="section_id"
                                            id="section_id"
                                            class="form-select @error('section_id') is-invalid @enderror"
                                            disabled>

                                        <option value="">
                                            Select Class First
                                        </option>

                                    </select>

                                    @error('section_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                    <div id="section_loading"
                                         class="form-text d-none">
                                        Loading sections...
                                    </div>

                                </div>


                                {{-- Student --}}
                                <div class="col-md-8">

                                    <label for="student_id"
                                           class="form-label">

                                        Student
                                        <span class="text-danger">*</span>

                                    </label>

                                    <select name="student_id"
                                            id="student_id"
                                            class="form-select @error('student_id') is-invalid @enderror"
                                            disabled>

                                        <option value="">
                                            Select Academic Year, Class and Section First
                                        </option>

                                    </select>

                                    @error('student_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                    <div id="student_loading"
                                         class="form-text d-none">
                                        Loading students...
                                    </div>

                                </div>


                                <div class="col-md-4 d-flex align-items-end">

                                    <div class="alert alert-info py-2 px-3 mb-0 w-100">

                                        <small>

                                            <i class="bx bx-info-circle me-1"></i>

                                            Only students with an active enrollment
                                            in the selected academic placement are shown.

                                        </small>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- =====================================================
                         STAFF
                    ====================================================== --}}
                    <div class="col-12 d-none"
                         id="staff_box">

                        <div class="border rounded p-3">

                            <h6 class="mb-3">
                                Staff Member
                            </h6>

                            <div class="row g-3">

                                <div class="col-md-6">

                                    <label for="staff_id"
                                           class="form-label">

                                        Staff
                                        <span class="text-danger">*</span>

                                    </label>

                                    <select name="staff_id"
                                            id="staff_id"
                                            class="form-select @error('staff_id') is-invalid @enderror"
                                            disabled>

                                        <option value="">
                                            Select Staff
                                        </option>

                                        @foreach($staff as $staffMember)

                                            <option value="{{ $staffMember->id }}"
                                                @selected(
                                                    (string) old('staff_id', $member->staff_id) ===
                                                    (string) $staffMember->id
                                                )>

                                                {{ trim(
                                                    $staffMember->first_name . ' ' .
                                                    ($staffMember->middle_name ?? '') . ' ' .
                                                    $staffMember->last_name
                                                ) }}

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


                    {{-- Joined Date --}}
                    <div class="col-md-4">

                        <label for="joined_at"
                               class="form-label">

                            Joined Date
                            <span class="text-danger">*</span>

                        </label>

                        <input type="date"
                               id="joined_at"
                               name="joined_at"
                               value="{{ old(
                                   'joined_at',
                                   optional($member->joined_at)->format('Y-m-d')
                                       ?? $member->joined_at
                               ) }}"
                               class="form-control @error('joined_at') is-invalid @enderror"
                               required>

                        @error('joined_at')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Expiry Date --}}
                    <div class="col-md-4">

                        <label for="expiry_date"
                               class="form-label">

                            Expiry Date

                        </label>

                        <input type="date"
                               id="expiry_date"
                               name="expiry_date"
                               value="{{ old(
                                   'expiry_date',
                                   optional($member->expiry_date)->format('Y-m-d')
                                       ?? $member->expiry_date
                               ) }}"
                               class="form-control @error('expiry_date') is-invalid @enderror">

                        @error('expiry_date')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Max Books --}}
                    <div class="col-md-2">

                        <label for="max_books"
                               class="form-label">

                            Max Books
                            <span class="text-danger">*</span>

                        </label>

                        <input type="number"
                               id="max_books"
                               name="max_books"
                               value="{{ old('max_books', $member->max_books ?? 3) }}"
                               class="form-control @error('max_books') is-invalid @enderror"
                               min="1"
                               step="1"
                               required>

                        @error('max_books')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Status --}}
                    <div class="col-md-2">

                        <label for="status"
                               class="form-label">

                            Status
                            <span class="text-danger">*</span>

                        </label>

                        <select name="status"
                                id="status" 
                                style="
                                    top: 45px;
                                    left: 23px;
                                    width: 182px;
                                "
                                class="form-select @error('status') is-invalid @enderror"
                                required>

                            @foreach([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                                'suspended' => 'Suspended',
                                'expired' => 'Expired',
                            ] as $value => $label)

                                <option value="{{ $value }}"
                                    @selected(
                                        old('status', $member->status) === $value
                                    )>

                                    {{ $label }}

                                </option>

                            @endforeach

                        </select>

                        @error('status')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Notes --}}
                    <div class="col-12">

                        <label for="notes"
                               class="form-label">

                            Notes

                        </label>

                        <textarea name="notes"
                                  id="notes"
                                  rows="4"
                                  class="form-control @error('notes') is-invalid @enderror"
                                  placeholder="Optional notes about this library member">{{ old('notes', $member->notes) }}</textarea>

                        @error('notes')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

            </div>


            <div class="card-footer d-flex gap-2">

                <button type="submit"
                        class="btn btn-primary">

                    <i class="bx bx-save me-1"></i>
                    Update Member

                </button>

                <a href="{{ route('admin.library.members.index') }}"
                   class="btn btn-light">

                    Cancel

                </a>

            </div>

        </div>

    </form>

</div>

@endsection


@section('js')
<script>
(function () {

    function initLibraryMemberEditForm() {

        const holderType =
            document.getElementById('holder_type');

        const studentBox =
            document.getElementById('student_box');

        const staffBox =
            document.getElementById('staff_box');

        const academicYear =
            document.getElementById('academic_year_id');

        const classSelect =
            document.getElementById('class_id');

        const sectionSelect =
            document.getElementById('section_id');

        const studentSelect =
            document.getElementById('student_id');

        const staffSelect =
            document.getElementById('staff_id');

        const classLoading =
            document.getElementById('class_loading');

        const sectionLoading =
            document.getElementById('section_loading');

        const studentLoading =
            document.getElementById('student_loading');


        if (
            !holderType ||
            !studentBox ||
            !staffBox ||
            !academicYear ||
            !classSelect ||
            !sectionSelect ||
            !studentSelect ||
            !staffSelect
        ) {
            console.error(
                'Library Member edit form elements were not found.'
            );

            return;
        }


        const classesUrl = @json(
            route('admin.library.members.filter.classes')
        );

        const sectionsUrl = @json(
            route('admin.library.members.filter.sections')
        );

        const studentsUrl = @json(
            route('admin.library.members.filter.students')
        );


        const oldAcademicYear =
            @json(old('academic_year_id'));

        const oldClass =
            @json(old('class_id'));

        const oldSection =
            @json(old('section_id'));

        const oldStudent =
            @json(old('student_id'));


        const currentAcademicYear =
            @json(optional($studentEnrollment)->academic_year_id);

        const currentClass =
            @json(optional($studentEnrollment)->class_id);

        const currentSection =
            @json(optional($studentEnrollment)->section_id);

        const currentStudent =
            @json($member->student_id);


        function resetSelect(
            select,
            placeholder
        ) {

            select.innerHTML = '';

            const option =
                document.createElement('option');

            option.value = '';
            option.textContent = placeholder;

            select.appendChild(option);
        }


        function setLoading(
            element,
            loading
        ) {

            if (!element) {
                return;
            }

            element.classList.toggle(
                'd-none',
                !loading
            );
        }


        function disable(
            select,
            value
        ) {

            select.disabled = value;
        }


        async function loadClasses(
            academicYearId,
            selectedClassId = null
        ) {

            resetSelect(
                classSelect,
                'Loading classes...'
            );

            resetSelect(
                sectionSelect,
                'Select Class First'
            );

            resetSelect(
                studentSelect,
                'Select Academic Year, Class and Section First'
            );

            disable(classSelect, true);
            disable(sectionSelect, true);
            disable(studentSelect, true);


            if (!academicYearId) {

                resetSelect(
                    classSelect,
                    'Select Academic Year First'
                );

                return;
            }


            setLoading(
                classLoading,
                true
            );


            try {

                const response =
                    await fetch(
                        classesUrl +
                        '?academic_year_id=' +
                        encodeURIComponent(
                            academicYearId
                        ),
                        {
                            method: 'GET',
                            credentials: 'same-origin',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        }
                    );


                if (!response.ok) {

                    const errorText =
                        await response.text();

                    console.error(
                        'Class endpoint failed:',
                        response.status,
                        errorText
                    );

                    throw new Error(
                        'Unable to load classes. HTTP ' +
                        response.status
                    );
                }


                const data =
                    await response.json();


                resetSelect(
                    classSelect,
                    'Select Class'
                );


                if (
                    !data.classes ||
                    data.classes.length === 0
                ) {

                    resetSelect(
                        classSelect,
                        'No active classes found'
                    );

                    return;
                }


                data.classes.forEach(
                    function (item) {

                        const option =
                            document.createElement('option');

                        option.value =
                            item.id;

                        option.textContent =
                            item.name +
                            (
                                item.code
                                    ? ' (' +
                                      item.code +
                                      ')'
                                    : ''
                            );


                        if (
                            selectedClassId !== null &&
                            String(selectedClassId) ===
                            String(item.id)
                        ) {
                            option.selected = true;
                        }


                        classSelect.appendChild(
                            option
                        );
                    }
                );


                disable(
                    classSelect,
                    false
                );


            } catch (error) {

                console.error(
                    'Library Member edit class loading error:',
                    error
                );

                resetSelect(
                    classSelect,
                    'Unable to load classes'
                );

            } finally {

                setLoading(
                    classLoading,
                    false
                );
            }
        }


        async function loadSections(
            classId,
            selectedSectionId = null
        ) {

            resetSelect(
                sectionSelect,
                'Loading sections...'
            );

            resetSelect(
                studentSelect,
                'Select Section First'
            );

            disable(sectionSelect, true);
            disable(studentSelect, true);


            if (!classId) {

                resetSelect(
                    sectionSelect,
                    'Select Class First'
                );

                return;
            }


            setLoading(
                sectionLoading,
                true
            );


            try {

                const response =
                    await fetch(
                        sectionsUrl +
                        '?class_id=' +
                        encodeURIComponent(classId),
                        {
                            method: 'GET',
                            credentials: 'same-origin',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        }
                    );


                if (!response.ok) {

                    const errorText =
                        await response.text();

                    console.error(
                        'Section endpoint failed:',
                        response.status,
                        errorText
                    );

                    throw new Error(
                        'Unable to load sections. HTTP ' +
                        response.status
                    );
                }


                const data =
                    await response.json();


                resetSelect(
                    sectionSelect,
                    'Select Section'
                );


                if (
                    !data.sections ||
                    data.sections.length === 0
                ) {

                    resetSelect(
                        sectionSelect,
                        'No active sections found'
                    );

                    return;
                }


                data.sections.forEach(
                    function (item) {

                        const option =
                            document.createElement('option');

                        option.value =
                            item.id;

                        option.textContent =
                            item.name +
                            (
                                item.code
                                    ? ' (' +
                                      item.code +
                                      ')'
                                    : ''
                            );


                        if (
                            selectedSectionId !== null &&
                            String(selectedSectionId) ===
                            String(item.id)
                        ) {
                            option.selected = true;
                        }


                        sectionSelect.appendChild(
                            option
                        );
                    }
                );


                disable(
                    sectionSelect,
                    false
                );


            } catch (error) {

                console.error(
                    'Library Member edit section loading error:',
                    error
                );

                resetSelect(
                    sectionSelect,
                    'Unable to load sections'
                );

            } finally {

                setLoading(
                    sectionLoading,
                    false
                );
            }
        }


        async function loadStudents(
            academicYearId,
            classId,
            sectionId,
            selectedStudentId = null
        ) {

            resetSelect(
                studentSelect,
                'Loading students...'
            );

            disable(
                studentSelect,
                true
            );


            if (
                !academicYearId ||
                !classId ||
                !sectionId
            ) {

                resetSelect(
                    studentSelect,
                    'Select Academic Year, Class and Section First'
                );

                return;
            }


            setLoading(
                studentLoading,
                true
            );


            try {

                const params =
                    new URLSearchParams({
                        academic_year_id:
                            academicYearId,

                        class_id:
                            classId,

                        section_id:
                            sectionId
                    });


                const response =
                    await fetch(
                        studentsUrl +
                        '?' +
                        params.toString(),
                        {
                            method: 'GET',
                            credentials: 'same-origin',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        }
                    );


                if (!response.ok) {

                    const errorText =
                        await response.text();

                    console.error(
                        'Student endpoint failed:',
                        response.status,
                        errorText
                    );

                    throw new Error(
                        'Unable to load students. HTTP ' +
                        response.status
                    );
                }


                const data =
                    await response.json();


                resetSelect(
                    studentSelect,
                    'Select Student'
                );


                if (
                    !data.students ||
                    data.students.length === 0
                ) {

                    resetSelect(
                        studentSelect,
                        'No active students found'
                    );

                    return;
                }


                data.students.forEach(
                    function (student) {

                        const option =
                            document.createElement('option');

                        option.value =
                            student.id;


                        const fullName = [
                            student.first_name,
                            student.middle_name,
                            student.last_name
                        ]
                            .filter(Boolean)
                            .join(' ');


                        let label =
                            fullName;


                        if (student.student_number) {

                            label +=
                                ' (' +
                                student.student_number +
                                ')';
                        }


                        if (student.admission_number) {

                            label +=
                                ' - ' +
                                student.admission_number;
                        }


                        option.textContent =
                            label;


                        if (
                            selectedStudentId !== null &&
                            String(selectedStudentId) ===
                            String(student.id)
                        ) {
                            option.selected = true;
                        }


                        studentSelect.appendChild(
                            option
                        );
                    }
                );


                disable(
                    studentSelect,
                    false
                );


            } catch (error) {

                console.error(
                    'Library Member edit student loading error:',
                    error
                );

                resetSelect(
                    studentSelect,
                    'Unable to load students'
                );

            } finally {

                setLoading(
                    studentLoading,
                    false
                );
            }
        }


        function toggleHolderFields() {

            if (
                holderType.value === 'student'
            ) {

                studentBox.classList.remove(
                    'd-none'
                );

                staffBox.classList.add(
                    'd-none'
                );

                academicYear.disabled = false;

                staffSelect.disabled = true;
                staffSelect.value = '';

            } else {

                studentBox.classList.add(
                    'd-none'
                );

                staffBox.classList.remove(
                    'd-none'
                );

                staffSelect.disabled = false;

                academicYear.disabled = true;
                classSelect.disabled = true;
                sectionSelect.disabled = true;
                studentSelect.disabled = true;

            }
        }


        holderType.addEventListener(
            'change',
            function () {

                toggleHolderFields();


                if (
                    holderType.value === 'student' &&
                    academicYear.value
                ) {

                    loadClasses(
                        academicYear.value
                    );
                }
            }
        );


        academicYear.addEventListener(
            'change',
            function () {

                loadClasses(
                    academicYear.value
                );
            }
        );


        classSelect.addEventListener(
            'change',
            function () {

                loadSections(
                    classSelect.value
                );
            }
        );


        sectionSelect.addEventListener(
            'change',
            function () {

                loadStudents(
                    academicYear.value,
                    classSelect.value,
                    sectionSelect.value
                );
            }
        );


        toggleHolderFields();


        /*
         * Initialise the current student hierarchy.
         *
         * Priority:
         * 1. old() values after validation failure
         * 2. current member enrollment
         */
        if (
            holderType.value === 'student' &&
            academicYear.value
        ) {

            const selectedYear =
                oldAcademicYear ||
                currentAcademicYear ||
                academicYear.value;

            const selectedClass =
                oldClass ||
                currentClass ||
                null;

            const selectedSection =
                oldSection ||
                currentSection ||
                null;

            const selectedStudent =
                oldStudent ||
                currentStudent ||
                null;


            /*
             * Make the selected year visible in the form.
             */
            academicYear.value =
                selectedYear;


            loadClasses(
                selectedYear,
                selectedClass
            )
                .then(function () {

                    if (
                        selectedClass &&
                        classSelect.value
                    ) {

                        return loadSections(
                            selectedClass,
                            selectedSection
                        );
                    }

                    return null;
                })
                .then(function () {

                    if (
                        selectedYear &&
                        selectedClass &&
                        selectedSection &&
                        sectionSelect.value
                    ) {

                        return loadStudents(
                            selectedYear,
                            selectedClass,
                            selectedSection,
                            selectedStudent
                        );
                    }

                    return null;
                })
                .catch(function (error) {

                    console.error(
                        'Unable to initialise Library Member edit hierarchy:',
                        error
                    );
                });
        }

    }


    if (
        document.readyState === 'loading'
    ) {

        document.addEventListener(
            'DOMContentLoaded',
            initLibraryMemberEditForm
        );

    } else {

        initLibraryMemberEditForm();

    }

})();
</script>
@endsection
