@extends('backend.layout.default')

@section('content')
    <div class="container-fluid">

        {{-- Page Header --}}
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Alumni</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active">Alumni</li>
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
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Error Message --}}
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bx bx-error-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Validation Errors --}}
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Statistics --}}
        <div class="row">
            <div class="col-xl-4 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm">
                                <span class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-20">
                                    <i class="bx bx-group"></i>
                                </span>
                            </div>

                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1">Total Alumni</p>
                                <h4 class="mb-0">
                                    {{ $alumni->total() }}
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm">
                                <span class="avatar-title rounded-circle bg-success bg-soft text-success font-size-20">
                                    <i class="bx bx-graduation"></i>
                                </span>
                            </div>

                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1">Current Page</p>
                                <h4 class="mb-0">
                                    {{ $alumni->count() }}
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm">
                                <span class="avatar-title rounded-circle bg-info bg-soft text-info font-size-20">
                                    <i class="bx bx-calendar"></i>
                                </span>
                            </div>

                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1">Latest Graduation Year</p>
                                <h4 class="mb-0">
                                    {{ $alumni->first()?->graduation_year ?? '—' }}
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Alumni List --}}
        <div class="card">
            <div class="card-body">

                {{-- Header --}}
                <div class="row align-items-center mb-3">
                    <div class="col-md-6">
                        <h4 class="card-title mb-1">Alumni Directory</h4>
                        <p class="text-muted mb-0">
                            Former students who have completed their graduation.
                        </p>
                    </div>

                    <div class="col-md-6 text-md-end mt-3 mt-md-0">
                        @can('alumni.manage')
                            <a href="{{ route('admin.alumni.create') }}"
                               class="btn btn-primary">
                                <i class="bx bx-plus me-1"></i>
                                Add Alumni
                            </a>
                        @endcan
                    </div>
                </div>

                {{-- Search --}}
                <form method="GET"
                      action="{{ route('admin.alumni.index') }}"
                      class="mb-4">

                    <div class="row g-2">

                        <div class="col-md-10">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bx bx-search"></i>
                                </span>

                                <input type="text"
                                       name="search"
                                       class="form-control"
                                       value="{{ request('search') }}"
                                       placeholder="Search by student name, student number, graduation year, occupation, employer, phone or email">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <button type="submit"
                                    class="btn btn-primary w-100">
                                <i class="bx bx-search me-1"></i>
                                Search
                            </button>
                        </div>

                    </div>

                    @if(request()->filled('search'))
                        <div class="mt-2">
                            <a href="{{ route('admin.alumni.index') }}"
                               class="text-danger">
                                <i class="bx bx-x-circle me-1"></i>
                                Clear Search
                            </a>
                        </div>
                    @endif

                </form>

                {{-- Table --}}
                @if($alumni->count())
                    <div class="table-responsive">
                        <table class="table align-middle table-nowrap mb-0">

                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Student</th>
                                    <th>Student Number</th>
                                    <th>Graduation</th>
                                    <th>Occupation</th>
                                    <th>Employer</th>
                                    <th>Contact</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($alumni as $item)
                                    <tr>

                                        {{-- Number --}}
                                        <td>
                                            {{ $alumni->firstItem() + $loop->index }}
                                        </td>

                                        {{-- Student --}}
                                        <td>
                                            <div class="d-flex align-items-center">

                                                <div class="avatar-xs me-2">
                                                    <span class="avatar-title rounded-circle bg-primary bg-soft text-primary">
                                                        {{ strtoupper(substr($item->student?->first_name ?? 'A', 0, 1)) }}
                                                    </span>
                                                </div>

                                                <div>
                                                    <h5 class="font-size-14 mb-0">
                                                        @if($item->student)
                                                            <a href="{{ route('admin.students.show', $item->student) }}"
                                                               class="text-dark">
                                                                {{ trim($item->student->first_name . ' ' . $item->student->middle_name . ' ' . $item->student->last_name) }}
                                                            </a>
                                                        @else
                                                            <span class="text-muted">
                                                                Student unavailable
                                                            </span>
                                                        @endif
                                                    </h5>
                                                </div>

                                            </div>
                                        </td>

                                        {{-- Student Number --}}
                                        <td>
                                            {{ $item->student?->student_number ?? '—' }}
                                        </td>

                                        {{-- Graduation --}}
                                        <td>
                                            <div>
                                                <span class="badge bg-success">
                                                    {{ $item->graduation_year ?? '—' }}
                                                </span>
                                            </div>

                                            @if($item->graduation_date)
                                                <small class="text-muted">
                                                    {{ $item->graduation_date->format('d M Y') }}
                                                </small>
                                            @endif
                                        </td>

                                        {{-- Occupation --}}
                                        <td>
                                            {{ $item->current_occupation ?: '—' }}
                                        </td>

                                        {{-- Employer --}}
                                        <td>
                                            {{ $item->current_employer ?: '—' }}
                                        </td>

                                        {{-- Contact --}}
                                        <td>
                                            @if($item->phone)
                                                <div>
                                                    <i class="bx bx-phone me-1"></i>
                                                    {{ $item->phone }}
                                                </div>
                                            @endif

                                            @if($item->email)
                                                <div>
                                                    <i class="bx bx-envelope me-1"></i>
                                                    {{ $item->email }}
                                                </div>
                                            @endif

                                            @if(!$item->phone && !$item->email)
                                                —
                                            @endif
                                        </td>

                                        {{-- Actions --}}
                                        <td class="text-end">
                                            <div class="d-inline-flex gap-1">

                                                @can('alumni.view')
                                                    <a href="{{ route('admin.alumni.show', $item) }}"
                                                    class="btn btn-sm btn-soft-primary"
                                                    title="View Alumni">
                                                        <i class="bx bx-show-alt"></i>
                                                    </a>
                                                @endcan

                                                @can('alumni.manage')
                                                    <a href="{{ route('admin.alumni.edit', $item) }}"
                                                    class="btn btn-sm btn-soft-info"
                                                    title="Edit Alumni">
                                                        <i class="bx bx-edit-alt"></i>
                                                    </a>

                                                    <form method="POST"
                                                        action="{{ route('admin.alumni.destroy', $item) }}"
                                                        class="d-inline"
                                                        onsubmit="return confirm('Are you sure you want to delete this Alumni record?');">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit"
                                                                class="btn btn-sm btn-soft-danger"
                                                                title="Delete Alumni">
                                                            <i class="bx bx-trash"></i>
                                                        </button>
                                                    </form>
                                                @endcan

                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-4">
                        {{ $alumni->links() }}
                    </div>

                @else

                    {{-- Empty State --}}
                    <div class="text-center py-5">

                        <div class="mb-4">
                            <i class="bx bx-group display-4 text-muted"></i>
                        </div>

                        <h5>No Alumni Records Found</h5>

                        @if(request()->filled('search'))
                            <p class="text-muted mb-4">
                                No Alumni records matched your search criteria.
                            </p>

                            <a href="{{ route('admin.alumni.index') }}"
                               class="btn btn-light">
                                <i class="bx bx-refresh me-1"></i>
                                Clear Search
                            </a>
                        @else
                            <p class="text-muted mb-4">
                                There are currently no Alumni records.
                            </p>

                            @can('alumni.manage')
                                <a href="{{ route('admin.alumni.create') }}"
                                   class="btn btn-primary">
                                    <i class="bx bx-plus me-1"></i>
                                    Add First Alumni
                                </a>
                            @endcan
                        @endif

                    </div>

                @endif

            </div>
        </div>

    </div>
@endsection