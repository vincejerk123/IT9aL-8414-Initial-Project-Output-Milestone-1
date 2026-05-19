<?php
namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index()
    {
        if (!auth()->user()->isAdmin()) abort(403);
        $jobs = Job::latest()->get();
        return view('jobs.index', compact('jobs'));
    }

    public function create()
    {
        if (!auth()->user()->isAdmin()) abort(403);
        return view('jobs.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) abort(403);
        
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'location' => 'required',
            'salary' => 'nullable|numeric',
            'slots' => 'nullable|integer|min:0',
        ]);

        Job::create([
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'salary' => $request->salary,
            'slots' => $request->slots ?? 0,
            'status' => 'open'
        ]);
        
        return redirect()->route('jobs.index')->with('success', 'Job created successfully!');
    }

    public function edit(Job $job)
    {
        if (!auth()->user()->isAdmin()) abort(403);
        return view('jobs.edit', compact('job'));
    }

    public function update(Request $request, Job $job)
    {
        if (!auth()->user()->isAdmin()) abort(403);
        
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'location' => 'required',
            'salary' => 'nullable|numeric',
            'slots' => 'nullable|integer|min:0',
        ]);

        // Check if new slots is less than already approved count
        if ($request->slots < $job->approved_count && $request->slots > 0) {
            return back()->with('error', 'Cannot set slots lower than already approved applicants (' . $job->approved_count . ' approved).');
        }

        $oldStatus = $job->status;
        $oldApprovedCount = $job->approved_count;
        $oldSlots = $job->slots;
        
        $job->update([
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'salary' => $request->salary,
            'slots' => $request->slots ?? 0,
        ]);
        
        $newSlots = $job->slots;
        
        // FIX: If slots were increased and job was closed due to being full, re-open it
        if ($oldStatus == 'closed' && $newSlots > 0 && $job->approved_count < $newSlots) {
            $job->status = 'open';
            $job->save();
            $message = 'Job updated successfully! The job has been automatically re-opened because slots were increased.';
        } 
        // Check if job was open but is now full, close it
        else if ($job->status == 'open' && $job->isHiringComplete()) {
            $job->status = 'closed';
            $job->save();
            $message = 'Job updated successfully! This position is now FULL and has been automatically closed.';
        } 
        else {
            $message = 'Job updated successfully!';
        }
        
        return redirect()->route('jobs.index')->with('success', $message);
    }

    public function destroy(Job $job)
    {
        if (!auth()->user()->isAdmin()) abort(403);
        
        // Delete all associated applications and their resumes
        foreach ($job->applications as $app) {
            if ($app->resume_path && \Storage::disk('public')->exists($app->resume_path)) {
                \Storage::disk('public')->delete($app->resume_path);
            }
            $app->forceDelete();
        }
        
        $job->delete();
        return redirect()->route('jobs.index')->with('success', 'Job deleted!');
    }

    public function toggleStatus(Job $job)
    {
        if (!auth()->user()->isAdmin()) abort(403);
        
        // Prevent opening a job that is full (approved count >= slots)
        if ($job->status == 'closed' && $job->isHiringComplete()) {
            return back()->with('error', 'Cannot open this job because all slots are already filled (' . $job->approved_count . '/' . $job->slots . '). Please increase slots first.');
        }
        
        $job->status = $job->status === 'open' ? 'closed' : 'open';
        $job->save();
        
        return back()->with('success', 'Job status updated to ' . ucfirst($job->status) . '!');
    }
}