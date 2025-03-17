<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string|null>
     */
    protected $policies = [
        // ... existing policies ...
        \App\Models\User::class => \App\Policies\UserPolicy::class,
        \App\Models\FacilityNeed::class => \App\Policies\FacilityNeedPolicy::class,
        \App\Models\UserDocument::class => \App\Policies\UserDocumentPolicy::class,
        \App\Models\OnboardingTask::class => \App\Policies\OnboardingTaskPolicy::class,
        \App\Models\Certificate::class => \App\Policies\CertificatePolicy::class,
        \App\Models\UserCertificate::class => \App\Policies\UserCertificatePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
} 