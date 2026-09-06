@extends('backend.layout.default')

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-12">

            <div class="page-title-box d-flex align-items-center justify-content-between">

                <h4 class="mb-0">
                    Enrollment Details
                </h4>

                <div class="d-flex gap-2">

                    <a href="{{ route('admin.student-enrollments.index') }}"
                       class="btn btn-light">
                        <i class="mdi mdi-arrow-left me-1"></i>
                        Back
                    </a>

                    @can('enrollments.update')
                        <a href="{{ route('admin.student-enrollments.edit', $enrollment) }}"
                           class="btn btn-primary">
                            <i class="mdi mdi-pencil me-1"></i>
                            Edit
                        </a>
                    @endcan

                </div>

            </div>

        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">

        {{-- Student --}}
        <div class="col-lg-4">

            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">
                        Student
                    </h5>
                </div>

                <div class="card-body text-center">

                    <div class="avatar-lg mx-auto mb-3">
                        @if($enrollment->student?->photo)
                            <img src="{{ asset('storage/' . $enrollment->student->photo) }}"
                                 class="rounded-circle img-thumbnail"
                                 style="width:100px;height:100px;object-fit:cover;">
                        @else
                            <div class="avatar-title rounded-circle bg-primary bg-soft text-primary"
                                 style="width:100px;height:100px;font-size:32px;">
                                {{ strtoupper(substr($enrollment->student?->first_name ?? 'S', 0, 1)) }}
                            </div>
                        @endif
                    </div>

                    <h5 class="mb-1">
                        {{ $enrollment->student?->first_name }}
                        {{ $enrollment->student?->middle_name }}
                        {{ $enrollment->student?->last_name }}
                    </h5>

                    <p class="text-muted mb-0">
                        {{ $enrollment->student?->student_number ?? '—' }}
                    </p>

                </div>

            </div>

        </div>

        {{-- Enrollment --}}
        <div class="col-lg-8">

            <div class="card">

                <div class="card-header d-flex justify-content-between">

                    <h5 class="card-title mb-0">
                        Enrollment Information
                    </h5>

                    @php
                        $statusClass = match($enrollment->status) {
                            'active' => 'bg-success',
                            'completed' => 'bg-primary',
                            'transferred' => 'bg-warning text-dark',
                            'withdrawn' => 'bg-danger',
                            default => 'bg-secondary',
                        };
                    @endphp

                    <span class="badge {{ $statusClass }}">
                        {{ ucfirst($enrollment->status) }}
                    </span>

                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6 mb-4">
                            <small class="text-muted d-block">
                                Enrollment Number
                            </small>

                            <strong>
                                {{ $enrollment->enrollment_number }}
                            </strong>
                        </div>

                        <div class="col-md-6 mb-4">
                            <small class="text-muted d-block">
                                Enrollment Date
                            </small>

                            <strong>
                                {{ $enrollment->enrollment_date?->format('Y-m-d') ?? '—' }}
                            </strong>
                        </div>

                        <div class="col-md-6 mb-4">
                            <small class="text-muted d-block">
                                Academic Year
                            </small>

                            <strong>
                                {{ $enrollment->academicYear?->name ?? '—' }}
                            </strong>
                        </div>

                        <div class="col-md-6 mb-4">
                            <small class="text-muted d-block">
                                Term
                            </small>

                            <strong>
                                {{ $enrollment->term?->name ?? '—' }}
                            </strong>
                        </div>

                        <div class="col-md-6 mb-4">
                            <small class="text-muted d-block">
                                Class
                            </small>

                            <strong>
                                {{ $enrollment->class?->name ?? '—' }}
                            </strong>
                        </div>

                        <div class="col-md-6 mb-4">
                            <small class="text-muted d-block">
                                Section
                            </small>

                            <strong>
                                {{ $enrollment->section?->name ?? '—' }}
                            </strong>
                        </div>

                    </div>

                    @if($enrollment->notes)

                        <hr>

                        <div>
                            <small class="text-muted d-block mb-2">
                                Notes
                            </small>

                            <p class="mb-0">
                                {{ $enrollment->notes }}
                            </p>
                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

    {{-- Audit --}}
    <div class="card">

        <div class="card-header">
            <h5 class="card-title mb-0">
                Record Information
            </h5>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4">
                    <small class="text-muted d-block">
                        Created By
                    </small>

                    <strong>
                        {{ $enrollment->createdBy?->name ?? 'System' }}
                    </strong>
                </div>

                <div class="col-md-4">
                    <small class="text-muted d-block">
                        Created At
                    </small>

                    <strong>
                        {{ $enrollment->created_at?->format('Y-m-d H:i') ?? '—' }}
                    </strong>
                </div>

                <div class="col-md-4">
                    <small class="text-muted d-block">
                        Last Updated
                    </small>

                    <strong>
                        {{ $enrollment->updated_at?->format('Y-m-d H:i') ?? '—' }}
                    </strong>
                </div>

            </div>

        </div>

    </div>

    @can('enrollments.delete')

        <div class="card border-danger">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <h5 class="text-danger mb-1">
                            Delete Enrollment
                        </h5>

                        <p class="text-muted mb-0">
                            Deleting this record will soft-delete the enrollment.
                        </p>
                    </div>

                    <form method="POST"
                          action="{{ route('admin.student-enrollments.destroy', $enrollment) }}"
                          onsubmit="return confirm('Are you sure you want to delete this enrollment?');">

                        @csrf
                        @method('DELETE')

                        <button type="submit"
                                class="btn btn-danger">
                            <i class="mdi mdi-delete me-1"></i>
                            Delete Enrollment
                        </button>

                    </form>

                </div>

            </div>

        </div>

    @endcan

</div>
@endsection