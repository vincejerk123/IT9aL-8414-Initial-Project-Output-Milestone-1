<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    // Specify the correct table name
    protected $table = 'jobs_table';

    protected $fillable = [
        'title',
        'description',
        'company',
        'salary'
    ];

    public function applications()
    {
        return $this->hasMany(Application::class, 'job_id');
    }

    public function hasUserApplied($userId)
    {
        return $this->applications()->where('user_id', $userId)->exists();
    }

    public function getUserApplication($userId)
    {
        return $this->applications()->where('user_id', $userId)->first();
    }
}