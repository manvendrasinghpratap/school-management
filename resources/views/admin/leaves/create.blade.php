@extends('backend.layout.default')

@section('content')
<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">New Leave Request</h4>

                <div class="page-title-right">
                    <a href="{{ route('admin.leaves.index') }}"
                       class="btn btn-light">
                        <i class="mdi mdi-arrow-left me-1"></i>
                        Back to Leave Requests
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Please correct the following:</strong>

            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    @endif

    {{-- Leave Request Form --}}
    <div class="row">
        <div class="col-xl-8 col-lg-10">

            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-calendar-plus-outline me-1"></i>
                        Leave Request Details
                    </h5>
                </div>

                <div class="card-body">

                    <form method="POST"
                          action="{{ route('admin.leaves.store') }}">

                        @csrf

                        <div class="row g-3">

                            {{-- Staff --}}
                            <div class="col-md-12">
                                <label for="staff_id" class="form-label">
                                    Staff <span class="text-danger">*</span>
                                </label>

                                <select name="staff_id"
                                        id="staff_id"
                                        class="form-select @error('staff_id') is-invalid @enderror"
                                        required>

                                    <option value="">
                                        Select Staff
                                    </option>

                                    @foreach($staff as $member)
                                        <option value="{{ $member->id }}"
                                            {{ old('staff_id') == $member->id ? 'selected' : '' }}>

                                            {{ $member->first_name }}
                                            {{ $member->middle_name }}
                                            {{ $member->last_name }}

                                            @if($member->employee_number)
                                                — {{ $member->employee_number }}
                                            @endif

                                        </option>
                                    @endforeach

                                </select>

                                @error('staff_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Leave Type --}}
                            <div class="col-md-6">
                                <label for="leave_type" class="form-label">
                                    Leave Type <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                       name="leave_type"
                                       id="leave_type"
                                       class="form-control @error('leave_type') is-invalid @enderror"
                                       value="{{ old('leave_type') }}"
                                       placeholder="e.g. Annual Leave"
                                       maxlength="100"
                                       required>

                                @error('leave_type')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Start Date --}}
                            <div class="col-md-3">
                                <label for="start_date" class="form-label">
                                    Start Date <span class="text-danger">*</span>
                                </label>

                                <input type="date"
                                       name="start_date"
                                       id="start_date"
                                       class="form-control @error('start_date') is-invalid @enderror"
                                       value="{{ old('start_date') }}"
                                       required>

                                @error('start_date')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- End Date --}}
                            <div class="col-md-3">
                                <label for="end_date" class="form-label">
                                    End Date <span class="text-danger">*</span>
                                </label>

                                <input type="date"
                                       name="end_date"
                                       id="end_date"
                                       class="form-control @error('end_date') is-invalid @enderror"
                                       value="{{ old('end_date') }}"
                                       required>

                                @error('end_date')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Reason --}}
                            <div class="col-12">
                                <label for="reason" class="form-label">
                                    Reason
                                </label>

                                <textarea name="reason"
                                          id="reason"
                                          rows="5"
                                          class="form-control @error('reason') is-invalid @enderror"
                                          maxlength="5000"
                                          placeholder="Enter the reason for the leave request...">{{ old('reason') }}</textarea>

                                @error('reason')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Information --}}
                            <div class="col-12">
                                <div class="alert alert-info mb-0">
                                    <i class="mdi mdi-information-outline me-1"></i>

                                    New leave requests are submitted with
                                    <strong>Pending</strong> status and must be
                                    approved or rejected by an authorized user.
                                </div>
                            </div>

                            {{-- Buttons --}}
                            <div class="col-12">

                                <button type="submit"
                                        class="btn btn-primary">
                                    <i class="mdi mdi-content-save me-1"></i>
                                    Submit Leave Request
                                </button>

                                <a href="{{ route('admin.leaves.index') }}"
                                   class="btn btn-light ms-1">
                                    Cancel
                                </a>

                            </div>

                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

</div>
@endsection