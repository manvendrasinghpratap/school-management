@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <h4>Transport Routes</h4>

        <a href="{{ route('admin.transport.routes.create') }}"
           class="btn btn-primary">
            <i class="bx bx-plus me-1"></i>
            Add Route
        </a>

    </div>

    @include('admin.transport._flash')

    <form class="row g-2 mb-3" method="GET">

        <div class="col-md-5">
            <input
                name="search"
                value="{{ request('search') }}"
                class="form-control"
                placeholder="Search route name, code, start or end point"
            >
        </div>

        <div class="col-md-3">

            <select name="status" class="form-select">

                <option value="">
                    All Statuses
                </option>

                @foreach(['active', 'inactive'] as $s)

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

                <a href="{{ route('admin.transport.routes.index') }}"
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
                        <th>Code</th>
                        <th>Route Name</th>
                        <th>Vehicle</th>
                        <th>Driver</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Monthly Fee</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($routes as $route)

                        <tr>

                            <td>
                                <strong>
                                    {{ $route->code ?: '—' }}
                                </strong>
                            </td>

                            <td>
                                {{ $route->name ?: '—' }}
                            </td>

                            <td>
                                {{ $route->vehicle?->vehicle_number ?: '—' }}
                            </td>

                            <td>
                                {{ $route->driver?->name ?: '—' }}
                            </td>

                            <td>
                                {{ $route->start_point ?: '—' }}
                            </td>

                            <td>
                                {{ $route->end_point ?: '—' }}
                            </td>

                            <td>
                                @if($route->monthly_fee !== null)
                                    ₹{{ number_format((float) $route->monthly_fee, 2) }}
                                @else
                                    —
                                @endif
                            </td>

                            <td>

                                @if($route->status === 'active')

                                    <span class="badge bg-success">
                                        Active
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        Inactive
                                    </span>

                                @endif

                            </td>

                            <td class="text-end">

                                <a
                                    class="btn btn-sm btn-outline-info"
                                    href="{{ route('admin.transport.routes.show', $route) }}"
                                    title="View Route"
                                >
                                    <i class="bx bx-show"></i>
                                    View
                                </a>

                                <a
                                    class="btn btn-sm btn-outline-primary"
                                    href="{{ route('admin.transport.routes.edit', $route) }}"
                                    title="Edit Route"
                                >
                                    <i class="bx bx-edit-alt"></i>
                                    Edit
                                </a>

                                <form
                                    class="d-inline"
                                    method="POST"
                                    action="{{ route('admin.transport.routes.destroy', $route) }}"
                                    onsubmit="return confirm('Delete this transport route?')"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-sm btn-outline-danger"
                                        title="Delete Route"
                                    >
                                        <i class="bx bx-trash"></i>
                                        Delete
                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="9"
                                class="text-center py-4">

                                <div class="text-muted">

                                    <i class="bx bx-map font-size-24 d-block mb-2"></i>

                                    No transport routes found.

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

            <div class="mt-3">
                {{ $routes->links() }}
            </div>

        </div>

    </div>

</div>

@endsection