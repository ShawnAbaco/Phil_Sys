@extends('layouts.admin')

@section('title', 'System Settings')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-800">System Settings</h1>
        <button class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
            Save Changes
        </button>
    </div>

    <!-- Settings Tabs -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="border-b border-gray-200">
            <nav class="flex space-x-8 px-6" aria-label="Tabs">
                <button class="settings-tab active py-4 px-1 border-b-2 border-blue-600 text-blue-600 font-medium text-sm">
                    General
                </button>
                <button class="settings-tab py-4 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium text-sm">
                    Windows
                </button>
                <button class="settings-tab py-4 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium text-sm">
                    Notifications
                </button>
                <button class="settings-tab py-4 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium text-sm">
                    Queue Rules
                </button>
                <button class="settings-tab py-4 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium text-sm">
                    Backup
                </button>
                <button class="settings-tab py-4 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium text-sm">
                    API
                </button>
            </nav>
        </div>

        <!-- General Settings Tab -->
        <div class="settings-panel p-6" id="general">
            <div class="space-y-6">
                <h2 class="text-lg font-semibold text-gray-800">General Configuration</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">System Name</label>
                        <input type="text" value="QueueMaster Pro" class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Time Zone</label>
                        <select class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option>Eastern Time (US & Canada)</option>
                            <option>Central Time (US & Canada)</option>
                            <option>Mountain Time (US & Canada)</option>
                            <option>Pacific Time (US & Canada)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date Format</label>
                        <select class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option>MM/DD/YYYY</option>
                            <option>DD/MM/YYYY</option>
                            <option>YYYY-MM-DD</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Time Format</label>
                        <select class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option>12-hour (AM/PM)</option>
                            <option>24-hour</option>
                        </select>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-md font-medium text-gray-800 mb-4">Business Hours</h3>
                    <div class="space-y-4">
                        @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                        <div class="flex items-center space-x-4">
                            <span class="w-24 text-sm text-gray-600">{{ $day }}</span>
                            <select class="border border-gray-200 rounded-lg px-3 py-1 text-sm">
                                <option>9:00 AM</option>
                                <option>8:00 AM</option>
                                <option>10:00 AM</option>
                            </select>
                            <span class="text-gray-500">to</span>
                            <select class="border border-gray-200 rounded-lg px-3 py-1 text-sm">
                                <option>5:00 PM</option>
                                <option>6:00 PM</option>
                                <option>7:00 PM</option>
                            </select>
                            @if($day == 'Sunday')
                            <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full">Closed</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Windows Configuration Tab -->
        <div class="settings-panel p-6 hidden" id="windows">
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-800">Window Configuration</h2>
                    <button class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                        <i class="fas fa-plus mr-1"></i>Add Window
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach(range(1,8) as $window)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="font-medium text-gray-800">Window #{{ $window }}</h3>
                            <div class="flex space-x-2">
                                <button class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-gray-400 hover:text-red-600">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div>
                                <label class="text-xs text-gray-500">Category</label>
                                <select class="w-full border border-gray-200 rounded-lg px-3 py-1 text-sm mt-1">
                                    <option>All Categories</option>
                                    <option>Regular Only</option>
                                    <option>Priority Only</option>
                                    <option>VIP Only</option>
                                </select>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-500">Status</span>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer" {{ $window <= 6 ? 'checked' : '' }}>
                                    <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Notifications Tab -->
        <div class="settings-panel p-6 hidden" id="notifications">
            <div class="space-y-6">
                <h2 class="text-lg font-semibold text-gray-800">Notification Settings</h2>

                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <h3 class="font-medium text-gray-800">Email Notifications</h3>
                            <p class="text-sm text-gray-500">Send email alerts for system events</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <h3 class="font-medium text-gray-800">SMS Alerts</h3>
                            <p class="text-sm text-gray-500">Send SMS for urgent notifications</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <h3 class="font-medium text-gray-800">Display Notifications</h3>
                            <p class="text-sm text-gray-500">Show notifications on dashboard</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-md font-medium text-gray-800 mb-4">Email Configuration</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Host</label>
                            <input type="text" value="smtp.gmail.com" class="w-full border border-gray-200 rounded-lg px-4 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Port</label>
                            <input type="text" value="587" class="w-full border border-gray-200 rounded-lg px-4 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                            <input type="text" value="notifications@queuemaster.com" class="w-full border border-gray-200 rounded-lg px-4 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                            <input type="password" value="********" class="w-full border border-gray-200 rounded-lg px-4 py-2">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Backup Tab -->
        <div class="settings-panel p-6 hidden" id="backup">
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-800">Backup & Restore</h2>
                    <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        <i class="fas fa-database mr-2"></i>Create Backup Now
                    </button>
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex">
                        <i class="fas fa-exclamation-triangle text-yellow-600 mt-1 mr-3"></i>
                        <div>
                            <h3 class="text-sm font-medium text-yellow-800">Important</h3>
                            <p class="text-sm text-yellow-700">Regular backups are crucial. We recommend automatic daily backups.</p>
                        </div>
                    </div>
                </div>

                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Backup File</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Size</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach(range(1,5) as $backup)
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-900">backup_{{ now()->subDays($backup)->format('Y-m-d') }}.sql</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ rand(50, 200) }} MB</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ now()->subDays($backup)->format('M d, Y H:i') }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <button class="text-blue-600 hover:text-blue-800 mr-3">
                                        <i class="fas fa-download"></i>
                                    </button>
                                    <button class="text-green-600 hover:text-green-800 mr-3">
                                        <i class="fas fa-undo"></i>
                                    </button>
                                    <button class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-md font-medium text-gray-800 mb-4">Automatic Backup Schedule</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Frequency</label>
                            <select class="w-full border border-gray-200 rounded-lg px-4 py-2">
                                <option>Daily</option>
                                <option>Weekly</option>
                                <option>Monthly</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Time</label>
                            <input type="time" value="02:00" class="w-full border border-gray-200 rounded-lg px-4 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Retention</label>
                            <select class="w-full border border-gray-200 rounded-lg px-4 py-2">
                                <option>Keep 7 days</option>
                                <option>Keep 30 days</option>
                                <option>Keep 90 days</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Tab switching functionality
    document.querySelectorAll('.settings-tab').forEach((tab, index) => {
        tab.addEventListener('click', () => {
            // Remove active class from all tabs
            document.querySelectorAll('.settings-tab').forEach(t => {
                t.classList.remove('active', 'border-blue-600', 'text-blue-600');
                t.classList.add('border-transparent', 'text-gray-500');
            });

            // Add active class to clicked tab
            tab.classList.add('active', 'border-blue-600', 'text-blue-600');
            tab.classList.remove('border-transparent', 'text-gray-500');

            // Hide all panels
            document.querySelectorAll('.settings-panel').forEach(panel => {
                panel.classList.add('hidden');
            });

            // Show corresponding panel
            const panelId = ['general', 'windows', 'notifications', 'queue', 'backup', 'api'][index];
            document.getElementById(panelId)?.classList.remove('hidden');
        });
    });
</script>
@endsection
