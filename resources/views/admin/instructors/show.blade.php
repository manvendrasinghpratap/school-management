@extends('backend.layout.default')

@section('title', 'Instructor Details')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <div>
                    <h4 class="mb-sm-0">Instructor Details</h4>
                    <p class="text-muted mb-0">
                        View instructor profile and staff information.
                    </p>
                </div>

                <div class="page-title-right">
                    <a href="{{ route('admin.instructors.index') }}"
                       class="btn btn-light">
                        <i class="bx bx-arrow-back me-1"></i>
                        Back to Instructors
                    </a>
                </div>

            </div>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bx bx-check-circle me-1"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Main Content --}}
    <div class="row">

        {{-- Left Column --}}
        <div class="col-xl-4">

            {{-- Profile Card --}}
            <div class="card">

                <div class="card-body text-center">

                    {{-- Photo --}}
                    <div class="mb-3">

                        @if($instructor->staff?->photo)

                            <img src="{{ asset('storage/' . $instructor->staff->photo) }}"
                                 alt="{{ $instructor->staff->full_name }}"
                                 class="rounded-circle avatar-xl img-thumbnail">

                        @else

                            <div class="avatar-xl mx-auto">
                                <span class="avatar-title rounded-circle bg-primary-subtle text-primary fs-1">
                                    {{ strtoupper(substr($instructor->staff?->first_name ?? 'I', 0, 1)) }}
                                </span>
                            </div>

                        @endif

                    </div>

                    {{-- Name --}}
                    <h5 class="mb-1">
                        {{ $instructor->staff?->full_name ?? 'N/A' }}
                    </h5>

                    <p class="text-muted mb-3">
                        {{ $instructor->staff?->staff_number ?? 'N/A' }}
                    </p>

                    {{-- Status --}}
                    @if($instructor->staff?->status === 'active')

                        <span class="badge bg-success-subtle text-success px-3 py-2">
                            <i class="bx bx-check-circle me-1"></i>
                            Active
                        </span>

                    @elseif($instructor->staff?->status === 'inactive')

                        <span class="badge bg-warning-subtle text-warning px-3 py-2">
                            <i class="bx bx-pause-circle me-1"></i>
                            Inactive
                        </span>

                    @else

                        <span class="badge bg-danger-subtle text-danger px-3 py-2">
                            <i class="bx bx-x-circle me-1"></i>
                            {{ ucfirst($instructor->staff?->status ?? 'Unknown') }}
                        </span>

                    @endif

                    <hr class="my-4">

                    {{-- Quick Details --}}
                    <div class="text-start">

                        <div class="d-flex align-items-center mb-3">

                            <div class="avatar-xs me-3">
                                <span class="avatar-title rounded-circle bg-light text-muted">
                                    <i class="bx bx-building"></i>
                                </span>
                            </div>

                            <div>
                                <small class="text-muted d-block">
                                    Department
                                </small>

                                <span class="fw-medium">
                                    {{ $instructor->staff?->department?->name ?? '—' }}
                                </span>
                            </div>

                        </div>

                        <div class="d-flex align-items-center mb-3">

                            <div class="avatar-xs me-3">
                                <span class="avatar-title rounded-circle bg-light text-muted">
                                    <i class="bx bx-briefcase"></i>
                                </span>
                            </div>

                            <div>
                                <small class="text-muted d-block">
                                    Staff Type
                                </small>

                                <span class="fw-medium">
                                    {{ $instructor->staff?->staff_type ?? '—' }}
                                </span>
                            </div>

                        </div>

                        <div class="d-flex align-items-center mb-3">

                            <div class="avatar-xs me-3">
                                <span class="avatar-title rounded-circle bg-light text-muted">
                                    <i class="bx bx-phone"></i>
                                </span>
                            </div>

                            <div>
                                <small class="text-muted d-block">
                                    Phone
                                </small>

                                <span class="fw-medium">
                                    {{ $instructor->staff?->phone ?? '—' }}
                                </span>
                            </div>

                        </div>

                        <div class="d-flex align-items-center">

                            <div class="avatar-xs me-3">
                                <span class="avatar-title rounded-circle bg-light text-muted">
                                    <i class="bx bx-calendar"></i>
                                </span>
                            </div>

                            <div>
                                <small class="text-muted d-block">
                                    Employment Date
                                </small>

                                <span class="fw-medium">
                                    {{ $instructor->staff?->employment_date?->format('d M Y') ?? '—' }}
                                </span>
                            </div>

                        </div>

                    </div>

                    {{-- Actions --}}
                    <div class="d-flex justify-content-center gap-2 mt-4">

                        <a href="{{ route('admin.instructors.edit', $instructor) }}"
                           class="btn btn-primary">
                            <i class="bx bx-edit-alt me-1"></i>
                            Edit
                        </a>

                        <form method="POST"
                              action="{{ route('admin.instructors.destroy', $instructor) }}"
                              onsubmit="return confirm('Remove this instructor profile? The staff member will not be deleted.');">

                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="btn btn-danger">
                                <i class="bx bx-trash me-1"></i>
                                Delete
                            </button>

                        </form>

                    </div>

                </div>

            </div>

            {{-- Instructor Details --}}
            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-chalkboard me-1"></i>
                        Teaching Profile
                    </h5>
                </div>

                <div class="card-body">

                    <div class="mb-4">

                        <label class="text-muted small d-block mb-1">
                            Specialization
                        </label>

                        <h6 class="mb-0">
                            {{ $instructor->specialization ?: 'Not specified' }}
                        </h6>

                    </div>

                    <div>

                        <label class="text-muted small d-block mb-1">
                            Qualification
                        </label>

                        <div class="text-body">
                            {!! nl2br(e($instructor->qualification ?: 'Not specified')) !!}
                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- Right Column --}}
        <div class="col-xl-8">

            {{-- Personal Information --}}
            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">
                        Personal Information
                    </h5>
                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-4 mb-4">
                            <label class="text-muted small d-block">
                                First Name
                            </label>

                            <span class="fw-medium">
                                {{ $instructor->staff?->first_name ?? '—' }}
                            </span>
                        </div>

                        <div class="col-md-4 mb-4">
                            <label class="text-muted small d-block">
                                Middle Name
                            </label>

                            <span class="fw-medium">
                                {{ $instructor->staff?->middle_name ?? '—' }}
                            </span>
                        </div>

                        <div class="col-md-4 mb-4">
                            <label class="text-muted small d-block">
                                Last Name
                            </label>

                            <span class="fw-medium">
                                {{ $instructor->staff?->last_name ?? '—' }}
                            </span>
                        </div>

                        <div class="col-md-4 mb-4">
                            <label class="text-muted small d-block">
                                Date of Birth
                            </label>

                            <span class="fw-medium">
                                {{ $instructor->staff?->date_of_birth?->format('d M Y') ?? '—' }}
                            </span>
                        </div>

                        <div class="col-md-4 mb-4">
                            <label class="text-muted small d-block">
                                Gender
                            </label>

                            <span class="fw-medium">
                                {{ ucfirst($instructor->staff?->gender ?? '—') }}
                            </span>
                        </div>

                        <div class="col-md-4 mb-4">
                            <label class="text-muted small d-block">
                                Nationality
                            </label>

                            <span class="fw-medium">
                                {{ $instructor->staff?->nationality ?? '—' }}
                            </span>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small d-block">
                                Phone
                            </label>

                            <span class="fw-medium">
                                {{ $instructor->staff?->phone ?? '—' }}
                            </span>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small d-block">
                                Department
                            </label>

                            <span class="fw-medium">
                                {{ $instructor->staff?->department?->name ?? '—' }}
                            </span>
                        </div>

                    </div>

                </div>
            </div>

            {{-- Employment Information --}}
            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">
                        Employment Information
                    </h5>
                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-4 mb-4">
                            <label class="text-muted small d-block">
                                Staff Number
                            </label>

                            <span class="fw-medium">
                                {{ $instructor->staff?->staff_number ?? '—' }}
                            </span>
                        </div>

                        <div class="col-md-4 mb-4">
                            <label class="text-muted small d-block">
                                Staff Type
                            </label>

                            <span class="fw-medium">
                                {{ $instructor->staff?->staff_type ?? '—' }}
                            </span>
                        </div>

                        <div class="col-md-4 mb-4">
                            <label class="text-muted small d-block">
                                Employment Date
                            </label>

                            <span class="fw-medium">
                                {{ $instructor->staff?->employment_date?->format('d M Y') ?? '—' }}
                            </span>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small d-block">
                                Department
                            </label>

                            <span class="fw-medium">
                                {{ $instructor->staff?->department?->name ?? '—' }}
                            </span>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small d-block">
                                Employment Status
                            </label>

                            @if($instructor->staff?->status === 'active')
                                <span class="badge bg-success-subtle text-success">
                                    Active
                                </span>
                            @elseif($instructor->staff?->status === 'inactive')
                                <span class="badge bg-warning-subtle text-warning">
                                    Inactive
                                </span>
                            @else
                                <span class="badge bg-danger-subtle text-danger">
                                    {{ ucfirst($instructor->staff?->status ?? 'Unknown') }}
                                </span>
                            @endif
                        </div>

                    </div>

                </div>
            </div>

            {{-- System User Account --}}
            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">
                        System User Account
                    </h5>
                </div>

                <div class="card-body">

                    @if($instructor->staff?->user)

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label class="text-muted small d-block">
                                    Name
                                </label>

                                <span class="fw-medium">
                                    {{ $instructor->staff->user->name }}
                                </span>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="text-muted small d-block">
                                    Email
                                </label>

                                <span class="fw-medium">
                                    {{ $instructor->staff->user->email }}
                                </span>
                            </div>

                        </div>

                    @else

                        <div class="text-center py-3">

                            <i class="bx bx-user-x fs-2 text-muted"></i>

                            <p class="text-muted mb-0 mt-2">
                                No system user account is linked to this staff member.
                            </p>

                        </div>

                    @endif

                </div>

            </div>

            {{-- Record Information --}}
            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">
                        Record Information
                    </h5>
                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="text-muted small d-block">
                                Instructor Profile Created
                            </label>

                            <span class="fw-medium">
                                {{ $instructor->created_at?->format('d M Y, h:i A') ?? '—' }}
                            </span>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="text-muted small d-block">
                                Last Updated
                            </label>

                            <span class="fw-medium">
                                {{ $instructor->updated_at?->format('d M Y, h:i A') ?? '—' }}
                            </span>
                        </div>

                    </div>

                </div>
            </div>

        </div>

    </div>

</div>

@endsection