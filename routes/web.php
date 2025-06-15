<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AgencyRegistrationController;
use App\Http\Controllers\NewPasswordResetController;
use App\Http\Controllers\PasswordController;

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

// Demo routes for testing the layout
Route::get('/dashboard', function () {
    return view('welcome');
})->name('dashboard');

Route::get('/profile', function () {
    return view('welcome');
})->name('profile.index');