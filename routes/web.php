<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Controllers
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\ComplianceController;
use App\Http\Controllers\ResetPasswordController;

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


    // Route::view('/overview', 'components.overview');
    // Route::view('/overview', 'components.overview');
    Route::get('/overview', [ComplianceController::class, 'projections'])->name('overview');



    // Route::get('/projection', [ComplianceController::class, 'showAllCompliances']);
    Route::get('/projection', [ComplianceController::class, 'projections'])->name('projections');

    Route::view('/records', 'components.records');
    Route::view('/accounts', 'components.accounts');

    // Compliance List
    Route::resource('/compliances', ComplianceController::class);

    Route::get('/admin/compliance/requests', [ComplianceController::class, 'reviewRequests'])->name('complianceRequests');
    Route::post('/admin/compliance/approve/{id}', [ComplianceController::class, 'approveRequest'])->name('approveRequest');
    Route::post('/admin/compliance/cancel/{id}', [ComplianceController::class, 'cancelRequest'])->name('cancelRequest');


    // Route::view('/settings', 'components.settings');

    // Route::get('/compliance-list', [ComplianceController::class, 'getCompliance'])->name('compliance-list');
    // Route::post('/compliance-list', [ComplianceController::class, 'post']);
    // Route::put('/compliance-list', [ComplianceController::class, 'update'])->name('compliance-update');

    // Route::view('/compliance-list', 'components.compliance-list');

    // New Compliance
    // Route::get('/new-compliance', [ComplianceController::class, 'getDepartment'])->name('new-compliance');
    // Route::post('/new-compliance', [ComplianceController::class, 'post']);

    
    Route::view('/my-account', 'profile.my-account');
    Route::view('/account-settings', 'profile.my-account-settings');
    
    Route::post('/logout', [AuthenticationController::class, 'logout'])->name('logout');

    // Email Verification Notice
    Route::get('/email/verify', [AuthenticationController::class, 'verifyNotice'])->name('verification.notice');

    // Email Verification Handler
    Route::get('/email/verify/{id}/{hash}', [AuthenticationController::class, 'verifyEmail'])->middleware('signed')->name('verification.verify');

    // Resending the Verification Email
    Route::post('/email/verification-notification', [AuthenticationController::class, 'verifyHandler'])->middleware('throttle:6,1')->name('verification.send');
});

// Fallback route
// Route::fallback(function () {
//     // Check if the user is authenticated
//     if (Auth::check()) {
//         return redirect()->route('dashboard'); // Redirect authenticated users to the dashboard
//     }
//     return redirect()->route('login'); // Redirect guests to the login page
// });