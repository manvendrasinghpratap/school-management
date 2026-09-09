@extends('backend.layout.default')

@section('content')
    <div class="container-fluid">

        {{-- Page Header --}}
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Alumni Profile</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                            </li>

                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.alumni.index') }}">
                                    Alumni
                                </a>
                            </li>

                            <li class="breadcrumb-item active">
                                Profile
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bx bx-check-circle me-2"></i>
                {{ session('success') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>
            </div>
        @endif

        {{-- Error Message --}}
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bx bx-error-circle me-2"></i>
                {{ session('error') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>
            </div>
        @endif

        <div class="row">

            {{-- Profile --}}
            <div class="col-lg-4">

                <div class="card">
                    <div class="card-body text-center">

                        <div class="avatar-xl mx-auto mb-4">
                            @if($alumni->student?->photo)
                                <img src="{{ asset('storage/' . $alumni->student->photo) }}"
                                     alt="Student Photo"
                                     class="rounded-circle img-thumbnail"
                                     style="width: 100px; height: 100px; object-fit: cover;">
                            @else
                                <span class="avatar-title rounded-circle bg-primary bg-soft text-primary display-5">
                                    {{ strtoupper(substr($alumni->student?->first_name ?? 'A', 0, 1)) }}
                                </span>
                            @endif
                        </div>

                        <h4 class="mb-1">
                            {{ trim(
                                ($alumni->student?->first_name ?? '') . ' ' .
                                ($alumni->student?->middle_name ?? '') . ' ' .
                                ($alumni->student?->last_name ?? '')
                            ) }}
                        </h4>

                        <p class="text-muted mb-2">
                            {{ $alumni->student?->student_number ?? '—' }}
                        </p>

                        @if($alumni->graduation_year)
                            <span class="badge bg-success">
                                Class of {{ $alumni->graduation_year }}
                            </span>
                        @endif

                    </div>
                </div>

                {{-- Career --}}
                <div class="card">
                    <div class="card-body">

                        <h4 class="card-title mb-4">
                            <i class="bx bx-briefcase me-1"></i>
                            Career Information
                        </h4>

                        <div class="mb-3">
                            <small class="text-muted d-block">
                                Current Occupation
                            </small>

                            <strong>
                                {{ $alumni->current_occupation ?: '—' }}
                            </strong>
                        </div>

                        <div>
                            <small class="text-muted d-block">
                                Current Employer
                            </small>

                            <strong>
                                {{ $alumni->current_employer ?: '—' }}
                            </strong>
                        </div>

                    </div>
                </div>

            </div>

            {{-- Details --}}
            <div class="col-lg-8">

                {{-- Graduation --}}
                <div class="card">
                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="card-title mb-0">
                                <i class="bx bx-graduation me-1"></i>
                                Graduation Information
                            </h4>

                            <span class="badge bg-success">
                                Alumni
                            </span>
                        </div>

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <small class="text-muted d-block">
                                    Graduation Year
                                </small>

                                <strong>
                                    {{ $alumni->graduation_year ?? '—' }}
                                </strong>
                            </div>

                            <div class="col-md-6 mb-3">
                                <small class="text-muted d-block">
                                    Graduation Date
                                </small>

                                <strong>
                                    {{ $alumni->graduation_date?->format('d M Y') ?? '—' }}
                                </strong>
                            </div>

                        </div>

                    </div>
                </div>

                {{-- Contact Information --}}
                <div class="card">
                    <div class="card-body">

                        <h4 class="card-title mb-4">
                            <i class="bx bx-phone me-1"></i>
                            Contact Information
                        </h4>

                        <div class="row">

                            <div class="col-md-6 mb-4">
                                <small class="text-muted d-block">
                                    Phone
                                </small>

                                @if($alumni->phone)
                                    <a href="tel:{{ $alumni->phone }}">
                                        <i class="bx bx-phone me-1"></i>
                                        {{ $alumni->phone }}
                                    </a>
                                @else
                                    <strong>—</strong>
                                @endif
                            </div>

                            <div class="col-md-6 mb-4">
                                <small class="text-muted d-block">
                                    Email
                                </small>

                                @if($alumni->email)
                                    <a href="mailto:{{ $alumni->email }}">
                                        <i class="bx bx-envelope me-1"></i>
                                        {{ $alumni->email }}
                                    </a>
                                @else
                                    <strong>—</strong>
                                @endif
                            </div>

                            <div class="col-12">
                                <small class="text-muted d-block">
                                    Address
                                </small>

                                <strong>
                                    {!! nl2br(e($alumni->address ?: '—')) !!}
                                </strong>
                            </div>

                        </div>

                    </div>
                </div>

                {{-- Student Information --}}
                <div class="card">
                    <div class="card-body">

                        <h4 class="card-title mb-4">
                            <i class="bx bx-user me-1"></i>
                            Student Information
                        </h4>

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <small class="text-muted d-block">
                                    Student Number
                                </small>

                                <strong>
                                    {{ $alumni->student?->student_number ?? '—' }}
                                </strong>
                            </div>

                            <div class="col-md-6 mb-3">
                                <small class="text-muted d-block">
                                    Admission Number
                                </small>

                                <strong>
                                    {{ $alumni->student?->admission_number ?? '—' }}
                                </strong>
                            </div>

                            <div class="col-md-6 mb-3">
                                <small class="text-muted d-block">
                                    Date of Birth
                                </small>

                                <strong>
                                    {{ $alumni->student?->date_of_birth?->format('d M Y') ?? '—' }}
                                </strong>
                            </div>

                            <div class="col-md-6 mb-3">
                                <small class="text-muted d-block">
                                    Gender
                                </small>

                                <strong>
                                    {{ $alumni->student?->gender ? ucfirst($alumni->student->gender) : '—' }}
                                </strong>
                            </div>

                            <div class="col-md-6 mb-3">
                                <small class="text-muted d-block">
                                    Nationality
                                </small>

                                <strong>
                                    {{ $alumni->student?->nationality ?? '—' }}
                                </strong>
                            </div>

                            <div class="col-md-6 mb-3">
                                <small class="text-muted d-block">
                                    Student Phone
                                </small>

                                <strong>
                                    {{ $alumni->student?->phone ?? '—' }}
                                </strong>
                            </div>

                        </div>

                        @if($alumni->student)
                            <div class="mt-2">
                                <a href="{{ route('admin.students.show', $alumni->student) }}"
                                   class="btn btn-light">
                                    <i class="bx bx-user me-1"></i>
                                    View Student Profile
                                </a>
                            </div>
                        @endif

                    </div>
                </div>

            </div>

        </div>

        {{-- Actions --}}
        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <a href="{{ route('admin.alumni.index') }}"
                               class="btn btn-light">
                                <i class="bx bx-arrow-back me-1"></i>
                                Back to Alumni
                            </a>

                            <div class="d-flex gap-2">

                                @can('alumni.manage')
                                    <a href="{{ route('admin.alumni.edit', $alumni) }}"
                                       class="btn btn-primary">
                                        <i class="bx bx-edit-alt me-1"></i>
                                        Edit Alumni
                                    </a>

                                    <form method="POST"
                                          action="{{ route('admin.alumni.destroy', $alumni) }}"
                                          onsubmit="return confirm('Are you sure you want to delete this Alumni record?');">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-danger">
                                            <i class="bx bx-trash me-1"></i>
                                            Delete
                                        </button>
                                    </form>
                                @endcan

                            </div>

                        </div>

                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection