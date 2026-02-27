<?php
// routes/web.php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Appointment\AppointmentController;
use App\Http\Controllers\Operator\OperatorController;
use Illuminate\Support\Facades\Route;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Login Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected Routes - Use your custom middleware
Route::middleware(['auth.session'])->group(function () {
    // Screener routes
    Route::get('/appointment-issuance', [AppointmentController::class, 'issuance'])
         ->name('appointment.issuance');
    Route::post('/appointment-issue', [AppointmentController::class, 'issue'])
         ->name('appointment.issue');
    Route::post('/appointment/serve/{id}', [AppointmentController::class, 'serve'])
         ->name('appointment.serve');

    // Operator routes
    Route::get('/operator/dashboard', [OperatorController::class, 'dashboard'])
         ->name('operator.dashboard');
    Route::get('/operator/fetch-appointments', [OperatorController::class, 'fetchAppointments'])
         ->name('operator.fetch-appointments');
    Route::post('/operator/update-window', [OperatorController::class, 'updateWindow'])
         ->name('operator.update-window');
});
