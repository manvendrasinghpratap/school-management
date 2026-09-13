@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Title --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">EDIT FEE CATEGORY</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">Finance</li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.fee-categories.index') }}">
                                Fee Categories
                            </a>
                        </li>
                        <li class="breadcrumb-item active">Edit</li>
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

        <div class="col-lg-8">

            <div class="card">

                <div class="card-header">
                    <h4 class="card-title mb-1">Edit Fee Category</h4>
                    <p class="card-title-desc mb-0">
                        Update the fee category information.
                    </p>
                </div>

                <div class="card-body">

                    <form
                        method="POST"
                        action="{{ route('admin.fee-categories.update', $feeCategory) }}"
                    >
                        @csrf
                        @method('PUT')

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
                                value="{{ old('name', $feeCategory->name) }}"
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
                                value="{{ old('code', $feeCategory->code) }}"
                                maxlength="100"
                            >

                            @error('code')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div class="form-text">
                                Optional internal code for this category.
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
                            >{{ old('description', $feeCategory->description) }}</textarea>

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
                                    {{ old('is_active', $feeCategory->is_active) ? 'checked' : '' }}
                                >

                                <label class="form-check-label" for="is_active">
                                    Active
                                </label>

                            </div>

                            <div class="form-text">
                                Inactive categories cannot be used for new finance configuration.
                            </div>
                        </div>

                        {{-- Buttons --}}
                        <div class="d-flex gap-2">

                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save me-1"></i>
                                Update Fee Category
                            </button>

                            <a
                                href="{{ route('admin.fee-categories.show', $feeCategory) }}"
                                class="btn btn-light"
                            >
                                <i class="bx bx-x me-1"></i>
                                Cancel
                            </a>

                        </div>

                    </form>

                </div>
            </div>

        </div>

        {{-- Information --}}
        <div class="col-lg-4">

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Category Information</h4>
                </div>

                <div class="card-body">

                    <div class="mb-3">
                        <label class="text-muted d-block">Current Name</label>
                        <strong>{{ $feeCategory->name }}</strong>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted d-block">Current Code</label>
                        <strong>
                            {{ $feeCategory->code ?: '—' }}
                        </strong>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted d-block">Fee Structures</label>
                        <strong>
                            {{ $feeCategory->feeStructures()->count() }}
                        </strong>
                    </div>

                    <div>
                        <label class="text-muted d-block">Status</label>

                        @if($feeCategory->is_active)
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