<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    // Explicitly set the table name
    protected $table = 'feedbacks';

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'title',
        'content',
        'session',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
    
    public function isUnread()
    {
        return $this->read_at === null;
    }
} 