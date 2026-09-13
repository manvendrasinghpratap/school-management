<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mark;
use App\Models\Result;
use App\Models\School;
use App\Models\Student;
use App\Models\Transcript;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TranscriptController extends Controller
{
    /**
     * Display generated transcripts.
     */
    public function index(Request $request): View
    {
        abort_unless(
            $request->user()->can('transcripts.view'),
            403
        );

        $user = $request->user();

        $this->ensureUserHasSchool($user->school_id);

        $schoolId = (int) $user->school_id;

        $query = Transcript::query()
            ->with([
                'student',
                'generatedBy',
            ])
            ->where('school_id', $schoolId);

        /*
        |--------------------------------------------------------------------------
        | Student Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('student_id')) {
            $query->where(
                'student_id',
                $request->integer('student_id')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Date Filters
        |--------------------------------------------------------------------------
        */

        if ($request->filled('generated_from')) {
            $query->whereDate(
                'generated_at',
                '>=',
                $request->input('generated_from')
            );
        }

        if ($request->filled('generated_to')) {
            $query->whereDate(
                'generated_at',
                '<=',
                $request->input('generated_to')
            );
        }

        $transcripts = $query
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Students
        |--------------------------------------------------------------------------
        */

        $students = Student::query()
            ->where('school_id', $schoolId)
            ->where('status', 'active')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        return view(
            'admin.transcripts.index',
            compact(
                'transcripts',
                'students'
            )
        );
    }


    /**
     * Generate a transcript for a student.
     */
    public function generate(Request $request): RedirectResponse
    {
        abort_unless(
            $request->user()->can('transcripts.generate'),
            403
        );

        $user = $request->user();

        $this->ensureUserHasSchool($user->school_id);

        $schoolId = (int) $user->school_id;

        /*
        |--------------------------------------------------------------------------
        | Validate Student
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'student_id' => [
                'required',
                'integer',
                'exists:students,id',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Load Student
        |--------------------------------------------------------------------------
        */

        $student = Student::query()
            ->where('id', $validated['student_id'])
            ->where('school_id', $schoolId)
            ->first();

        if (!$student) {
            abort(
                403,
                'You are not authorized to generate a transcript for this student.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Load School
        |--------------------------------------------------------------------------
        */

        $school = School::findOrFail($schoolId);

        /*
        |--------------------------------------------------------------------------
        | Published Results
        |--------------------------------------------------------------------------
        |
        | Only published results are official enough to appear on a transcript.
        |
        */

        $results = Result::query()
            ->with([
                'examination',
            ])
            ->where('student_id', $student->id)
            ->where('status', 'published')
            ->whereHas(
                'examination',
                function ($query) use ($schoolId) {
                    $query->where('school_id', $schoolId);
                }
            )
            ->orderByDesc('id')
            ->get();

        if ($results->isEmpty()) {
            return back()->with(
                'error',
                'No published academic results were found for this student. A transcript cannot be generated.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Load Approved Marks
        |--------------------------------------------------------------------------
        |
        | Marks are grouped by examination so the PDF can present the
        | student's academic history examination-by-examination.
        |
        */

        $examinationIds = $results
            ->pluck('examination_id')
            ->unique()
            ->values();

        $marks = Mark::query()
            ->with([
                'course',
            ])
            ->where('student_id', $student->id)
            ->whereIn('examination_id', $examinationIds)
            ->where('status', 'approved')
            ->orderBy('examination_id')
            ->orderBy('course_id')
            ->get();

        if ($marks->isEmpty()) {
            return back()->with(
                'error',
                'No approved subject marks were found for the student\'s published results. A transcript cannot be generated.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Group Marks By Examination
        |--------------------------------------------------------------------------
        */

        $marksByExamination = $marks->groupBy(
            'examination_id'
        );

        /*
        |--------------------------------------------------------------------------
        | Prepare Transcript Data
        |--------------------------------------------------------------------------
        */

        $transcriptData = $results->map(
            function (Result $result) use ($marksByExamination) {

                return [
                    'result' => $result,
                    'marks' => $marksByExamination->get(
                        $result->examination_id,
                        collect()
                    ),
                ];
            }
        );

        /*
        |--------------------------------------------------------------------------
        | Existing Transcript
        |--------------------------------------------------------------------------
        |
        | We intentionally do not make transcripts unique by student.
        |
        | A student may have more than one generated transcript over time.
        |
        */

        $transcript = new Transcript();

        $transcript->school_id = $schoolId;
        $transcript->student_id = $student->id;
        $transcript->generated_by = $user->id;
        $transcript->generated_at = now();

        /*
        |--------------------------------------------------------------------------
        | Generate PDF
        |--------------------------------------------------------------------------
        */

        $pdf = Pdf::loadView(
            'admin.transcripts.pdf',
            [
                'school' => $school,
                'student' => $student,
                'results' => $results,
                'marks' => $marks,
                'marksByExamination' => $marksByExamination,
                'transcriptData' => $transcriptData,
                'transcript' => $transcript,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | File Name
        |--------------------------------------------------------------------------
        */

        $fileName = sprintf(
            'transcript-%s-%s.pdf',
            $student->id,
            now()->format('YmdHis')
        );

        $filePath = 'transcripts/' . $fileName;

        /*
        |--------------------------------------------------------------------------
        | Store PDF
        |--------------------------------------------------------------------------
        */

        Storage::disk('public')->put(
            $filePath,
            $pdf->output()
        );

        /*
        |--------------------------------------------------------------------------
        | Save Transcript Record
        |--------------------------------------------------------------------------
        */

        $transcript->file_path = $filePath;

        $transcript->save();

        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'admin.transcripts.show',
                $transcript
            )
            ->with(
                'success',
                'Transcript generated successfully.'
            );
    }


    /**
     * Display a transcript.
     */
    public function show(
        Request $request,
        Transcript $transcript
    ): View {
        abort_unless(
            $request->user()->can('transcripts.view'),
            403
        );

        $this->ensureSchoolAccess(
            $request,
            $transcript
        );

        $transcript->load([
            'school',
            'student',
            'generatedBy',
        ]);

        return view(
            'admin.transcripts.show',
            compact('transcript')
        );
    }


    /**
     * Display the stored transcript PDF.
     */
    public function pdf(
        Request $request,
        Transcript $transcript
    )
    {
        abort_unless(
            $request->user()->can('transcripts.view'),
            403
        );

        $this->ensureSchoolAccess(
            $request,
            $transcript
        );

        if (!$transcript->file_path) {
            abort(
                404,
                'Transcript PDF was not found.'
            );
        }

        if (!Storage::disk('public')->exists(
            $transcript->file_path
        )) {
            abort(
                404,
                'Transcript PDF file does not exist.'
            );
        }

        return response()->file(
            Storage::disk('public')->path(
                $transcript->file_path
            )
        );
    }


    /**
     * Soft delete a transcript.
     */
    public function destroy(
        Request $request,
        Transcript $transcript
    ): RedirectResponse {
        abort_unless(
            $request->user()->can('transcripts.generate'),
            403
        );

        $this->ensureSchoolAccess(
            $request,
            $transcript
        );

        /*
        |--------------------------------------------------------------------------
        | Delete Stored PDF
        |--------------------------------------------------------------------------
        */

        if (
            $transcript->file_path &&
            Storage::disk('public')->exists(
                $transcript->file_path
            )
        ) {
            Storage::disk('public')->delete(
                $transcript->file_path
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Soft Delete Database Record
        |--------------------------------------------------------------------------
        */

        $transcript->delete();

        return redirect()
            ->route('admin.transcripts.index')
            ->with(
                'success',
                'Transcript deleted successfully.'
            );
    }


    /**
     * Ensure the current user has a school.
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
     * Ensure transcript belongs to current user's school.
     */
    private function ensureSchoolAccess(
        Request $request,
        Transcript $transcript
    ): void {
        $user = $request->user();

        $this->ensureUserHasSchool(
            $user->school_id
        );

        if (
            (int) $transcript->school_id !==
            (int) $user->school_id
        ) {
            abort(
                403,
                'You are not authorized to access this transcript.'
            );
        }
    }
}