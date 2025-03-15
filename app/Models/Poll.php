<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Poll extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'is_mandatory',
        'is_active',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'is_mandatory' => 'boolean',
        'is_active' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function options(): HasMany
    {
        return $this->hasMany(PollOption::class);
    }

    public function responses(): HasMany
    {
        return $this->hasMany(PollResponse::class);
    }

    public function hasUserResponded(User $user): bool
    {
        return $this->responses()->where('user_id', $user->id)->exists();
    }

    public function isActive(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();
        
        if ($this->start_date && $this->start_date->gt($now)) {
            return false;
        }
        
        if ($this->end_date && $this->end_date->lt($now)) {
            return false;
        }
        
        return true;
    }
} 