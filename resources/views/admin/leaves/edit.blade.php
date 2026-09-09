@extends('backend.layout.default')

@section('content')
<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Edit Leave Request</h4>

                <div class="page-title-right">
                    <a href="{{ route('admin.leaves.show', $leave) }}"
                       class="btn btn-light">
                        <i class="mdi mdi-arrow-left me-1"></i>
                        Back to Leave Details
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

    {{-- Edit Form --}}
    <div class="row">
        <div class="col-xl-8 col-lg-10">

            <div class="card">

                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-calendar-edit-outline me-1"></i>
                        Edit Leave Request
                    </h5>

                    <span class="badge bg-warning text-dark">
                        Pending
                    </span>
                </div>

                <div class="card-body">

                    <form method="POST"
                          action="{{ route('admin.leaves.update', $leave) }}">

                        @csrf
                        @method('PUT')

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
                                            {{ (string)old('staff_id', $leave->staff_id) === (string)$member->id ? 'selected' : '' }}>

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
                                       value="{{ old('leave_type', $leave->leave_type) }}"
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
                                       value="{{ old('start_date', $leave->start_date?->format('Y-m-d')) }}"
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
                                       value="{{ old('end_date', $leave->end_date?->format('Y-m-d')) }}"
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
                                          maxlength="5000"
                                          class="form-control @error('reason') is-invalid @enderror"
                                          placeholder="Enter the reason for the leave request...">{{ old('reason', $leave->reason) }}</textarea>

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

                                    Only <strong>pending</strong> leave requests can
                                    be edited. Approved, rejected, and cancelled
                                    requests cannot be modified here.
                                </div>
                            </div>

                            {{-- Buttons --}}
                            <div class="col-12">

                                <button type="submit"
                                        class="btn btn-primary">
                                    <i class="mdi mdi-content-save me-1"></i>
                                    Update Leave Request
                                </button>

                                <a href="{{ route('admin.leaves.show', $leave) }}"
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