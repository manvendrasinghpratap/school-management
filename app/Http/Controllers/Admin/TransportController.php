<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRouteStudentRequest;
use App\Http\Requests\StoreTransportDriverRequest;
use App\Http\Requests\StoreTransportFeeRequest;
use App\Http\Requests\StoreTransportRouteRequest;
use App\Http\Requests\StoreTransportStopRequest;
use App\Http\Requests\StoreVehicleRequest;
use App\Models\RouteStudent;
use App\Models\Student;
use App\Models\TransportDriver;
use App\Models\TransportFee;
use App\Models\TransportRoute;
use App\Models\TransportStop;
use App\Models\Vehicle;
use App\Models\Staff;
use App\Services\TransportService;
use App\Services\AcademicHierarchyService;
use Illuminate\Http\Request;

class TransportController extends Controller
{
    public function __construct(
        private TransportService $service,
        private AcademicHierarchyService $academicHierarchy
    ) {
    }

    private function sid(): int
    {
        abort_unless(
            auth()->user()?->school_id,
            403,
            'No school is assigned to the current user.'
        );

        return (int) auth()->user()->school_id;
    }

    private function own($model): void
    {
        abort_unless(
            (int) $model->school_id === $this->sid(),
            404
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DRIVERS
    |--------------------------------------------------------------------------
    */

    public function drivers(Request $r)
    {
        $sid = $this->sid();

        $q = TransportDriver::where('school_id', $sid)
            ->with('staff')
            ->latest('id');

        if ($r->filled('search')) {
            $s = trim($r->search);

            $q->where(function ($x) use ($s) {
                $x->where('name', 'like', "%$s%")
                    ->orWhere('license_number', 'like', "%$s%")
                    ->orWhere('phone', 'like', "%$s%");
            });
        }

        if ($r->filled('status')) {
            $q->where('status', $r->status);
        }

        return view('admin.transport.drivers.index', [
            'drivers' => $q->paginate(20)->withQueryString(),
        ]);
    }

    public function createDriver()
    {
        return view('admin.transport.drivers.create', [
            'staff' => Staff::where('school_id', $this->sid())
                ->orderBy('first_name')
                ->get(),
        ]);
    }

    public function showDriver(TransportDriver $driver)
    {
        $this->own($driver);

        $driver->load('staff');

        return view('admin.transport.drivers.show', [
            'driver' => $driver,
        ]);
    }

    public function storeDriver(StoreTransportDriverRequest $r)
    {
        $this->service->saveDriver($r->validated());

        return back()->with(
            'success',
            'Driver created successfully.'
        );
    }

    public function editDriver(TransportDriver $driver)
    {
        $this->own($driver);

        return view('admin.transport.drivers.edit', [
            'driver' => $driver,
            'staff' => Staff::where('school_id', $this->sid())
                ->orderBy('first_name')
                ->get(),
        ]);
    }

    public function updateDriver(
        StoreTransportDriverRequest $r,
        TransportDriver $driver
    ) {
        $this->own($driver);

        $this->service->saveDriver(
            $r->validated(),
            $driver
        );

        return redirect()
            ->route('admin.transport.drivers.index')
            ->with('success', 'Driver updated successfully.');
    }

    public function destroyDriver(TransportDriver $driver)
    {
        $this->own($driver);

        $this->service->delete($driver);

        return back()->with(
            'success',
            'Driver deleted successfully.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | VEHICLES
    |--------------------------------------------------------------------------
    */

    public function vehicles(Request $r)
    {
        $sid = $this->sid();

        $q = Vehicle::where('school_id', $sid)
            ->latest('id');

        if ($r->filled('search')) {
            $s = trim($r->search);

            $q->where(function ($x) use ($s) {
                $x->where(
                    'vehicle_number',
                    'like',
                    "%$s%"
                )
                    ->orWhere(
                        'registration_number',
                        'like',
                        "%$s%"
                    )
                    ->orWhere(
                        'vehicle_type',
                        'like',
                        "%$s%"
                    );
            });
        }

        if ($r->filled('status')) {
            $q->where('status', $r->status);
        }

        return view('admin.transport.vehicles.index', [
            'vehicles' => $q->paginate(20)->withQueryString(),
        ]);
    }

    public function createVehicle()
    {
        return view('admin.transport.vehicles.create');
    }

    public function showVehicle(Vehicle $vehicle)
    {
        $this->own($vehicle);

        return view('admin.transport.vehicles.show', [
            'vehicle' => $vehicle,
        ]);
    }

    public function storeVehicle(StoreVehicleRequest $r)
    {
        $this->service->saveVehicle($r->validated());

        return back()->with(
            'success',
            'Vehicle created successfully.'
        );
    }

    public function editVehicle(Vehicle $vehicle)
    {
        $this->own($vehicle);

        return view('admin.transport.vehicles.edit', [
            'vehicle' => $vehicle,
        ]);
    }

    public function updateVehicle(
        StoreVehicleRequest $r,
        Vehicle $vehicle
    ) {
        $this->own($vehicle);

        $this->service->saveVehicle(
            $r->validated(),
            $vehicle
        );

        return redirect()
            ->route('admin.transport.vehicles.index')
            ->with('success', 'Vehicle updated successfully.');
    }

    public function destroyVehicle(Vehicle $vehicle)
    {
        $this->own($vehicle);

        $this->service->delete($vehicle);

        return back()->with(
            'success',
            'Vehicle deleted successfully.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | TRANSPORT ROUTES
    |--------------------------------------------------------------------------
    */

    public function routes(Request $r)
    {
        $sid = $this->sid();

        $q = TransportRoute::where('school_id', $sid)
            ->with(['vehicle', 'driver'])
            ->latest('id');

        if ($r->filled('search')) {
            $s = trim($r->search);

            $q->where(function ($x) use ($s) {
                $x->where('name', 'like', "%$s%")
                    ->orWhere('code', 'like', "%$s%")
                    ->orWhere(
                        'start_point',
                        'like',
                        "%$s%"
                    )
                    ->orWhere(
                        'end_point',
                        'like',
                        "%$s%"
                    );
            });
        }

        if ($r->filled('status')) {
            $q->where('status', $r->status);
        }

        return view('admin.transport.routes.index', [
            'routes' => $q->paginate(20)->withQueryString(),
        ]);
    }

    public function createRoute()
    {
        return view('admin.transport.routes.create', [
            'vehicles' => Vehicle::where(
                'school_id',
                $this->sid()
            )
                ->where('status', 'active')
                ->orderBy('vehicle_number')
                ->get(),

            'drivers' => TransportDriver::where(
                'school_id',
                $this->sid()
            )
                ->where('status', 'active')
                ->orderBy('name')
                ->get(),
        ]);
    }
    public function showRoute(TransportRoute $transportRoute)
{
    $this->own($transportRoute);

    $transportRoute->load([
        'vehicle',
        'driver',
        'stops' => function ($query) {
            $query->orderBy('sequence_no');
        },
    ]);

    return view('admin.transport.routes.show', [
        'transportRoute' => $transportRoute,
    ]);
}
    public function storeRoute(StoreTransportRouteRequest $r)
    {
        $this->service->saveRoute(
            $r->validated()
        );

        return redirect()
            ->route('admin.transport.routes.index')
            ->with(
                'success',
                'Transport route created successfully.'
            );
    }

    public function editRoute(
        TransportRoute $transportRoute
    ) {
        $this->own($transportRoute);

        return view('admin.transport.routes.edit', [
            'transportRoute' => $transportRoute,

            'vehicles' => Vehicle::where(
                'school_id',
                $this->sid()
            )
                ->orderBy('vehicle_number')
                ->get(),

            'drivers' => TransportDriver::where(
                'school_id',
                $this->sid()
            )
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function updateRoute(
        StoreTransportRouteRequest $r,
        TransportRoute $transportRoute
    ) {
        $this->own($transportRoute);

        $this->service->saveRoute(
            $r->validated(),
            $transportRoute
        );

        return redirect()
            ->route('admin.transport.routes.index')
            ->with(
                'success',
                'Transport route updated successfully.'
            );
    }

    public function destroyRoute(TransportRoute $transportRoute)
    {
        $this->own($transportRoute);
        $this->service->delete($transportRoute);
        return back()->with('success','Transport route deleted successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | ROUTE STOPS
    |--------------------------------------------------------------------------
    */

    public function stops(
        TransportRoute $transportRoute
    ) {
        $this->own($transportRoute);

        return view('admin.transport.stops.index', [
            'route' => $transportRoute,
            'stops' => $transportRoute
                ->stops()
                ->paginate(20),
        ]);
    }

    public function createStop(
        TransportRoute $transportRoute
    ) {
        $this->own($transportRoute);

        return view('admin.transport.stops.create', [
            'route' => $transportRoute,
        ]);
    }

    public function storeStop(
        StoreTransportStopRequest $r
    ) {
        $route = TransportRoute::where(
            'id',
            $r->route_id
        )
            ->where(
                'school_id',
                $this->sid()
            )
            ->firstOrFail();

        $this->service->saveStop(
            $r->validated()
        );

        return redirect()
            ->route(
                'admin.transport.routes.stops.index',
                $route
            )
            ->with(
                'success',
                'Stop created successfully.'
            );
    }

    public function editStop(
        TransportStop $stop
    ) {
        $this->own($stop);

        return view('admin.transport.stops.edit', [
            'stop' => $stop,
            'route' => $stop->route,
        ]);
    }

    public function updateStop(
        StoreTransportStopRequest $r,
        TransportStop $stop
    ) {
        $this->own($stop);

        $this->service->saveStop(
            $r->validated(),
            $stop
        );

        return redirect()
            ->route(
                'admin.transport.routes.stops.index',
                $stop->route
            )
            ->with(
                'success',
                'Stop updated successfully.'
            );
    }

    public function destroyStop(
        TransportStop $stop
    ) {
        $this->own($stop);

        $route = $stop->route;

        $this->service->delete($stop);

        return back()->with(
            'success',
            'Stop deleted successfully.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STUDENT TRANSPORT ASSIGNMENTS
    |--------------------------------------------------------------------------
    */

    public function assignments(Request $r)
    {
        $sid = $this->sid();

        $q = RouteStudent::where('school_id', $sid)
            ->with(['route', 'student'])
            ->latest('id');

        if ($r->filled('search')) {
            $s = trim($r->search);

            $q->where(function ($x) use ($s) {

                $x->whereHas(
                    'student',
                    function ($st) use ($s) {
                        $st->where(
                            'first_name',
                            'like',
                            "%$s%"
                        )
                            ->orWhere(
                                'middle_name',
                                'like',
                                "%$s%"
                            )
                            ->orWhere(
                                'last_name',
                                'like',
                                "%$s%"
                            )
                            ->orWhere(
                                'student_number',
                                'like',
                                "%$s%"
                            );
                    }
                )
                    ->orWhereHas(
                        'route',
                        function ($rt) use ($s) {
                            $rt->where(
                                'name',
                                'like',
                                "%$s%"
                            )
                                ->orWhere(
                                    'code',
                                    'like',
                                    "%$s%"
                                );
                        }
                    );
            });
        }

        if ($r->filled('status')) {
            $q->where('status', $r->status);
        }

        return view('admin.transport.assignments.index', [
            'assignments' => $q
                ->paginate(20)
                ->withQueryString(),
        ]);
    }

    public function createAssignment()
    {
        return view(
            'admin.transport.assignments.create',
            [
                'routes' => TransportRoute::where(
                    'school_id',
                    $this->sid()
                )
                    ->where('status', 'active')
                    ->orderBy('name')
                    ->get(),

                'academicYears' => $this->academicHierarchy->academicYears(),

                'currentAcademicYear' => $this->academicHierarchy->currentAcademicYear(),
            ]
        );
    }

    public function showAssignment(RouteStudent $assignment)
    {
        $this->own($assignment);

        $assignment->load([
            'student',
            'route.vehicle',
            'route.driver',
        ]);

        return view('admin.transport.assignments.show', [
            'assignment' => $assignment,
        ]);
    }

    public function storeAssignment(
        StoreRouteStudentRequest $r
    ) {
        $this->service->saveAssignment(
            $r->validated()
        );

        return redirect()
            ->route(
                'admin.transport.assignments.index'
            )
            ->with(
                'success',
                'Student assigned to route successfully.'
            );
    }

    public function editAssignment(
        RouteStudent $assignment
    ) {
        $this->own($assignment);

        return view(
            'admin.transport.assignments.edit',
            [
                'assignment' => $assignment,

                'routes' => TransportRoute::where(
                    'school_id',
                    $this->sid()
                )
                    ->orderBy('name')
                    ->get(),

                'academicYears' => $this->academicHierarchy->academicYears(),

                'currentAcademicYear' => $this->academicHierarchy->currentAcademicYear(),

                'academicHierarchy' => $this->academicHierarchy
                    ->studentHierarchy($assignment->student_id),
            ]
        );
    }

    public function updateAssignment(
        StoreRouteStudentRequest $r,
        RouteStudent $assignment
    ) {
        $this->own($assignment);

        $this->service->saveAssignment(
            $r->validated(),
            $assignment
        );

        return redirect()
            ->route(
                'admin.transport.assignments.index'
            )
            ->with(
                'success',
                'Assignment updated successfully.'
            );
    }

    public function destroyAssignment(
        RouteStudent $assignment
    ) {
        $this->own($assignment);

        $this->service->delete($assignment);

        return back()->with(
            'success',
            'Assignment deleted successfully.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | TRANSPORT FEES
    |--------------------------------------------------------------------------
    */

    public function fees(Request $r)
    {
        $sid = $this->sid();

        $q = TransportFee::where('school_id', $sid)
            ->with([
                'assignment.route',
                'assignment.student',
            ])
            ->latest('id');

        if ($r->filled('status')) {
            $q->where('status', $r->status);
        }

        if ($r->filled('search')) {
            $s = trim($r->search);

            $q->whereHas(
                'assignment.student',
                function ($st) use ($s) {
                    $st->where(
                        'first_name',
                        'like',
                        "%$s%"
                    )
                        ->orWhere(
                            'last_name',
                            'like',
                            "%$s%"
                        );
                }
            );
        }

        return view('admin.transport.fees.index', [
            'fees' => $q->paginate(20)->withQueryString(),
        ]);
    }

    public function createFee()
    {
        return view('admin.transport.fees.create', [
            'assignments' => RouteStudent::where(
                'school_id',
                $this->sid()
            )
                ->where('status', 'active')
                ->with(['student', 'route'])
                ->latest('id')
                ->get(),
        ]);
    }

    public function storeFee(
        StoreTransportFeeRequest $r
    ) {
        $this->service->saveFee(
            $r->validated()
        );

        return redirect()
            ->route('admin.transport.fees.index')
            ->with(
                'success',
                'Transport fee created successfully.'
            );
    }

    public function editFee(
        TransportFee $fee
    ) {
        $this->own($fee);

        return view('admin.transport.fees.edit', [
            'fee' => $fee,

            'assignments' => RouteStudent::where(
                'school_id',
                $this->sid()
            )
                ->with(['student', 'route'])
                ->orderByDesc('id')
                ->get(),
        ]);
    }

    public function updateFee(
        StoreTransportFeeRequest $r,
        TransportFee $fee
    ) {
        $this->own($fee);

        $this->service->saveFee(
            $r->validated(),
            $fee
        );

        return redirect()
            ->route('admin.transport.fees.index')
            ->with(
                'success',
                'Transport fee updated successfully.'
            );
    }

    public function destroyFee(
        TransportFee $fee
    ) {
        $this->own($fee);

        $this->service->delete($fee);

        return back()->with(
            'success',
            'Transport fee deleted successfully.'
        );
    }
}