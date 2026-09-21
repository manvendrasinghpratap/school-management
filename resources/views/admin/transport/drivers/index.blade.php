@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Transport Drivers</h4>

        <a href="{{ route('admin.transport.drivers.create') }}"
           class="btn btn-primary">
            <i class="bx bx-plus me-1"></i>
            Add Driver
        </a>
    </div>

    @include('admin.transport._flash')

    <form class="row g-2 mb-3" method="GET">

        <div class="col-md-5">
            <input
                name="search"
                value="{{ request('search') }}"
                class="form-control"
                placeholder="Search name, license or phone"
            >
        </div>

        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">All Statuses</option>

                @foreach(['active', 'inactive', 'suspended'] as $s)
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
            <button type="submit" class="btn btn-outline-primary">
                <i class="bx bx-search me-1"></i>
                Filter
            </button>
        </div>

        @if(request('search') || request('status'))
            <div class="col-auto">
                <a href="{{ route('admin.transport.drivers.index') }}"
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
                        <th>Name</th>
                        <th>Staff</th>
                        <th>License</th>
                        <th>Expiry</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($drivers as $d)

                        <tr>

                            <td>
                                <strong>{{ $d->name }}</strong>
                            </td>

                            <td>
                                {{ $d->staff?->first_name }}
                                {{ $d->staff?->last_name }}

                                @if(!$d->staff)
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            <td>
                                {{ $d->license_number ?: '—' }}
                            </td>

                            <td>
                                {{ $d->license_expiry?->format('d M Y') ?: '—' }}
                            </td>

                            <td>
                                {{ $d->phone ?: '—' }}
                            </td>

                            <td>

                                @if($d->status === 'active')

                                    <span class="badge bg-success">
                                        Active
                                    </span>

                                @elseif($d->status === 'suspended')

                                    <span class="badge bg-warning text-dark">
                                        Suspended
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
                                    href="{{ route('admin.transport.drivers.show', $d) }}"
                                    title="View Driver"
                                >
                                    <i class="bx bx-show"></i>
                                    View
                                </a>

                                {{-- Edit --}}
                                <a
                                    class="btn btn-sm btn-outline-primary"
                                    href="{{ route('admin.transport.drivers.edit', $d) }}"
                                    title="Edit Driver"
                                >
                                    <i class="bx bx-edit-alt"></i>
                                    Edit
                                </a>

                                {{-- Delete --}}
                                <form
                                    class="d-inline"
                                    method="POST"
                                    action="{{ route('admin.transport.drivers.destroy', $d) }}"
                                    onsubmit="return confirm('Delete this driver?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-sm btn-outline-danger"
                                        title="Delete Driver"
                                    >
                                        <i class="bx bx-trash"></i>
                                        Delete
                                    </button>
                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="bx bx-user-x font-size-24 d-block mb-2"></i>
                                    No drivers found.
                                </div>
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

            <div class="mt-3">
                {{ $drivers->links() }}
            </div>

        </div>

    </div>

</div>

@endsection