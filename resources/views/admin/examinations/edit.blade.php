@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0 font-size-18">
                    Edit Examination
                </h4>

                <div class="page-title-right">
                    <a href="{{ route('admin.examinations.show', $examination) }}"
                       class="btn btn-light">
                        <i class="bx bx-arrow-back me-1"></i>
                        Back to Details
                    </a>
                </div>

            </div>
        </div>
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
                    data-bs-dismiss="alert"
                    aria-label="Close">
            </button>

        </div>

    @endif

    {{-- Edit Form --}}
    <div class="card">

        <div class="card-body">

            <h5 class="card-title mb-4">
                Examination Information
            </h5>

            <form method="POST"
                  action="{{ route('admin.examinations.update', $examination) }}">

                @csrf
                @method('PUT')

                <div class="row g-3">

                    {{-- Examination Name --}}
                    <div class="col-md-6">

                        <label for="name"
                               class="form-label">
                            Examination Name
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               id="name"
                               name="name"
                               value="{{ old('name', $examination->name) }}"
                               class="form-control @error('name') is-invalid @enderror"
                               required>

                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Examination Type --}}
                    <div class="col-md-6">

                        <label for="type"
                               class="form-label">
                            Examination Type
                            <span class="text-danger">*</span>
                        </label>

                        <select id="type"
                                name="type"
                                class="form-select @error('type') is-invalid @enderror"
                                required>

                            <option value="continuous_assessment"
                                {{ old('type', $examination->type) === 'continuous_assessment' ? 'selected' : '' }}>
                                Continuous Assessment
                            </option>

                            <option value="mid_term"
                                {{ old('type', $examination->type) === 'mid_term' ? 'selected' : '' }}>
                                Mid Term
                            </option>

                            <option value="final"
                                {{ old('type', $examination->type) === 'final' ? 'selected' : '' }}>
                                Final Examination
                            </option>

                            <option value="entrance"
                                {{ old('type', $examination->type) === 'entrance' ? 'selected' : '' }}>
                                Entrance Examination
                            </option>

                            <option value="other"
                                {{ old('type', $examination->type) === 'other' ? 'selected' : '' }}>
                                Other
                            </option>

                        </select>

                        @error('type')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Academic Year --}}
                    <div class="col-md-6">

                        <label for="academic_year_id"
                               class="form-label">
                            Academic Year
                            <span class="text-danger">*</span>
                        </label>

                        <select id="academic_year_id"
                                name="academic_year_id"
                                class="form-select @error('academic_year_id') is-invalid @enderror"
                                required>

                            <option value="">
                                Select Academic Year
                            </option>

                            @foreach($academicYears as $academicYear)

                                <option value="{{ $academicYear->id }}"
                                    {{ (string) old('academic_year_id', $examination->academic_year_id) === (string) $academicYear->id ? 'selected' : '' }}>

                                    {{ $academicYear->name
                                        ?? $academicYear->year
                                        ?? $academicYear->id }}

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
                    <div class="col-md-6">

                        <label for="term_id"
                               class="form-label">
                            Term / Semester
                        </label>

                        <select id="term_id"
                                name="term_id"
                                class="form-select @error('term_id') is-invalid @enderror">

                            <option value="">
                                No Term Selected
                            </option>

                            @foreach($terms as $term)

                                <option value="{{ $term->id }}"
                                    {{ (string) old('term_id', $examination->term_id) === (string) $term->id ? 'selected' : '' }}>

                                    {{ $term->name
                                        ?? $term->title
                                        ?? $term->id }}

                                </option>

                            @endforeach

                        </select>

                        @error('term_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Start Date --}}
                    <div class="col-md-6">

                        <label for="start_date"
                               class="form-label">
                            Start Date
                        </label>

                        <input type="date"
                               id="start_date"
                               name="start_date"
                               value="{{ old(
                                   'start_date',
                                   $examination->start_date
                                       ? $examination->start_date->format('Y-m-d')
                                       : ''
                               ) }}"
                               class="form-control @error('start_date') is-invalid @enderror">

                        @error('start_date')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- End Date --}}
                    <div class="col-md-6">

                        <label for="end_date"
                               class="form-label">
                            End Date
                        </label>

                        <input type="date"
                               id="end_date"
                               name="end_date"
                               value="{{ old(
                                   'end_date',
                                   $examination->end_date
                                       ? $examination->end_date->format('Y-m-d')
                                       : ''
                               ) }}"
                               class="form-control @error('end_date') is-invalid @enderror">

                        @error('end_date')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Status --}}
                    <div class="col-md-6 mb-4">

                        <label for="status"
                               class="form-label">
                            Status
                            <span class="text-danger">*</span>
                        </label>

                        <select id="status"
                                name="status"
                                style="left: 5%; top: 111%; min-width: 97%;"
                                class="form-select @error('status') is-invalid @enderror"
                                required>

                            <option value="draft"
                                {{ old('status', $examination->status) === 'draft' ? 'selected' : '' }}>
                                Draft
                            </option>

                            <option value="scheduled"
                                {{ old('status', $examination->status) === 'scheduled' ? 'selected' : '' }}>
                                Scheduled
                            </option>

                            <option value="ongoing"
                                {{ old('status', $examination->status) === 'ongoing' ? 'selected' : '' }}>
                                Ongoing
                            </option>

                            <option value="completed"
                                {{ old('status', $examination->status) === 'completed' ? 'selected' : '' }}>
                                Completed
                            </option>

                            <option value="published"
                                {{ old('status', $examination->status) === 'published' ? 'selected' : '' }}>
                                Published
                            </option>

                        </select>

                        @error('status')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

                {{-- Form Actions --}}
                <div class="mt-4 pt-3 border-top">

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="bx bx-save me-1"></i>
                        Update Examination

                    </button>

                    <a href="{{ route('admin.examinations.show', $examination) }}"
                       class="btn btn-light ms-1">

                        Cancel

                    </a>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection