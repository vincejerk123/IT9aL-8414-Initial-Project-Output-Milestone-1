<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'employment_status',
        'hired_position_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function jobs()
    {
        return $this->hasMany(Job::class, 'employer_id');
    }

    public function appliedJobs()
    {
        return $this->belongsToMany(Job::class, 'applications', 'user_id', 'job_id')
                    ->withPivot('status', 'cover_letter', 'resume', 'date_applied')
                    ->withTimestamps();
    }

    public function hiredPosition()
    {
        return $this->belongsTo(Job::class, 'hired_position_id');
    }

    public function resignations()
    {
        return $this->hasMany(Resignation::class);
    }

    public function pendingResignation()
    {
        return $this->hasOne(Resignation::class)->where('status', 'pending');
    }

    public function hasPendingResignation()
    {
        return $this->resignations()->where('status', 'pending')->exists();
    }

    public function canApply()
    {
        // Cannot apply if hired and no pending resignation
        if ($this->employment_status === 'hired' && !$this->hasPendingResignation()) {
            return false;
        }
        
        // Cannot apply if there's a pending resignation
        if ($this->hasPendingResignation()) {
            return false;
        }
        
        return in_array($this->employment_status, ['applicant', 'rejected', 'not_employed']);
    }

    public function isHired()
    {
        return $this->employment_status === 'hired';
    }

    public function hireForPosition(Job $job)
    {
        $this->update([
            'employment_status' => 'hired',
            'hired_position_id' => $job->id,
        ]);
    }

    public function releaseFromEmployment()
    {
        $this->update([
            'employment_status' => 'not_employed',
            'hired_position_id' => null,
        ]);
    }
}