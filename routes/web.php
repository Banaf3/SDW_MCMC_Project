<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ManageInquiryFormSubmission\PublicUser\InquirySubmissionController;

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

// Routes for Public User Inquiry Submission
Route::middleware(['auth'])->group(function () {
    Route::get('/inquiries/submit', [InquirySubmissionController::class, 'create'])->name('inquiries.create');
    Route::post('/inquiries', [InquirySubmissionController::class, 'store'])->name('inquiries.store');
});

// Test routes without authentication (remove in production)
Route::get('/test/inquiry/create', [InquirySubmissionController::class, 'create'])->name('test.inquiry.create');
Route::post('/test/inquiry', [InquirySubmissionController::class, 'store'])->name('test.inquiry.store');

// User Inquiries Management Routes
use App\Http\Controllers\ManageInquiryFormSubmission\PublicUser\UserInquiriesController;
Route::prefix('inquiries')->group(function() {
    // Routes that would normally require authentication
    Route::get('/', [UserInquiriesController::class, 'index'])->name('inquiries.index');
    Route::get('/{id}', [UserInquiriesController::class, 'show'])->name('inquiries.show');
    
    // Test versions without authentication
    Route::get('/test/list', [UserInquiriesController::class, 'index'])->name('test.inquiries.user.index');
    Route::get('/test/view/{id}', [UserInquiriesController::class, 'show'])->name('test.inquiries.user.show');
});

// Public Inquiries Routes (Browse all inquiries without personal info)
use App\Http\Controllers\ManageInquiryFormSubmission\PublicUser\PublicInquiriesController;
Route::prefix('public-inquiries')->group(function() {
    Route::get('/', [PublicInquiriesController::class, 'index'])->name('public.inquiries.index');
    Route::get('/{id}', [PublicInquiriesController::class, 'show'])->name('public.inquiries.show');
});

// Easy access route for testing - redirects to the test form
Route::get('/test-form', function() {
    return redirect()->route('test.inquiry.create');
});

// Easy access to user's inquiries list
Route::get('/my-inquiries', function() {
    return redirect()->route('test.inquiries.user.index');
});

// Easy access to public inquiries
Route::get('/browse-inquiries', function() {
    return redirect()->route('public.inquiries.index');
});

// Test route to view inquiries
Route::get('/test-inquiries', function () {
    $inquiries = \App\Models\Inquiry::orderBy('SubmitionDate', 'desc')->get();
    return view('test-inquiries', compact('inquiries'));
})->name('test.inquiries.index');