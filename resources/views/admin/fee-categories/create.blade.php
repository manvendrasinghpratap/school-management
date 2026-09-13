@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Title --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">CREATE FEE CATEGORY</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">Finance</li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.fee-categories.index') }}">
                                Fee Categories
                            </a>
                        </li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please correct the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">

        {{-- Form --}}
        <div class="col-lg-8">

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-1">Add Fee Category</h4>
                    <p class="card-title-desc mb-0">
                        Create a fee category that can be used throughout the school's finance module.
                    </p>
                </div>

                <div class="card-body">

                    <form method="POST" action="{{ route('admin.fee-categories.store') }}">
                        @csrf

                        {{-- Name --}}
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                Fee Category Name <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                class="form-control @error('name') is-invalid @enderror"
                                id="name"
                                name="name"
                                value="{{ old('name') }}"
                                placeholder="e.g. Tuition"
                                maxlength="255"
                                required
                            >

                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Code --}}
                        <div class="mb-3">
                            <label for="code" class="form-label">
                                Code
                            </label>

                            <input
                                type="text"
                                class="form-control @error('code') is-invalid @enderror"
                                id="code"
                                name="code"
                                value="{{ old('code') }}"
                                placeholder="e.g. TUITION"
                                maxlength="100"
                            >

                            @error('code')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div class="form-text">
                                Optional internal code for this fee category.
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="mb-3">
                            <label for="description" class="form-label">
                                Description
                            </label>

                            <textarea
                                class="form-control @error('description') is-invalid @enderror"
                                id="description"
                                name="description"
                                rows="4"
                                maxlength="5000"
                                placeholder="Describe what this fee category is used for..."
                            >{{ old('description') }}</textarea>

                            @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Active --}}
                        <div class="mb-4">
                            <div class="form-check form-switch form-switch-md">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    id="is_active"
                                    name="is_active"
                                    value="1"
                                    {{ old('is_active', true) ? 'checked' : '' }}
                                >

                                <label class="form-check-label" for="is_active">
                                    Active
                                </label>
                            </div>

                            <div class="form-text">
                                Active categories can be used when configuring fee structures and invoices.
                            </div>
                        </div>

                        {{-- Buttons --}}
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save me-1"></i>
                                Save Fee Category
                            </button>

                            <a
                                href="{{ route('admin.fee-categories.index') }}"
                                class="btn btn-light"
                            >
                                <i class="bx bx-arrow-back me-1"></i>
                                Cancel
                            </a>
                        </div>

                    </form>

                </div>
            </div>

        </div>

        {{-- Help --}}
        <div class="col-lg-4">

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Examples</h4>
                </div>

                <div class="card-body">

                    <p class="text-muted">
                        Common fee categories may include:
                    </p>

                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-xs me-3">
                            <span class="avatar-title rounded-circle bg-soft-primary text-primary">
                                <i class="bx bx-book"></i>
                            </span>
                        </div>
                        <div>
                            <h6 class="mb-0">Tuition</h6>
                            <small class="text-muted">Regular school fees</small>
                        </div>
                    </div>

                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-xs me-3">
                            <span class="avatar-title rounded-circle bg-soft-success text-success">
                                <i class="bx bx-user-plus"></i>
                            </span>
                        </div>
                        <div>
                            <h6 class="mb-0">Admission</h6>
                            <small class="text-muted">Admission-related charges</small>
                        </div>
                    </div>

                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-xs me-3">
                            <span class="avatar-title rounded-circle bg-soft-warning text-warning">
                                <i class="bx bx-edit"></i>
                            </span>
                        </div>
                        <div>
                            <h6 class="mb-0">Examination</h6>
                            <small class="text-muted">Examination charges</small>
                        </div>
                    </div>

                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-xs me-3">
                            <span class="avatar-title rounded-circle bg-soft-info text-info">
                                <i class="bx bx-plus-circle"></i>
                            </span>
                        </div>
                        <div>
                            <h6 class="mb-0">Extra</h6>
                            <small class="text-muted">Additional school charges</small>
                        </div>
                    </div>

                    <div class="d-flex align-items-center">
                        <div class="avatar-xs me-3">
                            <span class="avatar-title rounded-circle bg-soft-danger text-danger">
                                <i class="bx bx-error"></i>
                            </span>
                        </div>
                        <div>
                            <h6 class="mb-0">Fine</h6>
                            <small class="text-muted">Late or other penalties</small>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>

</div>

@endsection