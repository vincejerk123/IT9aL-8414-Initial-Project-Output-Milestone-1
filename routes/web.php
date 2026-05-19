<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ApplicationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::middleware(['auth'])->group(function () {
    
    // Role-based Dashboard
    Route::get('/dashboard', function () {
        if (auth()->user()->isAdmin()) {
            return view('admin.dashboard');
        }
        return view('dashboard');
    })->name('dashboard');
    
    // Job Routes (Admin only)
    Route::resource('jobs', JobController::class);
    Route::post('/jobs/{job}/toggle', [JobController::class, 'toggleStatus'])->name('jobs.toggle');
    
    // Application Routes (Multi-step for Users)
    Route::get('/apply/{job}', [ApplicationController::class, 'start'])->name('applications.start');
    Route::get('/apply/step1/{application}', [ApplicationController::class, 'step1'])->name('applications.step1');
    Route::post('/apply/step1/{application}', [ApplicationController::class, 'updateStep1'])->name('applications.updateStep1');
    Route::post('/apply/{job}/step1', [ApplicationController::class, 'postStep1'])->name('applications.postStep1');
    Route::get('/apply/step2/{application}', [ApplicationController::class, 'step2'])->name('applications.step2');
    Route::post('/apply/step2/{application}', [ApplicationController::class, 'postStep2'])->name('applications.postStep2');
    Route::get('/apply/step3/{application}', [ApplicationController::class, 'step3'])->name('applications.step3');
    Route::post('/apply/step3/{application}', [ApplicationController::class, 'postStep3'])->name('applications.postStep3');
    Route::get('/apply/complete/{application}', [ApplicationController::class, 'complete'])->name('applications.complete');
    Route::get('/application/{application}/status', [ApplicationController::class, 'status'])->name('applications.status');
    
    // Cancel incomplete application
    Route::delete('/application/{application}/cancel', [ApplicationController::class, 'cancel'])->name('applications.cancel');
    
    // ==================== RESIGNATION ROUTES ====================
    Route::post('/resign/submit', [ApplicationController::class, 'submitResignation'])->name('applications.resign.submit');
    Route::get('/admin/resignations', [ApplicationController::class, 'manageResignations'])->name('admin.resignations');
    Route::post('/admin/resignation/{resignation}/approve', [ApplicationController::class, 'approveResignation'])->name('admin.resignation.approve');
    Route::post('/admin/resignation/{resignation}/reject', [ApplicationController::class, 'rejectResignation'])->name('admin.resignation.reject');
    
    // ==================== ADMIN ROUTES ====================
    
    // Application Management
    Route::get('/admin/applications', [ApplicationController::class, 'manage'])->name('admin.applications');
    Route::put('/admin/application/{application}/status', [ApplicationController::class, 'updateStatus'])->name('admin.updateStatus');
    Route::delete('/admin/application/{application}', [ApplicationController::class, 'destroy'])->name('admin.application.destroy');
    
    // Archive Routes
    Route::get('/admin/archived', [ApplicationController::class, 'archived'])->name('admin.archived');
    Route::delete('/admin/application/{application}/archive', [ApplicationController::class, 'archiveApplication'])->name('admin.archive');
    Route::put('/admin/application/{application}/restore', [ApplicationController::class, 'restoreApplication'])->name('admin.restore');
    Route::delete('/admin/application/{application}/delete', [ApplicationController::class, 'deleteArchived'])->name('admin.deleteArchived');

    Route::post('/admin/resignation/{resignation}/clearance', [ApplicationController::class, 'completeClearance'])->name('admin.resignation.clearance');
    });

require __DIR__.'/auth.php';