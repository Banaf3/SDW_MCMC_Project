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
use App\Http\Controllers\ManageInquiryFormSubmission\MCMC\InquiryManagementController;
use App\Http\Controllers\ManageInquiryFormSubmission\Agency\AssignedInquiriesController;
use App\Http\Controllers\ManageUser\User_Controller;

Route::get('/', function () {
    return view('welcome');
});

// Demo routes for testing the layout
Route::get('/dashboard', function () {
    return view('welcome');
})->name('dashboard');

Route::get('/profile', function () {
    return view('welcome');
})->name('profile.index');

// Authentication routes
Route::get('/login', [User_Controller::class, 'showLoginForm'])->name('login');
Route::post('/login', [User_Controller::class, 'login']);
Route::post('/logout', [User_Controller::class, 'logout'])->name('logout');

// Public user registration
Route::get('/register', [User_Controller::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [User_Controller::class, 'register']);

// Agency staff login
Route::get('/agency/login', [User_Controller::class, 'showAgencyLoginForm'])->name('agency.login');
Route::post('/agency/login', [User_Controller::class, 'agencyLogin']);

// Profile management (for all user types)
Route::middleware(['web'])->group(function () {
    Route::get('/profile/edit', [User_Controller::class, 'editProfile'])->name('profile.edit');
    Route::post('/profile/update', [User_Controller::class, 'updateProfile'])->name('profile.update');
    Route::get('/password/edit', [User_Controller::class, 'editPassword'])->name('password.edit');
    Route::post('/password/update', [User_Controller::class, 'updatePassword'])->name('password.update');
});

// Agency staff forced password change
Route::get('/password/change', [User_Controller::class, 'showForcedPasswordChange'])->name('password.change');
Route::post('/password/change', [User_Controller::class, 'updateForcedPassword'])->name('password.change.update');

// Admin routes (agency staff registration)
Route::middleware(['web'])->group(function () {
    Route::get('/admin/agency/register', [User_Controller::class, 'showAgencyRegistrationForm'])->name('admin.agency.register');
    Route::post('/admin/agency/register', [User_Controller::class, 'registerAgencyStaff'])->name('admin.agency.register.store');
    Route::get('/admin/agency/management', [User_Controller::class, 'showAgencyManagement'])->name('admin.agency.management');
    Route::post('/admin/agency/create', [User_Controller::class, 'createAgency'])->name('admin.agency.create');
    
    // MCMC Admin inquiry management routes (placeholders)
    Route::get('/mcmc/inquiries/unassigned', function () {
        return view('welcome');
    })->name('mcmc.unassigned.inquiries');
    
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

// Inquiry routes (placeholder for now)
Route::middleware(['web'])->group(function () {
    // User inquiry routes
    Route::get('/inquiries/create', function () {
        return view('welcome');
    })->name('inquiries.create');
    
    Route::post('/inquiries', function () {
        return redirect()->back()->with('success', 'Inquiry submitted successfully (placeholder)');
    })->name('inquiries.store');
    
    Route::get('/inquiries', function () {
        return view('welcome');
    })->name('inquiries.index');
    
    Route::get('/inquiries/{id}', function ($id) {
        return view('welcome');
    })->name('inquiries.show');
    
    // Public inquiry routes
    Route::get('/public/inquiries', function () {
        return view('welcome');
    })->name('public.inquiries.index');
    
    Route::get('/public/inquiries/{id}', function ($id) {
        return view('welcome');
    })->name('public.inquiries.show');
    
    // Agency inquiry routes
    Route::get('/agency/inquiries/assigned', function () {
        return view('welcome');
    })->name('agency.inquiries.assigned');
    
    // Agency-specific profile routes
    Route::post('/agency/profile/update', [User_Controller::class, 'updateProfile'])->name('agency.profile.update');
    Route::get('/agency/password/change', [User_Controller::class, 'editPassword'])->name('agency.password.change');
    Route::post('/agency/password/change', [User_Controller::class, 'updatePassword'])->name('agency.password.change.submit');
    
    // Test routes (if needed)
    Route::get('/test/inquiry/create', function () {
        return view('welcome');
    })->name('test.inquiry.create');
});