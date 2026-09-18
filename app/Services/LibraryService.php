<?php

namespace App\Services;

use App\Models\Book;
use App\Models\BookCopy;
use App\Models\BookIssue;
use App\Models\LibraryMember;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LibraryService
{
    public function __construct(
        private Wave2SchoolScope $scope
    ) {}

    /**
     * Issue a physical book copy to an active library member.
     */
    public function issue(
        BookCopy $copy,
        LibraryMember $member,
        array $data
    ): BookIssue {
        $schoolId = $this->scope->schoolId();

        if ((int) $copy->school_id !== $schoolId) {
            abort(404);
        }

        if ((int) $member->school_id !== $schoolId) {
            abort(404);
        }

        return DB::transaction(function () use (
            $copy,
            $member,
            $data,
            $schoolId
        ) {
            /*
             * Lock the physical copy so two users cannot issue
             * the same copy simultaneously.
             */
            $lockedCopy = BookCopy::query()
                ->where('id', $copy->id)
                ->where('school_id', $schoolId)
                ->lockForUpdate()
                ->first();

            if (!$lockedCopy) {
                abort(404);
            }

            if ($lockedCopy->status !== 'available') {
                throw ValidationException::withMessages([
                    'book_copy_id' => 'This book copy is not available.'
                ]);
            }

            /*
             * Lock the member because max_books is checked
             * immediately before creating the issue.
             */
            $lockedMember = LibraryMember::query()
                ->where('id', $member->id)
                ->where('school_id', $schoolId)
                ->lockForUpdate()
                ->first();

            if (!$lockedMember) {
                abort(404);
            }

            if ($lockedMember->status !== 'active') {
                throw ValidationException::withMessages([
                    'library_member_id' => 'This library member is not active.'
                ]);
            }

            /*
             * Prevent the same physical copy from being issued
             * more than once while an earlier issue is active.
             */
            $copyAlreadyIssued = BookIssue::query()
                ->where('school_id', $schoolId)
                ->where('book_copy_id', $lockedCopy->id)
                ->whereIn('status', ['issued', 'overdue'])
                ->whereNull('returned_date')
                ->lockForUpdate()
                ->exists();

            if ($copyAlreadyIssued) {
                throw ValidationException::withMessages([
                    'book_copy_id' => 'This book copy is already issued.'
                ]);
            }

            /*
             * Count all currently active loans for this member.
             */
            $activeCount = BookIssue::query()
                ->where('school_id', $schoolId)
                ->where('library_member_id', $lockedMember->id)
                ->whereIn('status', ['issued', 'overdue'])
                ->whereNull('returned_date')
                ->lockForUpdate()
                ->count();

            $maxBooks = (int) $lockedMember->max_books;

            if ($maxBooks > 0 && $activeCount >= $maxBooks) {
                throw ValidationException::withMessages([
                    'library_member_id' =>
                        'The member has reached the maximum number of books.'
                ]);
            }

            /*
             * Make sure the member cannot receive the same book
             * title again while already holding an active copy.
             */
            $sameBookAlreadyIssued = BookIssue::query()
                ->where('school_id', $schoolId)
                ->where('library_member_id', $lockedMember->id)
                ->where('book_id', $lockedCopy->book_id)
                ->whereIn('status', ['issued', 'overdue'])
                ->whereNull('returned_date')
                ->exists();

            if ($sameBookAlreadyIssued) {
                throw ValidationException::withMessages([
                    'book_copy_id' =>
                        'This member already has an active copy of this book.'
                ]);
            }

            $issuedDate = !empty($data['issued_date'])
                ? $data['issued_date']
                : now()->toDateString();

            $dueDate = $data['due_date'] ?? null;

            if (!$dueDate) {
                throw ValidationException::withMessages([
                    'due_date' => 'A due date is required.'
                ]);
            }

            /*
             * Create the issue using only columns that actually
             * exist in the live book_issues table.
             */
            $issue = BookIssue::create([
                'school_id'        => $schoolId,
                'library_member_id'=> $lockedMember->id,
                'book_id'          => $lockedCopy->book_id,
                'book_copy_id'     => $lockedCopy->id,

                'student_id'       => $lockedMember->student_id,
                'staff_id'         => $lockedMember->staff_id,

                'issued_date'      => $issuedDate,
                'due_date'        => $dueDate,
                'due_at'          => $data['due_at'] ?? null,

                'returned_date'    => null,
                'returned_at'     => null,

                'status'           => 'issued',
                'fine'             => 0,

                'issued_by'        => Auth::id(),
            ]);

            /*
             * Mark the physical copy as issued.
             */
            $lockedCopy->update([
                'status' => 'issued',
            ]);

            /*
             * Synchronize book-level availability.
             *
             * Physical copies are authoritative once registered.
             */
            $availableCopies = BookCopy::query()
                ->where('school_id', $schoolId)
                ->where('book_id', $lockedCopy->book_id)
                ->where('status', 'available')
                ->count();

            Book::query()
                ->where('id', $lockedCopy->book_id)
                ->where('school_id', $schoolId)
                ->update([
                    'available_quantity' => $availableCopies,
                ]);

            return $issue->fresh([
                'book',
                'copy',
                'member',
                'student',
                'staff',
                'issuer',
            ]);
        });
    }

    /**
     * Return an issued book.
     */
    public function return(
        BookIssue $issue,
        array $data
    ): BookIssue {
        $schoolId = $this->scope->schoolId();

        if ((int) $issue->school_id !== $schoolId) {
            abort(404);
        }

        return DB::transaction(function () use (
            $issue,
            $data,
            $schoolId
        ) {
            $lockedIssue = BookIssue::query()
                ->where('id', $issue->id)
                ->where('school_id', $schoolId)
                ->lockForUpdate()
                ->first();

            if (!$lockedIssue) {
                abort(404);
            }

            if (!in_array(
                $lockedIssue->status,
                ['issued', 'overdue'],
                true
            )) {
                throw ValidationException::withMessages([
                    'issue' => 'This book issue has already been returned.'
                ]);
            }

            $returnDate = now();

            $fine = (float) ($data['fine'] ?? 0);

            /*
             * If no fine was explicitly supplied, calculate it
             * when the loan is overdue and a daily rate exists.
             */
            if (
                $fine <= 0 &&
                $lockedIssue->due_at &&
                $returnDate->gt($lockedIssue->due_at)
            ) {
                $dailyFine = (float) ($data['daily_fine'] ?? 0);

                if ($dailyFine > 0) {
                    $days = $lockedIssue->due_at
                        ->copy()
                        ->startOfDay()
                        ->diffInDays(
                            $returnDate->copy()->startOfDay()
                        );

                    $fine = $days * $dailyFine;
                }
            }

            $lockedIssue->update([
                'returned_date' => $returnDate->toDateString(),
                'returned_at'   => $returnDate,
                'fine'          => $fine,
                'status'        => 'returned',
            ]);

            /*
             * Lock and release the physical copy.
             */
            $copy = BookCopy::query()
                ->where('id', $lockedIssue->book_copy_id)
                ->where('school_id', $schoolId)
                ->lockForUpdate()
                ->first();

            if ($copy) {
                $copy->update([
                    'status' => 'available',
                ]);

                $availableCopies = BookCopy::query()
                    ->where('school_id', $schoolId)
                    ->where('book_id', $copy->book_id)
                    ->where('status', 'available')
                    ->count();

                Book::query()
                    ->where('id', $copy->book_id)
                    ->where('school_id', $schoolId)
                    ->update([
                        'available_quantity' => $availableCopies,
                    ]);
            }

            return $lockedIssue->fresh([
                'book',
                'copy',
                'member',
                'student',
                'staff',
                'issuer',
            ]);
        });
    }

    /**
     * Renew an active book issue.
     */
    public function renew(
        BookIssue $issue,
        array $data
    ): BookIssue {
        $schoolId = $this->scope->schoolId();

        if ((int) $issue->school_id !== $schoolId) {
            abort(404);
        }

        return DB::transaction(function () use (
            $issue,
            $data,
            $schoolId
        ) {
            $lockedIssue = BookIssue::query()
                ->where('id', $issue->id)
                ->where('school_id', $schoolId)
                ->lockForUpdate()
                ->first();

            if (!$lockedIssue) {
                abort(404);
            }

            if (!in_array(
                $lockedIssue->status,
                ['issued', 'overdue'],
                true
            )) {
                throw ValidationException::withMessages([
                    'issue' => 'Only active issues can be renewed.'
                ]);
            }

            if (empty($data['due_date']) && empty($data['due_at'])) {
                throw ValidationException::withMessages([
                    'due_date' => 'A new due date is required.'
                ]);
            }

            $update = [
                'status' => 'issued',
            ];

            if (!empty($data['due_date'])) {
                $update['due_date'] = $data['due_date'];
            }

            if (array_key_exists('due_at', $data)) {
                $update['due_at'] = $data['due_at'];
            }

            $lockedIssue->update($update);

            return $lockedIssue->fresh([
                'book',
                'copy',
                'member',
                'student',
                'staff',
                'issuer',
            ]);
        });
    }
}