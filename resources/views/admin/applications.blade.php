@extends('layouts.app')

@section('content')
<div class="bg-white rounded-xl shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manage Applications - Sweetwen</h1>
            <p class="text-gray-500">Review and manage job applications from candidates</p>
            @if(request()->has('job_id'))
                @php $filteredJob = \App\Models\Job::find(request()->job_id); @endphp
                @if($filteredJob)
                    <div class="mt-2 inline-flex items-center gap-2 bg-blue-50 text-blue-700 px-3 py-1 rounded-lg text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Filtered by: <strong>{{ $filteredJob->title }}</strong> ({{ $filteredJob->location }})
                        <a href="{{ route('admin.applications') }}" class="ml-2 text-blue-600 hover:text-blue-800 text-xs font-semibold">Clear Filter</a>
                    </div>
                @endif
            @endif
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.archived') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-semibold transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                </svg>
                Archived
            </a>
            <div class="text-right">
                <span class="text-sm text-gray-500">Total Applications:</span>
                <p class="text-2xl font-bold text-red-600">{{ $applications->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Filter Buttons -->
    <div class="flex flex-wrap gap-2 mb-4">
        <button onclick="filterApplications('all')" class="app-filter-btn px-4 py-2 rounded-lg text-sm font-semibold transition bg-gray-600 text-white" data-filter="all">
            All Applications
        </button>
        <button onclick="filterApplications('pending')" class="app-filter-btn px-4 py-2 rounded-lg text-sm font-semibold transition bg-yellow-500 text-white hover:bg-yellow-600" data-filter="pending">
            Pending ({{ $applications->where('status', 'pending')->count() }})
        </button>
        <button onclick="filterApplications('approved')" class="app-filter-btn px-4 py-2 rounded-lg text-sm font-semibold transition bg-green-500 text-white hover:bg-green-600" data-filter="approved">
            Approved ({{ $applications->where('status', 'approved')->count() }})
        </button>
        <button onclick="filterApplications('rejected')" class="app-filter-btn px-4 py-2 rounded-lg text-sm font-semibold transition bg-red-500 text-white hover:bg-red-600" data-filter="rejected">
            Rejected ({{ $applications->where('status', 'rejected')->count() }})
        </button>
        <button onclick="filterApplications('completed')" class="app-filter-btn px-4 py-2 rounded-lg text-sm font-semibold transition bg-blue-500 text-white hover:bg-blue-600" data-filter="completed">
            Completed ({{ $applications->where('status', 'completed')->count() }})
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full" id="applicationsTable">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Applicant</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Position</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Contact</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Applied Date</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Status</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $app)
                <tr class="border-t hover:bg-gray-50 application-row" data-status="{{ $app->status }}" data-id="{{ $app->id }}">
                    <td class="px-4 py-3">
                        <p class="font-semibold">{{ $app->full_name }}</p>
                        <p class="text-xs text-gray-500">ID: #{{ $app->id }}</p>
                        <p class="text-xs text-gray-500">User ID: #{{ $app->user_id }}</p>
                    </td>
                    <td class="px-4 py-3">
                        <span class="font-medium">{{ $app->job->title }}</span>
                        <p class="text-xs text-gray-500">{{ $app->job->location }}</p>
                        @if($app->job->slots > 0)
                            <p class="text-xs text-gray-400">Slots left: {{ $app->job->getAvailableSlots() }}</p>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm">
                        <div>{{ $app->email }}</div>
                        <div class="text-gray-500">{{ $app->phone }}</div>
                    </td>
                    <td class="px-4 py-3 text-sm">{{ $app->created_at->format('M d, Y') }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                            @if($app->status == 'pending') bg-yellow-100 text-yellow-700
                            @elseif($app->status == 'approved') bg-green-100 text-green-700
                            @elseif($app->status == 'rejected') bg-red-100 text-red-700
                            @else bg-blue-100 text-blue-700
                            @endif">
                            @if($app->status == 'pending') Pending
                            @elseif($app->status == 'approved') Approved
                            @elseif($app->status == 'rejected') Rejected
                            @else Completed
                            @endif
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex flex-wrap gap-2">
                            <button onclick="showDetails({{ $app->id }})" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded text-sm transition font-medium">
                                View Details
                            </button>
                            
                            @if($app->status == 'pending')
                                <button onclick="openApproveModal({{ $app->id }}, '{{ $app->job->title }}', '{{ $app->full_name }}')" 
                                        class="bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 rounded text-sm transition font-medium {{ !$app->job->canApproveMore() ? 'opacity-50 cursor-not-allowed' : '' }}"
                                        {{ !$app->job->canApproveMore() ? 'disabled' : '' }}>
                                    Approve
                                </button>
                                
                                <button onclick="openRejectModal({{ $app->id }}, '{{ $app->job->title }}', '{{ $app->full_name }}')" 
                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded text-sm transition font-medium">
                                    Reject
                                </button>
                            @endif
                            
                            @if($app->status == 'approved')
                                <button onclick="openRevokeModal({{ $app->id }}, '{{ $app->job->title }}', '{{ $app->full_name }}')" 
                                        class="bg-orange-500 hover:bg-orange-600 text-white px-3 py-1.5 rounded text-sm transition font-medium">
                                    Revoke Approval
                                </button>
                            @endif
                            
                            @if($app->status == 'rejected')
                                <button onclick="openApproveModal({{ $app->id }}, '{{ $app->job->title }}', '{{ $app->full_name }}')" 
                                        class="bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 rounded text-sm transition font-medium {{ !$app->job->canApproveMore() ? 'opacity-50 cursor-not-allowed' : '' }}"
                                        {{ !$app->job->canApproveMore() ? 'disabled' : '' }}>
                                    Approve
                                </button>
                            @endif
                            
                            <button onclick="openArchiveModal({{ $app->id }}, '{{ $app->job->title }}', '{{ $app->full_name }}')" 
                                    class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1.5 rounded text-sm transition font-medium">
                                Archive
                            </button>
                            
                            <button onclick="openDeleteModal({{ $app->id }}, '{{ $app->job->title }}', '{{ $app->full_name }}')" 
                                    class="bg-red-700 hover:bg-red-800 text-white px-3 py-1.5 rounded text-sm transition font-medium">
                                Delete
                            </button>
                        </div>
                    </td>
                </tr>

                <!-- Modal for Details -->
                <div id="modal-{{ $app->id }}" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
                    <div class="bg-white rounded-xl max-w-2xl w-full mx-4 p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-bold">Application Details</h2>
                            <button onclick="closeModal({{ $app->id }})" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
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
                                <div class="bg-gray-50 p-3 rounded-lg mt-1 text-sm max-h-40 overflow-y-auto">{{ $app->experience ?: 'No experience provided' }}</div>
                            </div>
                            @if($app->resume_path)
                            <div>
                                <p class="text-sm text-gray-500">Resume</p>
                                <a href="{{ Storage::url($app->resume_path) }}" target="_blank" class="text-red-600 hover:underline">View Resume</a>
                            </div>
                            @endif
                            @if($app->status == 'completed')
                            <div class="bg-blue-50 p-3 rounded-lg">
                                <p class="text-sm text-blue-700">This application is marked as COMPLETED (User resigned).</p>
                            </div>
                            @endif
                        </div>
                        <div class="mt-6 pt-4 border-t flex justify-end">
                            <button onclick="closeModal({{ $app->id }})" class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded-lg">Close</button>
                        </div>
                    </div>
                </div>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-8 text-gray-500">
                        @if(request()->has('job_id'))
                            No applications found for this position.
                        @else
                            No applications found.
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Approve Confirmation Modal -->
<div id="approveModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl max-w-md w-full mx-4 p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-green-600">Approve Application</h2>
            <button onclick="closeApproveModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
        </div>
        <p class="text-gray-700 mb-4" id="approveMessage"></p>
        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-3 mb-4">
            <p class="text-sm text-yellow-700">Warning: Approving this application will mark the user as HIRED. They will no longer be able to apply for other positions.</p>
        </div>
        <form id="approveForm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" value="approved">
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeApproveModal()" class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded-lg">Cancel</button>
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">Yes, Approve Application</button>
            </div>
        </form>
    </div>
</div>

<!-- Reject Confirmation Modal -->
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl max-w-md w-full mx-4 p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-red-600">Reject Application</h2>
            <button onclick="closeRejectModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
        </div>
        <p class="text-gray-700 mb-4" id="rejectMessage"></p>
        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-3 mb-4">
            <p class="text-sm text-yellow-700">Warning: Rejecting this application will allow the user to apply again for this position.</p>
        </div>
        <form id="rejectForm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" value="rejected">
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeRejectModal()" class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded-lg">Cancel</button>
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg">Yes, Reject Application</button>
            </div>
        </form>
    </div>
</div>

<!-- Revoke Approval Confirmation Modal -->
<div id="revokeModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl max-w-md w-full mx-4 p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-orange-600">Revoke Approval</h2>
            <button onclick="closeRevokeModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
        </div>
        <p class="text-gray-700 mb-4" id="revokeMessage"></p>
        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-3 mb-4">
            <p class="text-sm text-yellow-700">Warning: Revoking approval will mark the application as PENDING and free up a slot. The user will no longer be marked as HIRED.</p>
        </div>
        <form id="revokeForm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" value="pending">
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeRevokeModal()" class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded-lg">Cancel</button>
                <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg">Yes, Revoke Approval</button>
            </div>
        </form>
    </div>
</div>

<!-- Archive Confirmation Modal -->
<div id="archiveModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl max-w-md w-full mx-4 p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-600">Archive Application</h2>
            <button onclick="closeArchiveModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
        </div>
        <p class="text-gray-700 mb-4" id="archiveMessage"></p>
        <p class="text-sm text-gray-500 mb-4">Archived applications can be restored later from the Archived page.</p>
        <form id="archiveForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeArchiveModal()" class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded-lg">Cancel</button>
                <button type="submit" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">Yes, Archive Application</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl max-w-md w-full mx-4 p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-red-700">Delete Application</h2>
            <button onclick="closeDeleteModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
        </div>
        <p class="text-gray-700 mb-4" id="deleteMessage"></p>
        <div class="bg-red-50 border-l-4 border-red-500 p-3 mb-4">
            <p class="text-sm text-red-700">Warning: This action CANNOT be undone. The application and its resume will be permanently deleted.</p>
        </div>
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeDeleteModal()" class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded-lg">Cancel</button>
                <button type="submit" class="bg-red-700 hover:bg-red-800 text-white px-4 py-2 rounded-lg">Yes, Permanently Delete</button>
            </div>
        </form>
    </div>
</div>

<script>
// Filter function
function filterApplications(status) {
    const rows = document.querySelectorAll('.application-row');
    rows.forEach(row => {
        if (status === 'all' || row.dataset.status === status) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
    
    document.querySelectorAll('.app-filter-btn').forEach(btn => {
        if (btn.dataset.filter === status) {
            btn.classList.add('ring-2', 'ring-offset-2', 'ring-gray-400');
        } else {
            btn.classList.remove('ring-2', 'ring-offset-2', 'ring-gray-400');
        }
    });
}

// Details Modal
function showDetails(id) {
    const modal = document.getElementById(`modal-${id}`);
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
}

function closeModal(id) {
    const modal = document.getElementById(`modal-${id}`);
    if (modal) {
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }
}

// Approve Modal
function openApproveModal(id, jobTitle, applicantName) {
    const modal = document.getElementById('approveModal');
    const message = document.getElementById('approveMessage');
    const form = document.getElementById('approveForm');
    
    message.innerHTML = `Are you sure you want to <strong class="text-green-600">APPROVE</strong> ${applicantName}'s application for <strong>${jobTitle}</strong>?`;
    form.action = `/admin/application/${id}/status`;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeApproveModal() {
    const modal = document.getElementById('approveModal');
    modal.classList.remove('flex');
    modal.classList.add('hidden');
}

// Reject Modal
function openRejectModal(id, jobTitle, applicantName) {
    const modal = document.getElementById('rejectModal');
    const message = document.getElementById('rejectMessage');
    const form = document.getElementById('rejectForm');
    
    message.innerHTML = `Are you sure you want to <strong class="text-red-600">REJECT</strong> ${applicantName}'s application for <strong>${jobTitle}</strong>?`;
    form.action = `/admin/application/${id}/status`;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeRejectModal() {
    const modal = document.getElementById('rejectModal');
    modal.classList.remove('flex');
    modal.classList.add('hidden');
}

// Revoke Modal
function openRevokeModal(id, jobTitle, applicantName) {
    const modal = document.getElementById('revokeModal');
    const message = document.getElementById('revokeMessage');
    const form = document.getElementById('revokeForm');
    
    message.innerHTML = `Are you sure you want to <strong class="text-orange-600">REVOKE APPROVAL</strong> for ${applicantName}'s application for <strong>${jobTitle}</strong>?`;
    form.action = `/admin/application/${id}/status`;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeRevokeModal() {
    const modal = document.getElementById('revokeModal');
    modal.classList.remove('flex');
    modal.classList.add('hidden');
}

// Archive Modal
function openArchiveModal(id, jobTitle, applicantName) {
    const modal = document.getElementById('archiveModal');
    const message = document.getElementById('archiveMessage');
    const form = document.getElementById('archiveForm');
    
    message.innerHTML = `Are you sure you want to <strong class="text-gray-600">ARCHIVE</strong> ${applicantName}'s application for <strong>${jobTitle}</strong>?`;
    form.action = `/admin/application/${id}/archive`;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeArchiveModal() {
    const modal = document.getElementById('archiveModal');
    modal.classList.remove('flex');
    modal.classList.add('hidden');
}

// Delete Modal
function openDeleteModal(id, jobTitle, applicantName) {
    const modal = document.getElementById('deleteModal');
    const message = document.getElementById('deleteMessage');
    const form = document.getElementById('deleteForm');
    
    message.innerHTML = `Are you sure you want to <strong class="text-red-700">PERMANENTLY DELETE</strong> ${applicantName}'s application for <strong>${jobTitle}</strong>?`;
    form.action = `/admin/application/${id}`;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.classList.remove('flex');
    modal.classList.add('hidden');
}

// Close modals when clicking outside
window.onclick = function(event) {
    const modals = ['approveModal', 'rejectModal', 'revokeModal', 'archiveModal', 'deleteModal'];
    modals.forEach(modalId => {
        const modal = document.getElementById(modalId);
        if (event.target === modal) {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }
    });
    
    const detailModals = document.querySelectorAll('[id^="modal-"]');
    detailModals.forEach(modal => {
        if (event.target === modal) {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }
    });
}
</script>
@endsection