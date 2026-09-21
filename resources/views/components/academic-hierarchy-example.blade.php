{{--
    Example usage.

    In your controller:

        $academicHierarchy = app(\App\Services\AcademicHierarchyService::class);

        return view('your-view', [
            'academicYears' => $academicHierarchy->academicYears(),
            'currentAcademicYear' => $academicHierarchy->currentAcademicYear(),
        ]);

    In your Blade:

        <x-academic-hierarchy
            :academic-years="$academicYears"
            :current-academic-year="$currentAcademicYear"
        />

    Then include the common JavaScript once:

        @push('scripts')
            <script src="{{ asset('assets/js/academic-hierarchy.js') }}"></script>
        @endpush

    If your layout uses @section('js') instead of @stack('scripts'),
    place the script there instead.
--}}

<x-academic-hierarchy
    :academic-years="$academicYears"
    :current-academic-year="$currentAcademicYear"
/>
