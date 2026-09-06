@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0">
                    Add Section / Stream
                </h4>

                <div class="page-title-right">
                    <a href="{{ route('admin.sections.index') }}"
                       class="btn btn-light">
                        <i class="bx bx-arrow-back me-1"></i>
                        Back to Sections
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

        {{-- Form --}}
        <div class="col-lg-8">

            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">
                        Section Information
                    </h5>
                </div>

                <div class="card-body">

                    <form method="POST"
                          action="{{ route('admin.sections.store') }}">

                        @csrf

                        {{-- Class --}}
                        <div class="mb-3">

                            <label for="class_id"
                                   class="form-label">
                                Class
                                <span class="text-danger">*</span>
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
                                        @selected(old('class_id') == $class->id)>

                                        {{ $class->name }}

                                        @if($class->code)
                                            ({{ $class->code }})
                                        @endif

                                    </option>

                                @endforeach

                            </select>

                            @error('class_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="row">

                            {{-- Section Name --}}
                            <div class="col-md-6 mb-3">

                                <label for="name"
                                       class="form-label">
                                    Section Name
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                       name="name"
                                       id="name"
                                       value="{{ old('name') }}"
                                       class="form-control @error('name') is-invalid @enderror"
                                       placeholder="e.g. A, B, C"
                                       maxlength="100"
                                       required>

                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            {{-- Code --}}
                            <div class="col-md-6 mb-3">

                                <label for="code"
                                       class="form-label">
                                    Section Code
                                </label>

                                <input type="text"
                                       name="code"
                                       id="code"
                                       value="{{ old('code') }}"
                                       class="form-control @error('code') is-invalid @enderror"
                                       placeholder="e.g. JSS1-A"
                                       maxlength="50">

                                @error('code')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                        <div class="row">

                            {{-- Capacity --}}
                            <div class="col-md-6 mb-3">

                                <label for="capacity"
                                       class="form-label">
                                    Capacity
                                </label>

                                <input type="number"
                                       name="capacity"
                                       id="capacity"
                                       value="{{ old('capacity') }}"
                                       class="form-control @error('capacity') is-invalid @enderror"
                                       placeholder="e.g. 40"
                                       min="1"
                                       max="100000">

                                <div class="form-text">
                                    Leave blank if there is no fixed capacity.
                                </div>

                                @error('capacity')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            {{-- Status --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label d-block">
                                    Status
                                </label>

                                <div class="form-check form-switch mt-2">

                                    <input type="hidden"
                                           name="is_active"
                                           value="0">

                                    <input type="checkbox"
                                           name="is_active"
                                           value="1"
                                           class="form-check-input"
                                           id="is_active"
                                           @checked(old('is_active', true))>

                                    <label class="form-check-label"
                                           for="is_active">
                                        Active
                                    </label>

                                </div>

                            </div>

                        </div>

                        {{-- Submit --}}
                        <div class="mt-4">

                            <button type="submit"
                                    class="btn btn-primary">
                                <i class="bx bx-save me-1"></i>
                                Save Section
                            </button>

                            <a href="{{ route('admin.sections.index') }}"
                               class="btn btn-light ms-1">
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
                    <h5 class="card-title mb-0">
                        Section / Stream
                    </h5>
                </div>

                <div class="card-body">

                    <p class="text-muted">
                        Sections or streams divide students within a class.
                    </p>

                    <ul class="text-muted mb-0">
                        <li class="mb-2">
                            Each section belongs to one class.
                        </li>
                        <li class="mb-2">
                            Section names must be unique within a class.
                        </li>
                        <li class="mb-2">
                            Capacity is optional.
                        </li>
                        <li>
                            Inactive sections remain in historical records.
                        </li>
                    </ul>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection