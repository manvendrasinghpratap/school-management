@extends('backend.layout.default')

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0">Department Details</h4>

                <div class="page-title-right">
                    <a href="{{ route('admin.departments.index') }}"
                       class="btn btn-light">
                        <i class="bx bx-arrow-back me-1"></i>
                        Back to Departments
                    </a>
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
                    data-bs-dismiss="alert">
            </button>
        </div>
    @endif

    <div class="row">

        {{-- Department Information --}}
        <div class="col-lg-8">

            <div class="card">

                <div class="card-header d-flex align-items-center justify-content-between">

                    <h5 class="card-title mb-0">
                        Department Information
                    </h5>

                    <div class="d-flex gap-1">

                        <a href="{{ route('admin.departments.edit', $department) }}"
                           class="btn btn-sm btn-warning">
                            <i class="bx bx-edit me-1"></i>
                            Edit
                        </a>

                        <form method="POST"
                              action="{{ route('admin.departments.destroy', $department) }}"
                              onsubmit="return confirm('Are you sure you want to delete this department?');">

                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="btn btn-sm btn-danger">
                                <i class="bx bx-trash me-1"></i>
                                Delete
                            </button>

                        </form>

                    </div>

                </div>

                <div class="card-body">

                    <div class="row">

                        {{-- Name --}}
                        <div class="col-md-6 mb-4">

                            <div class="text-muted small mb-1">
                                Department Name
                            </div>

                            <h5 class="mb-0">
                                {{ $department->name }}
                            </h5>

                        </div>

                        {{-- Code --}}
                        <div class="col-md-6 mb-4">

                            <div class="text-muted small mb-1">
                                Department Code
                            </div>

                            <h5 class="mb-0">
                                {{ $department->code ?? '—' }}
                            </h5>

                        </div>

                        {{-- Status --}}
                        <div class="col-md-6 mb-4">

                            <div class="text-muted small mb-1">
                                Status
                            </div>

                            @if($department->is_active)
                                <span class="badge bg-success">
                                    Active
                                </span>
                            @else
                                <span class="badge bg-secondary">
                                    Inactive
                                </span>
                            @endif

                        </div>

                        {{-- Department ID --}}
                        <div class="col-md-6 mb-4">

                            <div class="text-muted small mb-1">
                                Department ID
                            </div>

                            <strong>
                                {{ $department->id }}
                            </strong>

                        </div>

                        {{-- Description --}}
                        <div class="col-12 mb-4">

                            <div class="text-muted small mb-1">
                                Description
                            </div>

                            <div>
                                {{ $department->description ?? 'No description provided.' }}
                            </div>

                        </div>

                        {{-- Created --}}
                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Created
                            </div>

                            <div>
                                {{ $department->created_at?->format('Y-m-d H:i:s') ?? '—' }}
                            </div>

                        </div>

                        {{-- Updated --}}
                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Last Updated
                            </div>

                            <div>
                                {{ $department->updated_at?->format('Y-m-d H:i:s') ?? '—' }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- Classes --}}
        <div class="col-lg-4">

            <div class="card">

                <div class="card-header">

                    <h5 class="card-title mb-0">
                        Assigned Classes
                    </h5>

                </div>

                <div class="card-body">

                    @if($department->classes->count())

                        <div class="list-group">

                            @foreach($department->classes as $class)

                                <div class="list-group-item">

                                    <div class="d-flex justify-content-between align-items-start">

                                        <div>

                                            <h6 class="mb-1">
                                                {{ $class->name }}
                                            </h6>

                                            @if($class->code)
                                                <small class="text-muted">
                                                    {{ $class->code }}
                                                </small>
                                            @endif

                                        </div>

                                        @if($class->is_active)
                                            <span class="badge bg-success">
                                                Active
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                Inactive
                                            </span>
                                        @endif

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    @else

                        <div class="text-center text-muted py-4">

                            <i class="bx bx-book-open fs-1"></i>

                            <p class="mb-0 mt-2">
                                No classes are assigned to this department.
                            </p>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>
@endsection