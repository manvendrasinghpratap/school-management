@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Title --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Staff Details</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.staff.index') }}">Staff</a>
                        </li>
                        <li class="breadcrumb-item active">
                            {{ $staff->full_name }}
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>


    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ri-checkbox-circle-line me-2"></i>
            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>
        </div>
    @endif


    <div class="row">

        {{-- LEFT PROFILE --}}
        <div class="col-xl-4">

            <div class="card">

                <div class="card-body">

                    <div class="text-center">

                        {{-- Photo --}}
                        @if($staff->photo)

                            <div class="mb-3">
                                <img
                                    src="{{ asset('storage/' . $staff->photo) }}"
                                    alt="{{ $staff->full_name }}"
                                    class="rounded-circle avatar-xl object-fit-cover"
                                >
                            </div>

                        @else

                            <div class="avatar-xl mx-auto mb-3">
                                <span class="avatar-title rounded-circle bg-primary text-white fs-3">
                                    {{ strtoupper(substr($staff->first_name, 0, 1) . substr($staff->last_name, 0, 1)) }}
                                </span>
                            </div>

                        @endif


                        {{-- Name --}}
                        <h5 class="mb-1">
                            {{ $staff->full_name }}
                        </h5>

                        {{-- Staff Number --}}
                        <p class="text-muted mb-3">
                            {{ $staff->staff_number }}
                        </p>


                        {{-- Status --}}
                        @if($staff->status === 'active')

                            <span class="badge bg-success-subtle text-success px-3 py-2">
                                <i class="ri-checkbox-circle-line me-1"></i>
                                Active
                            </span>

                        @elseif($staff->status === 'inactive')

                            <span class="badge bg-warning-subtle text-warning px-3 py-2">
                                <i class="ri-pause-circle-line me-1"></i>
                                Inactive
                            </span>

                        @else

                            <span class="badge bg-danger-subtle text-danger px-3 py-2">
                                <i class="ri-close-circle-line me-1"></i>
                                Terminated
                            </span>

                        @endif

                    </div>


                    <div class="border-top mt-4 pt-4">

                        {{-- Department --}}
                        <div class="d-flex align-items-center mb-3">

                            <div class="avatar-xs me-3">
                                <span class="avatar-title rounded bg-primary-subtle text-primary">
                                    <i class="ri-building-line"></i>
                                </span>
                            </div>

                            <div>
                                <div class="text-muted fs-12">
                                    Department
                                </div>

                                <div class="fw-medium">
                                    {{ $staff->department?->name ?: 'Not assigned' }}
                                </div>
                            </div>

                        </div>


                        {{-- Staff Type --}}
                        <div class="d-flex align-items-center mb-3">

                            <div class="avatar-xs me-3">
                                <span class="avatar-title rounded bg-info-subtle text-info">
                                    <i class="ri-user-star-line"></i>
                                </span>
                            </div>

                            <div>
                                <div class="text-muted fs-12">
                                    Staff Type
                                </div>

                                <div class="fw-medium">
                                    {{ $staff->staff_type ?: 'Not specified' }}
                                </div>
                            </div>

                        </div>


                        {{-- Phone --}}
                        <div class="d-flex align-items-center mb-3">

                            <div class="avatar-xs me-3">
                                <span class="avatar-title rounded bg-success-subtle text-success">
                                    <i class="ri-phone-line"></i>
                                </span>
                            </div>

                            <div>
                                <div class="text-muted fs-12">
                                    Phone
                                </div>

                                <div class="fw-medium">
                                    {{ $staff->phone ?: 'Not provided' }}
                                </div>
                            </div>

                        </div>


                        {{-- Employment Date --}}
                        <div class="d-flex align-items-center">

                            <div class="avatar-xs me-3">
                                <span class="avatar-title rounded bg-warning-subtle text-warning">
                                    <i class="ri-calendar-line"></i>
                                </span>
                            </div>

                            <div>
                                <div class="text-muted fs-12">
                                    Employment Date
                                </div>

                                <div class="fw-medium">
                                    {{ $staff->employment_date?->format('d M Y') ?: 'Not provided' }}
                                </div>
                            </div>

                        </div>

                    </div>


                    {{-- Actions --}}
                    <div class="border-top mt-4 pt-4">

                        <div class="d-grid gap-2">

                            <a
                                href="{{ route('admin.staff.edit', $staff) }}"
                                class="btn btn-primary"
                            >
                                <i class="ri-edit-line me-1"></i>
                                Edit Staff
                            </a>

                            <a
                                href="{{ route('admin.staff.index') }}"
                                class="btn btn-light"
                            >
                                <i class="ri-arrow-left-line me-1"></i>
                                Back to Staff List
                            </a>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Instructor Card --}}
            @if($staff->instructor)

                <div class="card">

                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ri-presentation-line me-1"></i>
                            Instructor Profile
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="mb-3">
                            <div class="text-muted fs-12 mb-1">
                                Specialization
                            </div>

                            <div class="fw-medium">
                                {{ $staff->instructor->specialization ?: 'Not specified' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-muted fs-12 mb-1">
                                Qualification
                            </div>

                            <div class="fw-medium">
                                {{ $staff->instructor->qualification ?: 'Not specified' }}
                            </div>
                        </div>

                    </div>

                </div>

            @endif

        </div>


        {{-- RIGHT CONTENT --}}
        <div class="col-xl-8">

            {{-- Personal Information --}}
            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ri-user-line me-1"></i>
                        Personal Information
                    </h5>
                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-4 mb-4">
                            <div class="text-muted fs-12 mb-1">
                                First Name
                            </div>

                            <div class="fw-medium">
                                {{ $staff->first_name }}
                            </div>
                        </div>


                        <div class="col-md-4 mb-4">
                            <div class="text-muted fs-12 mb-1">
                                Middle Name
                            </div>

                            <div class="fw-medium">
                                {{ $staff->middle_name ?: '—' }}
                            </div>
                        </div>


                        <div class="col-md-4 mb-4">
                            <div class="text-muted fs-12 mb-1">
                                Last Name
                            </div>

                            <div class="fw-medium">
                                {{ $staff->last_name }}
                            </div>
                        </div>


                        <div class="col-md-4 mb-4">
                            <div class="text-muted fs-12 mb-1">
                                Gender
                            </div>

                            <div class="fw-medium text-capitalize">
                                {{ $staff->gender ?: '—' }}
                            </div>
                        </div>


                        <div class="col-md-4 mb-4">
                            <div class="text-muted fs-12 mb-1">
                                Date of Birth
                            </div>

                            <div class="fw-medium">
                                {{ $staff->date_of_birth?->format('d M Y') ?: '—' }}
                            </div>
                        </div>


                        <div class="col-md-4 mb-4">
                            <div class="text-muted fs-12 mb-1">
                                Nationality
                            </div>

                            <div class="fw-medium">
                                {{ $staff->nationality ?: '—' }}
                            </div>
                        </div>


                        <div class="col-md-4">
                            <div class="text-muted fs-12 mb-1">
                                Phone
                            </div>

                            <div class="fw-medium">
                                {{ $staff->phone ?: '—' }}
                            </div>
                        </div>

                    </div>

                </div>

            </div>


            {{-- Employment Information --}}
            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ri-briefcase-line me-1"></i>
                        Employment Information
                    </h5>
                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-4 mb-4">
                            <div class="text-muted fs-12 mb-1">
                                Staff Number
                            </div>

                            <div class="fw-medium">
                                {{ $staff->staff_number }}
                            </div>
                        </div>


                        <div class="col-md-4 mb-4">
                            <div class="text-muted fs-12 mb-1">
                                Staff Type
                            </div>

                            <div class="fw-medium">
                                {{ $staff->staff_type ?: '—' }}
                            </div>
                        </div>


                        <div class="col-md-4 mb-4">
                            <div class="text-muted fs-12 mb-1">
                                Department
                            </div>

                            <div class="fw-medium">
                                {{ $staff->department?->name ?: '—' }}
                            </div>
                        </div>


                        <div class="col-md-4">
                            <div class="text-muted fs-12 mb-1">
                                Employment Date
                            </div>

                            <div class="fw-medium">
                                {{ $staff->employment_date?->format('d M Y') ?: '—' }}
                            </div>
                        </div>


                        <div class="col-md-4">
                            <div class="text-muted fs-12 mb-1">
                                Status
                            </div>

                            <div>

                                @if($staff->status === 'active')
                                    <span class="badge bg-success">
                                        Active
                                    </span>
                                @elseif($staff->status === 'inactive')
                                    <span class="badge bg-warning">
                                        Inactive
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        Terminated
                                    </span>
                                @endif

                            </div>
                        </div>

                    </div>

                </div>

            </div>


            {{-- System Account --}}
            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ri-shield-user-line me-1"></i>
                        System User Account
                    </h5>
                </div>

                <div class="card-body">

                    @if($staff->user)

                        <div class="row">

                            <div class="col-md-6">

                                <div class="text-muted fs-12 mb-1">
                                    User Name
                                </div>

                                <div class="fw-medium">
                                    {{ $staff->user->name }}
                                </div>

                            </div>


                            <div class="col-md-6">

                                <div class="text-muted fs-12 mb-1">
                                    Email
                                </div>

                                <div class="fw-medium">
                                    {{ $staff->user->email ?: '—' }}
                                </div>

                            </div>

                        </div>

                    @else

                        <div class="d-flex align-items-center">

                            <div class="avatar-sm me-3">
                                <span class="avatar-title rounded bg-light text-muted">
                                    <i class="ri-user-unfollow-line fs-18"></i>
                                </span>
                            </div>

                            <div>
                                <h6 class="mb-1">
                                    No system account linked
                                </h6>

                                <p class="text-muted mb-0">
                                    This staff member does not currently have a login account.
                                </p>
                            </div>

                        </div>

                    @endif

                </div>

            </div>


            {{-- Record Information --}}
            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ri-information-line me-1"></i>
                        Record Information
                    </h5>
                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6">

                            <div class="text-muted fs-12 mb-1">
                                Created
                            </div>

                            <div class="fw-medium">
                                {{ $staff->created_at?->format('d M Y, h:i A') ?: '—' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted fs-12 mb-1">
                                Last Updated
                            </div>

                            <div class="fw-medium">
                                {{ $staff->updated_at?->format('d M Y, h:i A') ?: '—' }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
