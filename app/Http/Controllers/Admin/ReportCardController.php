<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Examination;
use App\Models\Mark;
use App\Models\ReportCard;
use App\Models\Result;
use App\Models\School;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ReportCardController extends Controller
{
    /**
     * Display report cards.
     */
    public function index(Request $request): View
    {
        abort_unless(
            $request->user()->can('report-cards.view'),
            403
        );

        $user = $request->user();

        $this->ensureUserHasSchool($user->school_id);

        $schoolId = (int) $user->school_id;

        $query = ReportCard::query()
            ->with([
                'student',
                'examination',
                'generatedBy',
            ])
            ->where('school_id', $schoolId);

        /*
         * Examination filter.
         */
        if ($request->filled('examination_id')) {
            $query->where(
                'examination_id',
                $request->integer('examination_id')
            );
        }

        /*
         * Student filter.
         */
        if ($request->filled('student_id')) {
            $query->where(
                'student_id',
                $request->integer('student_id')
            );
        }

        /*
         * Status filter.
         */
        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->input('status')
            );
        }

        $reportCards = $query
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        /*
         * Load examinations belonging only to this school.
         */
        $examinations = Examination::query()
            ->where('school_id', $schoolId)
            ->orderBy('name')
            ->get();

        /*
         * Load active students belonging only to this school.
         */
        $students = Student::query()
            ->where('school_id', $schoolId)
            ->where('status', 'active')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        /*
         * Avoid querying Result separately inside Blade for every row.
         */
        $results = collect();

        if ($reportCards->isNotEmpty()) {
            $studentIds = $reportCards
                ->pluck('student_id')
                ->unique()
                ->values();

            $examinationIds = $reportCards
                ->pluck('examination_id')
                ->unique()
                ->values();

            $results = Result::query()
                ->whereIn('student_id', $studentIds)
                ->whereIn('examination_id', $examinationIds)
                ->get()
                ->keyBy(function (Result $result) {
                    return $this->resultKey(
                        $result->student_id,
                        $result->examination_id
                    );
                });
        }

        return view('admin.report-cards.index', compact(
            'reportCards',
            'examinations',
            'students',
            'results'
        ));
    }

    /**
     * Show report card details.
     */
    public function show(
        Request $request,
        ReportCard $reportCard
    ): View {
        abort_unless(
            $request->user()->can('report-cards.view'),
            403
        );

        $this->ensureSchoolAccess(
            $request,
            $reportCard
        );

        $reportCard->load([
            'school',
            'student',
            'examination',
            'generatedBy',
        ]);

        /*
         * Load the student's result for this examination.
         */
        $result = Result::query()
            ->where('student_id', $reportCard->student_id)
            ->where('examination_id', $reportCard->examination_id)
            ->first();

        /*
         * Load approved marks for the report-card details page.
         */
        $marks = Mark::query()
            ->with('course')
            ->where('student_id', $reportCard->student_id)
            ->where('examination_id', $reportCard->examination_id)
            ->where('status', 'approved')
            ->orderBy('course_id')
            ->get();

        return view('admin.report-cards.show', compact(
            'reportCard',
            'result',
            'marks'
        ));
    }

    /**
     * Generate a report card from a published result.
     */
    public function generate(
        Request $request,
        Result $result
    ): RedirectResponse {
        abort_unless(
            $request->user()->can('report-cards.generate'),
            403
        );

        $user = $request->user();

        $this->ensureUserHasSchool($user->school_id);

        $schoolId = (int) $user->school_id;

        /*
         * Load all required relationships.
         */
        $result->load([
            'student',
            'examination',
        ]);

        /*
         * Make sure the result has a valid student.
         */
        if (!$result->student) {
            abort(
                404,
                'Student associated with this result was not found.'
            );
        }

        /*
         * Make sure the result has a valid examination.
         */
        if (!$result->examination) {
            abort(
                404,
                'Examination associated with this result was not found.'
            );
        }

        /*
         * School isolation:
         * The examination must belong to the logged-in user's school.
         */
        if (
            (int) $result->examination->school_id !==
            $schoolId
        ) {
            abort(
                403,
                'You are not authorized to access this result.'
            );
        }

        /*
         * School isolation:
         * The student must also belong to the same school.
         */
        if (
            (int) $result->student->school_id !==
            $schoolId
        ) {
            abort(
                403,
                'The student does not belong to your school.'
            );
        }

        /*
         * Only published results can produce official report cards.
         */
        if ($result->status !== 'published') {
            return back()->with(
                'error',
                'Report card can only be generated from a published result.'
            );
        }

        /*
         * Load approved subject marks.
         */
        $marks = Mark::query()
            ->with('course')
            ->where('student_id', $result->student_id)
            ->where('examination_id', $result->examination_id)
            ->where('status', 'approved')
            ->orderBy('course_id')
            ->get();

        /*
         * A report card without approved subject marks is not valid.
         */
        if ($marks->isEmpty()) {
            return back()->with(
                'error',
                'No approved subject marks were found for this result.'
            );
        }

        /*
         * Find an existing report card, including soft-deleted records.
         *
         * The database has:
         *
         * student_id + examination_id UNIQUE
         *
         * so we must reuse an existing record instead of inserting
         * another record.
         */
        $reportCard = ReportCard::withTrashed()
            ->where('student_id', $result->student_id)
            ->where('examination_id', $result->examination_id)
            ->first();

        /*
         * If the report card has already been published, do not silently
         * regenerate it and move it backwards to "generated".
         */
        if (
            $reportCard &&
            $reportCard->status === 'published'
        ) {
            return back()->with(
                'info',
                'This report card has already been published.'
            );
        }

        /*
         * Restore a soft-deleted report card if one exists.
         */
        if ($reportCard && $reportCard->trashed()) {
            $reportCard->restore();
        }

        /*
         * Remove the previous generated PDF if regenerating.
         */
        if (
            $reportCard &&
            $reportCard->file_path
        ) {
            Storage::disk('public')->delete(
                $reportCard->file_path
            );
        }

        /*
         * Get the school record for the PDF header.
         */
        $school = School::findOrFail($schoolId);

        /*
         * Create a new ReportCard instance when necessary.
         */
        if (!$reportCard) {
            $reportCard = new ReportCard();
        }

        /*
         * Populate the report card record.
         */
        $reportCard->school_id = $schoolId;
        $reportCard->student_id = $result->student_id;
        $reportCard->examination_id = $result->examination_id;
        $reportCard->generated_by = $user->id;
        $reportCard->status = 'generated';
        $reportCard->published_at = null;

        /*
         * Generate the PDF from the Blade template.
         */
        $pdf = Pdf::loadView(
            'admin.report-cards.pdf',
            [
                'school' => $school,
                'reportCard' => $reportCard,
                'result' => $result,
                'marks' => $marks,
            ]
        );

        /*
         * Build a deterministic filename.
         */
        $fileName = sprintf(
            'report-card-%s-%s.pdf',
            $result->student_id,
            $result->examination_id
        );

        $filePath = 'report-cards/' . $fileName;

        /*
         * Store the generated PDF.
         */
        Storage::disk('public')->put(
            $filePath,
            $pdf->output()
        );

        /*
         * Save the file path.
         */
        $reportCard->file_path = $filePath;
        $reportCard->save();

        return redirect()
            ->route(
                'admin.report-cards.show',
                $reportCard
            )
            ->with(
                'success',
                'Report card generated successfully.'
            );
    }

    /**
     * Publish a generated report card.
     */
    public function publish(
        Request $request,
        ReportCard $reportCard
    ): RedirectResponse {
        abort_unless(
            $request->user()->can('report-cards.generate'),
            403
        );

        $this->ensureSchoolAccess(
            $request,
            $reportCard
        );

        /*
         * Prevent publishing an already published report card.
         */
        if ($reportCard->status === 'published') {
            return back()->with(
                'info',
                'This report card is already published.'
            );
        }

        /*
         * A report card must have a PDF before publication.
         */
        if (!$reportCard->file_path) {
            return back()->with(
                'error',
                'This report card does not have a generated PDF.'
            );
        }

        /*
         * Verify the physical PDF still exists.
         */
        if (
            !Storage::disk('public')->exists(
                $reportCard->file_path
            )
        ) {
            return back()->with(
                'error',
                'The report card PDF file could not be found.'
            );
        }

        /*
         * Make sure the underlying result is still published.
         */
        $result = Result::query()
            ->where('student_id', $reportCard->student_id)
            ->where('examination_id', $reportCard->examination_id)
            ->first();

        if (!$result) {
            return back()->with(
                'error',
                'The result associated with this report card could not be found.'
            );
        }

        if ($result->status !== 'published') {
            return back()->with(
                'error',
                'The associated result is no longer published.'
            );
        }

        /*
         * Publish the report card.
         */
        $reportCard->status = 'published';
        $reportCard->published_at = now();
        $reportCard->save();

        return back()->with(
            'success',
            'Report card published successfully.'
        );
    }

    /**
     * Stream the generated PDF.
     */
    public function pdf(
        Request $request,
        ReportCard $reportCard
    ) {
        abort_unless(
            $request->user()->can('report-cards.view'),
            403
        );

        $this->ensureSchoolAccess(
            $request,
            $reportCard
        );

        if (!$reportCard->file_path) {
            abort(
                404,
                'Report card PDF was not found.'
            );
        }

        if (
            !Storage::disk('public')->exists(
                $reportCard->file_path
            )
        ) {
            abort(
                404,
                'Report card PDF file does not exist.'
            );
        }

        return response()->file(
            Storage::disk('public')->path(
                $reportCard->file_path
            )
        );
    }

    /**
     * Ensure the authenticated user belongs to a school.
     */
    private function ensureUserHasSchool(
        ?int $schoolId
    ): void {
        if (!$schoolId) {
            abort(
                403,
                'No school is assigned to the current user.'
            );
        }
    }

    /**
     * Ensure the report card belongs to the user's school.
     */
    private function ensureSchoolAccess(
        Request $request,
        ReportCard $reportCard
    ): void {
        $user = $request->user();

        $this->ensureUserHasSchool(
            $user->school_id
        );

        if (
            (int) $reportCard->school_id !==
            (int) $user->school_id
        ) {
            abort(
                403,
                'You are not authorized to access this report card.'
            );
        }
    }

    /**
     * Create a consistent key for student/examination result lookup.
     */
    private function resultKey(
        int|string $studentId,
        int|string $examinationId
    ): string {
        return $studentId . ':' . $examinationId;
    }
}