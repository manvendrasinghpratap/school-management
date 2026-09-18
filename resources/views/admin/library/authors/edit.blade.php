@extends('backend.layout.default')

@section('title', 'Edit Library Author')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">Edit Library Author</h4>
            <p class="text-muted mb-0">
                Update author information.
            </p>
        </div>

        <a href="{{ route('admin.library.authors.index') }}"
           class="btn btn-light">
            <i class="mdi mdi-arrow-left me-1"></i>
            Back
        </a>

    </div>


    @if($errors->any())

        <div class="alert alert-danger">

            <strong>Please correct the following:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    <div class="card">

        <div class="card-header">

            <h5 class="card-title mb-1">
                Author Information
            </h5>

        </div>


        <div class="card-body">

            <form method="POST"
                  action="{{ route('admin.library.authors.update', $author) }}">

                @csrf
                @method('PUT')

                <div class="row">

                    <div class="col-md-12 mb-3">

                        <label for="name" class="form-label">
                            Author Name
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               id="name"
                               name="name"
                               value="{{ old('name', $author->name) }}"
                               class="form-control @error('name') is-invalid @enderror"
                               required>

                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <div class="col-md-12 mb-3">

                        <label for="bio" class="form-label">
                            Biography
                        </label>

                        <textarea id="bio"
                                  name="bio"
                                  rows="6"
                                  class="form-control @error('bio') is-invalid @enderror">{{ old('bio', $author->bio) }}</textarea>

                        @error('bio')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>


                <div class="d-flex justify-content-between">

                    @can('library.delete')

                        <button type="button"
                                class="btn btn-outline-danger"
                                onclick="if(confirm('Delete this author?')) document.getElementById('delete-author-form').submit();">
                            <i class="mdi mdi-delete-outline me-1"></i>
                            Delete
                        </button>

                    @endcan


                    <div class="d-flex gap-2 ms-auto">

                        <a href="{{ route('admin.library.authors.index') }}"
                           class="btn btn-light">
                            Cancel
                        </a>

                        <button type="submit"
                                class="btn btn-primary">
                            <i class="mdi mdi-content-save-outline me-1"></i>
                            Update Author
                        </button>

                    </div>

                </div>

            </form>


            @can('library.delete')

                <form id="delete-author-form"
                      method="POST"
                      action="{{ route('admin.library.authors.destroy', $author) }}"
                      class="d-none">

                    @csrf
                    @method('DELETE')

                </form>

            @endcan

        </div>

    </div>

</div>

@endsection