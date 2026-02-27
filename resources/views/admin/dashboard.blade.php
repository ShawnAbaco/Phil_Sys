@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
        <div class="text-sm text-gray-600">
            <span>{{ now()->format('l, F j, Y') }}</span>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Queue Today -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Queue Today</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">156</p>
                    <p class="text-sm text-green-600 mt-2">
                        <i class="fas fa-arrow-up mr-1"></i>12% from yesterday
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-2xl text-blue-600"></i>
                </div>
            </div>
        </div>

        <!-- Active Windows -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Active Windows</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">8/12</p>
                    <p class="text-sm text-orange-600 mt-2">
                        <i class="fas fa-clock mr-1"></i>4 windows idle
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-desktop text-2xl text-green-600"></i>
                </div>
            </div>
        </div>

        <!-- Average Wait Time -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Avg. Wait Time</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">12 min</p>
                    <p class="text-sm text-green-600 mt-2">
                        <i class="fas fa-arrow-down mr-1"></i>3 min less
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-hourglass-half text-2xl text-purple-600"></i>
                </div>
            </div>
        </div>

        <!-- Service Rate -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Service Rate</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">94%</p>
                    <p class="text-sm text-green-600 mt-2">
                        <i class="fas fa-arrow-up mr-1"></i>5% increase
                    </p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-line text-2xl text-yellow-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Queue Flow Chart -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-800">Queue Flow</h2>
                <select class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option>Today</option>
                    <option>This Week</option>
                    <option>This Month</option>
                </select>
            </div>
            <canvas id="queueFlowChart" height="250"></canvas>
        </div>

        <!-- Window Performance -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-800">Window Performance</h2>
                <a href="#" class="text-sm text-blue-600 hover:text-blue-700">View Details →</a>
            </div>
            <div class="space-y-4">
                @foreach(range(1,5) as $window)
                <div>
                    <div class="flex items-center justify-between text-sm mb-1">
                        <span class="font-medium text-gray-700">Window #{{ $window }}</span>
                        <span class="text-gray-600">{{ rand(20, 45) }} served</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ rand(60, 95) }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Current Queue Status -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-800">Current Queue Status</h2>
            <div class="flex items-center space-x-3">
                <span class="text-sm text-gray-500">
                    <i class="fas fa-sync-alt mr-1"></i>Auto-refresh in <span id="refreshTimer">10</span>s
                </span>
                <button class="text-blue-600 hover:text-blue-700 text-sm font-medium">Refresh Now</button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Queue #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Window</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Wait Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach(range(1,5) as $index)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ sprintf('%03d', $index) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Regular</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">John Doe</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Window {{ rand(1,4) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Waiting</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ rand(2,8) }} min</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <button class="text-blue-600 hover:text-blue-800 mr-3">
                                <i class="fas fa-edit"></i>
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
        <div class="px-6 py-4 border-t border-gray-100">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-600">Showing 5 of 23 active queues</p>
                <a href="#" class="text-sm text-blue-600 hover:text-blue-700 font-medium">View All →</a>
            </div>
        </div>
    </div>

    <!-- Operator Status -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Active Operators -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-800">Active Operators</h2>
            </div>
            <div class="p-6 space-y-4">
                @foreach(range(1,4) as $index)
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-r from-green-400 to-green-500 rounded-full flex items-center justify-center text-white font-semibold">
                            OP
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Operator {{ $index }}</p>
                            <p class="text-xs text-gray-500">Window #{{ $index }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-600">{{ rand(15, 30) }} served</span>
                        <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Appointments -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-800">Recent Appointments</h2>
            </div>
            <div class="p-6 space-y-4">
                @foreach(range(1,4) as $index)
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">Appointment #{{ sprintf('%03d', $index) }}</p>
                        <p class="text-xs text-gray-500">{{ now()->subHours($index)->format('h:i A') }}</p>
                    </div>
                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Completed</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Queue Flow Chart
    const ctx = document.getElementById('queueFlowChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['9 AM', '10 AM', '11 AM', '12 PM', '1 PM', '2 PM', '3 PM', '4 PM'],
            datasets: [{
                label: 'Queue Length',
                data: [12, 19, 15, 17, 14, 13, 10, 8],
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        display: true,
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Auto-refresh timer
    let timer = 10;
    const timerElement = document.getElementById('refreshTimer');
    setInterval(() => {
        timer = timer > 0 ? timer - 1 : 10;
        timerElement.textContent = timer;
    }, 1000);
</script>
@endpush
