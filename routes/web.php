<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\AgencyController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NewPasswordResetController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\ManageInquiryFormSubmission\PublicUser\InquirySubmissionController;
use App\Http\Controllers\ManageInquiryFormSubmission\PublicUser\UserInquiriesController;
use App\Http\Controllers\ManageInquiryFormSubmission\PublicUser\PublicInquiriesController;
use App\Http\Controllers\ManageInquiryFormSubmission\MCMC\InquiryManagementController;
use App\Http\Controllers\InquiryAssignment\MCMCController;
use App\Http\Controllers\InquiryAssignment\AgencyController as InquiryAssignmentAgencyController;
use App\Http\Controllers\ManageInquiryFormSubmission\Agency\AssignedInquiriesController;
use App\Http\Controllers\ManageUser\User_Controller;

Route::get('/', function () {
    return view('welcome');
});

// Dashboard route with proper session handling
Route::get('/dashboard', function () {
    // Check if user is logged in via session
    $userType = session('user_type');
    $userId = session('user_id');
    
    if (empty($userType) || empty($userId)) {
        // No user session, redirect to login
        return redirect()->route('login')->with('message', 'Please log in to access the dashboard.');
    }
    
    // User is logged in, show dashboard
    return view('welcome');
})->name('dashboard');

// Authentication Routes
Route::get('/login', [User_Controller::class, 'showLoginForm'])->name('login');
Route::post('/login', [User_Controller::class, 'login']);
Route::get('/register', [User_Controller::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [User_Controller::class, 'register']);
Route::post('/logout', [User_Controller::class, 'logout'])->name('logout');

// Profile Routes
Route::get('/profile/edit', [User_Controller::class, 'editProfile'])->name('profile.edit');
Route::post('/profile/update', [User_Controller::class, 'updateProfile'])->name('profile.update');

// CSRF Token refresh route
Route::get('/refresh-csrf', function () {
    return response()->json(['token' => csrf_token()]);
})->name('refresh-csrf');

// Password Reset Routes (commented out - controllers not available)
/*
Route::get('/password/reset', [PasswordResetController::class, 'showRequestForm'])->name('password.request');
Route::post('/password/email', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
Route::get('/password/reset/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [PasswordResetController::class, 'reset'])->name('password.update');
*/

// Agency staff forced password change
Route::get('/password/change', [User_Controller::class, 'showForcedPasswordChange'])->name('password.change');
Route::post('/password/change', [User_Controller::class, 'updateForcedPassword'])->name('password.change.update');

// General password edit route for profile pages
Route::get('/password/edit', [User_Controller::class, 'editPassword'])->name('password.edit');
Route::post('/password/update', [User_Controller::class, 'updatePassword'])->name('password.update.profile');

// Admin routes (agency staff registration)
Route::middleware(['web'])->group(function () {
    Route::get('/admin/agency/register', [User_Controller::class, 'showAgencyRegistrationForm'])->name('admin.agency.register');
    Route::post('/admin/agency/register', [User_Controller::class, 'registerAgencyStaff'])->name('admin.agency.register.store');
    Route::get('/admin/agency/management', [User_Controller::class, 'showAgencyManagement'])->name('admin.agency.management');
    Route::post('/admin/agency/create', [User_Controller::class, 'createAgency'])->name('admin.agency.create');
    
    // MCMC Admin inquiry management routes
    Route::get('/admin/inquiries/new', [InquiryManagementController::class, 'viewNewInquiries'])->name('admin.inquiries.new');
    Route::get('/admin/inquiries/previous', [InquiryManagementController::class, 'viewPreviousInquiries'])->name('admin.inquiries.previous');
    Route::get('/admin/inquiries/{id}', [InquiryManagementController::class, 'showInquiry'])->name('admin.inquiries.show');
    Route::get('/admin/inquiries/{id}/evidence/{fileIndex}', [InquiryManagementController::class, 'downloadEvidence'])->name('admin.inquiries.download-evidence');
    Route::put('/admin/inquiries/{id}/status', [InquiryManagementController::class, 'updateInquiryStatus'])->name('admin.inquiries.update-status');
    Route::put('/admin/inquiries/{id}/flag', [InquiryManagementController::class, 'flagAsNonSerious'])->name('admin.inquiries.flag');
    Route::delete('/admin/inquiries/{id}/discard', [InquiryManagementController::class, 'discardInquiry'])->name('admin.inquiries.discard');
    Route::get('/admin/audit-logs', [InquiryManagementController::class, 'viewAuditLogs'])->name('admin.audit-logs');
    Route::get('/admin/reports', [InquiryManagementController::class, 'reports'])->name('admin.reports');
    
    // MCMC Admin inquiry management routes
    Route::get('/mcmc/inquiries/unassigned', [MCMCController::class, 'unassignedInquiries'])->name('mcmc.unassigned.inquiries');
    Route::post('/mcmc/inquiries/{inquiryId}/assign', [MCMCController::class, 'assignInquiry'])->name('mcmc.inquiries.assign');
    Route::get('/mcmc/inquiries/{inquiryId}/details', [MCMCController::class, 'getInquiryDetails'])->name('mcmc.inquiries.details');
    Route::get('/mcmc/inquiries/{id}/evidence/{fileIndex}', [MCMCController::class, 'downloadEvidence'])->name('mcmc.inquiries.download-evidence');
    
    Route::get('/mcmc/inquiries/assigned', function () {
        return view('welcome');
    })->name('mcmc.assigned.inquiries');
    
    Route::get('/mcmc/reports/assignments', function () {
        return view('welcome');
    })->name('mcmc.assignment.reports');
    
    Route::get('/mcmc/analytics', function () {
        return view('welcome');
    })->name('mcmc.analytics');
});

// Password recovery routes
Route::get('/password/forgot', [User_Controller::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/password/email', [User_Controller::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [User_Controller::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [User_Controller::class, 'resetPassword'])->name('password.reset.update');

// Public User Inquiry routes
Route::middleware(['web'])->group(function () {
    // Inquiry creation and submission
    Route::get('/inquiries/create', [InquirySubmissionController::class, 'create'])->name('inquiries.create');
    Route::post('/inquiries', [InquirySubmissionController::class, 'store'])->name('inquiries.store');
    
    // User's own inquiries
    Route::get('/inquiries', [UserInquiriesController::class, 'index'])->name('inquiries.index');
    Route::get('/inquiries/{id}', [UserInquiriesController::class, 'show'])->name('inquiries.show');
    
    // Public inquiry browsing
    Route::get('/public/inquiries', [PublicInquiriesController::class, 'index'])->name('public.inquiries.index');
    Route::get('/public/inquiries/{id}', [PublicInquiriesController::class, 'show'])->name('public.inquiries.show');
    
    // Agency inquiry routes
    Route::get('/agency/inquiries/assigned', [InquiryAssignmentAgencyController::class, 'assignedInquiries'])->name('agency.inquiries.assigned'); // Module 3
    Route::post('/agency/inquiries/{id}/reject', [InquiryAssignmentAgencyController::class, 'rejectInquiry'])->name('agency.inquiries.reject'); // Module 3
    Route::get('/agency/inquiries/{id}/evidence/{fileIndex}', [InquiryAssignmentAgencyController::class, 'downloadEvidence'])->name('agency.inquiries.download-evidence'); // Module 3
    Route::get('/agency/inquiries/list', [AssignedInquiriesController::class, 'index'])->name('agency.inquiries.list'); // Module 2
    Route::get('/agency/inquiries/{inquiryId}', [AssignedInquiriesController::class, 'show'])->name('agency.inquiries.show');
    Route::get('/agency/inquiries/detail/{inquiryId}/evidence/{fileIndex}', [AssignedInquiriesController::class, 'downloadEvidence'])->name('agency.inquiries.detail.download-evidence'); // Module 2
    
    // Agency-specific profile routes
    Route::post('/agency/profile/update', [User_Controller::class, 'updateProfile'])->name('agency.profile.update');
    Route::get('/agency/password/change', [User_Controller::class, 'editPassword'])->name('agency.password.change');
    Route::post('/agency/password/change', [User_Controller::class, 'updatePassword'])->name('agency.password.change.submit');
    
    // Test routes (if needed)
    Route::get('/test/inquiry/create', function () {
        return view('welcome');
    })->name('test.inquiry.create');
});

// Test route for debugging admin login (REMOVE IN PRODUCTION)
Route::get('/test-admin-login', function (Request $request) {
    // Simulate admin login
    $request->session()->put('user_id', 1);
    $request->session()->put('user_type', 'admin');
    $request->session()->put('user_name', 'Admin User');
    $request->session()->put('user_email', 'admin@admin.com');
    
    return redirect()->route('dashboard')->with('success', 'Test admin session created');
});