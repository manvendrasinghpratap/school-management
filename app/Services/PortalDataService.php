<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Guardian;
use App\Models\Invoice;
use App\Models\Mark;
use App\Models\ReportCard;
use App\Models\Result;
use App\Models\Staff;
use App\Models\Student;
use App\Models\StudentCourse;
use App\Models\StudentDocument;
use App\Models\StudentEnrollment;
use App\Models\Timetable;
use App\Models\Transcript;
use App\Models\CourseAssignment;
use App\Models\Courses;
use App\Models\Classes;
use App\Models\Section;
use App\Models\Examination;
use App\Models\Leave;
use Illuminate\Support\Collection;

class PortalDataService
{
    public function schoolId(int $userId): int
    {
        return (int) \App\Models\User::query()->whereKey($userId)->value('school_id');
    }

    public function studentForUser(\App\Models\User $user): Student
    {
        return Student::query()->where('school_id', $user->school_id)->where('user_id', $user->id)->firstOrFail();
    }

    public function guardianForUser(\App\Models\User $user): Guardian
    {
        return Guardian::query()->where('school_id', $user->school_id)->where('user_id', $user->id)->firstOrFail();
    }

    public function staffForUser(\App\Models\User $user): Staff
    {
        return Staff::query()->where('school_id', $user->school_id)->where('user_id', $user->id)->firstOrFail();
    }

    public function studentIdsForGuardian(Guardian $guardian): array
    {
        return $guardian->students()->where('students.school_id', $guardian->school_id)->pluck('students.id')->map(fn ($id) => (int) $id)->all();
    }

    public function studentDashboard(Student $student): array
    {
        $schoolId = (int) $student->school_id;
        $attendance = Attendance::query()->where('school_id', $schoolId)->where('student_id', $student->id);
        $enrollment = StudentEnrollment::query()->with(['academicYear', 'term', 'class', 'section'])->where('school_id', $schoolId)->where('student_id', $student->id)->where('status', 'active')->latest('enrollment_date')->first();

        return [
            'student' => $student->load('guardians'),
            'enrollment' => $enrollment,
            'attendance' => [
                'total' => (clone $attendance)->count(),
                'present' => (clone $attendance)->where('status', 'present')->count(),
                'absent' => (clone $attendance)->where('status', 'absent')->count(),
                'late' => (clone $attendance)->where('status', 'late')->count(),
                'excused' => (clone $attendance)->where('status', 'excused')->count(),
            ],
            'outstanding' => Invoice::query()->where('school_id', $schoolId)->where('student_id', $student->id)->whereIn('status', ['unpaid', 'partial'])->sum('balance'),
            'unreadNotifications' => \App\Models\PortalNotification::query()->where('user_id', $student->user_id)->unread()->count(),
        ];
    }

    public function studentAcademic(Student $student): array
    {
        $schoolId = (int) $student->school_id;
        return [
            'enrollments' => StudentEnrollment::with(['academicYear', 'term', 'class', 'section'])->where('school_id', $schoolId)->where('student_id', $student->id)->latest('enrollment_date')->get(),
            'courses' => StudentCourse::with(['course', 'academicYear', 'term'])->where('student_id', $student->id)->get(),
            'timetable' => Timetable::with(['course', 'class', 'section', 'academicYear', 'term'])->where('school_id', $schoolId)->whereIn('class_id', StudentEnrollment::where('school_id', $schoolId)->where('student_id', $student->id)->pluck('class_id'))->where(function ($q) use ($student) {
                $sectionIds = StudentEnrollment::where('school_id', $student->school_id)->where('student_id', $student->id)->pluck('section_id')->filter();
                if ($sectionIds->isNotEmpty()) $q->whereIn('section_id', $sectionIds)->orWhereNull('section_id');
            })->orderByRaw("FIELD(day_of_week,'monday','tuesday','wednesday','thursday','friday','saturday','sunday')")->orderBy('start_time')->get(),
        ];
    }

    public function studentResults(Student $student): array
    {
        $schoolId = (int) $student->school_id;
        return [
            'examinations' => Examination::where('school_id', $schoolId)->whereIn('status', ['completed', 'published'])->latest('start_date')->get(),
            'marks' => Mark::with(['examination', 'course'])->where('student_id', $student->id)->whereIn('status', ['approved'])->latest()->get(),
            'results' => Result::with('examination')->where('student_id', $student->id)->where('status', 'published')->latest()->get(),
            'reportCards' => ReportCard::with('examination')->where('school_id', $schoolId)->where('student_id', $student->id)->where('status', 'published')->latest()->get(),
            'transcripts' => Transcript::where('school_id', $schoolId)->where('student_id', $student->id)->latest()->get(),
        ];
    }

    public function studentFinance(Student $student): array
    {
        $schoolId = (int) $student->school_id;
        $invoices = Invoice::with(['items', 'payments'])->where('school_id', $schoolId)->where('student_id', $student->id)->latest('invoice_date')->get();
        return ['invoices' => $invoices, 'payments' => $invoices->flatMap->payments->sortByDesc('paid_at')->values()];
    }

    public function studentDocuments(Student $student): Collection
    {
        return StudentDocument::whereHas('student', fn ($q) => $q->where('school_id', $student->school_id))->where('student_id', $student->id)->latest()->get();
    }

    public function guardianDashboard(Guardian $guardian): array
    {
        $students = $guardian->students()->where('students.school_id', $guardian->school_id)->with(['enrollments' => fn ($q) => $q->where('status', 'active')->latest('enrollment_date')->with(['academicYear', 'class', 'section'])])->get();
        $ids = $students->pluck('id');
        return [
            'guardian' => $guardian,
            'students' => $students,
            'outstanding' => Invoice::where('school_id', $guardian->school_id)->whereIn('student_id', $ids)->whereIn('status', ['unpaid', 'partial'])->sum('balance'),
            'unreadNotifications' => \App\Models\PortalNotification::where('user_id', $guardian->user_id)->unread()->count(),
        ];
    }

    public function teacherDashboard(Staff $staff): array
    {
        $schoolId = (int) $staff->school_id;
        $assignments = CourseAssignment::query()->where('staff_id', $staff->id)->where('academic_year_id', '>', 0)->get();
        $studentCount = StudentEnrollment::where('school_id', $schoolId)->whereIn('class_id', $assignments->pluck('class_id'))->where('status', 'active')->distinct('student_id')->count('student_id');
        return [
            'staff' => $staff->load(['department', 'user']),
            'assignments' => $assignments->map(function ($a) use ($schoolId) {
                return [
                    'id' => $a->id,
                    'course' => Courses::where('school_id', $schoolId)->find($a->course_id),
                    'class' => Classes::where('school_id', $schoolId)->find($a->class_id),
                    'section' => $a->section_id ? Section::whereKey($a->section_id)->first() : null,
                ];
            }),
            'studentCount' => $studentCount,
            'timetable' => Timetable::with(['course', 'class', 'section', 'academicYear', 'term'])->where('school_id', $schoolId)->where('staff_id', $staff->id)->orderByRaw("FIELD(day_of_week,'monday','tuesday','wednesday','thursday','friday','saturday','sunday')")->orderBy('start_time')->get(),
            'pendingLeaves' => Leave::where('school_id', $schoolId)->where('staff_id', $staff->id)->where('status', 'pending')->count(),
            'unreadNotifications' => \App\Models\PortalNotification::where('user_id', $staff->user_id)->unread()->count(),
        ];
    }

    public function teacherStudents(Staff $staff): Collection
    {
        $assignment = CourseAssignment::where('staff_id', $staff->id)->get();
        return Student::where('school_id', $staff->school_id)->whereIn('id', StudentEnrollment::where('school_id', $staff->school_id)->whereIn('class_id', $assignment->pluck('class_id'))->where('status', 'active')->pluck('student_id'))->with('enrollments')->orderBy('last_name')->get();
    }
}
