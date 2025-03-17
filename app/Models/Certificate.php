<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'due_date',
        'is_mandatory',
        'created_by',
    ];

    protected $casts = [
        'due_date' => 'date',
        'is_mandatory' => 'boolean',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function userCertificates(): HasMany
    {
        return $this->hasMany(UserCertificate::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_certificates')
            ->withPivot(['status', 'file_path', 'file_name', 'file_type', 'file_size', 'completed_at'])
            ->withTimestamps();
    }
} 