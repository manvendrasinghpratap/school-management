@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Title --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">FEE STRUCTURES</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">Finance</li>
                        <li class="breadcrumb-item active">Fee Structures</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Please correct the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Main Card --}}
    <div class="card">

        <div class="card-header d-flex align-items-center justify-content-between">
            <div>
                <h4 class="card-title mb-1">Fee Structure Master</h4>
                <p class="card-title-desc mb-0">
                    Configure fees by category, academic year, class and term.
                </p>
            </div>

            @can('fee-structures.manage')
                <a
                    href="{{ route('admin.fee-structures.create') }}"
                    class="btn btn-primary"
                >
                    <i class="bx bx-plus me-1"></i>
                    Add Fee Structure
                </a>
            @endcan
        </div>

        <div class="card-body">

            {{-- Filters --}}
            <form
                method="GET"
                action="{{ route('admin.fee-structures.index') }}"
                class="mb-4"
            >
                <div class="row g-3">

                    {{-- Search --}}
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">Search</label>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="form-control"
                            placeholder="Category, code, year or class"
                        >
                    </div>

                    {{-- Fee Category --}}
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label">Fee Category</label>

                        <select
                            name="fee_category_id"
                            class="form-select"
                        >
                            <option value="">All Categories</option>

                            @foreach($feeCategories as $category)
                                <option
                                    value="{{ $category->id }}"
                                    {{ request('fee_category_id') == $category->id ? 'selected' : '' }}
                                >
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Academic Year --}}
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label">Academic Year</label>

                        <select
                            name="academic_year_id"
                            class="form-select"
                            onchange="this.form.submit()"
                        >
                            <option value="">All Years</option>

                            @foreach($academicYears as $academicYear)
                                <option
                                    value="{{ $academicYear->id }}"
                                    {{ request('academic_year_id') == $academicYear->id ? 'selected' : '' }}
                                >
                                    {{ $academicYear->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Class --}}
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label">Class</label>

                        <select
                            name="class_id"
                            class="form-select"
                        >
                            <option value="">All Classes</option>

                            @foreach($classes as $class)
                                <option
                                    value="{{ $class->id }}"
                                    {{ request('class_id') == $class->id ? 'selected' : '' }}
                                >
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Term --}}
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label">Term</label>

                        <select
                            name="term_id"
                            class="form-select"
                        >
                            <option value="">All Terms</option>

                            @foreach($terms as $term)
                                <option
                                    value="{{ $term->id }}"
                                    {{ request('term_id') == $term->id ? 'selected' : '' }}
                                >
                                    {{ $term->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Status --}}
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label">Status</label>

                        <select
                            name="status"
                            class="form-select"
                        >
                            <option value="">All Statuses</option>
                            <option
                                value="active"
                                {{ request('status') === 'active' ? 'selected' : '' }}
                            >
                                Active
                            </option>
                            <option
                                value="inactive"
                                {{ request('status') === 'inactive' ? 'selected' : '' }}
                            >
                                Inactive
                            </option>
                        </select>
                    </div>

                    {{-- Buttons --}}
                    <div class="col-lg-3 col-md-6 d-flex align-items-end gap-2">

                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-search me-1"></i>
                            Search
                        </button>

                        <a
                            href="{{ route('admin.fee-structures.index') }}"
                            class="btn btn-light"
                        >
                            Reset
                        </a>

                    </div>

                </div>
            </form>

            {{-- Table --}}
            @if($feeStructures->count())

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle mb-0">

                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Fee Category</th>
                                <th>Academic Year</th>
                                <th>Class</th>
                                <th>Term</th>
                                <th>Amount</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th width="150">Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($feeStructures as $structure)

                                <tr>

                                    <td>
                                        {{ $feeStructures->firstItem() + $loop->index }}
                                    </td>

                                    <td>
                                        <strong>
                                            {{ optional($structure->feeCategory)->name ?: '—' }}
                                        </strong>

                                        @if(optional($structure->feeCategory)->code)
                                            <br>
                                            <small class="text-muted">
                                                {{ $structure->feeCategory->code }}
                                            </small>
                                        @endif
                                    </td>

                                    <td>
                                        {{ optional($structure->academicYear)->name ?: '—' }}
                                    </td>

                                    <td>
                                        {{ optional($structure->classModel)->name ?: 'All Classes' }}
                                    </td>

                                    <td>
                                        {{ optional($structure->term)->name ?: 'All Terms' }}
                                    </td>

                                    <td>
                                        <strong>
                                            {{ number_format((float) $structure->amount, 2) }}
                                        </strong>
                                    </td>

                                    <td>
                                        @if($structure->due_date)
                                            {{ \Carbon\Carbon::parse($structure->due_date)->format('d M Y') }}
                                        @else
                                            —
                                        @endif
                                    </td>

                                    <td>
                                        @if($structure->is_active)
                                            <span class="badge bg-success">
                                                Active
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>

                                    <td>

                                        <div class="d-flex gap-1">

                                            @can('fee-structures.view')
                                                <a
                                                    href="{{ route('admin.fee-structures.show', $structure) }}"
                                                    class="btn btn-sm btn-soft-info"
                                                    title="View"
                                                >
                                                    <i class="bx bx-show"></i>
                                                </a>
                                            @endcan

                                            @can('fee-structures.manage')
                                                <a
                                                    href="{{ route('admin.fee-structures.edit', $structure) }}"
                                                    class="btn btn-sm btn-soft-primary"
                                                    title="Edit"
                                                >
                                                    <i class="bx bx-edit"></i>
                                                </a>

                                                <form
                                                    method="POST"
                                                    action="{{ route('admin.fee-structures.toggle-status', $structure) }}"
                                                    class="d-inline"
                                                >
                                                    @csrf
                                                    @method('PATCH')

                                                    <button
                                                        type="submit"
                                                        class="btn btn-sm btn-soft-warning"
                                                        title="{{ $structure->is_active ? 'Deactivate' : 'Activate' }}"
                                                    >
                                                        <i class="bx bx-power-off"></i>
                                                    </button>
                                                </form>

                                                @if(!$structure->studentFees()->exists() && !$structure->installments()->exists())

                                                    <form
                                                        method="POST"
                                                        action="{{ route('admin.fee-structures.destroy', $structure) }}"
                                                        class="d-inline"
                                                        onsubmit="return confirm('Are you sure you want to delete this fee structure?');"
                                                    >
                                                        @csrf
                                                        @method('DELETE')

                                                        <button
                                                            type="submit"
                                                            class="btn btn-sm btn-soft-danger"
                                                            title="Delete"
                                                        >
                                                            <i class="bx bx-trash"></i>
                                                        </button>
                                                    </form>

                                                @endif

                                            @endcan

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

                <div class="mt-3">
                    {{ $feeStructures->links() }}
                </div>

            @else

                {{-- Empty State --}}
                <div class="text-center py-5">

                    <div class="avatar-md mx-auto mb-4">
                        <span class="avatar-title rounded-circle bg-soft-primary text-primary font-size-24">
                            <i class="bx bx-wallet"></i>
                        </span>
                    </div>

                    <h5>No fee structures found</h5>

                    <p class="text-muted mb-3">
                        No fee structures have been configured for this school yet.
                    </p>

                    @can('fee-structures.manage')
                        <a
                            href="{{ route('admin.fee-structures.create') }}"
                            class="btn btn-primary"
                        >
                            <i class="bx bx-plus me-1"></i>
                            Create First Fee Structure
                        </a>
                    @endcan

                </div>

            @endif

        </div>
    </div>

</div>

@endsection