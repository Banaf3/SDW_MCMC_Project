<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\AgencyController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AgencyRegistrationController;
use App\Http\Controllers\NewPasswordResetController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\ManageInquiryFormSubmission\PublicUser\InquirySubmissionController;
use App\Http\Controllers\ManageInquiryFormSubmission\PublicUser\UserInquiriesController;
use App\Http\Controllers\ManageInquiryFormSubmission\PublicUser\PublicInquiriesController;
use App\Http\Controllers\MCMCController;

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Registration Routes
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Profile Routes
Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

// Password Change Routes
Route::get('/password/edit', [PasswordController::class, 'edit'])->name('password.edit');
Route::put('/password', [PasswordController::class, 'update'])->name('password.change');

// Password Reset Routes
Route::get('/forgot-password', [NewPasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [NewPasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [NewPasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [NewPasswordResetController::class, 'reset'])->name('password.update');

// Agency Registration Routes (Admin only)
Route::get('/admin/agency/register', [AgencyRegistrationController::class, 'showRegistrationForm'])->name('admin.agency.register');
Route::post('/admin/agency/register', [AgencyRegistrationController::class, 'register']);
Route::get('/admin/agency/management', [AgencyRegistrationController::class, 'showAgencyManagement'])->name('admin.agency.management');
Route::post('/admin/agency/create', [AgencyRegistrationController::class, 'createAgency'])->name('admin.agency.create');

// Use the InquiryController to fetch real data from database
Route::get('/inquiry_list', [InquiryController::class, 'index']);

// All inquiries view (Module4-MCMC)
Route::get('/all-inquiries', [InquiryController::class, 'allInquiries']);

Route::get('/inquiry-detail/{id}', [InquiryController::class, 'show']);

// Notification API routes
Route::get('/api/notifications', [NotificationController::class, 'index']);
Route::post('/api/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead']);
Route::post('/api/notifications/{id}/mark-read', [NotificationController::class, 'markAsRead']);
Route::get('/api/notifications/unread-count', [NotificationController::class, 'getUnreadCount']);

// Agency routes
Route::get('/agency/inquiries/assigned', [AgencyController::class, 'assignedInquiries'])->name('agency.inquiries.assigned');
Route::get('/agency/inquiry-edit/{id}', [AgencyController::class, 'editInquiry']);
Route::put('/agency/inquiry-update/{id}', [AgencyController::class, 'updateInquiry']);
Route::put('/agency/inquiry-status-update/{id}', [AgencyController::class, 'updateInquiryStatus']);

// Testing guide route
Route::get('/testing-guide', function () {
    return view('testing-guide');
})->name('testing.guide');

// Demo routes for testing the layout
Route::get('/dashboard', function () {
    return view('welcome');
})->name('dashboard');

Route::get('/profile', function () {
    return view('welcome');
})->name('profile.index');

// Routes for Public User Inquiry Submission
Route::get('/inquiries/submit', [InquirySubmissionController::class, 'create'])->name('inquiries.create');
Route::post('/inquiries', [InquirySubmissionController::class, 'store'])->name('inquiries.store');

// User Inquiries Management Routes
Route::prefix('inquiries')->group(function() {
    Route::get('/', [UserInquiriesController::class, 'index'])->name('inquiries.index');
    Route::get('/{id}', [UserInquiriesController::class, 'show'])->name('inquiries.show');
});

// Public Inquiries Routes (Browse all inquiries without personal info)
Route::prefix('public-inquiries')->group(function() {
    Route::get('/', [PublicInquiriesController::class, 'index'])->name('public.inquiries.index');
    Route::get('/{id}', [PublicInquiriesController::class, 'show'])->name('public.inquiries.show');
});

// Easy access routes
Route::get('/my-inquiries', function() {
    return redirect()->route('inquiries.index');
});

Route::get('/browse-inquiries', function() {
    return redirect()->route('public.inquiries.index');
});

// Test route to simulate public user login (remove in production)
Route::get('/test-login/{userId?}', function($userId = 1) {
    session()->put('user_id', $userId);
    session()->put('user_type', 'public');
    session()->put('user_name', 'Test User ' . $userId);
    session()->put('user_email', 'testuser' . $userId . '@example.com');
    
    return redirect()->route('inquiries.index')->with('success', 'Logged in as Test User ' . $userId);
});

// Test route to logout (remove in production)
Route::get('/test-logout', function() {
    session()->flush();
    return redirect('/')->with('success', 'Logged out successfully');
});

// Module 3 routes
Route::get('/mcmc/unassigned-inquiries', [MCMCController::class, 'unassignedInquiries'])->name('mcmc.unassigned.inquiries');
Route::get('/agency/assigned-inquiries', [AgencyController::class, 'assignedInquiries'])->name('agency.assigned.inquiries');
Route::get('/mcmc/assigned-inquiries', [MCMCController::class, 'assignedInquiries'])->name('mcmc.assigned.inquiries');

// Assignment route
Route::post('/mcmc/assign-inquiry/{id}', [MCMCController::class, 'assignInquiry'])->name('mcmc.assign.inquiry');

// Inquiry details route
Route::get('/mcmc/inquiry-details/{id}', [MCMCController::class, 'getInquiryDetails'])->name('mcmc.inquiry.details');

// Agency rejection route
Route::post('/agency/reject-inquiry/{id}', [AgencyController::class, 'rejectInquiry'])->name('agency.reject.inquiry');
