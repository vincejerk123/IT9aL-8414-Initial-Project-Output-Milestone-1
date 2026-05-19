<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $table = 'job_listings';
    
    protected $fillable = ['title', 'description', 'location', 'salary', 'status', 'slots', 'approved_count'];

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function hasUserApplied($userId)
    {
        // Only count pending, approved applications as "applied"
        // Exclude rejected and completed applications
        return $this->applications()
            ->where('user_id', $userId)
            ->whereIn('status', ['pending', 'approved'])
            ->exists();
    }

    public function getUserApplication($userId)
    {
        return $this->applications()->where('user_id', $userId)->first();
    }

    public function isHiringComplete()
    {
        return $this->slots > 0 && $this->approved_count >= $this->slots;
    }

    public function getAvailableSlots()
    {
        if ($this->slots <= 0) return null;
        return max(0, $this->slots - $this->approved_count);
    }

    public function canApproveMore()
    {
        if ($this->slots <= 0) return true;
        return $this->approved_count < $this->slots;
    }

    public function checkAndAutoClose()
    {
        if ($this->isHiringComplete() && $this->status == 'open') {
            $this->status = 'closed';
            $this->save();
            return true;
        }
        return false;
    }
}