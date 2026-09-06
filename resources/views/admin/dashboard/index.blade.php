@extends('backend.layout.default')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row mb-3">
        <div class="col-12">
            <div>
                <h4 class="mb-1">Dashboard</h4>
                <p class="text-muted mb-0">
                    Welcome to your School Management System.
                </p>
            </div>
        </div>
    </div>

    {{-- Current Academic Period --}}
    <div class="row mb-4">

        <div class="col-xl-6 col-lg-6">
            <div class="card">
                <div class="card-body">

                    <div class="d-flex align-items-center">
                        <div class="avatar-sm">
                            <span class="avatar-title rounded bg-primary-subtle text-primary font-size-20">
                                <i class="bx bx-calendar"></i>
                            </span>
                        </div>

                        <div class="ms-3">
                            <p class="text-muted mb-1">
                                Current Academic Year
                            </p>

                            <h5 class="mb-0">
                                {{ $currentAcademicYear?->name ?? 'Not Set' }}
                            </h5>

                            @if($currentAcademicYear)
                                <small class="text-muted">
                                    {{ $currentAcademicYear->start_date?->format('d M Y') }}
                                    -
                                    {{ $currentAcademicYear->end_date?->format('d M Y') }}
                                </small>
                            @endif
                        </div>

                    </div>

                </div>
            </div>
        </div>

        <div class="col-xl-6 col-lg-6">
            <div class="card">
                <div class="card-body">

                    <div class="d-flex align-items-center">
                        <div class="avatar-sm">
                            <span class="avatar-title rounded bg-success-subtle text-success font-size-20">
                                <i class="bx bx-book-open"></i>
                            </span>
                        </div>

                        <div class="ms-3">
                            <p class="text-muted mb-1">
                                Current Term
                            </p>

                            <h5 class="mb-0">
                                {{ $currentTerm?->name ?? 'Not Set' }}
                            </h5>

                            @if($currentTerm)
                                <small class="text-muted">
                                    {{ $currentTerm->start_date?->format('d M Y') }}
                                    -
                                    {{ $currentTerm->end_date?->format('d M Y') }}
                                </small>
                            @endif
                        </div>

                    </div>

                </div>
            </div>
        </div>

    </div>

    {{-- Main Statistics --}}
    <div class="row">

        {{-- Students --}}
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">

                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-2">
                                Total Students
                            </p>

                            <h4 class="mb-0">
                                {{ number_format($statistics['students']) }}
                            </h4>
                        </div>

                        <div class="avatar-sm">
                            <span class="avatar-title rounded bg-primary-subtle text-primary font-size-20">
                                <i class="bx bx-user"></i>
                            </span>
                        </div>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('admin.students.index') }}"
                           class="text-primary">
                            View Students
                            <i class="bx bx-right-arrow-alt"></i>
                        </a>
                    </div>

                </div>
            </div>
        </div>

        {{-- Active Students --}}
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">

                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-2">
                                Active Students
                            </p>

                            <h4 class="mb-0">
                                {{ number_format($statistics['active_students']) }}
                            </h4>
                        </div>

                        <div class="avatar-sm">
                            <span class="avatar-title rounded bg-success-subtle text-success font-size-20">
                                <i class="bx bx-user-check"></i>
                            </span>
                        </div>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('admin.students.index', ['status' => 'active']) }}"
                           class="text-success">
                            Active Students
                            <i class="bx bx-right-arrow-alt"></i>
                        </a>
                    </div>

                </div>
            </div>
        </div>

        {{-- Staff --}}
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">

                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-2">
                                Total Staff
                            </p>

                            <h4 class="mb-0">
                                {{ number_format($statistics['staff']) }}
                            </h4>
                        </div>

                        <div class="avatar-sm">
                            <span class="avatar-title rounded bg-info-subtle text-info font-size-20">
                                <i class="bx bx-group"></i>
                            </span>
                        </div>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('admin.staff.index') }}"
                           class="text-info">
                            View Staff
                            <i class="bx bx-right-arrow-alt"></i>
                        </a>
                    </div>

                </div>
            </div>
        </div>

        {{-- Instructors --}}
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">

                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-2">
                                Instructors
                            </p>

                            <h4 class="mb-0">
                                {{ number_format($statistics['instructors']) }}
                            </h4>
                        </div>

                        <div class="avatar-sm">
                            <span class="avatar-title rounded bg-warning-subtle text-warning font-size-20">
                                <i class="bx bx-chalkboard"></i>
                            </span>
                        </div>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('admin.instructors.index') }}"
                           class="text-warning">
                            View Instructors
                            <i class="bx bx-right-arrow-alt"></i>
                        </a>
                    </div>

                </div>
            </div>
        </div>

    </div>

    {{-- Academic Statistics --}}
    <div class="row">

        {{-- Classes --}}
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">

                    <p class="text-muted mb-2">
                        Classes
                    </p>

                    <h4 class="mb-0">
                        {{ number_format($statistics['classes']) }}
                    </h4>

                    <div class="mt-3">
                        <a href="{{ route('admin.classes.index') }}"
                           class="text-primary">
                            Manage Classes
                            <i class="bx bx-right-arrow-alt"></i>
                        </a>
                    </div>

                </div>
            </div>
        </div>

        {{-- Sections --}}
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">

                    <p class="text-muted mb-2">
                        Sections
                    </p>

                    <h4 class="mb-0">
                        {{ number_format($statistics['sections']) }}
                    </h4>

                    <div class="mt-3">
                        <a href="{{ route('admin.sections.index') }}"
                           class="text-primary">
                            Manage Sections
                            <i class="bx bx-right-arrow-alt"></i>
                        </a>
                    </div>

                </div>
            </div>
        </div>

        {{-- Departments --}}
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">

                    <p class="text-muted mb-2">
                        Departments
                    </p>

                    <h4 class="mb-0">
                        {{ number_format($statistics['departments']) }}
                    </h4>

                    <div class="mt-3">
                        <a href="{{ route('admin.departments.index') }}"
                           class="text-primary">
                            Manage Departments
                            <i class="bx bx-right-arrow-alt"></i>
                        </a>
                    </div>

                </div>
            </div>
        </div>

        {{-- Courses --}}
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">

                    <p class="text-muted mb-2">
                        Subjects / Courses
                    </p>

                    <h4 class="mb-0">
                        {{ number_format($statistics['courses']) }}
                    </h4>

                    <div class="mt-3">
                        <a href="{{ route('admin.courses.index') }}"
                           class="text-primary">
                            Manage Courses
                            <i class="bx bx-right-arrow-alt"></i>
                        </a>
                    </div>

                </div>
            </div>
        </div>

    </div>

    {{-- Quick Actions --}}
    <div class="row">

        <div class="col-12">

            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-bolt-circle me-2"></i>
                        Quick Actions
                    </h5>
                </div>

                <div class="card-body">

                    <div class="d-flex flex-wrap gap-2">

                        <a href="{{ route('admin.students.create') }}"
                           class="btn btn-primary">
                            <i class="bx bx-user-plus me-1"></i>
                            Add Student
                        </a>

                        <a href="{{ route('admin.staff.create') }}"
                           class="btn btn-info">
                            <i class="bx bx-user-plus me-1"></i>
                            Add Staff
                        </a>

                        <a href="{{ route('admin.instructors.create') }}"
                           class="btn btn-warning">
                            <i class="bx bx-chalkboard me-1"></i>
                            Add Instructor
                        </a>

                        <a href="{{ route('admin.classes.create') }}"
                           class="btn btn-success">
                            <i class="bx bx-building me-1"></i>
                            Add Class
                        </a>

                        <a href="{{ route('admin.courses.create') }}"
                           class="btn btn-secondary">
                            <i class="bx bx-book-add me-1"></i>
                            Add Course
                        </a>

                        <a href="{{ route('admin.academic-years.create') }}"
                           class="btn btn-dark">
                            <i class="bx bx-calendar-plus me-1"></i>
                            Add Academic Year
                        </a>

                        <a href="{{ route('admin.terms.create') }}"
                           class="btn btn-outline-primary">
                            <i class="bx bx-calendar-event me-1"></i>
                            Add Term
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="row">

        {{-- Recent Students --}}
        <div class="col-xl-6">

            <div class="card">

                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">

                        <h5 class="card-title mb-0">
                            <i class="bx bx-user me-2"></i>
                            Recent Students
                        </h5>

                        <a href="{{ route('admin.students.index') }}"
                           class="btn btn-sm btn-outline-primary">
                            View All
                        </a>

                    </div>
                </div>

                <div class="card-body">

                    @forelse($recentStudents as $student)

                        <div class="d-flex align-items-center py-2 border-bottom">

                            <div class="avatar-sm">
                                <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                    {{ strtoupper(substr($student->first_name, 0, 1)) }}
                                </span>
                            </div>

                            <div class="ms-3 flex-grow-1">

                                <h6 class="mb-1">
                                    {{ $student->first_name }}
                                    {{ $student->middle_name }}
                                    {{ $student->last_name }}
                                </h6>

                                <small class="text-muted">
                                    {{ $student->student_number }}
                                </small>

                            </div>

                            <span class="badge
                                @if($student->status === 'active')
                                    bg-success-subtle text-success
                                @else
                                    bg-secondary-subtle text-secondary
                                @endif">
                                {{ ucfirst($student->status) }}
                            </span>

                        </div>

                    @empty

                        <div class="text-center py-4 text-muted">
                            <i class="bx bx-user-x font-size-24 d-block mb-2"></i>
                            No students registered yet.
                        </div>

                    @endforelse

                </div>

            </div>

        </div>

        {{-- Recent Staff --}}
        <div class="col-xl-6">

            <div class="card">

                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">

                        <h5 class="card-title mb-0">
                            <i class="bx bx-group me-2"></i>
                            Recent Staff
                        </h5>

                        <a href="{{ route('admin.staff.index') }}"
                           class="btn btn-sm btn-outline-primary">
                            View All
                        </a>

                    </div>
                </div>

                <div class="card-body">

                    @forelse($recentStaff as $staff)

                        <div class="d-flex align-items-center py-2 border-bottom">

                            <div class="avatar-sm">
                                <span class="avatar-title rounded-circle bg-info-subtle text-info">
                                    {{ strtoupper(substr($staff->first_name, 0, 1)) }}
                                </span>
                            </div>

                            <div class="ms-3 flex-grow-1">

                                <h6 class="mb-1">
                                    {{ $staff->first_name }}
                                    {{ $staff->middle_name }}
                                    {{ $staff->last_name }}
                                </h6>

                                <small class="text-muted">
                                    {{ $staff->staff_number }}
                                </small>

                            </div>

                            <span class="badge
                                @if($staff->status === 'active')
                                    bg-success-subtle text-success
                                @else
                                    bg-secondary-subtle text-secondary
                                @endif">
                                {{ ucfirst($staff->status) }}
                            </span>

                        </div>

                    @empty

                        <div class="text-center py-4 text-muted">
                            <i class="bx bx-group font-size-24 d-block mb-2"></i>
                            No staff registered yet.
                        </div>

                    @endforelse

                </div>

            </div>

        </div>

    </div>

</div>
@endsection