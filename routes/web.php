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

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication routes (provided by Laravel Breeze/UI)
// These are already defined if you're using Laravel's auth scaffolding

// Routes that require authentication
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
});

require __DIR__.'/auth.php';
