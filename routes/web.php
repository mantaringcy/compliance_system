<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Controllers
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\ComplianceController;
use App\Http\Controllers\ResetPasswordController;
use Carbon\Carbon;

Route::redirect('/', 'login');

Route::middleware('guest')->group(function() {
    // Register
    Route::get('/register', [AuthenticationController::class, 'index'])->name('register');
    Route::post('/register', [AuthenticationController::class, 'register']);
    
    // Login
    Route::view('/login', 'authentication.login')->name('login');
    Route::post('/login', [AuthenticationController::class, 'login']);

    // Password Reset Link Request Form
    Route::view('/forgot-password', 'authentication.forgot-password')->name('password.request');

    // Handling the Form Submission
    Route::post('/forgot-password', [ResetPasswordController::class, 'passwordEmail']);

    // Password Reset Form
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'passwordReset'])->name('password.reset');

    // Handling the Form Submission 2
    Route::post('/reset-password', [ResetPasswordController::class, 'passwordUpdate'])->name('password.update');
});


Route::middleware('auth')->group(function() {
    Route::view('/dashboard', 'components.dashboard')->middleware('verified')->name('dashboard');

    // Overview - Compliance for the Month
    Route::get('/overview', [ComplianceController::class, 'projections'])->name('overview');

    // Projection - 12 Months Compliance Projection
    Route::get('/projection', [ComplianceController::class, 'projections'])->name('projections');

    // Compliances - Compliance List
    Route::resource('/compliances', ComplianceController::class);
    
    // Logs - Records of changes on the system
    Route::get('/logs', [ComplianceController::class, 'getAllLogs'])->name('logs.data');

    Route::get('/logs-sample', [ComplianceController::class, 'showAllLogs'])->name('logs.sample');


    // Request - Request for Compliance Change
    Route::get('/admin/compliance/requests', [ComplianceController::class, 'reviewRequests'])->name('complianceRequests');
    Route::post('/admin/compliance/approve/{id}', [ComplianceController::class, 'approveRequest'])->name('approveRequest');
    Route::post('/admin/compliance/cancel/{id}', [ComplianceController::class, 'cancelRequest'])->name('cancelRequest');

    Route::view('/my-account', 'profile.my-account');
    Route::view('/account-settings', 'profile.my-account-settings');

    // Logout
    Route::post('/logout', [AuthenticationController::class, 'logout'])->name('logout');

    // Email Verification Notice
    Route::get('/email/verify', [AuthenticationController::class, 'verifyNotice'])->name('verification.notice');

    // Email Verification Handler
    Route::get('/email/verify/{id}/{hash}', [AuthenticationController::class, 'verifyEmail'])->middleware('signed')->name('verification.verify');

    // Resending the Verification Email
    Route::post('/email/verification-notification', [AuthenticationController::class, 'verifyHandler'])->middleware('throttle:6,1')->name('verification.send');
});

// Fallback route
Route::fallback(function () {
    // Check if the user is authenticated
    if (Auth::check()) {
        return redirect()->route('dashboard'); // Redirect authenticated users to the dashboard
    }
    return redirect()->route('login'); // Redirect guests to the login page
});