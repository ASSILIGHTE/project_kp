<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->role === 'admin'
            ? redirect()->route('dashboard')
            : redirect()->route('dashboard.petugas.home');
    }
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Shared Auth Group (Laporan details, download & JSON views accessible to logged-in users)
Route::middleware(['auth'])->group(function () {
    Route::get('/reports/{id}', [ReportController::class, 'show'])->name('reports.show');
    Route::get('/reports/{id}/download/word', [ReportController::class, 'downloadWord'])->name('reports.downloadWord');
    Route::get('/reports/{id}/download/pdf', [ReportController::class, 'downloadPdf'])->name('reports.downloadPdf');
});

// Admin Routes (Admin Dashboard & User Management)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [UserController::class, 'index'])->name('dashboard');
    
    // User CRUD
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{id}/reset', [UserController::class, 'resetPassword'])->name('users.reset');
});

// Petugas & Report CRUD Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard-petugas/home', [ReportController::class, 'dashboard'])->name('dashboard.petugas.home');
    Route::get('/dashboard-petugas', [ReportController::class, 'create'])->name('dashboard.petugas');
    Route::get('/riwayat', [ReportController::class, 'index'])->name('reports.index');
    
    // Report CRUD
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
    Route::get('/reports/{id}/edit', [ReportController::class, 'edit'])->name('reports.edit');
    Route::put('/reports/{id}', [ReportController::class, 'update'])->name('reports.update');
    Route::delete('/reports/{id}', [ReportController::class, 'destroy'])->name('reports.destroy');
});