<div class="row g-3 my-2">

    <div class="col-md-6">
        <label class="form-label">Route <span class="text-danger">*</span></label>
        <select name="route_id" class="form-select" required>
            <option value="">Select route</option>

            @foreach($routes as $r)
                <option
                    value="{{ $r->id }}"
                    @selected(old('route_id', $assignment->route_id ?? '') == $r->id)
                >
                    {{ $r->name }}
                    @if($r->code)
                        ({{ $r->code }})
                    @endif
                </option>
            @endforeach
        </select>
        @error('route_id')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <div class="card border">
            <div class="card-header">
                <h5 class="card-title mb-0">Student Selection</h5>
                <p class="text-muted mb-0 small">
                    Select the academic hierarchy first. Only students actively
                    enrolled in the selected combination will be available.
                </p>
            </div>

            <div class="card-body">
                <x-academic-hierarchy
                    :academic-years="$academicYears"
                    :current-academic-year="$currentAcademicYear"
                    :academic-year-id="$academicHierarchy['academic_year_id'] ?? null"
                    :class-id="$academicHierarchy['class_id'] ?? null"
                    :section-id="$academicHierarchy['section_id'] ?? null"
                    :student-id="$academicHierarchy['student_id'] ?? ($assignment->student_id ?? null)"
                />

                @error('academic_year_id')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror

                @error('class_id')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror

                @error('section_id')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror

                @error('student_id')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <label class="form-label">Pickup Point</label>
        <input
            name="pickup_point"
            class="form-control"
            value="{{ old('pickup_point', $assignment->pickup_point ?? '') }}"
        >
        @error('pickup_point')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Dropoff Point</label>
        <input
            name="dropoff_point"
            class="form-control"
            value="{{ old('dropoff_point', $assignment->dropoff_point ?? '') }}"
        >
        @error('dropoff_point')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Start Date</label>
        <input
            type="date"
            name="start_date"
            class="form-control"
            value="{{ old(
                'start_date',
                isset($assignment) && $assignment->start_date
                    ? $assignment->start_date->format('Y-m-d')
                    : ''
            ) }}"
        >
        @error('start_date')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">End Date</label>
        <input
            type="date"
            name="end_date"
            class="form-control"
            value="{{ old(
                'end_date',
                isset($assignment) && $assignment->end_date
                    ? $assignment->end_date->format('Y-m-d')
                    : ''
            ) }}"
        >
        @error('end_date')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Status <span class="text-danger">*</span></label>
        <select name="status" class="form-select" required>
            @foreach(['active', 'inactive'] as $s)
                <option
                    value="{{ $s }}"
                    @selected(old('status', $assignment->status ?? 'active') === $s)
                >
                    {{ ucfirst($s) }}
                </option>
            @endforeach
        </select>
        @error('status')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>
</div>

@once
    <script src="{{ asset('assets/js/academic-hierarchy.js') }}"></script>
@endonce
