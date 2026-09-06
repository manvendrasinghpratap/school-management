@extends('backend.layout.default')

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0">Edit Department</h4>

                <div class="page-title-right">
                    <a href="{{ route('admin.departments.show', $department) }}"
                       class="btn btn-light">
                        <i class="bx bx-arrow-back me-1"></i>
                        Back to Department
                    </a>
                </div>

            </div>
        </div>
    </div>

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">

        {{-- Edit Form --}}
        <div class="col-lg-8">

            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">
                        Department Information
                    </h5>
                </div>

                <div class="card-body">

                    <form method="POST"
                          action="{{ route('admin.departments.update', $department) }}">

                        @csrf
                        @method('PUT')

                        {{-- Department Name --}}
                        <div class="mb-3">

                            <label for="name" class="form-label">
                                Department Name
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                name="name"
                                id="name"
                                value="{{ old('name', $department->name) }}"
                                class="form-control @error('name') is-invalid @enderror"
                                maxlength="255"
                                required>

                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        {{-- Department Code --}}
                        <div class="mb-3">

                            <label for="code" class="form-label">
                                Department Code
                            </label>

                            <input
                                type="text"
                                name="code"
                                id="code"
                                value="{{ old('code', $department->code) }}"
                                class="form-control @error('code') is-invalid @enderror"
                                maxlength="100">

                            <div class="form-text">
                                Department code must be unique within your school.
                            </div>

                            @error('code')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        {{-- Description --}}
                        <div class="mb-3">

                            <label for="description" class="form-label">
                                Description
                            </label>

                            <textarea
                                name="description"
                                id="description"
                                rows="5"
                                maxlength="5000"
                                class="form-control @error('description') is-invalid @enderror"
                                placeholder="Enter a description...">{{ old('description', $department->description) }}</textarea>

                            @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        {{-- Status --}}
                        <div class="mb-3">

                            <label class="form-label d-block">
                                Status
                            </label>

                            <div class="form-check form-switch mt-2">

                                <input
                                    type="hidden"
                                    name="is_active"
                                    value="0">

                                <input
                                    type="checkbox"
                                    name="is_active"
                                    value="1"
                                    class="form-check-input"
                                    id="is_active"
                                    @checked(old('is_active', $department->is_active))>

                                <label class="form-check-label" for="is_active">
                                    Active
                                </label>

                            </div>

                        </div>

                        {{-- Buttons --}}
                        <div class="mt-4">

                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save me-1"></i>
                                Update Department
                            </button>

                            <a href="{{ route('admin.departments.show', $department) }}"
                               class="btn btn-light ms-1">
                                Cancel
                            </a>

                        </div>

                    </form>

                </div>

            </div>

        </div>

        {{-- Current Department --}}
        <div class="col-lg-4">

            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">
                        Current Department
                    </h5>
                </div>

                <div class="card-body">

                    <h5 class="mb-1">
                        {{ $department->name }}
                    </h5>

                    @if($department->code)
                        <p class="text-muted mb-3">
                            {{ $department->code }}
                        </p>
                    @endif

                    <div class="mb-3">
                        <div class="text-muted small">
                            Assigned Classes
                        </div>

                        <strong>
                            {{ $department->classes()->count() }}
                        </strong>
                    </div>

                    <div>
                        <div class="text-muted small">
                            Status
                        </div>

                        @if($department->is_active)
                            <span class="badge bg-success">
                                Active
                            </span>
                        @else
                            <span class="badge bg-secondary">
                                Inactive
                            </span>
                        @endif
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
@endsection