@extends('backend.layout.default')

@section('title', 'Add Library Member')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1">Add Library Member</h4>
            <p class="text-muted mb-0">
                Register a student or staff member for library services.
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
        <div class="alert alert-danger">
            <div class="fw-semibold mb-2">
                Please correct the following:
            </div>

            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST"
          action="{{ route('admin.library.members.store') }}">
        @csrf

        <div class="card">

            <div class="card-header">
                <h5 class="card-title mb-0">
                    Member Information
                </h5>
            </div>

            <div class="card-body">

                <div class="row g-3">

                    {{-- Holder Type --}}
                    <div class="col-md-4">
                        <label for="holder_type" class="form-label">
                            Holder Type
                            <span class="text-danger">*</span>
                        </label>

                        <select name="holder_type"
                                id="holder_type"
                                class="form-select @error('holder_type') is-invalid @enderror"
                                required>

                            <option value="student"
                                @selected(old('holder_type', 'student') === 'student')>
                                Student
                            </option>

                            <option value="staff"
                                @selected(old('holder_type') === 'staff')>
                                Staff
                            </option>

                        </select>

                        @error('holder_type')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- =====================================================
                         STUDENT SECTION
                         Academic Year -> Class -> Section -> Student
                         ===================================================== --}}
                    <div class="col-12" id="student_box">

                        <div class="border rounded p-3">

                            <div class="d-flex align-items-center mb-3">
                                <div>
                                    <h6 class="mb-1">Student Academic Placement</h6>
                                    <small class="text-muted">
                                        Select the student's active academic year,
                                        class and section to load eligible students.
                                    </small>
                                </div>
                            </div>

                            <div class="row g-3">

                                {{-- Academic Year --}}
                                <div class="col-md-4">
                                    <label for="academic_year_id" class="form-label">
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
                                            <option value="{{ $academicYear->id }}"
                                                @selected((string) old('academic_year_id') === (string) $academicYear->id)>
                                                {{ $academicYear->name
                                                    ?? ($academicYear->title
                                                        ?? ($academicYear->code ?? 'Academic Year #' . $academicYear->id)) }}
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
                                    <label for="class_id" class="form-label">
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
                                    <label for="section_id" class="form-label">
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
                                    <label for="student_id" class="form-label">
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

                    {{-- Staff --}}
                    <div class="col-12 d-none" id="staff_box">

                        <div class="border rounded p-3">

                            <h6 class="mb-3">Staff Member</h6>

                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label for="staff_id" class="form-label">
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
                                                @selected((string) old('staff_id') === (string) $staffMember->id)>

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

                    {{-- Joined --}}
                    <div class="col-md-4">
                        <label for="joined_at" class="form-label">
                            Joined Date
                            <span class="text-danger">*</span>
                        </label>

                        <input type="date"
                               id="joined_at"
                               name="joined_at"
                               value="{{ old('joined_at', now()->format('Y-m-d')) }}"
                               class="form-control @error('joined_at') is-invalid @enderror"
                               required>

                        @error('joined_at')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Expiry --}}
                    <div class="col-md-4">
                        <label for="expiry_date" class="form-label">
                            Expiry Date
                        </label>

                        <input type="date"
                               id="expiry_date"
                               name="expiry_date"
                               value="{{ old('expiry_date') }}"
                               class="form-control @error('expiry_date') is-invalid @enderror">

                        @error('expiry_date')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Max Books --}}
                    <div class="col-md-2">
                        <label for="max_books" class="form-label">
                            Max Books
                            <span class="text-danger">*</span>
                        </label>

                        <input type="number"
                               id="max_books"
                               name="max_books"
                               value="{{ old('max_books', 3) }}"
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
                        <label for="status" class="form-label">
                            Status
                            <span class="text-danger">*</span>
                        </label>

                        <select name="status"
                                id="status"
                                class="form-select @error('status') is-invalid @enderror"
                                required>

                            @foreach([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                                'suspended' => 'Suspended',
                                'expired' => 'Expired',
                            ] as $value => $label)

                                <option value="{{ $value }}"
                                    @selected(old('status', 'active') === $value)>
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
                        <label for="notes" class="form-label">
                            Notes
                        </label>

                        <textarea name="notes"
                                  id="notes"
                                  rows="4"
                                  class="form-control @error('notes') is-invalid @enderror"
                                  placeholder="Optional notes about this library member">{{ old('notes') }}</textarea>

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
                    Create Member
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
document.addEventListener('DOMContentLoaded', function () {

    const holderType       = document.getElementById('holder_type');

    const studentBox       = document.getElementById('student_box');
    const staffBox         = document.getElementById('staff_box');

    const academicYear     = document.getElementById('academic_year_id');
    const classSelect      = document.getElementById('class_id');
    const sectionSelect    = document.getElementById('section_id');
    const studentSelect    = document.getElementById('student_id');
    const staffSelect      = document.getElementById('staff_id');

    const classLoading     = document.getElementById('class_loading');
    const sectionLoading   = document.getElementById('section_loading');
    const studentLoading   = document.getElementById('student_loading');

    /*
     * These endpoints correspond to the filter methods added to
     * LibraryOperationsController:
     *
     * filterMemberClasses()
     * filterMemberSections()
     * filterMemberStudents()
     */
    const filterClassesUrl =
        @json(url('admin/library/members/filter/classes'));

    const filterSectionsUrl =
        @json(url('admin/library/members/filter/sections'));

    const filterStudentsUrl =
        @json(url('admin/library/members/filter/students'));

    const oldAcademicYear = @json(old('academic_year_id'));
    const oldClass        = @json(old('class_id'));
    const oldSection      = @json(old('section_id'));
    const oldStudent      = @json(old('student_id'));

    function resetSelect(select, placeholder) {
        select.innerHTML = '';

        const option = document.createElement('option');
        option.value = '';
        option.textContent = placeholder;

        select.appendChild(option);
    }

    function setLoading(element, loading) {
        if (!element) {
            return;
        }

        element.classList.toggle('d-none', !loading);
    }

    function setSelectDisabled(select, disabled) {
        select.disabled = disabled;
    }

    function studentLabel(student) {
        const fullName = [
            student.first_name,
            student.middle_name,
            student.last_name
        ]
            .filter(Boolean)
            .join(' ');

        const number = student.student_number
            ? ' (' + student.student_number + ')'
            : '';

        const admission = student.admission_number
            ? ' - ' + student.admission_number
            : '';

        return fullName + number + admission;
    }

    /*
     * ================================================================
     * Load Classes
     * ================================================================
     */
    async function loadClasses(academicYearId, selectedClassId = null) {

        resetSelect(
            classSelect,
            'Select Class'
        );

        resetSelect(
            sectionSelect,
            'Select Class First'
        );

        resetSelect(
            studentSelect,
            'Select Academic Year, Class and Section First'
        );

        setSelectDisabled(classSelect, true);
        setSelectDisabled(sectionSelect, true);
        setSelectDisabled(studentSelect, true);

        if (!academicYearId) {
            classSelect.innerHTML =
                '<option value="">Select Academic Year First</option>';

            return;
        }

        setLoading(classLoading, true);

        try {

            const response = await fetch(
                filterClassesUrl +
                '?academic_year_id=' +
                encodeURIComponent(academicYearId),
                {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }
            );

            const data = await response.json();

            if (!response.ok) {
                throw new Error(
                    data.message || 'Unable to load classes.'
                );
            }

            resetSelect(classSelect, 'Select Class');

            if (!data.classes || data.classes.length === 0) {

                resetSelect(
                    classSelect,
                    'No active classes found'
                );

                return;
            }

            data.classes.forEach(function (item) {

                const option = document.createElement('option');

                option.value = item.id;

                option.textContent =
                    item.name +
                    (item.code ? ' (' + item.code + ')' : '');

                if (
                    selectedClassId !== null &&
                    String(selectedClassId) === String(item.id)
                ) {
                    option.selected = true;
                }

                classSelect.appendChild(option);
            });

            setSelectDisabled(classSelect, false);

        } catch (error) {

            console.error(error);

            resetSelect(
                classSelect,
                'Unable to load classes'
            );

        } finally {
            setLoading(classLoading, false);
        }
    }

    /*
     * ================================================================
     * Load Sections
     * ================================================================
     */
    async function loadSections(classId, selectedSectionId = null) {

        resetSelect(
            sectionSelect,
            'Select Section'
        );

        resetSelect(
            studentSelect,
            'Select Section First'
        );

        setSelectDisabled(sectionSelect, true);
        setSelectDisabled(studentSelect, true);

        if (!classId) {
            sectionSelect.innerHTML =
                '<option value="">Select Class First</option>';

            return;
        }

        setLoading(sectionLoading, true);

        try {

            const response = await fetch(
                filterSectionsUrl +
                '?class_id=' +
                encodeURIComponent(classId),
                {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }
            );

            const data = await response.json();

            if (!response.ok) {
                throw new Error(
                    data.message || 'Unable to load sections.'
                );
            }

            resetSelect(sectionSelect, 'Select Section');

            if (!data.sections || data.sections.length === 0) {

                resetSelect(
                    sectionSelect,
                    'No active sections found'
                );

                return;
            }

            data.sections.forEach(function (item) {

                const option = document.createElement('option');

                option.value = item.id;

                option.textContent =
                    item.name +
                    (item.code ? ' (' + item.code + ')' : '');

                if (
                    selectedSectionId !== null &&
                    String(selectedSectionId) === String(item.id)
                ) {
                    option.selected = true;
                }

                sectionSelect.appendChild(option);
            });

            setSelectDisabled(sectionSelect, false);

        } catch (error) {

            console.error(error);

            resetSelect(
                sectionSelect,
                'Unable to load sections'
            );

        } finally {
            setLoading(sectionLoading, false);
        }
    }

    /*
     * ================================================================
     * Load Students
     * ================================================================
     */
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

        setSelectDisabled(studentSelect, true);

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

        setLoading(studentLoading, true);

        try {

            const params = new URLSearchParams({
                academic_year_id: academicYearId,
                class_id: classId,
                section_id: sectionId
            });

            const response = await fetch(
                filterStudentsUrl + '?' + params.toString(),
                {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }
            );

            const data = await response.json();

            if (!response.ok) {
                throw new Error(
                    data.message || 'Unable to load students.'
                );
            }

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

            data.students.forEach(function (student) {

                const option = document.createElement('option');

                option.value = student.id;

                option.textContent = studentLabel(student);

                if (
                    selectedStudentId !== null &&
                    String(selectedStudentId) === String(student.id)
                ) {
                    option.selected = true;
                }

                studentSelect.appendChild(option);
            });

            setSelectDisabled(studentSelect, false);

        } catch (error) {

            console.error(error);

            resetSelect(
                studentSelect,
                'Unable to load students'
            );

        } finally {
            setLoading(studentLoading, false);
        }
    }

    /*
     * ================================================================
     * Holder Type
     * ================================================================
     */
    function toggleHolderFields() {

        const type = holderType.value;

        if (type === 'student') {

            studentBox.classList.remove('d-none');
            staffBox.classList.add('d-none');

            staffSelect.disabled = true;
            staffSelect.value = '';

            academicYear.disabled = false;

        } else {

            studentBox.classList.add('d-none');
            staffBox.classList.remove('d-none');

            staffSelect.disabled = false;

            academicYear.disabled = true;
            classSelect.disabled = true;
            sectionSelect.disabled = true;
            studentSelect.disabled = true;

            studentSelect.value = '';
        }
    }

    /*
     * ================================================================
     * Events
     * ================================================================
     */
    holderType.addEventListener(
        'change',
        toggleHolderFields
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

    /*
     * ================================================================
     * Initial State / Old Input Restoration
     * ================================================================
     */
    toggleHolderFields();

    /*
     * ================================================================
     * Initial Student Hierarchy
     * ================================================================
     *
     * IMPORTANT:
     * On the first GET request there is no old('academic_year_id').
     * The Academic Year dropdown can nevertheless already contain the
     * current active academic year. Therefore we must load classes
     * from the current selected value automatically.
     *
     * This was the reason the Class dropdown remained disabled even
     * though the controller endpoint was returning valid JSON.
     */
    if (
        holderType.value === 'student' &&
        academicYear.value
    ) {

        loadClasses(
            academicYear.value,
            oldClass || null
        ).then(function () {

            /*
             * Restore Class and load Sections when coming back
             * after a validation error.
             */
            if (
                oldClass &&
                classSelect.value
            ) {

                return loadSections(
                    oldClass,
                    oldSection || null
                );
            }

            return null;

        }).then(function () {

            /*
             * Restore Section and load Students when old input
             * is available.
             */
            if (
                oldAcademicYear &&
                oldClass &&
                oldSection &&
                sectionSelect.value
            ) {

                return loadStudents(
                    oldAcademicYear,
                    oldClass,
                    oldSection,
                    oldStudent || null
                );
            }

            return null;

        }).catch(function (error) {

            console.error(
                'Unable to initialise student academic hierarchy.',
                error
            );

        });
    }

});
</script>
@endsection
