<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Examination;
use App\Models\Mark;
use App\Models\Result;
use App\Models\School;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ResultsController extends Controller
{
    /**
     * Display examination results.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $schoolId = $this->schoolId($user);

        $query = Result::query()
            ->with([
                'student',
                'examination',
            ])
            ->whereHas('examination', function ($query) use ($schoolId) {
                $query->where('school_id', $schoolId);
            });

        /*
         * Examination filter
         */
        if ($request->filled('examination_id')) {

            $query->where(
                'examination_id',
                $request->integer('examination_id')
            );
        }

        /*
         * Student filter
         */
        if ($request->filled('student_id')) {

            $query->where(
                'student_id',
                $request->integer('student_id')
            );
        }

        /*
         * Status filter
         */
        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->input('status')
            );
        }

        /*
         * Results ordered newest first.
         */
        $results = $query
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        /*
         * Examination dropdown.
         */
        $examinations = Examination::query()
            ->where('school_id', $schoolId)
            ->orderByDesc('start_date')
            ->orderByDesc('id')
            ->get();

        /*
         * Students who have results for this school.
         */
        $students = Result::query()
            ->with('student')
            ->whereHas('examination', function ($query) use ($schoolId) {
                $query->where('school_id', $schoolId);
            })
            ->get()
            ->pluck('student')
            ->filter()
            ->unique('id')
            ->sortBy(function ($student) {
                return strtolower(
                    trim(
                        ($student->first_name ?? '') . ' ' .
                        ($student->middle_name ?? '') . ' ' .
                        ($student->last_name ?? '')
                    )
                );
            })
            ->values();

        return view('admin.results.index', compact(
            'results',
            'examinations',
            'students'
        ));
    }


    /**
     * Display a single examination result.
     */
    public function show(
        Request $request,
        Result $result
    ): View {

        $this->ensureResultBelongsToSchool(
            $request->user(),
            $result
        );

        $result->load([
            'student',
            'examination',
            'approvedBy',
            'publishedBy',
        ]);

        return view(
            'admin.results.show',
            compact('result')
        );
    }


    /**
     * Approve a calculated examination result.
     *
     * Workflow:
     *
     * Draft
     *   ↓
     * Approved
     */
    public function approve(
        Request $request,
        Result $result
    ): RedirectResponse {

        /*
         * Make sure the result belongs to the
         * current user's school.
         */
        $this->ensureResultBelongsToSchool(
            $request->user(),
            $result
        );

        /*
         * A result can only be approved when
         * it is currently in draft status.
         */
        if ($result->status !== 'draft') {

            return back()
                ->withErrors([
                    'result' =>
                        'Only draft results can be approved.',
                ]);
        }

        /*
         * Get all marks for this student
         * and examination.
         */
        $marks = Mark::query()
            ->where('student_id', $result->student_id)
            ->where('examination_id', $result->examination_id)
            ->get();

        /*
         * A result cannot be approved without marks.
         */
        if ($marks->isEmpty()) {

            return back()
                ->withErrors([
                    'result' =>
                        'This result cannot be approved because no subject marks were found.',
                ]);
        }

        /*
         * Every mark used for the result must
         * already be approved.
         */
        $unapprovedMarks = $marks->where(
            'status',
            '!=',
            'approved'
        );

        if ($unapprovedMarks->isNotEmpty()) {

            return back()
                ->withErrors([
                    'result' =>
                        'This result cannot be approved because not all subject marks have been approved.',
                ]);
        }

        /*
         * Approve the result.
         */
        $result->update([
            'status' => 'approved',
            'approved_by' => $request->user()->id,
        ]);

        return redirect()
            ->route('admin.results.show', $result)
            ->with(
                'success',
                'Result approved successfully.'
            );
    }


    /**
     * Publish an approved result.
     *
     * This will be implemented after the
     * approval workflow has been tested.
     */
    public function publish(
        Request $request,
        Result $result
    ): RedirectResponse {

        /*
         * Make sure the result belongs to the
         * current user's school.
         */
        $this->ensureResultBelongsToSchool(
            $request->user(),
            $result
        );

        /*
         * Only approved results can be published.
         */
        if ($result->status !== 'approved') {

            return back()
                ->withErrors([
                    'result' =>
                        'Only approved results can be published.',
                ]);
        }

        /*
         * Publication workflow will be completed
         * in the next step.
         */
        $result->update([
            'status' => 'published',
            'published_by' => $request->user()->id,
        ]);

        return redirect()
            ->route('admin.results.show', $result)
            ->with(
                'success',
                'Result published successfully.'
            );
    }


    /**
     * Resolve the current user's school.
     */
    private function schoolId($user): int
    {
        if (!$user) {

            abort(
                403,
                'Unauthenticated.'
            );
        }

        if (!empty($user->school_id)) {

            return (int) $user->school_id;
        }

        $schoolId = School::query()->value('id');

        if (!$schoolId) {

            abort(
                403,
                'No school is configured.'
            );
        }

        return (int) $schoolId;
    }


    /**
     * Ensure the result belongs to the
     * current user's school.
     */
    private function ensureResultBelongsToSchool(
        $user,
        Result $result
    ): void {

        $schoolId = $this->schoolId($user);

        /*
         * The examination determines the school
         * ownership of the result.
         */
        $resultSchoolId = $result->examination
            ? (int) $result->examination->school_id
            : null;

        if ($resultSchoolId !== $schoolId) {

            abort(
                403,
                'You are not authorized to access this result.'
            );
        }
    }
}