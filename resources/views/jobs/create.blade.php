<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-blue-100 rounded-lg">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Create New Job</h2>
                <p class="text-sm text-gray-500 mt-0.5">Fill in the details to post a new job opening</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <form action="/jobs" method="POST" class="space-y-6 p-6 sm:p-8">
                @csrf

                <!-- Job Title -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Job Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="title"
                           placeholder="e.g., Senior Laravel Developer"
                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    <p class="text-xs text-gray-400 mt-1">Enter a clear and descriptive job title</p>
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Description <span class="text-red-500">*</span>
                    </label>
                    <textarea name="description" 
                              rows="6"
                              placeholder="Describe the role, responsibilities, and requirements..."
                              class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 resize-none"></textarea>
                    <p class="text-xs text-gray-400 mt-1">Provide a detailed job description</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Company -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Company <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="company"
                               placeholder="Company name"
                               class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    </div>

                    <!-- Salary -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Salary <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">₱</span>
                            <input type="number" 
                                   name="salary"
                                   placeholder="0"
                                   class="w-full border border-gray-300 rounded-xl pl-8 pr-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center gap-4 pt-6 border-t border-gray-200">
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl font-semibold transition-all duration-200 shadow-sm hover:shadow-md">
                        Create Job Listing
                    </button>
                    <a href="/jobs" 
                       class="text-gray-600 hover:text-gray-800 font-medium transition-colors duration-200">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>