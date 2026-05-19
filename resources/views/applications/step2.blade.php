@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="mb-8">
            <div class="flex justify-between mb-2">
                <span class="text-sm font-semibold text-red-600">Step 1 of 2</span>
                <span class="text-sm text-gray-500">Work Experience</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2.5">
                <div class="bg-gradient-to-r from-red-600 to-red-700 h-2.5 rounded-full" style="width: 50%"></div>
            </div>
        </div>

        <!-- User Info Card (Auto-filled from account) -->
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Applying as:</p>
                    <p class="font-semibold text-gray-800">{{ $application->full_name }}</p>
                    <p class="text-sm text-gray-600">{{ $application->email }} | {{ $application->phone }}</p>
                </div>
            </div>
            <p class="text-xs text-green-600 mt-2">This information is from your account. It will be used for this application.</p>
        </div>

        <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                <span class="text-2xl">💼</span>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Tell us about your experience</h1>
                <p class="text-gray-500">Position: {{ $application->job->title }}</p>
            </div>
        </div>
        
        <form action="{{ route('applications.postStep2', $application->id) }}" method="POST">
            @csrf
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Previous Work Experience *</label>
                <textarea name="experience" rows="6" required 
                          class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition"
                          placeholder="Describe your previous jobs, skills, and why you'd be great at Sweetwen...">{{ old('experience', $application->experience) }}</textarea>
                <p class="text-xs text-gray-400 mt-1">Minimum 50 characters</p>
                <p class="text-xs text-gray-400 mt-1" id="charCount">0 characters</p>
                @error('experience')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex justify-between mt-8 pt-4 border-t">
                <a href="{{ route('dashboard') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-3 rounded-lg font-semibold transition text-center">
                    Cancel Application
                </a>
                <button type="submit" class="bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white px-8 py-3 rounded-lg font-semibold transition shadow-md">
                    Next Step →
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const textarea = document.querySelector('textarea[name="experience"]');
const charCount = document.getElementById('charCount');

function updateCharCount() {
    const length = textarea.value.length;
    charCount.textContent = length + ' characters';
    if (length < 50) {
        charCount.style.color = '#eab308';
        charCount.style.fontWeight = 'bold';
    } else {
        charCount.style.color = '#22c55e';
        charCount.style.fontWeight = 'normal';
    }
}

if (textarea) {
    textarea.addEventListener('input', updateCharCount);
    updateCharCount();
}
</script>
@endsection