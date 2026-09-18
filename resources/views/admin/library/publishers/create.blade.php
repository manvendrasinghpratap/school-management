@extends('backend.layout.default')

@section('title', 'Add Library Publisher')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">Add Library Publisher</h4>
            <p class="text-muted mb-0">
                Register a new publisher.
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

            <p class="text-muted mb-0">
                Enter publisher contact details.
            </p>

        </div>


        <div class="card-body">

            <form method="POST"
                  action="{{ route('admin.library.publishers.store') }}">

                @csrf

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Publisher Name
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="name"
                               value="{{ old('name') }}"
                               class="form-control"
                               required>

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Phone
                        </label>

                        <input type="text"
                               name="phone"
                               value="{{ old('phone') }}"
                               class="form-control">

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Email
                        </label>

                        <input type="email"
                               name="email"
                               value="{{ old('email') }}"
                               class="form-control">

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Website
                        </label>

                        <input type="text"
                               name="website"
                               value="{{ old('website') }}"
                               class="form-control"
                               placeholder="https://example.com">

                    </div>


                    <div class="col-12 mb-3">

                        <label class="form-label">
                            Address
                        </label>

                        <textarea name="address"
                                  rows="4"
                                  class="form-control"
                                  placeholder="Publisher address...">{{ old('address') }}</textarea>

                    </div>

                </div>


                <div class="d-flex justify-content-end gap-2">

                    <a href="{{ route('admin.library.publishers.index') }}"
                       class="btn btn-light">
                        Cancel
                    </a>

                    <button type="submit"
                            class="btn btn-primary">
                        <i class="mdi mdi-content-save-outline me-1"></i>
                        Save Publisher
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection