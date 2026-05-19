@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="mb-8">
            <div class="flex justify-between mb-2">
                <span class="text-sm font-semibold text-red-600">Step 2 of 2</span>
                <span class="text-sm text-gray-500">Upload Resume</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2.5">
                <div class="bg-gradient-to-r from-red-600 to-red-700 h-2.5 rounded-full" style="width: 100%"></div>
            </div>
        </div>

        <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                <span class="text-2xl">📄</span>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Upload your Resume/CV</h1>
                <p class="text-gray-500">Accepted formats: PDF, DOC, DOCX (Max 2MB)</p>
            </div>
        </div>
        
        <form action="{{ route('applications.postStep3', $application->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-red-400 transition cursor-pointer" id="dropzone">
                <input type="file" name="resume" required accept=".pdf,.doc,.docx" class="hidden" id="resumeInput">
                <div class="text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    <p class="mt-2 text-sm text-gray-600" id="fileLabel">Click to upload or drag and drop</p>
                    <p class="text-xs text-gray-500 mt-1">PDF, DOC, DOCX up to 2MB</p>
                </div>
            </div>
            
            @if($application->resume_path)
                <div class="mt-3 p-3 bg-green-50 rounded-lg">
                    <p class="text-sm text-green-700">Current file: {{ basename($application->resume_path) }}</p>
                </div>
            @endif
            
            <div class="flex justify-between mt-8 pt-4 border-t">
                <a href="{{ route('applications.step2', $application->id) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-3 rounded-lg font-semibold transition text-center">
                    ← Back
                </a>
                <button type="submit" class="bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-8 py-3 rounded-lg font-semibold transition shadow-md">
                    Submit Application
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const dropzone = document.getElementById('dropzone');
const fileInput = document.getElementById('resumeInput');
const fileLabel = document.getElementById('fileLabel');

if (dropzone) {
    dropzone.addEventListener('click', () => {
        fileInput.click();
    });
}

if (fileInput) {
    fileInput.addEventListener('change', function(e) {
        if (e.target.files[0]) {
            fileLabel.textContent = 'Selected: ' + e.target.files[0].name;
            fileLabel.classList.add('text-green-600');
        } else {
            fileLabel.textContent = 'Click to upload or drag and drop';
            fileLabel.classList.remove('text-green-600');
        }
    });
}

if (dropzone) {
    dropzone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropzone.classList.add('border-red-500', 'bg-red-50');
    });

    dropzone.addEventListener('dragleave', (e) => {
        e.preventDefault();
        dropzone.classList.remove('border-red-500', 'bg-red-50');
    });

    dropzone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropzone.classList.remove('border-red-500', 'bg-red-50');
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            fileLabel.textContent = 'Selected: ' + files[0].name;
            fileLabel.classList.add('text-green-600');
        }
    });
}
</script>
@endsection