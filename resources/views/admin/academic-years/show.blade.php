@extends('backend.layout.default')

@section('title', 'Academic Year Details')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row">
        <div class="col-12">

            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0 font-size-18">
                    Academic Year Details
                </h4>

                <div class="page-title-right">

                    <ol class="breadcrumb m-0">

                        <li class="breadcrumb-item">
                            <a href="{{ url('/admin/dashboard') }}">
                                Dashboard
                            </a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.academic-years.index') }}">
                                Academic Years
                            </a>
                        </li>

                        <li class="breadcrumb-item active">
                            {{ $academicYear->name }}
                        </li>

                    </ol>

                </div>

            </div>

        </div>
    </div>


    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show" role="alert">

            <i class="bx bx-check-circle me-2"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Validation / Error Messages --}}
    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show" role="alert">

            <strong>Please check the following:</strong>

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


    {{-- Main Information --}}
    <div class="row">

        {{-- Academic Year Profile --}}
        <div class="col-xl-8">

            <div class="card">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div class="d-flex align-items-center">

                            <div class="avatar-lg me-3">

                                <span class="avatar-title rounded-circle bg-primary-subtle text-primary font-size-30">

                                    <i class="bx bx-calendar"></i>

                                </span>

                            </div>

                            <div>

                                <h4 class="mb-1">
                                    {{ $academicYear->name }}
                                </h4>

                                <p class="text-muted mb-0">
                                    Academic Year
                                </p>

                            </div>

                        </div>


                        {{-- Status --}}
                        <div class="text-end">

                            @if($academicYear->is_current)

                                <span class="badge bg-success font-size-13">
                                    <i class="bx bx-check-circle me-1"></i>
                                    Current
                                </span>

                            @else

                                <span class="badge bg-light text-muted font-size-13">
                                    Not Current
                                </span>

                            @endif


                            <div class="mt-2">

                                @if($academicYear->is_active)

                                    <span class="badge bg-success-subtle text-success">
                                        Active
                                    </span>

                                @else

                                    <span class="badge bg-danger-subtle text-danger">
                                        Inactive
                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>


                    <hr class="my-4">


                    {{-- Dates --}}
                    <div class="row">

                        <div class="col-md-6">

                            <div class="d-flex align-items-center mb-4">

                                <div class="avatar-sm me-3">

                                    <span class="avatar-title rounded-circle bg-primary-subtle text-primary">

                                        <i class="bx bx-calendar-event"></i>

                                    </span>

                                </div>

                                <div>

                                    <p class="text-muted mb-1">
                                        Start Date
                                    </p>

                                    <h5 class="mb-0">
                                        {{ $academicYear->start_date?->format('d M Y') }}
                                    </h5>

                                </div>

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="d-flex align-items-center mb-4">

                                <div class="avatar-sm me-3">

                                    <span class="avatar-title rounded-circle bg-info-subtle text-info">

                                        <i class="bx bx-calendar-check"></i>

                                    </span>

                                </div>

                                <div>

                                    <p class="text-muted mb-1">
                                        End Date
                                    </p>

                                    <h5 class="mb-0">
                                        {{ $academicYear->end_date?->format('d M Y') }}
                                    </h5>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- Summary --}}
                    <div class="row">

                        <div class="col-md-4">

                            <div class="border rounded p-3 mb-3">

                                <p class="text-muted mb-1">
                                    Academic Year ID
                                </p>

                                <h5 class="mb-0">
                                    #{{ $academicYear->id }}
                                </h5>

                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="border rounded p-3 mb-3">

                                <p class="text-muted mb-1">
                                    Terms
                                </p>

                                <h5 class="mb-0">

                                    {{ $academicYear->terms->count() }}

                                    {{ $academicYear->terms->count() === 1 ? 'Term' : 'Terms' }}

                                </h5>

                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="border rounded p-3 mb-3">

                                <p class="text-muted mb-1">
                                    School
                                </p>

                                <h5 class="mb-0">

                                    {{ $academicYear->school?->name ?? 'N/A' }}

                                </h5>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Terms --}}
            <div class="card">

                <div class="card-body">

                    <div class="d-flex align-items-center justify-content-between mb-4">

                        <div>

                            <h4 class="card-title mb-1">
                                Academic Terms
                            </h4>

                            <p class="text-muted mb-0">
                                Terms configured for {{ $academicYear->name }}.
                            </p>

                        </div>

                        {{-- Terms will be added when Terms module is built --}}
                        <span class="badge bg-info-subtle text-info">
                            {{ $academicYear->terms->count() }}
                            {{ $academicYear->terms->count() === 1 ? 'Term' : 'Terms' }}
                        </span>

                    </div>


                    @if($academicYear->terms->count())

                        <div class="table-responsive">

                            <table class="table align-middle table-nowrap mb-0">

                                <thead class="table-light">

                                    <tr>

                                        <th>#</th>
                                        <th>Term</th>
                                        <th>Period</th>
                                        <th>Current</th>
                                        <th>Status</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach($academicYear->terms->sortBy('term_number') as $term)

                                        <tr>

                                            <td>
                                                {{ $loop->iteration }}
                                            </td>

                                            <td>

                                                <h5 class="font-size-14 mb-0">
                                                    {{ $term->name }}
                                                </h5>

                                                <small class="text-muted">
                                                    Term {{ $term->term_number }}
                                                </small>

                                            </td>

                                            <td>

                                                {{ $term->start_date?->format('d M Y') }}

                                                <span class="text-muted">
                                                    to
                                                </span>

                                                {{ $term->end_date?->format('d M Y') }}

                                            </td>

                                            <td>

                                                @if($term->is_current)

                                                    <span class="badge bg-success">
                                                        Current
                                                    </span>

                                                @else

                                                    <span class="badge bg-light text-muted">
                                                        Not Current
                                                    </span>

                                                @endif

                                            </td>

                                            <td>

                                                @if($term->is_active)

                                                    <span class="badge bg-success-subtle text-success">
                                                        Active
                                                    </span>

                                                @else

                                                    <span class="badge bg-danger-subtle text-danger">
                                                        Inactive
                                                    </span>

                                                @endif

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="text-center py-5">

                            <div class="avatar-lg mx-auto mb-3">

                                <span class="avatar-title rounded-circle bg-light text-primary font-size-30">

                                    <i class="bx bx-list-ul"></i>

                                </span>

                            </div>

                            <h5>
                                No Terms Configured
                            </h5>

                            <p class="text-muted mb-0">

                                No academic terms have been configured for this
                                academic year yet.

                            </p>

                            <p class="text-muted mt-1 mb-0">

                                Term management will be available in the
                                <strong>Terms/Semesters</strong> module.

                            </p>

                        </div>

                    @endif

                </div>

            </div>

        </div>


        {{-- Right Sidebar --}}
        <div class="col-xl-4">

            {{-- Actions --}}
            <div class="card">

                <div class="card-body">

                    <h5 class="card-title mb-4">
                        Actions
                    </h5>


                    <div class="d-grid gap-2">

                        <a href="{{ route('admin.academic-years.edit', $academicYear) }}"
                           class="btn btn-primary">

                            <i class="bx bx-edit-alt me-1"></i>
                            Edit Academic Year

                        </a>


                        @if(!$academicYear->is_current)

                            <form method="POST"
                                  action="{{ route('admin.academic-years.set-current', $academicYear) }}">

                                @csrf
                                @method('PATCH')

                                <button type="submit"
                                        class="btn btn-success w-100"
                                        onclick="return confirm('Set {{ $academicYear->name }} as the current academic year?')">

                                    <i class="bx bx-check-circle me-1"></i>
                                    Set as Current

                                </button>

                            </form>

                        @else

                            <button type="button"
                                    class="btn btn-light"
                                    disabled>

                                <i class="bx bx-check-circle me-1"></i>
                                Current Academic Year

                            </button>

                        @endif


                        <form method="POST"
                              action="{{ route('admin.academic-years.destroy', $academicYear) }}">

                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="btn btn-outline-danger w-100"
                                    onclick="return confirm('Are you sure you want to delete {{ $academicYear->name }}?')">

                                <i class="bx bx-trash me-1"></i>
                                Delete Academic Year

                            </button>

                        </form>


                        <a href="{{ route('admin.academic-years.index') }}"
                           class="btn btn-light">

                            <i class="bx bx-arrow-back me-1"></i>
                            Back to Academic Years

                        </a>

                    </div>

                </div>

            </div>


            {{-- Record Information --}}
            <div class="card">

                <div class="card-body">

                    <h5 class="card-title mb-4">
                        Record Information
                    </h5>

                    <div class="table-responsive">

                        <table class="table table-sm table-borderless mb-0">

                            <tbody>

                                <tr>

                                    <td class="text-muted">
                                        ID
                                    </td>

                                    <td class="text-end fw-medium">
                                        #{{ $academicYear->id }}
                                    </td>

                                </tr>

                                <tr>

                                    <td class="text-muted">
                                        Created
                                    </td>

                                    <td class="text-end">

                                        {{ $academicYear->created_at?->format('d M Y H:i') }}

                                    </td>

                                </tr>

                                <tr>

                                    <td class="text-muted">
                                        Last Updated
                                    </td>

                                    <td class="text-end">

                                        {{ $academicYear->updated_at?->format('d M Y H:i') }}

                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection