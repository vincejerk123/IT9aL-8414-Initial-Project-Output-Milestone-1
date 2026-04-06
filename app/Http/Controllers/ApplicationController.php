<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function apply(Request $request, Job $job)
    {
        // Check if user already applied
        $existingApplication = Application::where('user_id', Auth::id())
            ->where('job_id', $job->id)
            ->first();

        if ($existingApplication) {
            return redirect()->back()->with('error', 'You have already applied for this job!');
        }

        // Validate the request
        $request->validate([
            'cover_letter' => 'nullable|string|max:1000',
        ]);

        // Create application
        Application::create([
            'user_id' => Auth::id(),
            'job_id' => $job->id,
            'cover_letter' => $request->cover_letter,
            'status' => 'pending'
        ]);

        return redirect()->back()->with('success', 'Application submitted successfully!');
    }

    public function myApplications()
    {
        $applications = Application::with('job')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('applications.index', compact('applications'));
    }

    // Admin functions
    public function manageApplications()
    {
        $applications = Application::with(['user', 'job'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('applications.manage', compact('applications'));
    }

    public function updateStatus(Request $request, Application $application)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,declined'
        ]);

        $application->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Application status updated successfully!');
    }
}