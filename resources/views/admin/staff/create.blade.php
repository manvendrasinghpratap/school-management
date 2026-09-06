@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Add Staff</h4>

                <div class="page-title-right">
                    <a href="{{ route('admin.staff.index') }}" class="btn btn-secondary">
                        <i class="mdi mdi-arrow-left me-1"></i>
                        Back to Staff
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <h5 class="alert-heading">Please correct the following errors:</h5>

            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        method="POST"
        action="{{ route('admin.staff.store') }}"
        enctype="multipart/form-data"
    >
        @csrf

        <div class="row">

            {{-- Basic Information --}}
            <div class="col-lg-8">

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">
                            Staff Information
                        </h4>
                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            {{-- Staff Number --}}
                            <div class="col-md-6">
                                <label class="form-label">
                                    Staff Number <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="staff_number"
                                    class="form-control @error('staff_number') is-invalid @enderror"
                                    value="{{ old('staff_number') }}"
                                    required
                                >

                                @error('staff_number')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Staff Type --}}
                            <div class="col-md-6">
                                <label class="form-label">
                                    Staff Type
                                </label>

                                <input
                                    type="text"
                                    name="staff_type"
                                    class="form-control @error('staff_type') is-invalid @enderror"
                                    value="{{ old('staff_type') }}"
                                    placeholder="Teacher, Accountant, Secretary..."
                                >

                                @error('staff_type')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- First Name --}}
                            <div class="col-md-4">
                                <label class="form-label">
                                    First Name <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="first_name"
                                    class="form-control @error('first_name') is-invalid @enderror"
                                    value="{{ old('first_name') }}"
                                    required
                                >

                                @error('first_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Middle Name --}}
                            <div class="col-md-4">
                                <label class="form-label">
                                    Middle Name
                                </label>

                                <input
                                    type="text"
                                    name="middle_name"
                                    class="form-control @error('middle_name') is-invalid @enderror"
                                    value="{{ old('middle_name') }}"
                                >

                                @error('middle_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Last Name --}}
                            <div class="col-md-4">
                                <label class="form-label">
                                    Last Name <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="last_name"
                                    class="form-control @error('last_name') is-invalid @enderror"
                                    value="{{ old('last_name') }}"
                                    required
                                >

                                @error('last_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Department --}}
                            <div class="col-md-6">
                                <label class="form-label">
                                    Department
                                </label>

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
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- System User --}}
                            <div class="col-md-6">
                                <label class="form-label">
                                    Link System User
                                </label>

                                <select
                                    name="user_id"
                                    class="form-select @error('user_id') is-invalid @enderror"
                                >
                                    <option value="">
                                        No System User
                                    </option>

                                    @foreach($users as $user)
                                        <option
                                            value="{{ $user->id }}"
                                            @selected(old('user_id') == $user->id)
                                        >
                                            {{ $user->name }}
                                            @if($user->email)
                                                — {{ $user->email }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>

                                @error('user_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <small class="text-muted">
                                    Optional. Use this when the staff member needs
                                    a system login.
                                </small>
                            </div>

                            {{-- Date of Birth --}}
                            <div class="col-md-4">
                                <label class="form-label">
                                    Date of Birth
                                </label>

                                <input
                                    type="date"
                                    name="date_of_birth"
                                    class="form-control @error('date_of_birth') is-invalid @enderror"
                                    value="{{ old('date_of_birth') }}"
                                >

                                @error('date_of_birth')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Gender --}}
                            <div class="col-md-4">
                                <label class="form-label">
                                    Gender
                                </label>

                                <select
                                    name="gender"
                                    class="form-select @error('gender') is-invalid @enderror"
                                >
                                    <option value="">Select Gender</option>
                                    <option value="male" @selected(old('gender') === 'male')>
                                        Male
                                    </option>
                                    <option value="female" @selected(old('gender') === 'female')>
                                        Female
                                    </option>
                                    <option value="other" @selected(old('gender') === 'other')>
                                        Other
                                    </option>
                                </select>

                                @error('gender')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Nationality --}}
                            <div class="col-md-4">
                                <label class="form-label">
                                    Nationality
                                </label>

                                <input
                                    type="text"
                                    name="nationality"
                                    class="form-control @error('nationality') is-invalid @enderror"
                                    value="{{ old('nationality') }}"
                                >

                                @error('nationality')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Phone --}}
                            <div class="col-md-6">
                                <label class="form-label">
                                    Phone
                                </label>

                                <input
                                    type="text"
                                    name="phone"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    value="{{ old('phone') }}"
                                >

                                @error('phone')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Employment Date --}}
                            <div class="col-md-6">
                                <label class="form-label">
                                    Employment Date
                                </label>

                                <input
                                    type="date"
                                    name="employment_date"
                                    class="form-control @error('employment_date') is-invalid @enderror"
                                    value="{{ old('employment_date') }}"
                                >

                                @error('employment_date')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Status --}}
                            <div class="col-md-6">
                                <label class="form-label">
                                    Status <span class="text-danger">*</span>
                                </label>

                                <select
                                    name="status"
                                    class="form-select @error('status') is-invalid @enderror"
                                    required
                                >
                                    <option value="active" @selected(old('status', 'active') === 'active')>
                                        Active
                                    </option>

                                    <option value="inactive" @selected(old('status') === 'inactive')>
                                        Inactive
                                    </option>

                                    <option value="terminated" @selected(old('status') === 'terminated')>
                                        Terminated
                                    </option>
                                </select>

                                @error('status')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                        </div>

                    </div>
                </div>

            </div>

            {{-- Photo --}}
            <div class="col-lg-4">

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">
                            Staff Photo
                        </h4>
                    </div>

                    <div class="card-body">

                        <div class="text-center mb-3">

                            <div
                                id="photoPreview"
                                class="mx-auto rounded-circle bg-light d-flex align-items-center justify-content-center"
                                style="width: 150px; height: 150px; overflow: hidden;"
                            >
                                <i class="mdi mdi-account text-muted" style="font-size: 80px;"></i>
                            </div>

                        </div>

                        <label class="form-label">
                            Upload Photo
                        </label>

                        <input
                            type="file"
                            name="photo"
                            id="photo"
                            class="form-control @error('photo') is-invalid @enderror"
                            accept=".jpg,.jpeg,.png,.webp"
                        >

                        @error('photo')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                        <small class="text-muted">
                            JPG, JPEG, PNG or WEBP. Maximum 5MB.
                        </small>

                    </div>
                </div>

            </div>

        </div>

        {{-- Form Actions --}}
        <div class="card">
            <div class="card-body">

                <div class="d-flex justify-content-end gap-2">

                    <a
                        href="{{ route('admin.staff.index') }}"
                        class="btn btn-light"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="mdi mdi-content-save me-1"></i>
                        Save Staff
                    </button>

                </div>

            </div>
        </div>

    </form>

</div>

@endsection

@push('script')
<script>
    document.getElementById('photo')?.addEventListener('change', function (event) {
        const file = event.target.files[0];
        const preview = document.getElementById('photoPreview');

        if (!file) {
            preview.innerHTML =
                '<i class="mdi mdi-account text-muted" style="font-size: 80px;"></i>';
            return;
        }

        const reader = new FileReader();

        reader.onload = function (e) {
            preview.innerHTML =
                '<img src="' + e.target.result + '" alt="Photo Preview" style="width:100%;height:100%;object-fit:cover;">';
        };

        reader.readAsDataURL(file);
    });
</script>
@endpush