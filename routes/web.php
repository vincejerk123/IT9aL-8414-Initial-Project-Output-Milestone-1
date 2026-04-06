<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ApplicationController;
use Illuminate\Support\Facades\Route;

// Home Route
Route::get('/', function () {
    return view('home');
})->name('home');

// Authenticated Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Job Routes
    Route::resource('jobs', JobController::class);
    
    // Application Routes
    Route::post('/jobs/{job}/apply', [ApplicationController::class, 'apply'])->name('applications.apply');
    Route::get('/my-applications', [ApplicationController::class, 'myApplications'])->name('applications.my');
    
    // Admin-only application management routes
    Route::middleware(['auth'])->group(function () {
        Route::get('/manage-applications', [ApplicationController::class, 'manageApplications'])->name('applications.manage');
        Route::put('/applications/{application}/status', [ApplicationController::class, 'updateStatus'])->name('applications.update-status');
    });
});

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';