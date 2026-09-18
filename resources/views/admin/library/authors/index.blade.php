@extends('backend.layout.default')

@section('title', 'Library Authors')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">Library Authors</h4>
            <p class="text-muted mb-0">
                Manage authors registered in your school library.
            </p>
        </div>

        @can('library.create')
            <a href="{{ route('admin.library.authors.create') }}"
               class="btn btn-primary">
                <i class="mdi mdi-plus me-1"></i>
                Add Author
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


    {{-- Search --}}
    <div class="card mb-4">

        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.library.authors.index') }}">

                <div class="row g-2">

                    <div class="col-md-8">

                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               class="form-control"
                               placeholder="Search author name or biography...">

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

                            <a href="{{ route('admin.library.authors.index') }}"
                               class="btn btn-light">
                                Clear
                            </a>

                        </div>

                    @endif

                </div>

            </form>

        </div>

    </div>


    {{-- Authors --}}
    <div class="card">

        <div class="card-header d-flex justify-content-between">

            <div>
                <h5 class="card-title mb-1">
                    Authors
                </h5>

                <p class="text-muted mb-0">
                    Library authors for this school.
                </p>
            </div>

            <span class="badge bg-primary-subtle text-primary align-self-center">
                {{ $authors->total() }} authors
            </span>

        </div>


        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>
                        <th style="width:70px;">#</th>
                        <th>Author</th>
                        <th>Biography</th>
                        <th style="width:180px;">Actions</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($authors as $author)

                        <tr>

                            <td>
                                {{ $authors->firstItem() + $loop->index }}
                            </td>

                            <td>
                                <strong>
                                    {{ $author->name }}
                                </strong>
                            </td>

                            <td>
                                @if($author->bio)
                                    {{ \Illuminate\Support\Str::limit($author->bio, 100) }}
                                @else
                                    <span class="text-muted">
                                        —
                                    </span>
                                @endif
                            </td>

                            <td>

                                <div class="d-flex gap-1">

                                    @can('library.update')

                                        <a href="{{ route('admin.library.authors.edit', $author) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="mdi mdi-pencil-outline"></i>
                                            Edit
                                        </a>

                                    @endcan


                                    @can('library.delete')

                                        <form method="POST"
                                              action="{{ route('admin.library.authors.destroy', $author) }}"
                                              onsubmit="return confirm('Are you sure you want to delete this author?');">

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

                            <td colspan="4"
                                class="text-center py-5">

                                <div class="text-muted">
                                    No authors found.
                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        @if($authors->hasPages())

            <div class="card-body border-top">

                {{ $authors->links() }}

            </div>

        @endif

    </div>

</div>

@endsection