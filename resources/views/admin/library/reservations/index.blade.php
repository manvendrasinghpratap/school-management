@extends('backend.layout.default')

@section('title', 'Library Reservations')

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <div>
                    <h4 class="mb-sm-0 font-size-18">Library Reservations</h4>
                    <p class="text-muted mb-0 mt-1">
                        Manage reserved books and reservation status.
                    </p>
                </div>

                @can('library.reservations.manage')
                    <a href="{{ route('admin.library.reservations.create') }}"
                       class="btn btn-primary">
                        <i class="bx bx-bookmark-plus me-1"></i>
                        New Reservation
                    </a>
                @endcan
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

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

    <div class="card">
        <div class="card-body">

            <form method="GET" class="row g-2 mb-3">
                <div class="col-md-6">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           class="form-control"
                           placeholder="Search book, ISBN, member number or member name">
                </div>

                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        @foreach(['active','fulfilled','cancelled','expired'] as $status)
                            <option value="{{ $status }}"
                                @selected(request('status') === $status)>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 d-flex gap-2">
                    <button class="btn btn-outline-primary flex-fill">
                        <i class="bx bx-search me-1"></i>
                        Filter
                    </button>

                    <a href="{{ route('admin.library.reservations.index') }}"
                       class="btn btn-light">
                        Reset
                    </a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table align-middle table-nowrap mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Book</th>
                            <th>Member</th>
                            <th>Reserved</th>
                            <th>Expires</th>
                            <th>Status</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                    @forelse($reservations as $reservation)
                        @php
                            $student = $reservation->member?->student;
                            $staff = $reservation->member?->staff;

                            $memberName = $student
                                ? trim(
                                    $student->first_name . ' ' .
                                    ($student->middle_name ?? '') . ' ' .
                                    $student->last_name
                                )
                                : ($staff
                                    ? trim(
                                        ($staff->first_name ?? '') . ' ' .
                                        ($staff->last_name ?? '')
                                    )
                                    : '—');
                        @endphp

                        <tr>
                            <td>
                                <div class="fw-semibold">
                                    {{ $reservation->book?->title ?? '—' }}
                                </div>
                                <small class="text-muted">
                                    ISBN: {{ $reservation->book?->isbn ?? '—' }}
                                </small>
                            </td>

                            <td>
                                <div>{{ $memberName }}</div>
                                <small class="text-muted">
                                    {{ $reservation->member?->member_number ?? '—' }}
                                </small>
                            </td>

                            <td>
                                {{ $reservation->reserved_at?->format('d M Y H:i') ?? '—' }}
                            </td>

                            <td>
                                {{ $reservation->expires_at?->format('d M Y H:i') ?? 'No expiry' }}
                            </td>

                            <td>
                                @php
                                    $badge = match($reservation->status) {
                                        'active' => 'bg-primary',
                                        'fulfilled' => 'bg-success',
                                        'cancelled' => 'bg-secondary',
                                        'expired' => 'bg-danger',
                                        default => 'bg-light text-dark',
                                    };
                                @endphp

                                <span class="badge {{ $badge }}">
                                    {{ ucfirst($reservation->status) }}
                                </span>
                            </td>

                            <td class="text-end">
                                @if($reservation->status === 'active')
                                    @can('library.reservations.manage')
                                        <form method="POST"
                                              action="{{ route('admin.library.reservations.fulfill', $reservation) }}"
                                              class="d-inline"
                                              onsubmit="return confirm('Mark this reservation as fulfilled?');">
                                            @csrf
                                            <button class="btn btn-sm btn-success">
                                                <i class="bx bx-check me-1"></i>
                                                Fulfill
                                            </button>
                                        </form>

                                        <form method="POST"
                                              action="{{ route('admin.library.reservations.cancel', $reservation) }}"
                                              class="d-inline ms-1"
                                              onsubmit="return confirm('Cancel this reservation?');">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-danger">
                                                <i class="bx bx-x me-1"></i>
                                                Cancel
                                            </button>
                                        </form>
                                    @endcan
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="bx bx-bookmark font-size-32 text-muted d-block mb-2"></i>
                                <div class="text-muted">No reservations found.</div>

                                @can('library.reservations.manage')
                                    <a href="{{ route('admin.library.reservations.create') }}"
                                       class="btn btn-primary btn-sm mt-3">
                                        Create Reservation
                                    </a>
                                @endcan
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $reservations->links() }}
            </div>

        </div>
    </div>
</div>
@endsection
