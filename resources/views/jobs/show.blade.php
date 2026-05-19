<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('jobs.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Job Details</h2>
                <p class="text-sm text-gray-500 mt-0.5">Review the complete job information</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Header Section -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-8 border-b border-gray-200">
                <div class="flex items-start gap-4">
                    <div class="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $job->title }}</h1>
                        <div class="flex flex-wrap items-center gap-4 text-sm">
                            <span class="flex items-center gap-1 text-gray-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                {{ $job->location }}
                            </span>
                            <span class="flex items-center gap-1 text-green-600 font-semibold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                ₱ {{ number_format($job->salary, 2) }}/month
                            </span>
                            <span class="flex items-center gap-1 text-gray-500 text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Posted: {{ $job->date_posted ? $job->date_posted->format('M d, Y') : $job->created_at->format('M d, Y') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Section -->
            <div class="p-8">
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Job Description
                    </h3>
                    <div class="text-gray-700 leading-relaxed whitespace-pre-wrap">
                        {{ $job->description }}
                    </div>
                </div>

                <!-- Employer Info -->
                <div class="mb-8 p-4 bg-gray-50 rounded-xl">
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">Posted by</h3>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <span class="text-blue-600 font-semibold">{{ $job->employer ? substr($job->employer->name, 0, 1) : 'E' }}</span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">{{ $job->employer ? $job->employer->name : 'Employer' }}</p>
                            <p class="text-sm text-gray-500">{{ $job->employer ? $job->employer->email : '' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Apply Section (Only for non-admin users) -->
                @if(auth()->user()->role != 'admin')
                    @php
                        $hasApplied = $job->hasUserApplied(auth()->id());
                        $application = $job->getUserApplication(auth()->id());
                    @endphp

                    <div class="border-t border-gray-200 pt-6 mt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Apply for this Position
                        </h3>

                        @if(session('success'))
                            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if($hasApplied)
                            <div class="p-4 rounded-lg {{ $application->status == 'pending' ? 'bg-yellow-50 border border-yellow-200' : ($application->status == 'approved' ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200') }}">
                                <div class="flex items-center gap-3">
                                    @if($application->status == 'pending')
                                        <span class="text-2xl">⏳</span>
                                        <div>
                                            <p class="font-semibold text-yellow-800">Application Pending</p>
                                            <p class="text-sm text-yellow-700">Your application is being reviewed by the employer.</p>
                                        </div>
                                    @elseif($application->status == 'approved')
                                        <span class="text-2xl">✅</span>
                                        <div>
                                            <p class="font-semibold text-green-800">Application Approved!</p>
                                            <p class="text-sm text-green-700">Congratulations! The employer will contact you soon.</p>
                                        </div>
                                    @else
                                        <span class="text-2xl">❌</span>
                                        <div>
                                            <p class="font-semibold text-red-800">Application Declined</p>
                                            <p class="text-sm text-red-700">Thank you for your interest. Keep applying to other positions!</p>
                                        </div>
                                    @endif
                                </div>
                                @if($application->resume)
                                    <div class="mt-3 pt-3 border-t border-gray-200">
                                        <p class="text-sm text-gray-600">Resume submitted: 
                                            <a href="{{ Storage::url($application->resume) }}" target="_blank" class="text-blue-600 hover:underline">
                                                View Resume
                                            </a>
                                        </p>
                                    </div>
                                @endif
                            </div>
                        @else
                            <form action="{{ route('applications.apply', $job) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                
                                <!-- Resume Upload -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Resume <span class="text-gray-400">(Optional)</span>
                                    </label>
                                    <input type="file" 
                                           name="resume" 
                                           id="resume"
                                           accept=".pdf,.doc,.docx"
                                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    <p class="text-xs text-gray-400 mt-1">Accepted formats: PDF, DOC, DOCX (Max 2MB)</p>
                                </div>

                                <!-- Cover Letter -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Cover Letter <span class="text-gray-400">(Optional)</span>
                                    </label>
                                    <textarea 
                                        name="cover_letter" 
                                        rows="5"
                                        placeholder="Tell us why you're the perfect fit for this position..."
                                        class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 resize-none"
                                    >{{ old('cover_letter') }}</textarea>
                                    <div class="flex justify-between items-center mt-1">
                                        <p class="text-xs text-gray-400">Max 1000 characters</p>
                                        <p class="text-xs text-gray-400" id="charCount">0/1000</p>
                                    </div>
                                </div>

                                <button type="submit" 
                                        class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 shadow-md hover:shadow-lg">
                                    Submit Application
                                </button>
                            </form>

                            <script>
                                const textarea = document.querySelector('textarea[name="cover_letter"]');
                                const charCount = document.getElementById('charCount');
                                if (textarea && charCount) {
                                    textarea.addEventListener('input', function() {
                                        charCount.textContent = this.value.length + '/1000';
                                    });
                                }
                            </script>
                        @endif
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex flex-wrap items-center gap-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('jobs.index') }}" 
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-medium transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Jobs
                    </a>
                    
                    @if(auth()->user()->role == 'admin')
                        <a href="{{ route('jobs.edit', $job) }}"
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-yellow-500 hover:bg-yellow-600 text-white rounded-xl font-medium transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit Job
                        </a>

                        <form action="{{ route('jobs.destroy', $job) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('Are you sure you want to delete this job?')"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-500 hover:bg-red-600 text-white rounded-xl font-medium transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Delete Job
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>