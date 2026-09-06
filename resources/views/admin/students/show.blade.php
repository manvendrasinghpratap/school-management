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
                    <li>{{ $error }}</li>
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

        <div class="col-lg-4">

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


        {{-- ===================================================== --}}
        {{-- Student Details --}}
        {{-- ===================================================== --}}
        <div class="col-lg-8">

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
                                {{ $student->admission_date?->format('Y-m-d') }}
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

        </div>

    </div>

</div>

@endsection