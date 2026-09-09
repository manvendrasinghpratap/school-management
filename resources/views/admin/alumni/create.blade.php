@extends('backend.layout.default')

@section('content')
    <div class="container-fluid">

        {{-- Page Header --}}
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Add Alumni</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.alumni.index') }}">Alumni</a>
                            </li>
                            <li class="breadcrumb-item active">Add Alumni</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        {{-- Validation Errors --}}
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Please correct the following errors:</strong>

                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>
            </div>
        @endif

        {{-- Information --}}
        <div class="alert alert-info">
            <i class="bx bx-info-circle me-2"></i>

            Only students with a
            <strong>completed graduation</strong>
            can be added to the Alumni directory.
        </div>

        <form method="POST"
              action="{{ route('admin.alumni.store') }}">

            @csrf

            <div class="row">

                {{-- Student / Graduation --}}
                <div class="col-lg-8">

                    <div class="card">
                        <div class="card-body">

                            <h4 class="card-title mb-4">
                                Alumni Information
                            </h4>

                            {{-- Completed Graduation --}}
                            <div class="mb-4">
                                <label for="student_id" class="form-label">
                                    Completed Graduate
                                    <span class="text-danger">*</span>
                                </label>

                                <select name="student_id"
                                        id="student_id"
                                        class="form-select @error('student_id') is-invalid @enderror"
                                        required>

                                    <option value="">
                                        -- Select Completed Graduate --
                                    </option>

                                    @forelse($completedGraduations as $graduation)

                                        @php
                                            $student = $graduation->student;
                                        @endphp

                                        @if($student)
                                            <option value="{{ $student->id }}"
                                                {{ old('student_id', request('student_id')) == $student->id ? 'selected' : '' }}>

                                                {{ trim($student->first_name . ' ' . $student->middle_name . ' ' . $student->last_name) }}

                                                —
                                                {{ $student->student_number }}

                                                —
                                                {{ $graduation->academicYear?->name ?? 'Graduation Year N/A' }}

                                            </option>
                                        @endif

                                    @empty

                                        <option value="" disabled>
                                            No students with completed graduation are currently eligible.
                                        </option>

                                    @endforelse

                                </select>

                                @error('student_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                                @if($completedGraduations->isEmpty())
                                    <div class="form-text text-danger">
                                        No eligible graduates are available.
                                        A student must have a completed graduation before being added to Alumni.
                                    </div>
                                @else
                                    <div class="form-text">
                                        Only completed graduates without an existing Alumni record are listed.
                                    </div>
                                @endif
                            </div>

                            {{-- Occupation --}}
                            <div class="mb-3">
                                <label for="current_occupation" class="form-label">
                                    Current Occupation
                                </label>

                                <input type="text"
                                       name="current_occupation"
                                       id="current_occupation"
                                       class="form-control @error('current_occupation') is-invalid @enderror"
                                       value="{{ old('current_occupation') }}"
                                       maxlength="255"
                                       placeholder="e.g. Software Engineer">

                                @error('current_occupation')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Employer --}}
                            <div class="mb-3">
                                <label for="current_employer" class="form-label">
                                    Current Employer
                                </label>

                                <input type="text"
                                       name="current_employer"
                                       id="current_employer"
                                       class="form-control @error('current_employer') is-invalid @enderror"
                                       value="{{ old('current_employer') }}"
                                       maxlength="255"
                                       placeholder="e.g. ABC Technologies">

                                @error('current_employer')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Phone --}}
                            <div class="mb-3">
                                <label for="phone" class="form-label">
                                    Phone
                                </label>

                                <input type="text"
                                       name="phone"
                                       id="phone"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       value="{{ old('phone') }}"
                                       maxlength="50"
                                       placeholder="e.g. 08012345678">

                                @error('phone')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    Email
                                </label>

                                <input type="email"
                                       name="email"
                                       id="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}"
                                       maxlength="255"
                                       placeholder="e.g. alumni@example.com">

                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Address --}}
                            <div class="mb-3">
                                <label for="address" class="form-label">
                                    Address
                                </label>

                                <textarea name="address"
                                          id="address"
                                          rows="4"
                                          class="form-control @error('address') is-invalid @enderror"
                                          maxlength="5000"
                                          placeholder="Current residential or contact address">{{ old('address') }}</textarea>

                                @error('address')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                        </div>
                    </div>

                </div>

                {{-- Graduation Summary --}}
                <div class="col-lg-4">

                    <div class="card">
                        <div class="card-body">

                            <h4 class="card-title mb-4">
                                Graduation Summary
                            </h4>

                            <div id="graduation-summary">

                                <div class="text-center text-muted py-4">
                                    <i class="bx bx-graduation display-5"></i>

                                    <p class="mt-2 mb-0">
                                        Select a completed graduate to view
                                        graduation information.
                                    </p>
                                </div>

                            </div>

                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">

                            <h5 class="font-size-15 mb-3">
                                <i class="bx bx-info-circle me-1"></i>
                                Alumni Workflow
                            </h5>

                            <ol class="text-muted mb-0 ps-3">
                                <li class="mb-2">
                                    Student completes final academic requirements.
                                </li>

                                <li class="mb-2">
                                    Graduation record is approved and completed.
                                </li>

                                <li class="mb-2">
                                    Graduate becomes eligible for Alumni registration.
                                </li>

                                <li>
                                    Alumni contact and career information can then be maintained.
                                </li>
                            </ol>

                        </div>
                    </div>

                </div>

            </div>

            {{-- Form Actions --}}
            <div class="row">
                <div class="col-12">

                    <div class="card">
                        <div class="card-body">

                            <div class="d-flex justify-content-between">

                                <a href="{{ route('admin.alumni.index') }}"
                                   class="btn btn-light">
                                    <i class="bx bx-arrow-back me-1"></i>
                                    Cancel
                                </a>

                                @can('alumni.manage')
                                    <button type="submit"
                                            class="btn btn-primary"
                                            {{ $completedGraduations->isEmpty() ? 'disabled' : '' }}>
                                        <i class="bx bx-save me-1"></i>
                                        Create Alumni
                                    </button>
                                @endcan

                            </div>

                        </div>
                    </div>

                </div>
            </div>

        </form>

    </div>
@endsection
@push('script')
    @php
        $graduationData = $completedGraduations->map(function ($graduation) {
            return [
                'student_id' => $graduation->student_id,
                'graduation_date' => optional($graduation->graduation_date)->format('d M Y'),
                'graduation_year' => $graduation->academicYear?->name,
                'qualification' => $graduation->qualification,
                'status' => $graduation->status,
            ];
        })->values()->all();
    @endphp

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const studentSelect = document.getElementById('student_id');
            const summary = document.getElementById('graduation-summary');

            const graduations = @json($graduationData);

            function escapeHtml(value) {
                const div = document.createElement('div');
                div.textContent = value ?? '';
                return div.innerHTML;
            }

            function updateSummary() {
                const studentId = studentSelect.value;

                if (!studentId) {
                    summary.innerHTML = `
                        <div class="text-center text-muted py-4">
                            <i class="bx bx-graduation display-5"></i>

                            <p class="mt-2 mb-0">
                                Select a completed graduate to view
                                graduation information.
                            </p>
                        </div>
                    `;

                    return;
                }

                const graduation = graduations.find(function (item) {
                    return String(item.student_id) === String(studentId);
                });

                if (!graduation) {
                    summary.innerHTML = `
                        <div class="alert alert-warning mb-0">
                            Graduation information could not be found.
                        </div>
                    `;

                    return;
                }

                summary.innerHTML = `
                    <div class="mb-3">
                        <small class="text-muted d-block">
                            Graduation Year
                        </small>

                        <strong>
                            ${escapeHtml(graduation.graduation_year || '—')}
                        </strong>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block">
                            Graduation Date
                        </small>

                        <strong>
                            ${escapeHtml(graduation.graduation_date || '—')}
                        </strong>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block">
                            Qualification
                        </small>

                        <strong>
                            ${escapeHtml(graduation.qualification || '—')}
                        </strong>
                    </div>

                    <div>
                        <small class="text-muted d-block mb-1">
                            Status
                        </small>

                        <span class="badge bg-success">
                            Completed
                        </span>
                    </div>
                `;
            }

            studentSelect.addEventListener('change', updateSummary);

            updateSummary();
        });
    </script>
@endpush