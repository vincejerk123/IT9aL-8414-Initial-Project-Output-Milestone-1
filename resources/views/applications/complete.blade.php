@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto text-center">
    <div class="bg-white rounded-xl shadow-md p-8">
        <div class="text-6xl mb-4">🎉</div>
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Application Submitted!</h1>
        <p class="text-gray-600 mb-4">Thank you for applying to Sweetwen as <strong>{{ $application->job->title }}</strong></p>
        
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <p class="text-green-700">Your application is now being reviewed by our HR team.</p>
            <p class="text-sm text-green-600 mt-1">We'll notify you once there's an update.</p>
        </div>
        
        <div class="flex gap-3 justify-center">
            <a href="{{ route('dashboard') }}" class="inline-block bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-bold px-6 py-3 rounded-lg transition shadow-md">
                Browse More Jobs
            </a>
            <a href="{{ route('applications.status', $application->id) }}" class="inline-block bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold px-6 py-3 rounded-lg transition">
                View Status
            </a>
        </div>
    </div>
</div>
@endsection