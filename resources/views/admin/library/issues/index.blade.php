@extends('backend.layout.default')

@section('title', 'Book Issues')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row mb-3">
        <div class="col-12">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h4 class="mb-1">Book Issues</h4>

                    <p class="text-muted mb-0">
                        Manage issued, returned and overdue library books.
                    </p>
                </div>

                <div>
                    <a
                        href="{{ route('admin.library.issues.create') }}"
                        class="btn btn-primary"
                    >
                        <i class="bx bx-book-add me-1"></i>
                        Issue Book
                    </a>
                </div>

            </div>

        </div>
    </div>


    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show" role="alert">

            <i class="bx bx-check-circle me-1"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- Error Messages --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Search --}}
    <div class="card mb-3">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('admin.library.issues.index') }}"
            >

                <div class="row g-2 align-items-end">

                    <div class="col-md-10">

                        <label
                            for="search"
                            class="form-label"
                        >
                            Search Book or Member
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bx bx-search"></i>
                            </span>

                            <input
                                type="text"
                                name="search"
                                id="search"
                                class="form-control"
                                value="{{ request('search') }}"
                                placeholder="Search by book name, ISBN, member number or member name..."
                            >

                        </div>

                        <small class="text-muted">
                            Search works with book title, ISBN, member number,
                            student name, student number, admission number and staff name.
                        </small>

                    </div>


                    <div class="col-md-2">

                        <div class="d-flex gap-2">

                            <button
                                type="submit"
                                class="btn btn-primary w-100"
                            >
                                <i class="bx bx-search me-1"></i>
                                Search
                            </button>

                            @if(request()->filled('search'))

                                <a
                                    href="{{ route('admin.library.issues.index') }}"
                                    class="btn btn-outline-secondary"
                                    title="Clear Search"
                                >
                                    <i class="bx bx-x"></i>
                                </a>

                            @endif

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Search Result Information --}}
    @if(request()->filled('search'))

        <div class="mb-3">

            <span class="text-muted">
                Search results for:
            </span>

            <strong>
                "{{ request('search') }}"
            </strong>

            <span class="text-muted ms-1">
                ({{ $issues->total() }} result{{ $issues->total() === 1 ? '' : 's' }})
            </span>

        </div>

    @endif


    {{-- Transactions --}}
    <div class="card">

        <div class="card-header">

            <h5 class="card-title mb-0">
                Library Transactions
            </h5>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>Book</th>

                            <th>Copy</th>

                            <th>Member</th>

                            <th>Issued</th>

                            <th>Due</th>

                            <th>Status</th>

                            <th class="text-end">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($issues as $issue)

                            @php

                                $student = $issue->member?->student;

                                $staff = $issue->member?->staff;

                                $memberName = '';

                                if ($student) {

                                    $memberName = trim(
                                        $student->first_name . ' ' .
                                        ($student->middle_name ?? '') . ' ' .
                                        $student->last_name
                                    );

                                } elseif ($staff) {

                                    $memberName = trim(
                                        $staff->first_name . ' ' .
                                        ($staff->middle_name ?? '') . ' ' .
                                        $staff->last_name
                                    );

                                }

                            @endphp


                            <tr>

                                {{-- Book --}}
                                <td>

                                    <div class="fw-semibold">

                                        {{ $issue->book?->title
                                            ?? $issue->copy?->book?->title
                                            ?? '—'
                                        }}

                                    </div>

                                    @if($issue->book?->isbn)

                                        <small class="text-muted">
                                            ISBN: {{ $issue->book->isbn }}
                                        </small>

                                    @endif

                                </td>


                                {{-- Copy --}}
                                <td>

                                    @if($issue->copy)

                                        <div class="fw-semibold">
                                            {{ $issue->copy->accession_number ?? '—' }}
                                        </div>

                                        @if($issue->copy->barcode)

                                            <small class="text-muted">
                                                {{ $issue->copy->barcode }}
                                            </small>

                                        @endif

                                    @else

                                        —

                                    @endif

                                </td>


                                {{-- Member --}}
                                <td>

                                    @if($memberName)

                                        <div class="fw-semibold">
                                            {{ $memberName }}
                                        </div>

                                    @endif

                                    @if($issue->member?->member_number)

                                        <small class="text-muted">
                                            {{ $issue->member->member_number }}
                                        </small>

                                    @endif

                                </td>


                                {{-- Issued --}}
                                <td>

                                    @if($issue->issued_date)

                                        {{ $issue->issued_date->format('d M Y') }}

                                    @else

                                        —

                                    @endif

                                </td>


                                {{-- Due --}}
                                <td>

                                    @if($issue->due_at)

                                        {{ $issue->due_at->format('d M Y H:i') }}

                                    @elseif($issue->due_date)

                                        {{ $issue->due_date->format('d M Y') }}

                                    @else

                                        —

                                    @endif

                                </td>


                                {{-- Status --}}
                                <td>

                                    @php

                                        $statusClass = match($issue->status) {

                                            'issued' => 'bg-primary',

                                            'returned' => 'bg-success',

                                            'overdue' => 'bg-danger',

                                            default => 'bg-secondary',

                                        };

                                    @endphp

                                    <span class="badge {{ $statusClass }}">

                                        {{ ucfirst($issue->status) }}

                                    </span>

                                </td>


                                {{-- Actions --}}
                                <td class="text-end">

                                    @if(
                                        in_array(
                                            $issue->status,
                                            ['issued', 'overdue']
                                        )
                                    )

                                        {{-- Return --}}
                                        <form
                                            method="POST"
                                            action="{{ route(
                                                'admin.library.issues.return',
                                                $issue
                                            ) }}"
                                            class="d-inline"
                                        >

                                            @csrf

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-success"
                                                onclick="return confirm('Are you sure you want to return this book?')"
                                            >

                                                <i class="bx bx-log-in me-1"></i>

                                                Return

                                            </button>

                                        </form>


                                        {{-- Renew --}}
                                        <form
                                            method="POST"
                                            action="{{ route(
                                                'admin.library.issues.renew',
                                                $issue
                                            ) }}"
                                            class="d-inline ms-1"
                                        >

                                            @csrf

                                            <input
                                                type="hidden"
                                                name="due_at"
                                                value="{{ now()->addDays(14)->format('Y-m-d H:i:s') }}"
                                            >

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-outline-primary"
                                                onclick="return confirm('Renew this book for another 14 days?')"
                                            >

                                                <i class="bx bx-refresh me-1"></i>

                                                Renew

                                            </button>

                                        </form>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="7"
                                    class="text-center py-5"
                                >

                                    <div class="mb-2">

                                        <i
                                            class="bx bx-search-alt-2 text-muted"
                                            style="font-size: 40px;"
                                        ></i>

                                    </div>

                                    @if(request()->filled('search'))

                                        <h6 class="mb-1">
                                            No matching transactions found
                                        </h6>

                                        <p class="text-muted mb-3">
                                            No book issues matched
                                            "{{ request('search') }}".
                                        </p>

                                        <a
                                            href="{{ route('admin.library.issues.index') }}"
                                            class="btn btn-outline-primary btn-sm"
                                        >
                                            Clear Search
                                        </a>

                                    @else

                                        <h6 class="mb-1">
                                            No book issues found
                                        </h6>

                                        <p class="text-muted mb-0">
                                            No library transactions have been recorded yet.
                                        </p>

                                    @endif

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- Pagination --}}
        @if($issues->hasPages())

            <div class="card-footer">

                {{ $issues->links() }}

            </div>

        @endif

    </div>

</div>

@endsection