@extends('backend.layout.default')

@section('content')
    <div class="container-fluid">

        {{-- Page Header --}}
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Edit Alumni</h4>

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

                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.alumni.show', $alumni) }}">
                                    Profile
                                </a>
                            </li>

                            <li class="breadcrumb-item active">
                                Edit
                            </li>
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

        {{-- Student / Graduation Information --}}
        <div class="row">

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

                <div class="card">
                    <div class="card-body">

                        <h4 class="card-title mb-4">
                            <i class="bx bx-graduation me-1"></i>
                            Graduation Information
                        </h4>

                        <div class="mb-3">
                            <small class="text-muted d-block">
                                Graduation Year
                            </small>

                            <strong>
                                {{ $alumni->graduation_year ?? '—' }}
                            </strong>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block">
                                Graduation Date
                            </small>

                            <strong>
                                {{ $alumni->graduation_date?->format('d M Y') ?? '—' }}
                            </strong>
                        </div>

                        <div>
                            <small class="text-muted d-block">
                                Alumni Since
                            </small>

                            <strong>
                                {{ $alumni->created_at?->format('d M Y') ?? '—' }}
                            </strong>
                        </div>

                    </div>
                </div>

            </div>

            {{-- Editable Information --}}
            <div class="col-lg-8">

                <form method="POST"
                      action="{{ route('admin.alumni.update', $alumni) }}">

                    @csrf
                    @method('PUT')

                    <div class="card">
                        <div class="card-body">

                            <h4 class="card-title mb-4">
                                <i class="bx bx-edit-alt me-1"></i>
                                Alumni Information
                            </h4>

                            {{-- Occupation --}}
                            <div class="mb-3">
                                <label for="current_occupation" class="form-label">
                                    Current Occupation
                                </label>

                                <input type="text"
                                       name="current_occupation"
                                       id="current_occupation"
                                       class="form-control @error('current_occupation') is-invalid @enderror"
                                       value="{{ old('current_occupation', $alumni->current_occupation) }}"
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
                                       value="{{ old('current_employer', $alumni->current_employer) }}"
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
                                       value="{{ old('phone', $alumni->phone) }}"
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
                                       value="{{ old('email', $alumni->email) }}"
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
                                          rows="5"
                                          class="form-control @error('address') is-invalid @enderror"
                                          maxlength="5000"
                                          placeholder="Current residential or contact address">{{ old('address', $alumni->address) }}</textarea>

                                @error('address')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- Important Notice --}}
                    <div class="alert alert-info">
                        <i class="bx bx-info-circle me-2"></i>

                        Graduation information is maintained from the student's
                        completed graduation record and cannot be changed from
                        this page.
                    </div>

                    {{-- Actions --}}
                    <div class="card">
                        <div class="card-body">

                            <div class="d-flex justify-content-between">

                                <a href="{{ route('admin.alumni.show', $alumni) }}"
                                   class="btn btn-light">
                                    <i class="bx bx-arrow-back me-1"></i>
                                    Cancel
                                </a>

                                @can('alumni.manage')
                                    <button type="submit"
                                            class="btn btn-primary">
                                        <i class="bx bx-save me-1"></i>
                                        Save Changes
                                    </button>
                                @endcan

                            </div>

                        </div>
                    </div>

                </form>

            </div>

        </div>

    </div>
@endsection