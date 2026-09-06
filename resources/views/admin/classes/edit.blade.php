@extends('backend.layout.default')

@section('title', 'Edit Class')

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-12">

            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0 font-size-18">
                    Edit Class
                </h4>

                <div class="page-title-right">
                    <a href="{{ route('admin.classes.index') }}"
                       class="btn btn-light me-1">
                        <i class="bx bx-arrow-back me-1"></i>
                        Back to Classes
                    </a>

                    <a href="{{ route('admin.classes.show', $class) }}"
                       class="btn btn-info">
                        <i class="bx bx-show me-1"></i>
                        View Class
                    </a>
                </div>

            </div>

        </div>
    </div>

    @if($errors->any())

        <div class="alert alert-danger">
            <strong>Please correct the following errors:</strong>

            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>

    @endif

    <div class="row">

        <div class="col-lg-8">

            <div class="card">

                <div class="card-body">

                    <h4 class="card-title mb-4">
                        Class Information
                    </h4>

                    <form method="POST"
                          action="{{ route('admin.classes.update', $class) }}">

                        @csrf
                        @method('PUT')

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label for="name" class="form-label">
                                    Class Name
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                       id="name"
                                       name="name"
                                       value="{{ old('name', $class->name) }}"
                                       class="form-control @error('name') is-invalid @enderror"
                                       required>

                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            <div class="col-md-6 mb-3">

                                <label for="code" class="form-label">
                                    Class Code
                                </label>

                                <input type="text"
                                       id="code"
                                       name="code"
                                       value="{{ old('code', $class->code) }}"
                                       class="form-control @error('code') is-invalid @enderror">

                                @error('code')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            <div class="col-md-6 mb-3">

                                <label for="department_id" class="form-label">
                                    Department
                                </label>

                                <select id="department_id"
                                        name="department_id"
                                        class="form-select @error('department_id') is-invalid @enderror">

                                    <option value="">
                                        Select Department
                                    </option>

                                    @foreach($departments as $department)

                                        <option value="{{ $department->id }}"
                                            @selected(
                                                old(
                                                    'department_id',
                                                    $class->department_id
                                                ) == $department->id
                                            )>
                                            {{ $department->name }}

                                            @if($department->code)
                                                ({{ $department->code }})
                                            @endif
                                        </option>

                                    @endforeach

                                </select>

                                @error('department_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            <div class="col-md-6 mb-3">

                                <label for="level_id" class="form-label">
                                    Level
                                </label>

                                <select id="level_id"
                                        name="level_id"
                                        class="form-select @error('level_id') is-invalid @enderror">

                                    <option value="">
                                        Select Level
                                    </option>

                                    @foreach($levels as $level)

                                        <option value="{{ $level->id }}"
                                            @selected(
                                                old(
                                                    'level_id',
                                                    $class->level_id
                                                ) == $level->id
                                            )>
                                            {{ $level->name }}

                                            @if($level->code)
                                                ({{ $level->code }})
                                            @endif
                                        </option>

                                    @endforeach

                                </select>

                                @error('level_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            <div class="col-12 mb-3">

                                <label for="description" class="form-label">
                                    Description
                                </label>

                                <textarea id="description"
                                          name="description"
                                          rows="4"
                                          class="form-control @error('description') is-invalid @enderror">{{ old('description', $class->description) }}</textarea>

                                @error('description')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            <div class="col-12 mb-4">

                                <div class="form-check form-switch">

                                    <input type="hidden"
                                           name="is_active"
                                           value="0">

                                    <input class="form-check-input"
                                           type="checkbox"
                                           role="switch"
                                           id="is_active"
                                           name="is_active"
                                           value="1"
                                           @checked(old('is_active', $class->is_active))>

                                    <label class="form-check-label"
                                           for="is_active">
                                        Active Class
                                    </label>

                                </div>

                                <small class="text-muted">
                                    Inactive classes will not appear in active class selections.
                                </small>

                            </div>

                        </div>

                        <div class="d-flex justify-content-end gap-2">

                            <a href="{{ route('admin.classes.show', $class) }}"
                               class="btn btn-light">
                                Cancel
                            </a>

                            <button type="submit"
                                    class="btn btn-primary">
                                <i class="bx bx-save me-1"></i>
                                Update Class
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

        <div class="col-lg-4">

            <div class="card">

                <div class="card-body">

                    <h4 class="card-title mb-3">
                        Current Class
                    </h4>

                    <h5 class="mb-1">
                        {{ $class->name }}
                    </h5>

                    @if($class->code)
                        <p class="text-muted mb-3">
                            {{ $class->code }}
                        </p>
                    @endif

                    <hr>

                    <div class="mb-3">
                        <small class="text-muted d-block">
                            Department
                        </small>

                        <strong>
                            {{ $class->department?->name ?: '—' }}
                        </strong>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block">
                            Level
                        </small>

                        <strong>
                            {{ $class->level?->name ?: '—' }}
                        </strong>
                    </div>

                    <div>
                        <small class="text-muted d-block">
                            Status
                        </small>

                        @if($class->is_active)
                            <span class="badge bg-success-subtle text-success">
                                Active
                            </span>
                        @else
                            <span class="badge bg-danger-subtle text-danger">
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