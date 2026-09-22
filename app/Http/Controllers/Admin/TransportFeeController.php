<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransportFeeRequest;
use App\Models\RouteStudent;
use App\Models\TransportFee;
use App\Services\AcademicHierarchyService;
use App\Services\TransportFeeService;
use Illuminate\Http\Request;

class TransportFeeController extends Controller
{
    public function __construct(
        protected TransportFeeService $service,
        protected AcademicHierarchyService $academicHierarchy,
    ) {
    }

    private function schoolId(): int
    {
        abort_unless(
            auth()->user()?->school_id,
            403,
            'No school is assigned to the current user.'
        );

        return (int) auth()->user()->school_id;
    }

    private function own(TransportFee $fee): void
    {
        abort_unless((int) $fee->school_id === $this->schoolId(), 404);
    }

    public function fees(Request $request)
    {
        $schoolId = $this->schoolId();

        $fees = TransportFee::query()
            ->with([
                'routeStudent.student',
                'routeStudent.route',
            ])
            ->where('school_id', $schoolId)
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = trim($request->string('search')->toString());

                $query->where(function ($searchQuery) use ($search) {
                    $searchQuery->whereHas('routeStudent.student', function ($studentQuery) use ($search) {
                        $studentQuery->where(function ($q) use ($search) {
                            $q->where('first_name', 'like', "%{$search}%")
                                ->orWhere('middle_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%")
                                ->orWhere('student_number', 'like', "%{$search}%");
                        });
                    })->orWhereHas('routeStudent.route', function ($routeQuery) use ($search) {
                        $routeQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('code', 'like', "%{$search}%");
                    });
                });
            })
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
            ->orderByDesc('fee_month')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.transport.fees.index', compact('fees'));
    }

    public function createFee()
    {
        return view('admin.transport.fees.create', [
            'academicYears' => $this->academicHierarchy->academicYears(),
            'currentAcademicYear' => $this->academicHierarchy->currentAcademicYear(),
        ]);
    }

    public function storeFee(StoreTransportFeeRequest $request)
    {
        $this->service->save($request->validated());

        return redirect()
            ->route('admin.transport.fees.index')
            ->with('success', 'Transport fee created successfully.');
    }

    public function editFee(TransportFee $fee)
    {
        $this->own($fee);

        $fee->load('routeStudent.student');

        $hierarchy = $this->academicHierarchy->studentHierarchy(
            (int) $fee->routeStudent->student_id
        );

        return view('admin.transport.fees.edit', [
            'fee' => $fee,
            'academicYears' => $this->academicHierarchy->academicYears(),
            'currentAcademicYear' => $this->academicHierarchy->currentAcademicYear(),
            'academicHierarchy' => $hierarchy,
        ]);
    }

    public function showFee(TransportFee $fee)
{
    $this->own($fee);

    $fee->load([
        'routeStudent.student',
        'routeStudent.route.vehicle',
        'routeStudent.route.driver',
        'invoice',
    ]);

    return view('admin.transport.fees.show', [
        'fee' => $fee,
    ]);
}

    public function updateFee(StoreTransportFeeRequest $request, TransportFee $fee)
    {
        $this->own($fee);

        $this->service->save($request->validated(), $fee);

        return redirect()
            ->route('admin.transport.fees.index')
            ->with('success', 'Transport fee updated successfully.');
    }

    public function destroyFee(TransportFee $fee)
    {
        $this->own($fee);

        $this->service->delete($fee);

        return redirect()
            ->route('admin.transport.fees.index')
            ->with('success', 'Transport fee deleted successfully.');
    }

    /**
     * Return active route assignments for a student in the current school.
     * This is intentionally filtered by the selected student; the fee stores
     * route_student_id, not duplicated academic hierarchy fields.
     */
    public function assignments(Request $request)
    {
        $schoolId = $this->schoolId();

        $studentId = (int) $request->input('student_id');

        abort_unless($studentId > 0, 422, 'A student is required.');

        // Validate that the student belongs to the selected hierarchy.
        $this->academicHierarchy->validateStudentHierarchy(
            (int) $request->input('academic_year_id'),
            (int) $request->input('class_id'),
            (int) $request->input('section_id'),
            $studentId
        );

        $assignments = RouteStudent::query()
            ->with('route')
            ->where('school_id', $schoolId)
            ->where('student_id', $studentId)
            ->where('status', 'active')
            ->whereNull('deleted_at')
            ->whereHas('route', fn ($q) => $q
                ->where('school_id', $schoolId)
                ->where('status', 'active')
                ->whereNull('deleted_at')
            )
            ->orderBy('id')
            ->get()
            ->map(fn ($assignment) => [
                'id' => $assignment->id,
                'route_name' => $assignment->route?->name,
                'route_code' => $assignment->route?->code,
                'pickup_point' => $assignment->pickup_point,
                'dropoff_point' => $assignment->dropoff_point,
                'status' => $assignment->status,
            ])
            ->values();

        return response()->json([
            'data' => $assignments,
        ]);
    }
}
