<?php

namespace App\Services;

use App\Models\Book;
use App\Models\BookReservation;
use App\Models\LibraryMember;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LibraryReservationService
{
    public function __construct(private Wave2SchoolScope $scope)
    {
    }

    public function create(
        Book $book,
        LibraryMember $member,
        array $data
    ): BookReservation {
        $schoolId = $this->scope->schoolId();

        if (
            (int) $book->school_id !== $schoolId ||
            (int) $member->school_id !== $schoolId
        ) {
            abort(404);
        }

        return DB::transaction(function () use (
            $book,
            $member,
            $data,
            $schoolId
        ) {
            $lockedBook = Book::query()
                ->where('id', $book->id)
                ->where('school_id', $schoolId)
                ->lockForUpdate()
                ->first();

            $lockedMember = LibraryMember::query()
                ->where('id', $member->id)
                ->where('school_id', $schoolId)
                ->lockForUpdate()
                ->first();

            if (!$lockedBook || !$lockedMember) {
                abort(404);
            }

            if ($lockedMember->status !== 'active') {
                throw ValidationException::withMessages([
                    'library_member_id' =>
                        'Only active library members can make reservations.',
                ]);
            }

            $existing = BookReservation::query()
                ->where('school_id', $schoolId)
                ->where('book_id', $lockedBook->id)
                ->where('library_member_id', $lockedMember->id)
                ->where('status', 'active')
                ->lockForUpdate()
                ->first();

            if ($existing) {
                throw ValidationException::withMessages([
                    'library_member_id' =>
                        'This member already has an active reservation for this book.',
                ]);
            }

            $reservedAt = !empty($data['reserved_at'])
                ? $data['reserved_at']
                : now();

            $expiresAt = $data['expires_at'] ?? null;

            return BookReservation::create([
                'school_id' => $schoolId,
                'book_id' => $lockedBook->id,
                'library_member_id' => $lockedMember->id,
                'reserved_at' => $reservedAt,
                'expires_at' => $expiresAt,
                'status' => 'active',
                'notes' => $data['notes'] ?? null,
            ])->fresh([
                'book',
                'member.student',
                'member.staff',
            ]);
        });
    }

    public function cancel(BookReservation $reservation): BookReservation
    {
        $schoolId = $this->scope->schoolId();

        if ((int) $reservation->school_id !== $schoolId) {
            abort(404);
        }

        return DB::transaction(function () use ($reservation, $schoolId) {
            $locked = BookReservation::query()
                ->where('id', $reservation->id)
                ->where('school_id', $schoolId)
                ->lockForUpdate()
                ->first();

            if (!$locked) {
                abort(404);
            }

            if ($locked->status !== 'active') {
                throw ValidationException::withMessages([
                    'reservation' =>
                        'Only an active reservation can be cancelled.',
                ]);
            }

            $locked->update([
                'status' => 'cancelled',
            ]);

            return $locked->fresh([
                'book',
                'member.student',
                'member.staff',
            ]);
        });
    }

    public function fulfill(BookReservation $reservation): BookReservation
    {
        $schoolId = $this->scope->schoolId();

        if ((int) $reservation->school_id !== $schoolId) {
            abort(404);
        }

        return DB::transaction(function () use ($reservation, $schoolId) {
            $locked = BookReservation::query()
                ->where('id', $reservation->id)
                ->where('school_id', $schoolId)
                ->lockForUpdate()
                ->first();

            if (!$locked) {
                abort(404);
            }

            if ($locked->status !== 'active') {
                throw ValidationException::withMessages([
                    'reservation' =>
                        'Only an active reservation can be fulfilled.',
                ]);
            }

            if (
                $locked->expires_at &&
                $locked->expires_at->isPast()
            ) {
                $locked->update([
                    'status' => 'expired',
                ]);

                throw ValidationException::withMessages([
                    'reservation' =>
                        'This reservation has expired.',
                ]);
            }

            $locked->update([
                'status' => 'fulfilled',
            ]);

            return $locked->fresh([
                'book',
                'member.student',
                'member.staff',
            ]);
        });
    }

    public function expireReservations(?int $schoolId = null): int
    {
        $schoolId ??= $this->scope->schoolId();

        return BookReservation::query()
            ->where('school_id', $schoolId)
            ->where('status', 'active')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now())
            ->update([
                'status' => 'expired',
                'updated_at' => now(),
            ]);
    }
}
