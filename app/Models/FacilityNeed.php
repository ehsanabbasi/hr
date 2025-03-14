<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacilityNeed extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'status', // 'pending', 'in_review', 'accepted', 'delivered', 'rejected'
        'priority', // 'low', 'medium', 'high'
        'notes',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function statusHistory()
    {
        return $this->hasMany(FacilityNeedStatusHistory::class)->orderBy('created_at', 'desc');
    }
} 