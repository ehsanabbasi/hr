<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class WorkingHour extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'start_time',
        'end_time',
        'break_start_time',
        'break_end_time',
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'break_start_time' => 'datetime:H:i',
        'break_end_time' => 'datetime:H:i',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTotalHoursAttribute()
    {
        $startTime = Carbon::parse($this->start_time);
        $endTime = Carbon::parse($this->end_time);
        $totalHours = $endTime->diffInHours($startTime);

        if ($this->break_start_time && $this->break_end_time) {
            $breakStartTime = Carbon::parse($this->break_start_time);
            $breakEndTime = Carbon::parse($this->break_end_time);
            $breakHours = $breakEndTime->diffInHours($breakStartTime);
            $totalHours -= $breakHours;
        }

        return $totalHours;
    }
} 