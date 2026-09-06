@extends('backend.layout.default')

@section('title', 'Class Details')

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-12">

            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0 font-size-18">
                    Class Details
                </h4>

                <div class="page-title-right">
                    <a href="{{ route('admin.classes.index') }}"
                       class="btn btn-light me-1">
                        <i class="bx bx-arrow-back me-1"></i>
                        Back
                    </a>

                    <a href="{{ route('admin.classes.edit', $class) }}"
                       class="btn btn-primary">
                        <i class="bx bx-edit me-1"></i>
                        Edit Class
                    </a>
                </div>

            </div>

        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    @endif

    <div class="row">

        <div class="col-lg-8">

            <div class="card">

                <div class="card-body">

                    <div class="d-flex align-items-center mb-4">

                        <div class="avatar-sm me-3">
                            <span class="avatar-title rounded-circle bg-primary-subtle text-primary font-size-20">
                                <i class="bx bx-book-open"></i>
                            </span>
                        </div>

                        <div>
                            <h4 class="mb-1">
                                {{ $class->name }}
                            </h4>

                            @if($class->code)
                                <p class="text-muted mb-0">
                                    {{ $class->code }}
                                </p>
                            @endif
                        </div>

                    </div>

                    <div class="table-responsive">

                        <table class="table table-borderless mb-0">

                            <tbody>

                                <tr>
                                    <th style="width: 35%;">
                                        Class Name
                                    </th>

                                    <td>
                                        {{ $class->name }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        Class Code
                                    </th>

                                    <td>
                                        {{ $class->code ?: '—' }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        Department
                                    </th>

                                    <td>
                                        {{ $class->department?->name ?: '—' }}

                                        @if($class->department?->code)
                                            <span class="text-muted">
                                                ({{ $class->department->code }})
                                            </span>
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        Level
                                    </th>

                                    <td>
                                        {{ $class->level?->name ?: '—' }}

                                        @if($class->level?->code)
                                            <span class="text-muted">
                                                ({{ $class->level->code }})
                                            </span>
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        Status
                                    </th>

                                    <td>
                                        @if($class->is_active)
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

                                <tr>
                                    <th>
                                        Description
                                    </th>

                                    <td>
                                        @if($class->description)
                                            {!! nl2br(e($class->description)) !!}
                                        @else
                                            <span class="text-muted">
                                                No description provided.
                                            </span>
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        Created
                                    </th>

                                    <td>
                                        {{ $class->created_at?->format('d M Y, h:i A') }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        Last Updated
                                    </th>

                                    <td>
                                        {{ $class->updated_at?->format('d M Y, h:i A') }}
                                    </td>
                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-lg-4">

            <div class="card">

                <div class="card-body">

                    <h4 class="card-title mb-4">
                        Quick Actions
                    </h4>

                    <div class="d-grid gap-2">

                        <a href="{{ route('admin.classes.edit', $class) }}"
                           class="btn btn-primary">
                            <i class="bx bx-edit me-1"></i>
                            Edit Class
                        </a>

                        <a href="{{ route('admin.classes.index') }}"
                           class="btn btn-light">
                            <i class="bx bx-list-ul me-1"></i>
                            All Classes
                        </a>

                        <form method="POST"
                              action="{{ route('admin.classes.destroy', $class) }}"
                              onsubmit="return confirm('Are you sure you want to delete this class?');">

                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="btn btn-danger w-100">
                                <i class="bx bx-trash me-1"></i>
                                Delete Class
                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection