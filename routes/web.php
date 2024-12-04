<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Controllers
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ComplianceController;
use App\Http\Controllers\ComplianceManagementController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\UserProfileController;
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
    Route::get('/overview', [ComplianceController::class, 'monthlyCompliances'])->name('overview');

    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');

    // Projection - 12 Months Compliance Projection
    Route::get('/projection', [ComplianceController::class, 'projections'])->name('projections');

    // Compliances - Compliance List
    Route::resource('/compliances', ComplianceController::class);
    
    // Logs - Records of changes on the system
    Route::get('/logs', [LogController::class, 'index'])->name('logs.data');

    Route::resource('compliance-management', ComplianceManagementController::class);

    Route::post('/compliance/update-status/{id}', [ComplianceManagementController::class, 'updateStatus'])->name('compliance.updateStatus');

    Route::post('/compliance/approve/{id}', [ComplianceManagementController::class, 'approve'])->name('compliance.approve');

    Route::post('compliance-management/{id}/upload-image', [ComplianceManagementController::class, 'uploadImage'])->name('compliance-management.upload-image');

    // Route::delete('/compliance-management/{complianceId}/image/{filePath}', [ComplianceManagementController::class, 'deleteImage'])->name('deleteImage');
    
    // Route::get('/compliance-management/{complianceId}/image/{filePath}', [ComplianceManagementController::class, 'deleteImage'])->name('deleteImage');

    Route::delete('/compliance-management/{id}/delete-image', [ComplianceManagementController::class, 'deleteImage'])->name('compliance-management.delete-image');

    Route::post('/compliance-management/{id}/messages', [MessageController::class, 'store'])->name('compliance-management.store-message');
    Route::get('/compliance-management/{id}/messages', [MessageController::class, 'fetch'])->name('compliance-management.fetch-message');


    // Route::match(['delete', 'post'], '/compliance-management/{complianceId}/image/{filePath}', [ComplianceManagementController::class, 'deleteImage'])->name('delete.image');



    // Route::get('/logs-sample', [LogController::class, 'showAllLogs'])->name('logs.sample');


    // Request - Request for Compliance Change
    Route::get('/admin/compliance/requests', [RequestController::class, 'index'])->name('complianceRequests');
    Route::post('/admin/compliance/approve/{id}', [RequestController::class, 'approveRequest'])->name('approveRequest');
    Route::post('/admin/compliance/cancel/{id}', [RequestController::class, 'cancelRequest'])->name('cancelRequest');

    // User Profile
    Route::get('/account-profile', [UserProfileController::class, 'index'])->name('profile.update');
    Route::post('/account-profile/profile-udpate', [UserProfileController::class, 'updateProfile'])->name('update.profile');
    Route::post('/account-profile/password-update', [UserProfileController::class, 'updatePassword'])->name('update.password');
    Route::view('/account-settings', 'profile.my-account-settings');

    // Logout
    Route::post('/logout', [AuthenticationController::class, 'logout'])->name('logout');

    // Email Verification Notice
    Route::get('/email/verify', [AuthenticationController::class, 'verifyNotice'])->name('verification.notice');

    // Email Verification Handler
    Route::get('/email/verify/{id}/{hash}', [AuthenticationController::class, 'verifyEmail'])->middleware('signed')->name('verification.verify');

    // Resending the Verification Email
    Route::post('/email/verification-notification', [AuthenticationController::class, 'verifyHandler'])->middleware('throttle:6,1')->name('verification.send');

    Route::view('/test-email', 'emails.superadmin_compliance_created');

    Route::get('/compliance/{id}/gallery', [ComplianceController::class, 'showGallery']);

});

// Fallback route
Route::fallback(function () {
    // Check if the user is authenticated
    if (Auth::check()) {
        return redirect()->route('dashboard'); // Redirect authenticated users to the dashboard
    }
    return redirect()->route('login'); // Redirect guests to the login page
});