<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\table_jobs_uploading;
use App\Models\User;

class user_application extends Model
{
    //
    use HasFactory;
    protected $table = 'user_application';
    protected $fillable = [
        'applicant',
        'jobApplied',
        'appliedAt',
        'name',
        'experience',
        'contactNumber',
        'resumeLink',
        'expectations'


    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'applicant');
    }

    // Relationship to Job model
    public function job()
    {
        return $this->belongsTo(table_jobs_uploading::class, 'jobApplied');
    }
}
