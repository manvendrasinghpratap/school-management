@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-12">

            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Edit Staff</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.staff.index') }}">Staff</a>
                        </li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>

        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        action="{{ route('admin.staff.update', $staff) }}"
        method="POST"
        enctype="multipart/form-data"
    >
        @csrf
        @method('PUT')

        <div class="row">

            <div class="col-xl-8">

                <div class="card">

                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            Staff Information
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label for="staff_number" class="form-label">
                                    Staff Number <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    class="form-control @error('staff_number') is-invalid @enderror"
                                    id="staff_number"
                                    name="staff_number"
                                    value="{{ old('staff_number', $staff->staff_number) }}"
                                    required
                                >

                                @error('staff_number')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="staff_type" class="form-label">
                                    Staff Type
                                </label>

                                <input
                                    type="text"
                                    class="form-control @error('staff_type') is-invalid @enderror"
                                    id="staff_type"
                                    name="staff_type"
                                    value="{{ old('staff_type', $staff->staff_type) }}"
                                >

                                @error('staff_type')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="first_name" class="form-label">
                                    First Name <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    class="form-control @error('first_name') is-invalid @enderror"
                                    id="first_name"
                                    name="first_name"
                                    value="{{ old('first_name', $staff->first_name) }}"
                                    required
                                >

                                @error('first_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="middle_name" class="form-label">
                                    Middle Name
                                </label>

                                <input
                                    type="text"
                                    class="form-control @error('middle_name') is-invalid @enderror"
                                    id="middle_name"
                                    name="middle_name"
                                    value="{{ old('middle_name', $staff->middle_name) }}"
                                >

                                @error('middle_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="last_name" class="form-label">
                                    Last Name <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    class="form-control @error('last_name') is-invalid @enderror"
                                    id="last_name"
                                    name="last_name"
                                    value="{{ old('last_name', $staff->last_name) }}"
                                    required
                                >

                                @error('last_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="department_id" class="form-label">
                                    Department
                                </label>

                                <select
                                    class="form-select @error('department_id') is-invalid @enderror"
                                    id="department_id"
                                    name="department_id"
                                >
                                    <option value="">Select Department</option>

                                    @foreach($departments as $department)
                                        <option
                                            value="{{ $department->id }}"
                                            @selected(old('department_id', $staff->department_id) == $department->id)
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

                            <div class="col-md-6 mb-3">
                                <label for="user_id" class="form-label">
                                    System User
                                </label>

                                <select
                                    class="form-select @error('user_id') is-invalid @enderror"
                                    id="user_id"
                                    name="user_id"
                                >
                                    <option value="">No User Account</option>

                                    @foreach($users as $user)
                                        <option
                                            value="{{ $user->id }}"
                                            @selected(old('user_id', $staff->user_id) == $user->id)
                                        >
                                            {{ $user->name }} — {{ $user->email }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('user_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="date_of_birth" class="form-label">
                                    Date of Birth
                                </label>

                                <input
                                    type="date"
                                    class="form-control @error('date_of_birth') is-invalid @enderror"
                                    id="date_of_birth"
                                    name="date_of_birth"
                                    value="{{ old('date_of_birth', $staff->date_of_birth?->format('Y-m-d')) }}"
                                >

                                @error('date_of_birth')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="employment_date" class="form-label">
                                    Employment Date
                                </label>

                                <input
                                    type="date"
                                    class="form-control @error('employment_date') is-invalid @enderror"
                                    id="employment_date"
                                    name="employment_date"
                                    value="{{ old('employment_date', $staff->employment_date?->format('Y-m-d')) }}"
                                >

                                @error('employment_date')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="gender" class="form-label">
                                    Gender
                                </label>

                                <select
                                    class="form-select @error('gender') is-invalid @enderror"
                                    id="gender"
                                    name="gender"
                                >
                                    <option value="">Select Gender</option>

                                    <option
                                        value="male"
                                        @selected(old('gender', $staff->gender) === 'male')
                                    >
                                        Male
                                    </option>

                                    <option
                                        value="female"
                                        @selected(old('gender', $staff->gender) === 'female')
                                    >
                                        Female
                                    </option>

                                    <option
                                        value="other"
                                        @selected(old('gender', $staff->gender) === 'other')
                                    >
                                        Other
                                    </option>
                                </select>

                                @error('gender')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="nationality" class="form-label">
                                    Nationality
                                </label>

                                <input
                                    type="text"
                                    class="form-control @error('nationality') is-invalid @enderror"
                                    id="nationality"
                                    name="nationality"
                                    value="{{ old('nationality', $staff->nationality) }}"
                                >

                                @error('nationality')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="phone" class="form-label">
                                    Phone
                                </label>

                                <input
                                    type="text"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    id="phone"
                                    name="phone"
                                    value="{{ old('phone', $staff->phone) }}"
                                >

                                @error('phone')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">
                                    Status <span class="text-danger">*</span>
                                </label>

                                <select
                                    class="form-select @error('status') is-invalid @enderror"
                                    id="status"
                                    name="status"
                                    required
                                >
                                    <option
                                        value="active"
                                        @selected(old('status', $staff->status) === 'active')
                                    >
                                        Active
                                    </option>

                                    <option
                                        value="inactive"
                                        @selected(old('status', $staff->status) === 'inactive')
                                    >
                                        Inactive
                                    </option>

                                    <option
                                        value="terminated"
                                        @selected(old('status', $staff->status) === 'terminated')
                                    >
                                        Terminated
                                    </option>
                                </select>

                                @error('status')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="photo" class="form-label">
                                    Staff Photo
                                </label>

                                <input
                                    type="file"
                                    class="form-control @error('photo') is-invalid @enderror"
                                    id="photo"
                                    name="photo"
                                    accept=".jpg,.jpeg,.png,.webp"
                                >

                                @error('photo')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <div class="form-text">
                                    Leave empty to keep the current photo.
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Current Photo --}}
            <div class="col-xl-4">

                <div class="card">

                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            Current Photo
                        </h5>
                    </div>

                    <div class="card-body text-center">

                        @if($staff->photo)

                            <img
                                src="{{ asset('storage/' . $staff->photo) }}"
                                alt="{{ $staff->full_name }}"
                                class="img-fluid rounded"
                                style="max-height: 250px;"
                            >

                        @else

                            <div class="avatar-xl mx-auto">
                                <span class="avatar-title rounded bg-primary-subtle text-primary fs-3">
                                    {{ strtoupper(substr($staff->first_name, 0, 1) . substr($staff->last_name, 0, 1)) }}
                                </span>
                            </div>

                            <p class="text-muted mt-3 mb-0">
                                No photo uploaded.
                            </p>

                        @endif

                    </div>

                </div>

                <div class="card">

                    <div class="card-body">

                        <div class="d-flex justify-content-end gap-2">

                            <a
                                href="{{ route('admin.staff.show', $staff) }}"
                                class="btn btn-light"
                            >
                                <i class="ri-close-line me-1"></i>
                                Cancel
                            </a>

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                <i class="ri-save-line me-1"></i>
                                Update Staff
                            </button>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection