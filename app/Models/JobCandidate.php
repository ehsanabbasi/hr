<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobCandidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'name',
        'gender',
        'birthday',
        'email',
        'phone',
        'resume_path',
        'status',
        'notes',
    ];

    protected $casts = [
        'birthday' => 'date',
    ];

    /**
     * Get the job that this candidate applied for.
     */
    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }
}