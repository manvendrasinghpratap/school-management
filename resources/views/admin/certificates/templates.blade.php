@extends('backend.layout.default')

@section('content')
<div class="container-fluid">

    {{-- Page Title --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Certificate Templates</h4>

                <div>
                    <a class="btn btn-secondary"
                       href="{{ route('admin.certificates.index') }}">
                        <i class="ri-arrow-left-line align-middle me-1"></i>
                        Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Success --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Errors --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- ============================================================
         CREATE TEMPLATE
    ============================================================ --}}
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                Create Certificate Template
            </h5>
        </div>

        <div class="card-body">

            <form method="POST"
                  action="{{ route('admin.certificates.templates.store') }}">

                @csrf

                <div class="row g-3">

                    {{-- Name --}}
                    <div class="col-md-4">
                        <label class="form-label">
                            Template Name
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            value="{{ old('name') }}"
                            placeholder="Template name"
                            required
                        >
                    </div>

                    {{-- Type --}}
                    <div class="col-md-3">
                        <label class="form-label">
                            Certificate Type
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="certificate_type"
                            class="form-control"
                            value="{{ old('certificate_type') }}"
                            placeholder="e.g. Completion"
                            required
                        >
                    </div>

                    {{-- Footer --}}
                    <div class="col-md-5">
                        <label class="form-label">
                            Footer Text
                        </label>

                        <input
                            type="text"
                            name="footer_text"
                            class="form-control"
                            value="{{ old('footer_text') }}"
                            placeholder="Footer"
                        >
                    </div>

                    {{-- Body --}}
                    <div class="col-12">
                        <label class="form-label">
                            Certificate Body
                            <span class="text-danger">*</span>
                        </label>

                        <textarea
                            name="body"
                            rows="7"
                            class="form-control"
                            placeholder="Use {student_name}, {class_or_course}, {certificate_number}, {issue_date}"
                            required
                        >{{ old('body') }}</textarea>

                        <div class="form-text">
                            Available placeholders:
                            <code>{student_name}</code>,
                            <code>{class_or_course}</code>,
                            <code>{certificate_number}</code>,
                            <code>{issue_date}</code>
                        </div>
                    </div>

                    {{-- Active --}}
                    <div class="col-12">

                        <div class="form-check form-switch">

                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="is_active"
                                value="1"
                                id="create_is_active"
                                {{ old('is_active', true) ? 'checked' : '' }}
                            >

                            <label class="form-check-label"
                                   for="create_is_active">
                                <strong>Active Template</strong>
                            </label>

                        </div>

                        <div class="form-text">
                            Active templates are available when issuing certificates.
                        </div>

                    </div>

                    {{-- Submit --}}
                    <div class="col-12">

                        <button type="submit"
                                class="btn btn-primary">

                            <i class="ri-add-line align-middle me-1"></i>
                            Create Template

                        </button>

                    </div>

                </div>

            </form>

        </div>
    </div>


    {{-- ============================================================
         TEMPLATE LIST
    ============================================================ --}}
    <div class="card">

        <div class="card-header">
            <div class="d-flex align-items-center justify-content-between">

                <h5 class="card-title mb-0">
                    Certificate Templates
                </h5>

                <span class="badge bg-primary">
                    {{ $templates->total() }} Templates
                </span>

            </div>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead>
                        <tr>
                            <th style="width: 30px;">#</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Active</th>
                            <th>Created By</th>
                            <th style="width: 220px;">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                    @forelse($templates as $t)

                        <tr>

                            <td>
                                {{ $templates->firstItem() + $loop->index }}
                            </td>

                            <td>
                                <strong>
                                    {{ $t->name }}
                                </strong>
                            </td>

                            <td>
                                <span class="badge bg-info-subtle text-info">
                                    {{ $t->certificate_type }}
                                </span>
                            </td>

                            <td>

                                @if($t->is_active)

                                    <span class="badge bg-success">
                                        <i class="ri-checkbox-circle-line me-1"></i>
                                        Active
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        <i class="ri-close-circle-line me-1"></i>
                                        Inactive
                                    </span>

                                @endif

                            </td>

                            <td>
                                {{ $t->creator?->name ?? 'System' }}
                            </td>

                            <td>

                                {{-- Edit --}}
                                <button
                                    type="button"
                                    class="btn btn-sm btn-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editTemplate{{ $t->id }}"
                                >
                                    <i class="ri-edit-line"></i>
                                    Edit
                                </button>


                                {{-- Delete --}}
                                <form
                                    method="POST"
                                    action="{{ route('admin.certificates.templates.delete', $t) }}"
                                    class="d-inline"
                                    onsubmit="return confirm('Delete this certificate template?');"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-sm btn-danger"
                                    >
                                        <i class="ri-delete-bin-line"></i>
                                        Delete
                                    </button>

                                </form>

                            </td>

                        </tr>


                        {{-- =================================================
                             EDIT MODAL
                        ================================================== --}}
                        <div
                            class="modal fade"
                            id="editTemplate{{ $t->id }}"
                            tabindex="-1"
                            aria-hidden="true"
                        >

                            <div class="modal-dialog modal-lg">

                                <div class="modal-content">

                                    <div class="modal-header">

                                        <h5 class="modal-title">
                                            Edit Certificate Template
                                        </h5>

                                        <button
                                            type="button"
                                            class="btn-close"
                                            data-bs-dismiss="modal"
                                        ></button>

                                    </div>


                                    <form
                                        method="POST"
                                        action="{{ route('admin.certificates.templates.update', $t) }}"
                                    >

                                        @csrf
                                        @method('PUT')

                                        <div class="modal-body">

                                            <div class="row g-3">

                                                {{-- Name --}}
                                                <div class="col-md-6">

                                                    <label class="form-label">
                                                        Template Name
                                                        <span class="text-danger">*</span>
                                                    </label>

                                                    <input
                                                        type="text"
                                                        name="name"
                                                        class="form-control"
                                                        value="{{ $t->name }}"
                                                        required
                                                    >

                                                </div>


                                                {{-- Type --}}
                                                <div class="col-md-6">

                                                    <label class="form-label">
                                                        Certificate Type
                                                        <span class="text-danger">*</span>
                                                    </label>

                                                    <input
                                                        type="text"
                                                        name="certificate_type"
                                                        class="form-control"
                                                        value="{{ $t->certificate_type }}"
                                                        required
                                                    >

                                                </div>


                                                {{-- Footer --}}
                                                <div class="col-12">

                                                    <label class="form-label">
                                                        Footer Text
                                                    </label>

                                                    <input
                                                        type="text"
                                                        name="footer_text"
                                                        class="form-control"
                                                        value="{{ $t->footer_text }}"
                                                    >

                                                </div>


                                                {{-- Body --}}
                                                <div class="col-12">

                                                    <label class="form-label">
                                                        Certificate Body
                                                        <span class="text-danger">*</span>
                                                    </label>

                                                    <textarea
                                                        name="body"
                                                        rows="8"
                                                        class="form-control"
                                                        required
                                                    >{{ $t->body }}</textarea>

                                                    <div class="form-text">
                                                        Use:
                                                        <code>{student_name}</code>,
                                                        <code>{class_or_course}</code>,
                                                        <code>{certificate_number}</code>,
                                                        <code>{issue_date}</code>
                                                    </div>

                                                </div>


                                                {{-- Active --}}
                                                <div class="col-12">

                                                    <div class="form-check form-switch">

                                                        <input
                                                            class="form-check-input"
                                                            type="checkbox"
                                                            name="is_active"
                                                            value="1"
                                                            id="edit_is_active_{{ $t->id }}"
                                                            {{ $t->is_active ? 'checked' : '' }}
                                                        >

                                                        <label
                                                            class="form-check-label"
                                                            for="edit_is_active_{{ $t->id }}"
                                                        >
                                                            <strong>Active Template</strong>
                                                        </label>

                                                    </div>

                                                    <div class="form-text">
                                                        Disable this template to prevent it from appearing
                                                        when issuing new certificates.
                                                    </div>

                                                </div>

                                            </div>

                                        </div>


                                        <div class="modal-footer">

                                            <button
                                                type="button"
                                                class="btn btn-light"
                                                data-bs-dismiss="modal"
                                            >
                                                Cancel
                                            </button>

                                            <button
                                                type="submit"
                                                class="btn btn-primary"
                                            >
                                                <i class="ri-save-line align-middle me-1"></i>
                                                Save Changes
                                            </button>

                                        </div>

                                    </form>

                                </div>

                            </div>

                        </div>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="text-center text-muted py-4"
                            >
                                No certificate templates found.
                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $templates->links() }}
            </div>

        </div>

    </div>

</div>
@endsection