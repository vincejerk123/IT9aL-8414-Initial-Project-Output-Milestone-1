<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index()
    {
        $jobs = Job::all();
        return view('jobs.index', compact('jobs'));
    }

    public function create()
{
    if(auth()->user()->role != 'admin'){
        abort(403);
    }

    return view('jobs.create');
}

    public function store(Request $request)
{
    if(auth()->user()->role != 'admin'){
        abort(403);
    }

    Job::create($request->all());
    return redirect('/jobs');
}
    public function show(Job $job)
    {
        return view('jobs.show', compact('job'));
    }

    public function edit(Job $job)
{
    if(auth()->user()->role != 'admin'){
        abort(403);
    }

    return view('jobs.edit', compact('job'));
}

    public function update(Request $request, Job $job)
{
    if(auth()->user()->role != 'admin'){
        abort(403);
    }

    $job->update($request->all());
    return redirect('/jobs');
}

    public function destroy(Job $job)
{
    if(auth()->user()->role != 'admin'){
        abort(403);
    }

    $job->delete();
    return redirect('/jobs');
}
}