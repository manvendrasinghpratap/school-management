@extends('backend.layout.default')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Hostel Allocations</h4>
        @can('hostel.allocations.manage')
            <a href="{{ route('admin.hostel.allocations.create') }}" class="btn btn-primary">Allocate Student</a>
        @endcan
    </div>
    @include('admin.hostel._flash')

    <form class="row g-2 mb-3">
        <div class="col-md-6">
            <input name="search" class="form-control" value="{{ request('search') }}" placeholder="Search student">
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">All Statuses</option>
                @foreach(['allocated','checked_in','checked_out','cancelled'] as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst(str_replace('_',' ',$status)) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-auto"><button class="btn btn-outline-primary">Filter</button><a href="{{ route('admin.hostel.allocations.index') }}" class="btn btn-light">Reset</a></div>
    </form>

    <div class="card"><div class="card-body">
        <table class="table align-middle">
            <thead><tr><th>Student</th><th>Hostel</th><th>Room</th><th>Bed</th><th>Start</th><th>Status</th><th>Action</th></tr></thead>
            <tbody>
            @forelse($allocations as $allocation)
                <tr>
                    <td>{{ trim(($allocation->student->first_name ?? '').' '.($allocation->student->last_name ?? '')) }}<div class="small text-muted">{{ $allocation->student->student_number ?? '' }}</div></td>
                    <td>{{ $allocation->hostel->name ?? '—' }}</td>
                    <td>{{ $allocation->room->room_number ?? '—' }}</td>
                    <td>{{ $allocation->bed->bed_number ?? '—' }}</td>
                    <td>{{ $allocation->start_date?->format('d M Y') }}</td>
                    <td>{{ ucfirst(str_replace('_',' ',$allocation->status)) }}</td>
                    <td class="text-nowrap">
                        <a href="{{ route('admin.hostel.allocations.show', $allocation) }}" class="btn btn-sm btn-outline-info">View</a>
                        @if(in_array($allocation->status, ['allocated','checked_in'], true))
                            @can('hostel.allocations.manage')
                                <form method="POST" action="{{ route('admin.hostel.allocations.checkout', $allocation) }}" class="d-inline" onsubmit="return confirm('Check out this student?')">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-warning">Checkout</button>
                                </form>
                            @endcan
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted">No allocations found.</td></tr>
            @endforelse
            </tbody>
        </table>
        {{ $allocations->links() }}
    </div></div>
</div>
@endsection
