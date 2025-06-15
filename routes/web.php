<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\AgencyController;
use App\Http\Controllers\NotificationController;

Route::get('/', function () {
    return view('welcome');
});

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

// Demo routes for testing the layout
Route::get('/dashboard', function () {
    return view('welcome');
})->name('dashboard');

Route::get('/profile', function () {
    return view('welcome');
})->name('profile.index');