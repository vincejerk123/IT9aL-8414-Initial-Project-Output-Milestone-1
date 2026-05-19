@extends('layouts.app')

@section('content')
<div class="bg-white rounded-xl shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Archived Applications</h1>
            <p class="text-gray-500">View and restore archived applications</p>
        </div>
        <a href="{{ route('admin.applications') }}" class="jollibee-bg text-white px-4 py-2 rounded-lg font-semibold transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Active
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-lg mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Applicant</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Position</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Contact</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Archived Date</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Original Status</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($archivedApplications as $app)
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <p class="font-semibold">{{ $app->full_name }}</p>
                        <p class="text-xs text-gray-500">ID: #{{ $app->id }}</p>
                    </td>
                    <td class="px-4 py-3">
                        <span class="font-medium">{{ $app->job->title }}</span>
                        <p class="text-xs text-gray-500">{{ $app->job->location }}</p>
                    </td>
                    <td class="px-4 py-3 text-sm">
                        <div>{{ $app->email }}</div>
                        <div class="text-gray-500">{{ $app->phone }}</div>
                    </td>
                    <td class="px-4 py-3 text-sm">{{ $app->archived_at ? $app->archived_at->format('M d, Y') : 'N/A' }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                            @if($app->status == 'pending') bg-yellow-100 text-yellow-700
                            @elseif($app->status == 'approved') bg-green-100 text-green-700
                            @else bg-red-100 text-red-700
                            @endif">
                            {{ ucfirst($app->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <button onclick="showDetails({{ $app->id }})" class="text-blue-600 hover:underline text-sm mr-2">View</button>
                        
                        <form action="{{ route('admin.restore', $app) }}" method="POST" class="inline mr-2">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="text-green-600 hover:text-green-800 text-sm" onclick="return confirm('Restore this application? It will appear in active applications.')">
                                🔄 Restore
                            </button>
                        </form>

                        <form action="{{ route('admin.deleteArchived', $app) }}" method="POST" class="inline" onsubmit="return confirm('Permanently delete this application? This action cannot be undone!')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                🗑️ Delete Permanently
                            </button>
                        </form>
                    </td>
                </tr>

                <!-- Modal -->
                <div id="modal-{{ $app->id }}" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50" onclick="closeModal({{ $app->id }})">
                    <div class="bg-white rounded-xl max-w-2xl w-full mx-4 p-6" onclick="event.stopPropagation()">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-bold">Archived Application Details</h2>
                            <button onclick="closeModal({{ $app->id }})" class="text-gray-500">✕</button>
                        </div>
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div><p class="text-sm text-gray-500">Full Name</p><p class="font-semibold">{{ $app->full_name }}</p></div>
                                <div><p class="text-sm text-gray-500">Position</p><p class="font-semibold">{{ $app->job->title }}</p></div>
                                <div><p class="text-sm text-gray-500">Email</p><p>{{ $app->email }}</p></div>
                                <div><p class="text-sm text-gray-500">Phone</p><p>{{ $app->phone }}</p></div>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Work Experience</p>
                                <div class="bg-gray-50 p-3 rounded-lg mt-1 text-sm">{{ $app->experience ?: 'No experience provided' }}</div>
                            </div>
                            @if($app->resume_path)
                            <div>
                                <p class="text-sm text-gray-500">Resume</p>
                                <a href="{{ Storage::url($app->resume_path) }}" target="_blank" class="text-red-600 hover:underline">📄 View Resume</a>
                            </div>
                            @endif
                            <div class="bg-gray-100 p-3 rounded-lg">
                                <p class="text-sm text-gray-500">Archived on:</p>
                                <p class="font-medium">{{ $app->archived_at ? $app->archived_at->format('F d, Y g:i A') : 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="mt-6 pt-4 border-t flex justify-end">
                            <button onclick="closeModal({{ $app->id }})" class="bg-gray-300 px-4 py-2 rounded-lg">Close</button>
                        </div>
                    </div>
                </div>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-8 text-gray-500">
                        <div class="text-6xl mb-4">📦</div>
                        <p class="text-lg font-semibold">No archived applications</p>
                        <p class="text-sm">Archived applications will appear here</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
function showDetails(id) {
    document.getElementById(`modal-${id}`).classList.remove('hidden');
    document.getElementById(`modal-${id}`).classList.add('flex');
}
function closeModal(id) {
    document.getElementById(`modal-${id}`).classList.remove('flex');
    document.getElementById(`modal-${id}`).classList.add('hidden');
}
</script>
@endsection