<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CareerOpportunityCandidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'career_opportunity_id',
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
     * Get the career opportunity that this candidate applied for.
     */
    public function careerOpportunity(): BelongsTo
    {
        return $this->belongsTo(CareerOpportunity::class);
    }
}