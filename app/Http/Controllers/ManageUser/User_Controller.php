<?php

namespace App\Http\Controllers\ManageUser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\PublicUser;
use App\Models\AgencyStaff;
use App\Models\Administrator;

class User_Controller extends Controller
{
    /**
     * ====================================
     * USER AUTHENTICATION METHODS (Public Users & Agency Staff)
     * ====================================
     */
    
    /**
     * Show user login form
     */
    public function showLoginForm()
    {
        return view('Module01.login');
    }
      /**
     * Handle user login (Public Users, Agency Staff & Administrators)
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

        // Determine login type based on input format and try authentication
        $user = $this->attemptAuthentication($loginField, $password, $request);
        
        if ($user) {
            return $user; // Returns redirect response
        }
        
        return back()->with('error', 'Invalid credentials.')->withInput();
    }    /**
     * Attempt authentication for all user types with smart detection
     */
    private function attemptAuthentication($loginField, $password, $request)
    {
        // Check if input looks like email (contains @)
        $isEmail = filter_var($loginField, FILTER_VALIDATE_EMAIL);
        
        // Strategy 1: Try Admin authentication (prioritize username, then email)
        $admin = null;
        if (!$isEmail) {
            // Try username first for non-email inputs
            $admin = Administrator::findByUsername($loginField);
        }
        if (!$admin && $isEmail) {
            // Try email if username failed or input is email format
            $admin = Administrator::findByEmail($loginField);
        }
        if ($admin && Hash::check($password, $admin->Password)) {
            return $this->createUserSession($admin, 'admin', $request);
        }
        
        // Strategy 2: Try Agency Staff authentication (prioritize username, then email)
        $staff = null;
        if (!$isEmail) {
            // Try username first for non-email inputs
            $staff = AgencyStaff::findByUsername($loginField);
        }
        if (!$staff && $isEmail) {
            // Try email if username failed or input is email format
            $staff = AgencyStaff::findByEmail($loginField);
        }
        if ($staff && Hash::check($password, $staff->Password)) {
            // Check if password change is required (first login)
            if ($staff->password_change_required) {
                $this->createUserSession($staff, 'agency', $request);
                return redirect()->route('password.change')
                    ->with('warning', 'Welcome! For security reasons, you must change your password before continuing.');
            }
            return $this->createUserSession($staff, 'agency', $request);
        }
        
        // Strategy 3: Try Public User authentication (email only)
        if ($isEmail) {
            $publicUser = PublicUser::findForLogin($loginField);
            if ($publicUser && Hash::check($password, $publicUser->Password)) {
                return $this->createUserSession($publicUser, 'public', $request);
            }
        }
        
        return null; // Authentication failed
    }

    /**
     * Create user session and redirect
     */
    private function createUserSession($user, $userType, $request)
    {
        switch ($userType) {
            case 'admin':
                $request->session()->put('user_id', $user->AdminID);
                $request->session()->put('user_type', 'admin');
                $request->session()->put('user_name', $user->AdminName);
                $request->session()->put('user_email', $user->AdminEmail);
                $request->session()->put('username', $user->Username);
                break;
                
            case 'agency':
                $request->session()->put('user_id', $user->StaffID);
                $request->session()->put('user_type', 'agency');
                $request->session()->put('user_name', $user->StaffName);
                $request->session()->put('user_email', $user->staffEmail);
                $request->session()->put('username', $user->Username);
                break;
                
            case 'public':
                $request->session()->put('user_id', $user->UserID);
                $request->session()->put('user_type', 'public');
                $request->session()->put('user_name', $user->UserName);
                $request->session()->put('user_email', $user->UserEmail);
                // Public users don't have usernames
                break;
        }
        
        return redirect()->intended('/dashboard');
    }
    
    /**
     * Show user registration form (Public Users only)
     */
    public function showRegistrationForm()
    {
        return view('Module01.Public_user.register');
    }
    
    /**
     * Handle user registration (Public Users only)
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $email = $request->email;
        
        // Only allow public user registration through the public registration page
        if (strpos($email, '@admin.com') !== false || strpos($email, '@agency.com') !== false) {
            return back()->withErrors(['email' => 'Admin and agency staff accounts cannot be created through public registration. Please contact your administrator.'])->withInput();
        }
        
        // Check if public user email already exists
        if (PublicUser::where('UserEmail', $email)->exists()) {
            return back()->withErrors(['email' => 'Email already exists'])->withInput();
        }
        
        PublicUser::create([
            'UserName' => $request->name,
            'UserEmail' => $email,
            'Password' => Hash::make($request->password),
            'UserPhoneNum' => '0000000000', // Default value
            'Useraddress' => 'Default Address', // Default value
        ]);

        $successMessage = "Registration successful! Please login with your email ({$email}) and password.";
        return redirect('login')->with('status', $successMessage);
    }
    
    /**
     * Handle user logout
     */
    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
    
    /**
     * ====================================
     * USER PROFILE MANAGEMENT METHODS
     * ====================================
     */
      /**
     * Show user profile editing form
     */
    public function editProfile(Request $request)
    {
        $userId = $request->session()->get('user_id');
        $userType = $request->session()->get('user_type');
        
        if (!$userId || !in_array($userType, ['public', 'agency', 'admin'])) {
            return redirect()->route('login')->with('error', 'You must be logged in to access this page.');
        }
        
        // Get the user data based on type
        $user = $this->getUserData($userId, $userType);
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'User not found.');
        }
        
        // Format the user data for the view
        $formattedUser = $this->formatUserData($user, $userType);
        
        // Determine which view to show based on user type
        switch ($userType) {
            case 'agency':
                $viewPath = 'Module01.Agency.edit-profile';
                break;
            case 'admin':
                $viewPath = 'Module01.MCMC_Admin.edit-profile';
                break;
            default:
                $viewPath = 'Module01.Public_user.edit-profile';
                break;
        }
        
        return view($viewPath, ['user' => $formattedUser]);
    }
      /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $userId = $request->session()->get('user_id');
        $userType = $request->session()->get('user_type');
        
        if (!$userId || !in_array($userType, ['public', 'agency', 'admin'])) {
            return redirect()->route('login')->with('error', 'You must be logged in to access this page.');
        }
        
        // Validate common fields
        $validationRules = [
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'profile_pic' => 'nullable|image|max:2048', // 2MB max
            'address' => 'nullable|string|max:500',
        ];
        
        // Add email validation for agency staff (must be unique if provided)
        if ($userType === 'agency') {
            $validationRules['email'] = 'nullable|email|max:255|unique:agency_staff,staffEmail,' . $userId . ',StaffID';
        }
        
        $request->validate($validationRules);
        
        // Get the user model based on type
        $user = $this->getUserData($userId, $userType);
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'User not found.');
        }
        
        // Handle profile picture upload
        $profilePicPath = null;
        if ($request->hasFile('profile_pic')) {
            $path = storage_path('app/public/profile_pics');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $profilePicPath = $request->file('profile_pic')->store('profile_pics', 'public');
            chmod(storage_path('app/public/' . $profilePicPath), 0644);
        }
        
        // Update user profile based on type
        $this->updateUserData($user, $userType, $request, $profilePicPath);
        
        // Update session data if name changed
        if ($request->filled('name')) {
            $request->session()->put('user_name', $request->name);
        }
        
        // Update session email for agency staff if changed
        if ($userType === 'agency' && $request->filled('email')) {
            $request->session()->put('user_email', $request->email);
        }
        
        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
    
    /**
     * ====================================
     * USER PASSWORD MANAGEMENT METHODS
     * ====================================
     */
      /**
     * Show password change form
     */
    public function editPassword(Request $request)
    {
        $userId = $request->session()->get('user_id');
        $userType = $request->session()->get('user_type');
        
        if (!$userId || !in_array($userType, ['public', 'agency', 'admin'])) {
            return redirect()->route('login')->with('error', 'You must be logged in to access this page.');
        }
        
        // Determine which view to show based on user type
        switch ($userType) {
            case 'agency':
                $viewPath = 'Module01.Agency.edit-password';
                break;
            case 'admin':
                $viewPath = 'Module01.MCMC_Admin.edit-password';
                break;
            default:
                $viewPath = 'Module01.Public_user.edit-password';
                break;
        }
        
        return view($viewPath);
    }
      /**
     * Update user password
     */
    public function updatePassword(Request $request)
    {
        $userId = $request->session()->get('user_id');
        $userType = $request->session()->get('user_type');
        
        if (!$userId || !in_array($userType, ['public', 'agency', 'admin'])) {
            return redirect()->route('login')->with('error', 'You must be logged in to access this page.');
        }
        
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);
        
        // Get the user model based on type
        $user = $this->getUserData($userId, $userType);
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'User not found.');
        }
        
        // Get the password field name based on user type
        $passwordField = $userType === 'public' ? 'Password' : 'Password';
        
        // Check current password
        if (!Hash::check($request->current_password, $user->$passwordField)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }
        
        // Update password and clear password change requirement for agency staff
        $updateData = [$passwordField => Hash::make($request->new_password)];
        if ($userType === 'agency') {
            $updateData['password_change_required'] = false;
            $updateData['password_changed_at'] = now();
        }
        
        $user->update($updateData);
        
        return redirect()->back()->with('success', 'Password updated successfully!');
    }
    
    /**
     * ====================================
     * AGENCY STAFF FORCED PASSWORD CHANGE (First Login)
     * ====================================
     */
    
    /**
     * Show forced password change form for agency staff
     */
    public function showForcedPasswordChange()
    {
        // Only allow agency staff to access this page
        if (session('user_type') !== 'agency') {
            return redirect('/login')->with('error', 'Access denied.');
        }

        return view('Module01.Agency.edit-password');
    }

    /**
     * Handle forced password change for agency staff
     */
    public function updateForcedPassword(Request $request)
    {
        // Only allow agency staff
        if (session('user_type') !== 'agency') {
            return redirect('/login')->with('error', 'Access denied.');
        }

        $staffId = session('user_id');
        $staff = AgencyStaff::find($staffId);

        if (!$staff) {
            return redirect('/login')->with('error', 'User not found.');
        }

        // Validate with strong password requirements
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/'
            ],
        ], [
            'new_password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
            'new_password.min' => 'Password must be at least 8 characters long.',
            'new_password.confirmed' => 'Password confirmation does not match.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Check if current password is correct
        if (!Hash::check($request->current_password, $staff->Password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.'])->withInput();
        }

        // Check if new password is different from current password
        if (Hash::check($request->new_password, $staff->Password)) {
            return back()->withErrors(['new_password' => 'New password must be different from your current password.'])->withInput();
        }

        // Update the password
        $staff->update([
            'Password' => Hash::make($request->new_password),
            'password_change_required' => false,
            'password_changed_at' => now(),
        ]);

        return redirect('/dashboard')->with('success', 'Password changed successfully! You can now access all features.');
    }
    
    /**
     * ====================================
     * PASSWORD RESET METHODS
     * ====================================
     */
    
    /**
     * Show forgot password form
     */
    public function showForgotPasswordForm()
    {
        return view('Module01.recovery');
    }
      /**
     * Send password reset link email
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        
        $email = $request->email;        // Check if user exists (in any of the user tables)
        $user = null;
        $userType = null;
        
        // Check administrators
        $admin = Administrator::where('AdminEmail', $email)->first();
        if ($admin) {
            $user = $admin;
            $userType = 'administrator';
        }
        
        // Check agency staff
        if (!$user) {
            $staff = AgencyStaff::where('staffEmail', $email)->first();
            if ($staff) {
                $user = $staff;
                $userType = 'agency_staff';
            }
        }
        
        // Check public users
        if (!$user) {
            $publicUser = PublicUser::where('UserEmail', $email)->first();
            if ($publicUser) {
                $user = $publicUser;
                $userType = 'public_user';
            }
        }
        
        if (!$user) {
            return back()->withErrors(['email' => 'We could not find a user with that email address.']);
        }
        
        // Generate token
        $token = Str::random(64);
        
        // Delete existing password reset records for this email
        DB::table('password_resets')->where('email', $email)->delete();
          // Insert new password reset record
        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token, // Store plain token for easier lookup
            'created_at' => now(),
        ]);
          // For demo purposes, redirect directly to reset page instead of sending email
        return redirect()->route('password.reset', ['token' => $token])
                        ->with('success', 'Password reset request processed. Please enter your new password below.');
    }    /**
     * Show password reset form
     */
    public function showResetForm($token)
    {
        // Find the password reset record by token
        $passwordReset = DB::table('password_resets')
            ->where('token', $token)
            ->first();
        
        if (!$passwordReset) {
            return redirect()->route('password.request')
                           ->withErrors(['token' => 'Invalid password reset token.']);
        }
        
        // Check if token is not expired (24 hours)
        if (Carbon::parse($passwordReset->created_at)->addHours(24)->isPast()) {
            return redirect()->route('password.request')
                           ->withErrors(['token' => 'Password reset token has expired.']);
        }
        
        return view('Module01.reset', [
            'token' => $token,
            'email' => $passwordReset->email
        ]);
    }
      /**
     * Reset password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);
        
        $email = $request->email;
        $token = $request->token;
        $password = $request->password;
          // Find the password reset record
        $passwordReset = DB::table('password_resets')
            ->where('email', $email)
            ->where('token', $token)
            ->first();
          if (!$passwordReset) {
            return back()->withErrors(['email' => 'Invalid password reset token.']);
        }
        
        // Check if token is not expired (24 hours)
        if (Carbon::parse($passwordReset->created_at)->addHours(24)->isPast()) {
            return back()->withErrors(['token' => 'Password reset token has expired.']);
        }// Find and update the user's password
        $userUpdated = false;
        
        // Check administrators
        $admin = Administrator::where('AdminEmail', $email)->first();
        if ($admin) {
            $admin->update(['Password' => Hash::make($password)]);
            $userUpdated = true;
        }
        
        // Check agency staff
        if (!$userUpdated) {
            $staff = AgencyStaff::where('staffEmail', $email)->first();
            if ($staff) {
                $staff->update(['Password' => Hash::make($password)]);
                $userUpdated = true;
            }
        }
        
        // Check public users
        if (!$userUpdated) {
            $publicUser = PublicUser::where('UserEmail', $email)->first();
            if ($publicUser) {
                $publicUser->update(['Password' => Hash::make($password)]);
                $userUpdated = true;
            }
        }
        
        if (!$userUpdated) {
            return back()->withErrors(['email' => 'Could not find user with that email address.']);
        }
        
        // Delete the password reset record
        DB::table('password_resets')->where('email', $email)->delete();
        
        return redirect()->route('login')->with('success', 'Password has been reset successfully. You can now login with your new password.');
    }

    /**
     * ====================================
     * AGENCY STAFF REGISTRATION METHODS (Admin Only)
     * ====================================
     */
    
    /**
     * Show agency registration form (Admin only)
     */
    public function showAgencyRegistrationForm()
    {
        if (!session('user_type') || session('user_type') !== 'admin') {
            return redirect('/login')->with('error', 'Only administrators can access the agency registration page.');
        }
        
        $agencies = \App\Models\Agency::all();
        return view('Module01.MCMC_Admin.agency-registration', compact('agencies'));
    }
    
    /**
     * Register new agency staff (Admin only) - with username system
     */
    public function registerAgencyStaff(Request $request)
    {
        if (!session('user_type') || session('user_type') !== 'admin') {
            return redirect('/login')->with('error', 'Only administrators can register agency staff.');
        }
          $request->validate([
            'staff_name' => 'nullable|string|max:255',  // Name is now optional
            'agency_id' => 'required|exists:agencies,AgencyID',
            'staff_phone' => 'nullable|string|max:20',  // Phone is now optional
            'staff_email' => 'required|email|max:255|unique:agency_staff,staffEmail', // Email is now required
        ]);
        
        $agency = \App\Models\Agency::findOrFail($request->agency_id);
        
        // Generate unique username using the model method
        $username = AgencyStaff::generateUniqueUsername($request->staff_name, $request->agency_id);
        
        // Generate secure temporary password
        $tempPassword = 'Staff' . random_int(10000, 99999) . '!';
          // Create agency staff with username system
        $staff = AgencyStaff::create([
            'StaffName' => $request->staff_name ?: 'Agency Staff', // Default name if not provided
            'Username' => $username,
            'Password' => Hash::make($tempPassword),
            'staffEmail' => $request->staff_email, // Required now
            'staffPhoneNum' => $request->staff_phone ?: '0000000000', // Default phone if not provided
            'AgencyID' => $request->agency_id,
            'password_change_required' => true, // Force password change on first login
        ]);
        
        // Store credentials for display
        $credentials = [
            'staff_name' => $request->staff_name ?: 'Agency Staff',
            'agency_name' => $agency->AgencyName,
            'username' => $username,
            'email' => $request->staff_email,
            'password' => $tempPassword,
            'login_url' => route('login'),
        ];
        
        return redirect()->back()->with([
            'success' => 'Agency staff registered successfully with username: ' . $username,
            'new_staff_credentials' => $credentials
        ]);
    }
    
    /**
     * Alias for registerAgencyStaff to maintain backward compatibility
     */
    public function registerAgency(Request $request)
    {
        return $this->registerAgencyStaff($request);
    }
    
    /**
     * ====================================
     * ADMINISTRATOR CREATION METHODS
     * ====================================
     */
    
    /**
     * Create new administrator account with username system
     */
    public function createAdministrator(Request $request)
    {
        if (!session('user_type') || session('user_type') !== 'admin') {
            return redirect('/login')->with('error', 'Only administrators can create other administrator accounts.');
        }
        
        $request->validate([
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|max:255|unique:administrators,AdminEmail',
            'admin_phone' => 'required|string|max:20',
            'admin_address' => 'nullable|string|max:500',
            'admin_role' => 'required|string|in:Super Admin,Admin,Moderator',
        ]);
        
        // Generate unique username using the model method
        $username = Administrator::generateUniqueUsername($request->admin_name);
        
        // Generate secure temporary password
        $tempPassword = 'Admin' . random_int(10000, 99999) . '!';
        
        // Create administrator with username system
        $admin = Administrator::create([
            'AdminName' => $request->admin_name,
            'Username' => $username,
            'AdminEmail' => $request->admin_email,
            'Password' => Hash::make($tempPassword),
            'AdminRole' => $request->admin_role,
            'AdminPhoneNum' => $request->admin_phone,
            'AdminAddress' => $request->admin_address,
        ]);
        
        // Store credentials for display
        $credentials = [
            'admin_name' => $request->admin_name,
            'username' => $username,
            'email' => $request->admin_email,
            'password' => $tempPassword,
            'role' => $request->admin_role,
            'login_url' => route('admin.login'),
        ];
        
        return redirect()->back()->with([
            'success' => 'Administrator created successfully with username: ' . $username,
            'new_admin_credentials' => $credentials
        ]);
    }

    /**
     * Create new agency (Admin only)
     */
    public function createAgency(Request $request)
    {
        if (!session('user_type') || session('user_type') !== 'admin') {
            return redirect('/login')->with('error', 'Only administrators can create agencies.');
        }
        
        $request->validate([
            'agency_name' => 'required|string|max:255',
            'agency_email' => 'required|email|unique:agencies,AgencyEmail',
            'agency_phone' => 'required|string|max:20',
            'agency_type' => 'required|string|max:100',
            'agency_address' => 'nullable|string|max:500',
        ]);
        
        \App\Models\Agency::create([
            'AgencyName' => $request->agency_name,
            'AgencyEmail' => $request->agency_email,
            'AgencyPhoneNum' => $request->agency_phone,
            'AgencyType' => $request->agency_type,
            'AgencyAddress' => $request->agency_address,
        ]);
        
        return redirect()->route('admin.agency.management')
                         ->with('success', 'Agency created successfully!');
    }

    /**
     * ====================================
     * HELPER METHODS
     * ====================================
     */
      /**
     * Get user data based on type
     */
    private function getUserData($userId, $userType)
    {
        switch ($userType) {
            case 'agency':
                return AgencyStaff::find($userId);
            case 'public':
                return PublicUser::find($userId);
            case 'admin':
                return Administrator::find($userId);
            default:
                return null;
        }
    }
      /**
     * Format user data for consistent view usage
     */
    private function formatUserData($user, $userType)
    {
        switch ($userType) {
            case 'agency':
                return [
                    'id' => $user->StaffID,
                    'name' => $user->StaffName,
                    'username' => $user->Username,
                    'email' => $user->staffEmail,
                    'phone' => $user->staffPhoneNum,
                    'address' => null, // Agency staff doesn't have address field
                    'profile_pic' => $user->ProfilePic,
                    'role' => 'Agency Staff'
                ];            case 'public':
                return [
                    'id' => $user->UserID,
                    'name' => $user->UserName,
                    'username' => null, // Public users don't have usernames
                    'email' => $user->UserEmail,
                    'phone' => $user->UserPhoneNum,
                    'address' => $user->Useraddress,
                    'profile_pic' => $user->ProfilePic ?? null,
                    'role' => 'Public User'
                ];
            case 'admin':
                return [
                    'id' => $user->AdminID,
                    'name' => $user->AdminName,
                    'username' => $user->Username,
                    'email' => $user->AdminEmail,
                    'phone' => $user->AdminPhoneNum,
                    'address' => $user->AdminAddress,
                    'profile_pic' => null, // Admin doesn't have profile pic field
                    'role' => $user->AdminRole
                ];
            default:
                return null;
        }
    }
      /**
     * Update user data based on type
     */
    private function updateUserData($user, $userType, $request, $profilePicPath)
    {
        $updateData = [];
        
        switch ($userType) {
            case 'agency':
                if ($request->filled('name')) {
                    $updateData['StaffName'] = $request->name;
                }
                if ($request->filled('email')) {
                    $updateData['staffEmail'] = $request->email;
                }
                if ($request->filled('phone')) {
                    $updateData['staffPhoneNum'] = $request->phone;
                }
                if ($profilePicPath) {
                    $updateData['ProfilePic'] = $profilePicPath;
                }
                break;
                  case 'public':
                if ($request->filled('name')) {
                    $updateData['UserName'] = $request->name;
                }
                if ($request->filled('phone')) {
                    $updateData['UserPhoneNum'] = $request->phone;
                }
                if ($request->filled('address')) {
                    $updateData['Useraddress'] = $request->address;
                }
                if ($profilePicPath) {
                    $updateData['ProfilePic'] = $profilePicPath;
                }
                break;
                
            case 'admin':
                if ($request->filled('name')) {
                    $updateData['AdminName'] = $request->name;
                }
                if ($request->filled('phone')) {
                    $updateData['AdminPhoneNum'] = $request->phone;
                }
                if ($request->filled('address')) {
                    $updateData['AdminAddress'] = $request->address;
                }
                // Admin profile pictures are not implemented yet
                break;
        }
        
        if (!empty($updateData)) {
            $user->update($updateData);
        }
    }
    
    /**
     * Show agency login form
     */
    public function showAgencyLoginForm()
    {
        return view('Module01.Agency.login');
    }
    
    /**
     * Handle agency staff login
     */
    public function agencyLogin(Request $request)
    {
        // This method can use the same logic as the regular login method
        // since it handles both public users and agency staff
        return $this->login($request);
    }
    
    /**
     * Show agency profile edit form
     */
    public function editAgencyProfile(Request $request)
    {
        $userId = session('user_id');
        $userType = session('user_type');
        
        if (!$userId || $userType !== 'agency') {
            return redirect()->route('login')->with('error', 'Agency access required.');
        }
        
        $user = $this->getUserData($userId, $userType);
        if (!$user) {
            return redirect()->route('login')->with('error', 'User not found.');
        }
        
        $formattedUser = $this->formatUserData($user, $userType);
        return view('Module01.Agency.edit-profile', ['user' => $formattedUser]);
    }
    
    /**
     * Update agency profile
     */
    public function updateAgencyProfile(Request $request)
    {
        // Use the same logic as updateProfile but ensure it's for agency users
        $userType = session('user_type');
        if ($userType !== 'agency') {
            return redirect()->route('login')->with('error', 'Agency access required.');
        }
        
        return $this->updateProfile($request);
    }
    
    /**
     * Show agency password change form
     */
    public function showChangePasswordForm()
    {
        $userId = session('user_id');
        $userType = session('user_type');
        
        if (!$userId || $userType !== 'agency') {
            return redirect()->route('login')->with('error', 'Agency access required.');
        }
        
        return view('Module01.Agency.change-password');
    }
    
    /**
     * Handle agency password change
     */
    public function changePassword(Request $request)
    {
        // Use the same logic as updateForcedPassword for agency staff
        return $this->updateForcedPassword($request);
    }
    
    /**
     * Show agency management page (Admin only)
     */
    public function showAgencyManagement()
    {
        if (!session('user_type') || session('user_type') !== 'admin') {
            return redirect('/login')->with('error', 'Only administrators can access the agency management page.');
        }
        
        $agencies = \App\Models\Agency::all();
        return view('Module01.MCMC_Admin.agency-management', compact('agencies'));
    }
}