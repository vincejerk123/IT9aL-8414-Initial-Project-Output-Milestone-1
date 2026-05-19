@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-red-700 to-red-800 rounded-xl shadow-sm p-6 text-white">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold">Welcome back, {{ auth()->user()->name }}!</h1>
                <p class="text-red-100 mt-1">Manage your Sweetwen recruitment portal</p>
            </div>
            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Jobs</p>
                    <p class="text-2xl font-bold text-gray-800">{{ \App\Models\Job::count() }}</p>
                </div>
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex gap-2 text-xs">
                <span class="text-green-600">Open: {{ \App\Models\Job::where('status', 'open')->count() }}</span>
                <span class="text-red-600">Closed: {{ \App\Models\Job::where('status', 'closed')->count() }}</span>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Applications</p>
                    <p class="text-2xl font-bold text-gray-800">{{ \App\Models\Application::whereNotNull('status')->count() }}</p>
                </div>
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex gap-2 text-xs flex-wrap">
                <span class="text-yellow-600">Pending: {{ \App\Models\Application::where('status', 'pending')->count() }}</span>
                <span class="text-green-600">Approved: {{ \App\Models\Application::where('status', 'approved')->count() }}</span>
                <span class="text-red-600">Rejected: {{ \App\Models\Application::where('status', 'rejected')->count() }}</span>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Users</p>
                    <p class="text-2xl font-bold text-gray-800">{{ \App\Models\User::count() }}</p>
                </div>
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-3 text-xs">
                <span class="text-purple-600">Admins: {{ \App\Models\User::where('role', 'admin')->count() }}</span>
                <span class="text-gray-600 ml-2">Users: {{ \App\Models\User::where('role', 'user')->count() }}</span>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Hiring Rate</p>
                    @php $total = \App\Models\Application::whereNotNull('status')->count(); $approved = \App\Models\Application::where('status', 'approved')->count(); $rate = $total > 0 ? round(($approved / $total) * 100) : 0; @endphp
                    <p class="text-2xl font-bold text-gray-800">{{ $rate }}%</p>
                </div>
                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="w-full bg-gray-200 rounded-full h-1.5">
                    <div class="bg-green-600 h-1.5 rounded-full" style="width: {{ $rate }}%"></div>
                </div>
                <p class="text-xs text-gray-500 mt-1">{{ $approved }} of {{ $total }} approved</p>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <a href="{{ route('jobs.create') }}" class="bg-red-700 hover:bg-red-800 rounded-xl p-5 text-white text-center transition">
            <div class="flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span class="font-medium">Create New Job</span>
            </div>
        </a>
        <a href="{{ route('admin.applications') }}" class="bg-blue-700 hover:bg-blue-800 rounded-xl p-5 text-white text-center transition">
            <div class="flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <span class="font-medium">Review All Applications</span>
            </div>
        </a>
        <a href="{{ route('jobs.index') }}" class="bg-green-700 hover:bg-green-800 rounded-xl p-5 text-white text-center transition">
            <div class="flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                <span class="font-medium">Manage Jobs</span>
            </div>
        </a>
    </div>

    <!-- Recent Jobs Table with Buttons -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Recent Job Listings</h2>
                <p class="text-sm text-gray-500 mt-0.5">Monitor job postings and available slots</p>
            </div>
            <a href="{{ route('jobs.index') }}" class="text-red-600 hover:text-red-700 text-sm font-semibold">View All →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Salary</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slots Available</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(\App\Models\Job::latest()->take(5)->get() as $job)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $job->title }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $job->location }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">₱{{ number_format($job->salary, 2) }}</td>
                        <td class="px-4 py-3">
                            @if($job->slots > 0)
                                @php $available = $job->slots - $job->approved_count; @endphp
                                @if($available > 0)
                                    <div class="flex items-center gap-1">
                                        <span class="text-green-600 font-semibold">{{ $available }}</span>
                                        <span class="text-gray-400">/ {{ $job->slots }}</span>
                                        <span class="text-xs text-green-600 bg-green-50 px-1.5 py-0.5 rounded-full">Available</span>
                                    </div>
                                @else
                                    <div class="flex items-center gap-1">
                                        <span class="text-red-600 font-semibold">0</span>
                                        <span class="text-gray-400">/ {{ $job->slots }}</span>
                                        <span class="text-xs text-red-600 bg-red-50 px-1.5 py-0.5 rounded-full">Full</span>
                                    </div>
                                @endif
                            @else
                                <div class="flex items-center gap-1">
                                    <span class="text-blue-600 font-semibold">∞</span>
                                    <span class="text-gray-400">Unlimited</span>
                                    <span class="text-xs text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded-full">Open</span>
                                </div>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($job->slots > 0 && $job->approved_count >= $job->slots)
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">Full / Closed</span>
                            @elseif($job->status == 'open')
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Open</span>
                            @else
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">Closed</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('jobs.edit', $job) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white text-xs font-medium rounded-lg transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit
                                </a>
                                <a href="{{ route('admin.applications', ['job_id' => $job->id]) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white text-xs font-medium rounded-lg transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    View Applications
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Applications -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Recent Applications</h2>
                <p class="text-sm text-gray-500 mt-0.5">Latest applicants and their status</p>
            </div>
            <a href="{{ route('admin.applications') }}" class="text-red-600 hover:text-red-700 text-sm font-semibold">View All →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applicant</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applied</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(\App\Models\Application::with('job')->whereNotNull('status')->latest()->take(5)->get() as $app)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $app->full_name }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $app->job->title }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $app->job->location }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $app->created_at->diffForHumans() }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                @if($app->status == 'pending') bg-yellow-100 text-yellow-700
                                @elseif($app->status == 'approved') bg-green-100 text-green-700
                                @else bg-red-100 text-red-700
                                @endif">
                                {{ ucfirst($app->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.applications') }}" class="text-blue-600 hover:text-blue-800 text-sm">Review</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection