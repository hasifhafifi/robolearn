<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    protected $fillable = [
        'submissionName',
        'submissionDesc',
        'submissionType',
        'duedate',
        'duetime',
        'documentID',
        'classID',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class, 'documentID');
    }
}
