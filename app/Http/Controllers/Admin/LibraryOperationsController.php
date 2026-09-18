<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreIssueRequest;
use App\Http\Requests\StoreLibraryMemberRequest;
use App\Models\AcademicYears;
use App\Models\BookCopy;
use App\Models\BookIssue;
use App\Models\Classes;
use App\Models\LibraryMember;
use App\Models\Section;
use App\Models\Student;
use App\Models\Staff;
use App\Models\StudentEnrollment;
use App\Services\LibraryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class LibraryOperationsController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | School
    |--------------------------------------------------------------------------
    */

    private function schoolId(): int
    {
        return (int) Auth::user()->school_id;
    }

    /*
    |--------------------------------------------------------------------------
    | Library Members
    |--------------------------------------------------------------------------
    */

    public function members(Request $request)
    {
        $schoolId = $this->schoolId();

        $query = LibraryMember::query()
            ->where('school_id', $schoolId)
            ->with([
                'student',
                'staff',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->input('search'));

            $query->where(function ($q) use ($search) {

                $q->where(
                    'member_number',
                    'like',
                    '%' . $search . '%'
                );

                $q->orWhereHas('student', function ($student) use ($search) {

                    $student->where(function ($q) use ($search) {

                        $q->where(
                            'first_name',
                            'like',
                            '%' . $search . '%'
                        )
                        ->orWhere(
                            'middle_name',
                            'like',
                            '%' . $search . '%'
                        )
                        ->orWhere(
                            'last_name',
                            'like',
                            '%' . $search . '%'
                        );
                    });
                });

                $q->orWhereHas('staff', function ($staff) use ($search) {

                    $staff->where(function ($q) use ($search) {

                        $q->where(
                            'first_name',
                            'like',
                            '%' . $search . '%'
                        )
                        ->orWhere(
                            'middle_name',
                            'like',
                            '%' . $search . '%'
                        )
                        ->orWhere(
                            'last_name',
                            'like',
                            '%' . $search . '%'
                        );
                    });
                });
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->input('status')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Holder Type
        |--------------------------------------------------------------------------
        */

        if ($request->input('holder_type') === 'student') {
            $query->whereNotNull('student_id');
        }

        if ($request->input('holder_type') === 'staff') {
            $query->whereNotNull('staff_id');
        }

        $members = $query
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view(
            'admin.library.members.index',
            compact('members')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Create Member
    |--------------------------------------------------------------------------
    */

    public function createMember()
    {
        $schoolId = $this->schoolId();

        /*
         * Student hierarchy:
         *
         * Academic Year
         *      ↓
         * Class
         *      ↓
         * Section
         *      ↓
         * Student
         *
         * Academic years and staff are loaded initially.
         * Classes, sections and students are loaded dynamically.
         */

        $academicYears = AcademicYears::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->orderByDesc('start_date')
            ->get();

        $staff = Staff::query()
            ->where('school_id', $schoolId)
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        return view(
            'admin.library.members.create',
            compact(
                'academicYears',
                'staff'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Academic Year → Class
    |--------------------------------------------------------------------------
    */

    public function filterMemberClasses(Request $request)
    {
        $schoolId = $this->schoolId();

        $validated = $request->validate([
            'academic_year_id' => [
                'required',
                'integer',
            ],
        ]);

        $academicYear = AcademicYears::query()
            ->where('id', $validated['academic_year_id'])
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->firstOrFail();

        $classes = Classes::query()
            ->join(
                'student_enrollments',
                'student_enrollments.class_id',
                '=',
                'classes.id'
            )
            ->where(
                'student_enrollments.school_id',
                $schoolId
            )
            ->where(
                'student_enrollments.academic_year_id',
                $academicYear->id
            )
            ->where(
                'student_enrollments.status',
                'active'
            )
            ->where(
                'classes.school_id',
                $schoolId
            )
            ->where(
                'classes.is_active',
                true
            )
            ->select([
                'classes.id',
                'classes.name',
                'classes.code',
            ])
            ->distinct()
            ->orderBy('classes.name')
            ->get();

        return response()->json([
            'classes' => $classes,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Class → Section
    |--------------------------------------------------------------------------
    */

    public function filterMemberSections(Request $request)
    {
        $schoolId = $this->schoolId();

        $validated = $request->validate([
            'class_id' => [
                'required',
                'integer',
            ],
        ]);

        $class = Classes::query()
            ->where('id', $validated['class_id'])
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->firstOrFail();

        $sections = Section::query()
            ->where(
                'class_id',
                $class->id
            )
            ->where(
                'is_active',
                true
            )
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'code',
            ]);

        return response()->json([
            'sections' => $sections,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Academic Year + Class + Section → Student
    |--------------------------------------------------------------------------
    */

    public function filterMemberStudents(Request $request)
    {
        $schoolId = $this->schoolId();

        $validated = $request->validate([
            'academic_year_id' => [
                'required',
                'integer',
            ],

            'class_id' => [
                'required',
                'integer',
            ],

            'section_id' => [
                'required',
                'integer',
            ],
        ]);

        /*
         * Verify academic year.
         */

        $academicYear = AcademicYears::query()
            ->where('id', $validated['academic_year_id'])
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->firstOrFail();

        /*
         * Verify class.
         */

        $class = Classes::query()
            ->where('id', $validated['class_id'])
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->firstOrFail();

        /*
         * Verify section.
         */

        $section = Section::query()
            ->where('id', $validated['section_id'])
            ->where('class_id', $class->id)
            ->where('is_active', true)
            ->firstOrFail();

        /*
         * Enrollment is the source of truth for:
         *
         * Academic Year + Class + Section + Student
         */

        $students = StudentEnrollment::query()
            ->where(
                'student_enrollments.school_id',
                $schoolId
            )
            ->where(
                'student_enrollments.academic_year_id',
                $academicYear->id
            )
            ->where(
                'student_enrollments.class_id',
                $class->id
            )
            ->where(
                'student_enrollments.section_id',
                $section->id
            )
            ->where(
                'student_enrollments.status',
                'active'
            )
            ->join(
                'students',
                'students.id',
                '=',
                'student_enrollments.student_id'
            )
            ->where(
                'students.school_id',
                $schoolId
            )
            ->where(
                'students.status',
                'active'
            )
            ->select([
                'students.id',
                'students.student_number',
                'students.admission_number',
                'students.first_name',
                'students.middle_name',
                'students.last_name',
            ])
            ->orderBy('students.first_name')
            ->orderBy('students.last_name')
            ->get();

        return response()->json([
            'students' => $students,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Store Member
    |--------------------------------------------------------------------------
    */

    public function storeMember(
        StoreLibraryMemberRequest $request
    ) {
        $schoolId = $this->schoolId();

        $data = $request->validated();

        /*
         * Keep academic placement fields available for
         * verification even if the request class does not
         * include them in validated().
         */

        if ($request->filled('academic_year_id')) {
            $data['academic_year_id'] =
                $request->input('academic_year_id');
        }

        if ($request->filled('class_id')) {
            $data['class_id'] =
                $request->input('class_id');
        }

        if ($request->filled('section_id')) {
            $data['section_id'] =
                $request->input('section_id');
        }

        if ($request->has('student_id')) {
            $data['student_id'] =
                $request->input('student_id');
        }

        if ($request->has('staff_id')) {
            $data['staff_id'] =
                $request->input('staff_id');
        }

        $holderType = $data['holder_type'];

        /*
        |--------------------------------------------------------------------------
        | STUDENT
        |--------------------------------------------------------------------------
        */

        if ($holderType === 'student') {

            if (
                empty($data['academic_year_id']) ||
                empty($data['class_id']) ||
                empty($data['section_id'])
            ) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'academic_year_id' =>
                            'Please select academic year, class and section.',
                    ]);
            }

            if (empty($data['student_id'])) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'student_id' =>
                            'Please select a student.',
                    ]);
            }

            /*
             * Verify Academic Year.
             */

            $academicYear = AcademicYears::query()
                ->where(
                    'id',
                    $data['academic_year_id']
                )
                ->where(
                    'school_id',
                    $schoolId
                )
                ->where(
                    'is_active',
                    true
                )
                ->firstOrFail();

            /*
             * Verify Class.
             */

            $class = Classes::query()
                ->where(
                    'id',
                    $data['class_id']
                )
                ->where(
                    'school_id',
                    $schoolId
                )
                ->where(
                    'is_active',
                    true
                )
                ->firstOrFail();

            /*
             * Verify Section.
             */

            $section = Section::query()
                ->where(
                    'id',
                    $data['section_id']
                )
                ->where(
                    'class_id',
                    $class->id
                )
                ->where(
                    'is_active',
                    true
                )
                ->firstOrFail();

            /*
             * Verify selected student's exact enrollment.
             */

            $enrollmentExists = StudentEnrollment::query()
                ->where(
                    'school_id',
                    $schoolId
                )
                ->where(
                    'academic_year_id',
                    $academicYear->id
                )
                ->where(
                    'class_id',
                    $class->id
                )
                ->where(
                    'section_id',
                    $section->id
                )
                ->where(
                    'student_id',
                    $data['student_id']
                )
                ->where(
                    'status',
                    'active'
                )
                ->exists();

            if (!$enrollmentExists) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'student_id' =>
                            'The selected student does not belong to the selected academic year, class and section.',
                    ]);
            }

            /*
             * Verify student belongs to current school
             * and is active.
             */

            abort_unless(
                Student::query()
                    ->where(
                        'school_id',
                        $schoolId
                    )
                    ->where(
                        'status',
                        'active'
                    )
                    ->whereKey(
                        $data['student_id']
                    )
                    ->exists(),
                404
            );

            /*
             * Prevent duplicate active membership.
             */

            $alreadyMember = LibraryMember::query()
                ->where(
                    'school_id',
                    $schoolId
                )
                ->where(
                    'student_id',
                    $data['student_id']
                )
                ->where(
                    'status',
                    'active'
                )
                ->exists();

            if ($alreadyMember) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'student_id' =>
                            'This student already has an active library membership.',
                    ]);
            }

            $data['staff_id'] = null;
        }

        /*
        |--------------------------------------------------------------------------
        | STAFF
        |--------------------------------------------------------------------------
        */

        else {

            if (empty($data['staff_id'])) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'staff_id' =>
                            'Please select a staff member.',
                    ]);
            }

            /*
             * Verify staff belongs to current school.
             */

            abort_unless(
                Staff::query()
                    ->where(
                        'school_id',
                        $schoolId
                    )
                    ->whereKey(
                        $data['staff_id']
                    )
                    ->exists(),
                404
            );

            /*
             * Prevent duplicate active membership.
             */

            $alreadyMember = LibraryMember::query()
                ->where(
                    'school_id',
                    $schoolId
                )
                ->where(
                    'staff_id',
                    $data['staff_id']
                )
                ->where(
                    'status',
                    'active'
                )
                ->exists();

            if ($alreadyMember) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'staff_id' =>
                            'This staff member already has an active library membership.',
                    ]);
            }

            $data['student_id'] = null;
        }

        /*
        |--------------------------------------------------------------------------
        | Remove form-only fields
        |--------------------------------------------------------------------------
        */

        unset(
            $data['holder_type'],
            $data['academic_year_id'],
            $data['class_id'],
            $data['section_id']
        );

        $data['school_id'] = $schoolId;
        $data['created_by'] = Auth::id();

        $data['member_number'] =
            $this->generateMemberNumber($schoolId);

        DB::transaction(function () use ($data) {

            LibraryMember::create($data);
        });

        return redirect()
            ->route('admin.library.members.index')
            ->with(
                'success',
                'Library member created successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Edit Member
    |--------------------------------------------------------------------------
    */

    public function editMember(
        LibraryMember $member
    ) {
        abort_unless(
            (int) $member->school_id === $this->schoolId(),
            404
        );

        $schoolId = $this->schoolId();

        $academicYears = AcademicYears::query()
            ->where(
                'school_id',
                $schoolId
            )
            ->where(
                'is_active',
                true
            )
            ->orderByDesc('start_date')
            ->get();

        $staff = Staff::query()
            ->where(
                'school_id',
                $schoolId
            )
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        $students = Student::query()
            ->where(
                'school_id',
                $schoolId
            )
            ->where(
                'status',
                'active'
            )
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        $holderType = $member->student_id
            ? 'student'
            : 'staff';

        $studentEnrollment = null;

        if ($member->student_id) {

            $studentEnrollment = StudentEnrollment::query()
                ->where(
                    'school_id',
                    $schoolId
                )
                ->where(
                    'student_id',
                    $member->student_id
                )
                ->where(
                    'status',
                    'active'
                )
                ->orderByDesc('id')
                ->first();
        }

        return view(
            'admin.library.members.edit',
            compact(
                'member',
                'academicYears',
                'staff',
                'students',
                'holderType',
                'studentEnrollment'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Update Member
    |--------------------------------------------------------------------------
    */

    public function updateMember(
        Request $request,
        LibraryMember $member
    ) {
        abort_unless(
            (int) $member->school_id === $this->schoolId(),
            404
        );

        $schoolId = $this->schoolId();

        $data = $request->validate([

            'holder_type' => [
                'required',
                'in:student,staff',
            ],

            'student_id' => [
                'nullable',
                'integer',

                Rule::exists(
                    'students',
                    'id'
                )->where(
                    fn ($query) =>
                    $query->where(
                        'school_id',
                        $schoolId
                    )
                ),
            ],

            'staff_id' => [
                'nullable',
                'integer',

                Rule::exists(
                    'staff',
                    'id'
                )->where(
                    fn ($query) =>
                    $query->where(
                        'school_id',
                        $schoolId
                    )
                ),
            ],

            'joined_at' => [
                'required',
                'date',
            ],

            'expiry_date' => [
                'nullable',
                'date',
                'after_or_equal:joined_at',
            ],

            'max_books' => [
                'required',
                'integer',
                'min:1',
            ],

            'status' => [
                'required',
                'in:active,inactive,suspended,expired',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Student
        |--------------------------------------------------------------------------
        */

        if ($data['holder_type'] === 'student') {

            if (empty($data['student_id'])) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'student_id' =>
                            'Please select a student.',
                    ]);
            }

            $data['staff_id'] = null;

            $duplicate = LibraryMember::query()
                ->where(
                    'school_id',
                    $schoolId
                )
                ->where(
                    'student_id',
                    $data['student_id']
                )
                ->where(
                    'status',
                    'active'
                )
                ->where(
                    'id',
                    '!=',
                    $member->id
                )
                ->exists();

            if (
                $duplicate &&
                $data['status'] === 'active'
            ) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'student_id' =>
                            'This student already has another active library membership.',
                    ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Staff
        |--------------------------------------------------------------------------
        */

        if ($data['holder_type'] === 'staff') {

            if (empty($data['staff_id'])) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'staff_id' =>
                            'Please select a staff member.',
                    ]);
            }

            $data['student_id'] = null;

            $duplicate = LibraryMember::query()
                ->where(
                    'school_id',
                    $schoolId
                )
                ->where(
                    'staff_id',
                    $data['staff_id']
                )
                ->where(
                    'status',
                    'active'
                )
                ->where(
                    'id',
                    '!=',
                    $member->id
                )
                ->exists();

            if (
                $duplicate &&
                $data['status'] === 'active'
            ) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'staff_id' =>
                            'This staff member already has another active library membership.',
                    ]);
            }
        }

        unset($data['holder_type']);

        $member->update($data);

        return redirect()
            ->route(
                'admin.library.members.index'
            )
            ->with(
                'success',
                'Library member updated successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Member
    |--------------------------------------------------------------------------
    */

    public function destroyMember(
        LibraryMember $member
    ) {
        abort_unless(
            (int) $member->school_id === $this->schoolId(),
            404
        );

        /*
        |--------------------------------------------------------------------------
        | Protect members with active issues
        |--------------------------------------------------------------------------
        */

        $hasActiveIssuesQuery = BookIssue::query()
            ->where(
                'school_id',
                $this->schoolId()
            )
            ->whereNull('returned_date');

        if ($member->student_id) {

            $hasActiveIssuesQuery
                ->where(
                    'student_id',
                    $member->student_id
                );

        } elseif ($member->staff_id) {

            $hasActiveIssuesQuery
                ->where(
                    'staff_id',
                    $member->staff_id
                );

        } else {

            $hasActiveIssuesQuery
                ->whereRaw('1 = 0');
        }

        $hasActiveIssues =
            $hasActiveIssuesQuery->exists();

        if ($hasActiveIssues) {

            return back()
                ->withErrors([
                    'member' =>
                        'This member cannot be deleted because they have active issued books. Return all books first.',
                ]);
        }

        $member->delete();

        return redirect()
            ->route(
                'admin.library.members.index'
            )
            ->with(
                'success',
                'Library member deleted successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Member Number
    |--------------------------------------------------------------------------
    */

    private function generateMemberNumber(
        int $schoolId
    ): string {

        do {

            $number =
                'LIB-' .
                now()->format('Ymd') .
                '-' .
                strtoupper(
                    Str::random(8)
                );

        } while (
            LibraryMember::query()
                ->where(
                    'school_id',
                    $schoolId
                )
                ->where(
                    'member_number',
                    $number
                )
                ->exists()
        );

        return $number;
    }

    /*
    |--------------------------------------------------------------------------
    | Issues
    |--------------------------------------------------------------------------
    */

    /*
|--------------------------------------------------------------------------
| Issues
|--------------------------------------------------------------------------
*/

public function issues(Request $request)
{
    $schoolId = $this->schoolId();

    $query = BookIssue::query()
        ->where('school_id', $schoolId)
        ->with([
            'book',
            'copy.book',
            'member.student',
            'member.staff',
        ]);

    /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    |
    | Search by:
    | - Book title
    | - ISBN
    | - Member number
    | - Student first/middle/last name
    | - Staff first/last name
    |
    */

    if ($request->filled('search')) {

        $search = trim($request->input('search'));

        $query->where(function ($q) use ($search) {

            /*
            |--------------------------------------------------------------------------
            | Book
            |--------------------------------------------------------------------------
            */

            $q->whereHas('book', function ($book) use ($search) {

                $book->where(function ($query) use ($search) {

                    $query->where(
                        'title',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'isbn',
                        'like',
                        '%' . $search . '%'
                    );
                });
            });

            /*
            |--------------------------------------------------------------------------
            | Member Number
            |--------------------------------------------------------------------------
            */

            $q->orWhereHas('member', function ($member) use ($search) {

                $member->where(
                    'member_number',
                    'like',
                    '%' . $search . '%'
                );
            });

            /*
            |--------------------------------------------------------------------------
            | Student Member
            |--------------------------------------------------------------------------
            */

            $q->orWhereHas('member.student', function ($student) use ($search) {

                $student->where(function ($query) use ($search) {

                    $query->where(
                        'first_name',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'middle_name',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'last_name',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'student_number',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'admission_number',
                        'like',
                        '%' . $search . '%'
                    );
                });
            });

            /*
            |--------------------------------------------------------------------------
            | Staff Member
            |--------------------------------------------------------------------------
            */

            $q->orWhereHas('member.staff', function ($staff) use ($search) {

                $staff->where(function ($query) use ($search) {

                    $query->where(
                        'first_name',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'middle_name',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'last_name',
                        'like',
                        '%' . $search . '%'
                    );
                });
            });
        });
    }

    $issues = $query
        ->latest('id')
        ->paginate(20)
        ->withQueryString();

    return view(
        'admin.library.issues.index',
        compact('issues')
    );
}

    /*
    |--------------------------------------------------------------------------
    | Create Issue
    |--------------------------------------------------------------------------
    */

    public function createIssue()
    {
        $schoolId = $this->schoolId();

        /*
        |--------------------------------------------------------------------------
        | Available Physical Copies
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | We issue physical copies, not just books.
        |
        | Only copies with status = available are shown.
        |
        */

        $copies = BookCopy::query()
            ->where(
                'school_id',
                $schoolId
            )
            ->where(
                'status',
                'available'
            )
            ->with([
                'book',
            ])
            ->orderBy('book_id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Active Library Members
        |--------------------------------------------------------------------------
        */

        $members = LibraryMember::query()
            ->where(
                'school_id',
                $schoolId
            )
            ->where(
                'status',
                'active'
            )
            ->with([
                'student',
                'staff',
            ])
            ->orderBy('member_number')
            ->get();

        return view(
            'admin.library.issues.create',
            compact(
                'copies',
                'members'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Store Issue
    |--------------------------------------------------------------------------
    */

    public function storeIssue(
        StoreIssueRequest $request,
        LibraryService $service
    ) {

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        |
        | StoreIssueRequest already performs school-scoped validation.
        |
        */

        $data = $request->validated();

        $schoolId = $this->schoolId();

        /*
        |--------------------------------------------------------------------------
        | Verify Physical Copy
        |--------------------------------------------------------------------------
        */

        $copy = BookCopy::query()
            ->where(
                'school_id',
                $schoolId
            )
            ->where(
                'status',
                'available'
            )
            ->with('book')
            ->findOrFail(
                $data['book_copy_id']
            );

        /*
        |--------------------------------------------------------------------------
        | Verify Library Member
        |--------------------------------------------------------------------------
        */

        $member = LibraryMember::query()
            ->where(
                'school_id',
                $schoolId
            )
            ->where(
                'status',
                'active'
            )
            ->findOrFail(
                $data['library_member_id']
            );

        /*
        |--------------------------------------------------------------------------
        | Issue Through Service
        |--------------------------------------------------------------------------
        |
        | LibraryService performs the transaction and final
        | concurrency/business-rule checks.
        |
        */

        $service->issue(
            $copy,
            $member,
            $data
        );

        return redirect()
            ->route(
                'admin.library.issues.index'
            )
            ->with(
                'success',
                'Book issued successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Return Issue
    |--------------------------------------------------------------------------
    */

    public function returnIssue(
        BookIssue $issue,
        Request $request,
        LibraryService $service
    ) {

        /*
        |--------------------------------------------------------------------------
        | School Isolation
        |--------------------------------------------------------------------------
        */

        abort_unless(
            (int) $issue->school_id ===
                $this->schoolId(),
            404
        );

        /*
        |--------------------------------------------------------------------------
        | Return Validation
        |--------------------------------------------------------------------------
        */

        $data = $request->validate([

            'daily_fine' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'fine' => [
                'nullable',
                'numeric',
                'min:0',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Return Through Service
        |--------------------------------------------------------------------------
        */

        $service->return(
            $issue,
            [
                'daily_fine' =>
                    $data['daily_fine'] ?? 0,

                'fine' =>
                    $data['fine'] ?? 0,
            ]
        );

        return back()
            ->with(
                'success',
                'Book returned successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Renew Issue
    |--------------------------------------------------------------------------
    */

    public function renewIssue(
        BookIssue $issue,
        Request $request,
        LibraryService $service
    ) {

        /*
        |--------------------------------------------------------------------------
        | School Isolation
        |--------------------------------------------------------------------------
        */

        abort_unless(
            (int) $issue->school_id ===
                $this->schoolId(),
            404
        );

        /*
        |--------------------------------------------------------------------------
        | Renew Validation
        |--------------------------------------------------------------------------
        */

        $data = $request->validate([

            'due_at' => [
                'required',
                'date',
                'after:now',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Keep due_date and due_at synchronized.
        |--------------------------------------------------------------------------
        */

        $dueAt = \Carbon\Carbon::parse(
            $data['due_at']
        );

        /*
        |--------------------------------------------------------------------------
        | Renew Through Service
        |--------------------------------------------------------------------------
        */

        $service->renew(
            $issue,
            [
                'due_at' =>
                    $dueAt->format('Y-m-d H:i:s'),

                'due_date' =>
                    $dueAt->format('Y-m-d'),
            ]
        );

        return back()
            ->with(
                'success',
                'Book renewed successfully.'
            );
    }
}