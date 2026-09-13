@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0">Create Grade</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript:void(0);">Examination</a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.grading.index') }}">
                                Grading Setup
                            </a>
                        </li>

                        <li class="breadcrumb-item active">
                            Create Grade
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

    {{-- Create Grade Card --}}
    <div class="row">
        <div class="col-lg-8">

            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-1">
                        Create Grading Scale
                    </h5>

                    <p class="text-muted mb-0">
                        Define the score range, grade point and result status
                        for this grade.
                    </p>
                </div>

                <div class="card-body">

                    <form action="{{ route('admin.grading.store') }}"
                          method="POST">

                        @csrf

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
                                   value="{{ old('name') }}"
                                   maxlength="100"
                                   placeholder="e.g. Excellent"
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
                                   value="{{ old('code') }}"
                                   maxlength="20"
                                   placeholder="e.g. A"
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

                        <div class="row">

                            {{-- Minimum Score --}}
                            <div class="col-md-6 mb-3">

                                <label for="minimum_score" class="form-label">
                                    Minimum Score
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="number"
                                       name="minimum_score"
                                       id="minimum_score"
                                       class="form-control @error('minimum_score') is-invalid @enderror"
                                       value="{{ old('minimum_score') }}"
                                       min="0"
                                       max="100"
                                       step="0.01"
                                       placeholder="e.g. 80"
                                       required>

                                @error('minimum_score')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            {{-- Maximum Score --}}
                            <div class="col-md-6 mb-3">

                                <label for="maximum_score" class="form-label">
                                    Maximum Score
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="number"
                                       name="maximum_score"
                                       id="maximum_score"
                                       class="form-control @error('maximum_score') is-invalid @enderror"
                                       value="{{ old('maximum_score') }}"
                                       min="0"
                                       max="100"
                                       step="0.01"
                                       placeholder="e.g. 100"
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
                                   value="{{ old('grade_point') }}"
                                   min="0"
                                   max="100"
                                   step="0.01"
                                   placeholder="e.g. 4.00">

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
                                    {{ old('result') === 'pass' ? 'selected' : '' }}>
                                    Pass
                                </option>

                                <option value="fail"
                                    {{ old('result') === 'fail' ? 'selected' : '' }}>
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
                                      maxlength="255"
                                      placeholder="e.g. Excellent performance">{{ old('remark') }}</textarea>

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
                                Save Grade
                            </button>

                            <a href="{{ route('admin.grading.index') }}"
                               class="btn btn-light">
                                <i class="ri-arrow-left-line align-middle me-1"></i>
                                Cancel
                            </a>

                        </div>

                    </form>

                </div>

            </div>

        </div>

        {{-- Information Panel --}}
        <div class="col-lg-4">

            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">
                        Grading Scale Information
                    </h5>
                </div>

                <div class="card-body">

                    <p class="text-muted">
                        Define the score ranges that the system will use
                        when calculating student grades.
                    </p>

                    <div class="alert alert-info">
                        <strong>Example grading scale</strong>

                        <ul class="mb-0 mt-2">
                            <li>A: 80–100</li>
                            <li>B: 70–79.99</li>
                            <li>C: 60–69.99</li>
                            <li>D: 50–59.99</li>
                            <li>E: 40–49.99</li>
                            <li>F: 0–39.99</li>
                        </ul>
                    </div>

                    <div class="alert alert-warning mb-0">
                        <strong>Important:</strong>
                        Score ranges must not overlap with another
                        grading range.
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection