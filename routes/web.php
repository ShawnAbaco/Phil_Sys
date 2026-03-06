<?php
// routes/web.php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Appointment\AppointmentController;
use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Operator\OperatorController;
use App\Http\Controllers\Profile\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AppointmentsController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\WindowController;
use App\Http\Controllers\Admin\ExportController;


Route::get('/test-json', function() {
    return response()->json(['message' => 'JSON is working']);
});


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
    return redirect()->route('login');
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

// Logout route
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected Routes - Require authentication using Laravel's 'auth' middleware
Route::middleware(['auth'])->group(function () {
    // Screener routes
    Route::get('/appointment-issuance', [AppointmentController::class, 'issuance'])
         ->name('appointment.issuance');
    Route::post('/appointment-issue', [AppointmentController::class, 'issue'])
         ->name('appointment.issue');
    Route::post('/appointment/serve/{id}', [AppointmentController::class, 'serve'])
         ->name('appointment.serve');
    Route::get('/appointment/today', [AppointmentController::class, 'getTodayAppointments'])
         ->name('appointment.today');
    Route::get('/appointments/transactions-page', [AppointmentController::class, 'getTransactionsPage'])->name('appointment.transactions-page');
    // Export routes
    Route::get('/appointment/export/pdf', [AppointmentController::class, 'exportPDF'])->name('appointment.export.pdf');
    Route::get('/appointment/export/excel', [AppointmentController::class, 'exportExcel'])->name('appointment.export.excel');

    //profile routes
    Route::get('/profile/settings', [ProfileController::class, 'settings'])->name('profile.settings');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'password'])->name('profile.password');

    // Operator routes
    Route::get('/operator/dashboard', [OperatorController::class, 'dashboard'])
         ->name('operator.dashboard');
    Route::get('/operator/fetch-appointments', [OperatorController::class, 'fetchAppointments'])
         ->name('operator.fetch-appointments');
    Route::post('/operator/update-window', [OperatorController::class, 'updateWindow'])
         ->name('operator.update-window');
    Route::get('/operator/queued-appointments', [OperatorController::class, 'getQueuedAppointments'])
         ->name('operator.queued-appointments');
    Route::get('/operator/recent-transactions', [OperatorController::class, 'recentTransactions'])
         ->name('operator.recent-transactions');
    Route::get('/operator/transactions-page', [OperatorController::class, 'getTransactionsPage'])
         ->name('operator.transactions-page');
    // Export routes
    Route::get('/operator/export/pdf', [OperatorController::class, 'exportPDF'])->name('operator.export.pdf');
    Route::get('/operator/export/excel', [OperatorController::class, 'exportExcel'])->name('operator.export.excel');
});

// Admin Routes - Directly in web.php (no separate file needed)
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/notifications', [DashboardController::class, 'getNotifications'])->name('notifications');
    Route::get('/search', [DashboardController::class, 'search'])->name('search');
    Route::get('/quick-stats', [DashboardController::class, 'getQuickStats'])->name('quick-stats');
    
    // Users Management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/list', [UserController::class, 'getUsersList'])->name('list');
        Route::get('/{id}', [UserController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/reset-password', [UserController::class, 'resetPassword'])->name('reset-password');
        Route::post('/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle-status');
    });
    
    // Appointments Management
    Route::prefix('appointments')->name('appointments.')->group(function () {
        Route::get('/', [AppointmentsController::class, 'index'])->name('index');
        Route::get('/calendar', [AppointmentsController::class, 'getCalendarEvents'])->name('calendar');
        Route::get('/{id}', [AppointmentsController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [AppointmentsController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AppointmentsController::class, 'update'])->name('update');
        Route::delete('/{id}', [AppointmentsController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/serve', [AppointmentsController::class, 'markAsServed'])->name('serve');
    });
    
    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/daily', [ReportController::class, 'daily'])->name('daily');
        Route::get('/weekly', [ReportController::class, 'weekly'])->name('weekly');
        Route::get('/monthly', [ReportController::class, 'monthly'])->name('monthly');
        Route::get('/custom', [ReportController::class, 'custom'])->name('custom');
        Route::get('/chart-data', [ReportController::class, 'getChartData'])->name('chart-data');
    });
    
    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::post('/general', [SettingController::class, 'updateGeneral'])->name('update-general');
        Route::post('/windows', [SettingController::class, 'updateWindows'])->name('update-windows');
        Route::post('/notifications', [SettingController::class, 'updateNotifications'])->name('update-notifications');
        Route::post('/queue-rules', [SettingController::class, 'updateQueueRules'])->name('update-queue-rules');
        
        // Backup
        Route::post('/backup/create', [SettingController::class, 'createBackup'])->name('backup.create');
        Route::get('/backups', [SettingController::class, 'getBackups'])->name('backups');
        Route::post('/backup/restore', [SettingController::class, 'restoreBackup'])->name('backup.restore');
        Route::delete('/backup/delete', [SettingController::class, 'deleteBackup'])->name('backup.delete');
        
        // System
        Route::post('/maintenance/toggle', [SettingController::class, 'toggleMaintenance'])->name('maintenance.toggle');
        Route::post('/cache/clear', [SettingController::class, 'clearCache'])->name('cache.clear');
    });
    
    // Activity Logs
    Route::prefix('logs')->name('logs.')->group(function () {
        Route::get('/', [LogController::class, 'index'])->name('index');
        Route::get('/stats', [LogController::class, 'getStats'])->name('stats');
        Route::get('/{id}', [LogController::class, 'show'])->name('show');
        Route::delete('/clear-old', [LogController::class, 'clearOld'])->name('clear-old');
    });
    
    // Windows Management
    Route::prefix('windows')->name('windows.')->group(function () {
        Route::get('/', [WindowController::class, 'index'])->name('index');
        Route::get('/status', [WindowController::class, 'getAllStatus'])->name('status');
        Route::get('/{id}', [WindowController::class, 'show'])->name('show');
        Route::get('/{id}/stats', [WindowController::class, 'getStats'])->name('stats');
        Route::put('/{id}', [WindowController::class, 'update'])->name('update');
        Route::post('/{id}/assign-operator', [WindowController::class, 'assignOperator'])->name('assign-operator');
        Route::delete('/{id}/remove-operator', [WindowController::class, 'removeOperator'])->name('remove-operator');
        Route::post('/{id}/toggle', [WindowController::class, 'toggleStatus'])->name('toggle');
    });
    
    // Exports
    Route::prefix('export')->name('export.')->group(function () {
        Route::get('/users', [ExportController::class, 'exportUsers'])->name('users');
        Route::get('/appointments', [ExportController::class, 'exportAppointments'])->name('appointments');
        Route::get('/logs', [ExportController::class, 'exportLogs'])->name('logs');
        Route::get('/report', [ExportController::class, 'exportReport'])->name('report');
        Route::get('/backup', [ExportController::class, 'downloadBackup'])->name('backup');
    });
});