@extends('backend.layout.default')

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-12">

            <div class="page-title-box d-flex align-items-center justify-content-between">

                <h4 class="mb-0">
                    Edit Student Enrollment
                </h4>

                <a href="{{ route('admin.student-enrollments.show', $enrollment) }}"
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
          action="{{ route('admin.student-enrollments.update', $enrollment) }}">

        @csrf
        @method('PUT')

        <div class="row">

            <div class="col-lg-8">

                <div class="card">

                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            Enrollment Information
                        </h5>
                    </div>

                    <div class="card-body">

                        {{-- Student - read only --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Student
                            </label>

                            <input type="text"
                                   class="form-control"
                                   value="{{ $enrollment->student?->student_number }} — {{ $enrollment->student?->first_name }} {{ $enrollment->student?->middle_name }} {{ $enrollment->student?->last_name }}"
                                   readonly>

                            <input type="hidden"
                                   name="student_id"
                                   value="{{ $enrollment->student_id }}">

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

                                    <option value="">
                                        Select Academic Year
                                    </option>

                                    @foreach($academicYears as $year)

                                        <option value="{{ $year->id }}"
                                            {{ old('academic_year_id', $enrollment->academic_year_id) == $year->id ? 'selected' : '' }}>

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

                                    <option value="">
                                        Select Term
                                    </option>

                                    @foreach($terms as $term)

                                        <option value="{{ $term->id }}"
                                                data-year="{{ $term->academic_year_id }}"
                                            {{ old('term_id', $enrollment->term_id) == $term->id ? 'selected' : '' }}>

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

                                    <option value="">
                                        Select Class
                                    </option>

                                    @foreach($classes as $class)

                                        <option value="{{ $class->id }}"
                                            {{ old('class_id', $enrollment->class_id) == $class->id ? 'selected' : '' }}>

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

                                    <option value="">
                                        Select Section
                                    </option>

                                    @foreach($sections as $section)

                                        <option value="{{ $section->id }}"
                                                data-class="{{ $section->class_id }}"
                                            {{ old('section_id', $enrollment->section_id) == $section->id ? 'selected' : '' }}>

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
                                       value="{{ old('enrollment_date', optional($enrollment->enrollment_date)->format('Y-m-d')) }}"
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
                                    Status <span class="text-danger">*</span>
                                </label>

                                <select name="status"
                                        class="form-select @error('status') is-invalid @enderror"
                                        required>

                                    @foreach([
                                        'active' => 'Active',
                                        'completed' => 'Completed',
                                        'transferred' => 'Transferred',
                                        'withdrawn' => 'Withdrawn',
                                    ] as $value => $label)

                                        <option value="{{ $value }}"
                                            {{ old('status', $enrollment->status) === $value ? 'selected' : '' }}>

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

                        </div>

                        {{-- Notes --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Notes
                            </label>

                            <textarea name="notes"
                                      rows="4"
                                      class="form-control">{{ old('notes', $enrollment->notes) }}</textarea>

                        </div>

                    </div>

                </div>

            </div>

            <div class="col-lg-4">

                <div class="card">

                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            Enrollment Number
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="alert alert-info mb-0">

                            <strong>
                                {{ $enrollment->enrollment_number }}
                            </strong>

                            <p class="mb-0 mt-2">
                                The enrollment number is automatically generated
                                and cannot be changed.
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="card">

            <div class="card-body">

                <div class="d-flex justify-content-end gap-2">

                    <a href="{{ route('admin.student-enrollments.show', $enrollment) }}"
                       class="btn btn-light">
                        Cancel
                    </a>

                    <button type="submit"
                            class="btn btn-primary">
                        <i class="mdi mdi-content-save me-1"></i>
                        Update Enrollment
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