<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;


class table_jobs_uploading extends Model
{

    //
    use HasFactory;
    protected $table = 'table_jobs_uploading';
    protected $fillable = [
        'jobTitle',
        'employmentType',
        'location',
        'salary',
        'description',
        'creator',
        'status',
        'companyName',
        'requirements',
    ];
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator');
    }
}
