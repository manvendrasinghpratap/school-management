@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0">Edit Grade</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">

                        <li class="breadcrumb-item">
                            <a href="javascript:void(0);">
                                Examination
                            </a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.grading.index') }}">
                                Grading Setup
                            </a>
                        </li>

                        <li class="breadcrumb-item active">
                            Edit Grade
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

    <div class="row">

        {{-- Edit Form --}}
        <div class="col-lg-8">

            <div class="card">

                <div class="card-header">

                    <h5 class="card-title mb-1">
                        Edit Grading Scale
                    </h5>

                    <p class="text-muted mb-0">
                        Update the score range, grade point and result status.
                    </p>

                </div>

                <div class="card-body">

                    <form action="{{ route('admin.grading.update', $grade) }}"
                          method="POST">

                        @csrf
                        @method('PUT')

                        {{-- Grade Name --}}
                        <div class="mb-3">

                            <label for="name" class="form-label">
                                Grade Name
                                <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                   name="name"
                                   id="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $grade->name) }}"
                                   maxlength="100"
                                   required>

                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        {{-- Grade Code --}}
                        <div class="mb-3">

                            <label for="code" class="form-label">
                                Grade Code
                                <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                   name="code"
                                   id="code"
                                   class="form-control @error('code') is-invalid @enderror"
                                   value="{{ old('code', $grade->code) }}"
                                   maxlength="20"
                                   required>

                            <div class="form-text">
                                The grade code must be unique.
                            </div>

                            @error('code')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        {{-- Score Range --}}
                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label for="minimum_score" class="form-label">
                                    Minimum Score
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="number"
                                       name="minimum_score"
                                       id="minimum_score"
                                       class="form-control @error('minimum_score') is-invalid @enderror"
                                       value="{{ old('minimum_score', $grade->minimum_score) }}"
                                       min="0"
                                       max="100"
                                       step="0.01"
                                       required>

                                @error('minimum_score')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            <div class="col-md-6 mb-3">

                                <label for="maximum_score" class="form-label">
                                    Maximum Score
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="number"
                                       name="maximum_score"
                                       id="maximum_score"
                                       class="form-control @error('maximum_score') is-invalid @enderror"
                                       value="{{ old('maximum_score', $grade->maximum_score) }}"
                                       min="0"
                                       max="100"
                                       step="0.01"
                                       required>

                                @error('maximum_score')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                        {{-- Grade Point --}}
                        <div class="mb-3">

                            <label for="grade_point" class="form-label">
                                Grade Point
                            </label>

                            <input type="number"
                                   name="grade_point"
                                   id="grade_point"
                                   class="form-control @error('grade_point') is-invalid @enderror"
                                   value="{{ old('grade_point', $grade->grade_point) }}"
                                   min="0"
                                   max="100"
                                   step="0.01">

                            <div class="form-text">
                                Optional. Example: A = 4.00, B = 3.00.
                            </div>

                            @error('grade_point')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        {{-- Result --}}
                        <div class="mb-3">

                            <label for="result" class="form-label">
                                Result
                                <span class="text-danger">*</span>
                            </label>

                            <select name="result"
                                    id="result"
                                    class="form-select @error('result') is-invalid @enderror"
                                    required>

                                <option value="">
                                    Select Result
                                </option>

                                <option value="pass"
                                    {{ old('result', $grade->result) === 'pass' ? 'selected' : '' }}>
                                    Pass
                                </option>

                                <option value="fail"
                                    {{ old('result', $grade->result) === 'fail' ? 'selected' : '' }}>
                                    Fail
                                </option>

                            </select>

                            @error('result')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        {{-- Remark --}}
                        <div class="mb-4">

                            <label for="remark" class="form-label">
                                Remark
                            </label>

                            <textarea name="remark"
                                      id="remark"
                                      class="form-control @error('remark') is-invalid @enderror"
                                      rows="3"
                                      maxlength="255">{{ old('remark', $grade->remark) }}</textarea>

                            @error('remark')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        {{-- Actions --}}
                        <div class="d-flex gap-2">

                            <button type="submit"
                                    class="btn btn-primary">
                                <i class="ri-save-line align-middle me-1"></i>
                                Update Grade
                            </button>

                            <a href="{{ route('admin.grading.show', $grade) }}"
                               class="btn btn-light">
                                <i class="ri-arrow-left-line align-middle me-1"></i>
                                Cancel
                            </a>

                        </div>

                    </form>

                </div>

            </div>

        </div>

        {{-- Current Grade Information --}}
        <div class="col-lg-4">

            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">
                        Current Grade
                    </h5>
                </div>

                <div class="card-body">

                    <div class="text-center mb-4">

                        <span class="badge bg-secondary fs-6">
                            {{ $grade->code }}
                        </span>

                        <h5 class="mt-3 mb-1">
                            {{ $grade->name }}
                        </h5>

                        <p class="text-muted mb-0">
                            Current score range
                        </p>

                    </div>

                    <div class="alert alert-info text-center">
                        <strong class="fs-5">
                            {{ number_format((float) $grade->minimum_score, 2) }}
                            –
                            {{ number_format((float) $grade->maximum_score, 2) }}
                        </strong>
                    </div>

                    <div class="alert alert-warning mb-0">
                        <strong>Important:</strong>
                        The updated score range must not overlap another
                        grading range.
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection