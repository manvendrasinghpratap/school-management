@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row">
        <div class="col-12">

            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0 font-size-18">
                    Student Profile
                </h4>

                <div class="page-title-right">
                    <a
                        href="{{ route('admin.students.index') }}"
                        class="btn btn-secondary"
                    >
                        <i class="bx bx-arrow-back"></i>
                        Back to Students
                    </a>

                    <a
                        href="{{ route('admin.students.edit', $student) }}"
                        class="btn btn-primary"
                    >
                        <i class="bx bx-edit"></i>
                        Edit Student
                    </a>
                </div>

            </div>

        </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>
        </div>
    @endif

    {{-- Student Header --}}
    <div class="row">

        <div class="col-lg-4">

            <div class="card">

                <div class="card-body text-center">

                    @if($student->photo)

                        <img
                            src="{{ asset('storage/' . $student->photo) }}"
                            alt="{{ $student->full_name }}"
                            class="rounded-circle avatar-xl mb-3"
                            style="width:120px;height:120px;object-fit:cover;"
                        >

                    @else

                        <div
                            class="avatar-xl mx-auto mb-3 bg-light rounded-circle d-flex align-items-center justify-content-center"
                            style="width:120px;height:120px;"
                        >
                            <i
                                class="bx bx-user text-muted"
                                style="font-size:60px;"
                            ></i>
                        </div>

                    @endif

                    <h5 class="mb-1">
                        {{ $student->full_name }}
                    </h5>

                    <p class="text-muted mb-2">
                        {{ $student->student_number }}
                    </p>

                    @php
                        $statusClass = match($student->status) {
                            'active' => 'success',
                            'inactive' => 'secondary',
                            'graduated' => 'primary',
                            'transferred' => 'warning',
                            'withdrawn' => 'danger',
                            default => 'secondary',
                        };
                    @endphp

                    <span class="badge bg-{{ $statusClass }}">
                        {{ ucfirst($student->status) }}
                    </span>

                </div>

            </div>

            {{-- Quick Statistics --}}
            <div class="card">

                <div class="card-body">

                    <h5 class="card-title mb-4">
                        Student Records
                    </h5>

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">
                            Guardians
                        </span>

                        <strong>
                            {{ $student->guardians->count() }}
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">
                            Enrollments
                        </span>

                        <strong>
                            {{ $student->enrollments->count() }}
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">
                            Promotions
                        </span>

                        <strong>
                            {{ $student->promotions->count() }}
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between">
                        <span class="text-muted">
                            Documents
                        </span>

                        <strong>
                            {{ $student->documents->count() }}
                        </strong>
                    </div>

                </div>

            </div>

        </div>

        {{-- Student Details --}}
        <div class="col-lg-8">

            <div class="card">

                <div class="card-body">

                    <h5 class="card-title mb-4">
                        Personal Information
                    </h5>

                    <div class="row">

                        <div class="col-md-6 mb-4">
                            <label class="text-muted">
                                Student Number
                            </label>

                            <div class="fw-semibold">
                                {{ $student->student_number }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="text-muted">
                                Admission Number
                            </label>

                            <div class="fw-semibold">
                                {{ $student->admission_number ?: '—' }}
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <label class="text-muted">
                                First Name
                            </label>

                            <div class="fw-semibold">
                                {{ $student->first_name }}
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <label class="text-muted">
                                Middle Name
                            </label>

                            <div class="fw-semibold">
                                {{ $student->middle_name ?: '—' }}
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <label class="text-muted">
                                Last Name
                            </label>

                            <div class="fw-semibold">
                                {{ $student->last_name }}
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <label class="text-muted">
                                Date of Birth
                            </label>

                            <div class="fw-semibold">
                                {{ $student->date_of_birth?->format('Y-m-d') ?: '—' }}
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <label class="text-muted">
                                Gender
                            </label>

                            <div class="fw-semibold">
                                {{ $student->gender ? ucfirst($student->gender) : '—' }}
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <label class="text-muted">
                                Nationality
                            </label>

                            <div class="fw-semibold">
                                {{ $student->nationality ?: '—' }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="text-muted">
                                Phone
                            </label>

                            <div class="fw-semibold">
                                {{ $student->phone ?: '—' }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="text-muted">
                                Admission Date
                            </label>

                            <div class="fw-semibold">
                                {{ $student->admission_date?->format('Y-m-d') }}
                            </div>
                        </div>

                        <div class="col-12">

                            <label class="text-muted">
                                Address
                            </label>

                            <div class="fw-semibold">
                                {{ $student->address ?: '—' }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

            {{-- Guardians --}}
            <div class="card">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-4">

                        <h5 class="card-title mb-0">
                            Guardians
                        </h5>

                        <span class="badge bg-info">
                            {{ $student->guardians->count() }}
                        </span>

                    </div>

                    @forelse($student->guardians as $guardian)

                        <div class="border rounded p-3 mb-3">

                            <div class="row align-items-center">

                                <div class="col-md-5">

                                    <h6 class="mb-1">
                                        {{ $guardian->full_name }}
                                    </h6>

                                    <small class="text-muted">
                                        Guardian No:
                                        {{ $guardian->guardian_number }}
                                    </small>

                                </div>

                                <div class="col-md-3">

                                    <small class="text-muted d-block">
                                        Relationship
                                    </small>

                                    <strong>
                                        {{ $guardian->pivot->relationship }}
                                    </strong>

                                </div>

                                <div class="col-md-4">

                                    @if($guardian->pivot->is_primary)
                                        <span class="badge bg-primary me-1">
                                            Primary
                                        </span>
                                    @endif

                                    @if($guardian->pivot->is_emergency_contact)
                                        <span class="badge bg-danger">
                                            Emergency
                                        </span>
                                    @endif

                                </div>

                            </div>

                            <hr>

                            <div class="row">

                                <div class="col-md-4">
                                    <small class="text-muted d-block">
                                        Phone
                                    </small>

                                    {{ $guardian->phone ?: '—' }}
                                </div>

                                <div class="col-md-4">
                                    <small class="text-muted d-block">
                                        WhatsApp
                                    </small>

                                    {{ $guardian->whatsapp ?: '—' }}
                                </div>

                                <div class="col-md-4">
                                    <small class="text-muted d-block">
                                        Email
                                    </small>

                                    {{ $guardian->email ?: '—' }}
                                </div>

                            </div>

                        </div>

                    @empty

                        <div class="alert alert-info mb-0">
                            No guardians have been assigned to this student.
                        </div>

                    @endforelse

                </div>

            </div>

        </div>

    </div>

</div>

@endsection