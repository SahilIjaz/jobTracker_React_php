<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class jobModel extends Model
{
    //
    use HasFactory;
    protected $table = 'postejob';
    protected $fillable = [
        'jobTitle',
        'employmentType',
        'location',
        'salary',
        'description',
        'applicant',
        'creator',
        'status'
    ];

}
