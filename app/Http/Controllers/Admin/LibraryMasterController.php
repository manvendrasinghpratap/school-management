<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookCopy;
use App\Models\LibraryAuthor;
use App\Models\LibraryCategory;
use App\Models\LibraryPublisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class LibraryMasterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | SCHOOL
    |--------------------------------------------------------------------------
    */

    private function schoolId(): int
    {
        abort_unless(Auth::check(), 401);

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
    | CATEGORIES
    |--------------------------------------------------------------------------
    */

    public function categories(Request $request)
    {
        $schoolId = $this->schoolId();

        $query = LibraryCategory::query()
            ->where('school_id', $schoolId);

        if ($request->filled('search')) {
            $search = trim($request->input('search'));

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('code', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('code')) {
            $query->where(
                'code',
                'like',
                '%' . trim($request->input('code')) . '%'
            );
        }

        $items = $query
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view(
            'admin.library.categories.index',
            [
                'items' => $items,
                'title' => 'Library Categories',
                'search' => $request->input('search'),
                'code' => $request->input('code'),
            ]
        );
    }


    public function createCategory()
    {
        return view(
            'admin.library.categories.create',
            [
                'title' => 'Create Library Category',
            ]
        );
    }


    public function storeCategory(Request $request)
    {
        $schoolId = $this->schoolId();

        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:150',
                Rule::unique('library_categories', 'name')
                    ->where(
                        fn ($q) => $q->where(
                            'school_id',
                            $schoolId
                        )
                    ),
            ],

            'code' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('library_categories', 'code')
                    ->where(
                        fn ($q) => $q->where(
                            'school_id',
                            $schoolId
                        )
                    ),
            ],

            'description' => [
                'nullable',
                'string',
            ],
        ]);

        $data['school_id'] = $schoolId;
        $data['created_by'] = Auth::id();

        LibraryCategory::create($data);

        return redirect()
            ->route('admin.library.categories.index')
            ->with('success', 'Category created successfully.');
    }


    public function showCategory(LibraryCategory $category)
    {
        $this->authorizeCategory($category);

        $books = Book::query()
            ->where('school_id', $this->schoolId())
            ->where('category_id', $category->id)
            ->with([
                'category',
                'authors',
                'copies',
            ])
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view(
            'admin.library.categories.show',
            compact('category', 'books')
        );
    }


    public function editCategory(LibraryCategory $category)
    {
        $this->authorizeCategory($category);

        return view(
            'admin.library.categories.edit',
            compact('category')
        );
    }


    public function updateCategory(
        Request $request,
        LibraryCategory $category
    ) {
        $this->authorizeCategory($category);

        $schoolId = $this->schoolId();

        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:150',
                Rule::unique('library_categories', 'name')
                    ->where(
                        fn ($q) => $q->where(
                            'school_id',
                            $schoolId
                        )
                    )
                    ->ignore($category->id),
            ],

            'code' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('library_categories', 'code')
                    ->where(
                        fn ($q) => $q->where(
                            'school_id',
                            $schoolId
                        )
                    )
                    ->ignore($category->id),
            ],

            'description' => [
                'nullable',
                'string',
            ],
        ]);

        DB::transaction(function () use (
            $category,
            $data,
            $schoolId
        ) {
            $category->update($data);

            Book::query()
                ->where('school_id', $schoolId)
                ->where('category_id', $category->id)
                ->update([
                    'category' => $category->name,
                ]);
        });

        return redirect()
            ->route('admin.library.categories.index')
            ->with('success', 'Category updated successfully.');
    }


    public function destroyCategory(LibraryCategory $category)
    {
        $this->authorizeCategory($category);

        if ($category->books()->exists()) {
            return redirect()
                ->route('admin.library.categories.index')
                ->with(
                    'error',
                    'This category cannot be deleted because it is assigned to one or more books.'
                );
        }

        $category->delete();

        return redirect()
            ->route('admin.library.categories.index')
            ->with('success', 'Category deleted successfully.');
    }


    /*
    |--------------------------------------------------------------------------
    | AUTHORS
    |--------------------------------------------------------------------------
    */
    /*
|--------------------------------------------------------------------------
| Authors
|--------------------------------------------------------------------------
*/

/**
 * Display library authors.
 */
public function authors(Request $request)
{
    $schoolId = $this->schoolId();

    $query = LibraryAuthor::query()
        ->where('school_id', $schoolId);

    if ($request->filled('search')) {

        $search = trim($request->search);

        $query->where(function ($q) use ($search) {

            $q->where('name', 'like', '%' . $search . '%')
              ->orWhere('bio', 'like', '%' . $search . '%');

        });
    }

    $authors = $query
        ->latest('id')
        ->paginate(15)
        ->withQueryString();

    return view(
        'admin.library.authors.index',
        compact('authors')
    );
}


/**
 * Show create author form.
 */
public function createAuthor()
{
    return view('admin.library.authors.create');
}


/**
 * Store a new author.
 */
public function storeAuthor(Request $request)
{
    $schoolId = $this->schoolId();

    $data = $request->validate([

        'name' => [
            'required',
            'string',
            'max:255',
        ],

        'bio' => [
            'nullable',
            'string',
        ],

    ]);

    $data['school_id'] = $schoolId;
    $data['created_by'] = Auth::id();

    LibraryAuthor::create($data);

    return redirect()
        ->route('admin.library.authors.index')
        ->with('success', 'Author created successfully.');
}


/**
 * Show edit author form.
 */
public function editAuthor(LibraryAuthor $author)
{
    $this->authorizeAuthor($author);

    return view(
        'admin.library.authors.edit',
        compact('author')
    );
}


/**
 * Update an author.
 */
public function updateAuthor(
    Request $request,
    LibraryAuthor $author
) {
    $this->authorizeAuthor($author);

    $data = $request->validate([

        'name' => [
            'required',
            'string',
            'max:255',
        ],

        'bio' => [
            'nullable',
            'string',
        ],

    ]);

    $author->update($data);

    return redirect()
        ->route('admin.library.authors.index')
        ->with('success', 'Author updated successfully.');
}


/**
 * Delete an author.
 */
public function destroyAuthor(LibraryAuthor $author)
{
    $this->authorizeAuthor($author);

    /*
     * Do not delete an author that is already
     * assigned to a book.
     */
    if ($author->books()->exists()) {

        return back()->with(
            'error',
            'This author cannot be deleted because it is assigned to one or more books.'
        );
    }

    $author->delete();

    return redirect()
        ->route('admin.library.authors.index')
        ->with('success', 'Author deleted successfully.');
}


/**
 * Ensure author belongs to current school.
 */
private function authorizeAuthor(LibraryAuthor $author): void
{
    abort_unless(
        (int) $author->school_id === $this->schoolId(),
        404
    );
}


    /*
    |--------------------------------------------------------------------------
    | PUBLISHERS
    |--------------------------------------------------------------------------
    */
    /*
|--------------------------------------------------------------------------
| Publishers
|--------------------------------------------------------------------------
*/

/**
 * Display library publishers.
 */
public function publishers(Request $request)
{
    $schoolId = $this->schoolId();

    $query = LibraryPublisher::query()
        ->where('school_id', $schoolId);

    if ($request->filled('search')) {

        $search = trim($request->search);

        $query->where(function ($q) use ($search) {

            $q->where('name', 'like', '%' . $search . '%')
              ->orWhere('phone', 'like', '%' . $search . '%')
              ->orWhere('email', 'like', '%' . $search . '%');

        });
    }

    $publishers = $query
        ->latest('id')
        ->paginate(15)
        ->withQueryString();

    return view(
        'admin.library.publishers.index',
        compact('publishers')
    );
}


/**
 * Show create publisher form.
 */
public function createPublisher()
{
    return view('admin.library.publishers.create');
}


/**
 * Store publisher.
 */
public function storePublisher(Request $request)
{
    $schoolId = $this->schoolId();

    $data = $request->validate([

        'name' => [
            'required',
            'string',
            'max:255',
        ],

        'address' => [
            'nullable',
            'string',
        ],

        'phone' => [
            'nullable',
            'string',
            'max:50',
        ],

        'email' => [
            'nullable',
            'email',
            'max:255',
        ],

        'website' => [
            'nullable',
            'string',
            'max:255',
        ],

    ]);

    $data['school_id'] = $schoolId;
    $data['created_by'] = Auth::id();

    LibraryPublisher::create($data);

    return redirect()
        ->route('admin.library.publishers.index')
        ->with('success', 'Publisher created successfully.');
}


/**
 * Show edit publisher form.
 */
public function editPublisher(LibraryPublisher $publisher)
{
    $this->authorizePublisher($publisher);

    return view(
        'admin.library.publishers.edit',
        compact('publisher')
    );
}


/**
 * Update publisher.
 */
public function updatePublisher(
    Request $request,
    LibraryPublisher $publisher
) {
    $this->authorizePublisher($publisher);

    $data = $request->validate([

        'name' => [
            'required',
            'string',
            'max:255',
        ],

        'address' => [
            'nullable',
            'string',
        ],

        'phone' => [
            'nullable',
            'string',
            'max:50',
        ],

        'email' => [
            'nullable',
            'email',
            'max:255',
        ],

        'website' => [
            'nullable',
            'string',
            'max:255',
        ],

    ]);

    $publisher->update($data);

    return redirect()
        ->route('admin.library.publishers.index')
        ->with('success', 'Publisher updated successfully.');
}


/**
 * Delete publisher.
 */
public function destroyPublisher(
    LibraryPublisher $publisher
) {
    $this->authorizePublisher($publisher);

    $publisher->delete();

    return redirect()
        ->route('admin.library.publishers.index')
        ->with('success', 'Publisher deleted successfully.');
}


/**
 * Ensure publisher belongs to current school.
 */
private function authorizePublisher(
    LibraryPublisher $publisher
): void {
    abort_unless(
        (int) $publisher->school_id === $this->schoolId(),
        404
    );
}


    /*
    |--------------------------------------------------------------------------
    | BOOK COPIES - INDEX
    |--------------------------------------------------------------------------
    */

    public function copies(Book $book)
    {
        $this->authorizeBook($book);

        $schoolId = $this->schoolId();

        $book->load([
            'category',
            'authors',
        ]);

        $copies = BookCopy::query()
            ->where('school_id', $schoolId)
            ->where('book_id', $book->id)
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view(
            'admin.library.copies.index',
            compact(
                'book',
                'copies'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | BOOK COPIES - STORE
    |--------------------------------------------------------------------------
    */

    public function storeCopy(
        Request $request,
        Book $book
    ) {
        $this->authorizeBook($book);

        $schoolId = $this->schoolId();

        /*
        |--------------------------------------------------------------------------
        | Validate
        |--------------------------------------------------------------------------
        */

        $data = $request->validate([

            'accession_number' => [
                'required',
                'string',
                'max:100',

                Rule::unique(
                    'book_copies',
                    'accession_number'
                )->where(
                    fn ($q) => $q->where(
                        'school_id',
                        $schoolId
                    )
                ),
            ],

            'barcode' => [
                'nullable',
                'string',
                'max:100',

                Rule::unique(
                    'book_copies',
                    'barcode'
                )->where(
                    fn ($q) => $q->where(
                        'school_id',
                        $schoolId
                    )
                ),
            ],

            'purchase_date' => [
                'nullable',
                'date',
            ],

            'purchase_price' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'condition_status' => [
                'required',
                Rule::in([
                    'new',
                    'good',
                    'fair',
                    'damaged',
                    'lost',
                ]),
            ],

            'status' => [
                'required',
                Rule::in([
                    'available',
                    'issued',
                    'reserved',
                    'lost',
                    'damaged',
                    'maintenance',
                ]),
            ],

            'notes' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Create copy
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $data,
            $book,
            $schoolId
        ) {

            $copy = new BookCopy();

            $copy->school_id = $schoolId;
            $copy->book_id = $book->id;

            $copy->accession_number =
                $data['accession_number'];

            $copy->barcode =
                $data['barcode'] ?? null;

            $copy->purchase_date =
                $data['purchase_date'] ?? null;

            $copy->purchase_price =
                $data['purchase_price'] ?? null;

            $copy->condition_status =
                $data['condition_status'];

            $copy->status =
                $data['status'];

            $copy->notes =
                $data['notes'] ?? null;

            $copy->save();


            /*
            |--------------------------------------------------------------------------
            | Recalculate inventory
            |--------------------------------------------------------------------------
            */

            $this->syncBookInventory(
                $book
            );
        });


        return redirect()
            ->route(
                'admin.library.books.copies.index',
                $book
            )
            ->with(
                'success',
                'Book copy added successfully.'
            );
    }


    /*
|--------------------------------------------------------------------------
| BOOK COPIES - EDIT
|--------------------------------------------------------------------------
*/

public function editCopy(Book $book, BookCopy $copy)
{
    $this->authorizeBook($book);

    if (
        (int) $copy->school_id !== $this->schoolId() ||
        (int) $copy->book_id !== (int) $book->id
    ) {
        abort(404);
    }

    return view('admin.library.copies.edit', compact(
        'book',
        'copy'
    ));
}


/*
|--------------------------------------------------------------------------
| BOOK COPIES - UPDATE
|--------------------------------------------------------------------------
*/

public function updateCopy(Request $request, Book $book, BookCopy $copy)
{
    $this->authorizeBook($book);

    if (
        (int) $copy->school_id !== $this->schoolId() ||
        (int) $copy->book_id !== (int) $book->id
    ) {
        abort(404);
    }

    $data = $request->validate([
        'accession_number' => [
            'required',
            'string',
            'max:100',
            Rule::unique('book_copies', 'accession_number')
                ->where(fn ($query) => $query->where(
                    'school_id',
                    $this->schoolId()
                ))
                ->ignore($copy->id),
        ],

        'barcode' => [
            'nullable',
            'string',
            'max:100',
            Rule::unique('book_copies', 'barcode')
                ->where(fn ($query) => $query->where(
                    'school_id',
                    $this->schoolId()
                ))
                ->ignore($copy->id),
        ],

        'purchase_date' => [
            'nullable',
            'date',
        ],

        'purchase_price' => [
            'nullable',
            'numeric',
            'min:0',
        ],

        'condition_status' => [
            'required',
            Rule::in([
                'new',
                'good',
                'fair',
                'damaged',
                'lost',
            ]),
        ],

        'status' => [
            'required',
            Rule::in([
                'available',
                'issued',
                'reserved',
                'lost',
                'damaged',
                'maintenance',
            ]),
        ],

        'notes' => [
            'nullable',
            'string',
            'max:2000',
        ],
    ]);

    DB::transaction(function () use ($copy, $data, $book) {

        $copy->update([
            'accession_number' => $data['accession_number'],
            'barcode' => $data['barcode'] ?? null,
            'purchase_date' => $data['purchase_date'] ?? null,
            'purchase_price' => $data['purchase_price'] ?? 0,
            'condition_status' => $data['condition_status'],
            'status' => $data['status'],
            'notes' => $data['notes'] ?? null,
        ]);

        $this->recalculateBookInventory($book);
    });

    return redirect()
        ->route('admin.library.books.copies.index', $book)
        ->with('success', 'Book copy updated successfully.');
}


/*
|--------------------------------------------------------------------------
| BOOK COPIES - DELETE
|--------------------------------------------------------------------------
*/

public function destroyCopy(Book $book, BookCopy $copy)
{
    $this->authorizeBook($book);

    if (
        (int) $copy->school_id !== $this->schoolId() ||
        (int) $copy->book_id !== (int) $book->id
    ) {
        abort(404);
    }

    DB::transaction(function () use ($copy, $book) {

        $copy->delete();

        $this->recalculateBookInventory($book);
    });

    return redirect()
        ->route('admin.library.books.copies.index', $book)
        ->with('success', 'Book copy deleted successfully.');
}
    
private function recalculateBookInventory(Book $book): void
{
    $total = BookCopy::query()
        ->where('school_id', $this->schoolId())
        ->where('book_id', $book->id)
        ->count();

    $available = BookCopy::query()
        ->where('school_id', $this->schoolId())
        ->where('book_id', $book->id)
        ->where('status', 'available')
        ->count();

    $book->update([
        'quantity' => $total,
        'available_quantity' => $available,
    ]);
}

    /*
    |--------------------------------------------------------------------------
    | SYNCHRONIZE BOOK INVENTORY
    |--------------------------------------------------------------------------
    */

    private function syncBookInventory(
        Book $book
    ): void {

        $schoolId = $this->schoolId();

        $query = BookCopy::query()
            ->where(
                'school_id',
                $schoolId
            )
            ->where(
                'book_id',
                $book->id
            );

        $totalCopies = (clone $query)
            ->count();

        $availableCopies = (clone $query)
            ->where(
                'status',
                'available'
            )
            ->count();

        $book->update([
            'quantity' =>
                $totalCopies,

            'available_quantity' =>
                $availableCopies,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | AUTHORIZATION
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


    private function authorizeCategory(
        LibraryCategory $category
    ): void {

        abort_unless(
            (int) $category->school_id ===
            $this->schoolId(),
            404
        );
    }
}