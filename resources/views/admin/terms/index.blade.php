@extends('backend.layout.default')

@section('title', 'Terms / Semesters')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row">
        <div class="col-12">

            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0 font-size-18">
                    Terms / Semesters
                </h4>

                <div class="page-title-right">

                    <ol class="breadcrumb m-0">

                        <li class="breadcrumb-item">
                            <a href="{{ url('/admin/dashboard') }}">
                                Dashboard
                            </a>
                        </li>

                        <li class="breadcrumb-item active">
                            Terms / Semesters
                        </li>

                    </ol>

                </div>

            </div>

        </div>
    </div>


    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show"
             role="alert">

            <i class="bx bx-check-circle me-2"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Error Messages --}}
    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show"
             role="alert">

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


    {{-- Statistics --}}
    <div class="row">

        {{-- Total Terms --}}
        <div class="col-xl-4 col-md-6">

            <div class="card">

                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div class="avatar-sm">

                            <span class="avatar-title rounded-circle bg-primary-subtle text-primary font-size-20">

                                <i class="bx bx-list-ul"></i>

                            </span>

                        </div>

                        <div class="flex-grow-1 ms-3">

                            <p class="text-muted mb-1">
                                Total Terms
                            </p>

                            <h4 class="mb-0">
                                {{ $terms->total() }}
                            </h4>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Current Terms --}}
        <div class="col-xl-4 col-md-6">

            <div class="card">

                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div class="avatar-sm">

                            <span class="avatar-title rounded-circle bg-success-subtle text-success font-size-20">

                                <i class="bx bx-check-circle"></i>

                            </span>

                        </div>

                        <div class="flex-grow-1 ms-3">

                            <p class="text-muted mb-1">
                                Current Terms
                            </p>

                            <h4 class="mb-0">

                                {{ $terms->getCollection()->where('is_current', true)->count() }}

                            </h4>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Academic Years --}}
        <div class="col-xl-4 col-md-6">

            <div class="card">

                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div class="avatar-sm">

                            <span class="avatar-title rounded-circle bg-info-subtle text-info font-size-20">

                                <i class="bx bx-calendar"></i>

                            </span>

                        </div>

                        <div class="flex-grow-1 ms-3">

                            <p class="text-muted mb-1">
                                Active Academic Years
                            </p>

                            <h4 class="mb-0">
                                {{ $academicYears->count() }}
                            </h4>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Terms Card --}}
    <div class="card">

        <div class="card-body">

            {{-- Header --}}
            <div class="row mb-3">

                <div class="col-md-6">

                    <h4 class="card-title mb-1">
                        Term List
                    </h4>

                    <p class="text-muted mb-0">
                        Manage academic terms and semesters.
                    </p>

                </div>

                <div class="col-md-6 text-md-end mt-3 mt-md-0">

                    <a href="{{ route('admin.terms.create') }}"
                       class="btn btn-primary">

                        <i class="bx bx-plus me-1"></i>
                        Add Term

                    </a>

                </div>

            </div>


            {{-- Filters --}}
            <form method="GET"
                  action="{{ route('admin.terms.index') }}"
                  class="row g-2 mb-4">

                {{-- Search --}}
                <div class="col-md-4">

                    <label class="form-label">
                        Search
                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="bx bx-search"></i>
                        </span>

                        <input type="text"
                               name="search"
                               class="form-control"
                               placeholder="Search term..."
                               value="{{ request('search') }}">

                    </div>

                </div>


                {{-- Academic Year --}}
                <div class="col-md-3">

                    <label class="form-label">
                        Academic Year
                    </label>

                    <select name="academic_year_id"
                            class="form-select">

                        <option value="">
                            All Academic Years
                        </option>

                        @foreach($academicYears as $academicYear)

                            <option value="{{ $academicYear->id }}"
                                {{ (string) request('academic_year_id') === (string) $academicYear->id ? 'selected' : '' }}>

                                {{ $academicYear->name }}

                                @if($academicYear->is_current)
                                    — Current
                                @endif

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Status --}}
                <div class="col-md-2">

                    <label class="form-label">
                        Status
                    </label>

                    <select name="status"
                            class="form-select">

                        <option value="">
                            All
                        </option>

                        <option value="active"
                            {{ request('status') === 'active' ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="inactive"
                            {{ request('status') === 'inactive' ? 'selected' : '' }}>
                            Inactive
                        </option>

                    </select>

                </div>


                {{-- Current --}}
                <div class="col-md-2">

                    <label class="form-label">
                        Current
                    </label>

                    <select name="current"
                            class="form-select">

                        <option value="">
                            All
                        </option>

                        <option value="yes"
                            {{ request('current') === 'yes' ? 'selected' : '' }}>
                            Current
                        </option>

                        <option value="no"
                            {{ request('current') === 'no' ? 'selected' : '' }}>
                            Not Current
                        </option>

                    </select>

                </div>


                {{-- Filter --}}
                <div class="col-md-1 d-flex align-items-end">

                    <button type="submit"
                            class="btn btn-primary w-100"
                            title="Filter">

                        <i class="bx bx-filter-alt"></i>

                    </button>

                </div>

            </form>


            {{-- Table --}}
            <div class="table-responsive">

                <table class="table align-middle table-nowrap mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>Term</th>

                            <th>Academic Year</th>

                            <th>Period</th>

                            <th>Current</th>

                            <th>Status</th>

                            <th class="text-end">
                                Actions
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($terms as $term)

                            <tr>

                                {{-- Number --}}
                                <td>
                                    {{ $terms->firstItem() + $loop->index }}
                                </td>


                                {{-- Term --}}
                                <td>

                                    <div class="d-flex align-items-center">

                                        <div class="avatar-xs me-2">

                                            <span class="avatar-title rounded-circle bg-primary-subtle text-primary">

                                                <i class="bx bx-list-ol"></i>

                                            </span>

                                        </div>

                                        <div>

                                            <h5 class="font-size-14 mb-0">

                                                <a href="{{ route('admin.terms.show', $term) }}"
                                                   class="text-dark">

                                                    {{ $term->name }}

                                                </a>

                                            </h5>

                                            <small class="text-muted">

                                                Term {{ $term->term_number }}

                                            </small>

                                        </div>

                                    </div>

                                </td>


                                {{-- Academic Year --}}
                                <td>

                                    @if($term->academicYear)

                                        <a href="{{ route('admin.academic-years.show', $term->academicYear) }}"
                                           class="text-dark">

                                            {{ $term->academicYear->name }}

                                        </a>

                                        @if($term->academicYear->is_current)

                                            <br>

                                            <small class="text-success">

                                                <i class="bx bx-check-circle"></i>
                                                Current Year

                                            </small>

                                        @endif

                                    @else

                                        <span class="text-muted">
                                            N/A
                                        </span>

                                    @endif

                                </td>


                                {{-- Period --}}
                                <td>

                                    <div>

                                        <i class="bx bx-calendar-event text-muted me-1"></i>

                                        {{ $term->start_date?->format('d M Y') }}

                                    </div>

                                    <div class="text-muted font-size-12">

                                        to

                                        {{ $term->end_date?->format('d M Y') }}

                                    </div>

                                </td>


                                {{-- Current --}}
                                <td>

                                    @if($term->is_current)

                                        <span class="badge bg-success">

                                            <i class="bx bx-check me-1"></i>
                                            Current

                                        </span>

                                    @else

                                        <span class="badge bg-light text-muted">
                                            Not Current
                                        </span>

                                    @endif

                                </td>


                                {{-- Status --}}
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


                                {{-- Actions --}}
                                <td class="text-end">

                                    <div class="d-flex justify-content-end gap-1">

                                        {{-- View --}}
                                        <a href="{{ route('admin.terms.show', $term) }}"
                                           class="btn btn-sm btn-info"
                                           title="View Term">

                                            <i class="bx bx-show-alt"></i>

                                        </a>


                                        {{-- Edit --}}
                                        <a href="{{ route('admin.terms.edit', $term) }}"
                                           class="btn btn-sm btn-primary"
                                           title="Edit Term">

                                            <i class="bx bx-edit-alt"></i>

                                        </a>


                                        {{-- Delete --}}
                                        <form method="POST"
                                              action="{{ route('admin.terms.destroy', $term) }}"
                                              class="d-inline">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-danger"
                                                    title="Delete Term"
                                                    onclick="return confirm('Are you sure you want to delete {{ $term->name }}?')">

                                                <i class="bx bx-trash"></i>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7"
                                    class="text-center py-5">

                                    <div class="avatar-lg mx-auto mb-3">

                                        <span class="avatar-title rounded-circle bg-light text-primary font-size-30">

                                            <i class="bx bx-list-ul"></i>

                                        </span>

                                    </div>

                                    <h5>
                                        No Terms Found
                                    </h5>

                                    <p class="text-muted mb-3">

                                        No academic terms match your current
                                        search or filters.

                                    </p>

                                    <a href="{{ route('admin.terms.create') }}"
                                       class="btn btn-primary">

                                        <i class="bx bx-plus me-1"></i>
                                        Create Term

                                    </a>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- Pagination --}}
            @if($terms->hasPages())

                <div class="mt-4">

                    {{ $terms->links() }}

                </div>

            @endif

        </div>

    </div>

</div>

@endsection