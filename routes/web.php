<?php

use App\Http\Controllers\DocumentController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('equipment', [EquipmentController::class, 'index'])->name('equipment.index');
    Route::get('equipment/add', [EquipmentController::class, 'create'])->name('equipment.add');
    Route::post('equipment', [EquipmentController::class, 'store'])->name('equipment.store');
    Route::get('equipment/{id}/edit', [EquipmentController::class, 'edit'])->name('equipment.edit');
    Route::put('equipment/{id}', [EquipmentController::class, 'update'])->name('equipment.update');
    Route::delete('equipment/{id}', [EquipmentController::class, 'destroy'])->name('equipment.destroy');
    Route::get('/export', [EquipmentController::class, 'export'])->name('equipment.export');
    Route::get('/equipment/trash', [EquipmentController::class, 'trash'])->name('equipment.trash');;
});

Route::middleware(['auth'])->group(function () {
    Route::get('user', [UserController::class, 'index'])->name('user');
    Route::get('user/add', [UserController::class, 'create'])->name('user.create');
    Route::get('user/search', [UserController::class, 'search'])->name('user.search');
    Route::post('user', [UserController::class, 'store'])->name('user.store');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('user.show');
    Route::get('user/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
    Route::put('user/update/{id}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/users/delete', [UserController::class, 'destroy'])->name('user.delete');
    Route::get('user/trashed', [UserController::class, 'trashed'])->name('user.trashed');
    Route::get('user/trash/search', [UserController::class, 'searchTrash'])->name('user.trashsearch');
    Route::post('user/restore/{id}', [UserController::class, 'restore'])->name('user.restore'); // กู้คืนผู้ใช้
    Route::post('user/restore-selected', [UserController::class, 'restoreSelected'])->name('user.restoreSelected');
    Route::post('user/restore-all', [UserController::class, 'restoreAll'])->name('user.restoreAll'); // กู้คืนทั้งหมด
    Route::delete('user/force-delete/{id}', [UserController::class, 'forceDelete'])->name('user.forceDelete');
    Route::post('user/delete-selected', [UserController::class, 'deleteSelected'])->name('user.deleteSelected');
    Route::delete('user/delete-all', [UserController::class, 'deleteAll'])->name('user.deleteAll');
    Route::delete('user/delete-selected', [UserController::class, 'deleteSelected'])->name('user.deleteSelected'); // ลบที่เลือก
    // Route::delete('user/delete-all', [UserController::class, 'deleteAll'])->name('user.deleteAll'); // ลบทั้งหมด
    Route::delete('user/delete-selected-all', [UserController::class, 'deleteSelectedAll'])->name('user.deleteSelectedAll'); // ลบที่เลือกทั้งหมด
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
    Route::get('/document/trash', [DocumentController::class, 'trash'])->name('document.trash');
    Route::get('/trash/search', [DocumentController::class, 'searchTrash'])->name('trash.search');
    Route::post('/restore/{id}', [DocumentController::class, 'restore'])->name('document.restore');
    Route::post('/restore-multiple', [DocumentController::class, 'restoreMultiple'])->name('document.restoreMultiple');
    Route::post('/restore-all', [DocumentController::class, 'restoreAllDocuments'])->name('document.restoreAll');
    Route::delete('/force-delete/{id}', [DocumentController::class, 'forceDelete'])->name('document.forceDelete');
    Route::post('/delete-selected', [DocumentController::class, 'deleteSelected'])->name('document.deleteSelected');
    Route::delete('/delete-all', [DocumentController::class, 'deleteAll'])->name('document.deleteAll');
    Route::delete('/delete-selected', [DocumentController::class, 'deleteSelected'])->name('document.deleteSelected');
    Route::delete('/delete-selected-all', [DocumentController::class, 'deleteSelectedAll'])->name('document.deleteSelectedAll');
});

Route::middleware(['auth'])->group(function () {
    Route::get('profile', [UserController::class, 'profile'])->name('profile');
    Route::put('profile/update', [UserController::class, 'updateProfile'])->name('profile.update');
});


// Route::middleware(['auth'])->group(function () {
//     Route::redirect('users', 'users/show');

//     Volt::route('users/show', 'users.show')->name('users.show');
//     Volt::route('users/add', 'users.add')->name('users.add');
// });

// Route::get('/export', [EquipmentExportController::class, 'export'])
//     ->middleware(['auth'])
//     ->name('export.excel');

// Route::middleware(['auth'])->group(function () {
//     Route::redirect('settings', 'settings/profile');

//     Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
//     Volt::route('settings/password', 'settings.password')->name('settings.password');
//     Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
// });

require __DIR__.'/auth.php';
