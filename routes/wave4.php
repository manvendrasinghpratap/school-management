<?php

use App\Http\Controllers\Admin\Wave4\AdmissionController;
use App\Http\Controllers\Admin\Wave4\AssetController;
use App\Http\Controllers\Admin\Wave4\ComplaintController;
use App\Http\Controllers\Admin\Wave4\DesignationController;
use App\Http\Controllers\Admin\Wave4\DisciplineController;
use App\Http\Controllers\Admin\Wave4\InventoryController;
use App\Http\Controllers\Admin\Wave4\MedicalController;
use App\Http\Controllers\Admin\Wave4\StaffDocumentController;
use App\Http\Controllers\Admin\Wave4\SupplierController;
use App\Http\Controllers\Admin\Wave4\VisitorController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Designations
    |--------------------------------------------------------------------------
    */

    Route::get('designations', [DesignationController::class, 'index'])
        ->name('designations.index')
        ->middleware('permission:designations.view');

    Route::get('designations/create', [DesignationController::class, 'create'])
        ->name('designations.create')
        ->middleware('permission:designations.manage');

    Route::post('designations', [DesignationController::class, 'store'])
        ->name('designations.store')
        ->middleware('permission:designations.manage');

    Route::get('designations/{designation}/edit', [DesignationController::class, 'edit'])
        ->name('designations.edit')
        ->middleware('permission:designations.manage');

    Route::put('designations/{designation}', [DesignationController::class, 'update'])
        ->name('designations.update')
        ->middleware('permission:designations.manage');

    Route::patch('designations/{designation}', [DesignationController::class, 'update'])
        ->middleware('permission:designations.manage');

    Route::delete('designations/{designation}', [DesignationController::class, 'destroy'])
        ->name('designations.destroy')
        ->middleware('permission:designations.manage');


    /*
    |--------------------------------------------------------------------------
    | Suppliers
    |--------------------------------------------------------------------------
    */

    Route::get('suppliers', [SupplierController::class, 'index'])
        ->name('suppliers.index')
        ->middleware('permission:suppliers.view');

    Route::get('suppliers/create', [SupplierController::class, 'create'])
        ->name('suppliers.create')
        ->middleware('permission:suppliers.manage');

    Route::post('suppliers', [SupplierController::class, 'store'])
        ->name('suppliers.store')
        ->middleware('permission:suppliers.manage');

    Route::get('suppliers/{supplier}/edit', [SupplierController::class, 'edit'])
        ->name('suppliers.edit')
        ->middleware('permission:suppliers.manage');

    Route::put('suppliers/{supplier}', [SupplierController::class, 'update'])
        ->name('suppliers.update')
        ->middleware('permission:suppliers.manage');

    Route::patch('suppliers/{supplier}', [SupplierController::class, 'update'])
        ->middleware('permission:suppliers.manage');

    Route::delete('suppliers/{supplier}', [SupplierController::class, 'destroy'])
        ->name('suppliers.destroy')
        ->middleware('permission:suppliers.manage');


    /*
    |--------------------------------------------------------------------------
    | Inventory
    |--------------------------------------------------------------------------
    */

    Route::get('inventory', [InventoryController::class, 'index'])
        ->name('inventory.index')
        ->middleware('permission:inventory.view');

    Route::get('inventory/create', [InventoryController::class, 'create'])
        ->name('inventory.create')
        ->middleware('permission:inventory.manage');

    Route::post('inventory', [InventoryController::class, 'store'])
        ->name('inventory.store')
        ->middleware('permission:inventory.manage');

    Route::get('inventory/{inventory}/edit', [InventoryController::class, 'edit'])
        ->name('inventory.edit')
        ->middleware('permission:inventory.manage');

    Route::put('inventory/{inventory}', [InventoryController::class, 'update'])
        ->name('inventory.update')
        ->middleware('permission:inventory.manage');

    Route::patch('inventory/{inventory}', [InventoryController::class, 'update'])
        ->middleware('permission:inventory.manage');

    Route::delete('inventory/{inventory}', [InventoryController::class, 'destroy'])
        ->name('inventory.destroy')
        ->middleware('permission:inventory.manage');


    /*
    |--------------------------------------------------------------------------
    | Assets
    |--------------------------------------------------------------------------
    */

    Route::get('assets', [AssetController::class, 'index'])
        ->name('assets.index')
        ->middleware('permission:assets.view');

    Route::get('assets/create', [AssetController::class, 'create'])
        ->name('assets.create')
        ->middleware('permission:assets.manage');

    Route::post('assets', [AssetController::class, 'store'])
        ->name('assets.store')
        ->middleware('permission:assets.manage');

    Route::get('assets/{asset}/edit', [AssetController::class, 'edit'])
        ->name('assets.edit')
        ->middleware('permission:assets.manage');

    Route::put('assets/{asset}', [AssetController::class, 'update'])
        ->name('assets.update')
        ->middleware('permission:assets.manage');

    Route::patch('assets/{asset}', [AssetController::class, 'update'])
        ->middleware('permission:assets.manage');

    Route::delete('assets/{asset}', [AssetController::class, 'destroy'])
        ->name('assets.destroy')
        ->middleware('permission:assets.manage');

/*
|--------------------------------------------------------------------------
| Visitors
|--------------------------------------------------------------------------
*/

Route::get('visitors', [VisitorController::class, 'index'])
    ->name('visitors.index')
    ->middleware('permission:visitors.view');

Route::get('visitors/create', [VisitorController::class, 'create'])
    ->name('visitors.create')
    ->middleware('permission:visitors.manage');

Route::post('visitors', [VisitorController::class, 'store'])
    ->name('visitors.store')
    ->middleware('permission:visitors.manage');

Route::get('visitors/{visitor}/edit', [VisitorController::class, 'edit'])
    ->name('visitors.edit')
    ->middleware('permission:visitors.manage');

Route::put('visitors/{visitor}', [VisitorController::class, 'update'])
    ->name('visitors.update')
    ->middleware('permission:visitors.manage');

Route::patch('visitors/{visitor}', [VisitorController::class, 'update'])
    ->middleware('permission:visitors.manage');

Route::delete('visitors/{visitor}', [VisitorController::class, 'destroy'])
    ->name('visitors.destroy')
    ->middleware('permission:visitors.manage');


/*
|--------------------------------------------------------------------------
| Complaints
|--------------------------------------------------------------------------
*/

Route::get('complaints', [ComplaintController::class, 'index'])
    ->name('complaints.index')
    ->middleware('permission:complaints.view');

Route::get('complaints/create', [ComplaintController::class, 'create'])
    ->name('complaints.create')
    ->middleware('permission:complaints.manage');

Route::post('complaints', [ComplaintController::class, 'store'])
    ->name('complaints.store')
    ->middleware('permission:complaints.manage');

Route::get('complaints/{complaint}/edit', [ComplaintController::class, 'edit'])
    ->name('complaints.edit')
    ->middleware('permission:complaints.manage');

Route::put('complaints/{complaint}', [ComplaintController::class, 'update'])
    ->name('complaints.update')
    ->middleware('permission:complaints.manage');

Route::patch('complaints/{complaint}', [ComplaintController::class, 'update'])
    ->middleware('permission:complaints.manage');

Route::delete('complaints/{complaint}', [ComplaintController::class, 'destroy'])
    ->name('complaints.destroy')
    ->middleware('permission:complaints.manage');

/*
|--------------------------------------------------------------------------
| Disciplinary Records
|--------------------------------------------------------------------------
*/

Route::get('discipline', [DisciplineController::class, 'index'])
    ->name('discipline.index')
    ->middleware('permission:discipline.view');

Route::get('discipline/create', [DisciplineController::class, 'create'])
    ->name('discipline.create')
    ->middleware('permission:discipline.manage');

Route::post('discipline', [DisciplineController::class, 'store'])
    ->name('discipline.store')
    ->middleware('permission:discipline.manage');

Route::get('discipline/{discipline}/edit', [DisciplineController::class, 'edit'])
    ->name('discipline.edit')
    ->middleware('permission:discipline.manage');

Route::put('discipline/{discipline}', [DisciplineController::class, 'update'])
    ->name('discipline.update')
    ->middleware('permission:discipline.manage');

Route::patch('discipline/{discipline}', [DisciplineController::class, 'update'])
    ->middleware('permission:discipline.manage');

Route::delete('discipline/{discipline}', [DisciplineController::class, 'destroy'])
    ->name('discipline.destroy')
    ->middleware('permission:discipline.manage');



/*
|--------------------------------------------------------------------------
| Medical Records
|--------------------------------------------------------------------------
*/

Route::get('medical', [MedicalController::class, 'index'])
    ->name('medical.index')
    ->middleware('permission:medical.view');

Route::get('medical/create', [MedicalController::class, 'create'])
    ->name('medical.create')
    ->middleware('permission:medical.manage');

Route::post('medical', [MedicalController::class, 'store'])
    ->name('medical.store')
    ->middleware('permission:medical.manage');

Route::get('medical/{medical}/edit', [MedicalController::class, 'edit'])
    ->name('medical.edit')
    ->middleware('permission:medical.manage');

Route::put('medical/{medical}', [MedicalController::class, 'update'])
    ->name('medical.update')
    ->middleware('permission:medical.manage');

Route::patch('medical/{medical}', [MedicalController::class, 'update'])
    ->middleware('permission:medical.manage');

Route::delete('medical/{medical}', [MedicalController::class, 'destroy'])
    ->name('medical.destroy')
    ->middleware('permission:medical.manage');


    /*
    |--------------------------------------------------------------------------
    | Staff Documents
    |--------------------------------------------------------------------------
    */

    Route::get('staff/{staff}/documents', [StaffDocumentController::class, 'index'])
        ->name('staff.documents.index')
        ->middleware('permission:staff.documents.view');

    Route::post('staff/{staff}/documents', [StaffDocumentController::class, 'store'])
        ->name('staff.documents.store')
        ->middleware('permission:staff.documents.manage');

    Route::get('staff/{staff}/documents/{document}/download', [StaffDocumentController::class, 'download'])
        ->name('staff.documents.download')
        ->middleware('permission:staff.documents.view');

    Route::delete('staff/{staff}/documents/{document}', [StaffDocumentController::class, 'destroy'])
        ->name('staff.documents.destroy')
        ->middleware('permission:staff.documents.manage');


    /*
    |--------------------------------------------------------------------------
    | Admissions
    |--------------------------------------------------------------------------
    */

    Route::get('admissions', [AdmissionController::class, 'index'])
        ->name('admissions.index')
        ->middleware('permission:admissions.view');

    Route::get('admissions/create', [AdmissionController::class, 'create'])
        ->name('admissions.create')
        ->middleware('permission:admissions.manage');

    Route::post('admissions', [AdmissionController::class, 'store'])
        ->name('admissions.store')
        ->middleware('permission:admissions.manage');

    Route::get('admissions/{admission}/edit', [AdmissionController::class, 'edit'])
        ->name('admissions.edit')
        ->middleware('permission:admissions.manage');

    Route::put('admissions/{admission}', [AdmissionController::class, 'update'])
        ->name('admissions.update')
        ->middleware('permission:admissions.manage');

    Route::patch('admissions/{admission}', [AdmissionController::class, 'update'])
        ->middleware('permission:admissions.manage');

    Route::delete('admissions/{admission}', [AdmissionController::class, 'destroy'])
        ->name('admissions.destroy')
        ->middleware('permission:admissions.manage');

    Route::patch('admissions/{admission}/accept', [AdmissionController::class, 'accept'])
        ->name('admissions.accept')
        ->middleware('permission:admissions.manage');

    Route::post('admissions/{admission}/convert', [AdmissionController::class, 'convert'])
        ->name('admissions.convert')
        ->middleware('permission:admissions.convert');
});