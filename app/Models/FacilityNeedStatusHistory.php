<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacilityNeedStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'facility_need_id',
        'user_id',
        'old_status',
        'new_status',
        'notes',
    ];

    public function facilityNeed()
    {
        return $this->belongsTo(FacilityNeed::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 