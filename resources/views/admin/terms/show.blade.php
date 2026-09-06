@extends('backend.layout.default')

@section('title', 'View Term')

@section('content')
<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">Term Details</h4>
                    <p class="text-muted mb-0">
                        View academic term / semester information.
                    </p>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('admin.terms.index') }}"
                       class="btn btn-secondary">
                        <i class="bx bx-arrow-back me-1"></i>
                        Back
                    </a>

                    <a href="{{ route('admin.terms.edit', $term) }}"
                       class="btn btn-primary">
                        <i class="bx bx-edit-alt me-1"></i>
                        Edit
                    </a>

                    @if(!$term->is_current)
                        <form method="POST"
                              action="{{ route('admin.terms.set-current', $term) }}"
                              class="d-inline">
                            @csrf
                            @method('PATCH')

                            <button type="submit"
                                    class="btn btn-success"
                                    onclick="return confirm('Set this term as the current term?')">
                                <i class="bx bx-check-circle me-1"></i>
                                Set Current
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bx bx-check-circle me-1"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">

        {{-- Main Information --}}
        <div class="col-xl-8 col-lg-8">

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-calendar me-2"></i>
                        Term Information
                    </h5>
                </div>

                <div class="card-body">

                    <div class="row">

                        {{-- Term Name --}}
                        <div class="col-md-6 mb-4">
                            <label class="text-muted d-block mb-1">
                                Term Name
                            </label>

                            <h5 class="mb-0">
                                {{ $term->name }}
                            </h5>
                        </div>

                        {{-- Term Number --}}
                        <div class="col-md-6 mb-4">
                            <label class="text-muted d-block mb-1">
                                Term Number
                            </label>

                            <h5 class="mb-0">
                                Term {{ $term->term_number }}
                            </h5>
                        </div>

                        {{-- Academic Year --}}
                        <div class="col-md-6 mb-4">
                            <label class="text-muted d-block mb-1">
                                Academic Year
                            </label>

                            @if($term->academicYear)
                                <a href="{{ route('admin.academic-years.show', $term->academicYear) }}"
                                   class="fw-semibold">
                                    {{ $term->academicYear->name }}
                                </a>
                            @else
                                <span class="text-muted">
                                    —
                                </span>
                            @endif
                        </div>

                        {{-- Status --}}
                        <div class="col-md-6 mb-4">
                            <label class="text-muted d-block mb-1">
                                Status
                            </label>

                            @if($term->is_active)
                                <span class="badge bg-success-subtle text-success fs-6">
                                    Active
                                </span>
                            @else
                                <span class="badge bg-danger-subtle text-danger fs-6">
                                    Inactive
                                </span>
                            @endif

                            @if($term->is_current)
                                <span class="badge bg-primary-subtle text-primary fs-6 ms-1">
                                    Current
                                </span>
                            @endif
                        </div>

                        {{-- Start Date --}}
                        <div class="col-md-6 mb-4">
                            <label class="text-muted d-block mb-1">
                                Start Date
                            </label>

                            <div class="fw-semibold">
                                {{ $term->start_date?->format('d M Y') ?? '—' }}
                            </div>
                        </div>

                        {{-- End Date --}}
                        <div class="col-md-6 mb-4">
                            <label class="text-muted d-block mb-1">
                                End Date
                            </label>

                            <div class="fw-semibold">
                                {{ $term->end_date?->format('d M Y') ?? '—' }}
                            </div>
                        </div>

                        {{-- Duration --}}
                        <div class="col-md-6 mb-4">
                            <label class="text-muted d-block mb-1">
                                Duration
                            </label>

                            @if($term->start_date && $term->end_date)
                                <div class="fw-semibold">
                                    {{ $term->start_date->diffInDays($term->end_date) + 1 }}
                                    days
                                </div>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </div>

                        {{-- School --}}
                        <div class="col-md-6 mb-4">
                            <label class="text-muted d-block mb-1">
                                School
                            </label>

                            <div class="fw-semibold">
                                {{ $term->school?->name ?? '—' }}
                            </div>
                        </div>

                    </div>

                </div>
            </div>

        </div>

        {{-- Summary --}}
        <div class="col-xl-4 col-lg-4">

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-info-circle me-2"></i>
                        Term Summary
                    </h5>
                </div>

                <div class="card-body">

                    <div class="mb-4">
                        <div class="text-muted mb-1">
                            Term
                        </div>

                        <h4 class="mb-0">
                            {{ $term->term_number }}
                        </h4>
                    </div>

                    <div class="mb-4">
                        <div class="text-muted mb-1">
                            Academic Year
                        </div>

                        <div class="fw-semibold">
                            {{ $term->academicYear?->name ?? '—' }}
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="text-muted mb-1">
                            Current Term
                        </div>

                        @if($term->is_current)
                            <span class="badge bg-success">
                                Yes
                            </span>
                        @else
                            <span class="badge bg-secondary">
                                No
                            </span>
                        @endif
                    </div>

                    <div class="mb-4">
                        <div class="text-muted mb-1">
                            Active
                        </div>

                        @if($term->is_active)
                            <span class="badge bg-success">
                                Yes
                            </span>
                        @else
                            <span class="badge bg-danger">
                                No
                            </span>
                        @endif
                    </div>

                    <hr>

                    <div class="small text-muted">
                        <div class="mb-2">
                            <strong>Created:</strong>
                            {{ $term->created_at?->format('d M Y H:i') ?? '—' }}
                        </div>

                        <div>
                            <strong>Last Updated:</strong>
                            {{ $term->updated_at?->format('d M Y H:i') ?? '—' }}
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>

    {{-- Delete --}}
    <div class="card border-danger">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h5 class="text-danger mb-1">
                        Delete Term
                    </h5>

                    <p class="text-muted mb-0">
                        Deleting this term will soft-delete the record.
                        Terms with student enrollments cannot be deleted.
                    </p>
                </div>

                <form method="POST"
                      action="{{ route('admin.terms.destroy', $term) }}"
                      class="d-inline">

                    @csrf
                    @method('DELETE')

                    <button type="submit"
                            class="btn btn-danger"
                            onclick="return confirm('Are you sure you want to delete {{ $term->name }}?')">
                        <i class="bx bx-trash me-1"></i>
                        Delete Term
                    </button>

                </form>

            </div>

        </div>
    </div>

</div>
@endsection