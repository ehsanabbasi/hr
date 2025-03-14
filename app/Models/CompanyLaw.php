<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyLaw extends Model
{
    use HasFactory;

    protected $fillable = [
        'max_daily_hours',
        'max_daily_break_hours',
        'min_weekly_hours',
        'max_weekly_hours',
        'min_monthly_hours',
        'max_monthly_hours',
    ];

    protected $casts = [
        'max_daily_hours' => 'float',
        'max_daily_break_hours' => 'float',
        'min_weekly_hours' => 'float',
        'max_weekly_hours' => 'float',
        'min_monthly_hours' => 'float',
        'max_monthly_hours' => 'float',
    ];
} 