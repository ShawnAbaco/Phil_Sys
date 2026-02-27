@extends('layouts.admin')

@section('title', 'Manage Operators')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-800">Manage Operators</h1>
        <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center space-x-2">
            <i class="fas fa-plus"></i>
            <span>Add New Operator</span>
        </button>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input type="text" placeholder="Search by name..." class="border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <select class="border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option>All Windows</option>
                @foreach(range(1,12) as $window)
                <option>Window #{{ $window }}</option>
                @endforeach
            </select>
            <select class="border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option>All Status</option>
                <option>Active</option>
                <option>Inactive</option>
                <option>On Break</option>
            </select>
            <button class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors">
                Apply Filters
            </button>
        </div>
    </div>

    <!-- Operators Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Operator</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Window</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Today's Served</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avg. Service Time</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach(range(1,8) as $index)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold">
                                OP
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Operator {{ $index }}</p>
                                <p class="text-xs text-gray-500">operator{{ $index }}@example.com</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Window #{{ $index }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Active</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ rand(25, 45) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ rand(3, 7) }} min</td>
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

    <!-- Pagination -->
    <div class="flex items-center justify-between">
        <p class="text-sm text-gray-600">Showing 1 to 8 of 24 operators</p>
        <div class="flex space-x-2">
            <button class="px-3 py-1 border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50">Previous</button>
            <button class="px-3 py-1 bg-blue-600 text-white rounded-lg">1</button>
            <button class="px-3 py-1 border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50">2</button>
            <button class="px-3 py-1 border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50">3</button>
            <button class="px-3 py-1 border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50">Next</button>
        </div>
    </div>
</div>
@endsection
