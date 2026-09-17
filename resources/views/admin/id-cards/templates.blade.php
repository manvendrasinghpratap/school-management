@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="row">
        <div class="col-12">

            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <div>
                    <h4 class="mb-sm-0">ID Card Templates</h4>

                    <p class="text-muted mb-0">
                        Create and manage student and staff ID card designs.
                    </p>
                </div>

                <div>
                    <a href="{{ route('admin.id-cards.index') }}"
                       class="btn btn-secondary">

                        <i class="ri-arrow-left-line me-1"></i>
                        Back to ID Cards

                    </a>
                </div>

            </div>

        </div>
    </div>


    {{-- =========================================================
        SUCCESS MESSAGE
    ========================================================== --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show"
             role="alert">

            <i class="ri-checkbox-circle-line me-1"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
        ERROR MESSAGE
    ========================================================== --}}
    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show"
             role="alert">

            <strong>
                Please correct the following errors:
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


    {{-- =========================================================
        CREATE TEMPLATE
    ========================================================== --}}
    @can('id-cards.generate')

        <div class="card">

            <div class="card-header">

                <div class="d-flex align-items-center">

                    <div class="avatar-sm">

                        <div class="avatar-title bg-primary-subtle text-primary rounded">

                            <i class="ri-id-card-line fs-20"></i>

                        </div>

                    </div>

                    <div class="ms-3">

                        <h5 class="card-title mb-1">
                            Create ID Card Template
                        </h5>

                        <p class="text-muted mb-0">
                            Define the front and back design of an ID card.
                        </p>

                    </div>

                </div>

            </div>


            <div class="card-body">

                <form method="POST"
                      action="{{ route('admin.id-cards.templates.store') }}">

                    @csrf

                    <div class="row g-3">

                        {{-- Template Name --}}
                        <div class="col-md-5">

                            <label class="form-label">

                                Template Name

                                <span class="text-danger">
                                    *
                                </span>

                            </label>

                            <input type="text"
                                   name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}"
                                   placeholder="Example: Student ID Card 2026"
                                   required>

                            @error('name')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Card Type --}}
                        <div class="col-md-3">

                            <label class="form-label">

                                Card Type

                                <span class="text-danger">
                                    *
                                </span>

                            </label>

                            <select name="card_type"
                                    class="form-select @error('card_type') is-invalid @enderror"
                                    required>

                                <option value="">
                                    Select Card Type
                                </option>

                                <option value="student"
                                    {{ old('card_type') === 'student' ? 'selected' : '' }}>
                                    Student
                                </option>

                                <option value="staff"
                                    {{ old('card_type') === 'staff' ? 'selected' : '' }}>
                                    Staff
                                </option>

                                <option value="general"
                                    {{ old('card_type') === 'general' ? 'selected' : '' }}>
                                    General
                                </option>

                            </select>

                            @error('card_type')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Status --}}
                        <div class="col-md-4">

                            <label class="form-label d-block">
                                Status
                            </label>

                            <div class="form-check form-switch form-switch-lg">

                                <input type="checkbox"
                                       name="is_active"
                                       value="1"
                                       id="create_template_active"
                                       class="form-check-input"
                                       {{ old('is_active', true) ? 'checked' : '' }}>

                                <label class="form-check-label"
                                       for="create_template_active">

                                    Active Template

                                </label>

                            </div>

                            <div class="form-text">
                                Active templates can be selected when generating ID cards.
                            </div>

                        </div>


                        {{-- Front HTML --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Front HTML
                            </label>

                            <textarea name="front_html"
                                      rows="12"
                                      class="form-control font-monospace @error('front_html') is-invalid @enderror"
                                      placeholder="Enter front-side HTML...">{{ old('front_html') }}</textarea>

                            <div class="form-text">
                                HTML used for the front side of the ID card.
                            </div>

                            @error('front_html')

                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Back HTML --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Back HTML
                            </label>

                            <textarea name="back_html"
                                      rows="12"
                                      class="form-control font-monospace @error('back_html') is-invalid @enderror"
                                      placeholder="Enter back-side HTML...">{{ old('back_html') }}</textarea>

                            <div class="form-text">
                                HTML used for the back side of the ID card.
                            </div>

                            @error('back_html')

                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Buttons --}}
                        <div class="col-12">

                            <button type="submit"
                                    class="btn btn-primary">

                                <i class="ri-add-line me-1"></i>

                                Create Template

                            </button>

                            <button type="reset"
                                    class="btn btn-light ms-1">

                                <i class="ri-refresh-line me-1"></i>

                                Reset

                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </div>

    @endcan


    {{-- =========================================================
        EXISTING TEMPLATES
    ========================================================== --}}
    <div class="card">

        <div class="card-header">

            <div class="d-flex align-items-center justify-content-between">

                <div>

                    <h5 class="card-title mb-1">
                        Existing Templates
                    </h5>

                    <p class="text-muted mb-0">
                        Manage your school's ID card templates.
                    </p>

                </div>

                <span class="badge bg-primary-subtle text-primary">

                    {{ $templates->total() }}

                    {{ $templates->total() == 1 ? 'Template' : 'Templates' }}

                </span>

            </div>

        </div>


        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-light">

                        <tr>

                            <th style="width:60px;">
                                #
                            </th>

                            <th>
                                Template
                            </th>

                            <th>
                                Card Type
                            </th>

                            <th>
                                Created By
                            </th>

                            <th>
                                Status
                            </th>

                            <th class="text-end">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse($templates as $template)

                        <tr>

                            {{-- Number --}}
                            <td>

                                {{ $templates->firstItem() + $loop->index }}

                            </td>


                            {{-- Template --}}
                            <td>

                                <div class="d-flex align-items-center">

                                    <div class="avatar-sm flex-shrink-0">

                                        <div class="avatar-title bg-info-subtle text-info rounded">

                                            <i class="ri-id-card-line"></i>

                                        </div>

                                    </div>

                                    <div class="ms-3">

                                        <h6 class="mb-1">

                                            {{ $template->name }}

                                        </h6>

                                        <small class="text-muted">

                                            Template #{{ $template->id }}

                                        </small>

                                    </div>

                                </div>

                            </td>


                            {{-- Card Type --}}
                            <td>

                                @php

                                    $typeClass = match(
                                        strtolower($template->card_type)
                                    ) {

                                        'student' => 'primary',

                                        'staff' => 'info',

                                        default => 'secondary',

                                    };

                                @endphp


                                <span class="badge bg-{{ $typeClass }}-subtle text-{{ $typeClass }}">

                                    {{ ucfirst($template->card_type) }}

                                </span>

                            </td>


                            {{-- Created By --}}
                            <td>

                                @if($template->creator)

                                    {{ $template->creator->name }}

                                @else

                                    <span class="text-muted">
                                        System
                                    </span>

                                @endif

                            </td>


                            {{-- Status --}}
                            <td>

                                @if($template->is_active)

                                    <span class="badge bg-success-subtle text-success">

                                        <i class="ri-checkbox-circle-line me-1"></i>

                                        Active

                                    </span>

                                @else

                                    <span class="badge bg-secondary-subtle text-secondary">

                                        <i class="ri-close-circle-line me-1"></i>

                                        Inactive

                                    </span>

                                @endif

                            </td>


                            {{-- Actions --}}
                            <td class="text-end">

                                @can('id-cards.generate')

                                    {{-- Edit --}}
                                    <button type="button"
                                            class="btn btn-sm btn-soft-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editTemplateModal{{ $template->id }}">

                                        <i class="ri-edit-line me-1"></i>

                                        Edit

                                    </button>


                                    {{-- Delete --}}
                                    <form method="POST"
                                          action="{{ route('admin.id-cards.templates.delete', $template->id) }}"
                                          class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this ID card template?');">

                                        @csrf

                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-sm btn-soft-danger"
                                                title="Delete Template">

                                            <i class="ri-delete-bin-line"></i>

                                        </button>

                                    </form>

                                @endcan

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td colspan="6"
                                class="text-center py-5">

                                <div class="avatar-md mx-auto mb-3">

                                    <div class="avatar-title bg-light text-muted rounded-circle fs-24">

                                        <i class="ri-id-card-line"></i>

                                    </div>

                                </div>

                                <h5>
                                    No ID Card Templates
                                </h5>

                                <p class="text-muted mb-0">
                                    Create your first ID card template above.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>


            {{-- Pagination --}}
            @if($templates->hasPages())

                <div class="mt-3">

                    {{ $templates->links() }}

                </div>

            @endif

        </div>

    </div>


    {{-- =========================================================
        EDIT MODALS
        IMPORTANT:
        These are intentionally OUTSIDE the table.
    ========================================================== --}}

    @can('id-cards.generate')

        @foreach($templates as $template)

            <div class="modal fade"
                 id="editTemplateModal{{ $template->id }}"
                 tabindex="-1"
                 aria-labelledby="editTemplateLabel{{ $template->id }}"
                 aria-hidden="true">

                <div class="modal-dialog modal-xl modal-dialog-scrollable">

                    <div class="modal-content">


                        {{-- Modal Header --}}
                        <div class="modal-header">

                            <h5 class="modal-title"
                                id="editTemplateLabel{{ $template->id }}">

                                <i class="ri-edit-line me-1"></i>

                                Edit ID Card Template

                            </h5>

                            <button type="button"
                                    class="btn-close"
                                    data-bs-dismiss="modal"
                                    aria-label="Close">
                            </button>

                        </div>


                        {{-- Update Form --}}
                        <form method="POST"
                              action="{{ route('admin.id-cards.templates.update', $template->id) }}">

                            @csrf

                            @method('PUT')


                            <div class="modal-body">

                                <div class="row g-3">


                                    {{-- Template Name --}}
                                    <div class="col-md-5">

                                        <label class="form-label">

                                            Template Name

                                            <span class="text-danger">
                                                *
                                            </span>

                                        </label>

                                        <input type="text"
                                               name="name"
                                               class="form-control"
                                               value="{{ $template->name }}"
                                               required>

                                    </div>


                                    {{-- Card Type --}}
                                    <div class="col-md-3">

                                        <label class="form-label">

                                            Card Type

                                            <span class="text-danger">
                                                *
                                            </span>

                                        </label>

                                        <select name="card_type"
                                                class="form-select"
                                                required>

                                            <option value="student"
                                                {{ strtolower($template->card_type) === 'student' ? 'selected' : '' }}>
                                                Student
                                            </option>

                                            <option value="staff"
                                                {{ strtolower($template->card_type) === 'staff' ? 'selected' : '' }}>
                                                Staff
                                            </option>

                                            <option value="general"
                                                {{ strtolower($template->card_type) === 'general' ? 'selected' : '' }}>
                                                General
                                            </option>

                                        </select>

                                    </div>


                                    {{-- Status --}}
                                    <div class="col-md-4">

                                        <label class="form-label d-block">
                                            Status
                                        </label>

                                        <div class="form-check form-switch form-switch-lg">

                                            <input type="checkbox"
                                                   name="is_active"
                                                   value="1"
                                                   id="edit_template_active_{{ $template->id }}"
                                                   class="form-check-input"
                                                   {{ $template->is_active ? 'checked' : '' }}>

                                            <label class="form-check-label"
                                                   for="edit_template_active_{{ $template->id }}">

                                                Active Template

                                            </label>

                                        </div>

                                        <div class="form-text">

                                            Turn this off to deactivate the template.

                                        </div>

                                    </div>


                                    {{-- Front HTML --}}
                                    <div class="col-md-6">

                                        <label class="form-label">
                                            Front HTML
                                        </label>

                                        <textarea name="front_html"
                                                  rows="16"
                                                  class="form-control font-monospace">{{ $template->front_html }}</textarea>

                                        <div class="form-text">
                                            Front-side HTML design.
                                        </div>

                                    </div>


                                    {{-- Back HTML --}}
                                    <div class="col-md-6">

                                        <label class="form-label">
                                            Back HTML
                                        </label>

                                        <textarea name="back_html"
                                                  rows="16"
                                                  class="form-control font-monospace">{{ $template->back_html }}</textarea>

                                        <div class="form-text">
                                            Back-side HTML design.
                                        </div>

                                    </div>

                                </div>

                            </div>


                            {{-- Modal Footer --}}
                            <div class="modal-footer">

                                <button type="button"
                                        class="btn btn-light"
                                        data-bs-dismiss="modal">

                                    Cancel

                                </button>


                                <button type="submit"
                                        class="btn btn-primary">

                                    <i class="ri-save-line me-1"></i>

                                    Update Template

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        @endforeach

    @endcan

</div>

@endsection