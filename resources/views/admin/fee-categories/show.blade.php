@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Title --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0">FEE CATEGORY DETAILS</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">Finance</li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.fee-categories.index') }}">
                                Fee Categories
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            {{ $feeCategory->name }}
                        </li>
                    </ol>
                </div>

            </div>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Error Message --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">

        {{-- Category Details --}}
        <div class="col-lg-4">

            <div class="card">

                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="card-title mb-1">
                            {{ $feeCategory->name }}
                        </h4>

                        <p class="card-title-desc mb-0">
                            Fee category information
                        </p>
                    </div>

                    @if($feeCategory->is_active)
                        <span class="badge bg-success">
                            Active
                        </span>
                    @else
                        <span class="badge bg-secondary">
                            Inactive
                        </span>
                    @endif
                </div>

                <div class="card-body">

                    <div class="mb-4">
                        <label class="text-muted d-block mb-1">
                            Category Name
                        </label>

                        <h5 class="mb-0">
                            {{ $feeCategory->name }}
                        </h5>
                    </div>

                    <div class="mb-4">
                        <label class="text-muted d-block mb-1">
                            Code
                        </label>

                        <h6 class="mb-0">
                            {{ $feeCategory->code ?: '—' }}
                        </h6>
                    </div>

                    <div class="mb-4">
                        <label class="text-muted d-block mb-1">
                            Description
                        </label>

                        <p class="mb-0">
                            {{ $feeCategory->description ?: 'No description provided.' }}
                        </p>
                    </div>

                    <div class="mb-4">
                        <label class="text-muted d-block mb-1">
                            Fee Structures
                        </label>

                        <h5 class="mb-0">
                            {{ $feeCategory->feeStructures->count() }}
                        </h5>
                    </div>

                    <div class="mb-4">
                        <label class="text-muted d-block mb-1">
                            Created
                        </label>

                        <span>
                            {{ optional($feeCategory->created_at)->format('d M Y, h:i A') }}
                        </span>
                    </div>

                    <div>
                        <label class="text-muted d-block mb-1">
                            Last Updated
                        </label>

                        <span>
                            {{ optional($feeCategory->updated_at)->format('d M Y, h:i A') }}
                        </span>
                    </div>

                </div>

                <div class="card-footer">

                    <div class="d-flex gap-2">

                        @can('fees.manage')
                            <a
                                href="{{ route('admin.fee-categories.edit', $feeCategory) }}"
                                class="btn btn-primary"
                            >
                                <i class="bx bx-edit-alt me-1"></i>
                                Edit
                            </a>
                        @endcan

                        <a
                            href="{{ route('admin.fee-categories.index') }}"
                            class="btn btn-light"
                        >
                            <i class="bx bx-arrow-back me-1"></i>
                            Back
                        </a>

                    </div>

                </div>

            </div>

        </div>

        {{-- Fee Structures --}}
        <div class="col-lg-8">

            <div class="card">

                <div class="card-header d-flex justify-content-between align-items-center">

                    <div>
                        <h4 class="card-title mb-1">
                            Fee Structures
                        </h4>

                        <p class="card-title-desc mb-0">
                            Fee structures configured under this category.
                        </p>
                    </div>

                    <span class="badge bg-soft-primary text-primary">
                        {{ $feeCategory->feeStructures->count() }}
                    </span>

                </div>

                <div class="card-body">

                    @if($feeCategory->feeStructures->count())

                        <div class="table-responsive">

                            <table class="table table-bordered table-hover align-middle mb-0">

                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Academic Year</th>
                                        <th>Class</th>
                                        <th>Term</th>
                                        <th>Amount</th>
                                        <th>Due Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @foreach($feeCategory->feeStructures as $structure)

                                        <tr>

                                            <td>
                                                {{ $loop->iteration }}
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
                                                {{ $structure->due_date
                                                    ? \Carbon\Carbon::parse($structure->due_date)->format('d M Y')
                                                    : '—'
                                                }}
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

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="text-center py-5">

                            <div class="avatar-md mx-auto mb-4">
                                <span class="avatar-title rounded-circle bg-soft-primary text-primary font-size-24">
                                    <i class="bx bx-wallet"></i>
                                </span>
                            </div>

                            <h5>No fee structures configured</h5>

                            <p class="text-muted mb-0">
                                No fee structures have been created for this fee category yet.
                            </p>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@endsection