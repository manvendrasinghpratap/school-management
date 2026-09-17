<?php

use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\CertificateController;
use App\Http\Controllers\Admin\IdCardController;
use App\Http\Controllers\Admin\SchoolEventController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::resource('announcements', AnnouncementController::class)
        ->names('admin.announcements');

    Route::resource('events', SchoolEventController::class)
        ->names('admin.events');

    Route::get('events/{event}/participants', [SchoolEventController::class,'participants'])
        ->name('admin.events.participants');
    Route::post('events/{event}/participants', [SchoolEventController::class,'addParticipant'])
        ->name('admin.events.participants.add');
    Route::delete('events/{event}/participants/{participant}', [SchoolEventController::class,'removeParticipant'])
        ->name('admin.events.participants.remove');

    Route::get('certificates', [CertificateController::class,'index'])->name('admin.certificates.index');
    Route::get('certificates/create', [CertificateController::class,'create'])->name('admin.certificates.create');
    Route::post('certificates', [CertificateController::class,'store'])->name('admin.certificates.store');
    Route::get('certificates/templates', [CertificateController::class,'templates'])->name('admin.certificates.templates');
    Route::post('certificates/templates', [CertificateController::class,'storeTemplate'])->name('admin.certificates.templates.store');
    Route::put('certificates/templates/{template}', [CertificateController::class,'updateTemplate'])->name('admin.certificates.templates.update');
    Route::delete('certificates/templates/{template}', [CertificateController::class,'deleteTemplate'])->name('admin.certificates.templates.delete');
    Route::get('certificates/{certificate}', [CertificateController::class,'show'])->name('admin.certificates.show');
    Route::get('certificates/{certificate}/print', [CertificateController::class,'print'])->name('admin.certificates.print');
    Route::delete('certificates/{certificate}', [CertificateController::class,'destroy'])->name('admin.certificates.destroy');

    Route::get('id-cards', [IdCardController::class,'index'])->name('admin.id-cards.index');
    Route::get('id-cards/create', [IdCardController::class,'create'])->name('admin.id-cards.create');
    Route::post('id-cards', [IdCardController::class,'store'])->name('admin.id-cards.store');
    Route::get('id-cards/templates', [IdCardController::class,'templates'])->name('admin.id-cards.templates');
    Route::post('id-cards/templates', [IdCardController::class,'storeTemplate'])->name('admin.id-cards.templates.store');
    Route::put('id-cards/templates/{template}', [IdCardController::class,'updateTemplate'])->name('admin.id-cards.templates.update');
    Route::delete('id-cards/templates/{template}', [IdCardController::class,'deleteTemplate'])->name('admin.id-cards.templates.delete');
    Route::get('id-cards/{idCard}', [IdCardController::class,'show'])->name('admin.id-cards.show');
    Route::get('id-cards/{idCard}/print', [IdCardController::class,'print'])->name('admin.id-cards.print');
    Route::delete('id-cards/{idCard}', [IdCardController::class,'destroy'])->name('admin.id-cards.destroy');
});
