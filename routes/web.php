<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\JobTitleController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\LeaveApprovalController;
use App\Http\Controllers\LeaveReasonController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FacilityNeedController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\DocumentTypeController;
use App\Http\Controllers\UserDocumentController;
use App\Http\Controllers\WorkingHourController;
use App\Http\Controllers\CompanyLawController;
use App\Http\Controllers\PollController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\OnboardingTaskController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('auth/google', [App\Http\Controllers\Auth\GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [App\Http\Controllers\Auth\GoogleController::class, 'handleGoogleCallback']);

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/settings', function() {
        return view('settings');
    })->name('settings');
    // User Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Users
    Route::resource('users', UserController::class);
    
    // Departments
    Route::resource('departments', DepartmentController::class);
    Route::get('departments/{department}/job-titles', [JobTitleController::class, 'getByDepartment'])
        ->name('departments.job-titles');
    
    // Job Titles
    Route::resource('job-titles', JobTitleController::class);
    Route::get('users/job-titles', [UserController::class, 'getJobTitles'])
        ->name('users.job-titles');
    
    // Leave Management
    
    // Leave Reasons (will be admin only later)
    Route::resource('leave-reasons', LeaveReasonController::class);
    
    // Leave Requests (for all authenticated users)
    Route::get('/leave-requests', [LeaveRequestController::class, 'index'])->name('leave-requests.index');
    Route::get('/leave-requests/create', [LeaveRequestController::class, 'create'])->name('leave-requests.create');
    Route::post('/leave-requests', [LeaveRequestController::class, 'store'])->name('leave-requests.store');
    Route::get('/leave-requests/{leaveRequest}', [LeaveRequestController::class, 'show'])->name('leave-requests.show');
    Route::delete('/leave-requests/{leaveRequest}/cancel', [LeaveRequestController::class, 'cancel'])->name('leave-requests.cancel');
    
    // Leave Approvals (will be department head only later)
    Route::get('/leave-approvals', [LeaveApprovalController::class, 'index'])->name('leave-approvals.index');
    Route::get('/leave-approvals/{leaveRequest}', [LeaveApprovalController::class, 'show'])->name('leave-approvals.show');
    Route::post('/leave-approvals/{leaveRequest}/process', [LeaveApprovalController::class, 'process'])->name('leave-approvals.process');

    // Facility Needs Routes
    Route::resource('facility-needs', FacilityNeedController::class);
    Route::patch('facility-needs/{facilityNeed}/status', [FacilityNeedController::class, 'updateStatus'])
        ->name('facility-needs.update-status');

    // Feedback routes
    Route::get('/feedbacks', [FeedbackController::class, 'index'])->name('feedbacks.index');
    Route::get('/feedbacks/create/{user}', [FeedbackController::class, 'create'])->name('feedbacks.create');
    Route::post('/feedbacks', [FeedbackController::class, 'store'])->name('feedbacks.store');
    Route::get('/feedbacks/{feedback}', [FeedbackController::class, 'show'])->name('feedbacks.show');
    Route::delete('/feedbacks/{feedback}', [FeedbackController::class, 'destroy'])->name('feedbacks.destroy');

    // Document Types (Admin only)
    Route::resource('document-types', DocumentTypeController::class);

    // User Documents
    Route::prefix('users/{user}/documents')->name('users.documents.')->group(function () {
        Route::get('/', [UserDocumentController::class, 'index'])->name('index');
        Route::get('/create', [UserDocumentController::class, 'create'])->name('create');
        Route::post('/', [UserDocumentController::class, 'store'])->name('store');
        Route::get('/{document}', [UserDocumentController::class, 'show'])->name('show');
        Route::get('/{document}/download', [UserDocumentController::class, 'download'])->name('download');
        Route::delete('/{document}', [UserDocumentController::class, 'destroy'])->name('destroy');
    });

    // Working Hours
    Route::get('/working-hours', [WorkingHourController::class, 'index'])->name('working-hours.index');
    Route::get('/working-hours/create', [WorkingHourController::class, 'create'])->name('working-hours.create');
    Route::post('/working-hours', [WorkingHourController::class, 'store'])->name('working-hours.store');
    Route::get('/working-hours/{workingHour}/edit', [WorkingHourController::class, 'edit'])->name('working-hours.edit');
    Route::put('/working-hours/{workingHour}', [WorkingHourController::class, 'update'])->name('working-hours.update');
    Route::get('/working-hours/monthly', [WorkingHourController::class, 'showMonthly'])->name('working-hours.monthly');

    // Company Law Settings (admin only)
    Route::get('/company-laws', [CompanyLawController::class, 'index'])->name('company-laws.index');
    Route::get('/company-laws/edit', [CompanyLawController::class, 'edit'])->name('company-laws.edit');
    Route::put('/company-laws', [CompanyLawController::class, 'update'])->name('company-laws.update');

    // Poll routes
    Route::resource('polls', PollController::class);
    Route::post('polls/{poll}/respond', [PollController::class, 'respond'])->name('polls.respond');
    Route::get('polls/{poll}/results', [PollController::class, 'results'])->name('polls.results');

    // Survey routes
    
    Route::resource('surveys', SurveyController::class);
    Route::post('surveys/{survey}/submit', [SurveyController::class, 'submit'])->name('surveys.submit');
    Route::get('surveys/{survey}/results', [SurveyController::class, 'results'])->name('surveys.results');
    Route::get('surveys/{survey}/assign', [SurveyController::class, 'assign'])->name('surveys.assign');
    Route::post('surveys/{survey}/assign', [SurveyController::class, 'assignStore'])->name('surveys.assign.store');

    // Onboarding Tasks
    Route::get('/onboarding', [OnboardingTaskController::class, 'index'])->name('onboarding.index');
    Route::get('/onboarding/create', [OnboardingTaskController::class, 'create'])->name('onboarding.create');
    Route::post('/onboarding', [OnboardingTaskController::class, 'store'])->name('onboarding.store');
    Route::get('/onboarding/{task}/edit', [OnboardingTaskController::class, 'edit'])->name('onboarding.edit');
    Route::put('/onboarding/{task}', [OnboardingTaskController::class, 'update'])->name('onboarding.update');
    Route::patch('/onboarding/{task}/status', [OnboardingTaskController::class, 'updateStatus'])->name('onboarding.update-status');
    Route::delete('/onboarding/{task}', [OnboardingTaskController::class, 'destroy'])->name('onboarding.destroy');

    // User onboarding
    Route::get('/users/{user}/onboarding', [OnboardingTaskController::class, 'userOnboarding'])->name('users.onboarding');
    
});

require __DIR__.'/auth.php';
