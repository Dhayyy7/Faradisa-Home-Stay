<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\UserController;

// Homepage redirect or welcome
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

// Protected Admin Routes
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    // Super Admin Only Routes
    Route::middleware(['superadmin'])->group(function () {
        // Room & Unit Management Routes
        Route::get('/admin/rooms', [RoomController::class, 'index'])->name('admin.rooms.index');
        Route::post('/admin/rooms', [RoomController::class, 'store'])->name('admin.rooms.store');
        Route::put('/admin/rooms/{room}', [RoomController::class, 'update'])->name('admin.rooms.update');
        Route::delete('/admin/rooms/{room}', [RoomController::class, 'destroy'])->name('admin.rooms.destroy');

        // Master Facility Routes
        Route::get('/admin/facilities', [FacilityController::class, 'index'])->name('admin.facilities.index');
        Route::post('/admin/facilities', [FacilityController::class, 'store'])->name('admin.facilities.store');
        Route::put('/admin/facilities/{facility}', [FacilityController::class, 'update'])->name('admin.facilities.update');
        Route::delete('/admin/facilities/{facility}', [FacilityController::class, 'destroy'])->name('admin.facilities.destroy');

        // User Management Routes
        Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
        Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
        Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
        Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');

        // Role Management Routes
        Route::get('/admin/roles', [RoleController::class, 'index'])->name('admin.roles.index');
        Route::post('/admin/roles', [RoleController::class, 'store'])->name('admin.roles.store');
        Route::put('/admin/roles/{role}', [RoleController::class, 'update'])->name('admin.roles.update');
        Route::delete('/admin/roles/{role}', [RoleController::class, 'destroy'])->name('admin.roles.destroy');
    });
});
