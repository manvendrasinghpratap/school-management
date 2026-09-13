@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Grading Setup</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript:void(0);">Examination</a>
                        </li>
                        <li class="breadcrumb-item active">Grading Setup</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Please correct the following:</strong>

            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Grading Card --}}
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div>
                <h5 class="card-title mb-1">Grading Scale</h5>
                <p class="text-muted mb-0">
                    Configure the school's score ranges, grade points and result status.
                </p>
            </div>

            @can('grading.manage')
                <a href="{{ route('admin.grading.create') }}"
                   class="btn btn-primary">
                    <i class="ri-add-line align-middle me-1"></i>
                    Add Grade
                </a>
            @endcan
        </div>

        <div class="card-body">

            @if($grades->count())

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0">

                        <thead class="table-light">
                            <tr>
                                <th style="width: 60px;">#</th>
                                <th>Grade</th>
                                <th>Code</th>
                                <th>Minimum Score</th>
                                <th>Maximum Score</th>
                                <th>Grade Point</th>
                                <th>Result</th>
                                <th>Remark</th>
                                <th style="width: 180px;">Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($grades as $grade)
                                <tr>

                                    <td>
                                        {{ $grades->firstItem() + $loop->index }}
                                    </td>

                                    <td>
                                        <strong>{{ $grade->name }}</strong>
                                    </td>

                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ $grade->code }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ number_format((float) $grade->minimum_score, 2) }}
                                    </td>

                                    <td>
                                        {{ number_format((float) $grade->maximum_score, 2) }}
                                    </td>

                                    <td>
                                        {{ $grade->grade_point !== null
                                            ? number_format((float) $grade->grade_point, 2)
                                            : '—' }}
                                    </td>

                                    <td>
                                        @if($grade->result === 'pass')
                                            <span class="badge bg-success">
                                                Pass
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                Fail
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        {{ $grade->remark ?: '—' }}
                                    </td>

                                    <td>
                                        <div class="d-flex gap-1">

                                            @can('grading.view')
                                                <a href="{{ route('admin.grading.show', $grade) }}"
                                                   class="btn btn-sm btn-soft-info"
                                                   title="View">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                            @endcan

                                            @can('grading.manage')
                                                <a href="{{ route('admin.grading.edit', $grade) }}"
                                                   class="btn btn-sm btn-soft-primary"
                                                   title="Edit">
                                                    <i class="ri-edit-line"></i>
                                                </a>

                                                <form action="{{ route('admin.grading.destroy', $grade) }}"
                                                      method="POST"
                                                      class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this grade?');">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                            class="btn btn-sm btn-soft-danger"
                                                            title="Delete">
                                                        <i class="ri-delete-bin-line"></i>
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
                <div class="mt-3">
                    {{ $grades->links() }}
                </div>

            @else

                <div class="text-center py-5">

                    <div class="mb-3">
                        <i class="ri-bar-chart-2-line display-5 text-muted"></i>
                    </div>

                    <h5>No grading scale configured</h5>

                    <p class="text-muted mb-3">
                        No grades have been configured for this school yet.
                    </p>

                    @can('grading.manage')
                        <a href="{{ route('admin.grading.create') }}"
                           class="btn btn-primary">
                            <i class="ri-add-line align-middle me-1"></i>
                            Create First Grade
                        </a>
                    @endcan

                </div>

            @endif

        </div>
    </div>

</div>

@endsection