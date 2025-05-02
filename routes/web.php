<?php

use App\Http\Controllers\DocumentController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\EquipmentTypeController;
use App\Http\Controllers\EquipmentUnitController;
use App\Http\Controllers\TitleController;
use App\Models\Equipment;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Route::middleware(['auth'])->group(function () {
//     Route::get('documents', [DocumentController::class, 'index'])->name('document.index');
//     Route::get('equipments', [EquipmentController::class, 'index'])->name('equipment.index');
// });

Route::middleware(['auth'])->group(function () {
    Route::get('equipment/', [EquipmentController::class, 'index'])->name('equipment.index');
    Route::get('/export', [EquipmentController::class, 'export'])->name('equipment.index');
    Route::get('equipment/create', [EquipmentController::class, 'create'])->name('equipment.create');
    Route::post('equipment', [EquipmentController::class, 'store'])->name('equipment.store');
    Route::get('equipment/{id}/edit', [EquipmentController::class, 'edit'])->name('equipment.edit');
    Route::put('equipment/{id}', [EquipmentController::class, 'update'])->name('equipment.update');
    Route::delete('equipment/{id}', [EquipmentController::class, 'destroy'])->name('equipment.destroy');
    Route::get('/export', [EquipmentController::class, 'export'])->name('equipment.export');
    Route::post('/equipment-units/store', [EquipmentController::class, 'storeUnit'])->name('equipment_units.store');
    Route::get('/get-equipment-types/{title_id}', [EquipmentController::class, 'getEquipmentTypes']);
    Route::post('/equipment/move-to-trash', [EquipmentController::class, 'moveToTrash'])->name('equipment.moveToTrash');
    Route::post('/equipment/restore-from-trash', [EquipmentController::class, 'restoreFromTrash'])->name('equipment.restoreFromTrash');
});

Route::middleware(['auth'])->group(function () {
    Route::get('user', [UserController::class, 'index'])->name('user');
    Route::get('user/add', [UserController::class, 'create'])->name('user.add');
    Route::get('user/search', [UserController::class, 'search'])->name('user.search');
    Route::post('user', [UserController::class, 'store'])->name('user.store');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('user.show');
    Route::get('user/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
    Route::put('user/update/{id}', [UserController::class, 'update'])->name('user.update');
    Route::delete('user/{id}', [UserController::class, 'destroy'])->name('user.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/documents', [DocumentController::class, 'index'])->name('document.index');
    Route::get('/documents/create', [DocumentController::class, 'create'])->name('document.create');
    Route::get('documents/search', [DocumentController::class, 'search'])->name('document.search');
    Route::post('/documents', [DocumentController::class, 'store'])->name('document.store');
    Route::get('/documents/{id}/edit', [DocumentController::class, 'edit'])->name('document.edit');
    Route::put('/documents/{id}', [DocumentController::class, 'update'])->name('document.update');
    Route::get('/documents/{id}', [DocumentController::class, 'show'])->name('document.show');
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('document.delete');
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
    Route::get('equipment_units/{equipment_units}/check-usage', [EquipmentUnitController::class, 'checkUsage'])->name('equipment_units.checkUsage');
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
});

require __DIR__.'/auth.php';
