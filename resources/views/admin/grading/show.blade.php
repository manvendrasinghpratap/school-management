@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0">Grade Details</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">

                        <li class="breadcrumb-item">
                            <a href="javascript:void(0);">
                                Examination
                            </a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.grading.index') }}">
                                Grading Setup
                            </a>
                        </li>

                        <li class="breadcrumb-item active">
                            Grade Details
                        </li>

                    </ol>
                </div>

            </div>
        </div>
    </div>

    {{-- Grade Details --}}
    <div class="row">

        <div class="col-lg-8">

            <div class="card">

                <div class="card-header d-flex align-items-center justify-content-between">

                    <div>
                        <h5 class="card-title mb-1">
                            {{ $grade->name }}
                        </h5>

                        <p class="text-muted mb-0">
                            Grading scale details
                        </p>
                    </div>

                    <span class="badge bg-secondary fs-6">
                        {{ $grade->code }}
                    </span>

                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-bordered align-middle mb-0">

                            <tbody>

                                <tr>
                                    <th style="width: 35%;">
                                        Grade Name
                                    </th>
                                    <td>
                                        {{ $grade->name }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        Grade Code
                                    </th>
                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ $grade->code }}
                                        </span>
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        Minimum Score
                                    </th>
                                    <td>
                                        {{ number_format((float) $grade->minimum_score, 2) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        Maximum Score
                                    </th>
                                    <td>
                                        {{ number_format((float) $grade->maximum_score, 2) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        Grade Point
                                    </th>
                                    <td>
                                        {{ $grade->grade_point !== null
                                            ? number_format((float) $grade->grade_point, 2)
                                            : '—' }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        Result
                                    </th>
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
                                </tr>

                                <tr>
                                    <th>
                                        Remark
                                    </th>
                                    <td>
                                        {{ $grade->remark ?: '—' }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        Created At
                                    </th>
                                    <td>
                                        {{ $grade->created_at?->format('d M Y, h:i A') ?? '—' }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        Last Updated
                                    </th>
                                    <td>
                                        {{ $grade->updated_at?->format('d M Y, h:i A') ?? '—' }}
                                    </td>
                                </tr>

                            </tbody>

                        </table>

                    </div>

                    {{-- Actions --}}
                    <div class="d-flex gap-2 mt-4">

                        <a href="{{ route('admin.grading.index') }}"
                           class="btn btn-light">
                            <i class="ri-arrow-left-line align-middle me-1"></i>
                            Back
                        </a>

                        @can('grading.manage')
                            <a href="{{ route('admin.grading.edit', $grade) }}"
                               class="btn btn-primary">
                                <i class="ri-edit-line align-middle me-1"></i>
                                Edit Grade
                            </a>
                        @endcan

                    </div>

                </div>

            </div>

        </div>

        {{-- Score Range Summary --}}
        <div class="col-lg-4">

            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">
                        Score Range
                    </h5>
                </div>

                <div class="card-body text-center">

                    <h2 class="mb-2">
                        {{ number_format((float) $grade->minimum_score, 2) }}
                        –
                        {{ number_format((float) $grade->maximum_score, 2) }}
                    </h2>

                    <p class="text-muted mb-4">
                        Valid score range
                    </p>

                    @if($grade->result === 'pass')

                        <div class="alert alert-success mb-0">
                            <strong>Pass</strong>
                            <br>
                            Students within this score range receive a passing result.
                        </div>

                    @else

                        <div class="alert alert-danger mb-0">
                            <strong>Fail</strong>
                            <br>
                            Students within this score range receive a failing result.
                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@endsection