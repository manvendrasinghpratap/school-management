<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookRequest;
use App\Models\Book;
use App\Models\LibraryAuthor;
use App\Models\LibraryCategory;
use App\Models\LibraryPublisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class LibraryController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | School
    |--------------------------------------------------------------------------
    */

    private function schoolId(): int
    {
        abort_unless(
            Auth::check(),
            401
        );

        $schoolId = (int) Auth::user()->school_id;

        abort_if(
            $schoolId <= 0,
            403,
            'No school is assigned to the current user.'
        );

        return $schoolId;
    }


    /*
    |--------------------------------------------------------------------------
    | BOOK LIST
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $schoolId = $this->schoolId();

        $query = Book::query()
            ->where('school_id', $schoolId)
            ->with([
                'category',
                'authors',
                'copies',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim(
                $request->input('search')
            );

            $query->where(function ($q) use ($search) {

                $q->where(
                    'title',
                    'like',
                    '%' . $search . '%'
                )

                ->orWhere(
                    'isbn',
                    'like',
                    '%' . $search . '%'
                )

                ->orWhere(
                    'author',
                    'like',
                    '%' . $search . '%'
                )

                ->orWhere(
                    'publisher',
                    'like',
                    '%' . $search . '%'
                )

                ->orWhere(
                    'category',
                    'like',
                    '%' . $search . '%'
                );
            });
        }

        $books = $query
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view(
            'admin.library.books.index',
            compact('books')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE BOOK
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $schoolId = $this->schoolId();

        $categories = LibraryCategory::query()
            ->where('school_id', $schoolId)
            ->orderBy('name')
            ->get();

        $authors = LibraryAuthor::query()
            ->where('school_id', $schoolId)
            ->orderBy('name')
            ->get();

        $publishers = LibraryPublisher::query()
            ->where('school_id', $schoolId)
            ->orderBy('name')
            ->get();

        return view(
            'admin.library.books.create',
            compact(
                'categories',
                'authors',
                'publishers'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE BOOK
    |--------------------------------------------------------------------------
    */

    public function store(StoreBookRequest $request)
{
    $schoolId = $this->schoolId();

    $data = $request->validated();

    /*
    |--------------------------------------------------------------------------
    | Publisher
    |--------------------------------------------------------------------------
    |
    | The create form may submit publisher_id, but the current books table
    | stores the publisher name in the `publisher` column.
    |
    | StoreBookRequest already converts publisher_id into publisher.
    | Remove publisher_id before Book::create() because it is not a
    | column in the books table.
    |--------------------------------------------------------------------------
    */

    unset($data['publisher_id']);

    /*
    |--------------------------------------------------------------------------
    | Authors
    |--------------------------------------------------------------------------
    */

    $authorIds = $data['author_ids'] ?? [];

    unset($data['author_ids']);

    /*
    |--------------------------------------------------------------------------
    | School Ownership
    |--------------------------------------------------------------------------
    */

    $data['school_id'] = $schoolId;

    /*
    |--------------------------------------------------------------------------
    | Creator
    |--------------------------------------------------------------------------
    */

    if ($this->bookHasColumn('created_by')) {
        $data['created_by'] = Auth::id();
    }

    /*
    |--------------------------------------------------------------------------
    | Category
    |--------------------------------------------------------------------------
    |
    | category_id = real relationship
    | category    = legacy text value
    |
    */

    $category = LibraryCategory::query()
        ->where('school_id', $schoolId)
        ->findOrFail($data['category_id']);

    /*
    |--------------------------------------------------------------------------
    | Keep legacy category column synchronized
    |--------------------------------------------------------------------------
    */

    $data['category'] = $category->name;

    /*
    |--------------------------------------------------------------------------
    | Quantity
    |--------------------------------------------------------------------------
    */

    $quantity = (int) ($data['quantity'] ?? 0);

    $data['quantity'] = $quantity;

    /*
    |--------------------------------------------------------------------------
    | New Book Availability
    |--------------------------------------------------------------------------
    |
    | A newly created book has no physical copies yet, so the declared
    | quantity is initially considered available.
    |--------------------------------------------------------------------------
    */

    $data['available_quantity'] = $quantity;

    /*
    |--------------------------------------------------------------------------
    | Create Book
    |--------------------------------------------------------------------------
    */

    $book = DB::transaction(function () use (
        $data,
        $authorIds,
        $schoolId
    ) {

        /*
        |--------------------------------------------------------------------------
        | Create Book
        |--------------------------------------------------------------------------
        */

        $book = Book::create($data);

        /*
        |--------------------------------------------------------------------------
        | Attach Authors
        |--------------------------------------------------------------------------
        |
        | Only authors belonging to the current school may be attached.
        |--------------------------------------------------------------------------
        */

        if (!empty($authorIds)) {

            $validAuthorIds = LibraryAuthor::query()
                ->where('school_id', $schoolId)
                ->whereIn('id', $authorIds)
                ->pluck('id')
                ->toArray();

            $book->authors()->sync($validAuthorIds);
        }

        return $book;
    });

    /*
    |--------------------------------------------------------------------------
    | Redirect
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->route(
            'admin.library.books.show',
            $book
        )
        ->with(
            'success',
            'Book created successfully.'
        );
}


    /*
    |--------------------------------------------------------------------------
    | SHOW BOOK
    |--------------------------------------------------------------------------
    */

    public function show(Book $book)
    {
        $this->authorizeBook(
            $book
        );

        /*
        |--------------------------------------------------------------------------
        | IMPORTANT
        |--------------------------------------------------------------------------
        | Do NOT use "category".
        | The relationship is "category".
        |--------------------------------------------------------------------------
        */

        $book->load([
            'category',
            'authors',
            'copies',
        ]);

        return view(
            'admin.library.books.show',
            compact('book')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT BOOK
    |--------------------------------------------------------------------------
    */

    public function edit(Book $book)
    {
        $this->authorizeBook(
            $book
        );

        $schoolId = $this->schoolId();

        $categories = LibraryCategory::query()
            ->where(
                'school_id',
                $schoolId
            )
            ->orderBy('name')
            ->get();

        $authors = LibraryAuthor::query()
            ->where(
                'school_id',
                $schoolId
            )
            ->orderBy('name')
            ->get();

        $publishers = LibraryPublisher::query()
            ->where(
                'school_id',
                $schoolId
            )
            ->orderBy('name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Load relationships
        |--------------------------------------------------------------------------
        */

        $book->load([
            'category',
            'authors',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Selected authors
        |--------------------------------------------------------------------------
        */

        $selectedAuthors = $book->authors()
            ->pluck(
                'library_authors.id'
            )
            ->toArray();

        return view(
            'admin.library.books.edit',
            compact(
                'book',
                'categories',
                'authors',
                'publishers',
                'selectedAuthors'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE BOOK
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Book $book
    ) {
        $this->authorizeBook(
            $book
        );

        $schoolId = $this->schoolId();

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $data = $request->validate([

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'isbn' => [
                'nullable',
                'string',
                'max:100',

                Rule::unique(
                    'books',
                    'isbn'
                )
                ->where(
                    fn ($query) =>
                        $query->where(
                            'school_id',
                            $schoolId
                        )
                )
                ->ignore(
                    $book->id
                ),
            ],

            'category_id' => [
                'required',
                'integer',

                Rule::exists(
                    'library_categories',
                    'id'
                )
                ->where(
                    fn ($query) =>
                        $query->where(
                            'school_id',
                            $schoolId
                        )
                ),
            ],

            'author' => [
                'nullable',
                'string',
                'max:255',
            ],

            'publisher' => [
                'nullable',
                'string',
                'max:255',
            ],

            'shelf_location' => [
                'nullable',
                'string',
                'max:255',
            ],

            'quantity' => [
                'required',
                'integer',
                'min:0',
            ],

            'author_ids' => [
                'nullable',
                'array',
            ],

            'author_ids.*' => [
                'integer',

                Rule::exists(
                    'library_authors',
                    'id'
                )
                ->where(
                    fn ($query) =>
                        $query->where(
                            'school_id',
                            $schoolId
                        )
                ),
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Authors
        |--------------------------------------------------------------------------
        */

        $authorIds = $data['author_ids'] ?? [];

        unset(
            $data['author_ids']
        );

        /*
        |--------------------------------------------------------------------------
        | Category
        |--------------------------------------------------------------------------
        */

        $category = LibraryCategory::query()
            ->where(
                'school_id',
                $schoolId
            )
            ->findOrFail(
                $data['category_id']
            );

        /*
        |--------------------------------------------------------------------------
        | Keep legacy category synchronized
        |--------------------------------------------------------------------------
        */

        $data['category'] = $category->name;

        /*
        |--------------------------------------------------------------------------
        | Quantity / Inventory
        |--------------------------------------------------------------------------
        */

        $oldQuantity = (int) (
            $book->quantity ?? 0
        );

        $oldAvailable = (int) (
            $book->available_quantity ?? 0
        );

        $newQuantity = (int) (
            $data['quantity']
        );

        /*
        |--------------------------------------------------------------------------
        | Currently unavailable
        |--------------------------------------------------------------------------
        */

        $unavailableQuantity = max(
            0,
            $oldQuantity - $oldAvailable
        );

        /*
        |--------------------------------------------------------------------------
        | Physical copies registered
        |--------------------------------------------------------------------------
        */

        $registeredCopies = $book
            ->copies()
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Cannot reduce quantity below physical copies
        |--------------------------------------------------------------------------
        */

        if (
            $newQuantity < $registeredCopies
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'quantity' =>
                        'Total quantity cannot be less than the number of registered physical copies (' .
                        $registeredCopies .
                        ').',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Cannot reduce quantity below unavailable quantity
        |--------------------------------------------------------------------------
        */

        if (
            $newQuantity < $unavailableQuantity
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'quantity' =>
                        'Total quantity cannot be less than the number of currently unavailable copies (' .
                        $unavailableQuantity .
                        ').',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Calculate availability
        |--------------------------------------------------------------------------
        */

        $newAvailableQuantity =
            $newQuantity -
            $unavailableQuantity;

        $data['quantity'] =
            $newQuantity;

        $data['available_quantity'] =
            max(
                0,
                $newAvailableQuantity
            );

        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        DB::transaction(
            function () use (
                $book,
                $data,
                $authorIds,
                $schoolId
            ) {

                $book->update(
                    $data
                );

                /*
                |--------------------------------------------------------------------------
                | Attach only valid school authors
                |--------------------------------------------------------------------------
                */

                $validAuthorIds = [];

                if (!empty($authorIds)) {

                    $validAuthorIds = LibraryAuthor::query()
                        ->where(
                            'school_id',
                            $schoolId
                        )
                        ->whereIn(
                            'id',
                            $authorIds
                        )
                        ->pluck('id')
                        ->toArray();
                }

                $book->authors()->sync(
                    $validAuthorIds
                );
            }
        );

        return redirect()
            ->route(
                'admin.library.books.show',
                $book
            )
            ->with(
                'success',
                'Book updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE BOOK
    |--------------------------------------------------------------------------
    */

    public function destroy(Book $book)
    {
        $this->authorizeBook(
            $book
        );

        /*
        |--------------------------------------------------------------------------
        | Do not delete when physical copies exist
        |--------------------------------------------------------------------------
        */

        if (
            $book->copies()->exists()
        ) {

            return back()
                ->with(
                    'error',
                    'This book cannot be deleted because physical book copies are registered against it.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Do not delete when active issues exist
        |--------------------------------------------------------------------------
        */

        if (
            $book->issues()
                ->whereNull('returned_at')
                ->exists()
        ) {

            return back()
                ->with(
                    'error',
                    'This book cannot be deleted because the book has an active issue.'
                );
        }

        DB::transaction(
            function () use ($book) {

                /*
                | Remove author pivot records.
                */

                $book->authors()->detach();

                /*
                | Soft delete.
                */

                $book->delete();
            }
        );

        return redirect()
            ->route(
                'admin.library.books.index'
            )
            ->with(
                'success',
                'Book deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    private function authorizeBook(
        Book $book
    ): void {

        abort_unless(
            (int) $book->school_id ===
            $this->schoolId(),
            404
        );
    }


    /**
     * Check whether books table has a column.
     *
     * This keeps created_by safe because your current
     * Book model/table architecture has varied during migration.
     */
    private function bookHasColumn(
        string $column
    ): bool {

        return \Schema::hasColumn(
            'books',
            $column
        );
    }
}