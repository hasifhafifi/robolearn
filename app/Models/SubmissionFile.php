<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmissionFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'submissionID',
        'userID',
        'groupID',
        'submittedFileName',
        'submittedFileContent',
    ];

    public function submission()
    {
        return $this->belongsTo(Submission::class, 'submissionID');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userID');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'groupID');
    }
}
