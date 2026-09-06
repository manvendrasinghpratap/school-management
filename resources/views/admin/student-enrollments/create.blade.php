@extends('backend.layout.default')

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-12">

            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Create Student Enrollment</h4>

                <a href="{{ route('admin.student-enrollments.index') }}"
                   class="btn btn-light">
                    <i class="mdi mdi-arrow-left me-1"></i>
                    Back
                </a>
            </div>

        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Please correct the following:</strong>

            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST"
          action="{{ route('admin.student-enrollments.store') }}">

        @csrf

        <div class="row">

            {{-- Student --}}
            <div class="col-lg-8">

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            Enrollment Information
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="mb-3">
                            <label class="form-label">
                                Student <span class="text-danger">*</span>
                            </label>

                            <select name="student_id"
                                    class="form-select @error('student_id') is-invalid @enderror"
                                    required>

                                <option value="">Select Student</option>

                                @foreach($students as $student)

                                    <option value="{{ $student->id }}"
                                        {{ old('student_id') == $student->id ? 'selected' : '' }}>

                                        {{ $student->student_number }}
                                        —
                                        {{ $student->first_name }}
                                        {{ $student->middle_name }}
                                        {{ $student->last_name }}

                                    </option>

                                @endforeach

                            </select>

                            @error('student_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="row">

                            {{-- Academic Year --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Academic Year <span class="text-danger">*</span>
                                </label>

                                <select name="academic_year_id"
                                        id="academic_year_id"
                                        class="form-select @error('academic_year_id') is-invalid @enderror"
                                        required>

                                    <option value="">Select Academic Year</option>

                                    @foreach($academicYears as $year)

                                        <option value="{{ $year->id }}"
                                            {{ old('academic_year_id') == $year->id ? 'selected' : '' }}>

                                            {{ $year->name }}

                                        </option>

                                    @endforeach

                                </select>

                                @error('academic_year_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            {{-- Term --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Term
                                </label>

                                <select name="term_id"
                                        id="term_id"
                                        class="form-select @error('term_id') is-invalid @enderror">

                                    <option value="">Select Term</option>

                                    @foreach($terms as $term)

                                        <option value="{{ $term->id }}"
                                            data-year="{{ $term->academic_year_id }}"
                                            {{ old('term_id') == $term->id ? 'selected' : '' }}>

                                            {{ $term->name }}

                                        </option>

                                    @endforeach

                                </select>

                                @error('term_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                        <div class="row">

                            {{-- Class --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Class <span class="text-danger">*</span>
                                </label>

                                <select name="class_id"
                                        id="class_id"
                                        class="form-select @error('class_id') is-invalid @enderror"
                                        required>

                                    <option value="">Select Class</option>

                                    @foreach($classes as $class)

                                        <option value="{{ $class->id }}"
                                            {{ old('class_id') == $class->id ? 'selected' : '' }}>

                                            {{ $class->name }}

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
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Section
                                </label>

                                <select name="section_id"
                                        id="section_id"
                                        class="form-select @error('section_id') is-invalid @enderror">

                                    <option value="">Select Section</option>

                                    @foreach($sections as $section)

                                        <option value="{{ $section->id }}"
                                            data-class="{{ $section->class_id }}"
                                            {{ old('section_id') == $section->id ? 'selected' : '' }}>

                                            {{ $section->name }}

                                        </option>

                                    @endforeach

                                </select>

                                @error('section_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                        <div class="row">

                            {{-- Date --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Enrollment Date <span class="text-danger">*</span>
                                </label>

                                <input type="date"
                                       name="enrollment_date"
                                       value="{{ old('enrollment_date', now()->format('Y-m-d')) }}"
                                       class="form-control @error('enrollment_date') is-invalid @enderror"
                                       required>

                                @error('enrollment_date')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            {{-- Status --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Status
                                </label>

                                <select name="status"
                                        class="form-select">

                                    <option value="active"
                                        {{ old('status', 'active') === 'active' ? 'selected' : '' }}>
                                        Active
                                    </option>

                                    <option value="completed"
                                        {{ old('status') === 'completed' ? 'selected' : '' }}>
                                        Completed
                                    </option>

                                    <option value="transferred"
                                        {{ old('status') === 'transferred' ? 'selected' : '' }}>
                                        Transferred
                                    </option>

                                    <option value="withdrawn"
                                        {{ old('status') === 'withdrawn' ? 'selected' : '' }}>
                                        Withdrawn
                                    </option>

                                </select>

                            </div>

                        </div>

                        {{-- Notes --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Notes
                            </label>

                            <textarea name="notes"
                                      rows="4"
                                      class="form-control"
                                      placeholder="Optional enrollment notes">{{ old('notes') }}</textarea>

                        </div>

                    </div>
                </div>

            </div>

            {{-- Information --}}
            <div class="col-lg-4">

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            Enrollment Guide
                        </h5>
                    </div>

                    <div class="card-body">

                        <p class="text-muted">
                            Select the student and assign the academic year,
                            term, class and section for the enrollment.
                        </p>

                        <div class="alert alert-info">
                            <i class="mdi mdi-information-outline me-1"></i>

                            The system will automatically generate a unique
                            enrollment number.
                        </div>

                        <div class="alert alert-warning">
                            <i class="mdi mdi-alert-outline me-1"></i>

                            A student cannot have duplicate active enrollment
                            records for the same academic year and term.
                        </div>

                    </div>
                </div>

            </div>

        </div>

        <div class="card">
            <div class="card-body">

                <div class="d-flex justify-content-end gap-2">

                    <a href="{{ route('admin.student-enrollments.index') }}"
                       class="btn btn-light">
                        Cancel
                    </a>

                    <button type="submit"
                            class="btn btn-primary">
                        <i class="mdi mdi-content-save me-1"></i>
                        Create Enrollment
                    </button>

                </div>

            </div>
        </div>

    </form>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const academicYear = document.getElementById('academic_year_id');
    const term = document.getElementById('term_id');

    const classSelect = document.getElementById('class_id');
    const section = document.getElementById('section_id');

    function filterTerms() {

        const yearId = academicYear.value;

        Array.from(term.options).forEach(option => {

            if (!option.value) {
                option.hidden = false;
                return;
            }

            option.hidden = option.dataset.year !== yearId;

        });

        if (
            term.value &&
            term.selectedOptions[0]?.dataset.year !== yearId
        ) {
            term.value = '';
        }
    }

    function filterSections() {

        const classId = classSelect.value;

        Array.from(section.options).forEach(option => {

            if (!option.value) {
                option.hidden = false;
                return;
            }

            option.hidden = option.dataset.class !== classId;

        });

        if (
            section.value &&
            section.selectedOptions[0]?.dataset.class !== classId
        ) {
            section.value = '';
        }
    }

    academicYear.addEventListener('change', filterTerms);
    classSelect.addEventListener('change', filterSections);

    filterTerms();
    filterSections();
});
</script>
@endpush