@extends('backend.layout.default')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Add Subject / Course</h4>
            <p class="text-muted mb-0">
                Create a new subject or course.
            </p>
        </div>

        <a href="{{ route('admin.courses.index') }}" class="btn btn-light">
            <i class="mdi mdi-arrow-left"></i>
            Back
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">

            <form
                action="{{ route('admin.courses.store') }}"
                method="POST"
            >
                @csrf

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Course / Subject Code <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="course_code"
                            class="form-control @error('course_code') is-invalid @enderror"
                            value="{{ old('course_code') }}"
                            placeholder="e.g. ENG101"
                            required
                        >

                        @error('course_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Subject / Course Name <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}"
                            placeholder="e.g. English Language"
                            required
                        >

                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Department</label>

                        <select
                            name="department_id"
                            class="form-select @error('department_id') is-invalid @enderror"
                        >
                            <option value="">Select Department</option>

                            @foreach($departments as $department)
                                <option
                                    value="{{ $department->id }}"
                                    @selected(old('department_id') == $department->id)
                                >
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('department_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Credit Hours</label>

                        <input
                            type="number"
                            name="credit_hours"
                            class="form-control @error('credit_hours') is-invalid @enderror"
                            value="{{ old('credit_hours') }}"
                            min="0"
                            max="999.99"
                            step="0.01"
                            placeholder="e.g. 3"
                        >

                        @error('credit_hours')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Status</label>

                        <select name="is_active" class="form-select">
                            <option value="1" @selected(old('is_active', '1') == '1')>
                                Active
                            </option>
                            <option value="0" @selected(old('is_active') === '0')>
                                Inactive
                            </option>
                        </select>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Description</label>

                        <textarea
                            name="description"
                            rows="4"
                            class="form-control @error('description') is-invalid @enderror"
                            placeholder="Optional description"
                        >{{ old('description') }}</textarea>

                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-4">
                        <div class="form-check">
                            <input
                                type="hidden"
                                name="is_compulsory"
                                value="0"
                            >

                            <input
                                type="checkbox"
                                name="is_compulsory"
                                value="1"
                                class="form-check-input"
                                id="is_compulsory"
                                @checked(old('is_compulsory'))
                            >

                            <label
                                class="form-check-label"
                                for="is_compulsory"
                            >
                                This is a compulsory subject/course
                            </label>
                        </div>
                    </div>

                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a
                        href="{{ route('admin.courses.index') }}"
                        class="btn btn-light"
                    >
                        Cancel
                    </a>

                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-content-save"></i>
                        Save Subject / Course
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection