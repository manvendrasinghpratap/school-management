<?php
/*
|--------------------------------------------------------------------------
| LIBRARY RESERVATIONS
|--------------------------------------------------------------------------
|
| Add these routes INSIDE the existing:
|
| Route::prefix('admin')->middleware('auth')->group(...)
| Route::prefix('library')->name('admin.library.')->group(...)
|
*/
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::prefix('library')->name('admin.library.')->group(function () {

Route::get(
    '/reservations',
    [\App\Http\Controllers\Admin\LibraryReservationController::class, 'index']
)
    ->middleware('permission:library.reservations.view')
    ->name('reservations.index');

Route::get(
    '/reservations/create',
    [\App\Http\Controllers\Admin\LibraryReservationController::class, 'create']
)
    ->middleware('permission:library.reservations.manage')
    ->name('reservations.create');

Route::post(
    '/reservations',
    [\App\Http\Controllers\Admin\LibraryReservationController::class, 'store']
)
    ->middleware('permission:library.reservations.manage')
    ->name('reservations.store');

Route::post(
    '/reservations/{reservation}/cancel',
    [\App\Http\Controllers\Admin\LibraryReservationController::class, 'cancel']
)
    ->middleware('permission:library.reservations.manage')
    ->name('reservations.cancel');

Route::post(
    '/reservations/{reservation}/fulfill',
    [\App\Http\Controllers\Admin\LibraryReservationController::class, 'fulfill']
)
    ->middleware('permission:library.reservations.manage')
    ->name('reservations.fulfill');

});
});
