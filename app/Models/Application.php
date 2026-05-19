<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = [
        'user_id', 'job_id', 'full_name', 'email', 'phone', 
        'experience', 'resume_path', 'status', 'current_step', 'archived_at'
    ];

    protected $casts = [
        'archived_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }

    // Archive methods
    public function archive()
    {
        $this->update(['archived_at' => now()]);
    }

    public function restore()
    {
        $this->update(['archived_at' => null]);
    }

    public function isArchived()
    {
        return !is_null($this->archived_at);
    }

    public function scopeActive($query)
    {
        return $query->whereNull('archived_at');
    }

    public function scopeArchived($query)
    {
        return $query->whereNotNull('archived_at');
    }
}