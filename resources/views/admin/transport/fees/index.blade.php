@extends('backend.layout.default')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Transport Fees</h4>

        @can('transport.fees.manage')
            <a href="{{ route('admin.transport.fees.create') }}" class="btn btn-primary">
                Add Transport Fee
            </a>
        @endcan
    </div>

    @include('admin.transport._flash')

    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-6">
            <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Search student or route"
                value="{{ request('search') }}"
            >
        </div>

        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">All Statuses</option>
                @foreach(['pending' => 'Pending', 'invoiced' => 'Invoiced', 'paid' => 'Paid', 'waived' => 'Waived'] as $value => $label)
                    <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <button class="btn btn-outline-primary">Filter</button>
            <a href="{{ route('admin.transport.fees.index') }}" class="btn btn-light">Reset</a>
        </div>
    </form>

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Route</th>
                        <th>Fee Month</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Invoice</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($fees as $fee)
                        @php
                            $student = $fee->routeStudent?->student;
                            $route = $fee->routeStudent?->route;
                        @endphp

                        <tr>
                            <td>
                                {{ trim(($student->first_name ?? '') . ' ' . ($student->middle_name ?? '') . ' ' . ($student->last_name ?? '')) }}
                                @if($student?->student_number)
                                    <div class="text-muted small">{{ $student->student_number }}</div>
                                @endif
                            </td>
                            <td>
                                {{ $route?->name ?? '—' }}
                                @if($route?->code)
                                    <div class="text-muted small">{{ $route->code }}</div>
                                @endif
                            </td>
                            <td>{{ $fee->fee_month?->format('d M Y') }}</td>
                            <td>{{ number_format((float) $fee->amount, 2) }}</td>
                            <td>
                                @php
                                    $badge = match($fee->status) {
                                        'paid' => 'success',
                                        'waived' => 'secondary',
                                        'invoiced' => 'info',
                                        default => 'warning',
                                    };
                                @endphp
                                <span class="badge bg-{{ $badge }}">{{ ucfirst($fee->status) }}</span>
                            </td>
                            <td>{{ $fee->invoice_id ?: '—' }}</td>
                            <td class="text-nowrap">

    @can('transport.fees.view')
        <a
            href="{{ route('admin.transport.fees.show', $fee) }}"
            class="btn btn-sm btn-outline-info"
        >
            View
        </a>
    @endcan

    @can('transport.fees.manage')
        <a
            href="{{ route('admin.transport.fees.edit', $fee) }}"
            class="btn btn-sm btn-outline-primary"
        >
            Edit
        </a>

        <form
            method="POST"
            action="{{ route('admin.transport.fees.destroy', $fee) }}"
            class="d-inline"
            onsubmit="return confirm('Delete this transport fee?')"
        >
            @csrf
            @method('DELETE')

            <button class="btn btn-sm btn-outline-danger">
                Delete
            </button>
        </form>
    @endcan

</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                No transport fees found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($fees->hasPages())
            <div class="card-footer">
                {{ $fees->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
