@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- ===================================================== --}}
    {{-- Page Header --}}
    {{-- ===================================================== --}}
    <div class="row">
        <div class="col-12">

            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0 font-size-18">
                    Student Profile
                </h4>

                <div class="page-title-right d-flex gap-2 flex-wrap">

                    {{-- Back --}}
                    <a
                        href="{{ route('admin.students.index') }}"
                        class="btn btn-secondary"
                    >
                        <i class="bx bx-arrow-back me-1"></i>
                        Back to Students
                    </a>

                    {{-- Student Documents --}}
                    @can('students.documents.view')

                        <a
                            href="{{ route('admin.students.documents.index', $student) }}"
                            class="btn btn-info"
                        >
                            <i class="bx bx-file me-1"></i>
                            Documents
                        </a>

                    @endcan

                    {{-- Edit Student --}}
                    @can('students.update')

                        <a
                            href="{{ route('admin.students.edit', $student) }}"
                            class="btn btn-primary"
                        >
                            <i class="bx bx-edit me-1"></i>
                            Edit Student
                        </a>

                    @endcan

                </div>

            </div>

        </div>
    </div>


    {{-- ===================================================== --}}
    {{-- Alerts --}}
    {{-- ===================================================== --}}

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


    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show">

            <strong>Please correct the following errors:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- ===================================================== --}}
    {{-- Student Header --}}
    {{-- ===================================================== --}}

    <div class="row">

        {{-- ================================================= --}}
        {{-- Left Column --}}
        {{-- ================================================= --}}

        <div class="col-lg-4">

            {{-- Student Summary --}}
            <div class="card">

                <div class="card-body text-center">

                    {{-- Student Photo --}}
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


            {{-- ================================================= --}}
            {{-- Quick Statistics --}}
            {{-- ================================================= --}}

            <div class="card">

                <div class="card-body">

                    <h5 class="card-title mb-4">
                        Student Records
                    </h5>


                    {{-- Guardians --}}
                    @can('guardians.view')

                        <div class="d-flex justify-content-between mb-3">

                            <span class="text-muted">
                                Guardians
                            </span>

                            <strong>
                                {{ $student->guardians->count() }}
                            </strong>

                        </div>

                    @endcan


                    {{-- Enrollments --}}
                    @can('enrollments.view')

                        <div class="d-flex justify-content-between mb-3">

                            <span class="text-muted">
                                Enrollments
                            </span>

                            <strong>
                                {{ $student->enrollments->count() }}
                            </strong>

                        </div>

                    @endcan


                    {{-- Promotions --}}
                    @can('promotions.view')

                        <div class="d-flex justify-content-between mb-3">

                            <span class="text-muted">
                                Promotions
                            </span>

                            <strong>
                                {{ $student->promotions->count() }}
                            </strong>

                        </div>

                    @endcan


                    {{-- Documents --}}
                    @can('students.documents.view')

                        <div class="d-flex justify-content-between align-items-center">

                            <span class="text-muted">
                                Documents
                            </span>

                            <a
                                href="{{ route('admin.students.documents.index', $student) }}"
                                class="fw-semibold text-primary"
                            >

                                {{ $student->documents->count() }}

                                <i class="bx bx-right-arrow-alt"></i>

                            </a>

                        </div>

                    @endcan

                </div>

            </div>

        </div>


        {{-- ================================================= --}}
        {{-- Right Column --}}
        {{-- ================================================= --}}

        <div class="col-lg-8">

            {{-- ================================================= --}}
            {{-- Personal Information --}}
            {{-- ================================================= --}}

            <div class="card">

                <div class="card-body">

                    <h5 class="card-title mb-4">
                        Personal Information
                    </h5>


                    <div class="row">

                        {{-- Student Number --}}
                        <div class="col-md-6 mb-4">

                            <label class="text-muted">
                                Student Number
                            </label>

                            <div class="fw-semibold">
                                {{ $student->student_number }}
                            </div>

                        </div>


                        {{-- Admission Number --}}
                        <div class="col-md-6 mb-4">

                            <label class="text-muted">
                                Admission Number
                            </label>

                            <div class="fw-semibold">
                                {{ $student->admission_number ?: '—' }}
                            </div>

                        </div>


                        {{-- First Name --}}
                        <div class="col-md-4 mb-4">

                            <label class="text-muted">
                                First Name
                            </label>

                            <div class="fw-semibold">
                                {{ $student->first_name }}
                            </div>

                        </div>


                        {{-- Middle Name --}}
                        <div class="col-md-4 mb-4">

                            <label class="text-muted">
                                Middle Name
                            </label>

                            <div class="fw-semibold">
                                {{ $student->middle_name ?: '—' }}
                            </div>

                        </div>


                        {{-- Last Name --}}
                        <div class="col-md-4 mb-4">

                            <label class="text-muted">
                                Last Name
                            </label>

                            <div class="fw-semibold">
                                {{ $student->last_name }}
                            </div>

                        </div>


                        {{-- Date of Birth --}}
                        <div class="col-md-4 mb-4">

                            <label class="text-muted">
                                Date of Birth
                            </label>

                            <div class="fw-semibold">
                                {{ $student->date_of_birth?->format('Y-m-d') ?: '—' }}
                            </div>

                        </div>


                        {{-- Gender --}}
                        <div class="col-md-4 mb-4">

                            <label class="text-muted">
                                Gender
                            </label>

                            <div class="fw-semibold">
                                {{ $student->gender ? ucfirst($student->gender) : '—' }}
                            </div>

                        </div>


                        {{-- Nationality --}}
                        <div class="col-md-4 mb-4">

                            <label class="text-muted">
                                Nationality
                            </label>

                            <div class="fw-semibold">
                                {{ $student->nationality ?: '—' }}
                            </div>

                        </div>


                        {{-- Phone --}}
                        <div class="col-md-6 mb-4">

                            <label class="text-muted">
                                Phone
                            </label>

                            <div class="fw-semibold">
                                {{ $student->phone ?: '—' }}
                            </div>

                        </div>


                        {{-- Admission Date --}}
                        <div class="col-md-6 mb-4">

                            <label class="text-muted">
                                Admission Date
                            </label>

                            <div class="fw-semibold">
                                {{ $student->admission_date?->format('Y-m-d') ?: '—' }}
                            </div>

                        </div>


                        {{-- Address --}}
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


            {{-- ================================================= --}}
            {{-- Guardians --}}
            {{-- ================================================= --}}

            @can('guardians.view')

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

            @endcan


            {{-- ================================================= --}}
            {{-- Enrollment History --}}
            {{-- ================================================= --}}

            @can('enrollments.view')

                <div class="card">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center mb-4">

                            <div>

                                <h5 class="card-title mb-1">
                                    Enrollment History
                                </h5>

                                <p class="text-muted mb-0">
                                    Academic enrollment records for this student.
                                </p>

                            </div>


                            <div class="d-flex align-items-center gap-2">

                                <span class="badge bg-info">
                                    {{ $student->enrollments->count() }}
                                </span>


                                @can('enrollments.create')

                                    <a
                                        href="{{ route('admin.student-enrollments.create', ['student_id' => $student->id]) }}"
                                        class="btn btn-sm btn-primary"
                                    >

                                        <i class="bx bx-plus me-1"></i>
                                        Add Enrollment

                                    </a>

                                @endcan

                            </div>

                        </div>


                        @if($student->enrollments->count())

                            <div class="table-responsive">

                                <table class="table table-bordered table-hover align-middle mb-0">

                                    <thead class="table-light">

                                        <tr>

                                            <th>
                                                Enrollment No.
                                            </th>

                                            <th>
                                                Academic Year
                                            </th>

                                            <th>
                                                Term
                                            </th>

                                            <th>
                                                Class
                                            </th>

                                            <th>
                                                Section
                                            </th>

                                            <th>
                                                Enrollment Date
                                            </th>

                                            <th>
                                                Status
                                            </th>

                                            <th class="text-center">
                                                Action
                                            </th>

                                        </tr>

                                    </thead>


                                    <tbody>

                                        @foreach($student->enrollments as $enrollment)

                                            <tr>

                                                <td>

                                                    <strong>
                                                        {{ $enrollment->enrollment_number }}
                                                    </strong>

                                                </td>


                                                <td>
                                                    {{ $enrollment->academicYear?->name ?? '—' }}
                                                </td>


                                                <td>
                                                    {{ $enrollment->term?->name ?? '—' }}
                                                </td>


                                                <td>
                                                    {{ $enrollment->class?->name ?? '—' }}
                                                </td>


                                                <td>
                                                    {{ $enrollment->section?->name ?? '—' }}
                                                </td>


                                                <td>
                                                    {{ $enrollment->enrollment_date?->format('Y-m-d') ?? '—' }}
                                                </td>


                                                <td>

                                                    @php

                                                        $enrollmentStatusClass = match($enrollment->status) {

                                                            'active' => 'success',

                                                            'completed' => 'primary',

                                                            'transferred' => 'warning',

                                                            'withdrawn' => 'danger',

                                                            default => 'secondary',

                                                        };

                                                    @endphp


                                                    <span class="badge bg-{{ $enrollmentStatusClass }}">

                                                        {{ ucfirst($enrollment->status) }}

                                                    </span>

                                                </td>


                                                <td class="text-center">

                                                    @can('enrollments.view')

                                                        <a
                                                            href="{{ route('admin.student-enrollments.show', $enrollment) }}"
                                                            class="btn btn-sm btn-info"
                                                            title="View Enrollment"
                                                        >

                                                            <i class="bx bx-show"></i>

                                                        </a>

                                                    @endcan


                                                    @can('enrollments.update')

                                                        <a
                                                            href="{{ route('admin.student-enrollments.edit', $enrollment) }}"
                                                            class="btn btn-sm btn-primary"
                                                            title="Edit Enrollment"
                                                        >

                                                            <i class="bx bx-edit"></i>

                                                        </a>

                                                    @endcan

                                                </td>

                                            </tr>

                                        @endforeach

                                    </tbody>

                                </table>

                            </div>

                        @else

                            <div class="text-center py-4">

                                <i
                                    class="bx bx-school text-muted"
                                    style="font-size:48px;"
                                ></i>


                                <h6 class="mt-2 mb-1">
                                    No Enrollment Records
                                </h6>


                                <p class="text-muted mb-3">
                                    This student has not been enrolled in an academic year yet.
                                </p>


                                @can('enrollments.create')

                                    <a
                                        href="{{ route('admin.student-enrollments.create', ['student_id' => $student->id]) }}"
                                        class="btn btn-primary"
                                    >

                                        <i class="bx bx-plus me-1"></i>
                                        Create Enrollment

                                    </a>

                                @endcan

                            </div>

                        @endif

                    </div>

                </div>

            @endcan

        </div>

    </div>

</div>

@endsection