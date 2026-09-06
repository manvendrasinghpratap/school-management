@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Edit User</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.users.index') }}">Users</a>
                        </li>

                        <li class="breadcrumb-item active">
                            Edit User
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Please correct the following:</strong>

            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>
        </div>
    @endif

    {{-- Form --}}
    <div class="row">
        <div class="col-xl-10 mx-auto">

            <div class="card">

                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <h5 class="card-title mb-1">
                                Edit User
                            </h5>

                            <p class="text-muted mb-0">
                                Update account information for
                                <strong>{{ $user->name }}</strong>
                            </p>
                        </div>

                        <a href="{{ route('admin.users.show', $user) }}"
                           class="btn btn-light">
                            <i class="mdi mdi-arrow-left me-1"></i>
                            Back to User
                        </a>

                    </div>
                </div>

                <div class="card-body">

                    <form action="{{ route('admin.users.update', $user) }}"
                          method="POST"
                          enctype="multipart/form-data">

                        @csrf
                        @method('PUT')

                        @include('admin.users.form', [
                            'user' => $user,
                            'roles' => $roles,
                            'isEdit' => true
                        ])

                    </form>

                </div>

            </div>

        </div>
    </div>

</div>

@endsection