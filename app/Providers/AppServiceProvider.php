<?php

namespace App\Providers;

use App\Models\Feedback;
use App\Models\FacilityNeed;
use App\Models\OnboardingTask;
use App\Observers\FeedbackObserver;
use App\Observers\FacilityNeedObserver;
use App\Observers\OnboardingTaskObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Feedback::observe(FeedbackObserver::class);
        FacilityNeed::observe(FacilityNeedObserver::class);
        OnboardingTask::observe(OnboardingTaskObserver::class);
    }
}
