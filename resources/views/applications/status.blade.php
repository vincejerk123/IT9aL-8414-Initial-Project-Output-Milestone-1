@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="text-center mb-6">
            <div class="text-5xl mb-3">
                @if($application->status == 'pending') ⏳
                @elseif($application->status == 'approved') 🎉
                @else ❌
                @endif
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Application Status</h1>
            <p class="text-gray-500">Position: <strong>{{ $application->job->title }}</strong></p>
        </div>

        <div class="rounded-lg p-6 mb-6 text-center
            @if($application->status == 'pending') bg-yellow-50 border border-yellow-200
            @elseif($application->status == 'approved') bg-green-50 border border-green-200
            @else bg-red-50 border border-red-200
            @endif">
            
            @if($application->status == 'pending')
                <p class="text-yellow-700 font-semibold text-lg">Your application is being reviewed</p>
                <p class="text-yellow-600 text-sm mt-2">Our HR team will evaluate your application and get back to you soon.</p>
            @elseif($application->status == 'approved')
                <p class="text-green-700 font-semibold text-lg">Congratulations! 🎊</p>
                <p class="text-green-600 text-sm mt-2">Your application has been approved! Our team will contact you for the next steps.</p>
            @else
                <p class="text-red-700 font-semibold text-lg">Application Not Selected</p>
                <p class="text-red-600 text-sm mt-2">Thank you for your interest. We encourage you to apply for other positions at Sweetwen!</p>
            @endif
        </div>

        <div class="border-t pt-4">
            <h3 class="font-semibold text-gray-700 mb-3">Application Details</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Full Name:</span>
                    <span class="font-medium">{{ $application->full_name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Email:</span>
                    <span>{{ $application->email }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Phone:</span>
                    <span>{{ $application->phone }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Applied on:</span>
                    <span>{{ $application->created_at->format('F d, Y') }}</span>
                </div>
            </div>
        </div>

        @if($application->resume_path)
        <div class="border-t pt-4 mt-4">
            <a href="{{ Storage::url($application->resume_path) }}" target="_blank" class="text-red-600 hover:underline text-sm">📄 View Submitted Resume</a>
        </div>
        @endif

        <div class="flex gap-3 justify-center mt-6 pt-4 border-t">
            <a href="{{ route('dashboard') }}" class="inline-block bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-bold px-6 py-3 rounded-lg transition shadow-md">
                Browse More Jobs
            </a>
        </div>
    </div>
</div>
@endsection