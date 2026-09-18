@extends('backend.layout.default')

@section('title', 'Edit Library Publisher')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">Edit Library Publisher</h4>
            <p class="text-muted mb-0">
                Update publisher information.
            </p>
        </div>

        <a href="{{ route('admin.library.publishers.index') }}"
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
                Publisher Information
            </h5>

        </div>


        <div class="card-body">

            <form method="POST"
                  action="{{ route('admin.library.publishers.update', $publisher) }}">

                @csrf
                @method('PUT')

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Publisher Name
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="name"
                               value="{{ old('name', $publisher->name) }}"
                               class="form-control"
                               required>

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Phone
                        </label>

                        <input type="text"
                               name="phone"
                               value="{{ old('phone', $publisher->phone) }}"
                               class="form-control">

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Email
                        </label>

                        <input type="email"
                               name="email"
                               value="{{ old('email', $publisher->email) }}"
                               class="form-control">

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Website
                        </label>

                        <input type="text"
                               name="website"
                               value="{{ old('website', $publisher->website) }}"
                               class="form-control">

                    </div>


                    <div class="col-12 mb-3">

                        <label class="form-label">
                            Address
                        </label>

                        <textarea name="address"
                                  rows="4"
                                  class="form-control">{{ old('address', $publisher->address) }}</textarea>

                    </div>

                </div>


                <div class="d-flex justify-content-between">

                    @can('library.delete')

                        <button type="button"
                                class="btn btn-outline-danger"
                                onclick="if(confirm('Delete this publisher?')) document.getElementById('delete-publisher-form').submit();">
                            <i class="mdi mdi-delete-outline me-1"></i>
                            Delete
                        </button>

                    @endcan


                    <div class="d-flex gap-2 ms-auto">

                        <a href="{{ route('admin.library.publishers.index') }}"
                           class="btn btn-light">
                            Cancel
                        </a>

                        <button type="submit"
                                class="btn btn-primary">
                            <i class="mdi mdi-content-save-outline me-1"></i>
                            Update Publisher
                        </button>

                    </div>

                </div>

            </form>


            @can('library.delete')

                <form id="delete-publisher-form"
                      method="POST"
                      action="{{ route('admin.library.publishers.destroy', $publisher) }}"
                      class="d-none">

                    @csrf
                    @method('DELETE')

                </form>

            @endcan

        </div>

    </div>

</div>

@endsection