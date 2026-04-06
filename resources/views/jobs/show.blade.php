<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="/jobs" class="p-2 hover:bg-gray-100 rounded-lg transition-colors duration-200">
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                {{ $job->company }}
                            </span>
                            <span class="flex items-center gap-1 text-green-600 font-semibold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                ₱ {{ number_format($job->salary) }}/month
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
                            </div>
                        @else
                            <form action="{{ route('applications.apply', $job) }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Cover Letter (Optional)
                                    </label>
                                    <textarea 
                                        name="cover_letter" 
                                        rows="4"
                                        placeholder="Tell us why you're the perfect fit for this position..."
                                        class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 resize-none"
                                    ></textarea>
                                    <p class="text-xs text-gray-400 mt-1">Max 1000 characters</p>
                                </div>
                                <button type="submit" 
                                        class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 shadow-md hover:shadow-lg">
                                    Submit Application
                                </button>
                            </form>
                        @endif
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex flex-wrap items-center gap-3 pt-6 border-t border-gray-200">
                    <a href="/jobs" 
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-medium transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Jobs
                    </a>
                    
                    @if(auth()->user()->role == 'admin')
                        <a href="/jobs/{{ $job->id }}/edit"
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-yellow-500 hover:bg-yellow-600 text-white rounded-xl font-medium transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit Job
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>