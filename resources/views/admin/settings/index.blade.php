@extends('layouts.admin')

@section('title', 'System Settings')
@section('pageTitle', 'System Settings')
@section('pageSubtitle', 'Configure system parameters and preferences')

@php $activeMenu = 'settings'; @endphp

@section('content')
<!-- Settings Navigation -->
<div class="settings-nav">
    <button class="settings-tab active" onclick="switchTab('general')">
        <svg viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
        </svg>
        General
    </button>
    <button class="settings-tab" onclick="switchTab('windows')">
        <svg viewBox="0 0 20 20" fill="currentColor">
            <path d="M7 8a3 3 0 100-6 3 3 0 000 6zM14.5 9a2.5 2.5 0 100-5 2.5 2.5 0 000 5zM1.615 16.428a1.224 1.224 0 01-.569-1.175 6.002 6.002 0 0111.908 0c.058.467-.172.92-.57 1.174A9.953 9.953 0 017 18a9.953 9.953 0 01-5.385-1.572zM14.5 16h-.106c.07-.297.088-.611.048-.933a7.47 7.47 0 00-1.588-3.755 4.502 4.502 0 015.874 2.636.818.818 0 01-.36.98A7.963 7.963 0 0114.5 16z" />
        </svg>
        Windows
    </button>
    <button class="settings-tab" onclick="switchTab('notifications')">
        <svg viewBox="0 0 20 20" fill="currentColor">
            <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
        </svg>
        Notifications
    </button>
    <button class="settings-tab" onclick="switchTab('queue')">
        <svg viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
        </svg>
        Queue Rules
    </button>
    <button class="settings-tab" onclick="switchTab('backup')">
        <svg viewBox="0 0 20 20" fill="currentColor">
            <path d="M5.5 3A1.5 1.5 0 004 4.5v4A1.5 1.5 0 005.5 10h4A1.5 1.5 0 0011 8.5v-4A1.5 1.5 0 009.5 3h-4zM13.5 3A1.5 1.5 0 0012 4.5v4a1.5 1.5 0 001.5 1.5h4A1.5 1.5 0 0019 8.5v-4A1.5 1.5 0 0017.5 3h-4zM5.5 11A1.5 1.5 0 004 12.5v4A1.5 1.5 0 005.5 18h4a1.5 1.5 0 001.5-1.5v-4A1.5 1.5 0 009.5 11h-4zM16 15a1 1 0 100-2 1 1 0 000 2z" />
        </svg>
        Backup
    </button>
    <button class="settings-tab" onclick="switchTab('api')">
        <svg viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L3.414 10l2.293 2.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414L16.586 10l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
        API
    </button>
</div>

<!-- Settings Panels -->
<div class="settings-panels">
    <!-- General Settings -->
    <div class="settings-panel active" id="general">
        <form method="POST" action="{{ route('admin.settings.update-general') }}">
            @csrf
            <h2>General Configuration</h2>
            
            <div class="settings-group">
                <h3>System Information</h3>
                <div class="settings-grid">
                    <div class="settings-field">
                        <label>System Name</label>
                        <input type="text" name="system_name" value="PSA PhilSys Queue Management System">
                    </div>
                    <div class="settings-field">
                        <label>System Version</label>
                        <input type="text" value="v2.1.0" readonly disabled>
                    </div>
                    <div class="settings-field">
                        <label>Environment</label>
                        <select name="environment">
                            <option value="production">Production</option>
                            <option value="staging">Staging</option>
                            <option value="development">Development</option>
                        </select>
                    </div>
                    <div class="settings-field">
                        <label>Debug Mode</label>
                        <select name="debug">
                            <option value="false">Off</option>
                            <option value="true">On</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="settings-group">
                <h3>Date & Time</h3>
                <div class="settings-grid">
                    <div class="settings-field">
                        <label>Time Zone</label>
                        <select name="timezone">
                            <option value="Asia/Manila" selected>Asia/Manila (GMT+8)</option>
                            <option value="Asia/Singapore">Asia/Singapore</option>
                            <option value="UTC">UTC</option>
                        </select>
                    </div>
                    <div class="settings-field">
                        <label>Date Format</label>
                        <select name="date_format">
                            <option value="Y-m-d">YYYY-MM-DD</option>
                            <option value="m/d/Y">MM/DD/YYYY</option>
                            <option value="d/m/Y">DD/MM/YYYY</option>
                        </select>
                    </div>
                    <div class="settings-field">
                        <label>Time Format</label>
                        <select name="time_format">
                            <option value="12">12-hour (AM/PM)</option>
                            <option value="24">24-hour</option>
                        </select>
                    </div>
                    <div class="settings-field">
                        <label>Week Starts On</label>
                        <select name="week_start">
                            <option value="monday">Monday</option>
                            <option value="sunday">Sunday</option>
                            <option value="saturday">Saturday</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="settings-group">
                <h3>Business Hours</h3>
                @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                <div class="business-hour-row">
                    <span class="day">{{ $day }}</span>
                    <select name="hours[{{ strtolower($day) }}][open]">
                        <option value="08:00" {{ $day != 'Sunday' ? 'selected' : '' }}>8:00 AM</option>
                        <option value="09:00">9:00 AM</option>
                        <option value="10:00">10:00 AM</option>
                    </select>
                    <span>to</span>
                    <select name="hours[{{ strtolower($day) }}][close]">
                        <option value="17:00" {{ $day != 'Sunday' ? 'selected' : '' }}>5:00 PM</option>
                        <option value="18:00">6:00 PM</option>
                        <option value="19:00">7:00 PM</option>
                    </select>
                    @if($day == 'Sunday')
                    <span class="closed-badge">Closed</span>
                    @endif
                </div>
                @endforeach
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
    
    <!-- Windows Settings -->
    <div class="settings-panel" id="windows">
        <h2>Window Configuration</h2>
        <div class="windows-grid">
            @for($i = 1; $i <= 12; $i++)
            <div class="window-card">
                <div class="window-header">
                    <h3>Window #{{ $i }}</h3>
                    <label class="switch">
                        <input type="checkbox" {{ $i <= 8 ? 'checked' : '' }}>
                        <span class="slider"></span>
                    </label>
                </div>
                <div class="window-body">
                    <div class="window-field">
                        <label>Category</label>
                        <select>
                            <option value="all">All Categories</option>
                            <option value="registration">Registration Only</option>
                            <option value="inquiry">Inquiry Only</option>
                            <option value="updating">Updating Only</option>
                        </select>
                    </div>
                    <div class="window-field">
                        <label>Priority</label>
                        <select>
                            <option value="normal">Normal</option>
                            <option value="priority">Priority</option>
                            <option value="express">Express</option>
                        </select>
                    </div>
                </div>
            </div>
            @endfor
        </div>
        <div class="form-actions">
            <button class="btn btn-primary">Save Window Configuration</button>
        </div>
    </div>
    
    <!-- Notifications Settings -->
    <div class="settings-panel" id="notifications">
        <h2>Notification Settings</h2>
        
        <div class="settings-group">
            <h3>Email Notifications</h3>
            <div class="notification-option">
                <div>
                    <h4>System Alerts</h4>
                    <p>Receive alerts for system events and errors</p>
                </div>
                <label class="switch">
                    <input type="checkbox" checked>
                    <span class="slider"></span>
                </label>
            </div>
            <div class="notification-option">
                <div>
                    <h4>Daily Summary</h4>
                    <p>Receive daily summary of system activity</p>
                </div>
                <label class="switch">
                    <input type="checkbox" checked>
                    <span class="slider"></span>
                </label>
            </div>
            <div class="notification-option">
                <div>
                    <h4>User Reports</h4>
                    <p>Weekly user activity reports</p>
                </div>
                <label class="switch">
                    <input type="checkbox">
                    <span class="slider"></span>
                </label>
            </div>
        </div>
        
        <div class="settings-group">
            <h3>Email Configuration</h3>
            <div class="settings-grid">
                <div class="settings-field">
                    <label>SMTP Host</label>
                    <input type="text" value="smtp.gmail.com">
                </div>
                <div class="settings-field">
                    <label>SMTP Port</label>
                    <input type="text" value="587">
                </div>
                <div class="settings-field">
                    <label>Username</label>
                    <input type="text" value="notifications@psa.gov.ph">
                </div>
                <div class="settings-field">
                    <label>Password</label>
                    <input type="password" value="********">
                </div>
                <div class="settings-field">
                    <label>Encryption</label>
                    <select>
                        <option value="tls">TLS</option>
                        <option value="ssl">SSL</option>
                        <option value="none">None</option>
                    </select>
                </div>
                <div class="settings-field">
                    <label>From Email</label>
                    <input type="email" value="system@psa.gov.ph">
                </div>
            </div>
        </div>
        
        <div class="form-actions">
            <button class="btn btn-primary">Save Notification Settings</button>
        </div>
    </div>
    
    <!-- Queue Rules Settings -->
    <div class="settings-panel" id="queue">
        <h2>Queue Rules</h2>
        
        <div class="settings-group">
            <h3>Queue Configuration</h3>
            <div class="settings-grid">
                <div class="settings-field">
                    <label>Maximum Queue Length</label>
                    <input type="number" value="50" min="1" max="999">
                </div>
                <div class="settings-field">
                    <label>Estimated Wait Time</label>
                    <select>
                        <option value="auto">Auto-calculate</option>
                        <option value="fixed">Fixed per service</option>
                    </select>
                </div>
                <div class="settings-field">
                    <label>Queue Prefix</label>
                    <input type="text" value="Q">
                </div>
                <div class="settings-field">
                    <label>Queue Suffix</label>
                    <input type="text" value="">
                </div>
            </div>
        </div>
        
        <div class="settings-group">
            <h3>Service Priorities</h3>
            @foreach(['NID Registration', 'Status Inquiry', 'Updating'] as $service)
            <div class="priority-row">
                <span>{{ $service }}</span>
                <select>
                    <option value="1" {{ $loop->first ? 'selected' : '' }}>High Priority</option>
                    <option value="2" {{ $loop->index == 1 ? 'selected' : '' }}>Normal Priority</option>
                    <option value="3" {{ $loop->last ? 'selected' : '' }}>Low Priority</option>
                </select>
                <input type="number" placeholder="Est. minutes" value="{{ rand(5, 15) }}">
            </div>
            @endforeach
        </div>
        
        <div class="form-actions">
            <button class="btn btn-primary">Save Queue Rules</button>
        </div>
    </div>
    
    <!-- Backup Settings -->
    <div class="settings-panel" id="backup">
        <h2>Backup & Restore</h2>
        
        <div class="backup-actions">
            <button class="btn btn-primary" onclick="createBackup()">
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
                Create Backup Now
            </button>
            <button class="btn btn-outline" onclick="scheduleBackup()">
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                </svg>
                Schedule Backup
            </button>
        </div>
        
        <div class="settings-group">
            <h3>Backup Schedule</h3>
            <div class="settings-grid">
                <div class="settings-field">
                    <label>Frequency</label>
                    <select>
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                    </select>
                </div>
                <div class="settings-field">
                    <label>Time</label>
                    <input type="time" value="02:00">
                </div>
                <div class="settings-field">
                    <label>Retention</label>
                    <select>
                        <option value="7">Keep 7 days</option>
                        <option value="30">Keep 30 days</option>
                        <option value="90">Keep 90 days</option>
                    </select>
                </div>
                <div class="settings-field">
                    <label>Backup Location</label>
                    <input type="text" value="/backups/psa-philsys">
                </div>
            </div>
        </div>
        
        <div class="settings-group">
            <h3>Recent Backups</h3>
            <table class="backup-table">
                <thead>
                    <tr>
                        <th>Backup File</th>
                        <th>Size</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(range(1,5) as $i)
                    <tr>
                        <td>backup_{{ now()->subDays($i)->format('Y-m-d') }}.sql</td>
                        <td>{{ rand(50, 200) }} MB</td>
                        <td>{{ now()->subDays($i)->format('M d, Y H:i') }}</td>
                        <td>
                            <button class="action-btn" title="Download"><i class="fas fa-download"></i></button>
                            <button class="action-btn" title="Restore"><i class="fas fa-undo"></i></button>
                            <button class="action-btn delete" title="Delete"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- API Settings -->
    <div class="settings-panel" id="api">
        <h2>API Configuration</h2>
        
        <div class="settings-group">
            <h3>API Keys</h3>
            <div class="api-key-row">
                <div>
                    <strong>Production API Key</strong>
                    <code>pk_live_xxxxxxxxxxxxxxxxxxxxxxxx</code>
                </div>
                <button class="btn btn-outline btn-sm">Regenerate</button>
            </div>
            <div class="api-key-row">
                <div>
                    <strong>Test API Key</strong>
                    <code>pk_test_xxxxxxxxxxxxxxxxxxxxxxxx</code>
                </div>
                <button class="btn btn-outline btn-sm">Regenerate</button>
            </div>
        </div>
        
        <div class="settings-group">
            <h3>API Settings</h3>
            <div class="settings-grid">
                <div class="settings-field">
                    <label>Rate Limit (per minute)</label>
                    <input type="number" value="60">
                </div>
                <div class="settings-field">
                    <label>API Version</label>
                    <input type="text" value="v1">
                </div>
                <div class="settings-field">
                    <label>Request Timeout</label>
                    <input type="number" value="30"> seconds
                </div>
                <div class="settings-field">
                    <label>Enable API</label>
                    <select>
                        <option value="true">Enabled</option>
                        <option value="false">Disabled</option>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="form-actions">
            <button class="btn btn-primary">Save API Settings</button>
        </div>
    </div>
</div>

@push('styles')
<style>
    .settings-nav {
        display: flex;
        gap: 10px;
        margin-bottom: 25px;
        flex-wrap: wrap;
        border-bottom: 2px solid var(--gray-200);
        padding-bottom: 15px;
    }
    
    .settings-tab {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        border: none;
        background: none;
        border-radius: 30px;
        color: var(--gray-600);
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .settings-tab:hover {
        background: var(--psa-blue-light);
        color: var(--psa-blue);
    }
    
    .settings-tab.active {
        background: var(--psa-blue);
        color: white;
    }
    
    .settings-tab svg {
        width: 18px;
        height: 18px;
    }
    
    .settings-panels {
        background: white;
        border-radius: 20px;
        padding: 24px;
        border: 2px solid var(--gray-200);
    }
    
    .settings-panel {
        display: none;
    }
    
    .settings-panel.active {
        display: block;
    }
    
    .settings-panel h2 {
        font-size: 1.3rem;
        font-weight: 600;
        color: var(--psa-blue);
        margin-bottom: 20px;
    }
    
    .settings-group {
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid var(--gray-200);
    }
    
    .settings-group h3 {
        font-size: 1rem;
        font-weight: 600;
        color: var(--gray-700);
        margin-bottom: 15px;
    }
    
    .settings-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
    }
    
    .settings-field {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    
    .settings-field label {
        font-size: 0.9rem;
        font-weight: 500;
        color: var(--gray-600);
    }
    
    .settings-field input,
    .settings-field select {
        padding: 8px 12px;
        border: 2px solid var(--gray-200);
        border-radius: 8px;
        font-size: 0.9rem;
    }
    
    .settings-field input:focus,
    .settings-field select:focus {
        outline: none;
        border-color: var(--psa-blue);
        box-shadow: 0 0 0 4px var(--psa-blue-light);
    }
    
    .business-hour-row {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 10px 0;
        border-bottom: 1px solid var(--gray-100);
    }
    
    .business-hour-row .day {
        width: 100px;
        font-weight: 500;
    }
    
    .business-hour-row select {
        padding: 6px 10px;
        border: 2px solid var(--gray-200);
        border-radius: 6px;
    }
    
    .closed-badge {
        background: var(--gray-200);
        color: var(--gray-600);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
    }
    
    .windows-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }
    
    .window-card {
        border: 2px solid var(--gray-200);
        border-radius: 16px;
        overflow: hidden;
    }
    
    .window-header {
        background: linear-gradient(135deg, var(--psa-blue), var(--psa-red));
        padding: 15px;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .window-header h3 {
        font-size: 1rem;
        font-weight: 600;
    }
    
    .window-body {
        padding: 15px;
    }
    
    .window-field {
        margin-bottom: 10px;
    }
    
    .window-field label {
        display: block;
        font-size: 0.8rem;
        color: var(--gray-500);
        margin-bottom: 4px;
    }
    
    .window-field select {
        width: 100%;
        padding: 6px 10px;
        border: 2px solid var(--gray-200);
        border-radius: 6px;
    }
    
    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
    }
    
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(255,255,255,0.3);
        transition: .2s;
        border-radius: 24px;
    }
    
    .slider:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 2px;
        bottom: 2px;
        background-color: white;
        transition: .2s;
        border-radius: 50%;
    }
    
    input:checked + .slider {
        background-color: var(--psa-yellow);
    }
    
    input:checked + .slider:before {
        transform: translateX(26px);
    }
    
    .notification-option {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px;
        background: var(--gray-50);
        border-radius: 12px;
        margin-bottom: 10px;
    }
    
    .notification-option h4 {
        font-size: 1rem;
        font-weight: 600;
        color: var(--gray-800);
        margin-bottom: 4px;
    }
    
    .notification-option p {
        font-size: 0.85rem;
        color: var(--gray-500);
    }
    
    .priority-row {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 10px;
        background: var(--gray-50);
        border-radius: 8px;
        margin-bottom: 8px;
    }
    
    .priority-row span {
        width: 150px;
        font-weight: 500;
    }
    
    .priority-row select,
    .priority-row input {
        padding: 6px 10px;
        border: 2px solid var(--gray-200);
        border-radius: 6px;
    }
    
    .backup-actions {
        display: flex;
        gap: 15px;
        margin-bottom: 25px;
    }
    
    .backup-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .backup-table th {
        text-align: left;
        padding: 12px;
        background: var(--gray-50);
        color: var(--gray-600);
        font-weight: 600;
        font-size: 0.85rem;
    }
    
    .backup-table td {
        padding: 12px;
        border-bottom: 1px solid var(--gray-200);
    }
    
    .api-key-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px;
        background: var(--gray-50);
        border-radius: 12px;
        margin-bottom: 10px;
    }
    
    .api-key-row code {
        display: block;
        margin-top: 5px;
        padding: 8px;
        background: var(--gray-800);
        color: var(--gray-200);
        border-radius: 6px;
        font-size: 0.85rem;
    }
</style>
@endpush

@push('scripts')
<script>
    function switchTab(tabId) {
        // Remove active class from all tabs
        document.querySelectorAll('.settings-tab').forEach(tab => {
            tab.classList.remove('active');
        });
        
        // Hide all panels
        document.querySelectorAll('.settings-panel').forEach(panel => {
            panel.classList.remove('active');
        });
        
        // Add active class to clicked tab
        event.currentTarget.classList.add('active');
        
        // Show selected panel
        document.getElementById(tabId).classList.add('active');
    }
    
    function createBackup() {
        showLoading();
        setTimeout(() => {
            hideLoading();
            alert('Backup created successfully!');
        }, 2000);
    }
    
    function scheduleBackup() {
        alert('Backup scheduling interface would open here');
    }
</script>
@endpush
@endsection