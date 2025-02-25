<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Submission extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'message',
        'street',
        'state',
        'zip',
        'country'
    ];

    /**
     * Get the images for the submission.
     */
    public function images()
    {
        return $this->hasMany(SubmissionImage::class);
    }

    /**
     * Get the files for the submission.
     */
    public function files()
    {
        return $this->hasMany(SubmissionFile::class);
    }
}
