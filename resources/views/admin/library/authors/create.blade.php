@extends('backend.layout.default')

@section('title', 'Add Library Author')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">Add Library Author</h4>
            <p class="text-muted mb-0">
                Register a new author.
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

            <p class="text-muted mb-0">
                Enter the author details below.
            </p>

        </div>


        <div class="card-body">

            <form method="POST"
                  action="{{ route('admin.library.authors.store') }}">

                @csrf

                <div class="row">

                    <div class="col-md-12 mb-3">

                        <label for="name" class="form-label">
                            Author Name
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               id="name"
                               name="name"
                               value="{{ old('name') }}"
                               class="form-control @error('name') is-invalid @enderror"
                               placeholder="Enter author name"
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
                                  class="form-control @error('bio') is-invalid @enderror"
                                  placeholder="Optional author biography...">{{ old('bio') }}</textarea>

                        @error('bio')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>


                <div class="d-flex justify-content-end gap-2">

                    <a href="{{ route('admin.library.authors.index') }}"
                       class="btn btn-light">
                        Cancel
                    </a>

                    <button type="submit"
                            class="btn btn-primary">
                        <i class="mdi mdi-content-save-outline me-1"></i>
                        Save Author
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection