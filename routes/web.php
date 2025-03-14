<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Google login
Route::get('/auth/google', [App\Http\Controllers\Auth\GoogleController::class, 'redirectToGoogle'])
    ->name('auth.google');
Route::get('/auth/google/callback', [App\Http\Controllers\Auth\GoogleController::class, 'handleGoogleCallback']);

// Add these routes to your existing web.php file
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/settings', function() {
        return view('settings');
    })->name('settings');
    
    // User routes
    Route::resource('users', App\Http\Controllers\UserController::class);
    
    // Profile routes
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    
    // Department routes
    Route::resource('departments', App\Http\Controllers\DepartmentController::class);
    
    // Job Title routes
    Route::resource('job-titles', App\Http\Controllers\JobTitleController::class);
    Route::get('departments/{department}/job-titles', [App\Http\Controllers\JobTitleController::class, 'getByDepartment'])
        ->name('departments.job-titles');
    
    // User job titles by department
    Route::get('users/job-titles', [App\Http\Controllers\UserController::class, 'getJobTitles'])
        ->name('users.job-titles');
});

require __DIR__.'/auth.php';
