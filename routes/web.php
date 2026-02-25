<?php
// routes/web.php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Appointment\AppointmentController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('/login', [LoginController::class, 'showLoginForm']);
Route::post('/login', [LoginController::class, 'login']);
Route::get('/logout', [LoginController::class, 'logout']);

// Protected pages - add session check in each controller instead
Route::get('/appointment-issuance', [AppointmentController::class, 'issuance']);
Route::post('/appointment-issue', [AppointmentController::class, 'issue']);
Route::post('/appointment/serve/{id}', [AppointmentController::class, 'serve']);

