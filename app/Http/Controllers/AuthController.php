<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $email = $request->email;
        $password = $request->password;
        
        // Check if it's an admin
        if (strpos($email, '@admin.com') !== false) {
            $admin = \App\Models\Administrator::where('AdminEmail', $email)->first();
            if ($admin && Hash::check($password, $admin->Password)) {
                // Create a session for the admin
                $request->session()->put('user_id', $admin->AdminID);
                $request->session()->put('user_type', 'admin');
                $request->session()->put('user_name', $admin->AdminName);
                $request->session()->put('user_email', $admin->AdminEmail);
                
                return redirect()->intended('/dashboard');
            }
        }
        
        // Check if it's an agency staff
        else if (strpos($email, '@agency.com') !== false) {
            $staff = \App\Models\AgencyStaff::where('staffEmail', $email)->first();
            if ($staff && Hash::check($password, $staff->Password)) {
                // Create a session for the agency staff
                $request->session()->put('user_id', $staff->StaffID);
                $request->session()->put('user_type', 'agency');
                $request->session()->put('user_name', $staff->StaffName);
                $request->session()->put('user_email', $staff->staffEmail);
                
                return redirect()->intended('/dashboard');
            }
        }
        
        // Check if it's a public user
        else {
            $user = \App\Models\PublicUser::where('UserEmail', $email)->first();
            if ($user && Hash::check($password, $user->Password)) {
                // Create a session for the public user
                $request->session()->put('user_id', $user->UserID);
                $request->session()->put('user_type', 'public');
                $request->session()->put('user_name', $user->UserName);
                $request->session()->put('user_email', $user->UserEmail);
                
                return redirect()->intended('/dashboard');
            }
        }
        
        // Fallback to Laravel's default auth
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }
        
        return back()->with('error', 'Invalid credentials.');
    }    public function showRegistrationForm()
    {
        return view('register');
    }    public function register(Request $request)
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
