<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PollOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'poll_id',
        'option_text',
    ];

    public function poll(): BelongsTo
    {
        return $this->belongsTo(Poll::class);
    }

    public function responses(): HasMany
    {
        return $this->hasMany(PollResponse::class);
    }

    public function getResponsePercentage(): float
    {
        $totalResponses = $this->poll->responses()->count();
        
        if ($totalResponses === 0) {
            return 0;
        }
        
        $optionResponses = $this->responses()->count();
        
        return round(($optionResponses / $totalResponses) * 100, 1);
    }
} 