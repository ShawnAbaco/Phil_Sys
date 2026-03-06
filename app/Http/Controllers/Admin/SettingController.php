<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SettingController extends AdminController
{


    /**
     * Display system settings.
     */
    public function index()
    {
        $settings = [
            'system_name' => config('app.name'),
            'timezone' => config('app.timezone'),
            'date_format' => session('date_format', 'Y-m-d'),
            'time_format' => session('time_format', '12'),
            'environment' => app()->environment(),
            'debug' => config('app.debug'),
            'maintenance_mode' => app()->isDownForMaintenance(),
        ];
        
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update general settings.
     */
    public function updateGeneral(Request $request)
    {
        $validated = $request->validate([
            'system_name' => 'required|string|max:255',
            'timezone' => 'required|string|timezone',
            'date_format' => 'required|string',
            'time_format' => 'required|string|in:12,24',
            'environment' => 'required|string|in:local,staging,production',
            'debug' => 'nullable|boolean',
        ]);
        
        // Update .env or configuration
        // This is simplified - in production you'd use a proper settings package
        
        session([
            'date_format' => $validated['date_format'],
            'time_format' => $validated['time_format']
        ]);
        
        return redirect()->back()->with('success', 'General settings updated successfully');
    }

    /**
     * Update window configurations.
     */
    public function updateWindows(Request $request)
    {
        $validated = $request->validate([
            'windows' => 'required|array',
            'windows.*.id' => 'required|integer',
            'windows.*.enabled' => 'boolean',
            'windows.*.service_type' => 'string',
            'windows.*.priority' => 'string|in:normal,priority,express',
        ]);
        
        // Save window configurations
        foreach ($validated['windows'] as $windowConfig) {
            Cache::forever('window_' . $windowConfig['id'], $windowConfig);
        }
        
        return redirect()->back()->with('success', 'Window configurations updated successfully');
    }

    /**
     * Update notification settings.
     */
    public function updateNotifications(Request $request)
    {
        $validated = $request->validate([
            'email_notifications' => 'nullable|boolean',
            'sms_notifications' => 'nullable|boolean',
            'display_notifications' => 'nullable|boolean',
            'smtp_host' => 'required_if:email_notifications,true|nullable|string',
            'smtp_port' => 'required_if:email_notifications,true|nullable|integer',
            'smtp_username' => 'required_if:email_notifications,true|nullable|string',
            'smtp_password' => 'required_if:email_notifications,true|nullable|string',
        ]);
        
        // Save notification settings
        foreach ($validated as $key => $value) {
            Cache::forever('notification_' . $key, $value);
        }
        
        return redirect()->back()->with('success', 'Notification settings updated successfully');
    }

    /**
     * Update queue rules.
     */
    public function updateQueueRules(Request $request)
    {
        $validated = $request->validate([
            'max_queue_length' => 'required|integer|min:1|max:999',
            'estimated_wait_time' => 'required|string',
            'queue_prefix' => 'required|string|max:10',
            'queue_suffix' => 'nullable|string|max:10',
            'priorities' => 'required|array',
        ]);
        
        // Save queue rules
        Cache::forever('queue_rules', $validated);
        
        return redirect()->back()->with('success', 'Queue rules updated successfully');
    }

    /**
     * Create system backup.
     */
    public function createBackup()
    {
        try {
            // Run backup command
            Artisan::call('backup:run');
            
            return $this->sendSuccess('Backup created successfully');
            
        } catch (\Exception $e) {
            return $this->sendError('Failed to create backup', $e->getMessage(), 500);
        }
    }

    /**
     * Get list of backups.
     */
    public function getBackups()
    {
        $backups = [];
        $backupPath = storage_path('app/backups');
        
        if (is_dir($backupPath)) {
            $files = scandir($backupPath);
            foreach ($files as $file) {
                if (preg_match('/\.sql$/', $file)) {
                    $backups[] = [
                        'name' => $file,
                        'size' => round(filesize($backupPath . '/' . $file) / 1024 / 1024, 2) . ' MB',
                        'date' => date('Y-m-d H:i:s', filemtime($backupPath . '/' . $file))
                    ];
                }
            }
        }
        
        return $this->sendSuccess('Backups retrieved', array_reverse($backups));
    }

    /**
     * Restore from backup.
     */
    public function restoreBackup(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|string'
        ]);
        
        try {
            // Run restore command
            Artisan::call('backup:restore', ['--file' => $request->backup_file]);
            
            return $this->sendSuccess('Backup restored successfully');
            
        } catch (\Exception $e) {
            return $this->sendError('Failed to restore backup', $e->getMessage(), 500);
        }
    }

    /**
     * Delete backup.
     */
    public function deleteBackup(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|string'
        ]);
        
        $filePath = storage_path('app/backups/' . $request->backup_file);
        
        if (file_exists($filePath)) {
            unlink($filePath);
            return $this->sendSuccess('Backup deleted successfully');
        }
        
        return $this->sendError('Backup file not found');
    }

    /**
     * Toggle maintenance mode.
     */
    public function toggleMaintenance(Request $request)
    {
        $secret = $request->get('secret', '');
        
        if (app()->isDownForMaintenance()) {
            Artisan::call('up');
            $message = 'Application is now live';
        } else {
            Artisan::call('down', ['--secret' => $secret]);
            $message = 'Application is now in maintenance mode';
        }
        
        return redirect()->back()->with('success', $message);
    }

    /**
     * Clear system cache.
     */
    public function clearCache()
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        
        return redirect()->back()->with('success', 'System cache cleared successfully');
    }
}