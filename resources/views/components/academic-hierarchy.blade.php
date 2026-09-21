@props([
    'academicYears' => collect(),
    'currentAcademicYear' => null,
    'academicYearId' => null,
    'classId' => null,
    'sectionId' => null,
    'studentId' => null,
    'academicYearName' => 'academic_year_id',
    'className' => 'class_id',
    'sectionName' => 'section_id',
    'studentName' => 'student_id',
    'showAcademicYear' => true,
    'showClass' => true,
    'showSection' => true,
    'showStudent' => true,
    'required' => true,
    'studentPlaceholder' => 'Select Student',
])

@php
    $selectedAcademicYearId = old(
        $academicYearName,
        $academicYearId ?? optional($currentAcademicYear)->id
    );

    $selectedClassId = old($className, $classId);
    $selectedSectionId = old($sectionName, $sectionId);
    $selectedStudentId = old($studentName, $studentId);
@endphp

<div
    class="row g-3 academic-hierarchy"
    data-academic-hierarchy
    data-classes-url="{{ route('admin.academic-hierarchy.classes') }}"
    data-sections-url="{{ route('admin.academic-hierarchy.sections') }}"
    data-students-url="{{ route('admin.academic-hierarchy.students') }}"
    data-selected-academic-year="{{ $selectedAcademicYearId }}"
    data-selected-class="{{ $selectedClassId }}"
    data-selected-section="{{ $selectedSectionId }}"
    data-selected-student="{{ $selectedStudentId }}"
>
    @if($showAcademicYear)
        <div class="col-md-{{ $showClass ? '3' : '12' }}">
            <label class="form-label">
                Academic Year
                @if($required)<span class="text-danger">*</span>@endif
            </label>

            <select
                name="{{ $academicYearName }}"
                id="{{ $academicYearName }}"
                class="form-select"
                {{ $required ? 'required' : '' }}
                data-academic-year
            >
                <option value="">Select Academic Year</option>

                @foreach($academicYears as $year)
                    <option
                        value="{{ $year->id }}"
                        @selected((string) $selectedAcademicYearId === (string) $year->id)
                    >
                        {{ $year->name }}
                        @if($year->is_current)
                            (Current)
                        @endif
                    </option>
                @endforeach
            </select>
        </div>
    @endif

    @if($showClass)
        <div class="col-md-3">
            <label class="form-label">
                Class
                @if($required)<span class="text-danger">*</span>@endif
            </label>

            <select
                name="{{ $className }}"
                id="{{ $className }}"
                class="form-select"
                {{ $required ? 'required' : '' }}
                data-class
                disabled
            >
                <option value="">Select Academic Year First</option>
            </select>
        </div>
    @endif

    @if($showSection)
        <div class="col-md-3">
            <label class="form-label">
                Section
                @if($required)<span class="text-danger">*</span>@endif
            </label>

            <select
                name="{{ $sectionName }}"
                id="{{ $sectionName }}"
                class="form-select"
                {{ $required ? 'required' : '' }}
                data-section
                disabled
            >
                <option value="">Select Class First</option>
            </select>
        </div>
    @endif

    @if($showStudent)
        <div class="col-md-3">
            <label class="form-label">
                Student
                @if($required)<span class="text-danger">*</span>@endif
            </label>

            <select
                name="{{ $studentName }}"
                id="{{ $studentName }}"
                class="form-select"
                {{ $required ? 'required' : '' }}
                data-student
                disabled
            >
                <option value="">{{ $studentPlaceholder }}</option>
            </select>
        </div>
    @endif
</div>
