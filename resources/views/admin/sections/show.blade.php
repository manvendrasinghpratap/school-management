@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0">
                    Section Details
                </h4>

                <div class="page-title-right d-flex gap-2">

                    <a href="{{ route('admin.sections.index') }}"
                       class="btn btn-light">
                        <i class="bx bx-arrow-back me-1"></i>
                        Back
                    </a>

                    <a href="{{ route('admin.sections.edit', $section) }}"
                       class="btn btn-primary">
                        <i class="bx bx-edit me-1"></i>
                        Edit
                    </a>

                </div>

            </div>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show"
             role="alert">

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>
    @endif

    <div class="row">

        {{-- Main Information --}}
        <div class="col-lg-8">

            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">
                        Section Information
                    </h5>
                </div>

                <div class="card-body">

                    <div class="row">

                        {{-- Section Name --}}
                        <div class="col-md-6 mb-4">

                            <div class="text-muted small mb-1">
                                Section Name
                            </div>

                            <h5 class="mb-0">
                                {{ $section->name }}
                            </h5>

                        </div>

                        {{-- Code --}}
                        <div class="col-md-6 mb-4">

                            <div class="text-muted small mb-1">
                                Section Code
                            </div>

                            <h5 class="mb-0">
                                {{ $section->code ?: '—' }}
                            </h5>

                        </div>

                        {{-- Class --}}
                        <div class="col-md-6 mb-4">

                            <div class="text-muted small mb-1">
                                Class
                            </div>

                            @if($section->class)

                                <h5 class="mb-0">
                                    {{ $section->class->name }}
                                </h5>

                                @if($section->class->code)
                                    <div class="text-muted">
                                        {{ $section->class->code }}
                                    </div>
                                @endif

                            @else

                                <span class="text-muted">
                                    —
                                </span>

                            @endif

                        </div>

                        {{-- Capacity --}}
                        <div class="col-md-6 mb-4">

                            <div class="text-muted small mb-1">
                                Capacity
                            </div>

                            <h5 class="mb-0">
                                {{ $section->capacity ?? 'Unlimited' }}
                            </h5>

                        </div>

                        {{-- Status --}}
                        <div class="col-md-6 mb-4">

                            <div class="text-muted small mb-1">
                                Status
                            </div>

                            @if($section->is_active)

                                <span class="badge bg-success-subtle text-success">
                                    Active
                                </span>

                            @else

                                <span class="badge bg-danger-subtle text-danger">
                                    Inactive
                                </span>

                            @endif

                        </div>

                        {{-- Section ID --}}
                        <div class="col-md-6 mb-4">

                            <div class="text-muted small mb-1">
                                Section ID
                            </div>

                            <h5 class="mb-0">
                                #{{ $section->id }}
                            </h5>

                        </div>

                        {{-- Created --}}
                        <div class="col-md-6 mb-4">

                            <div class="text-muted small mb-1">
                                Created
                            </div>

                            <span>
                                {{ $section->created_at?->format('d M Y, h:i A') ?? '—' }}
                            </span>

                        </div>

                        {{-- Updated --}}
                        <div class="col-md-6 mb-4">

                            <div class="text-muted small mb-1">
                                Last Updated
                            </div>

                            <span>
                                {{ $section->updated_at?->format('d M Y, h:i A') ?? '—' }}
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- Quick Actions --}}
        <div class="col-lg-4">

            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">
                        Quick Actions
                    </h5>
                </div>

                <div class="card-body">

                    <div class="d-grid gap-2">

                        <a href="{{ route('admin.sections.edit', $section) }}"
                           class="btn btn-primary">
                            <i class="bx bx-edit me-1"></i>
                            Edit Section
                        </a>

                        @if($section->class)

                            <a href="{{ route('admin.classes.show', $section->class) }}"
                               class="btn btn-info">
                                <i class="bx bx-layer me-1"></i>
                                View Class
                            </a>

                        @endif

                        <form method="POST"
                              action="{{ route('admin.sections.destroy', $section) }}"
                              onsubmit="return confirm('Are you sure you want to delete this section?');">

                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="btn btn-danger w-100">
                                <i class="bx bx-trash me-1"></i>
                                Delete Section
                            </button>

                        </form>

                    </div>

                </div>

            </div>

            {{-- Parent Class --}}
            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">
                        Parent Class
                    </h5>
                </div>

                <div class="card-body">

                    @if($section->class)

                        <h5>
                            {{ $section->class->name }}
                        </h5>

                        @if($section->class->code)
                            <p class="text-muted mb-2">
                                Code: {{ $section->class->code }}
                            </p>
                        @endif

                        <p class="text-muted mb-0">
                            This section belongs to the selected class.
                        </p>

                    @else

                        <p class="text-muted mb-0">
                            No parent class found.
                        </p>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@endsection 