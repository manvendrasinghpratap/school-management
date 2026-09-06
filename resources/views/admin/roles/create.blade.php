@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-12">

            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0">Create Role</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                Dashboard
                            </a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.roles.index') }}">
                                Roles
                            </a>
                        </li>

                        <li class="breadcrumb-item active">
                            Create Role
                        </li>

                    </ol>
                </div>

            </div>

        </div>
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
                Create New Role
            </h5>

            <p class="text-muted mb-0">
                Create a role and assign the permissions it should have.
            </p>

        </div>

        <div class="card-body">

            <form
                action="{{ route('admin.roles.store') }}"
                method="POST"
            >

                @csrf

                @include('admin.roles.form')

            </form>

        </div>

    </div>

</div>

@endsection