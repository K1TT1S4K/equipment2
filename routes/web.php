<?php

// นี่คือ contract ไว้กำหนด method ที่เราต้องการ
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\EquipmentTypeController;
use App\Http\Controllers\EquipmentUnitController;
use App\Http\Controllers\TitleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EquipmentDocumentController;
use App\Http\Controllers\ActivityController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['auth'])->group(function () {
    Route::post('/equipment/update-status', [EquipmentController::class, 'updateStatus'])->name('equipment.update_status');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('equipment/', [EquipmentController::class, 'index'])->name('equipment.index');
    Route::get('/export', [EquipmentController::class, 'export'])->name('equipment.export');
    Route::get('equipment/{id}/edit', [EquipmentController::class, 'edit'])->name('equipment.edit');
    Route::post('equipments_documents', [EquipmentDocumentController::class, 'store'])->name('equipments_documents.store');
    Route::delete('equipments_documents/delete-selected', [EquipmentDocumentController::class, 'deleteSelected'])->name('equipments_documents.deleteSelected');
});

Route::middleware(['auth', 'can:admin-or-branch'])->group(function () {
    Route::get('equipment/create', [EquipmentController::class, 'create'])->name('equipment.create');
    Route::post('equipment', [EquipmentController::class, 'store'])->name('equipment.store');
    Route::put('equipment/{id}', [EquipmentController::class, 'update'])->name('equipment.update');
    Route::get('/equipment/trash', [EquipmentController::class, 'trash'])->name('equipment.trash');
    Route::get('equipments/search', [EquipmentController::class, 'search'])->name('equipment.search');
    Route::delete('/equipments/{equipment}', [EquipmentController::class, 'destroy'])->name('equipment.delete');
    Route::post('equipment/force-delete-multiple', [EquipmentController::class, 'forceDeleteMultiple'])->name('equipment.forceDeleteMultiple');
    Route::post('/equipments/delete-selected', [EquipmentController::class, 'deleteSelected'])->name('equipment.deleteSelected');
    Route::post('equipments/restore-multiple', [EquipmentController::class, 'restoreMultiple'])->name('equipment.restoreMultiple');
});

Route::middleware(['auth', 'can:admin'])->group(function () {
    Route::get('activity', [ActivityController::class, 'index'])->name('activity.index');
    Route::get('activity/search', [ActivityController::class, 'search'])->name('activity.search');
    Route::get('user', [UserController::class, 'index'])->name('user');
    Route::get('user/add', [UserController::class, 'create'])->name('user.create');
    Route::get('user/search', [UserController::class, 'search'])->name('user.search');
    Route::post('user', [UserController::class, 'store'])->name('user.store');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('user.show');
    Route::get('user/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
    Route::put('user/update/{id}', [UserController::class, 'update'])->name('user.update');
    Route::get('user/trashed', [UserController::class, 'trashed'])->name('user.trashed');
    Route::get('user/trash/search', [UserController::class, 'searchTrash'])->name('user.trashsearch');
    Route::post('user/restore-selected', [UserController::class, 'restoreSelected'])->name('user.restoreSelected');
    Route::delete('user/force-delete-selected', [UserController::class, 'forceDeleteSelected'])->name('user.forceDeleteSelected');
    Route::delete('user/delete-selected', [UserController::class, 'deleteSelected'])->name('user.deleteSelected'); // ลบที่เลือก
});

Route::middleware(['auth'])->group(function () {
    Route::get('/documents', [DocumentController::class, 'index'])->name('document.index');
    Route::get('documents/search', [DocumentController::class, 'search'])->name('document.search');
    Route::get('/documents/{id}/edit', [DocumentController::class, 'edit'])->name('document.edit');
});

Route::middleware(['auth', 'can:admin-or-branch-or-officer'])->group(function () {
    Route::get('/documents/create', [DocumentController::class, 'create'])->name('document.create');
    Route::post('/documents', [DocumentController::class, 'store'])->name('document.store');
    Route::put('/documents/{id}', [DocumentController::class, 'update'])->name('document.update');
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('document.delete');
    Route::get('/document/trash', [DocumentController::class, 'trash'])->name('document.trash');
    Route::get('/trash/search', [DocumentController::class, 'searchTrash'])->name('trash.search');
    Route::post('/restore-multiple', [DocumentController::class, 'restoreMultiple'])->name('document.restoreMultiple');
    Route::delete('/delete-selected', [DocumentController::class, 'deleteSelected'])->name('document.deleteSelected');
    Route::delete('/force-delete-selected', [DocumentController::class, 'forceDeleteSelected'])->name('document.forceDeleteSelected');
});


Route::middleware(['auth'])->group(function () {
    Route::get('profile', [UserController::class, 'profile'])->name('profile');
    Route::put('profile/update', [UserController::class, 'updateProfile'])->name('profile.update');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('types', EquipmentTypeController::class)->except(['create', 'edit', 'show']);

    // เพิ่ม name ให้ route นี้
    Route::get('types/{type}/check-usage', [EquipmentTypeController::class, 'checkUsage'])->name('types.checkUsage');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('equipment_units', EquipmentUnitController::class)->except(['create', 'edit', 'show']);

    // เพิ่ม name ให้ route นี้
    Route::get('equipment_units/{equipment_unit}/check-usage', [EquipmentUnitController::class, 'checkUsage'])->name('equipment_units.checkUsage');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('locations', LocationController::class)->except(['create', 'edit', 'show']);

    // เพิ่ม name ให้ route นี้
    Route::get('locations/{location}/check-usage', [LocationController::class, 'checkUsage'])->name('locations.checkUsage');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('titles', TitleController::class)->except(['create', 'edit', 'show']);

    // เพิ่ม name ให้ route นี้
    Route::get('titles/{title}/check-usage', [TitleController::class, 'checkUsage'])->name('titles.checkUsage');
    Route::get('titles/{title}/clone', [TitleController::class, 'clone'])->name('titles.clone');
});

require __DIR__ . '/auth.php';
