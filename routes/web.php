<?php
// routes/web.php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Appointment\AppointmentController;
use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Operator\OperatorController;
use Illuminate\Support\Facades\Route;

// Public routes (no authentication required)
Route::get('/', function () {
    return redirect()->route('login'); // Redirect to client dashboard
});

// Client routes - PUBLIC (no middleware)
Route::get('/client-dashboard', [ClientController::class, 'dashboard'])
     ->name('client.dashboard');
Route::get('/client/queues', [ClientController::class, 'getQueues'])
     ->name('client.queues');

// Guest routes (for non-authenticated users only)
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
});

// Logout route (requires authentication)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth.session');

// Protected Routes - Require authentication
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
