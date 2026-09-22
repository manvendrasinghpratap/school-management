@extends('backend.layout.default')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Hostel Fees</h4>
        @can('hostel.fees.manage')
            <a href="{{ route('admin.hostel.fees.create') }}" class="btn btn-primary">Add Hostel Fee</a>
        @endcan
    </div>
    @include('admin.hostel._flash')

    <form class="row g-2 mb-3">
        <div class="col-md-6"><input name="search" class="form-control" value="{{ request('search') }}" placeholder="Search student"></div>
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">All Statuses</option>
                @foreach(['pending','invoiced','paid','waived'] as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-auto"><button class="btn btn-outline-primary">Filter</button><a href="{{ route('admin.hostel.fees.index') }}" class="btn btn-light">Reset</a></div>
    </form>

    <div class="card"><div class="card-body">
        <table class="table align-middle">
            <thead><tr><th>Student</th><th>Hostel</th><th>Room</th><th>Fee Month</th><th>Amount</th><th>Status</th><th>Invoice</th><th>Action</th></tr></thead>
            <tbody>
            @forelse($fees as $fee)
                <tr>
                    <td>{{ trim(($fee->allocation->student->first_name ?? '').' '.($fee->allocation->student->last_name ?? '')) }}<div class="small text-muted">{{ $fee->allocation->student->student_number ?? '' }}</div></td>
                    <td>{{ $fee->allocation->hostel->name ?? '—' }}</td>
                    <td>{{ $fee->allocation->room->room_number ?? '—' }}</td>
                    <td>{{ $fee->fee_month?->format('d M Y') }}</td>
                    <td>{{ number_format((float)$fee->amount,2) }}</td>
                    <td>{{ ucfirst($fee->status) }}</td>
                    <td>{{ $fee->invoice_id ? '#'.$fee->invoice_id : '—' }}</td>
                    <td class="text-nowrap">
                        <a href="{{ route('admin.hostel.fees.show', $fee) }}" class="btn btn-sm btn-outline-info">View</a>
                        @can('hostel.fees.manage')
                            <a href="{{ route('admin.hostel.fees.edit', $fee) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form method="POST" action="{{ route('admin.hostel.fees.destroy', $fee) }}" class="d-inline" onsubmit="return confirm('Delete this hostel fee?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center text-muted">No hostel fees found.</td></tr>
            @endforelse
            </tbody>
        </table>
        {{ $fees->links() }}
    </div></div>
</div>
@endsection
