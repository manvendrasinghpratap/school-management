<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYears;
use App\Models\Graduation;
use App\Models\Student;
use App\Services\GraduationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class GraduationController extends Controller
{
    public function __construct(
        protected GraduationService $graduationService
    ) {
    }

    /**
     * Display graduation records.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        abort_unless($user->school_id, 403, 'No school is assigned to the current user.');

        $query = Graduation::query()
            ->where('school_id', $user->school_id)
            ->with([
                'student',
                'academicYear',
                'approvedBy',
            ])
            ->latest('graduation_date')
            ->latest('id');

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->whereHas('student', function ($studentQuery) use ($search) {
                $studentQuery->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('middle_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('student_number', 'like', "%{$search}%")
                        ->orWhere('admission_number', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('academic_year_id')) {
            $query->where(
                'academic_year_id',
                $request->academic_year_id
            );
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $graduations = $query
            ->paginate(15)
            ->withQueryString();

        $academicYears = AcademicYears::query()
            ->where('school_id', $user->school_id)
            ->where('is_active', true)
            ->whereNull('deleted_at')
            ->orderByDesc('start_date')
            ->get();

        return view('admin.graduations.index', compact(
            'graduations',
            'academicYears'
        ));
    }

    /**
     * Show the create graduation form.
     */
    public function create()
    {
        $user = Auth::user();

        abort_unless($user->school_id, 403, 'No school is assigned to the current user.');

        $academicYears = AcademicYears::query()
            ->where('school_id', $user->school_id)
            ->where('is_active', true)
            ->whereNull('deleted_at')
            ->orderByDesc('start_date')
            ->get();

        /*
         * Only students who are potentially eligible are loaded.
         *
         * Final eligibility is still enforced by GraduationService.
         */
        $students = Student::query()
            ->where('school_id', $user->school_id)
            ->where('status', 'active')
            ->whereHas('enrollments', function ($enrollmentQuery) {
                $enrollmentQuery
                    ->where('status', 'active')
                    ->whereNull('deleted_at')
                    ->whereHas('class', function ($classQuery) {
                        $classQuery
                            ->where('name', 'SS 3')
                            ->where('is_active', true)
                            ->whereNull('deleted_at');
                    });
            })
            ->with([
                'enrollments' => function ($query) {
                    $query
                        ->where('status', 'active')
                        ->whereNull('deleted_at')
                        ->with([
                            'class',
                            'section',
                            'academicYear',
                        ]);
                },
            ])
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        return view('admin.graduations.create', compact(
            'students',
            'academicYears'
        ));
    }

    /**
     * Store a new graduation record.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => [
                'required',
                'integer',
            ],
            'academic_year_id' => [
                'required',
                'integer',
            ],
            'graduation_date' => [
                'required',
                'date',
            ],
            'qualification' => [
                'nullable',
                'string',
                'max:255',
            ],
            'remarks' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        try {
            $graduation = $this->graduationService->create(
                Auth::user(),
                $validated
            );

            return redirect()
                ->route('admin.graduations.show', $graduation)
                ->with(
                    'success',
                    'Graduation record created successfully and is pending approval.'
                );
        } catch (ValidationException $e) {
            throw $e;
        }
    }

    /**
     * Display a graduation record.
     */
    public function show(Graduation $graduation)
    {
        $user = Auth::user();

        abort_unless(
            (int) $graduation->school_id === (int) $user->school_id,
            403,
            'The selected graduation is invalid.'
        );

        $graduation->load([
            'student',
            'academicYear',
            'approvedBy',
        ]);

        return view('admin.graduations.show', compact(
            'graduation'
        ));
    }

    /**
     * Approve a pending graduation.
     */
    public function approve(Graduation $graduation)
    {
        $graduation = $this->graduationService->approve(
            Auth::user(),
            $graduation
        );

        return redirect()
            ->route('admin.graduations.show', $graduation)
            ->with(
                'success',
                'Graduation has been approved successfully.'
            );
    }

    /**
     * Complete an approved graduation.
     */
    public function complete(Graduation $graduation)
    {
        $graduation = $this->graduationService->complete(
            Auth::user(),
            $graduation
        );

        return redirect()
            ->route('admin.graduations.show', $graduation)
            ->with(
                'success',
                'Graduation has been marked as completed successfully.'
            );
    }

    /**
     * Delete a pending graduation.
     */
    public function destroy(Graduation $graduation)
    {
        $this->graduationService->delete(
            Auth::user(),
            $graduation
        );

        return redirect()
            ->route('admin.graduations.index')
            ->with(
                'success',
                'Pending graduation record deleted successfully.'
            );
    }
}