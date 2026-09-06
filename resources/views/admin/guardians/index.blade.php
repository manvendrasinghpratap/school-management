@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- ===================================================== --}}
    {{-- Page Header --}}
    {{-- ===================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="h3 mb-1">
                Parent / Guardian Management
            </h1>

            <p class="text-muted mb-0">
                Manage parents and guardians registered in the school.
            </p>
        </div>

        {{-- Add Guardian --}}
        @can('guardians.create')
            <a
                href="{{ route('admin.guardians.create') }}"
                class="btn btn-primary"
            >
                <i class="bx bx-user-plus me-1"></i>
                Add Guardian
            </a>
        @endcan

    </div>


    {{-- ===================================================== --}}
    {{-- Success Message --}}
    {{-- ===================================================== --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- ===================================================== --}}
    {{-- Error Message --}}
    {{-- ===================================================== --}}
    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- ===================================================== --}}
    {{-- Search --}}
    {{-- ===================================================== --}}
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
                            <i class="bx bx-search me-1"></i>
                            Search
                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- Guardian Table --}}
    {{-- ===================================================== --}}
    <div class="card">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0">
                    Registered Guardians
                </h5>

                <span class="badge bg-info">
                    {{ $guardians->total() }}
                </span>

            </div>

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

                                    {{-- Number --}}
                                    <td>
                                        {{ $guardians->firstItem() + $loop->index }}
                                    </td>


                                    {{-- Guardian Number --}}
                                    <td>

                                        <strong>
                                            {{ $guardian->guardian_number }}
                                        </strong>

                                    </td>


                                    {{-- Name --}}
                                    <td>
                                        {{ $guardian->full_name }}
                                    </td>


                                    {{-- Phone --}}
                                    <td>
                                        {{ $guardian->phone ?: '—' }}
                                    </td>


                                    {{-- WhatsApp --}}
                                    <td>
                                        {{ $guardian->whatsapp ?: '—' }}
                                    </td>


                                    {{-- Occupation --}}
                                    <td>
                                        {{ $guardian->occupation ?: '—' }}
                                    </td>


                                    {{-- Students --}}
                                    <td>

                                        <span class="badge bg-light text-dark">
                                            {{ $guardian->students_count }}
                                        </span>

                                    </td>


                                    {{-- Actions --}}
                                    <td>

                                        <div class="d-flex gap-1 flex-wrap">

                                            {{-- View --}}
                                            @can('guardians.view')

                                                <a
                                                    href="{{ route('admin.guardians.show', $guardian) }}"
                                                    class="btn btn-sm btn-outline-primary"
                                                    title="View Guardian"
                                                >
                                                    <i class="bx bx-show"></i>
                                                    View
                                                </a>

                                            @endcan


                                            {{-- Edit --}}
                                            @can('guardians.update')

                                                <a
                                                    href="{{ route('admin.guardians.edit', $guardian) }}"
                                                    class="btn btn-sm btn-outline-secondary"
                                                    title="Edit Guardian"
                                                >
                                                    <i class="bx bx-edit"></i>
                                                    Edit
                                                </a>

                                            @endcan


                                            {{-- Delete --}}
                                            @can('guardians.delete')

                                                <form
                                                    method="POST"
                                                    action="{{ route('admin.guardians.destroy', $guardian) }}"
                                                    onsubmit="return confirm('Are you sure you want to delete this guardian?');"
                                                    class="d-inline"
                                                >

                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="Delete Guardian"
                                                    >
                                                        <i class="bx bx-trash"></i>
                                                        Delete
                                                    </button>

                                                </form>

                                            @endcan

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- Pagination --}}
                <div class="p-3">

                    {{ $guardians->links() }}

                </div>

            @else

                {{-- ================================================= --}}
                {{-- Empty State --}}
                {{-- ================================================= --}}
                <div class="p-5 text-center">

                    <div class="mb-3">

                        <i
                            class="bx bx-group text-muted"
                            style="font-size:48px;"
                        ></i>

                    </div>

                    <h5>
                        No guardians found
                    </h5>

                    <p class="text-muted mb-3">
                        No parent or guardian records have been registered yet.
                    </p>


                    {{-- Register First Guardian --}}
                    @can('guardians.create')

                        <a
                            href="{{ route('admin.guardians.create') }}"
                            class="btn btn-primary"
                        >
                            <i class="bx bx-user-plus me-1"></i>
                            Register First Guardian
                        </a>

                    @endcan

                </div>

            @endif

        </div>

    </div>

</div>

@endsection