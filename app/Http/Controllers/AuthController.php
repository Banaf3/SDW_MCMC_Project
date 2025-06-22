<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{    public function showLoginForm()
    {
        return view('Module01.login');
    }    public function login(Request $request)
    {
        $loginField = $request->email; // Can be email or username
        $password = $request->password;
        
        // Try to find admin by email or AdminName (username)
        $admin = \App\Models\Administrator::where('AdminEmail', $loginField)
                                         ->orWhere('AdminName', $loginField)
                                         ->first();
        if ($admin && Hash::check($password, $admin->Password)) {
            // Create a session for the admin
            $request->session()->put('user_id', $admin->AdminID);
            $request->session()->put('user_type', 'admin');
            $request->session()->put('user_name', $admin->AdminName);
            $request->session()->put('user_email', $admin->AdminEmail);
            
            // Redirect MCMC admin to unassigned inquiries page
            return redirect()->intended(route('mcmc.unassigned.inquiries'));
        }
        
        // Try to find agency staff by email or StaffName (username)
        $staff = \App\Models\AgencyStaff::where('staffEmail', $loginField)
                                       ->orWhere('StaffName', $loginField)
                                       ->first();
        if ($staff && Hash::check($password, $staff->Password)) {
            // Create a session for the agency staff
            $request->session()->put('user_id', $staff->StaffID);
            $request->session()->put('user_type', 'agency');
            $request->session()->put('user_name', $staff->StaffName);
            $request->session()->put('user_email', $staff->staffEmail);
            
            // Redirect agency staff to their assigned inquiries page
            return redirect()->intended(route('agency.assigned.inquiries'));
        }
        
        // Try to find public user by email
        $user = \App\Models\PublicUser::where('UserEmail', $loginField)->first();
        if ($user && Hash::check($password, $user->Password)) {
            // Create a session for the public user
            $request->session()->put('user_id', $user->UserID);
            $request->session()->put('user_type', 'public');
            $request->session()->put('user_name', $user->UserName);
            $request->session()->put('user_email', $user->UserEmail);
            
            return redirect()->intended('/dashboard');
        }
        
        // Fallback to Laravel's default auth
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }
        
        return back()->with('error', 'Invalid credentials.');
    }public function showRegistrationForm()
    {
        return view('Module01.Public_user.register');
    }public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $email = $request->email;
        
        // Only allow public user registration through the public registration page
        // Admin and agency staff accounts are created through internal processes
        if (strpos($email, '@admin.com') !== false || strpos($email, '@agency.com') !== false) {
            return back()->withErrors(['email' => 'Admin and agency staff accounts cannot be created through public registration. Please contact your administrator.'])->withInput();
        }
        
        // Check if public user email already exists
        if(\App\Models\PublicUser::where('UserEmail', $email)->exists()) {
            return back()->withErrors(['email' => 'Email already exists'])->withInput();
        }
        
        \App\Models\PublicUser::create([
            'UserName' => $request->name,
            'UserEmail' => $email,
            'Password' => Hash::make($request->password),
            'UserPhoneNum' => '0000000000', // Default value
            'Useraddress' => 'Default Address', // Default value
        ]);

        $successMessage = "Registration successful! Please login with your email ({$email}) and password.";

        return redirect('login')->with('status', $successMessage);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
