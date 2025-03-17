<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'provider',
        'provider_id',
        'avatar',
        'department_id',
        'job_title_id',
        'birthday',
        'start_date',
        'emergency_contact_name',
        'emergency_contact_phone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'birthday' => 'date',
        'start_date' => 'date',
    ];

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the job title of the user.
     */
    public function jobTitle(): BelongsTo
    {
        return $this->belongsTo(JobTitle::class);
    }

    /**
     * Get the departments headed by the user.
     */
    public function headOfDepartments(): HasMany
    {
        return $this->hasMany(Department::class, 'head_id');
    }

    public function isHeadOfDepartment(): bool
    {
        return $this->headOfDepartments()->exists();
    }

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }
   
    public function processedLeaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class, 'approved_by');
    }

    public function pendingDepartmentLeaveRequests()
    {
        $departmentIds = $this->headOfDepartments()->pluck('id');
        
        return LeaveRequest::whereHas('user', function ($query) use ($departmentIds) {
            $query->whereIn('department_id', $departmentIds);
        })->where('status', 'pending');
    }

    public function sentFeedbacks()
    {
        return $this->hasMany(Feedback::class, 'sender_id');
    }

    public function receivedFeedbacks()
    {
        return $this->hasMany(Feedback::class, 'receiver_id');
    }

    public function documents()
    {
        return $this->hasMany(UserDocument::class);
    }

    public function uploadedDocuments()
    {
        return $this->hasMany(UserDocument::class, 'uploaded_by');
    }
    
    public function surveys(): BelongsToMany
    {
        return $this->belongsToMany(Survey::class, 'survey_user')
            ->withPivot('completed_at')
            ->withTimestamps();
    }
    
    public function onboardingTasks()
    {
        return $this->hasMany(OnboardingTask::class);
    }

    public function reviewCareerOpportunities(): BelongsToMany
    {
        return $this->belongsToMany(CareerOpportunity::class, 'career_opportunity_reviewers')
            ->withTimestamps();
    }

    public function certificates()
    {
        return $this->belongsToMany(Certificate::class, 'user_certificates')
            ->withPivot(['status', 'file_path', 'file_name', 'file_type', 'file_size', 'completed_at'])
            ->withTimestamps();
    }

    public function userCertificates()
    {
        return $this->hasMany(UserCertificate::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class)->orderBy('created_at', 'desc');
    }

    public function unreadNotifications()
    {
        return $this->notifications()->whereNull('read_at');
    }

}
