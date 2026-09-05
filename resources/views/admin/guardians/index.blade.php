@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Parent / Guardian Management</h1>
            <p class="text-muted mb-0">
                Manage parents and guardians registered in the school.
            </p>
        </div>

        <a
            href="{{ route('admin.guardians.create') }}"
            class="btn btn-primary"
        >
            Add Guardian
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Search --}}
    <div class="card mb-4">
        <div class="card-body">

            <form
                method="GET"
                action="{{ route('admin.guardians.index') }}"
            >
                <div class="row g-2">

                    <div class="col-md-10">
                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Search by guardian number, name, phone, WhatsApp or email..."
                            value="{{ request('search') }}"
                        >
                    </div>

                    <div class="col-md-2">
                        <button
                            type="submit"
                            class="btn btn-outline-primary w-100"
                        >
                            Search
                        </button>
                    </div>

                </div>
            </form>

        </div>
    </div>

    {{-- Guardian Table --}}
    <div class="card">

        <div class="card-header">
            <h5 class="mb-0">
                Registered Guardians
            </h5>
        </div>

        <div class="card-body p-0">

            @if($guardians->count())

                <div class="table-responsive">

                    <table class="table table-hover mb-0">

                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Guardian Number</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>WhatsApp</th>
                                <th>Occupation</th>
                                <th>Students</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($guardians as $guardian)

                                <tr>

                                    <td>
                                        {{ $guardians->firstItem() + $loop->index }}
                                    </td>

                                    <td>
                                        <strong>
                                            {{ $guardian->guardian_number }}
                                        </strong>
                                    </td>

                                    <td>
                                        {{ $guardian->full_name }}
                                    </td>

                                    <td>
                                        {{ $guardian->phone ?: '—' }}
                                    </td>

                                    <td>
                                        {{ $guardian->whatsapp ?: '—' }}
                                    </td>

                                    <td>
                                        {{ $guardian->occupation ?: '—' }}
                                    </td>

                                    <td>
                                        {{ $guardian->students_count }}
                                    </td>

                                    <td>
                                        <div class="d-flex gap-1">

                                            <a
                                                href="{{ route('admin.guardians.show', $guardian) }}"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                View
                                            </a>

                                            <a
                                                href="{{ route('admin.guardians.edit', $guardian) }}"
                                                class="btn btn-sm btn-outline-secondary"
                                            >
                                                Edit
                                            </a>

                                            <form
                                                method="POST"
                                                action="{{ route('admin.guardians.destroy', $guardian) }}"
                                                onsubmit="return confirm('Are you sure you want to delete this guardian?');"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                >
                                                    Delete
                                                </button>

                                            </form>

                                        </div>
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

                <div class="p-3">
                    {{ $guardians->links() }}
                </div>

            @else

                <div class="p-5 text-center">

                    <h5>No guardians found</h5>

                    <p class="text-muted">
                        No parent or guardian records have been registered yet.
                    </p>

                    <a
                        href="{{ route('admin.guardians.create') }}"
                        class="btn btn-primary"
                    >
                        Register First Guardian
                    </a>

                </div>

            @endif

        </div>
    </div>

</div>

@endsection