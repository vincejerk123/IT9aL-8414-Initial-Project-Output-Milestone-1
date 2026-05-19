<?php
namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Application;
use App\Models\Resignation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    // ==================== USER APPLICATION METHODS ====================
    
    public function start(Job $job)
    {
        $user = auth()->user();
        
        // Check if user is hired (cannot apply for any job)
        if ($user->isHired()) {
            return redirect()->route('dashboard')
                ->with('error', 'You are currently hired at Sweetwen. You cannot apply for other positions while employed.');
        }
        
        // Check if user has a pending resignation
        if ($user->hasPendingResignation()) {
            return redirect()->route('dashboard')
                ->with('error', 'You have a pending resignation request. Please wait for admin approval.');
        }
        
        // Check if user is not eligible to apply
        if (!$user->canApply()) {
            return redirect()->route('dashboard')
                ->with('error', 'You are not eligible to apply for positions at this time.');
        }
        
        // Check pending applications limit (max 3)
        $pendingCount = Application::where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();
        
        if ($pendingCount >= 3) {
            return redirect()->route('dashboard')
                ->with('error', 'You have ' . $pendingCount . ' pending applications. Please wait for results before applying to more positions. (Maximum 3 pending applications allowed)');
        }
        
        // Check if job is closed or hiring complete
        if ($job->status == 'closed') {
            return redirect()->route('dashboard')
                ->with('error', 'This position is no longer accepting applications.');
        }

        if ($job->isHiringComplete()) {
            return redirect()->route('dashboard')
                ->with('error', 'This position has already filled all available slots. Check back for future openings!');
        }
        
        // Check for existing application (pending or approved)
        $existingApplication = Application::where('user_id', auth()->id())
            ->where('job_id', $job->id)
            ->whereIn('status', ['pending', 'approved'])
            ->first();
            
        if ($existingApplication) {
            if ($existingApplication->status == 'pending') {
                return redirect()->route('applications.status', ['application' => $existingApplication->id])
                    ->with('error', 'You already have a pending application for this position!');
            } elseif ($existingApplication->status == 'approved') {
                return redirect()->route('dashboard')
                    ->with('error', 'You are currently hired for this position!');
            }
        }
        
        // Check for incomplete application (status is null)
        $incomplete = Application::where('user_id', auth()->id())
            ->where('job_id', $job->id)
            ->whereNull('status')
            ->first();
            
        if ($incomplete) {
            if ($incomplete->current_step == 2) {
                return redirect()->route('applications.step2', ['application' => $incomplete->id]);
            } elseif ($incomplete->current_step == 3) {
                return redirect()->route('applications.step3', ['application' => $incomplete->id]);
            }
        }
        
        // Delete any old rejected or completed applications to allow fresh start
        $oldApplications = Application::where('user_id', auth()->id())
            ->where('job_id', $job->id)
            ->whereIn('status', ['rejected', 'completed'])
            ->get();
            
        foreach ($oldApplications as $oldApp) {
            if ($oldApp->resume_path && Storage::disk('public')->exists($oldApp->resume_path)) {
                Storage::disk('public')->delete($oldApp->resume_path);
            }
            $oldApp->forceDelete();
        }
        
        // Create a new application with user's existing data (skip step 1)
        $application = Application::create([
            'user_id' => $user->id,
            'job_id' => $job->id,
            'full_name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone ?? '',
            'current_step' => 2,
            'status' => null
        ]);

        return redirect()->route('applications.step2', ['application' => $application->id]);
    }

    public function step2(Application $application)
    {
        if ($application->user_id != auth()->id()) abort(403);
        $application->refresh();
        return view('applications.step2', compact('application'));
    }

    public function postStep2(Request $request, Application $application)
    {
        if ($application->user_id != auth()->id()) abort(403);

        $request->validate([
            'experience' => 'required|string|min:50',
        ]);

        $application->update([
            'experience' => $request->experience,
            'current_step' => 3
        ]);

        return redirect()->route('applications.step3', ['application' => $application->id]);
    }

    public function step3(Application $application)
    {
        if ($application->user_id != auth()->id()) abort(403);
        $application->refresh();
        return view('applications.step3', compact('application'));
    }

    public function postStep3(Request $request, Application $application)
    {
        if ($application->user_id != auth()->id()) abort(403);

        $request->validate([
            'resume' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        if ($application->resume_path && Storage::disk('public')->exists($application->resume_path)) {
            Storage::disk('public')->delete($application->resume_path);
        }

        $path = $request->file('resume')->store('resumes', 'public');

        $application->update([
            'resume_path' => $path,
            'current_step' => 4,
            'status' => 'pending'
        ]);

        return redirect()->route('applications.complete', ['application' => $application->id]);
    }

    public function complete(Application $application)
    {
        if ($application->user_id != auth()->id()) abort(403);
        return view('applications.complete', compact('application'));
    }

    public function status(Application $application)
    {
        if ($application->user_id != auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }
        return view('applications.status', compact('application'));
    }

    public function cancel(Application $application)
{
    if ($application->user_id != auth()->id()) abort(403);
    
    // Allow cancellation for incomplete OR pending applications
    if ($application->status === null || $application->status == 'pending') {
        // Delete the resume file if exists
        if ($application->resume_path && Storage::disk('public')->exists($application->resume_path)) {
            Storage::disk('public')->delete($application->resume_path);
        }
        
        $application->forceDelete();
        
        return redirect()->route('dashboard')
            ->with('success', 'Your application has been cancelled successfully.');
    }
    
    return redirect()->route('dashboard')
        ->with('error', 'Cannot cancel a ' . $application->status . ' application.');
}

    // ==================== RESIGNATION METHODS ====================

    public function submitResignation(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->isHired()) {
            return redirect()->route('dashboard')
                ->with('error', 'You are not currently hired at Sweetwen.');
        }
        
        $existingPending = Resignation::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();
        
        if ($existingPending) {
            return redirect()->route('dashboard')
                ->with('error', 'You already have a pending resignation request. Please wait for admin approval.');
        }
        
        $request->validate([
            'reason' => 'required|string|min:10|max:1000',
            'exit_interview_reason' => 'required|string|max:255',
            'last_day' => 'required|date|after_or_equal:today',
            'feedback' => 'nullable|string|max:1000',
        ]);
        
        Resignation::create([
            'user_id' => $user->id,
            'job_id' => $user->hired_position_id,
            'reason' => $request->reason,
            'exit_interview_reason' => $request->exit_interview_reason,
            'feedback' => $request->feedback,
            'last_day' => $request->last_day,
            'status' => 'pending',
        ]);
        
        return redirect()->route('dashboard')
            ->with('success', 'Your resignation request has been submitted successfully. HR will review it shortly.');
    }

    public function manageResignations()
    {
        if (!auth()->user()->isAdmin()) abort(403);
        
        $resignations = Resignation::with(['user', 'job'])->latest()->get();
        $pendingCount = $resignations->where('status', 'pending')->count();
        
        return view('admin.resignations', compact('resignations', 'pendingCount'));
    }

    public function approveResignation(Request $request, Resignation $resignation)
    {
        if (!auth()->user()->isAdmin()) abort(403);
        
        if ($request->has('clearance_notes')) {
            $resignation->update(['clearance_notes' => $request->clearance_notes]);
        }
        
        $resignation->approve();
        
        return redirect()->route('admin.resignations')
            ->with('success', 'Resignation approved. User can now apply for new positions.');
    }

    public function rejectResignation(Request $request, Resignation $resignation)
    {
        if (!auth()->user()->isAdmin()) abort(403);
        
        $request->validate([
            'admin_notes' => 'nullable|string|max:500',
        ]);
        
        $resignation->update([
            'admin_notes' => $request->admin_notes,
        ]);
        $resignation->reject();
        
        return redirect()->route('admin.resignations')
            ->with('success', 'Resignation rejected. User remains hired.');
    }

    public function completeClearance(Resignation $resignation)
    {
        if (!auth()->user()->isAdmin()) abort(403);
        
        $resignation->completeClearance();
        
        return back()->with('success', 'Clearance marked as completed for this resignation.');
    }

    // ==================== ADMIN APPLICATION MANAGEMENT ====================

    public function manage(Request $request)
    {
        if (!auth()->user()->isAdmin()) abort(403);
        
        $query = Application::with(['user', 'job'])->whereNotNull('status');
        
        // Filter by job_id if provided
        if ($request->has('job_id') && $request->job_id) {
            $query->where('job_id', $request->job_id);
        }
        
        $applications = $query->latest()->get();
        $archivedCount = Application::archived()->count();
        
        return view('admin.applications', compact('applications', 'archivedCount'));
    }

    public function archived()
    {
        if (!auth()->user()->isAdmin()) abort(403);
        
        $archivedApplications = Application::with(['user', 'job'])
            ->archived()
            ->latest('archived_at')
            ->get();
        
        return view('admin.archived', compact('archivedApplications'));
    }

    public function archiveApplication(Application $application)
    {
        if (!auth()->user()->isAdmin()) abort(403);
        $application->archive();
        return redirect()->route('admin.applications')
            ->with('success', 'Application #' . $application->id . ' archived successfully!');
    }

    public function restoreApplication(Application $application)
    {
        if (!auth()->user()->isAdmin()) abort(403);
        $application->restore();
        return redirect()->route('admin.archived')
            ->with('success', 'Application #' . $application->id . ' restored successfully!');
    }

    public function deleteArchived(Application $application)
    {
        if (!auth()->user()->isAdmin()) abort(403);
        
        if ($application->status == 'approved') {
            $job = $application->job;
            $job->decrement('approved_count');
            if ($job->status == 'closed' && $job->slots > 0 && $job->approved_count < $job->slots) {
                $job->status = 'open';
                $job->save();
            }
        }
        
        if ($application->resume_path && Storage::disk('public')->exists($application->resume_path)) {
            Storage::disk('public')->delete($application->resume_path);
        }
        $application->forceDelete();
        
        return redirect()->route('admin.archived')
            ->with('success', 'Application #' . $application->id . ' permanently deleted!');
    }

    public function destroy(Application $application)
    {
        if (!auth()->user()->isAdmin()) abort(403);
        
        if ($application->status == 'approved') {
            $job = $application->job;
            $job->decrement('approved_count');
            if ($job->status == 'closed' && $job->slots > 0 && $job->approved_count < $job->slots) {
                $job->status = 'open';
                $job->save();
            }
        }
        
        if ($application->resume_path && Storage::disk('public')->exists($application->resume_path)) {
            Storage::disk('public')->delete($application->resume_path);
        }
        $application->forceDelete();
        
        return redirect()->route('admin.applications')
            ->with('success', 'Application #' . $application->id . ' deleted successfully!');
    }

    public function updateStatus(Request $request, Application $application)
    {
        if (!auth()->user()->isAdmin()) abort(403);
        
        $request->validate([
            'status' => 'required|in:pending,approved,rejected,completed'
        ]);

        $job = $application->job;
        $oldStatus = $application->status;
        $user = $application->user;
        
        if ($request->status == 'approved' && $oldStatus != 'approved') {
            if (!$job->canApproveMore()) {
                return back()->with('error', 'Cannot approve more applicants. This position has reached its limit of ' . $job->slots . ' approved applicants.');
            }
            $job->increment('approved_count');
            $job->checkAndAutoClose();
            $user->hireForPosition($job);
        }
        
        if ($request->status == 'completed' && $oldStatus == 'approved') {
            $job->decrement('approved_count');
            if ($job->status == 'closed' && $job->slots > 0 && $job->approved_count < $job->slots) {
                $job->status = 'open';
                $job->save();
            }
            $user->releaseFromEmployment();
        }
        
        if (($oldStatus == 'approved' && $request->status == 'pending') || ($oldStatus == 'approved' && $request->status == 'rejected')) {
            $job->decrement('approved_count');
            if ($job->status == 'closed' && $job->slots > 0 && $job->approved_count < $job->slots) {
                $job->status = 'open';
                $job->save();
            }
            if ($user->hired_position_id == $job->id) {
                $user->releaseFromEmployment();
            }
        }
        
        $application->update(['status' => $request->status]);
        
        $statusMessages = [
            'pending' => 'Pending',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'completed' => 'Completed'
        ];
        
        $message = 'Application status changed from ' . $statusMessages[$oldStatus] . ' to ' . $statusMessages[$request->status];
        
        if ($request->status == 'approved' && $job->isHiringComplete() && $job->status == 'closed') {
            $message .= ' This position is now FULL and has been automatically closed.';
        }
        
        if ($request->status == 'approved') {
            $message .= ' User has been marked as HIRED for this position.';
        }
        
        if ($request->status == 'completed') {
            $message .= ' User has been marked as RESIGNED. A slot has been freed up.';
        }
        
        return back()->with('success', $message);
    }

    public function archiveAllRejected()
    {
        if (!auth()->user()->isAdmin()) abort(403);
        $count = Application::where('status', 'rejected')->active()->count();
        Application::where('status', 'rejected')->active()->get()->each->archive();
        return redirect()->route('admin.applications')
            ->with('success', $count . ' rejected applications archived successfully!');
    }

    public function archiveOldApplications(Request $request)
    {
        if (!auth()->user()->isAdmin()) abort(403);
        $months = $request->input('months', 3);
        $date = now()->subMonths($months);
        $count = Application::where('created_at', '<', $date)
            ->where('status', '!=', 'pending')
            ->active()
            ->count();
        Application::where('created_at', '<', $date)
            ->where('status', '!=', 'pending')
            ->active()
            ->get()
            ->each->archive();
        return redirect()->route('admin.applications')
            ->with('success', $count . ' old applications (older than ' . $months . ' months) archived successfully!');
    }
}