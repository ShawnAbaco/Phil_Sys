<?php
// routes/web.php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Login Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected Routes (require authentication)
Route::middleware(['auth.session'])->group(function () {
    // Screener routes
    Route::get('/appointment-issuance', [AppointmentController::class, 'issuance'])->name('appointment.issuance');
    Route::post('/appointment-issue', [AppointmentController::class, 'issue'])->name('appointment.issue'); // ADD THIS LINE
    Route::post('/appointment/serve/{id}', [AppointmentController::class, 'serve'])->name('appointment.serve');
    // Operator routes
    Route::get('/operator-dashboard', [OperatorController::class, 'dashboard'])->name('operator.dashboard');

    // Client routes
    Route::get('/client-dashboard', [ClientController::class, 'dashboard'])->name('client.dashboard');

    // Default page
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('default.page');
});

// Optional: Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});
