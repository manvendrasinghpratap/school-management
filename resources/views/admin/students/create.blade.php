@extends('backend.layout.default')

@section('content')
<div class="container-fluid py-4">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Add Student</h1>
            <p class="text-muted mb-0">
                Register a new student in the school management system.
            </p>
        </div>

        <a href="{{ route('admin.students.index') }}"
           class="btn btn-outline-secondary">
            ← Back to Students
        </a>
    </div>

    {{-- Validation Errors --}}
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

    <form method="POST"
          action="{{ route('admin.students.store') }}"
          enctype="multipart/form-data">

        @csrf

        {{-- =========================================================
             STUDENT INFORMATION
        ========================================================== --}}
        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">
                <strong>Student Information</strong>
            </div>

            <div class="card-body">

                <div class="row g-3">

                    {{-- Student Number --}}
                    <div class="col-md-6">
                        <label for="student_number" class="form-label">
                            Student Number <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               id="student_number"
                               name="student_number"
                               value="{{ old('student_number') }}"
                               class="form-control @error('student_number') is-invalid @enderror"
                               required>

                        @error('student_number')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Admission Number --}}
                    <div class="col-md-6">
                        <label for="admission_number" class="form-label">
                            Admission Number
                        </label>

                        <input type="text"
                               id="admission_number"
                               name="admission_number"
                               value="{{ old('admission_number') }}"
                               class="form-control @error('admission_number') is-invalid @enderror">

                        @error('admission_number')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- First Name --}}
                    <div class="col-md-4">
                        <label for="first_name" class="form-label">
                            First Name <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               id="first_name"
                               name="first_name"
                               value="{{ old('first_name') }}"
                               class="form-control @error('first_name') is-invalid @enderror"
                               required>

                        @error('first_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Middle Name --}}
                    <div class="col-md-4">
                        <label for="middle_name" class="form-label">
                            Middle Name
                        </label>

                        <input type="text"
                               id="middle_name"
                               name="middle_name"
                               value="{{ old('middle_name') }}"
                               class="form-control @error('middle_name') is-invalid @enderror">

                        @error('middle_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Last Name --}}
                    <div class="col-md-4">
                        <label for="last_name" class="form-label">
                            Last Name <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               id="last_name"
                               name="last_name"
                               value="{{ old('last_name') }}"
                               class="form-control @error('last_name') is-invalid @enderror"
                               required>

                        @error('last_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Date of Birth --}}
                    <div class="col-md-4">
                        <label for="date_of_birth" class="form-label">
                            Date of Birth
                        </label>

                        <input type="date"
                               id="date_of_birth"
                               name="date_of_birth"
                               value="{{ old('date_of_birth') }}"
                               class="form-control @error('date_of_birth') is-invalid @enderror">

                        @error('date_of_birth')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Gender --}}
                    <div class="col-md-4">
                        <label for="gender" class="form-label">
                            Gender
                        </label>

                        <select id="gender"
                                name="gender"
                                class="form-select @error('gender') is-invalid @enderror">

                            <option value="">Select Gender</option>

                            <option value="male"
                                @selected(old('gender') === 'male')>
                                Male
                            </option>

                            <option value="female"
                                @selected(old('gender') === 'female')>
                                Female
                            </option>

                            <option value="other"
                                @selected(old('gender') === 'other')>
                                Other
                            </option>

                        </select>

                        @error('gender')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Nationality --}}
                    <div class="col-md-4">
                        <label for="nationality" class="form-label">
                            Nationality
                        </label>

                        <input type="text"
                               id="nationality"
                               name="nationality"
                               value="{{ old('nationality') }}"
                               class="form-control @error('nationality') is-invalid @enderror">

                        @error('nationality')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Phone --}}
                    <div class="col-md-6">
                        <label for="phone" class="form-label">
                            Phone
                        </label>

                        <input type="text"
                               id="phone"
                               name="phone"
                               value="{{ old('phone') }}"
                               class="form-control @error('phone') is-invalid @enderror">

                        @error('phone')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Admission Date --}}
                    <div class="col-md-6">
                        <label for="admission_date" class="form-label">
                            Admission Date <span class="text-danger">*</span>
                        </label>

                        <input type="date"
                               id="admission_date"
                               name="admission_date"
                               value="{{ old('admission_date', now()->format('Y-m-d')) }}"
                               class="form-control @error('admission_date') is-invalid @enderror"
                               required>

                        @error('admission_date')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div class="col-md-6">
                        <label for="status" class="form-label">
                            Status
                        </label>

                        <select id="status"
                                name="status"
                                class="form-select @error('status') is-invalid @enderror">

                            <option value="active"
                                @selected(old('status', 'active') === 'active')>
                                Active
                            </option>

                            <option value="inactive"
                                @selected(old('status') === 'inactive')>
                                Inactive
                            </option>

                            <option value="transferred"
                                @selected(old('status') === 'transferred')>
                                Transferred
                            </option>

                            <option value="withdrawn"
                                @selected(old('status') === 'withdrawn')>
                                Withdrawn
                            </option>

                        </select>

                        @error('status')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Photo --}}
                    <div class="col-md-6">
                        <label for="photo" class="form-label">
                            Student Photo
                        </label>

                        <input type="file"
                               id="photo"
                               name="photo"
                               accept=".jpg,.jpeg,.png,.webp"
                               class="form-control @error('photo') is-invalid @enderror">

                        <div class="form-text">
                            JPG, JPEG, PNG or WEBP. Maximum 2 MB.
                        </div>

                        @error('photo')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Address --}}
                    <div class="col-12">
                        <label for="address" class="form-label">
                            Address
                        </label>

                        <textarea id="address"
                                  name="address"
                                  rows="3"
                                  class="form-control @error('address') is-invalid @enderror"
                                  placeholder="Student residential address">{{ old('address') }}</textarea>

                        @error('address')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                </div>

            </div>
        </div>


        {{-- =========================================================
             PARENT / GUARDIAN INFORMATION
        ========================================================== --}}
        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <strong>Parent / Guardian</strong>

                        <div class="text-muted small mt-1">
                            Select one or more guardians and define their relationship to the student.
                        </div>
                    </div>

                </div>
            </div>

            <div class="card-body">

                @if($guardians->count())

                    @php
                        $oldGuardians = old('guardians', []);
                    @endphp

                    <div id="guardian-container">

                        {{-- Existing / old guardian rows --}}
                        @if(count($oldGuardians))

                            @foreach($oldGuardians as $index => $oldGuardian)

                                <div class="guardian-row border rounded p-3 mb-3">

                                    <div class="row g-3 align-items-end">

                                        {{-- Guardian --}}
                                        <div class="col-md-4">

                                            <label class="form-label">
                                                Guardian <span class="text-danger">*</span>
                                            </label>

                                            <select
                                                name="guardians[{{ $index }}][id]"
                                                class="form-select @error('guardians.'.$index.'.id') is-invalid @enderror"
                                                required
                                            >

                                                <option value="">
                                                    Select Guardian
                                                </option>

                                                @foreach($guardians as $guardian)

                                                    <option
                                                        value="{{ $guardian->id }}"
                                                        @selected((string)($oldGuardian['id'] ?? '') === (string)$guardian->id)
                                                    >
                                                        {{ $guardian->full_name }}

                                                        @if($guardian->phone)
                                                            — {{ $guardian->phone }}
                                                        @endif
                                                    </option>

                                                @endforeach

                                            </select>

                                            @error('guardians.'.$index.'.id')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror

                                        </div>


                                        {{-- Relationship --}}
                                        <div class="col-md-3">

                                            <label class="form-label">
                                                Relationship <span class="text-danger">*</span>
                                            </label>

                                            <select
                                                name="guardians[{{ $index }}][relationship]"
                                                class="form-select @error('guardians.'.$index.'.relationship') is-invalid @enderror"
                                                required
                                            >

                                                <option value="">
                                                    Select Relationship
                                                </option>

                                                <option value="Father"
                                                    @selected(($oldGuardian['relationship'] ?? '') === 'Father')>
                                                    Father
                                                </option>

                                                <option value="Mother"
                                                    @selected(($oldGuardian['relationship'] ?? '') === 'Mother')>
                                                    Mother
                                                </option>

                                                <option value="Stepfather"
                                                    @selected(($oldGuardian['relationship'] ?? '') === 'Stepfather')>
                                                    Stepfather
                                                </option>

                                                <option value="Stepmother"
                                                    @selected(($oldGuardian['relationship'] ?? '') === 'Stepmother')>
                                                    Stepmother
                                                </option>

                                                <option value="Grandfather"
                                                    @selected(($oldGuardian['relationship'] ?? '') === 'Grandfather')>
                                                    Grandfather
                                                </option>

                                                <option value="Grandmother"
                                                    @selected(($oldGuardian['relationship'] ?? '') === 'Grandmother')>
                                                    Grandmother
                                                </option>

                                                <option value="Uncle"
                                                    @selected(($oldGuardian['relationship'] ?? '') === 'Uncle')>
                                                    Uncle
                                                </option>

                                                <option value="Aunt"
                                                    @selected(($oldGuardian['relationship'] ?? '') === 'Aunt')>
                                                    Aunt
                                                </option>

                                                <option value="Sibling"
                                                    @selected(($oldGuardian['relationship'] ?? '') === 'Sibling')>
                                                    Sibling
                                                </option>

                                                <option value="Legal Guardian"
                                                    @selected(($oldGuardian['relationship'] ?? '') === 'Legal Guardian')>
                                                    Legal Guardian
                                                </option>

                                                <option value="Other"
                                                    @selected(($oldGuardian['relationship'] ?? '') === 'Other')>
                                                    Other
                                                </option>

                                            </select>

                                            @error('guardians.'.$index.'.relationship')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror

                                        </div>


                                        {{-- Primary --}}
                                        <div class="col-md-2">

                                            <div class="form-check mt-2">

                                                <input
                                                    type="hidden"
                                                    name="guardians[{{ $index }}][is_primary]"
                                                    value="0"
                                                >

                                                <input
                                                    type="checkbox"
                                                    name="guardians[{{ $index }}][is_primary]"
                                                    value="1"
                                                    class="form-check-input"
                                                    id="guardian_primary_{{ $index }}"
                                                    @checked(!empty($oldGuardian['is_primary']))
                                                >

                                                <label
                                                    class="form-check-label"
                                                    for="guardian_primary_{{ $index }}"
                                                >
                                                    Primary
                                                </label>

                                            </div>

                                        </div>


                                        {{-- Emergency --}}
                                        <div class="col-md-2">

                                            <div class="form-check mt-2">

                                                <input
                                                    type="hidden"
                                                    name="guardians[{{ $index }}][is_emergency_contact]"
                                                    value="0"
                                                >

                                                <input
                                                    type="checkbox"
                                                    name="guardians[{{ $index }}][is_emergency_contact]"
                                                    value="1"
                                                    class="form-check-input"
                                                    id="guardian_emergency_{{ $index }}"
                                                    @checked(!empty($oldGuardian['is_emergency_contact']))
                                                >

                                                <label
                                                    class="form-check-label"
                                                    for="guardian_emergency_{{ $index }}"
                                                >
                                                    Emergency
                                                </label>

                                            </div>

                                        </div>


                                        {{-- Remove --}}
                                        <div class="col-md-1">

                                            <button
                                                type="button"
                                                class="btn btn-outline-danger btn-sm remove-guardian"
                                                title="Remove guardian"
                                            >
                                                ×
                                            </button>

                                        </div>

                                    </div>

                                </div>

                            @endforeach

                        @else

                            {{-- First guardian row --}}
                            <div class="guardian-row border rounded p-3 mb-3">

                                <div class="row g-3 align-items-end">

                                    {{-- Guardian --}}
                                    <div class="col-md-4">

                                        <label class="form-label">
                                            Guardian <span class="text-danger">*</span>
                                        </label>

                                        <select
                                            name="guardians[0][id]"
                                            class="form-select"
                                        >

                                            <option value="">
                                                Select Guardian
                                            </option>

                                            @foreach($guardians as $guardian)

                                                <option value="{{ $guardian->id }}">
                                                    {{ $guardian->full_name }}

                                                    @if($guardian->phone)
                                                        — {{ $guardian->phone }}
                                                    @endif
                                                </option>

                                            @endforeach

                                        </select>

                                    </div>


                                    {{-- Relationship --}}
                                    <div class="col-md-3">

                                        <label class="form-label">
                                            Relationship
                                        </label>

                                        <select
                                            name="guardians[0][relationship]"
                                            class="form-select"
                                        >

                                            <option value="">
                                                Select Relationship
                                            </option>

                                            <option value="Father">Father</option>
                                            <option value="Mother">Mother</option>
                                            <option value="Stepfather">Stepfather</option>
                                            <option value="Stepmother">Stepmother</option>
                                            <option value="Grandfather">Grandfather</option>
                                            <option value="Grandmother">Grandmother</option>
                                            <option value="Uncle">Uncle</option>
                                            <option value="Aunt">Aunt</option>
                                            <option value="Sibling">Sibling</option>
                                            <option value="Legal Guardian">Legal Guardian</option>
                                            <option value="Other">Other</option>

                                        </select>

                                    </div>


                                    {{-- Primary --}}
                                    <div class="col-md-2">

                                        <div class="form-check">

                                            <input
                                                type="hidden"
                                                name="guardians[0][is_primary]"
                                                value="0"
                                            >

                                            <input
                                                type="checkbox"
                                                name="guardians[0][is_primary]"
                                                value="1"
                                                class="form-check-input"
                                                id="guardian_primary_0"
                                            >

                                            <label
                                                class="form-check-label"
                                                for="guardian_primary_0"
                                            >
                                                Primary
                                            </label>

                                        </div>

                                    </div>


                                    {{-- Emergency --}}
                                    <div class="col-md-2">

                                        <div class="form-check">

                                            <input
                                                type="hidden"
                                                name="guardians[0][is_emergency_contact]"
                                                value="0"
                                            >

                                            <input
                                                type="checkbox"
                                                name="guardians[0][is_emergency_contact]"
                                                value="1"
                                                class="form-check-input"
                                                id="guardian_emergency_0"
                                            >

                                            <label
                                                class="form-check-label"
                                                for="guardian_emergency_0"
                                            >
                                                Emergency
                                            </label>

                                        </div>

                                    </div>


                                    {{-- Remove --}}
                                    <div class="col-md-1">

                                        <button
                                            type="button"
                                            class="btn btn-outline-danger btn-sm remove-guardian"
                                            title="Remove guardian"
                                        >
                                            ×
                                        </button>

                                    </div>

                                </div>

                            </div>

                        @endif

                    </div>


                    {{-- Add Guardian --}}
                    <button
                        type="button"
                        id="add-guardian"
                        class="btn btn-outline-primary"
                    >
                        + Add Another Guardian
                    </button>

                    <div class="form-text mt-2">
                        A student may have multiple guardians. Each guardian must have a relationship.
                    </div>

                @else

                    <div class="alert alert-info mb-0">

                        No guardians have been registered yet.

                        <br>

                        <a
                            href="{{ route('admin.guardians.create') }}"
                            class="alert-link"
                        >
                            Register a parent/guardian first.
                        </a>

                    </div>

                @endif

            </div>
        </div>


        {{-- =========================================================
             FORM ACTIONS
        ========================================================== --}}
        <div class="d-flex justify-content-end gap-2 mb-4">

            <a href="{{ route('admin.students.index') }}"
               class="btn btn-outline-secondary">
                Cancel
            </a>

            <button type="submit"
                    class="btn btn-primary">
                Register Student
            </button>

        </div>

    </form>

</div>


{{-- =============================================================
     GUARDIAN ROW JAVASCRIPT
============================================================== --}}
@if($guardians->count())

<script>
document.addEventListener('DOMContentLoaded', function () {

    const container = document.getElementById('guardian-container');
    const addButton = document.getElementById('add-guardian');

    if (!container || !addButton) {
        return;
    }

    let guardianIndex = container.querySelectorAll('.guardian-row').length;

    function guardianOptions() {
        return `
            <option value="">Select Guardian</option>

            @foreach($guardians as $guardian)
                <option value="{{ $guardian->id }}">
                    {{ $guardian->full_name }}
                    @if($guardian->phone)
                        — {{ $guardian->phone }}
                    @endif
                </option>
            @endforeach
        `;
    }

    function relationshipOptions() {
        return `
            <option value="">Select Relationship</option>
            <option value="Father">Father</option>
            <option value="Mother">Mother</option>
            <option value="Stepfather">Stepfather</option>
            <option value="Stepmother">Stepmother</option>
            <option value="Grandfather">Grandfather</option>
            <option value="Grandmother">Grandmother</option>
            <option value="Uncle">Uncle</option>
            <option value="Aunt">Aunt</option>
            <option value="Sibling">Sibling</option>
            <option value="Legal Guardian">Legal Guardian</option>
            <option value="Other">Other</option>
        `;
    }

    addButton.addEventListener('click', function () {

        const row = document.createElement('div');

        row.className = 'guardian-row border rounded p-3 mb-3';

        row.innerHTML = `
            <div class="row g-3 align-items-end">

                <div class="col-md-4">

                    <label class="form-label">
                        Guardian <span class="text-danger">*</span>
                    </label>

                    <select
                        name="guardians[${guardianIndex}][id]"
                        class="form-select"
                        required
                    >
                        ${guardianOptions()}
                    </select>

                </div>

                <div class="col-md-3">

                    <label class="form-label">
                        Relationship <span class="text-danger">*</span>
                    </label>

                    <select
                        name="guardians[${guardianIndex}][relationship]"
                        class="form-select"
                        required
                    >
                        ${relationshipOptions()}
                    </select>

                </div>

                <div class="col-md-2">

                    <div class="form-check">

                        <input
                            type="hidden"
                            name="guardians[${guardianIndex}][is_primary]"
                            value="0"
                        >

                        <input
                            type="checkbox"
                            name="guardians[${guardianIndex}][is_primary]"
                            value="1"
                            class="form-check-input"
                            id="guardian_primary_${guardianIndex}"
                        >

                        <label
                            class="form-check-label"
                            for="guardian_primary_${guardianIndex}"
                        >
                            Primary
                        </label>

                    </div>

                </div>

                <div class="col-md-2">

                    <div class="form-check">

                        <input
                            type="hidden"
                            name="guardians[${guardianIndex}][is_emergency_contact]"
                            value="0"
                        >

                        <input
                            type="checkbox"
                            name="guardians[${guardianIndex}][is_emergency_contact]"
                            value="1"
                            class="form-check-input"
                            id="guardian_emergency_${guardianIndex}"
                        >

                        <label
                            class="form-check-label"
                            for="guardian_emergency_${guardianIndex}"
                        >
                            Emergency
                        </label>

                    </div>

                </div>

                <div class="col-md-1">

                    <button
                        type="button"
                        class="btn btn-outline-danger btn-sm remove-guardian"
                        title="Remove guardian"
                    >
                        ×
                    </button>

                </div>

            </div>
        `;

        container.appendChild(row);

        guardianIndex++;
    });


    container.addEventListener('click', function (event) {

        const removeButton = event.target.closest('.remove-guardian');

        if (!removeButton) {
            return;
        }

        const rows = container.querySelectorAll('.guardian-row');

        if (rows.length <= 1) {

            const row = removeButton.closest('.guardian-row');

            const guardianSelect = row.querySelector(
                'select[name*="[id]"]'
            );

            const relationshipSelect = row.querySelector(
                'select[name*="[relationship]"]'
            );

            if (guardianSelect) {
                guardianSelect.value = '';
            }

            if (relationshipSelect) {
                relationshipSelect.value = '';
            }

            const primaryCheckbox = row.querySelector(
                'input[type="checkbox"][name*="[is_primary]"]'
            );

            const emergencyCheckbox = row.querySelector(
                'input[type="checkbox"][name*="[is_emergency_contact]"]'
            );

            if (primaryCheckbox) {
                primaryCheckbox.checked = false;
            }

            if (emergencyCheckbox) {
                emergencyCheckbox.checked = false;
            }

            return;
        }

        removeButton.closest('.guardian-row').remove();
    });

});
</script>

@endif

@endsection