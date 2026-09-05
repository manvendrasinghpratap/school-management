@extends('backend.layout.default')

@section('content')
<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Guardian Details</h4>
            <p class="text-muted mb-0">
                View guardian information and linked students.
            </p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.guardians.edit', $guardian) }}"
               class="btn btn-primary">
                Edit Guardian
            </a>

            <a href="{{ route('admin.guardians.index') }}"
               class="btn btn-secondary">
                Back to Guardians
            </a>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">

        {{-- Guardian Information --}}
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Guardian Information</h5>
                </div>

                <div class="card-body">
                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <strong>Guardian Number</strong>
                            <div>
                                {{ $guardian->guardian_number }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Full Name</strong>
                            <div>
                                {{ $guardian->full_name }}
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <strong>Title</strong>
                            <div>
                                {{ $guardian->title ?: '—' }}
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <strong>First Name</strong>
                            <div>
                                {{ $guardian->first_name }}
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <strong>Last Name</strong>
                            <div>
                                {{ $guardian->last_name }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Phone</strong>
                            <div>
                                {{ $guardian->phone ?: '—' }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>WhatsApp</strong>
                            <div>
                                {{ $guardian->whatsapp ?: '—' }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Email</strong>
                            <div>
                                {{ $guardian->email ?: '—' }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Occupation</strong>
                            <div>
                                {{ $guardian->occupation ?: '—' }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>State</strong>
                            <div>
                                {{ $guardian->state ?: '—' }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Local Government</strong>
                            <div>
                                {{ $guardian->local_government ?: '—' }}
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <strong>Address</strong>
                            <div>
                                {{ $guardian->address ?: '—' }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- Summary --}}
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Summary</h5>
                </div>

                <div class="card-body">

                    <div class="mb-3">
                        <small class="text-muted">Guardian Number</small>
                        <div class="fw-bold">
                            {{ $guardian->guardian_number }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">Linked Students</small>
                        <div class="fw-bold">
                            {{ $guardian->students->count() }}
                        </div>
                    </div>

                    <div>
                        <small class="text-muted">Registered</small>
                        <div>
                            {{ $guardian->created_at?->format('Y-m-d H:i') ?? '—' }}
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

    {{-- Linked Students --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Linked Students</h5>

            <span class="badge bg-secondary">
                {{ $guardian->students->count() }}
            </span>
        </div>

        <div class="card-body p-0">

            @if($guardian->students->count())

                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Student Number</th>
                                <th>Student Name</th>
                                <th>Relationship</th>
                                <th>Primary</th>
                                <th>Emergency</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($guardian->students as $student)
                                <tr>
                                    <td>
                                        {{ $student->student_number }}
                                    </td>

                                    <td>
                                        {{ $student->full_name }}
                                    </td>

                                    <td>
                                        {{ $student->pivot->relationship ?: '—' }}
                                    </td>

                                    <td>
                                        @if($student->pivot->is_primary)
                                            <span class="badge bg-success">
                                                Yes
                                            </span>
                                        @else
                                            <span class="text-muted">
                                                No
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        @if($student->pivot->is_emergency_contact)
                                            <span class="badge bg-warning text-dark">
                                                Yes
                                            </span>
                                        @else
                                            <span class="text-muted">
                                                No
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            @else

                <div class="p-4 text-center text-muted">
                    No students are currently linked to this guardian.
                </div>

            @endif

        </div>
    </div>

</div>
@endsection