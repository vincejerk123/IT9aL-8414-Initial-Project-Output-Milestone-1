@extends('layouts.app')

@section('content')
<div class="bg-white rounded-xl shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manage Job Listings - Sweetwen</h1>
            <p class="text-gray-500">Create, edit, or close job positions</p>
        </div>
        <a href="{{ route('jobs.create') }}" class="inline-block bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-bold px-4 py-2 rounded-lg transition shadow-md">
            + Add New Job
        </a>
    </div>

    <!-- Filter Buttons -->
    <div class="flex flex-wrap gap-2 mb-4">
        <button onclick="filterJobTable('all')" class="job-filter-btn px-4 py-2 rounded-lg text-sm font-semibold transition bg-gray-600 text-white" data-job-filter="all">
            All Jobs
        </button>
        <button onclick="filterJobTable('open')" class="job-filter-btn px-4 py-2 rounded-lg text-sm font-semibold transition bg-green-500 text-white hover:bg-green-600" data-job-filter="open">
            Open Positions
        </button>
        <button onclick="filterJobTable('closed')" class="job-filter-btn px-4 py-2 rounded-lg text-sm font-semibold transition bg-red-500 text-white hover:bg-red-600" data-job-filter="closed">
            Closed Positions
        </button>
        <button onclick="filterJobTable('full')" class="job-filter-btn px-4 py-2 rounded-lg text-sm font-semibold transition bg-blue-500 text-white hover:bg-blue-600" data-job-filter="full">
            Full (No Slots)
        </button>
        <button onclick="filterJobTable('available')" class="job-filter-btn px-4 py-2 rounded-lg text-sm font-semibold transition bg-purple-500 text-white hover:bg-purple-600" data-job-filter="available">
            Available Slots
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full" id="jobsTable">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold">ID</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Title</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Location</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Salary</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Slots Available</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Status</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jobs as $job)
                <tr class="border-t hover:bg-gray-50 job-row" 
                    data-status="{{ $job->status }}" 
                    data-is-full="{{ $job->slots > 0 && $job->approved_count >= $job->slots ? 'full' : 'available' }}">
                    <td class="px-4 py-3">#{{ $job->id }}</td>
                    <td class="px-4 py-3 font-medium">{{ $job->title }}</td>
                    <td class="px-4 py-3">{{ $job->location }}</td>
                    <td class="px-4 py-3">₱{{ number_format($job->salary, 2) }}</td>
                    <td class="px-4 py-3">
                        @if($job->slots > 0)
                            @php $available = $job->slots - $job->approved_count; @endphp
                            @if($available > 0)
                                <span class="text-green-600 font-semibold">{{ $available }}</span>
                                <span class="text-gray-500 text-sm">/ {{ $job->slots }}</span>
                            @else
                                <span class="text-red-600 font-semibold">0</span>
                                <span class="text-gray-500 text-sm">/ {{ $job->slots }}</span>
                                <span class="ml-1 text-xs text-red-500">(Full)</span>
                            @endif
                        @else
                            <span class="text-gray-400">Unlimited</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @if($job->slots > 0 && $job->approved_count >= $job->slots)
                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">Full / Closed</span>
                        @elseif($job->status == 'open')
                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Open</span>
                        @else
                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">Closed</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 space-x-2">
                        <a href="{{ route('jobs.edit', $job) }}" class="inline-block text-blue-600 hover:text-blue-800 text-sm font-medium">Edit</a>
                        <form action="{{ route('jobs.toggle', $job) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="inline-block text-yellow-600 hover:text-yellow-800 text-sm font-medium">
                                {{ $job->status == 'open' ? 'Close' : 'Open' }}
                            </button>
                        </form>
                        <form action="{{ route('jobs.destroy', $job) }}" method="POST" class="inline" onsubmit="return confirm('Delete this job? This will also delete all applications!')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-block text-red-600 hover:text-red-800 text-sm font-medium">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-8 text-gray-500">No job listings yet. Click "Add New Job" to create one.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
function filterJobTable(filterType) {
    const rows = document.querySelectorAll('.job-row');
    rows.forEach(row => {
        const status = row.dataset.status;
        const isFull = row.dataset.isFull;
        
        if (filterType === 'all') {
            row.style.display = '';
        } else if (filterType === 'open') {
            row.style.display = status === 'open' ? '' : 'none';
        } else if (filterType === 'closed') {
            row.style.display = status === 'closed' ? '' : 'none';
        } else if (filterType === 'full') {
            row.style.display = isFull === 'full' ? '' : 'none';
        } else if (filterType === 'available') {
            row.style.display = isFull === 'available' ? '' : 'none';
        }
    });
    
    document.querySelectorAll('.job-filter-btn').forEach(btn => {
        if (btn.dataset.jobFilter === filterType) {
            btn.classList.add('ring-2', 'ring-offset-2', 'ring-gray-400');
        } else {
            btn.classList.remove('ring-2', 'ring-offset-2', 'ring-gray-400');
        }
    });
}
</script>
@endsection