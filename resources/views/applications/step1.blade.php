@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="mb-8">
            <div class="flex justify-between mb-2">
                <span class="text-sm font-semibold text-red-600">Step 1 of 3</span>
                <span class="text-sm text-gray-500">Personal Information</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2.5">
                <div class="bg-gradient-to-r from-red-600 to-red-700 h-2.5 rounded-full" style="width: 33%"></div>
            </div>
        </div>

        <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                <span class="text-2xl">📝</span>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Apply for: {{ $job->title }}</h1>
                <p class="text-gray-500">📍 {{ $job->location }} | 💰 ₱{{ number_format($job->salary, 2) }}</p>
            </div>
        </div>

        <form action="{{ route('applications.postStep1', $job->id) }}" method="POST">
            @csrf
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name *</label>
                    <input type="text" name="full_name" required 
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition"
                           placeholder="Juan Dela Cruz">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address *</label>
                    <input type="email" name="email" required 
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition"
                           placeholder="juan@sweetwen.com">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Phone Number *</label>
                    <input type="tel" name="phone" required 
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition"
                           placeholder="09123456789">
                </div>
            </div>
            
            <div class="flex justify-between mt-8 pt-4 border-t">
                <a href="{{ route('dashboard') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-3 rounded-lg font-semibold transition text-center">
                    Cancel
                </a>
                <button type="submit" class="bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white px-8 py-3 rounded-lg font-semibold transition shadow-md">
                    Next Step →
                </button>
            </div>
        </form>
    </div>
</div>
@endsection