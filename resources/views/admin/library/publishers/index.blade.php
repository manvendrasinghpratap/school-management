@extends('backend.layout.default')

@section('title', 'Library Publishers')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">Library Publishers</h4>
            <p class="text-muted mb-0">
                Manage publishers registered in your school library.
            </p>
        </div>

        @can('library.create')

            <a href="{{ route('admin.library.publishers.create') }}"
               class="btn btn-primary">
                <i class="mdi mdi-plus me-1"></i>
                Add Publisher
            </a>

        @endcan

    </div>


    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>
        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>
        </div>

    @endif


    <div class="card mb-4">

        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.library.publishers.index') }}">

                <div class="row g-2">

                    <div class="col-md-8">

                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               class="form-control"
                               placeholder="Search publisher, phone or email...">

                    </div>

                    <div class="col-auto">

                        <button type="submit"
                                class="btn btn-secondary">
                            <i class="mdi mdi-magnify me-1"></i>
                            Search
                        </button>

                    </div>

                    @if(request('search'))

                        <div class="col-auto">

                            <a href="{{ route('admin.library.publishers.index') }}"
                               class="btn btn-light">
                                Clear
                            </a>

                        </div>

                    @endif

                </div>

            </form>

        </div>

    </div>


    <div class="card">

        <div class="card-header d-flex justify-content-between">

            <div>
                <h5 class="card-title mb-1">
                    Publishers
                </h5>

                <p class="text-muted mb-0">
                    Library publishers for this school.
                </p>
            </div>

            <span class="badge bg-primary-subtle text-primary align-self-center">
                {{ $publishers->total() }} publishers
            </span>

        </div>


        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>
                        <th>#</th>
                        <th>Publisher</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Website</th>
                        <th style="width:180px;">Actions</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($publishers as $publisher)

                        <tr>

                            <td>
                                {{ $publishers->firstItem() + $loop->index }}
                            </td>

                            <td>
                                <strong>
                                    {{ $publisher->name }}
                                </strong>
                            </td>

                            <td>
                                {{ $publisher->phone ?: '—' }}
                            </td>

                            <td>
                                {{ $publisher->email ?: '—' }}
                            </td>

                            <td>

                                @if($publisher->website)

                                    <a href="{{ $publisher->website }}"
                                       target="_blank"
                                       rel="noopener noreferrer">
                                        {{ $publisher->website }}
                                    </a>

                                @else
                                    —
                                @endif

                            </td>

                            <td>

                                <div class="d-flex gap-1">

                                    @can('library.update')

                                        <a href="{{ route('admin.library.publishers.edit', $publisher) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="mdi mdi-pencil-outline"></i>
                                            Edit
                                        </a>

                                    @endcan


                                    @can('library.delete')

                                        <form method="POST"
                                              action="{{ route('admin.library.publishers.destroy', $publisher) }}"
                                              onsubmit="return confirm('Are you sure you want to delete this publisher?');">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger">
                                                <i class="mdi mdi-delete-outline"></i>
                                                Delete
                                            </button>

                                        </form>

                                    @endcan

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6"
                                class="text-center py-5 text-muted">
                                No publishers found.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        @if($publishers->hasPages())

            <div class="card-body border-top">
                {{ $publishers->links() }}
            </div>

        @endif

    </div>

</div>

@endsection