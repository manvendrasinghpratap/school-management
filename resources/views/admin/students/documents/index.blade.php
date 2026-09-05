@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row">
        <div class="col-12">

            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0 font-size-18">
                    Student Documents
                </h4>

                <div class="page-title-right">

                    <a
                        href="{{ route('admin.students.show', $student) }}"
                        class="btn btn-secondary"
                    >
                        <i class="bx bx-arrow-back"></i>
                        Back to Student
                    </a>

                </div>

            </div>

        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">

            <strong>Please correct the following errors:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>
    @endif


    {{-- Student Summary --}}
    <div class="row">

        <div class="col-12">

            <div class="card">

                <div class="card-body">

                    <div class="d-flex align-items-center">

                        @if($student->photo)

                            <img
                                src="{{ asset('storage/' . $student->photo) }}"
                                alt="{{ $student->full_name }}"
                                class="rounded-circle me-3"
                                style="width:70px;height:70px;object-fit:cover;"
                            >

                        @else

                            <div
                                class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3"
                                style="width:70px;height:70px;"
                            >
                                <i
                                    class="bx bx-user text-muted"
                                    style="font-size:35px;"
                                ></i>
                            </div>

                        @endif

                        <div>

                            <h5 class="mb-1">
                                {{ $student->full_name }}
                            </h5>

                            <p class="text-muted mb-0">
                                Student Number:
                                <strong>{{ $student->student_number }}</strong>
                            </p>

                        </div>

                        <div class="ms-auto">

                            <span class="badge bg-info font-size-13">
                                {{ $documents->total() }}
                                {{ $documents->total() === 1 ? 'Document' : 'Documents' }}
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Upload Document --}}
    <div class="row">

        <div class="col-lg-5">

            <div class="card">

                <div class="card-body">

                    <h5 class="card-title mb-4">
                        Upload Document
                    </h5>

                    <form
                        action="{{ route('admin.students.documents.store', $student) }}"
                        method="POST"
                        enctype="multipart/form-data"
                    >

                        @csrf

                        {{-- Document Type --}}
                        <div class="mb-3">

                            <label
                                for="document_type"
                                class="form-label"
                            >
                                Document Type
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                name="document_type"
                                id="document_type"
                                class="form-select @error('document_type') is-invalid @enderror"
                                required
                            >

                                <option value="">
                                    Select document type
                                </option>

                                <option
                                    value="Birth Certificate"
                                    @selected(old('document_type') === 'Birth Certificate')
                                >
                                    Birth Certificate
                                </option>

                                <option
                                    value="Passport"
                                    @selected(old('document_type') === 'Passport')
                                >
                                    Passport
                                </option>

                                <option
                                    value="National ID"
                                    @selected(old('document_type') === 'National ID')
                                >
                                    National ID
                                </option>

                                <option
                                    value="Admission Letter"
                                    @selected(old('document_type') === 'Admission Letter')
                                >
                                    Admission Letter
                                </option>

                                <option
                                    value="Previous School Record"
                                    @selected(old('document_type') === 'Previous School Record')
                                >
                                    Previous School Record
                                </option>

                                <option
                                    value="Medical Record"
                                    @selected(old('document_type') === 'Medical Record')
                                >
                                    Medical Record
                                </option>

                                <option
                                    value="Guardian ID"
                                    @selected(old('document_type') === 'Guardian ID')
                                >
                                    Guardian ID
                                </option>

                                <option
                                    value="Result / Transcript"
                                    @selected(old('document_type') === 'Result / Transcript')
                                >
                                    Result / Transcript
                                </option>

                                <option
                                    value="Other"
                                    @selected(old('document_type') === 'Other')
                                >
                                    Other
                                </option>

                            </select>

                            @error('document_type')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Document Name --}}
                        <div class="mb-3">

                            <label
                                for="document_name"
                                class="form-label"
                            >
                                Document Name
                            </label>

                            <input
                                type="text"
                                name="document_name"
                                id="document_name"
                                class="form-control @error('document_name') is-invalid @enderror"
                                value="{{ old('document_name') }}"
                                maxlength="255"
                                placeholder="e.g. Birth Certificate - John Doe"
                            >

                            <small class="text-muted">
                                Leave blank to use the uploaded file name.
                            </small>

                            @error('document_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- File --}}
                        <div class="mb-3">

                            <label
                                for="document"
                                class="form-label"
                            >
                                File
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="file"
                                name="document"
                                id="document"
                                class="form-control @error('document') is-invalid @enderror"
                                accept=".pdf,.jpg,.jpeg,.png,.webp,.doc,.docx"
                                required
                            >

                            <small class="text-muted">
                                PDF, JPG, JPEG, PNG, WEBP, DOC or DOCX.
                                Maximum size: 10 MB.
                            </small>

                            @error('document')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Description --}}
                        <div class="mb-4">

                            <label
                                for="description"
                                class="form-label"
                            >
                                Description
                            </label>

                            <textarea
                                name="description"
                                id="description"
                                rows="4"
                                class="form-control @error('description') is-invalid @enderror"
                                maxlength="5000"
                                placeholder="Optional description or notes about this document..."
                            >{{ old('description') }}</textarea>

                            @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Submit --}}
                        <div class="d-grid">

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                <i class="bx bx-upload me-1"></i>
                                Upload Document
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>


        {{-- Documents List --}}
        <div class="col-lg-7">

            <div class="card">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-4">

                        <h5 class="card-title mb-0">
                            Uploaded Documents
                        </h5>

                        <span class="badge bg-info">
                            {{ $documents->total() }}
                        </span>

                    </div>


                    @forelse($documents as $document)

                        <div class="border rounded p-3 mb-3">

                            <div class="d-flex align-items-start">

                                {{-- File Icon --}}
                                <div
                                    class="avatar-sm bg-light rounded d-flex align-items-center justify-content-center me-3"
                                >
                                    @php
                                        $extension = strtolower(
                                            pathinfo($document->file_path, PATHINFO_EXTENSION)
                                        );

                                        $icon = match($extension) {
                                            'pdf' => 'bxs-file-pdf',
                                            'doc', 'docx' => 'bxs-file-doc',
                                            'jpg', 'jpeg', 'png', 'webp' => 'bxs-file-image',
                                            default => 'bxs-file',
                                        };
                                    @endphp

                                    <i class="bx {{ $icon }} font-size-20"></i>

                                </div>


                                {{-- Details --}}
                                <div class="flex-grow-1">

                                    <h6 class="mb-1">

                                        {{ $document->document_name ?: basename($document->file_path) }}

                                    </h6>

                                    <div class="mb-2">

                                        <span class="badge bg-primary me-1">
                                            {{ $document->document_type }}
                                        </span>

                                        <span class="text-muted font-size-12">
                                            {{ strtoupper($extension) }}
                                        </span>

                                    </div>

                                    @if($document->description)

                                        <p class="text-muted mb-2">
                                            {{ $document->description }}
                                        </p>

                                    @endif

                                    <small class="text-muted">

                                        Uploaded
                                        {{ $document->created_at?->format('Y-m-d H:i') }}

                                        @if($document->uploadedBy)
                                            by {{ $document->uploadedBy->name }}
                                        @endif

                                    </small>

                                </div>


                                {{-- Actions --}}
                                <div class="ms-3 d-flex gap-2">

                                    <a
                                        href="{{ route('admin.students.documents.download', [$student, $document]) }}"
                                        class="btn btn-sm btn-outline-primary"
                                        title="Download"
                                    >
                                        <i class="bx bx-download"></i>
                                    </a>

                                    <form
                                        action="{{ route('admin.students.documents.destroy', [$student, $document]) }}"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this document?');"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                            title="Delete"
                                        >
                                            <i class="bx bx-trash"></i>
                                        </button>

                                    </form>

                                </div>

                            </div>

                        </div>

                    @empty

                        <div class="alert alert-info mb-0">

                            <i class="bx bx-info-circle me-1"></i>

                            No documents have been uploaded for this student yet.

                        </div>

                    @endforelse


                    {{-- Pagination --}}
                    @if($documents->hasPages())

                        <div class="mt-4">

                            {{ $documents->links() }}

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@endsection