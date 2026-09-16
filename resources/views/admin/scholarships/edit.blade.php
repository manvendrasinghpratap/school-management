@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Title --}}
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">

        <h4>Edit Scholarship</h4>

        <ol class="breadcrumb m-0">

            <li class="breadcrumb-item">
                Finance
            </li>

            <li class="breadcrumb-item">
                <a href="{{ route('admin.scholarships.index') }}">
                    Scholarships
                </a>
            </li>

            <li class="breadcrumb-item active">
                Edit
            </li>

        </ol>

    </div>


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show">

            <strong>
                Please correct the following:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Scholarship Form --}}
    <div class="card">

        <div class="card-header">

            <h5 class="card-title mb-0">
                Scholarship Details
            </h5>

        </div>

        <div class="card-body">

            <form method="POST"
                  action="{{ route('admin.scholarships.update', $scholarship) }}">

                @csrf
                @method('PUT')


                <div class="row g-3">

                    {{-- Scholarship Name --}}
                    <div class="col-md-6">

                        <label for="name"
                               class="form-label">

                            Scholarship Name

                            <span class="text-danger">
                                *
                            </span>

                        </label>

                        <input type="text"
                               name="name"
                               id="name"
                               value="{{ old('name', $scholarship->name) }}"
                               class="form-control @error('name') is-invalid @enderror"
                               required>

                        @error('name')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Scholarship Type --}}
                    <div class="col-md-3">

                        <label for="type"
                               class="form-label">

                            Type

                            <span class="text-danger">
                                *
                            </span>

                        </label>

                        <select name="type"
                                id="type"
                                class="form-select @error('type') is-invalid @enderror"
                                required>

                            <option value="percentage"
                                @selected(old('type', $scholarship->type) === 'percentage')>

                                Percentage

                            </option>

                            <option value="fixed"
                                @selected(old('type', $scholarship->type) === 'fixed')>

                                Fixed Amount

                            </option>

                        </select>

                        @error('type')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Scholarship Value --}}
                    <div class="col-md-3">

                        <label for="value"
                               class="form-label">

                            Value

                            <span class="text-danger">
                                *
                            </span>

                        </label>

                        <div class="input-group">

                            <input type="number"
                                   step="0.01"
                                   min="0"
                                   name="value"
                                   id="value"
                                   value="{{ old('value', $scholarship->value) }}"
                                   class="form-control @error('value') is-invalid @enderror"
                                   required>

                            <span class="input-group-text"
                                  id="valueSuffix">
                                %
                            </span>

                        </div>

                        @error('value')

                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Description --}}
                    <div class="col-12">

                        <label for="description"
                               class="form-label">

                            Description

                        </label>

                        <textarea name="description"
                                  id="description"
                                  rows="4"
                                  class="form-control @error('description') is-invalid @enderror">{{ old('description', $scholarship->description) }}</textarea>

                        @error('description')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Active Status --}}
                    <div class="col-12">

                        <div class="form-check form-switch">

                            <input class="form-check-input"
                                   type="checkbox"
                                   name="is_active"
                                   value="1"
                                   id="is_active"
                                   @checked(old('is_active', $scholarship->is_active))>

                            <label class="form-check-label"
                                   for="is_active">

                                Active

                            </label>

                        </div>

                    </div>

                </div>


                {{-- Form Buttons --}}
                <div class="mt-4">

                    <button type="submit"
                            class="btn btn-primary">

                        Save Scholarship

                    </button>

                    <a href="{{ route('admin.scholarships.show', $scholarship) }}"
                       class="btn btn-light ms-2">

                        Cancel

                    </a>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection


@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const typeSelect = document.getElementById('type');
    const valueSuffix = document.getElementById('valueSuffix');
    const valueInput = document.getElementById('value');

    function updateScholarshipType() {

        if (!typeSelect || !valueSuffix) {
            return;
        }

        if (typeSelect.value === 'percentage') {

            valueSuffix.textContent = '%';

            if (valueInput) {
                valueInput.setAttribute('max', '100');
            }

        } else {

            valueSuffix.textContent = '₹';

            if (valueInput) {
                valueInput.removeAttribute('max');
            }

        }
    }


    // Update when the user changes the type.
    typeSelect.addEventListener(
        'change',
        updateScholarshipType
    );


    // IMPORTANT:
    // Initialize the correct suffix from the saved database value.
    updateScholarshipType();

});
</script>

@endpush