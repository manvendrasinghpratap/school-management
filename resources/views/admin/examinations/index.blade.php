@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">
                    Examination Management
                </h4>

                <div class="page-title-right">
                    @can('examinations.create')
                        <a href="{{ route('admin.examinations.create') }}"
                           class="btn btn-primary">
                            <i class="bx bx-plus me-1"></i>
                            New Examination
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close">
            </button>
        </div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Filters --}}
    <div class="card">
        <div class="card-body">

            <h5 class="card-title mb-3">
                Search & Filter
            </h5>

            <form method="GET"
                  action="{{ route('admin.examinations.index') }}">

                <div class="row g-3">

                    {{-- Search --}}
                    <div class="col-md-4">
                        <label class="form-label">
                            Search
                        </label>

                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               class="form-control"
                               placeholder="Examination name or type">
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
                                    {{ $academicYear->name ?? $academicYear->year ?? $academicYear->id }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    {{-- Term --}}
                    <div class="col-md-3">
                        <label class="form-label">
                            Term
                        </label>

                        <select name="term_id"
                                class="form-select">

                            <option value="">
                                All Terms
                            </option>

                            @foreach($terms as $term)
                                <option value="{{ $term->id }}"
                                    {{ (string) request('term_id') === (string) $term->id ? 'selected' : '' }}>
                                    {{ $term->name ?? $term->title ?? $term->id }}
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

                            <option value="draft"
                                {{ request('status') === 'draft' ? 'selected' : '' }}>
                                Draft
                            </option>

                            <option value="scheduled"
                                {{ request('status') === 'scheduled' ? 'selected' : '' }}>
                                Scheduled
                            </option>

                            <option value="ongoing"
                                {{ request('status') === 'ongoing' ? 'selected' : '' }}>
                                Ongoing
                            </option>

                            <option value="completed"
                                {{ request('status') === 'completed' ? 'selected' : '' }}>
                                Completed
                            </option>

                            <option value="published"
                                {{ request('status') === 'published' ? 'selected' : '' }}>
                                Published
                            </option>

                        </select>
                    </div>

                </div>

                <div class="mt-3">

                    <button type="submit"
                            class="btn btn-primary">
                        <i class="bx bx-search me-1"></i>
                        Filter
                    </button>

                    <a href="{{ route('admin.examinations.index') }}"
                       class="btn btn-light">
                        Reset
                    </a>

                </div>

            </form>

        </div>
    </div>

    {{-- Examination List --}}
    <div class="card">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">
                    Examinations
                </h5>

                <span class="text-muted">
                    {{ $examinations->total() }} record(s)
                </span>
            </div>

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Examination</th>
                            <th>Type</th>
                            <th>Academic Year</th>
                            <th>Term</th>
                            <th>Period</th>
                            <th>Status</th>
                            <th style="width: 180px;">
                                Actions
                            </th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($examinations as $examination)

                            <tr>

                                <td>
                                    {{ $examinations->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    <strong>
                                        {{ $examination->name }}
                                    </strong>
                                </td>

                                <td>
                                    {{ ucwords(str_replace('_', ' ', $examination->type)) }}
                                </td>

                                <td>
                                    {{ $examination->academicYear?->name
                                        ?? $examination->academicYear?->year
                                        ?? $examination->academic_year_id }}
                                </td>

                                <td>
                                    {{ $examination->term?->name
                                        ?? $examination->term?->title
                                        ?? ($examination->term_id ?: '—') }}
                                </td>

                                <td>
                                    @if($examination->start_date)
                                        {{ $examination->start_date->format('Y-m-d') }}
                                    @else
                                        —
                                    @endif

                                    @if($examination->end_date)
                                        <br>
                                        <small class="text-muted">
                                            to {{ $examination->end_date->format('Y-m-d') }}
                                        </small>
                                    @endif
                                </td>

                                <td>

                                    @php
                                        $statusClass = match($examination->status) {
                                            'draft' => 'bg-secondary',
                                            'scheduled' => 'bg-info',
                                            'ongoing' => 'bg-warning',
                                            'completed' => 'bg-success',
                                            'published' => 'bg-primary',
                                            default => 'bg-secondary',
                                        };
                                    @endphp

                                    <span class="badge {{ $statusClass }}">
                                        {{ ucfirst($examination->status) }}
                                    </span>

                                </td>

                                <td>

                                    <div class="d-flex gap-1">

                                        @can('examinations.view')
                                            <a href="{{ route('admin.examinations.show', $examination) }}"
                                               class="btn btn-sm btn-info"
                                               title="View">
                                                <i class="bx bx-show"></i>
                                            </a>
                                        @endcan

                                        @can('examinations.update')
                                            <a href="{{ route('admin.examinations.edit', $examination) }}"
                                               class="btn btn-sm btn-warning"
                                               title="Edit">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                        @endcan

                                        @can('examinations.delete')
                                            <form method="POST"
                                                  action="{{ route('admin.examinations.destroy', $examination) }}"
                                                  onsubmit="return confirm('Are you sure you want to delete this examination?');">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-sm btn-danger"
                                                        title="Delete">
                                                    <i class="bx bx-trash"></i>
                                                </button>

                                            </form>
                                        @endcan

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="8"
                                    class="text-center py-4">

                                    <div class="text-muted">

                                        <i class="bx bx-calendar-x font-size-24 d-block mb-2"></i>

                                        No examinations found.

                                    </div>

                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- Pagination --}}
            @if($examinations->hasPages())

                <div class="mt-3">
                    {{ $examinations->links() }}
                </div>

            @endif

        </div>
    </div>

</div>

@endsection