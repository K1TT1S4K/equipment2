<?php

use App\Http\Controllers\DocumentController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\EquipmentTypeController;
use App\Http\Controllers\EquipmentUnitController;
use App\Http\Controllers\TitleController;
use App\Http\Controllers\DashboardController;
use App\Models\Equipment;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Route::get('/run-seed', function () {
//     Artisan::call('db:seed');
//     return 'Seeder has been run.';
// });


Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Route::middleware(['auth'])->group(function (){
// Route::get('dashboard/', [EquipmentController::class, 'dashboard'])->name('equipemnt.dashboard');
// Route::get('dashboard/', [DashboardController::class, 'index'])->name('dashboard.index');
// });

// Route::middleware(['auth'])->group(function () {
//     Route::get('documents', [DocumentController::class, 'index'])->name('document.index');
//     Route::get('equipments', [EquipmentController::class, 'index'])->name('equipment.index');
// });

Route::middleware(['auth'])->group(function() {
    Route::get('equipment/', [EquipmentController::class, 'index'])->name('equipment.index');
    Route::get('/export', [EquipmentController::class, 'export'])->name('equipment.index');
    Route::get('equipment/{id}/edit', [EquipmentController::class, 'edit'])->name('equipment.edit');
});

Route::middleware(['auth','can:admin-or-branch'])->group(function () {
    Route::get('equipment/create', [EquipmentController::class, 'create'])->name('equipment.create');
    Route::post('equipment', [EquipmentController::class, 'store'])->name('equipment.store');
    Route::put('equipment/{id}', [EquipmentController::class, 'update'])->name('equipment.update');
    Route::delete('equipment/{id}', [EquipmentController::class, 'destroy'])->name('equipment.destroy');
    Route::get('/export', [EquipmentController::class, 'export'])->name('equipment.export');
    Route::post('/equipment-units/store', [EquipmentController::class, 'storeUnit'])->name('equipment_units.store');
    Route::get('/get-equipment-types/{title_id}', [EquipmentController::class, 'getEquipmentTypes']);
    Route::post('/equipment/move-to-trash', [EquipmentController::class, 'moveToTrash'])->name('equipment.moveToTrash');
    Route::post('/equipment/restore-from-trash', [EquipmentController::class, 'restoreFromTrash'])->name('equipment.restoreFromTrash');
});

Route::middleware(['auth','can:admin'])->group(function () {
    Route::get('user', [UserController::class, 'index'])->name('user');
    Route::get('users/add', [UserController::class, 'create'])->name('user.create');
    Route::get('users/search', [UserController::class, 'search'])->name('user.search');

    // ย้ายบรรทัดนี้ขึ้นมาก่อน users/{user}
    Route::get('users/trashed', [UserController::class, 'trashed'])->name('user.trashed');

    Route::post('users', [UserController::class, 'store'])->name('user.store');

    // route ที่มี {user} หรือ {id} parameter ต้องอยู่หลัง route ที่มี static path
    Route::get('users/{user}', [UserController::class, 'show'])->name('user.show');
    Route::get('users/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('users/{id}', [UserController::class, 'update'])->name('user.update');
    Route::delete('users/{id}', [UserController::class, 'destroy'])->name('user.delete');

    // ส่วนอื่นๆ...
    Route::get('users/trash/search', [UserController::class, 'searchTrash'])->name('user.trashsearch');
    Route::post('users/restore/{id}', [UserController::class, 'restore'])->name('user.restore');
    Route::post('users/restore-selected', [UserController::class, 'restoreSelected'])->name('user.restoreSelected');
    Route::post('users/restore-all', [UserController::class, 'restoreAll'])->name('user.restoreAll');
    Route::delete('users/force-delete/{id}', [UserController::class, 'forceDelete'])->name('user.forceDelete');
    Route::post('users/delete-selected', [UserController::class, 'deleteSelected'])->name('user.deleteSelected');
    Route::delete('users/delete-all', [UserController::class, 'deleteAll'])->name('user.deleteAll');
});



Route::middleware(['auth'])->group(function(){
 Route::get('/documents', [DocumentController::class, 'index'])->name('document.index');
 Route::get('documents/search', [DocumentController::class, 'search'])->name('document.search');
 Route::get('/documents/{id}/edit', [DocumentController::class, 'edit'])->name('document.edit');
});

Route::middleware(['auth','can:admin-or-branch-or-officer'])->group(function () {
    Route::get('/documents/create', [DocumentController::class, 'create'])->name('document.create');
    Route::post('/documents', [DocumentController::class, 'store'])->name('document.store');
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

Route::middleware(['auth','can:admin-or-branch'])->group(function () {
    Route::resource('types', EquipmentTypeController::class)->except(['create', 'edit', 'show']);

    // เพิ่ม name ให้ route นี้
    Route::get('types/{type}/check-usage', [EquipmentTypeController::class, 'checkUsage'])->name('types.checkUsage');
});

Route::middleware(['auth','admin-or-branch'])->group(function () {
    Route::resource('equipment_units', EquipmentUnitController::class)->except(['create', 'edit', 'show']);

    // เพิ่ม name ให้ route นี้
    Route::get('equipment_units/{equipment_unit}/check-usage', [EquipmentUnitController::class, 'checkUsage'])->name('equipment_units.checkUsage');
});

Route::middleware(['auth','can:admin-or-branch'])->group(function () {
    Route::resource('locations', LocationController::class)->except(['create', 'edit', 'show']);

    // เพิ่ม name ให้ route นี้
    Route::get('locations/{location}/check-usage', [LocationController::class, 'checkUsage'])->name('locations.checkUsage');
});

Route::middleware(['auth','can:admin-or-branch'])->group(function () {
    Route::resource('titles', TitleController::class)->except(['create', 'edit', 'show']);

    // เพิ่ม name ให้ route นี้
    Route::get('titles/{title}/check-usage', [TitleController::class, 'checkUsage'])->name('titles.checkUsage');
});

require __DIR__ . '/auth.php';
