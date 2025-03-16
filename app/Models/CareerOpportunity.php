<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CareerOpportunity extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'department_id',
        'level',
        'is_active',
    ];

    /**
     * Get the department that this career opportunity belongs to.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the candidates for this career opportunity.
     */
    public function candidates(): HasMany
    {
        return $this->hasMany(CareerOpportunityCandidate::class);
    }

    /**
     * Get the users assigned to review this career opportunity.
     */
    public function reviewers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'career_opportunity_reviewers')
            ->withTimestamps();
    }
}
