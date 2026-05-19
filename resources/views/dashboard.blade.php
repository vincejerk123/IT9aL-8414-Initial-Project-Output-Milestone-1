@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Welcome Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Welcome back, {{ auth()->user()->name }}!</h1>
                <p class="text-gray-500 mt-1">Manage your job applications and career at Sweetwen</p>
            </div>
            <div class="flex items-center space-x-2">
                <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                    <span class="text-xl font-bold text-red-700">{{ substr(auth()->user()->name, 0, 1) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Applications Limit Warning -->
    @php
        $user = auth()->user();
        $pendingCount = \App\Models\Application::where('user_id', $user->id)->where('status', 'pending')->count();
    @endphp

    @if($pendingCount > 0)
        <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-3">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-sm text-blue-700">
                    You have <strong>{{ $pendingCount }}</strong> pending application(s). Maximum allowed is 3.
                    @if($pendingCount >= 3)
                        <span class="font-semibold">You cannot apply for more positions until these are processed.</span>
                    @endif
                </p>
            </div>
        </div>
    @endif

    <!-- Employment Status Banner -->
    @php
        $pendingResign = \App\Models\Resignation::where('user_id', $user->id)->where('status', 'pending')->first();
    @endphp

    @if($user->isHired() && !$pendingResign)
        <div class="bg-green-50 border-l-4 border-green-500 rounded-lg p-4">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <p class="font-bold text-green-800">You are hired!</p>
                    <p class="text-sm text-green-700">Position: <strong>{{ $user->hiredPosition->title ?? 'N/A' }}</strong></p>
                    <p class="text-xs text-green-600 mt-1">You cannot apply for other positions while employed.</p>
                </div>
                <button onclick="openResignModal()" class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-medium rounded-lg transition text-sm">
                    Submit Resignation Request
                </button>
            </div>
        </div>
    @elseif($pendingResign)
        <div class="bg-yellow-50 border-l-4 border-yellow-500 rounded-lg p-4">
            <p class="font-bold text-yellow-800">Resignation Request Pending</p>
            <p class="text-sm text-yellow-700">Your resignation is being reviewed by HR.</p>
        </div>
    @endif

    <!-- Available Jobs -->
    @if($user->canApply() && !$user->isHired() && !$pendingResign)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="text-lg font-bold text-gray-900">Available Positions</h2>
                <p class="text-sm text-gray-500 mt-1">Browse and apply to open positions at Sweetwen</p>
            </div>
            <div class="divide-y divide-gray-100">
                @php 
                $jobs = \App\Models\Job::where('status', 'open')
                    ->where(function($query) {
                        $query->where('slots', 0)
                              ->orWhereRaw('slots > approved_count');
                    })
                    ->latest()
                    ->get(); 
                @endphp
                
                @forelse($jobs as $job)
                    @php 
                        $hasActive = \App\Models\Application::where('user_id', auth()->id())
                            ->where('job_id', $job->id)
                            ->whereIn('status', ['pending', 'approved'])
                            ->exists();
                        $incomplete = \App\Models\Application::where('user_id', auth()->id())
                            ->where('job_id', $job->id)
                            ->whereNull('status')
                            ->first();
                        $availableSlots = $job->getAvailableSlots();
                        $isFull = $job->isHiringComplete();
                    @endphp
                    
                    <div class="p-5 hover:bg-gray-50 transition">
                        <div class="flex flex-col md:flex-row justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2 flex-wrap">
                                    <h3 class="text-lg font-bold text-gray-800">{{ $job->title }}</h3>
                                    @if($isFull)
                                        <span class="px-2 py-0.5 bg-red-100 text-red-700 text-xs rounded-full">Position Filled</span>
                                    @else
                                        <span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs rounded-full">Open</span>
                                    @endif
                                </div>
                                <div class="flex flex-wrap gap-3 text-sm text-gray-500 mb-2">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        {{ $job->location }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        ₱{{ number_format($job->salary, 2) }}/month
                                    </span>
                                    @if($job->slots > 0 && !$isFull)
                                        <span class="flex items-center gap-1 text-blue-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                            </svg>
                                            {{ $availableSlots }} slot(s) available
                                        </span>
                                    @elseif($job->slots == 0)
                                        <span class="flex items-center gap-1 text-green-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                            </svg>
                                            Unlimited slots
                                        </span>
                                    @endif
                                </div>
                                <p class="text-gray-600 text-sm line-clamp-2">{{ Str::limit($job->description, 120) }}</p>
                            </div>
                            <div class="flex items-center">
                                @if($hasActive)
                                    <span class="px-4 py-2 bg-gray-100 text-gray-500 rounded-lg text-sm font-medium">Applied</span>
                                @elseif($incomplete)
                                    <a href="{{ route('applications.step2', $incomplete) }}" class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-medium rounded-lg transition text-sm">Resume Application</a>
                                @elseif($isFull)
                                    <span class="px-4 py-2 bg-gray-200 text-gray-500 rounded-lg text-sm font-medium cursor-not-allowed">No Slots Available</span>
                                @else
                                    <a href="{{ route('applications.start', $job) }}" class="px-4 py-2 bg-red-700 hover:bg-red-800 text-white font-medium rounded-lg transition text-sm shadow-sm">Apply Now</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <p class="text-gray-500">No open positions at the moment.</p>
                        <p class="text-sm text-gray-400 mt-1">Check back later for opportunities at Sweetwen!</p>
                    </div>
                @endforelse
            </div>
        </div>
    @elseif($user->isHired() && !$pendingResign)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-700">You are currently employed at Sweetwen</h3>
            <p class="text-gray-500 mt-1">New job applications are not available while you are hired.</p>
            <p class="text-sm text-gray-400 mt-2">If you wish to resign, click the "Submit Resignation Request" button above.</p>
        </div>
    @elseif($pendingResign)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
            <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-700">Resignation Request Pending</h3>
            <p class="text-gray-500 mt-1">Your resignation is being processed. You cannot apply for jobs until it is approved.</p>
        </div>
    @endif

    <!-- My Applications with Cancel Button -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex flex-wrap justify-between items-center gap-3 bg-gray-50">
            <div>
                <h2 class="text-lg font-bold text-gray-900">My Applications</h2>
                <p class="text-sm text-gray-500 mt-1">Track your application status</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <button onclick="filterApps('all')" class="filter-btn px-3 py-1 rounded-lg text-xs font-medium bg-gray-600 text-white">All</button>
                <button onclick="filterApps('pending')" class="filter-btn px-3 py-1 rounded-lg text-xs font-medium bg-yellow-500 text-white">Pending</button>
                <button onclick="filterApps('approved')" class="filter-btn px-3 py-1 rounded-lg text-xs font-medium bg-green-500 text-white">Approved</button>
                <button onclick="filterApps('rejected')" class="filter-btn px-3 py-1 rounded-lg text-xs font-medium bg-red-500 text-white">Rejected</button>
                <button onclick="filterApps('completed')" class="filter-btn px-3 py-1 rounded-lg text-xs font-medium bg-blue-500 text-white">Completed</button>
            </div>
        </div>
        <div class="divide-y divide-gray-100" id="applications-list">
            @php $apps = auth()->user()->applications()->with('job')->latest()->get(); @endphp
            @forelse($apps as $app)
                <div class="p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 app-item" data-status="{{ $app->status }}">
                    <div>
                        <p class="font-semibold text-gray-800">{{ $app->job->title }}</p>
                        <p class="text-xs text-gray-500">Applied: {{ $app->created_at->format('M d, Y') }}</p>
                        @if($app->job->location)
                            <p class="text-xs text-gray-400 mt-1">📍 {{ $app->job->location }}</p>
                        @endif
                    </div>
                    <div class="flex items-center gap-2 flex-wrap">
                        @if($app->status == 'pending')
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs rounded-full">Pending</span>
                            <a href="{{ route('applications.status', $app) }}" class="text-blue-600 text-sm hover:underline">View Details</a>
                            <button onclick="openCancelModal({{ $app->id }}, '{{ $app->job->title }}')" 
                                    class="text-red-600 text-sm hover:underline">
                                Cancel Application
                            </button>
                        @elseif($app->status == 'approved')
                            <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">Approved</span>
                            <a href="{{ route('applications.status', $app) }}" class="text-blue-600 text-sm hover:underline">View Details</a>
                        @elseif($app->status == 'completed')
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded-full">Completed</span>
                        @elseif($app->status == 'rejected')
                            <span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded-full">Rejected</span>
                            <a href="{{ route('applications.start', $app->job) }}" class="text-green-600 text-sm hover:underline">Apply Again</a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-8 text-center">
                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-500">No applications yet.</p>
                    <p class="text-sm text-gray-400 mt-1">Apply to a position above to get started.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Cancel Application Modal -->
<div id="cancelModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl max-w-md w-full mx-4 p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-red-600">Cancel Application</h2>
            <button onclick="closeCancelModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        </div>
        <p class="text-gray-700 mb-4" id="cancelMessage"></p>
        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-3 mb-4">
            <p class="text-sm text-yellow-700">Warning: This action cannot be undone. You will need to reapply if you change your mind.</p>
        </div>
        <form id="cancelForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeCancelModal()" class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded-lg">Keep Application</button>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">Yes, Cancel Application</button>
            </div>
        </form>
    </div>
</div>

<!-- Resignation Modal with Exit Interview and Validation Errors -->
<div id="resignModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl max-w-2xl w-full mx-4 p-6 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4 sticky top-0 bg-white pb-2 border-b">
            <h2 class="text-xl font-bold">Submit Resignation Request</h2>
            <button onclick="closeResignModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        </div>
        
        <!-- Display Validation Errors -->
        @if($errors->any())
            <div class="mb-4 p-3 bg-red-50 border-l-4 border-red-500 rounded-lg">
                <div class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="text-sm text-red-700">
                        <p class="font-semibold mb-1">Please fix the following errors:</p>
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif
        
        <form action="{{ route('applications.resign.submit') }}" method="POST" id="resignationForm">
            @csrf
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Last Day of Work *</label>
                <input type="date" name="last_day" id="last_day" required min="{{ date('Y-m-d') }}" 
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500 @error('last_day') border-red-500 @enderror"
                       value="{{ old('last_day') }}">
                @error('last_day')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-400 mt-1">Please select your intended last working day</p>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Primary Reason for Resignation *</label>
                <select name="exit_interview_reason" id="exit_interview_reason" required 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500 @error('exit_interview_reason') border-red-500 @enderror">
                    <option value="">-- Select Reason --</option>
                    <option value="Better Opportunity" {{ old('exit_interview_reason') == 'Better Opportunity' ? 'selected' : '' }}>Better Career Opportunity</option>
                    <option value="Salary" {{ old('exit_interview_reason') == 'Salary' ? 'selected' : '' }}>Salary/Compensation</option>
                    <option value="Work-Life Balance" {{ old('exit_interview_reason') == 'Work-Life Balance' ? 'selected' : '' }}>Work-Life Balance</option>
                    <option value="Relocation" {{ old('exit_interview_reason') == 'Relocation' ? 'selected' : '' }}>Relocation</option>
                    <option value="Health" {{ old('exit_interview_reason') == 'Health' ? 'selected' : '' }}>Health Reasons</option>
                    <option value="Family" {{ old('exit_interview_reason') == 'Family' ? 'selected' : '' }}>Family Reasons</option>
                    <option value="Further Studies" {{ old('exit_interview_reason') == 'Further Studies' ? 'selected' : '' }}>Further Studies</option>
                    <option value="Other" {{ old('exit_interview_reason') == 'Other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('exit_interview_reason')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Detailed Explanation * <span class="text-xs text-gray-400">(Minimum 10 characters)</span></label>
                <textarea name="reason" id="reason" rows="3" required 
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500 @error('reason') border-red-500 @enderror"
                          placeholder="Please provide more details about your decision to resign...">{{ old('reason') }}</textarea>
                <div class="flex justify-between items-center mt-1">
                    <p class="text-xs text-gray-400" id="charCounter">0 / 10 characters minimum</p>
                    @error('reason')
                        <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Feedback/Suggestions (Optional)</label>
                <textarea name="feedback" id="feedback" rows="3" 
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500"
                          placeholder="Do you have any feedback or suggestions for Sweetwen?">{{ old('feedback') }}</textarea>
            </div>
            
            <div class="bg-gray-50 rounded-lg p-4 mb-4">
                <p class="text-sm font-semibold text-gray-700 mb-2">Clearance Requirements:</p>
                <ul class="text-xs text-gray-600 space-y-1 list-disc list-inside">
                    <li>Return of company ID and uniform</li>
                    <li>Settlement of any cash advances</li>
                    <li>Turnover of pending tasks</li>
                    <li>Exit interview with HR</li>
                </ul>
                <p class="text-xs text-gray-400 mt-2">Note: Clearance will be processed after HR approval.</p>
            </div>
            
            <div class="flex justify-end gap-3 sticky bottom-0 bg-white pt-4 border-t">
                <button type="button" onclick="closeResignModal()" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 transition">Cancel</button>
                <button type="submit" id="submitResignBtn" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition disabled:opacity-50">Submit Resignation</button>
            </div>
        </form>
    </div>
</div>

<script>
function filterApps(status) {
    document.querySelectorAll('.app-item').forEach(el => {
        el.style.display = (status === 'all' || el.dataset.status === status) ? '' : 'none';
    });
    
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.classList.remove('ring-2', 'ring-offset-2', 'ring-gray-400');
        if (btn.textContent.toLowerCase() === status || (status === 'all' && btn.textContent === 'All')) {
            btn.classList.add('ring-2', 'ring-offset-2', 'ring-gray-400');
        }
    });
}

function openCancelModal(id, jobTitle) {
    const modal = document.getElementById('cancelModal');
    const message = document.getElementById('cancelMessage');
    const form = document.getElementById('cancelForm');
    
    message.innerHTML = `Are you sure you want to <strong class="text-red-600">CANCEL</strong> your application for <strong>${jobTitle}</strong>?`;
    form.action = `/application/${id}/cancel`;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeCancelModal() {
    const modal = document.getElementById('cancelModal');
    modal.classList.remove('flex');
    modal.classList.add('hidden');
}

function openResignModal() { 
    document.getElementById('resignModal').classList.remove('hidden'); 
    document.getElementById('resignModal').classList.add('flex'); 
}

function closeResignModal() { 
    document.getElementById('resignModal').classList.remove('flex'); 
    document.getElementById('resignModal').classList.add('hidden'); 
}

// Live character counter for detailed explanation
const reasonTextarea = document.getElementById('reason');
const charCounter = document.getElementById('charCounter');

if (reasonTextarea) {
    function updateCharCounter() {
        const length = reasonTextarea.value.length;
        charCounter.innerHTML = `${length} / 10 characters minimum`;
        if (length >= 10) {
            charCounter.classList.remove('text-red-500');
            charCounter.classList.add('text-green-600');
        } else {
            charCounter.classList.remove('text-green-600');
            charCounter.classList.add('text-red-500');
        }
    }
    
    reasonTextarea.addEventListener('input', updateCharCounter);
    updateCharCounter();
}

// Form validation before submit
const resignationForm = document.getElementById('resignationForm');
if (resignationForm) {
    resignationForm.addEventListener('submit', function(e) {
        const reason = document.getElementById('reason');
        const lastDay = document.getElementById('last_day');
        const exitReason = document.getElementById('exit_interview_reason');
        
        let errors = [];
        
        if (!lastDay.value) {
            errors.push('Please select your last day of work');
            lastDay.classList.add('border-red-500');
        }
        
        if (!exitReason.value) {
            errors.push('Please select a primary reason for resignation');
            exitReason.classList.add('border-red-500');
        }
        
        if (reason.value.length < 10) {
            errors.push('Detailed explanation must be at least 10 characters');
            reason.classList.add('border-red-500');
        }
        
        if (errors.length > 0) {
            e.preventDefault();
            let errorHtml = '<div class="mb-4 p-3 bg-red-50 border-l-4 border-red-500 rounded-lg" id="dynamicError"><div class="flex items-start gap-2"><svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg><div class="text-sm text-red-700"><p class="font-semibold mb-1">Please fix the following errors:</p><ul class="list-disc list-inside">';
            errors.forEach(error => {
                errorHtml += `<li>${error}</li>`;
            });
            errorHtml += '</ul></div></div></div>';
            
            // Remove any existing error div
            const existingError = document.getElementById('dynamicError');
            if (existingError) {
                existingError.remove();
            }
            
            // Insert error at top of form
            resignationForm.insertAdjacentHTML('afterbegin', errorHtml);
            
            // Scroll to top
            document.querySelector('#resignModal .overflow-y-auto').scrollTop = 0;
        }
    });
    
    // Remove red border when user starts typing/fixing
    const reasonField = document.getElementById('reason');
    const lastDayField = document.getElementById('last_day');
    const exitReasonField = document.getElementById('exit_interview_reason');
    
    if (reasonField) {
        reasonField.addEventListener('input', function() {
            this.classList.remove('border-red-500');
            const errorDiv = document.getElementById('dynamicError');
            if (errorDiv && this.value.length >= 10) {
                errorDiv.remove();
            }
        });
    }
    
    if (lastDayField) {
        lastDayField.addEventListener('change', function() {
            this.classList.remove('border-red-500');
            const errorDiv = document.getElementById('dynamicError');
            if (errorDiv && this.value) {
                errorDiv.remove();
            }
        });
    }
    
    if (exitReasonField) {
        exitReasonField.addEventListener('change', function() {
            this.classList.remove('border-red-500');
            const errorDiv = document.getElementById('dynamicError');
            if (errorDiv && this.value) {
                errorDiv.remove();
            }
        });
    }
}

// Close modals when clicking outside
window.onclick = function(event) {
    const cancelModal = document.getElementById('cancelModal');
    if (event.target === cancelModal) {
        cancelModal.classList.remove('flex');
        cancelModal.classList.add('hidden');
    }
    const resignModal = document.getElementById('resignModal');
    if (event.target === resignModal) {
        resignModal.classList.remove('flex');
        resignModal.classList.add('hidden');
    }
}
</script>
@endsection