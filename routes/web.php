<?php

use App\Http\Controllers\DocumentController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\UserController;
// use App\Http\Livewire\Actions\DocumentsLivewire;
// use App\Http\Livewire\DocumentsLivewire;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Route::resource('documents', DocumentController::class);
Route::middleware(['auth'])->group(function () {
    Route::get('documents', [DocumentController::class, 'index'])->name('document.index');
});
// Route::view('equipment', 'livewire.equipments.show')
//     ->middleware(['auth', 'verified'])
//     ->name('equipment');

// Route::middleware(['auth'])->group(function () {
//     // ใช้ Livewire component สำหรับการจัดการเอกสาร
//     Route::get('/documents', DocumentsLivewire::class)->name('document.index');
// });


Route::middleware(['auth'])->group(function () {
    Route::get('equipment', [EquipmentController::class, 'index'])->name('equipment.index');
    Route::get('equipment/add', [EquipmentController::class, 'create'])->name('equipment.add');
    Route::post('equipment', [EquipmentController::class, 'store'])->name('equipment.store');
    Route::get('equipment/{id}/edit', [EquipmentController::class, 'edit'])->name('equipment.edit');
    Route::put('equipment/{id}', [EquipmentController::class, 'update'])->name('equipment.update');
    Route::delete('equipment/{id}', [EquipmentController::class, 'destroy'])->name('equipment.destroy');
    Route::get('/export', [EquipmentController::class, 'export'])->name('equipment.export');
    Route::post('/equipment-units/store', [EquipmentController::class, 'storeUnit'])->name('equipment_units.store');
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
