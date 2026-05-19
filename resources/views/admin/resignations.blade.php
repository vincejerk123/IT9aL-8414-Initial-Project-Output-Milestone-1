@extends('layouts.app')

@section('content')
<div class="bg-white rounded-xl shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manage Resignation Requests</h1>
            <p class="text-gray-500">Review and respond to employee resignation requests</p>
        </div>
        <div class="text-right">
            <span class="text-sm text-gray-500">Pending Requests:</span>
            <p class="text-2xl font-bold text-yellow-600">{{ $pendingCount }}</p>
        </div>
    </div>

    <!-- Filter Buttons -->
    <div class="flex flex-wrap gap-2 mb-4">
        <button onclick="filterTable('all')" class="resign-filter-btn px-4 py-2 rounded-lg text-sm font-semibold transition bg-gray-600 text-white" data-filter="all">
            All Requests
        </button>
        <button onclick="filterTable('pending')" class="resign-filter-btn px-4 py-2 rounded-lg text-sm font-semibold transition bg-yellow-500 text-white hover:bg-yellow-600" data-filter="pending">
            Pending
        </button>
        <button onclick="filterTable('approved')" class="resign-filter-btn px-4 py-2 rounded-lg text-sm font-semibold transition bg-green-500 text-white hover:bg-green-600" data-filter="approved">
            Approved
        </button>
        <button onclick="filterTable('rejected')" class="resign-filter-btn px-4 py-2 rounded-lg text-sm font-semibold transition bg-red-500 text-white hover:bg-red-600" data-filter="rejected">
            Rejected
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full" id="resignationsTable">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Employee</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Position</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Last Day</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Primary Reason</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Submitted</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Clearance</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Status</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($resignations as $res)
                <tr class="border-t hover:bg-gray-50 resign-row" data-status="{{ $res->status }}">
                    <td class="px-4 py-3">
                        <p class="font-semibold">{{ $res->user->name }}</p>
                        <p class="text-xs text-gray-500">{{ $res->user->email }}</p>
                    </td>
                    <td class="px-4 py-3">
                        <span class="font-medium">{{ $res->job->title }}</span>
                        <p class="text-xs text-gray-500">{{ $res->job->location }}</p>
                    </td>
                    <td class="px-4 py-3 text-sm">
                        @if($res->last_day)
                            <span class="font-medium {{ $res->last_day < now() ? 'text-red-600' : 'text-gray-800' }}">
                                {{ $res->last_day->format('M d, Y') }}
                            </span>
                            @if($res->last_day < now())
                                <span class="ml-1 text-xs text-red-500">(Past)</span>
                            @endif
                        @else
                            <span class="text-gray-400">Not set</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm">
                        <span class="inline-block px-2 py-0.5 bg-gray-100 rounded-full text-xs">
                            {{ $res->exit_interview_reason ?: 'Not specified' }}
                        </span>
                        <button onclick="showDetails({{ $res->id }})" class="text-blue-500 text-xs hover:underline block mt-1">
                            View Full Details
                        </button>
                    </td>
                    <td class="px-4 py-3 text-sm">{{ $res->created_at->format('M d, Y') }}</td>
                    <td class="px-4 py-3">
                        @if($res->clearance_completed)
                            <span class="inline-flex items-center gap-1 text-green-600 text-xs font-medium">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Completed
                            </span>
                        @else
                            <span class="text-gray-500 text-xs">Pending</span>
                            @if($res->status == 'approved')
                                <button onclick="completeClearance({{ $res->id }})" class="ml-1 text-blue-500 text-xs hover:underline">Mark Complete</button>
                            @endif
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                            @if($res->status == 'pending') bg-yellow-100 text-yellow-700
                            @elseif($res->status == 'approved') bg-green-100 text-green-700
                            @else bg-red-100 text-red-700
                            @endif">
                            {{ ucfirst($res->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        @if($res->status == 'pending')
                            <button onclick="openApproveModal({{ $res->id }})" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm mr-1">Approve</button>
                            <button onclick="openRejectModal({{ $res->id }})" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">Reject</button>
                        @else
                            <span class="text-gray-400 text-sm">Already {{ $res->status }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-8 text-gray-500">No resignation requests found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Approve Modal -->
<div id="approveModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl max-w-md w-full mx-4 p-6">
        <h2 class="text-xl font-bold mb-4 text-green-600">Approve Resignation</h2>
        <p class="text-gray-600 mb-4">Are you sure you want to approve this resignation? The user will be released from employment and can apply for new positions.</p>
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Clearance Notes (Optional)</label>
            <textarea id="clearanceNotes" rows="2" class="w-full border rounded-lg px-3 py-2" placeholder="Add notes about clearance processing..."></textarea>
        </div>
        <form id="approveForm" method="POST">
            @csrf
            <input type="hidden" name="clearance_notes" id="clearanceNotesInput">
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeApproveModal()" class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded-lg">Cancel</button>
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">Approve</button>
            </div>
        </form>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl max-w-md w-full mx-4 p-6">
        <h2 class="text-xl font-bold mb-4 text-red-600">Reject Resignation</h2>
        <p class="text-gray-600 mb-2">Add rejection reason (optional):</p>
        <form id="rejectForm" method="POST">
            @csrf
            <textarea name="admin_notes" rows="3" class="w-full border rounded-lg px-3 py-2 mb-4" placeholder="Reason for rejection..."></textarea>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeRejectModal()" class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded-lg">Cancel</button>
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg">Reject</button>
            </div>
        </form>
    </div>
</div>

<!-- Details Modal (Full Exit Interview Details) -->
<div id="detailsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl max-w-2xl w-full mx-4 p-6 max-h-[85vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4 sticky top-0 bg-white pb-2 border-b">
            <h2 class="text-xl font-bold">Exit Interview Details</h2>
            <button onclick="closeDetailsModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
        </div>
        <div class="space-y-4 mt-4" id="detailsContent">
            <!-- Content will be filled by JavaScript -->
        </div>
        <div class="mt-6 pt-4 border-t flex justify-end">
            <button onclick="closeDetailsModal()" class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded-lg">Close</button>
        </div>
    </div>
</div>

<script>
// Store resignation data for details modal
const resignationsData = @json($resignations);

function filterTable(status) {
    const rows = document.querySelectorAll('.resign-row');
    rows.forEach(row => {
        if (status === 'all' || row.dataset.status === status) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
    
    document.querySelectorAll('.resign-filter-btn').forEach(btn => {
        if (btn.dataset.filter === status) {
            btn.classList.add('ring-2', 'ring-offset-2', 'ring-gray-400');
        } else {
            btn.classList.remove('ring-2', 'ring-offset-2', 'ring-gray-400');
        }
    });
}

function showDetails(id) {
    const resignation = resignationsData.find(r => r.id === id);
    if (!resignation) return;
    
    const container = document.getElementById('detailsContent');
    container.innerHTML = `
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-gray-50 p-3 rounded-lg">
                <p class="text-xs text-gray-500">Employee Name</p>
                <p class="font-semibold">${resignation.user.name}</p>
            </div>
            <div class="bg-gray-50 p-3 rounded-lg">
                <p class="text-xs text-gray-500">Position</p>
                <p class="font-semibold">${resignation.job.title}</p>
            </div>
            <div class="bg-gray-50 p-3 rounded-lg">
                <p class="text-xs text-gray-500">Last Day of Work</p>
                <p class="font-semibold ${resignation.last_day && new Date(resignation.last_day) < new Date() ? 'text-red-600' : ''}">
                    ${resignation.last_day ? new Date(resignation.last_day).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) : 'Not specified'}
                </p>
            </div>
            <div class="bg-gray-50 p-3 rounded-lg">
                <p class="text-xs text-gray-500">Submitted Date</p>
                <p class="font-semibold">${new Date(resignation.created_at).toLocaleDateString()}</p>
            </div>
        </div>
        
        <div class="bg-yellow-50 p-3 rounded-lg">
            <p class="text-xs text-gray-500">Primary Reason for Resignation</p>
            <p class="font-semibold text-yellow-800">${resignation.exit_interview_reason || 'Not specified'}</p>
        </div>
        
        <div class="bg-gray-50 p-3 rounded-lg">
            <p class="text-xs text-gray-500">Detailed Explanation</p>
            <p class="text-gray-700 whitespace-pre-wrap">${resignation.reason || 'No explanation provided'}</p>
        </div>
        
        <div class="bg-gray-50 p-3 rounded-lg">
            <p class="text-xs text-gray-500">Feedback / Suggestions</p>
            <p class="text-gray-700 italic">${resignation.feedback || 'No feedback provided'}</p>
        </div>
        
        <div class="bg-blue-50 p-3 rounded-lg">
            <p class="text-xs text-gray-500">Clearance Status</p>
            <div class="flex items-center gap-2 mt-1">
                ${resignation.clearance_completed ? 
                    '<span class="text-green-600 font-semibold">✓ Completed</span>' : 
                    '<span class="text-gray-500">Pending</span>'}
                ${resignation.clearance_notes ? `<p class="text-xs text-gray-500 mt-1">Notes: ${resignation.clearance_notes}</p>` : ''}
            </div>
        </div>
        
        ${resignation.admin_notes ? `
        <div class="bg-red-50 p-3 rounded-lg">
            <p class="text-xs text-gray-500">Admin Notes</p>
            <p class="text-gray-700">${resignation.admin_notes}</p>
        </div>
        ` : ''}
        
        <div class="bg-gray-100 p-3 rounded-lg">
            <p class="text-xs text-gray-500">Status</p>
            <span class="px-2 py-1 rounded-full text-xs font-semibold
                ${resignation.status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 
                  resignation.status === 'approved' ? 'bg-green-100 text-green-700' : 
                  'bg-red-100 text-red-700'}">
                ${resignation.status.toUpperCase()}
            </span>
            ${resignation.responded_at ? `<p class="text-xs text-gray-400 mt-1">Responded: ${new Date(resignation.responded_at).toLocaleString()}</p>` : ''}
        </div>
    `;
    
    document.getElementById('detailsModal').classList.remove('hidden');
    document.getElementById('detailsModal').classList.add('flex');
}

function closeDetailsModal() {
    document.getElementById('detailsModal').classList.remove('flex');
    document.getElementById('detailsModal').classList.add('hidden');
}

function openApproveModal(id) {
    const modal = document.getElementById('approveModal');
    const form = document.getElementById('approveForm');
    form.action = '/admin/resignation/' + id + '/approve';
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeApproveModal() {
    const notes = document.getElementById('clearanceNotes').value;
    document.getElementById('clearanceNotesInput').value = notes;
    const modal = document.getElementById('approveModal');
    modal.classList.remove('flex');
    modal.classList.add('hidden');
    document.getElementById('clearanceNotes').value = '';
}

function openRejectModal(id) {
    const modal = document.getElementById('rejectModal');
    const form = document.getElementById('rejectForm');
    form.action = '/admin/resignation/' + id + '/reject';
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeRejectModal() {
    const modal = document.getElementById('rejectModal');
    modal.classList.remove('flex');
    modal.classList.add('hidden');
}

function completeClearance(id) {
    if (confirm('Mark clearance as completed for this resignation?')) {
        fetch('/admin/resignation/' + id + '/clearance', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        }).then(response => {
            if (response.ok) {
                location.reload();
            }
        });
    }
}

window.onclick = function(event) {
    const modals = ['approveModal', 'rejectModal', 'detailsModal'];
    modals.forEach(modalId => {
        const modal = document.getElementById(modalId);
        if (event.target === modal) {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }
    });
}
</script>
@endsection