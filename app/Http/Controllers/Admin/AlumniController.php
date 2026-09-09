<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\Graduation;
use App\Services\AlumniService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AlumniController extends Controller
{
    public function __construct(
        protected AlumniService $alumniService
    ) {
    }

    /**
     * Display Alumni records.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        abort_unless(
            $user->school_id,
            403,
            'No school is assigned to the current user.'
        );

        $query = Alumni::query()
            ->where('school_id', $user->school_id)
            ->with(['student'])
            ->latest('graduation_date')
            ->latest('id');

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('graduation_year', 'like', "%{$search}%")
                    ->orWhere('current_occupation', 'like', "%{$search}%")
                    ->orWhere('current_employer', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('student', function ($studentQuery) use ($search) {
                        $studentQuery->where(function ($studentQ) use ($search) {
                            $studentQ->where(
                                'first_name',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'middle_name',
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
                            )
                            ->orWhere(
                                'admission_number',
                                'like',
                                "%{$search}%"
                            );
                        });
                    });
            });
        }

        $alumni = $query
            ->paginate(15)
            ->withQueryString();

        return view('admin.alumni.index', compact('alumni'));
    }

    /**
     * Show the Alumni creation form.
     *
     * Only students with completed graduation records
     * are presented as eligible Alumni.
     */
    public function create()
    {
        $user = Auth::user();

        abort_unless(
            $user->school_id,
            403,
            'No school is assigned to the current user.'
        );

        $completedGraduations = Graduation::query()
            ->where('school_id', $user->school_id)
            ->where('status', 'completed')
            ->whereDoesntHave('student.alumni')
            ->with([
                'student',
                'academicYear',
            ])
            ->latest('graduation_date')
            ->latest('id')
            ->get();

        return view(
            'admin.alumni.create',
            compact('completedGraduations')
        );
    }

    /**
     * Store a new Alumni record.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => [
                'required',
                'integer',
            ],
            'current_occupation' => [
                'nullable',
                'string',
                'max:255',
            ],
            'current_employer' => [
                'nullable',
                'string',
                'max:255',
            ],
            'phone' => [
                'nullable',
                'string',
                'max:50',
            ],
            'email' => [
                'nullable',
                'email',
                'max:255',
            ],
            'address' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        try {
            $alumni = $this->alumniService->create(
                Auth::user(),
                $validated
            );

            return redirect()
                ->route('admin.alumni.show', $alumni)
                ->with(
                    'success',
                    'Alumni record created successfully.'
                );
        } catch (ValidationException $e) {
            throw $e;
        }
    }

    /**
     * Display an Alumni profile.
     */
    public function show(Alumni $alumni)
    {
        $user = Auth::user();

        abort_unless(
            $user->school_id,
            403,
            'No school is assigned to the current user.'
        );

        abort_unless(
            (int) $alumni->school_id === (int) $user->school_id,
            403,
            'The selected Alumni record is invalid.'
        );

        $alumni->load([
            'student',
        ]);

        return view(
            'admin.alumni.show',
            compact('alumni')
        );
    }

    /**
     * Show the Alumni edit form.
     */
    public function edit(Alumni $alumni)
    {
        $user = Auth::user();

        abort_unless(
            $user->school_id,
            403,
            'No school is assigned to the current user.'
        );

        abort_unless(
            (int) $alumni->school_id === (int) $user->school_id,
            403,
            'The selected Alumni record is invalid.'
        );

        $alumni->load('student');

        return view(
            'admin.alumni.edit',
            compact('alumni')
        );
    }

    /**
     * Update an Alumni record.
     */
    public function update(Request $request, Alumni $alumni)
    {
        $validated = $request->validate([
            'current_occupation' => [
                'nullable',
                'string',
                'max:255',
            ],
            'current_employer' => [
                'nullable',
                'string',
                'max:255',
            ],
            'phone' => [
                'nullable',
                'string',
                'max:50',
            ],
            'email' => [
                'nullable',
                'email',
                'max:255',
            ],
            'address' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        try {
            $alumni = $this->alumniService->update(
                Auth::user(),
                $alumni,
                $validated
            );

            return redirect()
                ->route('admin.alumni.show', $alumni)
                ->with(
                    'success',
                    'Alumni record updated successfully.'
                );
        } catch (ValidationException $e) {
            throw $e;
        }
    }

    /**
     * Soft delete an Alumni record.
     */
    public function destroy(Alumni $alumni)
    {
        try {
            $this->alumniService->delete(
                Auth::user(),
                $alumni
            );

            return redirect()
                ->route('admin.alumni.index')
                ->with(
                    'success',
                    'Alumni record deleted successfully.'
                );
        } catch (ValidationException $e) {
            throw $e;
        }
    }
}