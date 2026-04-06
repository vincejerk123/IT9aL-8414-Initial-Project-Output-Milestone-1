<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-purple-100 rounded-lg">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Manage Applications</h2>
                <p class="text-sm text-gray-500 mt-0.5">Review and update application statuses</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-4">
        @foreach($applications as $application)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-gray-900">{{ $application->job->title }}</h3>
                        <p class="text-gray-600">{{ $application->job->company }}</p>
                        <p class="text-sm text-gray-500 mt-1">
                            Applicant: {{ $application->user->name }} ({{ $application->user->email }})
                        </p>
                        @if($application->cover_letter)
                            <p class="text-gray-600 text-sm mt-2"><strong>Cover Letter:</strong> {{ $application->cover_letter }}</p>
                        @endif
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <form action="{{ route('applications.update-status', $application) }}" method="POST" class="flex items-center gap-2">
                            @csrf
                            @method('PUT')
                            <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                                <option value="pending" {{ $application->status == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                                <option value="approved" {{ $application->status == 'approved' ? 'selected' : '' }}>✅ Approved</option>
                                <option value="declined" {{ $application->status == 'declined' ? 'selected' : '' }}>❌ Declined</option>
                            </select>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                                Update
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>