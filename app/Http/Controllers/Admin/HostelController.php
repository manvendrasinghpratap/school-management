<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHostelAllocationRequest;
use App\Http\Requests\StoreHostelBedRequest;
use App\Http\Requests\StoreHostelRequest;
use App\Http\Requests\StoreHostelRoomRequest;
use App\Models\Hostel;
use App\Models\HostelAllocation;
use App\Models\HostelBed;
use App\Models\HostelRoom;
use App\Models\Staff;
use App\Services\AcademicHierarchyService;
use App\Services\HostelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HostelController extends Controller
{
    public function __construct(
        private HostelService $service,
        private AcademicHierarchyService $academicHierarchy
    ) {
    }

    // ---------------------------------------------------------------------
    // School / Authorization Helpers
    // ---------------------------------------------------------------------

    private function schoolId(): int
    {
        $schoolId = Auth::user()?->school_id;

        abort_unless(
            $schoolId,
            403,
            'No school is assigned to the current user.'
        );

        return (int) $schoolId;
    }

    private function own($model): void
    {
        abort_unless(
            (int) $model->school_id === $this->schoolId(),
            404
        );
    }

    // ---------------------------------------------------------------------
    // Hostel Module Landing Page
    // ---------------------------------------------------------------------

    /**
     * Main Hostel module entry point.
     *
     * /admin/hostel
     *
     * Reuses the existing hostel listing implementation so that
     * the listing logic is maintained in one place.
     */
    public function index(Request $request)
    {
        return $this->hostels($request);
    }

    // ---------------------------------------------------------------------
    // Hostels
    // ---------------------------------------------------------------------

    /**
     * Display the hostel listing.
     *
     * /admin/hostel/hostels
     */
    public function hostels(Request $request)
    {
        $schoolId = $this->schoolId();

        $hostels = Hostel::query()
            ->where('school_id', $schoolId)
            ->with('warden')
            ->withCount([
                'rooms',
                'allocations',
            ])
            ->when(
                $request->filled('search'),
                function ($query) use ($request) {
                    $search = trim(
                        (string) $request->input('search')
                    );

                    $query->where(function ($q) use ($search) {
                        $q->where(
                            'name',
                            'like',
                            "%{$search}%"
                        )->orWhere(
                            'code',
                            'like',
                            "%{$search}%"
                        );
                    });
                }
            )
            ->when(
                $request->filled('status'),
                fn ($q) => $q->where(
                    'status',
                    $request->input('status')
                )
            )
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view(
            'admin.hostel.hostels.index',
            compact('hostels')
        );
    }

    /**
     * Show hostel creation form.
     */
    public function createHostel()
    {
        $staff = Staff::query()
            ->where('school_id', $this->schoolId())
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        return view(
            'admin.hostel.hostels.create',
            compact('staff')
        );
    }

    /**
     * Store a new hostel.
     */
    public function storeHostel(StoreHostelRequest $request)
    {
        $this->service->saveHostel(
            $request->validated()
        );

        return redirect()
            ->route('admin.hostel.hostels.index')
            ->with(
                'success',
                'Hostel created successfully.'
            );
    }

    /**
     * Display a single hostel.
     */
    public function showHostel(Hostel $hostel)
    {
        $this->own($hostel);

        $hostel->load([
            'warden',
            'rooms' => fn ($q) => $q
                ->withCount('beds')
                ->orderBy('room_number'),
        ]);

        return view(
            'admin.hostel.hostels.show',
            compact('hostel')
        );
    }

    /**
     * Show hostel edit form.
     */
    public function editHostel(Hostel $hostel)
    {
        $this->own($hostel);

        $staff = Staff::query()
            ->where('school_id', $this->schoolId())
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        return view(
            'admin.hostel.hostels.edit',
            compact('hostel', 'staff')
        );
    }

    /**
     * Update an existing hostel.
     */
    public function updateHostel(
        StoreHostelRequest $request,
        Hostel $hostel
    ) {
        $this->own($hostel);

        $this->service->saveHostel(
            $request->validated(),
            $hostel
        );

        return redirect()
            ->route('admin.hostel.hostels.index')
            ->with(
                'success',
                'Hostel updated successfully.'
            );
    }

    /**
     * Delete a hostel.
     *
     * Hostels with existing rooms cannot be deleted.
     */
    public function destroyHostel(Hostel $hostel)
    {
        $this->own($hostel);

        if ($hostel->rooms()->exists()) {
            return back()->withErrors([
                'hostel' =>
                    'Remove or deactivate the hostel rooms before deleting this hostel.',
            ]);
        }

        $this->service->delete($hostel);

        return back()->with(
            'success',
            'Hostel deleted successfully.'
        );
    }

    // ---------------------------------------------------------------------
    // Rooms
    // ---------------------------------------------------------------------

    /**
     * Display rooms belonging to a hostel.
     */
    public function rooms(Hostel $hostel)
    {
        $this->own($hostel);

        $rooms = $hostel->rooms()
            ->withCount('beds')
            ->latest('id')
            ->paginate(20);

        return view(
            'admin.hostel.rooms.index',
            compact('hostel', 'rooms')
        );
    }

    /**
     * Show room creation form.
     */
    public function createRoom(Hostel $hostel)
    {
        $this->own($hostel);

        return view(
            'admin.hostel.rooms.create',
            compact('hostel')
        );
    }

    /**
     * Store a new hostel room.
     */
    public function storeRoom(StoreHostelRoomRequest $request)
    {
        $data = $request->validated();

        $room = $this->service->saveRoom($data);

        return redirect()
            ->route(
                'admin.hostel.rooms.index',
                $room->hostel_id
            )
            ->with(
                'success',
                'Hostel room created successfully.'
            );
    }

    /**
     * Display a single room.
     */
    public function showRoom(HostelRoom $room)
    {
        $this->own($room);

        $room->load([
            'hostel',
            'beds' => fn ($q) => $q->orderBy('bed_number'),
        ]);

        return view(
            'admin.hostel.rooms.show',
            compact('room')
        );
    }

    /**
     * Show room edit form.
     */
    public function editRoom(HostelRoom $room)
    {
        $this->own($room);

        $hostel = $room->hostel;

        return view(
            'admin.hostel.rooms.edit',
            compact('room', 'hostel')
        );
    }

    /**
     * Update a hostel room.
     */
    public function updateRoom(
        StoreHostelRoomRequest $request,
        HostelRoom $room
    ) {
        $this->own($room);

        $updated = $this->service->saveRoom(
            $request->validated(),
            $room
        );

        return redirect()
            ->route(
                'admin.hostel.rooms.show',
                $updated
            )
            ->with(
                'success',
                'Hostel room updated successfully.'
            );
    }

    /**
     * Delete a hostel room.
     *
     * Rooms with active allocations cannot be deleted.
     */
    public function destroyRoom(HostelRoom $room)
    {
        $this->own($room);

        if (
            $room->allocations()
                ->whereIn(
                    'status',
                    [
                        'allocated',
                        'checked_in',
                    ]
                )
                ->exists()
        ) {
            return back()->withErrors([
                'room' =>
                    'This room has an active hostel allocation and cannot be deleted.',
            ]);
        }

        /*
         * Deactivate beds before deleting the room.
         */
        $room->beds()->update([
            'status' => 'inactive',
        ]);

        $this->service->delete($room);

        return back()->with(
            'success',
            'Hostel room deleted successfully.'
        );
    }

    // ---------------------------------------------------------------------
    // Beds
    // ---------------------------------------------------------------------

    /**
     * Display beds belonging to a room.
     */
    public function beds(HostelRoom $room)
    {
        $this->own($room);

        $room->load('hostel');

        $beds = $room->beds()
            ->latest('id')
            ->paginate(20);

        return view(
            'admin.hostel.beds.index',
            compact('room', 'beds')
        );
    }

    /**
     * Show bed creation form.
     */
    public function createBed(HostelRoom $room)
    {
        $this->own($room);

        $room->load('hostel');

        return view(
            'admin.hostel.beds.create',
            compact('room')
        );
    }

    /**
     * Store a new bed.
     */
    public function storeBed(StoreHostelBedRequest $request)
    {
        $bed = $this->service->saveBed(
            $request->validated()
        );

        return redirect()
            ->route(
                'admin.hostel.beds.index',
                $bed->room_id
            )
            ->with(
                'success',
                'Hostel bed created successfully.'
            );
    }

    /**
     * Display a single bed.
     */
    public function showBed(HostelBed $bed)
    {
        $this->own($bed);

        $bed->load([
            'room.hostel',
        ]);

        return view(
            'admin.hostel.beds.show',
            compact('bed')
        );
    }

    /**
     * Show bed edit form.
     */
    public function editBed(HostelBed $bed)
    {
        $this->own($bed);

        $bed->load('room.hostel');

        $room = $bed->room;

        return view(
            'admin.hostel.beds.edit',
            compact('bed', 'room')
        );
    }

    /**
     * Update a bed.
     */
    public function updateBed(
        StoreHostelBedRequest $request,
        HostelBed $bed
    ) {
        $this->own($bed);

        $updated = $this->service->saveBed(
            $request->validated(),
            $bed
        );

        return redirect()
            ->route(
                'admin.hostel.beds.show',
                $updated
            )
            ->with(
                'success',
                'Hostel bed updated successfully.'
            );
    }

    /**
     * Delete a bed.
     *
     * Beds with active allocations cannot be deleted.
     */
    public function destroyBed(HostelBed $bed)
    {
        $this->own($bed);

        if (
            $bed->allocations()
                ->whereIn(
                    'status',
                    [
                        'allocated',
                        'checked_in',
                    ]
                )
                ->exists()
        ) {
            return back()->withErrors([
                'bed' =>
                    'This bed has an active allocation and cannot be deleted.',
            ]);
        }

        $this->service->delete($bed);

        return back()->with(
            'success',
            'Hostel bed deleted successfully.'
        );
    }

    // ---------------------------------------------------------------------
    // AJAX / Dependent Dropdowns
    // ---------------------------------------------------------------------

    /**
     * Return rooms belonging to a selected hostel.
     */
    public function roomsForHostel(Request $request)
    {
        $hostel = Hostel::query()
            ->where(
                'id',
                (int) $request->input('hostel_id')
            )
            ->where(
                'school_id',
                $this->schoolId()
            )
            ->where(
                'status',
                'active'
            )
            ->firstOrFail();

        return response()->json([
            'data' => $hostel->rooms()
                ->whereIn(
                    'status',
                    [
                        'available',
                        'full',
                    ]
                )
                ->orderBy('room_number')
                ->get([
                    'id',
                    'room_number',
                    'capacity',
                    'monthly_fee',
                    'status',
                ]),
        ]);
    }

    /**
     * Return available beds belonging to a selected room.
     */
    public function bedsForRoom(Request $request)
    {
        $room = HostelRoom::query()
            ->where(
                'id',
                (int) $request->input('room_id')
            )
            ->where(
                'school_id',
                $this->schoolId()
            )
            ->firstOrFail();

        return response()->json([
            'data' => $room->beds()
                ->where(
                    'status',
                    'available'
                )
                ->orderBy('bed_number')
                ->get([
                    'id',
                    'bed_number',
                    'status',
                ]),
        ]);
    }

    // ---------------------------------------------------------------------
    // Allocations
    // ---------------------------------------------------------------------

    /**
     * Display hostel allocations.
     */
    public function allocations(Request $request)
    {
        $allocations = HostelAllocation::query()
            ->where(
                'school_id',
                $this->schoolId()
            )
            ->with([
                'hostel',
                'room',
                'bed',
                'student',
                'academicYear',
            ])
            ->when(
                $request->filled('search'),
                function ($query) use ($request) {
                    $search = trim(
                        (string) $request->input('search')
                    );

                    $query->whereHas(
                        'student',
                        function ($q) use ($search) {
                            $q->where(
                                'first_name',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'last_name',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'student_number',
                                'like',
                                "%{$search}%"
                            );
                        }
                    );
                }
            )
            ->when(
                $request->filled('status'),
                fn ($q) => $q->where(
                    'status',
                    $request->input('status')
                )
            )
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view(
            'admin.hostel.allocations.index',
            compact('allocations')
        );
    }

    /**
     * Show allocation creation form.
     */
    public function createAllocation()
    {
        $schoolId = $this->schoolId();

        $academicYears =
            $this->academicHierarchy->academicYears();

        $currentAcademicYear =
            $this->academicHierarchy->currentAcademicYear();

        $hostels = Hostel::query()
            ->where(
                'school_id',
                $schoolId
            )
            ->where(
                'status',
                'active'
            )
            ->orderBy('name')
            ->get();

        return view(
            'admin.hostel.allocations.create',
            compact(
                'academicYears',
                'currentAcademicYear',
                'hostels'
            )
        );
    }

    /**
     * Store a new hostel allocation.
     */
    public function storeAllocation(
        StoreHostelAllocationRequest $request
    ) {
        $allocation = $this->service->allocate(
            $request->validated()
        );

        return redirect()
            ->route(
                'admin.hostel.allocations.show',
                $allocation
            )
            ->with(
                'success',
                'Student allocated to hostel successfully.'
            );
    }

    /**
     * Display a single allocation.
     */
    public function showAllocation(
        HostelAllocation $allocation
    ) {
        $this->own($allocation);

        $allocation->load([
            'hostel',
            'room',
            'bed',
            'student',
            'academicYear',
            'fees',
        ]);

        return view(
            'admin.hostel.allocations.show',
            compact('allocation')
        );
    }

    /**
     * Check a student out of the hostel.
     */
    public function checkout(
        HostelAllocation $allocation
    ) {
        $this->own($allocation);

        $this->service->checkout(
            $allocation
        );

        return back()->with(
            'success',
            'Student checked out successfully.'
        );
    }
}