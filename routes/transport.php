<?php

use App\Http\Controllers\Admin\TransportController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin/transport')
    ->middleware('auth')
    ->name('admin.transport.')
    ->group(function () {

        Route::get('/drivers', [TransportController::class, 'drivers'])->middleware('permission:transport.view')->name('drivers.index');
        Route::get('/drivers/create', [TransportController::class, 'createDriver'])->middleware('permission:transport.manage')->name('drivers.create');
        Route::post('/drivers', [TransportController::class, 'storeDriver'])->middleware('permission:transport.manage')->name('drivers.store');
        Route::get('/drivers/{driver}', [TransportController::class, 'showDriver'])->middleware('permission:transport.view')->name('drivers.show');
        Route::get('/drivers/{driver}/edit', [TransportController::class, 'editDriver'])->middleware('permission:transport.manage')->name('drivers.edit');
        Route::put('/drivers/{driver}', [TransportController::class, 'updateDriver'])->middleware('permission:transport.manage')->name('drivers.update');
        Route::patch('/drivers/{driver}', [TransportController::class, 'updateDriver'])->middleware('permission:transport.manage')->name('drivers.patch');
        Route::delete('/drivers/{driver}', [TransportController::class, 'destroyDriver'])->middleware('permission:transport.manage')->name('drivers.destroy');

        Route::get('/vehicles', [TransportController::class, 'vehicles'])->middleware('permission:transport.view')->name('vehicles.index');
        Route::get('/vehicles/create', [TransportController::class, 'createVehicle'])->middleware('permission:transport.manage')->name('vehicles.create');
        Route::post('/vehicles', [TransportController::class, 'storeVehicle'])->middleware('permission:transport.manage')->name('vehicles.store');
        Route::get('/vehicles/{vehicle}', [TransportController::class, 'showVehicle'])->middleware('permission:transport.view')->name('vehicles.show');
        Route::get('/vehicles/{vehicle}/edit', [TransportController::class, 'editVehicle'])->middleware('permission:transport.manage')->name('vehicles.edit');
        Route::put('/vehicles/{vehicle}', [TransportController::class, 'updateVehicle'])->middleware('permission:transport.manage')->name('vehicles.update');
        Route::patch('/vehicles/{vehicle}', [TransportController::class, 'updateVehicle'])->middleware('permission:transport.manage')->name('vehicles.patch');
        Route::delete('/vehicles/{vehicle}', [TransportController::class, 'destroyVehicle'])->middleware('permission:transport.manage')->name('vehicles.destroy');

        Route::get('/routes', [TransportController::class, 'routes'])->middleware('permission:transport.view')->name('routes.index');
        Route::get('/routes/create', [TransportController::class, 'createRoute'])->middleware('permission:transport.manage')->name('routes.create');
        Route::post('/routes', [TransportController::class, 'storeRoute'])->middleware('permission:transport.manage')->name('routes.store');
        Route::get('/routes/{transportRoute}', [TransportController::class, 'showRoute'])->middleware('permission:transport.view')->name('routes.show');
        Route::get('/routes/{transportRoute}/edit', [TransportController::class, 'editRoute'])->middleware('permission:transport.manage')->name('routes.edit');
        Route::put('/routes/{transportRoute}', [TransportController::class, 'updateRoute'])->middleware('permission:transport.manage')->name('routes.update');
        Route::patch('/routes/{transportRoute}', [TransportController::class, 'updateRoute'])->middleware('permission:transport.manage')->name('routes.patch');
        Route::delete('/routes/{transportRoute}', [TransportController::class, 'destroyRoute'])->middleware('permission:transport.manage')->name('routes.destroy');

        Route::get('/routes/{transportRoute}/stops', [TransportController::class, 'stops'])->middleware('permission:transport.view')->name('routes.stops.index');
        Route::get('/routes/{transportRoute}/stops/create', [TransportController::class, 'createStop'])->middleware('permission:transport.manage')->name('routes.stops.create');
        Route::post('/stops', [TransportController::class, 'storeStop'])->middleware('permission:transport.manage')->name('stops.store');
        Route::get('/stops/{stop}/edit', [TransportController::class, 'editStop'])->middleware('permission:transport.manage')->name('stops.edit');
        Route::put('/stops/{stop}', [TransportController::class, 'updateStop'])->middleware('permission:transport.manage')->name('stops.update');
        Route::patch('/stops/{stop}', [TransportController::class, 'updateStop'])->middleware('permission:transport.manage')->name('stops.patch');
        Route::delete('/stops/{stop}', [TransportController::class, 'destroyStop'])->middleware('permission:transport.manage')->name('stops.destroy');

        Route::get('/assignments', [TransportController::class, 'assignments'])->middleware('permission:transport.view')->name('assignments.index');
        Route::get('/assignments/create', [TransportController::class, 'createAssignment'])->middleware('permission:transport.assign')->name('assignments.create');
        Route::post('/assignments', [TransportController::class, 'storeAssignment'])->middleware('permission:transport.assign')->name('assignments.store');
        Route::get('/assignments/{assignment}', [TransportController::class, 'showAssignment'])->middleware('permission:transport.view')->name('assignments.show');
        Route::get('/assignments/{assignment}/edit', [TransportController::class, 'editAssignment'])->middleware('permission:transport.assign')->name('assignments.edit');
        Route::put('/assignments/{assignment}', [TransportController::class, 'updateAssignment'])->middleware('permission:transport.assign')->name('assignments.update');
        Route::patch('/assignments/{assignment}', [TransportController::class, 'updateAssignment'])->middleware('permission:transport.assign')->name('assignments.patch');
        Route::delete('/assignments/{assignment}', [TransportController::class, 'destroyAssignment'])->middleware('permission:transport.assign')->name('assignments.destroy');

        Route::get('/fees', [TransportController::class, 'fees'])->middleware('permission:transport.fees.view')->name('fees.index');
        Route::get('/fees/create', [TransportController::class, 'createFee'])->middleware('permission:transport.fees.manage')->name('fees.create');
        Route::post('/fees', [TransportController::class, 'storeFee'])->middleware('permission:transport.fees.manage')->name('fees.store');
        Route::get('/fees/{fee}/edit', [TransportController::class, 'editFee'])->middleware('permission:transport.fees.manage')->name('fees.edit');
        Route::put('/fees/{fee}', [TransportController::class, 'updateFee'])->middleware('permission:transport.fees.manage')->name('fees.update');
        Route::patch('/fees/{fee}', [TransportController::class, 'updateFee'])->middleware('permission:transport.fees.manage')->name('fees.patch');
        Route::delete('/fees/{fee}', [TransportController::class, 'destroyFee'])->middleware('permission:transport.fees.manage')->name('fees.destroy');
    });
