<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resignation extends Model
{
    protected $fillable = [
        'user_id', 'job_id', 'reason', 'exit_interview_reason', 'feedback', 
        'last_day', 'clearance_notes', 'clearance_completed', 'clearance_completed_at',
        'admin_notes', 'status', 'responded_at'
    ];

    protected $casts = [
        'responded_at' => 'datetime',
        'clearance_completed_at' => 'datetime',
        'last_day' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }

    public function approve()
    {
        $this->update([
            'status' => 'approved',
            'responded_at' => now(),
        ]);
        
        $this->user->releaseFromEmployment();
        
        $application = Application::where('user_id', $this->user_id)
            ->where('job_id', $this->job_id)
            ->where('status', 'approved')
            ->first();
        
        if ($application) {
            $application->update(['status' => 'completed']);
            
            $job = $this->job;
            if ($job) {
                $job->decrement('approved_count');
                if ($job->status == 'closed' && $job->slots > 0 && $job->approved_count < $job->slots) {
                    $job->status = 'open';
                    $job->save();
                }
            }
        }
    }

    public function reject()
    {
        $this->update([
            'status' => 'rejected',
            'responded_at' => now(),
        ]);
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }
    
    public function completeClearance()
    {
        $this->update([
            'clearance_completed' => true,
            'clearance_completed_at' => now(),
        ]);
    }
}