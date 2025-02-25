<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmissionFile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'submission_id',
        'path',
        'original_filename',
        'mime_type',
        'size'
    ];

    /**
     * Get the submission that owns the file.
     */
    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }
}
