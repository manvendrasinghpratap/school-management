<?php

use App\Http\Controllers\Admin\HostelController;
use App\Http\Controllers\Admin\HostelMasterController;
use App\Http\Controllers\Admin\LibraryController;
use App\Http\Controllers\Admin\LibraryMasterController;
use App\Http\Controllers\Admin\LibraryOperationsController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->middleware('auth')
    ->group(function () {


    /*
    |--------------------------------------------------------------------------
    | LIBRARY
    |--------------------------------------------------------------------------
    */

    Route::prefix('library')
        ->name('admin.library.')
        ->group(function () {


        /*
        |--------------------------------------------------------------------------
        | BOOKS
        |--------------------------------------------------------------------------
        */

        // Library dashboard / books listing
        Route::get(
            '/',
            [LibraryController::class, 'index']
        )
            ->middleware('permission:library.view')
            ->name('index');


        // Books listing
        Route::get(
            '/books',
            [LibraryController::class, 'index']
        )
            ->middleware('permission:library.view')
            ->name('books.index');


        // Create book form
        Route::get(
            '/books/create',
            [LibraryController::class, 'create']
        )
            ->middleware('permission:library.create')
            ->name('books.create');


        // Store book
        Route::post(
            '/books',
            [LibraryController::class, 'store']
        )
            ->middleware('permission:library.create')
            ->name('books.store');


        /*
        |--------------------------------------------------------------------------
        | BOOK COPIES
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | These routes must come before /books/{book}
        | so Laravel does not treat "copies" as a book ID.
        |
        */

        // List physical copies
        Route::get(
            '/books/{book}/copies',
            [LibraryMasterController::class, 'copies']
        )
            ->middleware('permission:library.view')
            ->name('books.copies.index');


        // Add physical copy
        Route::post(
            '/books/{book}/copies',
            [LibraryMasterController::class, 'storeCopy']
        )
            ->middleware('permission:library.create')
            ->name('books.copies.store');


        // Edit physical copy
        Route::get(
            '/books/{book}/copies/{copy}/edit',
            [LibraryMasterController::class, 'editCopy']
        )
            ->middleware('permission:library.update')
            ->name('books.copies.edit');


        // Update physical copy
        Route::put(
            '/books/{book}/copies/{copy}',
            [LibraryMasterController::class, 'updateCopy']
        )
            ->middleware('permission:library.update')
            ->name('books.copies.update');


        // PATCH update physical copy
        Route::patch(
            '/books/{book}/copies/{copy}',
            [LibraryMasterController::class, 'updateCopy']
        )
            ->middleware('permission:library.update')
            ->name('books.copies.update.patch');


        // Delete physical copy
        Route::delete(
            '/books/{book}/copies/{copy}',
            [LibraryMasterController::class, 'destroyCopy']
        )
            ->middleware('permission:library.delete')
            ->name('books.copies.destroy');


        /*
        |--------------------------------------------------------------------------
        | BOOK EDIT
        |--------------------------------------------------------------------------
        */

        // Edit book form
        Route::get(
            '/books/{book}/edit',
            [LibraryController::class, 'edit']
        )
            ->middleware('permission:library.update')
            ->name('books.edit');


        // Update book
        Route::put(
            '/books/{book}',
            [LibraryController::class, 'update']
        )
            ->middleware('permission:library.update')
            ->name('books.update');


        // PATCH update book
        Route::patch(
            '/books/{book}',
            [LibraryController::class, 'update']
        )
            ->middleware('permission:library.update')
            ->name('books.update.patch');


        /*
        |--------------------------------------------------------------------------
        | BOOK DETAILS
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/books/{book}',
            [LibraryController::class, 'show']
        )
            ->middleware('permission:library.view')
            ->name('books.show');


        /*
        |--------------------------------------------------------------------------
        | DELETE BOOK
        |--------------------------------------------------------------------------
        */

        Route::delete(
            '/books/{book}',
            [LibraryController::class, 'destroy']
        )
            ->middleware('permission:library.delete')
            ->name('books.destroy');


        /*
        |--------------------------------------------------------------------------
        | CATEGORIES
        |--------------------------------------------------------------------------
        */

        // Category listing
        Route::get(
            '/categories',
            [LibraryMasterController::class, 'categories']
        )
            ->middleware('permission:library.view')
            ->name('categories.index');


        // Create category
        Route::get(
            '/categories/create',
            [LibraryMasterController::class, 'createCategory']
        )
            ->middleware('permission:library.create')
            ->name('categories.create');


        // Store category
        Route::post(
            '/categories',
            [LibraryMasterController::class, 'storeCategory']
        )
            ->middleware('permission:library.create')
            ->name('categories.store');


        // Edit category
        Route::get(
            '/categories/{category}/edit',
            [LibraryMasterController::class, 'editCategory']
        )
            ->middleware('permission:library.update')
            ->name('categories.edit');


        // Update category
        Route::put(
            '/categories/{category}',
            [LibraryMasterController::class, 'updateCategory']
        )
            ->middleware('permission:library.update')
            ->name('categories.update');


        // PATCH update category
        Route::patch(
            '/categories/{category}',
            [LibraryMasterController::class, 'updateCategory']
        )
            ->middleware('permission:library.update')
            ->name('categories.update.patch');


        // Show category
        Route::get(
            '/categories/{category}',
            [LibraryMasterController::class, 'showCategory']
        )
            ->middleware('permission:library.view')
            ->name('categories.show');


        // Delete category
        Route::delete(
            '/categories/{category}',
            [LibraryMasterController::class, 'destroyCategory']
        )
            ->middleware('permission:library.delete')
            ->name('categories.destroy');


        /*
        |--------------------------------------------------------------------------
        | AUTHORS
        |--------------------------------------------------------------------------
        */

        // Authors listing
        Route::get('/authors', [LibraryMasterController::class, 'authors'])
            ->middleware('permission:library.view')
            ->name('authors.index');

        // Create author form
        Route::get('/authors/create', [LibraryMasterController::class, 'createAuthor'])
            ->middleware('permission:library.create')
            ->name('authors.create');

        // Store author
        Route::post('/authors', [LibraryMasterController::class, 'storeAuthor'])
            ->middleware('permission:library.create')
            ->name('authors.store');

        // Edit author form
        Route::get('/authors/{author}/edit', [LibraryMasterController::class, 'editAuthor'])
            ->middleware('permission:library.update')
            ->name('authors.edit');

        // Update author
        Route::put('/authors/{author}', [LibraryMasterController::class, 'updateAuthor'])
            ->middleware('permission:library.update')
            ->name('authors.update');

        // PATCH update author
        Route::patch('/authors/{author}', [LibraryMasterController::class, 'updateAuthor'])
            ->middleware('permission:library.update')
            ->name('authors.update.patch');

        // Delete author
        Route::delete('/authors/{author}', [LibraryMasterController::class, 'destroyAuthor'])
            ->middleware('permission:library.delete')
            ->name('authors.destroy');

        /*
        |--------------------------------------------------------------------------
        | PUBLISHERS
        |--------------------------------------------------------------------------
        */

        // Publishers listing
        Route::get('/publishers', [LibraryMasterController::class, 'publishers'])
            ->middleware('permission:library.view')
            ->name('publishers.index');

        // Create publisher form
        Route::get('/publishers/create', [LibraryMasterController::class, 'createPublisher'])
            ->middleware('permission:library.create')
            ->name('publishers.create');

        // Store publisher
        Route::post('/publishers', [LibraryMasterController::class, 'storePublisher'])
            ->middleware('permission:library.create')
            ->name('publishers.store');

        // Edit publisher form
        Route::get('/publishers/{publisher}/edit', [LibraryMasterController::class, 'editPublisher'])
            ->middleware('permission:library.update')
            ->name('publishers.edit');

        // Update publisher
        Route::put('/publishers/{publisher}', [LibraryMasterController::class, 'updatePublisher'])
            ->middleware('permission:library.update')
            ->name('publishers.update');

        // PATCH update publisher
        Route::patch('/publishers/{publisher}', [LibraryMasterController::class, 'updatePublisher'])
            ->middleware('permission:library.update')
            ->name('publishers.update.patch');

        // Delete publisher
        Route::delete('/publishers/{publisher}', [LibraryMasterController::class, 'destroyPublisher'])
            ->middleware('permission:library.delete')
            ->name('publishers.destroy');


        /*
        |--------------------------------------------------------------------------
        | LIBRARY MEMBERS
        |--------------------------------------------------------------------------
        */

        // Members listing
        Route::get(
            '/members',
            [LibraryOperationsController::class, 'members']
        )
            ->middleware('permission:library.members.view')
            ->name('members.index');


        // Create member
        Route::get(
            '/members/create',
            [LibraryOperationsController::class, 'createMember']
        )
            ->middleware('permission:library.members.manage')
            ->name('members.create');


        // Store member
        Route::post(
            '/members',
            [LibraryOperationsController::class, 'storeMember']
        )
            ->middleware('permission:library.members.manage')
            ->name('members.store');

            Route::get('/members/{member}/edit', [
    LibraryOperationsController::class,
    'editMember'
])
    ->middleware('permission:library.members.manage')
    ->name('members.edit');

Route::put('/members/{member}', [
    LibraryOperationsController::class,
    'updateMember'
])
    ->middleware('permission:library.members.manage')
    ->name('members.update');

Route::patch('/members/{member}', [
    LibraryOperationsController::class,
    'updateMember'
])
    ->middleware('permission:library.members.manage')
    ->name('members.update.patch');

Route::delete('/members/{member}', [
    LibraryOperationsController::class,
    'destroyMember'
])
    ->middleware('permission:library.members.manage')
    ->name('members.destroy');

    /*
|--------------------------------------------------------------------------
| LIBRARY MEMBER FILTERS
|--------------------------------------------------------------------------
*/

Route::get(
    '/members/filter/classes',
    [LibraryOperationsController::class, 'filterMemberClasses']
)
    ->middleware('permission:library.members.manage')
    ->name('members.filter.classes');


Route::get(
    '/members/filter/sections',
    [LibraryOperationsController::class, 'filterMemberSections']
)
    ->middleware('permission:library.members.manage')
    ->name('members.filter.sections');


Route::get(
    '/members/filter/students',
    [LibraryOperationsController::class, 'filterMemberStudents']
)
    ->middleware('permission:library.members.manage')
    ->name('members.filter.students');


        /*
        |--------------------------------------------------------------------------
        | LIBRARY ISSUES
        |--------------------------------------------------------------------------
        */

        // Issues listing
        Route::get(
            '/issues',
            [LibraryOperationsController::class, 'issues']
        )
            ->middleware('permission:library.issues.view')
            ->name('issues.index');


        // Create issue
        Route::get(
            '/issues/create',
            [LibraryOperationsController::class, 'createIssue']
        )
            ->middleware('permission:library.issues.manage')
            ->name('issues.create');


        // Store issue
        Route::post(
            '/issues',
            [LibraryOperationsController::class, 'storeIssue']
        )
            ->middleware('permission:library.issues.manage')
            ->name('issues.store');


        // Return issued book
        Route::post(
            '/issues/{issue}/return',
            [LibraryOperationsController::class, 'returnIssue']
        )
            ->middleware('permission:library.issues.manage')
            ->name('issues.return');


        // Renew issued book
        Route::post(
            '/issues/{issue}/renew',
            [LibraryOperationsController::class, 'renewIssue']
        )
            ->middleware('permission:library.issues.manage')
            ->name('issues.renew');


    });


    /*
    |--------------------------------------------------------------------------
    | HOSTEL
    |--------------------------------------------------------------------------
    */

    Route::prefix('hostel')
        ->name('admin.hostel.')
        ->group(function () {


        /*
        |--------------------------------------------------------------------------
        | HOSTELS
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/',
            [HostelController::class, 'index']
        )
            ->middleware('permission:hostel.view')
            ->name('index');


        Route::get(
            '/create',
            [HostelController::class, 'create']
        )
            ->middleware('permission:hostel.manage')
            ->name('create');


        Route::post(
            '/',
            [HostelController::class, 'store']
        )
            ->middleware('permission:hostel.manage')
            ->name('store');


        /*
        |--------------------------------------------------------------------------
        | HOSTEL ALLOCATIONS
        |--------------------------------------------------------------------------
        |
        | Keep these before /{hostel}/rooms.
        |
        */

        Route::get(
            '/allocations',
            [HostelController::class, 'allocations']
        )
            ->middleware('permission:hostel.allocations.view')
            ->name('allocations.index');


        Route::get(
            '/allocations/create',
            [HostelController::class, 'createAllocation']
        )
            ->middleware('permission:hostel.allocations.manage')
            ->name('allocations.create');


        Route::post(
            '/allocations',
            [HostelController::class, 'storeAllocation']
        )
            ->middleware('permission:hostel.allocations.manage')
            ->name('allocations.store');


        Route::post(
            '/allocations/{allocation}/checkout',
            [HostelController::class, 'checkout']
        )
            ->middleware('permission:hostel.allocations.manage')
            ->name('allocations.checkout');


        /*
        |--------------------------------------------------------------------------
        | HOSTEL ROOM BEDS
        |--------------------------------------------------------------------------
        |
        | More specific route before /{hostel}/rooms.
        |
        */

        Route::get(
            '/rooms/{room}/beds',
            [HostelMasterController::class, 'beds']
        )
            ->middleware('permission:hostel.rooms.manage')
            ->name('rooms.beds.index');


        Route::post(
            '/rooms/{room}/beds',
            [HostelMasterController::class, 'storeBed']
        )
            ->middleware('permission:hostel.rooms.manage')
            ->name('rooms.beds.store');


        /*
        |--------------------------------------------------------------------------
        | HOSTEL ROOMS
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/{hostel}/rooms',
            [HostelController::class, 'rooms']
        )
            ->middleware('permission:hostel.rooms.manage')
            ->name('rooms.index');


        Route::post(
            '/{hostel}/rooms',
            [HostelController::class, 'storeRoom']
        )
            ->middleware('permission:hostel.rooms.manage')
            ->name('rooms.store');


    });

});