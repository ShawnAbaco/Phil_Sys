<?php
// routes/web.php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Appointment\AppointmentController;
use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Operator\OperatorController;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


Route::get('/debug-appointments', function() {
    $today = Carbon\Carbon::today()->toDateString();

    $allToday = App\Models\TblAppointment::whereDate('date', $today)->get();

    $called = App\Models\TblAppointment::whereNotNull('window_num')
        ->whereDate('date', $today)
        ->get();

    $waiting = App\Models\TblAppointment::whereNull('window_num')
        ->whereDate('date', $today)
        ->get();

    return [
        'total_today' => $allToday->count(),
        'called_count' => $called->count(),
        'waiting_count' => $waiting->count(),
        'called_details' => $called->map(function($item) {
            return [
                'q_id' => $item->q_id,
                'window' => $item->window_num,
                'name' => $item->lname,
                'time' => $item->time_catered
            ];
        }),
        'waiting_details' => $waiting->map(function($item) {
            return [
                'q_id' => $item->q_id,
                'name' => $item->lname,
                'time' => $item->date
            ];
        }),
    ];
});



// FORCE LOGOUT - Complete session and cookie cleanup
Route::get('/force-logout', function() {
    // Clear Laravel Auth
    Auth::logout();

    // Clear all session data
    Session::flush();
    Session::regenerate(true);

    // Clear all cookies
    $cookies = [
        'laravel_session',
        'XSRF-TOKEN',
        'remember_web_59ba36addc2b2f9401580f014c7f58ea4e30989d'
    ];

    foreach ($cookies as $cookie) {
        if (isset($_COOKIE[$cookie])) {
            setcookie($cookie, '', time() - 3600, '/');
        }
    }

    // Clear any custom session cookies
    foreach ($_COOKIE as $key => $value) {
        setcookie($key, '', time() - 3600, '/');
    }

    // Return response with cache control headers to prevent caching
    return response()
        ->json([
            'success' => true,
            'message' => 'Force logout completed!',
            'instructions' => 'Please close your browser completely and open a new one.'
        ])
        ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
        ->header('Pragma', 'no-cache')
        ->header('Expires', '0');
});


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

// Admin routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/appointments', [AdminController::class, 'appointments'])->name('appointments');
        Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    });
