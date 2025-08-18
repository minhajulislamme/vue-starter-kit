<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Manager\ManagerDashboardController;
use App\Http\Controllers\User\UserDashboardController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

// Main dashboard route that redirects based on role
Route::get('dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Admin routes - ONLY admins can access
Route::middleware(['auth', 'verified', 'admin.only'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [AdminDashboardController::class, 'users'])->name('users');
    Route::put('/users/{user}/role', [AdminDashboardController::class, 'updateUserRole'])->name('users.updateRole');
});

// Manager routes - ONLY managers can access
Route::middleware(['auth', 'verified', 'manager.only'])->prefix('manager')->name('manager.')->group(function () {
    Route::get('/dashboard', [ManagerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [ManagerDashboardController::class, 'users'])->name('users');
    Route::get('/reports', [ManagerDashboardController::class, 'reports'])->name('reports');
});

// User routes - ONLY users can access
Route::middleware(['auth', 'verified', 'user.only'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [UserDashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [UserDashboardController::class, 'updateProfile'])->name('profile.update');
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
