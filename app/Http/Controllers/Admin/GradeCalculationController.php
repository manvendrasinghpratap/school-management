<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Examination;
use App\Models\Grade;
use App\Models\Mark;
use App\Models\Result;
use App\Models\School;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class GradeCalculationController extends Controller
{
    public function index(Request $request): View
    {
        $schoolId = $this->schoolId($request->user());

        $examinations = Examination::query()
            ->where('school_id', $schoolId)
            ->orderByDesc('start_date')
            ->orderByDesc('id')
            ->get();

        $selectedExamination = null;
        $students = collect();

        if ($request->filled('examination_id')) {

            $selectedExamination = Examination::query()
                ->where('school_id', $schoolId)
                ->findOrFail($request->integer('examination_id'));

            $students = Mark::query()
                ->with('student')
                ->where('examination_id', $selectedExamination->id)
                ->select([
                    'student_id',
                    'examination_id',
                ])
                ->selectRaw('COUNT(*) as total_marks')
                ->selectRaw(
                    "SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved_marks"
                )
                ->groupBy('student_id', 'examination_id')
                ->orderBy('student_id')
                ->get();
        }

        return view('admin.grade-calculation.index', compact(
            'examinations',
            'selectedExamination',
            'students'
        ));
    }

    public function calculate(Request $request): RedirectResponse
    {
    $schoolId = $this->schoolId($request->user());

    $validated = $request->validate([
        'examination_id' => [
            'required',
            'integer',
            'exists:examinations,id',
        ],
    ]);

    $examination = Examination::query()
        ->where('school_id', $schoolId)
        ->findOrFail($validated['examination_id']);

    $students = Mark::query()
        ->where('examination_id', $examination->id)
        ->select('student_id')
        ->groupBy('student_id')
        ->pluck('student_id');

    if ($students->isEmpty()) {
        return back()
            ->withInput()
            ->withErrors([
                'examination_id' =>
                    'There are no marks entered for this examination.',
            ]);
    }

    $calculated = 0;

    foreach ($students as $studentId) {

        /*
            * Load all marks for this student in this examination.
            */
        $allMarks = Mark::query()
            ->where('examination_id', $examination->id)
            ->where('student_id', $studentId)
            ->get();

        $totalMarks = $allMarks->count();

        /*
            * Only approved marks are eligible for calculation.
            */
        $approvedMarks = $allMarks->where('status', 'approved');

        $approvedCount = $approvedMarks->count();

        /*
            * Do not calculate a partial result.
            *
            * Every mark belonging to the student for this
            * examination must be approved first.
            */
        if ($totalMarks === 0 || $approvedCount !== $totalMarks) {
            continue;
        }

        /*
            * All marks are approved, so calculate the
            * student's examination result.
            */
        $this->calculateStudentResult(
            $studentId,
            $examination,
            $approvedMarks
        );

        $calculated++;
    }

    /*
        * No student had all required marks approved.
        */
    if ($calculated === 0) {
        return back()
            ->withInput()
            ->withErrors([
                'examination_id' =>
                    'No students have all subject marks approved for calculation.',
            ]);
    }

    return redirect()
        ->route('admin.grade-calculation.index', [
            'examination_id' => $examination->id,
        ])
        ->with(
            'success',
            "{$calculated} student result(s) calculated successfully."
        );
    }

    private function calculateStudentResult(
        int $studentId,
        Examination $examination,
        $approvedMarks
    ): Result {

        return DB::transaction(function () use (
            $studentId,
            $examination,
            $approvedMarks
        ) {

            $totalScore = 0.0;
            $totalMaximumScore = 0.0;

            foreach ($approvedMarks as $mark) {

                $score = (float) $mark->score;
                $maximumScore = (float) $mark->maximum_score;

                if ($maximumScore <= 0) {
                    throw ValidationException::withMessages([
                        'examination_id' =>
                            "Invalid maximum score found for mark #{$mark->id}.",
                    ]);
                }

                if ($score < 0 || $score > $maximumScore) {
                    throw ValidationException::withMessages([
                        'examination_id' =>
                            "Invalid score found for mark #{$mark->id}.",
                    ]);
                }

                $percentage = ($score / $maximumScore) * 100;

                $grade = $this->findGrade(
                    $examination->school_id,
                    $percentage
                );

                if (!$grade) {
                    throw ValidationException::withMessages([
                        'examination_id' =>
                            "No grading range is configured for {$percentage}%."
                    ]);
                }

                /*
                 * Keep the subject grade synchronized with
                 * the current Grading Setup.
                 */
                if ($mark->grade !== $grade->code) {
                    $mark->update([
                        'grade' => $grade->code,
                    ]);
                }

                $totalScore += $score;
                $totalMaximumScore += $maximumScore;
            }

            if ($totalMaximumScore <= 0) {
                throw ValidationException::withMessages([
                    'examination_id' =>
                        'The total maximum score must be greater than zero.',
                ]);
            }

            /*
             * Weighted examination average.
             *
             * Example:
             * 175 / 200 × 100 = 87.50%
             */
            $average = ($totalScore / $totalMaximumScore) * 100;

            $overallGrade = $this->findGrade(
                $examination->school_id,
                $average
            );

            if (!$overallGrade) {
                throw ValidationException::withMessages([
                    'examination_id' =>
                        "No grading range is configured for {$average}%."
                ]);
            }

            $result = Result::query()->firstOrNew([
                'student_id' => $studentId,
                'examination_id' => $examination->id,
            ]);

            /*
             * Recalculation returns the result to draft.
             *
             * Existing approval/publication must not survive
             * a new calculation.
             */
            $result->total_score = round($totalScore, 2);
            $result->average = round($average, 2);
            $result->grade = $overallGrade->code;
            $result->status = 'draft';
            $result->approved_by = null;
            $result->published_by = null;
            $result->save();

            return $result;
        });
    }

    private function findGrade(
        int $schoolId,
        float $percentage
    ): ?Grade {

        return Grade::query()
            ->where('school_id', $schoolId)
            ->where('minimum_score', '<=', $percentage)
            ->where('maximum_score', '>=', $percentage)
            ->orderBy('minimum_score', 'desc')
            ->first();
    }

    private function schoolId($user): int
    {
        if (!$user) {
            abort(403, 'Unauthenticated.');
        }

        if (!empty($user->school_id)) {
            return (int) $user->school_id;
        }

        $schoolId = School::query()->value('id');

        if (!$schoolId) {
            abort(403, 'No school is configured.');
        }

        return (int) $schoolId;
    }
}