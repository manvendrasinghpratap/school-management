@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row">
        <div class="col-12">

            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0 font-size-18">
                    Edit Student
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

    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <h6 class="alert-heading">
                Please correct the following errors:
            </h6>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif

    <form
        action="{{ route('admin.students.update', $student) }}"
        method="POST"
        enctype="multipart/form-data"
    >

        @csrf
        @method('PUT')

        {{-- Student Information --}}
        <div class="card">

            <div class="card-body">

                <h5 class="card-title mb-4">
                    Student Information
                </h5>

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Student Number <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="student_number"
                            class="form-control @error('student_number') is-invalid @enderror"
                            value="{{ old('student_number', $student->student_number) }}"
                            required
                        >

                        @error('student_number')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Admission Number
                        </label>

                        <input
                            type="text"
                            name="admission_number"
                            class="form-control @error('admission_number') is-invalid @enderror"
                            value="{{ old('admission_number', $student->admission_number) }}"
                        >

                        @error('admission_number')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            First Name <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="first_name"
                            class="form-control @error('first_name') is-invalid @enderror"
                            value="{{ old('first_name', $student->first_name) }}"
                            required
                        >

                        @error('first_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Middle Name
                        </label>

                        <input
                            type="text"
                            name="middle_name"
                            class="form-control"
                            value="{{ old('middle_name', $student->middle_name) }}"
                        >

                    </div>

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Last Name <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="last_name"
                            class="form-control @error('last_name') is-invalid @enderror"
                            value="{{ old('last_name', $student->last_name) }}"
                            required
                        >

                        @error('last_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Date of Birth
                        </label>

                        <input
                            type="date"
                            name="date_of_birth"
                            class="form-control"
                            value="{{ old(
                                'date_of_birth',
                                $student->date_of_birth?->format('Y-m-d')
                            ) }}"
                        >

                    </div>

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Gender
                        </label>

                        <select
                            name="gender"
                            class="form-select"
                        >

                            <option value="">
                                Select Gender
                            </option>

                            <option
                                value="male"
                                @selected(old('gender', $student->gender) === 'male')
                            >
                                Male
                            </option>

                            <option
                                value="female"
                                @selected(old('gender', $student->gender) === 'female')
                            >
                                Female
                            </option>

                            <option
                                value="other"
                                @selected(old('gender', $student->gender) === 'other')
                            >
                                Other
                            </option>

                        </select>

                    </div>

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Nationality
                        </label>

                        <input
                            type="text"
                            name="nationality"
                            class="form-control"
                            value="{{ old('nationality', $student->nationality) }}"
                        >

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Phone
                        </label>

                        <input
                            type="text"
                            name="phone"
                            class="form-control"
                            value="{{ old('phone', $student->phone) }}"
                        >

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Admission Date <span class="text-danger">*</span>
                        </label>

                        <input
                            type="date"
                            name="admission_date"
                            class="form-control"
                            value="{{ old(
                                'admission_date',
                                $student->admission_date?->format('Y-m-d')
                            ) }}"
                            required
                        >

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Status
                        </label>

                        <select
                            name="status"
                            class="form-select"
                        >

                            @foreach([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                                'graduated' => 'Graduated',
                                'transferred' => 'Transferred',
                                'withdrawn' => 'Withdrawn',
                            ] as $value => $label)

                                <option
                                    value="{{ $value }}"
                                    @selected(old('status', $student->status) === $value)
                                >
                                    {{ $label }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Student Photo
                        </label>

                        <input
                            type="file"
                            name="photo"
                            class="form-control @error('photo') is-invalid @enderror"
                            accept=".jpg,.jpeg,.png,.webp"
                        >

                        @error('photo')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                        @if($student->photo)

                            <div class="mt-2">

                                <img
                                    src="{{ asset('storage/' . $student->photo) }}"
                                    alt="Current photo"
                                    style="width:80px;height:80px;object-fit:cover;"
                                    class="rounded"
                                >

                            </div>

                        @endif

                    </div>

                    <div class="col-12 mb-3">

                        <label class="form-label">
                            Address
                        </label>

                        <textarea
                            name="address"
                            rows="3"
                            class="form-control"
                        >{{ old('address', $student->address) }}</textarea>

                    </div>

                </div>

            </div>

        </div>

        {{-- Guardians --}}
        <div class="card">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-4">

                    <div>
                        <h5 class="card-title mb-1">
                            Guardians
                        </h5>

                        <p class="text-muted mb-0">
                            Assign one or more guardians to this student.
                        </p>
                    </div>

                    <button
                        type="button"
                        class="btn btn-primary"
                        id="addGuardian"
                    >
                        <i class="bx bx-plus"></i>
                        Add Guardian
                    </button>

                </div>

                <div id="guardiansContainer">

                    @php
                        $oldGuardians = old('guardians');

                        if ($oldGuardians !== null) {
                            $guardianRows = $oldGuardians;
                        } else {
                            $guardianRows = $student->guardians->map(function ($guardian) {
                                return [
                                    'id' => $guardian->id,
                                    'relationship' => $guardian->pivot->relationship,
                                    'is_primary' => $guardian->pivot->is_primary ? 1 : 0,
                                    'is_emergency_contact' => $guardian->pivot->is_emergency_contact ? 1 : 0,
                                ];
                            })->values()->all();
                        }
                    @endphp

                    @forelse($guardianRows as $index => $guardianRow)

                        <div
                            class="guardian-row border rounded p-3 mb-3"
                            data-index="{{ $index }}"
                        >

                            <div class="row">

                                <div class="col-md-4 mb-3">

                                    <label class="form-label">
                                        Guardian
                                        <span class="text-danger">*</span>
                                    </label>

                                    <select
                                        name="guardians[{{ $index }}][id]"
                                        class="form-select"
                                        required
                                    >

                                        <option value="">
                                            Select Guardian
                                        </option>

                                        @foreach($guardians as $guardian)

                                            <option
                                                value="{{ $guardian->id }}"
                                                @selected(
                                                    (int)($guardianRow['id'] ?? 0)
                                                    === (int)$guardian->id
                                                )
                                            >
                                                {{ $guardian->full_name }}
                                                — {{ $guardian->guardian_number }}
                                            </option>

                                        @endforeach

                                    </select>

                                    @error("guardians.{$index}.id")
                                        <div class="text-danger small mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                <div class="col-md-4 mb-3">

                                    <label class="form-label">
                                        Relationship
                                        <span class="text-danger">*</span>
                                    </label>

                                    <select
                                        name="guardians[{{ $index }}][relationship]"
                                        class="form-select"
                                        required
                                    >

                                        <option value="">
                                            Select Relationship
                                        </option>

                                        @foreach([
                                            'Father',
                                            'Mother',
                                            'Stepfather',
                                            'Stepmother',
                                            'Grandfather',
                                            'Grandmother',
                                            'Uncle',
                                            'Aunt',
                                            'Sibling',
                                            'Legal Guardian',
                                            'Other',
                                        ] as $relationship)

                                            <option
                                                value="{{ $relationship }}"
                                                @selected(
                                                    ($guardianRow['relationship'] ?? '')
                                                    === $relationship
                                                )
                                            >
                                                {{ $relationship }}
                                            </option>

                                        @endforeach

                                    </select>

                                    @error("guardians.{$index}.relationship")
                                        <div class="text-danger small mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                <div class="col-md-4 mb-3">

                                    <label class="form-label d-block">
                                        Options
                                    </label>

                                    <div class="form-check mb-2">

                                        <input
                                            type="hidden"
                                            name="guardians[{{ $index }}][is_primary]"
                                            value="0"
                                        >

                                        <input
                                            type="checkbox"
                                            class="form-check-input"
                                            name="guardians[{{ $index }}][is_primary]"
                                            value="1"
                                            @checked(!empty($guardianRow['is_primary']))
                                        >

                                        <label class="form-check-label">
                                            Primary Guardian
                                        </label>

                                    </div>

                                    <div class="form-check">

                                        <input
                                            type="hidden"
                                            name="guardians[{{ $index }}][is_emergency_contact]"
                                            value="0"
                                        >

                                        <input
                                            type="checkbox"
                                            class="form-check-input"
                                            name="guardians[{{ $index }}][is_emergency_contact]"
                                            value="1"
                                            @checked(!empty($guardianRow['is_emergency_contact']))
                                        >

                                        <label class="form-check-label">
                                            Emergency Contact
                                        </label>

                                    </div>

                                </div>

                            </div>

                            <div class="text-end">

                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-danger removeGuardian"
                                >
                                    <i class="bx bx-trash"></i>
                                    Remove
                                </button>

                            </div>

                        </div>

                    @empty

                        {{-- No existing guardians --}}

                    @endforelse

                </div>

                @if($student->guardians->isEmpty() && !$oldGuardians)

                    <div
                        id="noGuardianMessage"
                        class="alert alert-light border"
                    >
                        No guardians assigned yet. Click
                        <strong>Add Guardian</strong>
                        to assign one.
                    </div>

                @endif

            </div>

        </div>

        {{-- Actions --}}
        <div class="card">

            <div class="card-body">

                <div class="d-flex justify-content-end gap-2">

                    <a
                        href="{{ route('admin.students.show', $student) }}"
                        class="btn btn-light"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="bx bx-save"></i>
                        Update Student
                    </button>

                </div>

            </div>

        </div>

    </form>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const container = document.getElementById('guardiansContainer');
    const addButton = document.getElementById('addGuardian');
    const noGuardianMessage = document.getElementById('noGuardianMessage');

    let guardianIndex = {{ count($guardianRows) }};

    const relationshipOptions = [
        'Father',
        'Mother',
        'Stepfather',
        'Stepmother',
        'Grandfather',
        'Grandmother',
        'Uncle',
        'Aunt',
        'Sibling',
        'Legal Guardian',
        'Other'
    ];

    const guardianOptions = `
        <option value="">Select Guardian</option>
        @foreach($guardians as $guardian)
            <option value="{{ $guardian->id }}">
                {{ addslashes($guardian->full_name) }}
                — {{ addslashes($guardian->guardian_number) }}
            </option>
        @endforeach
    `;

    const relationshipSelectOptions = relationshipOptions
        .map(function (relationship) {
            return `<option value="${relationship}">${relationship}</option>`;
        })
        .join('');

    function updateNoGuardianMessage() {
        const rows = container.querySelectorAll('.guardian-row');

        if (rows.length === 0) {

            if (!document.getElementById('noGuardianMessage')) {

                const message = document.createElement('div');

                message.id = 'noGuardianMessage';
                message.className = 'alert alert-light border';

                message.innerHTML =
                    'No guardians assigned yet. Click <strong>Add Guardian</strong> to assign one.';

                container.parentNode.insertBefore(
                    message,
                    container.nextSibling
                );
            }

        } else {

            const message =
                document.getElementById('noGuardianMessage');

            if (message) {
                message.remove();
            }
        }
    }

    function addGuardianRow() {

        const index = guardianIndex++;

        const row = document.createElement('div');

        row.className =
            'guardian-row border rounded p-3 mb-3';

        row.dataset.index = index;

        row.innerHTML = `
            <div class="row">

                <div class="col-md-4 mb-3">

                    <label class="form-label">
                        Guardian
                        <span class="text-danger">*</span>
                    </label>

                    <select
                        name="guardians[${index}][id]"
                        class="form-select"
                        required
                    >
                        ${guardianOptions}
                    </select>

                </div>

                <div class="col-md-4 mb-3">

                    <label class="form-label">
                        Relationship
                        <span class="text-danger">*</span>
                    </label>

                    <select
                        name="guardians[${index}][relationship]"
                        class="form-select"
                        required
                    >
                        <option value="">
                            Select Relationship
                        </option>

                        ${relationshipSelectOptions}
                    </select>

                </div>

                <div class="col-md-4 mb-3">

                    <label class="form-label d-block">
                        Options
                    </label>

                    <div class="form-check mb-2">

                        <input
                            type="hidden"
                            name="guardians[${index}][is_primary]"
                            value="0"
                        >

                        <input
                            type="checkbox"
                            class="form-check-input"
                            name="guardians[${index}][is_primary]"
                            value="1"
                        >

                        <label class="form-check-label">
                            Primary Guardian
                        </label>

                    </div>

                    <div class="form-check">

                        <input
                            type="hidden"
                            name="guardians[${index}][is_emergency_contact]"
                            value="0"
                        >

                        <input
                            type="checkbox"
                            class="form-check-input"
                            name="guardians[${index}][is_emergency_contact]"
                            value="1"
                        >

                        <label class="form-check-label">
                            Emergency Contact
                        </label>

                    </div>

                </div>

            </div>

            <div class="text-end">

                <button
                    type="button"
                    class="btn btn-sm btn-outline-danger removeGuardian"
                >
                    <i class="bx bx-trash"></i>
                    Remove
                </button>

            </div>
        `;

        container.appendChild(row);

        updateNoGuardianMessage();
    }

    addButton.addEventListener('click', function () {
        addGuardianRow();
    });

    container.addEventListener('click', function (event) {

        const removeButton =
            event.target.closest('.removeGuardian');

        if (!removeButton) {
            return;
        }

        const row =
            removeButton.closest('.guardian-row');

        if (row) {
            row.remove();
        }

        updateNoGuardianMessage();
    });

});
</script>

@endsection