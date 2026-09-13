@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Title --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0">Report Cards</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript:void(0);">Examinations</a>
                        </li>

                        <li class="breadcrumb-item active">
                            Report Cards
                        </li>
                    </ol>
                </div>

            </div>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ri-checkbox-circle-line me-1"></i>
            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close">
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="ri-error-warning-line me-1"></i>
            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close">
            </button>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="ri-information-line me-1"></i>
            {{ session('info') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close">
            </button>
        </div>
    @endif


    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">

            <strong>Please correct the following:</strong>

            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close">
            </button>

        </div>
    @endif


    {{-- Filters --}}
    <div class="card">

        <div class="card-header">
            <h5 class="card-title mb-0">
                Filter Report Cards
            </h5>
        </div>

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('admin.report-cards.index') }}"
            >

                <div class="row g-3 align-items-end">

                    {{-- Examination --}}
                    <div class="col-md-4">

                        <label
                            for="examination_id"
                            class="form-label"
                        >
                            Examination
                        </label>

                        <select
                            name="examination_id"
                            id="examination_id"
                            class="form-select w-100"
                        >

                            <option value="">
                                All Examinations
                            </option>

                            @foreach($examinations as $examination)

                                <option
                                    value="{{ $examination->id }}"
                                    @selected(
                                        (string) request('examination_id') ===
                                        (string) $examination->id
                                    )
                                >
                                    {{ $examination->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Student --}}
                    <div class="col-md-4">

                        <label
                            for="student_id"
                            class="form-label"
                        >
                            Student
                        </label>

                        <select
                            name="student_id"
                            id="student_id"
                            class="form-select w-100"
                        >

                            <option value="">
                                All Students
                            </option>

                            @foreach($students as $student)

                                <option
                                    value="{{ $student->id }}"
                                    @selected(
                                        (string) request('student_id') ===
                                        (string) $student->id
                                    )
                                >
                                    {{ trim(
                                        $student->first_name . ' ' .
                                        ($student->middle_name ?? '') . ' ' .
                                        $student->last_name
                                    ) }}
                                    — {{ $student->student_number }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Status --}}
                    <div class="col-md-2">

                        <label
                            for="status"
                            class="form-label"
                        >
                            Status
                        </label>

                        <select
                            name="status"
                            id="status"
                            class="form-select w-100"
                            style="min-width: 160px;"
                        >

                            <option value="">
                                All Statuses
                            </option>

                            <option
                                value="generated"
                                @selected(request('status') === 'generated')
                            >
                                Generated
                            </option>

                            <option
                                value="published"
                                @selected(request('status') === 'published')
                            >
                                Published
                            </option>

                        </select>

                    </div>


                    {{-- Filter Buttons --}}
                    <div class="col-md-2">

                        <div class="d-flex gap-2">

                            <button
                                type="submit"
                                class="btn btn-primary flex-grow-1"
                            >
                                <i class="ri-filter-2-line me-1"></i>
                                Filter
                            </button>

                            <a
                                href="{{ route('admin.report-cards.index') }}"
                                class="btn btn-light"
                                title="Reset Filters"
                            >
                                <i class="ri-refresh-line"></i>
                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Report Card Records --}}
    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">

            <h5 class="card-title mb-0">
                Report Card Records
            </h5>

            <span class="text-muted">
                {{ $reportCards->total() }} record(s)
            </span>

        </div>


        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th style="width: 50px;">
                                #
                            </th>

                            <th>
                                Student
                            </th>

                            <th>
                                Student No.
                            </th>

                            <th>
                                Examination
                            </th>

                            <th>
                                Average
                            </th>

                            <th>
                                Grade
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Generated By
                            </th>

                            <th>
                                Published At
                            </th>

                            <th style="width: 170px;">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($reportCards as $reportCard)

                            @php
                                $result = \App\Models\Result::query()
                                    ->where('student_id', $reportCard->student_id)
                                    ->where('examination_id', $reportCard->examination_id)
                                    ->first();
                            @endphp

                            <tr>

                                {{-- Number --}}
                                <td>
                                    {{ $reportCards->firstItem() + $loop->index }}
                                </td>


                                {{-- Student --}}
                                <td>

                                    @if($reportCard->student)

                                        <strong>
                                            {{ trim(
                                                $reportCard->student->first_name . ' ' .
                                                ($reportCard->student->middle_name ?? '') . ' ' .
                                                $reportCard->student->last_name
                                            ) }}
                                        </strong>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- Student Number --}}
                                <td>

                                    {{ $reportCard->student->student_number ?? '—' }}

                                </td>


                                {{-- Examination --}}
                                <td>

                                    {{ $reportCard->examination->name ?? '—' }}

                                </td>


                                {{-- Average --}}
                                <td>

                                    @if($result)
                                        {{ number_format((float) $result->average, 2) }}
                                    @else
                                        —
                                    @endif

                                </td>


                                {{-- Grade --}}
                                <td>

                                    {{ $result->grade ?? '—' }}

                                </td>


                                {{-- Status --}}
                                <td>

                                    @if($reportCard->status === 'published')

                                        <span class="badge bg-success">
                                            <i class="ri-checkbox-circle-line me-1"></i>
                                            Published
                                        </span>

                                    @else

                                        <span class="badge bg-warning text-dark">
                                            <i class="ri-file-line me-1"></i>
                                            Generated
                                        </span>

                                    @endif

                                </td>


                                {{-- Generated By --}}
                                <td>

                                    {{ $reportCard->generatedBy->name ?? '—' }}

                                </td>


                                {{-- Published At --}}
                                <td>

                                    @if($reportCard->published_at)

                                        {{ $reportCard->published_at->format('d M Y H:i') }}

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- Actions --}}
                                <td>

                                    <div class="d-flex gap-1 flex-wrap">

                                        {{-- View --}}
                                        @can('report-cards.view')

                                            <a
                                                href="{{ route(
                                                    'admin.report-cards.show',
                                                    $reportCard
                                                ) }}"
                                                class="btn btn-sm btn-info"
                                                title="View Report Card"
                                            >
                                                <i class="ri-eye-line"></i>
                                            </a>

                                        @endcan


                                        {{-- PDF --}}
                                        @can('report-cards.view')

                                            @if($reportCard->file_path)

                                                <a
                                                    href="{{ route(
                                                        'admin.report-cards.pdf',
                                                        $reportCard
                                                    ) }}"
                                                    class="btn btn-sm btn-danger"
                                                    target="_blank"
                                                    title="View PDF"
                                                >
                                                    <i class="ri-file-pdf-line"></i>
                                                </a>

                                            @endif

                                        @endcan


                                        {{-- Publish --}}
                                        @can('report-cards.generate')

                                            @if($reportCard->status === 'generated')

                                                <form
                                                    method="POST"
                                                    action="{{ route(
                                                        'admin.report-cards.publish',
                                                        $reportCard
                                                    ) }}"
                                                    class="d-inline"
                                                >

                                                    @csrf

                                                    <button
                                                        type="submit"
                                                        class="btn btn-sm btn-success"
                                                        title="Publish Report Card"
                                                        onclick="return confirm('Are you sure you want to publish this report card?');"
                                                    >
                                                        <i class="ri-send-plane-line"></i>
                                                    </button>

                                                </form>

                                            @endif

                                        @endcan

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="10"
                                    class="text-center py-5"
                                >

                                    <div class="text-muted">

                                        <i
                                            class="ri-file-list-3-line"
                                            style="font-size: 36px;"
                                        ></i>

                                        <div class="mt-2">
                                            No report cards found.
                                        </div>

                                        @if(
                                            request()->filled('examination_id') ||
                                            request()->filled('student_id') ||
                                            request()->filled('status')
                                        )

                                            <div class="mt-2">

                                                <a
                                                    href="{{ route('admin.report-cards.index') }}"
                                                    class="btn btn-sm btn-light"
                                                >
                                                    Clear Filters
                                                </a>

                                            </div>

                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- Pagination --}}
            @if($reportCards->hasPages())

                <div class="mt-3">

                    {{ $reportCards->links() }}

                </div>

            @endif

        </div>

    </div>

</div>

@endsection