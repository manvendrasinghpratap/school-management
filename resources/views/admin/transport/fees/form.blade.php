<div class="row g-3">
    <div class="col-12">
        <x-academic-hierarchy
            :academic-years="$academicYears"
            :current-academic-year="$currentAcademicYear"
            :academic-year-id="$academicHierarchy['academic_year_id'] ?? null"
            :class-id="$academicHierarchy['class_id'] ?? null"
            :section-id="$academicHierarchy['section_id'] ?? null"
            :student-id="$academicHierarchy['student_id'] ?? null"
        />
    </div>

    <div class="col-md-6">
        <label class="form-label">Route Assignment <span class="text-danger">*</span></label>
        <select
            name="route_student_id"
            id="transport_fee_route_student_id"
            class="form-select"
            required
            data-selected="{{ old('route_student_id', $fee->route_student_id ?? '') }}"
        >
            <option value="">Select academic hierarchy first</option>
        </select>
        @error('route_student_id')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
        <div id="transport-fee-assignment-help" class="form-text">
            Select Academic Year, Class, Section and Student to load active route assignments.
        </div>
    </div>

    <div class="col-md-6">
        <label class="form-label">Fee Month <span class="text-danger">*</span></label>
        <input
            type="date"
            name="fee_month"
            class="form-control"
            value="{{ old('fee_month', isset($fee) && $fee->fee_month ? $fee->fee_month->format('Y-m-d') : now()->format('Y-m-d')) }}"
            required
        >
        @error('fee_month')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Amount <span class="text-danger">*</span></label>
        <input
            type="number"
            name="amount"
            class="form-control"
            min="0.01"
            step="0.01"
            value="{{ old('amount', $fee->amount ?? '') }}"
            required
        >
        @error('amount')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Status <span class="text-danger">*</span></label>
        <select name="status" class="form-select" required>
            @foreach(['pending' => 'Pending', 'invoiced' => 'Invoiced', 'paid' => 'Paid', 'waived' => 'Waived'] as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $fee->status ?? 'pending') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('status')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Invoice ID</label>
        <input
            type="number"
            name="invoice_id"
            class="form-control"
            min="1"
            value="{{ old('invoice_id', $fee->invoice_id ?? '') }}"
        >
        @error('invoice_id')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label class="form-label">Notes</label>
        <textarea name="notes" class="form-control" rows="3">{{ old('notes', $fee->notes ?? '') }}</textarea>
        @error('notes')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>
</div>

@once
<script src="{{ asset('assets/js/academic-hierarchy.js') }}"></script>
@endonce

<script>
document.addEventListener('DOMContentLoaded', function () {

    const academicYear = document.querySelector('[name="academic_year_id"]');
    const classSelect = document.querySelector('[name="class_id"]');
    const sectionSelect = document.querySelector('[name="section_id"]');
    const studentSelect = document.querySelector('[name="student_id"]');

    const assignmentSelect =
        document.getElementById('transport_fee_route_student_id');

    const help =
        document.getElementById('transport-fee-assignment-help');

    if (
        !academicYear ||
        !classSelect ||
        !sectionSelect ||
        !studentSelect ||
        !assignmentSelect
    ) {
        return;
    }

    const assignmentUrl =
        @json(route('admin.transport.fees.assignments'));

    const selectedAssignment =
        assignmentSelect.dataset.selected || '';

    function clearAssignments(message) {

        assignmentSelect.innerHTML = '';

        const option = document.createElement('option');

        option.value = '';
        option.textContent = message;

        assignmentSelect.appendChild(option);
    }

    async function loadAssignments() {

        const academicYearId = academicYear.value;
        const classId = classSelect.value;
        const sectionId = sectionSelect.value;
        const studentId = studentSelect.value;

        if (
            !academicYearId ||
            !classId ||
            !sectionId ||
            !studentId
        ) {
            clearAssignments(
                'Select Academic Year, Class, Section and Student to load active route assignments.'
            );

            help.textContent =
                'Select Academic Year, Class, Section and Student to load active route assignments.';

            return;
        }

        assignmentSelect.disabled = true;

        clearAssignments('Loading route assignments...');

        const url =
            new URL(assignmentUrl, window.location.origin);

        url.searchParams.set(
            'academic_year_id',
            academicYearId
        );

        url.searchParams.set(
            'class_id',
            classId
        );

        url.searchParams.set(
            'section_id',
            sectionId
        );

        url.searchParams.set(
            'student_id',
            studentId
        );

        try {

            const response = await fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const payload = await response.json();

            if (!response.ok) {
                throw new Error(
                    payload.message ||
                    'Unable to load route assignments.'
                );
            }

            const assignments = payload.data || [];

            assignmentSelect.innerHTML = '';

            if (!assignments.length) {

                clearAssignments(
                    'No active route assignments found'
                );

                help.textContent =
                    'This student has no active transport route assignment.';

                return;
            }

            const placeholder =
                document.createElement('option');

            placeholder.value = '';
            placeholder.textContent =
                'Select route assignment';

            assignmentSelect.appendChild(placeholder);

            assignments.forEach(function (item) {

                const option =
                    document.createElement('option');

                option.value = item.id;

                let label =
                    item.route_name || 'Route';

                if (item.route_code) {
                    label += ` (${item.route_code})`;
                }

                if (item.pickup_point) {
                    label +=
                        ` — Pickup: ${item.pickup_point}`;
                }

                option.textContent = label;

                if (
                    String(item.id) ===
                    String(selectedAssignment)
                ) {
                    option.selected = true;
                }

                assignmentSelect.appendChild(option);
            });

            help.textContent =
                `${assignments.length} active route assignment(s) found for this student.`;

        } catch (error) {

            clearAssignments(
                'Unable to load route assignments'
            );

            help.textContent =
                error.message ||
                'Unable to load route assignments.';

        } finally {

            assignmentSelect.disabled = false;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Student changed manually
    |--------------------------------------------------------------------------
    */

    studentSelect.addEventListener(
        'change',
        loadAssignments
    );

    /*
    |--------------------------------------------------------------------------
    | Hierarchy changes
    |--------------------------------------------------------------------------
    */

    sectionSelect.addEventListener(
        'change',
        function () {
            setTimeout(loadAssignments, 300);
        }
    );

    classSelect.addEventListener(
        'change',
        function () {
            setTimeout(loadAssignments, 500);
        }
    );

    academicYear.addEventListener(
        'change',
        function () {
            setTimeout(loadAssignments, 700);
        }
    );

    /*
    |--------------------------------------------------------------------------
    | Initial page load
    |--------------------------------------------------------------------------
    |
    | The common hierarchy JS may need a little time to populate:
    |
    | Academic Year → Class → Section → Student
    |
    | Therefore retry a few times rather than relying on one fixed
    | initialization moment.
    |
    */

    let attempts = 0;

    const initializeAssignments = setInterval(function () {

        attempts++;

        if (
            academicYear.value &&
            classSelect.value &&
            sectionSelect.value &&
            studentSelect.value
        ) {
            clearInterval(initializeAssignments);

            loadAssignments();

            return;
        }

        if (attempts >= 20) {
            clearInterval(initializeAssignments);
        }

    }, 300);

});
</script>
