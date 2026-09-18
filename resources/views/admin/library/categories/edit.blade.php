@extends('backend.layout.default')

@section('title', $title)

@section('content')
<div class="container-fluid">

    {{-- ============================================================= --}}
    {{-- PAGE HEADER --}}
    {{-- ============================================================= --}}
    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>
            <h4 class="mb-1">{{ $title }}</h4>

            <p class="text-muted mb-0">
                Update library category information.
            </p>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('admin.library.categories.show', $category) }}"
               class="btn btn-light">

                <i class="bx bx-show me-1"></i>
                View

            </a>

            <a href="{{ route('admin.library.categories.index') }}"
               class="btn btn-light">

                <i class="bx bx-arrow-back me-1"></i>
                Back to Categories

            </a>

        </div>

    </div>


    {{-- ============================================================= --}}
    {{-- VALIDATION ERRORS --}}
    {{-- ============================================================= --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <strong>Please correct the following errors:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    {{-- ============================================================= --}}
    {{-- UPDATE FORM --}}
    {{-- ============================================================= --}}
    <div class="card">

        <div class="card-header">

            <h5 class="card-title mb-0">
                Category Information
            </h5>

        </div>


        <div class="card-body">

            <form method="POST"
                  action="{{ route(
                      'admin.library.categories.update',
                      $category
                  ) }}">

                @csrf
                @method('PUT')


                <div class="row">

                    {{-- Category Name --}}
                    <div class="col-md-6 mb-3">

                        <label for="name"
                               class="form-label">

                            Category Name
                            <span class="text-danger">*</span>

                        </label>

                        <input type="text"
                               id="name"
                               name="name"
                               value="{{ old(
                                   'name',
                                   $category->name
                               ) }}"
                               class="form-control @error('name') is-invalid @enderror"
                               maxlength="150"
                               required>

                        @error('name')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Category Code --}}
                    <div class="col-md-6 mb-3">

                        <label for="code"
                               class="form-label">

                            Category Code

                        </label>

                        <input type="text"
                               id="code"
                               name="code"
                               value="{{ old(
                                   'code',
                                   $category->code
                               ) }}"
                               class="form-control @error('code') is-invalid @enderror"
                               maxlength="50"
                               placeholder="Enter category code">

                        @error('code')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Description --}}
                    <div class="col-12 mb-3">

                        <label for="description"
                               class="form-label">

                            Description

                        </label>

                        <textarea id="description"
                                  name="description"
                                  rows="5"
                                  class="form-control @error('description') is-invalid @enderror"
                                  placeholder="Enter category description">{{ old(
                                      'description',
                                      $category->description
                                  ) }}</textarea>

                        @error('description')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- ACTIONS --}}
                {{-- ================================================= --}}
                <div class="d-flex justify-content-end gap-2 pt-3 border-top">

                    <a href="{{ route(
                        'admin.library.categories.index'
                    ) }}"
                       class="btn btn-light">

                        Cancel

                    </a>

                    @can('library.update')

                        <button type="submit"
                                class="btn btn-primary">

                            <i class="bx bx-save me-1"></i>

                            Update Category

                        </button>

                    @endcan

                </div>

            </form>

        </div>

    </div>

</div>
@endsection