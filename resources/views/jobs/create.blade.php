@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Create New Job Posting</h1>
                <p class="text-gray-500">Fill in the details to post a new job opening at Sweetwen</p>
            </div>
        </div>
        
        <form action="{{ route('jobs.store') }}" method="POST">
            @csrf
            
            <div class="space-y-4">
                <!-- Position Title -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Position Title *</label>
                    <select name="title" required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition">
                        <option value="">-- Select Position --</option>
                        <option>Service Crew</option>
                        <option>Cashier</option>
                        <option>Kitchen Crew</option>
                        <option>Shift Supervisor</option>
                        <option>Assistant Manager</option>
                        <option>Restaurant Manager</option>
                        <option>Delivery Rider</option>
                        <option>Maintenance Staff</option>
                        <option>HR Officer</option>
                        <option>Store Manager</option>
                    </select>
                </div>
                
                <!-- Description -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Job Description *</label>
                    <textarea name="description" rows="6" required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition resize-none" placeholder="Describe the role, responsibilities, requirements, and benefits..."></textarea>
                    <p class="text-xs text-gray-400 mt-1">Provide a detailed job description to attract qualified candidates</p>
                </div>
                
                <!-- Location Dropdown (Fixed) -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Location *</label>
                    <select name="location" required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition">
                        <option value="">-- Select Location --</option>
                        <option value="Metro Manila - Quezon City">Metro Manila - Quezon City</option>
                        <option value="Metro Manila - Manila">Metro Manila - Manila</option>
                        <option value="Metro Manila - Makati">Metro Manila - Makati</option>
                        <option value="Metro Manila - Taguig">Metro Manila - Taguig</option>
                        <option value="Metro Manila - Pasig">Metro Manila - Pasig</option>
                        <option value="Metro Manila - Mandaluyong">Metro Manila - Mandaluyong</option>
                        <option value="Metro Manila - Parañaque">Metro Manila - Parañaque</option>
                        <option value="Metro Manila - Las Piñas">Metro Manila - Las Piñas</option>
                        <option value="Metro Manila - Muntinlupa">Metro Manila - Muntinlupa</option>
                        <option value="Metro Manila - Marikina">Metro Manila - Marikina</option>
                        <option value="Metro Manila - Pasay">Metro Manila - Pasay</option>
                        <option value="Metro Manila - Caloocan">Metro Manila - Caloocan</option>
                        <option value="Metro Manila - Malabon">Metro Manila - Malabon</option>
                        <option value="Metro Manila - Navotas">Metro Manila - Navotas</option>
                        <option value="Metro Manila - Valenzuela">Metro Manila - Valenzuela</option>
                        <option value="Metro Manila - San Juan">Metro Manila - San Juan</option>
                        <option value="Luzon - Bulacan">Luzon - Bulacan</option>
                        <option value="Luzon - Cavite">Luzon - Cavite</option>
                        <option value="Luzon - Laguna">Luzon - Laguna</option>
                        <option value="Luzon - Rizal">Luzon - Rizal</option>
                        <option value="Luzon - Pampanga">Luzon - Pampanga</option>
                        <option value="Luzon - Batangas">Luzon - Batangas</option>
                        <option value="Visayas - Cebu">Visayas - Cebu</option>
                        <option value="Visayas - Iloilo">Visayas - Iloilo</option>
                        <option value="Visayas - Bacolod">Visayas - Bacolod</option>
                        <option value="Mindanao - Davao">Mindanao - Davao</option>
                        <option value="Mindanao - Cagayan de Oro">Mindanao - Cagayan de Oro</option>
                        <option value="Mindanao - Zamboanga">Mindanao - Zamboanga</option>
                        <option value="Mindanao - General Santos">Mindanao - General Santos</option>
                        <option value="Mindanao - Butuan">Mindanao - Butuan</option>
                    </select>
                </div>
                
                <!-- Salary -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Monthly Salary (₱)</label>
                    <input type="number" name="salary" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition" placeholder="e.g., 15000">
                </div>
                
                <!-- Number of Slots -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Number of Slots <span class="text-gray-400 text-xs font-normal">(Optional)</span></label>
                    <input type="number" name="slots" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition" placeholder="e.g., 5 (leave empty for unlimited)">
                    <p class="text-xs text-gray-400 mt-1">Set a limit if you only need a specific number of hires. Leave empty for unlimited applicants.</p>
                </div>
            </div>
            
            <div class="flex justify-between gap-4 mt-8 pt-6 border-t">
                <a href="{{ route('jobs.index') }}" class="inline-block bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-6 py-3 rounded-lg transition text-center">
                    Cancel
                </a>
                <button type="submit" class="inline-block bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-bold px-8 py-3 rounded-lg transition shadow-md">
                    Create Job Posting
                </button>
            </div>
        </form>
    </div>
</div>
@endsection