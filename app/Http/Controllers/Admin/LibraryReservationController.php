<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookReservationRequest;
use App\Models\Book;
use App\Models\BookReservation;
use App\Models\LibraryMember;
use App\Services\LibraryReservationService;
use Illuminate\Http\Request;

class LibraryReservationController extends Controller
{
    public function __construct(
    private LibraryReservationService $service
) {
}

    private function schoolId(): int
    {
        abort_unless(
            auth()->user()?->school_id,
            403,
            'No school is assigned to the current user.'
        );

        return (int) auth()->user()->school_id;
    }

    private function own(BookReservation $reservation): void
    {
        abort_unless(
            (int) $reservation->school_id === $this->schoolId(),
            404
        );
    }

    public function index(Request $request)
    {
        $schoolId = $this->schoolId();

        $this->service->expireReservations($schoolId);

        $query = BookReservation::query()
            ->where('school_id', $schoolId)
            ->with([
                'book',
                'member.student',
                'member.staff',
            ])
            ->latest('id');

        if ($request->filled('search')) {
            $search = trim($request->string('search')->toString());

            $query->where(function ($q) use ($search) {
                $q->whereHas('book', function ($bookQuery) use ($search) {
                    $bookQuery
                        ->where('title', 'like', "%{$search}%")
                        ->orWhere('isbn', 'like', "%{$search}%");
                })->orWhereHas('member', function ($memberQuery) use ($search) {
                    $memberQuery->where(
                        'member_number',
                        'like',
                        "%{$search}%"
                    );
                })->orWhereHas('member.student', function ($studentQuery) use ($search) {
                    $studentQuery
                        ->where('first_name', 'like', "%{$search}%")
                        ->orWhere('middle_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                })->orWhereHas('member.staff', function ($staffQuery) use ($search) {
                    $staffQuery
                        ->where('first_name', 'like', "%{$search}%")
                        ->orWhere('middle_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->string('status')->toString()
            );
        }

        $reservations = $query->paginate(20)->withQueryString();

        return view(
            'admin.library.reservations.index',
            compact('reservations')
        );
    }

    public function create()
    {
        $schoolId = $this->schoolId();

        $books = Book::query()
            ->where('school_id', $schoolId)
            ->orderBy('title')
            ->get([
                'id',
                'title',
                'isbn',
                'author',
                'available_quantity',
            ]);

        $members = LibraryMember::query()
            ->where('school_id', $schoolId)
            ->where('status', 'active')
            ->with(['student', 'staff'])
            ->orderBy('member_number')
            ->get();

        return view(
            'admin.library.reservations.create',
            compact('books', 'members')
        );
    }

    public function store(
        StoreBookReservationRequest $request
    ) {
        $data = $request->validated();

        $book = Book::query()
            ->where('school_id', $this->schoolId())
            ->findOrFail($data['book_id']);

        $member = LibraryMember::query()
            ->where('school_id', $this->schoolId())
            ->where('status', 'active')
            ->findOrFail($data['library_member_id']);

        $reservation = $this->service->create(
            $book,
            $member,
            $data
        );

        return redirect()
            ->route('admin.library.reservations.index')
            ->with(
                'success',
                'Book reservation created successfully.'
            );
    }

    public function cancel(BookReservation $reservation)
    {
        $this->own($reservation);

        $this->service->cancel($reservation);

        return redirect()
            ->route('admin.library.reservations.index')
            ->with(
                'success',
                'Reservation cancelled successfully.'
            );
    }

    public function fulfill(BookReservation $reservation)
    {
        $this->own($reservation);

        $this->service->fulfill($reservation);

        return redirect()
            ->route('admin.library.reservations.index')
            ->with(
                'success',
                'Reservation marked as fulfilled.'
            );
    }
}
