<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InquiryProgressTracking\InquiryController;
use App\Http\Controllers\InquiryAssignment\AgencyController as Module3AgencyController;
use App\Http\Controllers\InquiryAssignment\MCMCController as Module3MCMCController;
use App\Http\Controllers\InquiryProgressTracking\AgencyController;
use App\Http\Controllers\InquiryProgressTracking\NotificationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NewPasswordResetController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\ManageInquiryFormSubmission\PublicUser\InquirySubmissionController;
use App\Http\Controllers\ManageInquiryFormSubmission\PublicUser\UserInquiriesController;
use App\Http\Controllers\ManageInquiryFormSubmission\PublicUser\PublicInquiriesController;

Route::get('/', function () {
    return view('welcome');
});

// Public User Routes (Module 1 - Form Submission)
Route::get('/inquiries/create', [InquirySubmissionController::class, 'create'])->name('inquiries.create');
Route::post('/inquiries/store', [InquirySubmissionController::class, 'store'])->name('inquiries.store');
Route::get('/inquiries', [UserInquiriesController::class, 'index'])->name('inquiries.index');
Route::get('/public/inquiries', [PublicInquiriesController::class, 'index'])->name('public.inquiries.index');

// Use the InquiryController to fetch real data from database
Route::get('/inquiry_list', [InquiryController::class, 'index']);

// All inquiries view (Module4-MCMC)
Route::get('/all-inquiries', [InquiryController::class, 'allInquiries']);

// Reports page (Module4-MCMC)
Route::get('/reports', [InquiryController::class, 'reports']);

Route::get('/inquiry-detail/{id}', [InquiryController::class, 'show']);

// MCMC inquiry detail route
Route::get('/mcmc-inquiry-detail/{id}', [InquiryController::class, 'show']);

// Agency routes (Module 4 - Inquiry Progress Tracking)
Route::get('/agency/inquiries/assigned', [AgencyController::class, 'assignedInquiries'])->name('agency.inquiries.assigned');
Route::get('/agency/inquiry-edit/{id}', [AgencyController::class, 'editInquiry']);
Route::put('/agency/inquiry-update/{id}', [AgencyController::class, 'updateInquiry']);
Route::put('/agency/inquiry-status-update/{id}', [AgencyController::class, 'updateInquiryStatus']);

// Module 3 routes (MCMC and Agency Assignment)
Route::get('/mcmc/unassigned-inquiries', [Module3MCMCController::class, 'unassignedInquiries'])->name('mcmc.unassigned.inquiries');
Route::post('/mcmc/assign-inquiry', [Module3MCMCController::class, 'assignInquiry'])->name('mcmc.assign.inquiry');
Route::post('/mcmc/reject-inquiry', [Module3MCMCController::class, 'rejectInquiry'])->name('mcmc.reject.inquiry');
Route::get('/mcmc/inquiry/{id}/meta', [Module3MCMCController::class, 'getInquiryMeta'])->name('mcmc.inquiry.meta');

Route::get('/agency/assigned-inquiries', [Module3AgencyController::class, 'assignedInquiries'])->name('agency.assigned.inquiries');
Route::post('/agency/accept-inquiry', [Module3AgencyController::class, 'acceptInquiry'])->name('agency.accept.inquiry');
Route::post('/agency/reject-inquiry', [Module3AgencyController::class, 'rejectInquiry'])->name('agency.reject.inquiry');
Route::get('/agency/inquiry/{id}/meta', [Module3AgencyController::class, 'getInquiryMeta'])->name('agency.inquiry.meta');

// Testing guide route
Route::get('/testing-guide', function () {
    return view('testing-guide');
})->name('testing.guide');

// Demo routes for testing the layout
Route::get('/dashboard', function () {
    return view('welcome');
})->name('dashboard');

// Authentication Routes (commented out - controllers not available)
/*
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
*/

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

// Profile Routes (commented out - controllers not available)
/*
Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
*/

// Admin Agency Management Routes (commented out - controller not available)
/*
Route::get('/admin/agency/register', [AgencyRegistrationController::class, 'showRegistrationForm'])->name('admin.agency.register');
Route::post('/admin/agency/register', [AgencyRegistrationController::class, 'register'])->name('admin.agency.register.store');
Route::get('/admin/agency/management', [AgencyRegistrationController::class, 'showAgencyManagement'])->name('admin.agency.management');
Route::post('/admin/agency/create', [AgencyRegistrationController::class, 'createAgency'])->name('admin.agency.create');
*/

// Notification API Routes
Route::get('/api/notifications', [NotificationController::class, 'index']);
Route::get('/api/notifications/unread-count', [NotificationController::class, 'getUnreadCount']);
Route::post('/api/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead']);
Route::post('/api/notifications/{id}/mark-read', [NotificationController::class, 'markAsRead']);

// Test notification route for debugging
Route::get('/test-notification/{userId}', function($userId) {
    $testNotification = [
        'inquiry_id' => 999,
        'inquiry_title' => 'Test Inquiry',
        'old_status' => 'Under Investigation',
        'new_status' => 'Verified as True',
        'message' => "Your inquiry 'Test Inquiry' status has been changed from 'Under Investigation' to 'Verified as True'",
        'timestamp' => now()->format('Y-m-d H:i:s'),
        'read' => false
    ];
    
    $userNotifications = session()->get("user_notifications_{$userId}", []);
    $userNotifications[] = $testNotification;
    session()->put("user_notifications_{$userId}", $userNotifications);
    
    return response()->json(['message' => 'Test notification created for user ' . $userId]);
});
