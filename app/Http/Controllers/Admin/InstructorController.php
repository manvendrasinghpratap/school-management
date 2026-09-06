<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInstructorRequest;
use App\Http\Requests\UpdateInstructorRequest;
use App\Models\Instructor;
use App\Models\Staff;
use App\Services\InstructorService;
use Illuminate\Http\Request;

class InstructorController extends Controller
{
    public function __construct(
        protected InstructorService $instructorService
    ) {
    }

    /**
     * Display instructor profiles.
     */
    public function index(Request $request)
    {
        $schoolId = $this->schoolId();

        $query = Instructor::query()
            ->whereHas('staff', function ($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            })
            ->with(['staff.department']);

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->whereHas('staff', function ($q) use ($search) {
                $q->where(function ($q) use ($search) {
                    $q->where('staff_number', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('middle_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('specialization')) {
            $query->where(
                'specialization',
                'like',
                '%' . trim($request->specialization) . '%'
            );
        }

        $instructors = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.instructors.index', compact('instructors'));
    }

    /**
     * Show the instructor creation form.
     */
    public function create()
    {
        $schoolId = $this->schoolId();

        $staff = Staff::query()
            ->where('school_id', $schoolId)
            ->where('status', 'active')
            ->whereDoesntHave('instructor')
            ->with('department')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        return view('admin.instructors.create', compact('staff'));
    }

    /**
     * Store a new instructor profile.
     */
    public function store(StoreInstructorRequest $request)
    {
        $this->instructorService->create(
            $request->validated()
        );

        return redirect()
            ->route('admin.instructors.index')
            ->with('success', 'Instructor profile created successfully.');
    }

    /**
     * Display an instructor profile.
     */
    public function show(Instructor $instructor)
    {
        $this->ensureSameSchool($instructor);

        $instructor->load([
            'staff.department',
            'staff.user',
        ]);

        return view('admin.instructors.show', compact('instructor'));
    }

    /**
     * Show the instructor edit form.
     */
    public function edit(Instructor $instructor)
    {
        $this->ensureSameSchool($instructor);

        $instructor->load([
            'staff.department',
        ]);

        return view('admin.instructors.edit', compact('instructor'));
    }

    /**
     * Update an instructor profile.
     */
    public function update(
        UpdateInstructorRequest $request,
        Instructor $instructor
    ) {
        $this->instructorService->update(
            $instructor,
            $request->validated()
        );

        return redirect()
            ->route('admin.instructors.show', $instructor)
            ->with('success', 'Instructor profile updated successfully.');
    }

    /**
     * Delete an instructor profile.
     *
     * This does NOT delete the staff member.
     */
    public function destroy(Instructor $instructor)
    {
        $this->ensureSameSchool($instructor);

        $this->instructorService->delete($instructor);

        return redirect()
            ->route('admin.instructors.index')
            ->with('success', 'Instructor profile removed successfully.');
    }

    /**
     * Get the authenticated user's school ID.
     */
    protected function schoolId(): int
    {
        $schoolId = auth()->user()?->school_id;

        if (!$schoolId) {
            abort(403, 'No school is assigned to this user.');
        }

        return (int) $schoolId;
    }

    /**
     * Ensure instructor belongs to the authenticated user's school.
     */
    protected function ensureSameSchool(Instructor $instructor): void
    {
        $schoolId = $this->schoolId();

        $belongsToSchool = $instructor->staff()
            ->where('school_id', $schoolId)
            ->exists();

        if (!$belongsToSchool) {
            abort(403, 'You are not authorized to access this instructor.');
        }
    }
}