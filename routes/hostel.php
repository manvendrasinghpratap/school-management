<?php

use App\Http\Controllers\Admin\HostelController;
use App\Http\Controllers\Admin\HostelFeeController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin/hostel')
    ->middleware(['auth'])
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Hostels
        |--------------------------------------------------------------------------
        */

        Route::get('/hostels', [HostelController::class, 'hostels'])
            ->middleware('permission:hostel.view')
            ->name('admin.hostel.hostels.index');

        Route::get('/hostels/create', [HostelController::class, 'createHostel'])
            ->middleware('permission:hostel.manage')
            ->name('admin.hostel.hostels.create');

        Route::post('/hostels', [HostelController::class, 'storeHostel'])
            ->middleware('permission:hostel.manage')
            ->name('admin.hostel.hostels.store');

        Route::get('/hostels/{hostel}', [HostelController::class, 'showHostel'])
            ->middleware('permission:hostel.view')
            ->name('admin.hostel.hostels.show');

        Route::get('/hostels/{hostel}/edit', [HostelController::class, 'editHostel'])
            ->middleware('permission:hostel.manage')
            ->name('admin.hostel.hostels.edit');

        Route::put('/hostels/{hostel}', [HostelController::class, 'updateHostel'])
            ->middleware('permission:hostel.manage')
            ->name('admin.hostel.hostels.update');

        Route::patch('/hostels/{hostel}', [HostelController::class, 'updateHostel'])
            ->middleware('permission:hostel.manage')
            ->name('admin.hostel.hostels.patch');

        Route::delete('/hostels/{hostel}', [HostelController::class, 'destroyHostel'])
            ->middleware('permission:hostel.manage')
            ->name('admin.hostel.hostels.destroy');

        /*
        |--------------------------------------------------------------------------
        | Rooms
        |--------------------------------------------------------------------------
        */

        Route::get('/hostels/{hostel}/rooms', [HostelController::class, 'rooms'])
            ->middleware('permission:hostel.view')
            ->name('admin.hostel.rooms.index');

        Route::get('/hostels/{hostel}/rooms/create', [HostelController::class, 'createRoom'])
            ->middleware('permission:hostel.rooms.manage')
            ->name('admin.hostel.rooms.create');

        Route::post('/rooms', [HostelController::class, 'storeRoom'])
            ->middleware('permission:hostel.rooms.manage')
            ->name('admin.hostel.rooms.store');

        Route::get('/rooms/{room}', [HostelController::class, 'showRoom'])
            ->middleware('permission:hostel.view')
            ->name('admin.hostel.rooms.show');

        Route::get('/rooms/{room}/edit', [HostelController::class, 'editRoom'])
            ->middleware('permission:hostel.rooms.manage')
            ->name('admin.hostel.rooms.edit');

        Route::put('/rooms/{room}', [HostelController::class, 'updateRoom'])
            ->middleware('permission:hostel.rooms.manage')
            ->name('admin.hostel.rooms.update');

        Route::patch('/rooms/{room}', [HostelController::class, 'updateRoom'])
            ->middleware('permission:hostel.rooms.manage')
            ->name('admin.hostel.rooms.patch');

        Route::delete('/rooms/{room}', [HostelController::class, 'destroyRoom'])
            ->middleware('permission:hostel.rooms.manage')
            ->name('admin.hostel.rooms.destroy');

        /*
        |--------------------------------------------------------------------------
        | Beds
        |--------------------------------------------------------------------------
        */

        Route::get('/rooms/{room}/beds', [HostelController::class, 'beds'])
            ->middleware('permission:hostel.view')
            ->name('admin.hostel.beds.index');

        Route::get('/rooms/{room}/beds/create', [HostelController::class, 'createBed'])
            ->middleware('permission:hostel.rooms.manage')
            ->name('admin.hostel.beds.create');

        Route::post('/beds', [HostelController::class, 'storeBed'])
            ->middleware('permission:hostel.rooms.manage')
            ->name('admin.hostel.beds.store');

        Route::get('/beds/{bed}', [HostelController::class, 'showBed'])
            ->middleware('permission:hostel.view')
            ->name('admin.hostel.beds.show');

        Route::get('/beds/{bed}/edit', [HostelController::class, 'editBed'])
            ->middleware('permission:hostel.rooms.manage')
            ->name('admin.hostel.beds.edit');

        Route::put('/beds/{bed}', [HostelController::class, 'updateBed'])
            ->middleware('permission:hostel.rooms.manage')
            ->name('admin.hostel.beds.update');

        Route::patch('/beds/{bed}', [HostelController::class, 'updateBed'])
            ->middleware('permission:hostel.rooms.manage')
            ->name('admin.hostel.beds.patch');

        Route::delete('/beds/{bed}', [HostelController::class, 'destroyBed'])
            ->middleware('permission:hostel.rooms.manage')
            ->name('admin.hostel.beds.destroy');

        Route::get('/ajax/rooms', [HostelController::class, 'roomsForHostel'])
            ->middleware('permission:hostel.view')
            ->name('admin.hostel.ajax.rooms');

        Route::get('/ajax/beds', [HostelController::class, 'bedsForRoom'])
            ->middleware('permission:hostel.view')
            ->name('admin.hostel.ajax.beds');

        /*
        |--------------------------------------------------------------------------
        | Allocations
        |--------------------------------------------------------------------------
        */

        Route::get('/allocations', [HostelController::class, 'allocations'])
            ->middleware('permission:hostel.allocations.view')
            ->name('admin.hostel.allocations.index');

        Route::get('/allocations/create', [HostelController::class, 'createAllocation'])
            ->middleware('permission:hostel.allocations.manage')
            ->name('admin.hostel.allocations.create');

        Route::post('/allocations', [HostelController::class, 'storeAllocation'])
            ->middleware('permission:hostel.allocations.manage')
            ->name('admin.hostel.allocations.store');

        Route::get('/allocations/{allocation}', [HostelController::class, 'showAllocation'])
            ->middleware('permission:hostel.allocations.view')
            ->name('admin.hostel.allocations.show');

        Route::post('/allocations/{allocation}/checkout', [HostelController::class, 'checkout'])
            ->middleware('permission:hostel.allocations.manage')
            ->name('admin.hostel.allocations.checkout');

        /*
        |--------------------------------------------------------------------------
        | Hostel Fees
        |--------------------------------------------------------------------------
        */

        Route::get('/fees', [HostelFeeController::class, 'fees'])
            ->middleware('permission:hostel.fees.view')
            ->name('admin.hostel.fees.index');

        Route::get('/fees/create', [HostelFeeController::class, 'createFee'])
            ->middleware('permission:hostel.fees.manage')
            ->name('admin.hostel.fees.create');

        Route::post('/fees', [HostelFeeController::class, 'storeFee'])
            ->middleware('permission:hostel.fees.manage')
            ->name('admin.hostel.fees.store');

        Route::get('/fees/{fee}', [HostelFeeController::class, 'showFee'])
            ->middleware('permission:hostel.fees.view')
            ->name('admin.hostel.fees.show');

        Route::get('/fees/{fee}/edit', [HostelFeeController::class, 'editFee'])
            ->middleware('permission:hostel.fees.manage')
            ->name('admin.hostel.fees.edit');

        Route::put('/fees/{fee}', [HostelFeeController::class, 'updateFee'])
            ->middleware('permission:hostel.fees.manage')
            ->name('admin.hostel.fees.update');

        Route::patch('/fees/{fee}', [HostelFeeController::class, 'updateFee'])
            ->middleware('permission:hostel.fees.manage')
            ->name('admin.hostel.fees.patch');

        Route::delete('/fees/{fee}', [HostelFeeController::class, 'destroyFee'])
            ->middleware('permission:hostel.fees.manage')
            ->name('admin.hostel.fees.destroy');
    });
