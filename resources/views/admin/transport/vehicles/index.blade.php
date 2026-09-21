@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <h4>Transport Vehicles</h4>

        <a href="{{ route('admin.transport.vehicles.create') }}"
           class="btn btn-primary">
            <i class="bx bx-plus me-1"></i>
            Add Vehicle
        </a>

    </div>

    @include('admin.transport._flash')

    <form class="row g-2 mb-3" method="GET">

        <div class="col-md-5">
            <input
                name="search"
                value="{{ request('search') }}"
                class="form-control"
                placeholder="Search vehicle number, registration or type"
            >
        </div>

        <div class="col-md-3">

            <select name="status" class="form-select">

                <option value="">
                    All Statuses
                </option>

                @foreach(['active', 'inactive', 'maintenance'] as $s)

                    <option
                        value="{{ $s }}"
                        @selected(request('status') === $s)
                    >
                        {{ ucfirst($s) }}
                    </option>

                @endforeach

            </select>

        </div>

        <div class="col-auto">

            <button type="submit"
                    class="btn btn-outline-primary">
                <i class="bx bx-search me-1"></i>
                Filter
            </button>

        </div>

        @if(request('search') || request('status'))

            <div class="col-auto">

                <a href="{{ route('admin.transport.vehicles.index') }}"
                   class="btn btn-outline-secondary">
                    Clear
                </a>

            </div>

        @endif

    </form>

    <div class="card">

        <div class="card-body table-responsive">

            <table class="table align-middle">

                <thead>

                    <tr>
                        <th>Vehicle Number</th>
                        <th>Registration</th>
                        <th>Type</th>
                        <th>Capacity</th>
                        <th>Driver</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($vehicles as $v)

                        <tr>

                            <td>
                                <strong>
                                    {{ $v->vehicle_number }}
                                </strong>
                            </td>

                            <td>
                                {{ $v->registration_number ?: '—' }}
                            </td>

                            <td>
                                {{ $v->vehicle_type ?: '—' }}
                            </td>

                            <td>
                                {{ $v->capacity ?: '—' }}
                            </td>

                            <td>
                                {{ $v->driver_name ?: '—' }}
                            </td>

                            <td>
                                {{ $v->driver_phone ?: '—' }}
                            </td>

                            <td>

                                @if($v->status === 'active')

                                    <span class="badge bg-success">
                                        Active
                                    </span>

                                @elseif($v->status === 'maintenance')

                                    <span class="badge bg-warning text-dark">
                                        Maintenance
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        Inactive
                                    </span>

                                @endif

                            </td>

                            <td class="text-end">

                                {{-- View --}}
                                <a
                                    class="btn btn-sm btn-outline-info"
                                    href="{{ route('admin.transport.vehicles.show', $v) }}"
                                    title="View Vehicle"
                                >
                                    <i class="bx bx-show"></i>
                                    View
                                </a>

                                {{-- Edit --}}
                                <a
                                    class="btn btn-sm btn-outline-primary"
                                    href="{{ route('admin.transport.vehicles.edit', $v) }}"
                                    title="Edit Vehicle"
                                >
                                    <i class="bx bx-edit-alt"></i>
                                    Edit
                                </a>

                                {{-- Delete --}}
                                <form
                                    class="d-inline"
                                    method="POST"
                                    action="{{ route('admin.transport.vehicles.destroy', $v) }}"
                                    onsubmit="return confirm('Delete this vehicle?')"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-sm btn-outline-danger"
                                        title="Delete Vehicle"
                                    >
                                        <i class="bx bx-trash"></i>
                                        Delete
                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8"
                                class="text-center py-4">

                                <div class="text-muted">

                                    <i class="bx bx-bus font-size-24 d-block mb-2"></i>

                                    No vehicles found.

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

            <div class="mt-3">
                {{ $vehicles->links() }}
            </div>

        </div>

    </div>

</div>

@endsection