<?php
// routes/web.php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Appointment\AppointmentController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Redirect root to login
Route::get('/', function () {
    return redirect('/login');
});

// Login Routes (no names needed)
Route::get('/login', [LoginController::class, 'showLoginForm']);
Route::post('/login', [LoginController::class, 'login']);
Route::get('/logout', [LoginController::class, 'logout']); // Changed to GET for simplicity

// Screener routes (with simple auth check in controller)
Route::get('/appointment-issuance', [AppointmentController::class, 'issuance']);
Route::post('/appointment-issue', [AppointmentController::class, 'issue']);
Route::post('/appointment/serve/{id}', [AppointmentController::class, 'serve']);

// Simple dashboard routes (you can add controllers later)
Route::get('/operator-dashboard', function () {
    if (!Auth::check()) return redirect('/login');
    return "Operator Dashboard - Coming Soon";
});

Route::get('/client-dashboard', function () {
    if (!Auth::check()) return redirect('/login');
    return "Client Dashboard - Coming Soon";
});

Route::get('/dashboard', function () {
    if (!Auth::check()) return redirect('/login');
    return "Default Dashboard - Coming Soon";
});
