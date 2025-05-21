<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\table_jobs_uploading;
use App\Models\User;

class job_application extends Model
{
    use HasFactory;

    protected $table = 'job_application';

    protected $fillable = [
        'applicant',
        'jobApplied',
        'appliedAt',
        'status',
    ];

    // Relationship to the job
    public function job()
    {
        return $this->belongsTo(table_jobs_uploading::class, 'jobApplied');
    }

    // Relationship to the applicant
    public function applicant()
    {
        return $this->belongsTo(User::class, 'applicant');
    }
}
