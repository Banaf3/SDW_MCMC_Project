<?php

namespace App\Http\Controllers\ManageUser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Administrator;
use App\Models\Agency;
use App\Models\AgencyStaff;
use Illuminate\Support\Str;

class Admin_Controller extends Controller
{
    /**
     * ====================================
     * ADMIN AUTHENTICATION METHODS
     * ====================================
     */
    
    /**
     * Show admin login form
     */
    public function showLoginForm()
    {
        return view('Module01.login');
    }
      /**
     * Handle admin login
     */
    public function login(Request $request)
    {
        $loginField = $request->email; // Can be email or username
        $password = $request->password;
        
        // Validate input
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);
        
        // Try to find admin by username or email using new model method
        $admin = Administrator::findForLogin($loginField);
                             
        if ($admin && Hash::check($password, $admin->Password)) {
            // Create a session for the admin
            $request->session()->put('user_id', $admin->AdminID);
            $request->session()->put('user_type', 'admin');
            $request->session()->put('user_name', $admin->AdminName);
            $request->session()->put('user_email', $admin->AdminEmail);
            $request->session()->put('username', $admin->Username);
            
            return redirect()->intended('/dashboard');
        }
        
        return back()->with('error', 'Invalid admin credentials.')->withInput();
    }
    
    /**
     * Handle admin logout
     */
    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
    
    /**
     * ====================================
     * ADMIN PROFILE MANAGEMENT METHODS
     * ====================================
     */
    
    /**
     * Show admin profile editing form
     */
    public function editProfile(Request $request)
    {
        $userId = $request->session()->get('user_id');
        $userType = $request->session()->get('user_type');
        
        if (!$userId || $userType !== 'admin') {
            return redirect()->route('login')->with('error', 'Admin access required.');
        }
        
        $admin = Administrator::find($userId);
        if (!$admin) {
            return redirect()->route('login')->with('error', 'Admin not found.');
        }
          // Format admin data for the view
        $formattedAdmin = [
            'id' => $admin->AdminID,
            'name' => $admin->AdminName,
            'email' => $admin->AdminEmail,
            'phone' => $admin->AdminPhoneNum,
            'address' => $admin->AdminAddress,
            'role' => $admin->AdminRole
        ];
        
        return view('Module01.MCMC_Admin.edit-profile', ['user' => $formattedAdmin]);
    }
    
    /**
     * Update admin profile
     */
    public function updateProfile(Request $request)
    {
        $userId = $request->session()->get('user_id');
        $userType = $request->session()->get('user_type');
        
        if (!$userId || $userType !== 'admin') {
            return redirect()->route('login')->with('error', 'Admin access required.');
        }
        
        $validationRules = [
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'profile_pic' => 'nullable|image|max:2048',
            'address' => 'nullable|string|max:500',
        ];
        
        $request->validate($validationRules);
        
        $admin = Administrator::find($userId);
        if (!$admin) {
            return redirect()->route('login')->with('error', 'Admin not found.');
        }
          // Update admin profile
        $updateData = [];
        if ($request->filled('name')) {
            $updateData['AdminName'] = $request->name;
            $request->session()->put('user_name', $request->name);
        }
        if ($request->filled('phone')) {
            $updateData['AdminPhoneNum'] = $request->phone;
        }
        if ($request->filled('address')) {
            $updateData['AdminAddress'] = $request->address;
        }
        
        if (!empty($updateData)) {
            $admin->update($updateData);
        }
        
        return redirect()->back()->with('success', 'Admin profile updated successfully!');
    }
    
    /**
     * ====================================
     * ADMIN PASSWORD MANAGEMENT METHODS
     * ====================================
     */
    
    /**
     * Show admin password change form
     */
    public function editPassword(Request $request)
    {
        $userId = $request->session()->get('user_id');
        $userType = $request->session()->get('user_type');
        
        if (!$userId || $userType !== 'admin') {
            return redirect()->route('login')->with('error', 'Admin access required.');
        }
        
        return view('Module01.MCMC_Admin.edit-password');
    }
    
    /**
     * Update admin password
     */
    public function updatePassword(Request $request)
    {
        $userId = $request->session()->get('user_id');
        $userType = $request->session()->get('user_type');
        
        if (!$userId || $userType !== 'admin') {
            return redirect()->route('login')->with('error', 'Admin access required.');
        }
        
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);
        
        $admin = Administrator::find($userId);
        if (!$admin) {
            return redirect()->route('login')->with('error', 'Admin not found.');
        }
        
        // Check current password
        if (!Hash::check($request->current_password, $admin->Password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }
        
        // Update password
        $admin->update(['Password' => Hash::make($request->new_password)]);
        
        return redirect()->back()->with('success', 'Password updated successfully!');
    }
    
    /**
     * ====================================
     * ADMIN PASSWORD RESET METHODS
     * ====================================
     */
    
    /**
     * Show admin forgot password form
     */
    public function showForgotPasswordForm()
    {
        return view('Module01.MCMC_Admin.recovery');
    }
    
    /**
     * Send admin password reset link email
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        
        // For now, just redirect back with success message
        // In a full implementation, you would send an actual email
        return back()->with('success', 'Admin password reset link sent to your email address.');
    }
    
    /**
     * Show admin password reset form
     */
    public function showResetForm($token)
    {
        return view('Module01.MCMC_Admin.reset', ['token' => $token]);
    }
    
    /**
     * Reset admin password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);
        
        // For now, just redirect with success message
        // In a full implementation, you would verify the token and update the password
        return redirect()->route('admin.login')->with('success', 'Admin password has been reset successfully.');
    }

    /**
     * ====================================
     * AGENCY STAFF MANAGEMENT METHODS (Admin creates agency accounts)
     * ====================================
     */}