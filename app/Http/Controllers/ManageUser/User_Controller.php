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
use App\Models\Agency;

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
            return $this->redirectAfterLogin($user['type'], $user['data']);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput();
    }

    /**
     * Attempt authentication across all user types
     */
    private function attemptAuthentication($loginField, $password, $request)
    {
        // Try administrators first (if email contains @admin.com or direct admin match)
        if (strpos($loginField, '@admin.com') !== false || filter_var($loginField, FILTER_VALIDATE_EMAIL) === false) {
            $admin = $this->tryAdminAuthentication($loginField, $password);
            if ($admin) {
                $this->setUserSession($admin, 'admin');
                return ['type' => 'admin', 'data' => $admin];
            }
        }

        // Try agency staff authentication (if email contains @agency.com or looks like agency email)
        if (strpos($loginField, '@agency.com') !== false || filter_var($loginField, FILTER_VALIDATE_EMAIL)) {
            $agencyStaff = $this->tryAgencyAuthentication($loginField, $password);
            if ($agencyStaff) {
                $this->setUserSession($agencyStaff, 'agency');
                return ['type' => 'agency', 'data' => $agencyStaff];
            }
        }

        // Try public user authentication (email format)
        if (filter_var($loginField, FILTER_VALIDATE_EMAIL)) {
            $publicUser = $this->tryPublicAuthentication($loginField, $password);
            if ($publicUser) {
                $this->setUserSession($publicUser, 'public');
                return ['type' => 'public', 'data' => $publicUser];
            }
        }

        return null;
    }

    /**
     * Try administrator authentication
     */
    private function tryAdminAuthentication($loginField, $password)
    {
        // Try by email first, then by username
        $admin = Administrator::where('AdminEmail', $loginField)
                              ->orWhere('Username', $loginField)
                              ->first();

        if ($admin && Hash::check($password, $admin->Password)) {
            return $admin;
        }

        return null;
    }

    /**
     * Try agency staff authentication
     */
    private function tryAgencyAuthentication($loginField, $password)
    {
        // Try by email first, then by username
        $staff = AgencyStaff::where('staffEmail', $loginField)
                           ->orWhere('Username', $loginField)
                           ->first();

        if ($staff && Hash::check($password, $staff->Password)) {
            return $staff;
        }

        return null;
    }

    /**
     * Try public user authentication
     */
    private function tryPublicAuthentication($loginField, $password)
    {
        $user = PublicUser::where('UserEmail', $loginField)->first();

        if ($user && Hash::check($password, $user->Password)) {
            return $user;
        }

        return null;
    }    /**
     * Set user session data and track login history
     */
    private function setUserSession($user, $type)
    {
        $sessionData = [
            'user_type' => $type,
            'is_logged_in' => true,
        ];

        // Track login history
        $this->trackLoginHistory($user, $type);

        switch ($type) {
            case 'admin':
                $sessionData['user_id'] = $user->AdminID;
                $sessionData['user_name'] = $user->AdminName;
                $sessionData['user_email'] = $user->AdminEmail;
                $sessionData['user_role'] = $user->AdminRole;
                break;
            case 'agency':
                $sessionData['user_id'] = $user->StaffID;
                $sessionData['user_name'] = $user->StaffName;
                $sessionData['user_email'] = $user->staffEmail;
                $sessionData['agency_id'] = $user->AgencyID;
                $sessionData['password_change_required'] = $user->password_change_required ?? false;
                break;
            case 'public':
                $sessionData['user_id'] = $user->UserID;
                $sessionData['user_name'] = $user->UserName;
                $sessionData['user_email'] = $user->UserEmail;
                break;
        }

        session($sessionData);
    }

    /**
     * Track user login history
     */
    private function trackLoginHistory($user, $type)
    {
        try {
            // Get current login history
            $loginHistory = $user->LoginHistory ? json_decode($user->LoginHistory, true) : [];
            
            // Add new login entry
            $newLogin = [
                'timestamp' => now()->toISOString(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ];
            
            // Keep only last 10 login records
            array_unshift($loginHistory, $newLogin);
            $loginHistory = array_slice($loginHistory, 0, 10);
            
            // Update the user's login history
            $user->update(['LoginHistory' => json_encode($loginHistory)]);
            
        } catch (\Exception $e) {
            // Silently fail if login history tracking fails
            \Log::warning('Failed to track login history for user: ' . $user->getKey());
        }
    }

    /**
     * Redirect user after successful login
     */
    private function redirectAfterLogin($userType, $userData)
    {
        switch ($userType) {
            case 'admin':
                return redirect('/dashboard')->with('success', 'Welcome back, ' . $userData->AdminName . '!');
            case 'agency':
                // Check if password change is required
                if ($userData->password_change_required) {
                    return redirect()->route('agency.password.change.forced')
                                   ->with('warning', 'Please change your password to continue.');
                }
                return redirect('/dashboard')->with('success', 'Welcome back, ' . $userData->StaffName . '!');
            case 'public':
                return redirect('/dashboard')->with('success', 'Welcome back, ' . $userData->UserName . '!');
            default:
                return redirect('/login');
        }
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
        // Clear all session data
        $request->session()->flush();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
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
     * Update user data based on user type
     */
    private function updateUserData($user, $userType, $request, $profilePicPath = null)
    {
        $updateData = [];
        
        // Handle profile picture
        if ($profilePicPath) {
            $updateData['ProfilePic'] = $profilePicPath;
        }
        
        // Handle common fields based on user type
        switch ($userType) {
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
                break;
                
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
                break;
        }
        
        // Update the user if there's data to update
        if (!empty($updateData)) {
            $user->update($updateData);
        }
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
        $passwordField = 'Password';
        
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
        
        $email = $request->email;
        
        // Check if user exists (in any of the user tables)
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
    }
    
    /**
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
        
        // Check if token is not expired (24 hours)        if (Carbon::parse($passwordReset->created_at)->addHours(24)->isPast()) {
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
        }
          // Find and update the user's password
        $userUpdated = false;
        
        // Check administrators
        $admin = Administrator::where('AdminEmail', $email)->first();
        if ($admin) {
            $admin->update(['Password' => Hash::make($password)]);
            $userUpdated = true;
        } elseif ($staff = AgencyStaff::where('staffEmail', $email)->first()) {
            // Check agency staff
            $staff->update(['Password' => Hash::make($password)]);
            $userUpdated = true;
        } elseif ($publicUser = PublicUser::where('UserEmail', $email)->first()) {
            // Check public users
            $publicUser->update(['Password' => Hash::make($password)]);
            $userUpdated = true;
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
     * AGENCY MANAGEMENT METHODS (Admin Only)
     * ====================================
     */
    
    /**
     * Show agency and staff management page (Admin only)
     */
    public function showAgencyManagement(Request $request)
    {
        if (!session('user_type') || session('user_type') !== 'admin') {
            return redirect('/login')->with('error', 'Only administrators can access the agency management page.');
        }
        
        // Get all agencies for dropdowns and management
        $agencies = Agency::orderBy('AgencyName')->get();
        
        // Get all agency staff with their agencies
        $agencyStaff = AgencyStaff::with('agency')->orderBy('created_at', 'desc')->get();
        
        // Calculate stats
        $stats = [
            'total_agencies' => $agencies->count(),
            'total_staff' => $agencyStaff->count(),
            'active_agencies' => $agencies->where('deleted_at', null)->count(),
            'recent_registrations' => $agencyStaff->where('created_at', '>=', Carbon::now()->subDays(30))->count(),
        ];
        
        return view('Module01.MCMC_Admin.agency-staff-management', compact(
            'agencies', 
            'agencyStaff', 
            'stats'
        ));
    }
    
    /**
     * Create a new agency (Admin only)
     */
    public function createAgency(Request $request)
    {
        if (!session('user_type') || session('user_type') !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $request->validate([
            'agency_name' => 'required|string|max:255|unique:agencies,AgencyName',
            'agency_type' => 'required|string|max:100',
            'agency_email' => 'nullable|email|max:255',
            'agency_phone' => 'nullable|string|max:20',
            'agency_address' => 'nullable|string|max:500',
            'agency_description' => 'nullable|string|max:1000',
        ]);
        
        try {
            $agency = Agency::create([
                'AgencyName' => $request->agency_name,
                'AgencyType' => $request->agency_type,
                'AgencyEmail' => $request->agency_email,
                'AgencyPhoneNum' => $request->agency_phone,
                'AgencyAddress' => $request->agency_address,
                'AgencyDescription' => $request->agency_description,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            return redirect()->back()->with('success', 'Agency created successfully!');
            
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to create agency. Please try again.'])->withInput();
        }
    }
    
    /**
     * Register agency staff (Admin only) - Enhanced version
     */
    public function registerAgencyStaff(Request $request)
    {
        if (!session('user_type') || session('user_type') !== 'admin') {
            return redirect('/login')->with('error', 'Unauthorized access');
        }
        
        $request->validate([
            'staff_name' => 'required|string|max:255',
            'agency_id' => 'required|exists:agencies,AgencyID',
            'staff_email' => 'nullable|email|max:255|unique:agency_staff,staffEmail',
            'staff_phone' => 'nullable|string|max:20',
            'staff_role' => 'nullable|string|max:100',
        ]);

        try {
            // Get agency details
            $agency = Agency::find($request->agency_id);
              // Generate unique username based on staff name
            $staffNameParts = explode(' ', trim($request->staff_name));
            $firstName = strtolower($staffNameParts[0]);
            $lastName = isset($staffNameParts[1]) ? strtolower($staffNameParts[1]) : '';
            
            // Create base username using first name + last name or just first name
            if ($lastName) {
                $baseUsername = $firstName . '.' . $lastName;
            } else {
                $baseUsername = $firstName;
            }
            
            // Remove any special characters and ensure it's clean
            $baseUsername = preg_replace('/[^a-z0-9.]/', '', $baseUsername);
            
            $username = $baseUsername;
            $counter = 1;
            
            // Ensure username is unique
            while (AgencyStaff::where('Username', $username)->exists()) {
                $username = $baseUsername . $counter;
                $counter++;
            }
            
            // Generate random password
            $password = $this->generateRandomPassword();
            
            // Create agency staff user
            $staff = AgencyStaff::create([
                'StaffName' => $request->staff_name,
                'AgencyID' => $request->agency_id,
                'staffEmail' => $request->staff_email,
                'staffPhoneNum' => $request->staff_phone,
                'StaffRole' => $request->staff_role ?? 'Staff',
                'Username' => $username,
                'Password' => Hash::make($password),
                'password_change_required' => true, // Force password change on first login
                'created_at' => now(),
                'updated_at' => now(),
            ]);
              // Store credentials in session for display (showing that email was sent)
            session()->flash('new_staff_credentials', [
                'staff_name' => $request->staff_name,
                'agency_name' => $agency->AgencyName,
                'username' => $username,
                'email' => $request->staff_email ?? 'Not provided',
                'password' => $password,
                'login_url' => url('/login'),
                'email_sent' => true, // Flag to show email sent message
            ]);
            
            return redirect()->back()->with('success', 'Agency staff registered successfully! Login credentials have been sent to the staff member\'s email address.');
            
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to register staff member. Please try again.'])->withInput();
        }
    }
    
    /**
     * Generate a random password
     */
    private function generateRandomPassword($length = 12)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*';
        $password = '';
        
        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        return $password;
    }

    /**
     * ====================================
     * ADMIN USER MANAGEMENT METHODS
     * ====================================
     */
      /**
     * Show user management page (Admin only)
     */
    public function showUserManagement(Request $request)
    {
        if (!session('user_type') || session('user_type') !== 'admin') {
            return redirect('/login')->with('error', 'Only administrators can access the user management page.');
        }
        
        // Get search and filter parameters
        $search = $request->get('search', '');
        $userType = $request->get('user_type', 'all');
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
          // Get user data with search and filters (exclude deleted users)
        $administrators = $this->getFilteredUsers('administrators', $search, $sortBy, $sortOrder);
        $agencyStaff = $this->getFilteredUsers('agency_staff', $search, $sortBy, $sortOrder);
        $publicUsers = $this->getFilteredUsers('public_users', $search, $sortBy, $sortOrder);
        
        // Get all agencies for reports dropdown
        $agencies = Agency::orderBy('AgencyName')->get();
        
        // Calculate summary statistics
        $stats = [
            'total_users' => $administrators->count() + $agencyStaff->count() + $publicUsers->count(),
            'total_admins' => $administrators->count(),
            'total_staff' => $agencyStaff->count(),
            'total_public' => $publicUsers->count(),
            'recent_registrations' => $this->getRecentRegistrations(),
        ];
        
        return view('Module01.MCMC_Admin.user-management', compact(
            'administrators', 
            'agencyStaff', 
            'publicUsers', 
            'agencies',
            'stats',
            'search',
            'userType',
            'sortBy',
            'sortOrder'
        ));
    }
    
    /**
     * Get filtered users based on search criteria
     */
    private function getFilteredUsers($table, $search, $sortBy, $sortOrder)
    {
        $query = null;
        
        switch ($table) {
            case 'administrators':
                $query = Administrator::query();
                if ($search) {
                    $query->where(function($q) use ($search) {
                        $q->where('AdminName', 'LIKE', "%{$search}%")
                          ->orWhere('AdminEmail', 'LIKE', "%{$search}%")
                          ->orWhere('Username', 'LIKE', "%{$search}%");
                    });
                }
                break;
                
            case 'agency_staff':
                $query = AgencyStaff::with('agency');
                if ($search) {
                    $query->where(function($q) use ($search) {
                        $q->where('StaffName', 'LIKE', "%{$search}%")
                          ->orWhere('staffEmail', 'LIKE', "%{$search}%")
                          ->orWhere('Username', 'LIKE', "%{$search}%");
                    });
                }
                break;
                
            case 'public_users':
                $query = PublicUser::query();
                if ($search) {
                    $query->where(function($q) use ($search) {
                        $q->where('UserName', 'LIKE', "%{$search}%")
                          ->orWhere('UserEmail', 'LIKE', "%{$search}%")
                          ->orWhere('UserPhoneNum', 'LIKE', "%{$search}%");
                    });
                }
                break;
        }
        
        // Apply sorting
        $validSortColumns = [
            'administrators' => ['AdminName', 'AdminEmail', 'created_at', 'AdminRole'],
            'agency_staff' => ['StaffName', 'staffEmail', 'created_at', 'AgencyID'],
            'public_users' => ['UserName', 'UserEmail', 'created_at']
        ];
        
        if (in_array($sortBy, $validSortColumns[$table])) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('created_at', 'desc');
        }
        
        return $query->get();
    }
    
    /**
     * Get recent registrations count (last 30 days)
     */
    private function getRecentRegistrations()
    {
        $thirtyDaysAgo = Carbon::now()->subDays(30);
        
        $recentAdmins = Administrator::where('created_at', '>=', $thirtyDaysAgo)->count();
        $recentStaff = AgencyStaff::where('created_at', '>=', $thirtyDaysAgo)->count();
        $recentPublic = PublicUser::where('created_at', '>=', $thirtyDaysAgo)->count();
        
        return $recentAdmins + $recentStaff + $recentPublic;
    }
    
    /**
     * Get user details for modal view
     */
    public function getUserDetails(Request $request)
    {
        if (!session('user_type') || session('user_type') !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $userId = $request->get('user_id');
        $userType = $request->get('user_type');
        
        try {
            switch ($userType) {
                case 'admin':
                    $user = Administrator::find($userId);
                    $userData = [
                        'id' => $user->AdminID,
                        'name' => $user->AdminName,
                        'email' => $user->AdminEmail,
                        'username' => $user->Username,
                        'phone' => $user->AdminPhoneNum,
                        'address' => $user->AdminAddress,
                        'role' => $user->AdminRole,
                        'created_at' => $user->created_at,
                        'updated_at' => $user->updated_at,
                        'login_history' => $user->LoginHistory ?? [],
                        'profile_pic' => null,
                        'type' => 'Administrator'
                    ];
                    break;
                    
                case 'agency':
                    $user = AgencyStaff::with('agency')->find($userId);
                    $userData = [
                        'id' => $user->StaffID,
                        'name' => $user->StaffName,
                        'email' => $user->staffEmail,
                        'username' => $user->Username,
                        'phone' => $user->staffPhoneNum,
                        'agency' => $user->agency->AgencyName ?? 'Unknown',
                        'created_at' => $user->created_at,
                        'updated_at' => $user->updated_at,
                        'login_history' => $user->LoginHistory ?? [],
                        'profile_pic' => $user->ProfilePic,
                        'password_change_required' => $user->password_change_required,
                        'type' => 'Agency Staff'
                    ];
                    break;
                    
                case 'public':
                    $user = PublicUser::find($userId);
                    $userData = [
                        'id' => $user->UserID,
                        'name' => $user->UserName,
                        'email' => $user->UserEmail,
                        'phone' => $user->UserPhoneNum,
                        'address' => $user->Useraddress,
                        'created_at' => $user->created_at,
                        'updated_at' => $user->updated_at,
                        'login_history' => $user->LoginHistory ?? [],
                        'profile_pic' => $user->ProfilePic,
                        'type' => 'Public User'
                    ];
                    break;
                    
                default:
                    return response()->json(['error' => 'Invalid user type'], 400);
            }
            
            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }
            
            return response()->json(['user' => $userData]);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve user details'], 500);
        }
    }    /**
     * Delete user permanently (Admin only)
     */    public function deleteUser(Request $request)
    {
        // Log all request data for debugging
        \Log::info("=== DELETE USER REQUEST START ===");
        \Log::info("Delete user request received", [
            'session_user_type' => session('user_type'),
            'session_user_id' => session('user_id'),
            'request_method' => $request->method(),
            'request_all' => $request->all(),
            'request_json' => $request->json() ? $request->json()->all() : null,
            'content_type' => $request->header('Content-Type'),
            'headers' => $request->headers->all(),
            'raw_body' => $request->getContent()
        ]);

        if (!session('user_type') || session('user_type') !== 'admin') {
            \Log::warning("Unauthorized delete attempt", [
                'session_user_type' => session('user_type'),
                'session' => session()->all()
            ]);
            return response()->json(['error' => 'Unauthorized'], 403);
        }
          // Try to get data from different sources (form data or JSON)
        $userId = $request->input('user_id') ?? $request->json('user_id');
        $userType = $request->input('user_type') ?? $request->json('user_type');
        $reason = $request->input('reason') ?? $request->json('reason') ?? 'Deleted by administrator';
        
        \Log::info("Parsed delete request data", [
            'user_id' => $userId,
            'user_type' => $userType,
            'reason' => $reason
        ]);
        
        if (!$userId || !$userType) {
            \Log::error("Missing required parameters", [
                'user_id' => $userId,
                'user_type' => $userType
            ]);
            return response()->json(['error' => 'User ID and type are required'], 400);
        }
        
        try {
            $user = null;
            
            switch ($userType) {
                case 'admin':
                    $user = Administrator::find($userId);
                    \Log::info("Looking for admin user", [
                        'user_id' => $userId,
                        'found' => $user ? true : false,
                        'user_data' => $user ? $user->toArray() : null
                    ]);
                    // Prevent self-deletion
                    if ($user && $user->AdminID == session('user_id')) {
                        \Log::warning("Attempted self-deletion", [
                            'user_id' => $userId,
                            'session_user_id' => session('user_id')
                        ]);
                        return response()->json(['error' => 'Cannot delete your own account'], 400);
                    }
                    break;
                    
                case 'agency':
                    $user = AgencyStaff::find($userId);
                    \Log::info("Looking for agency user", [
                        'user_id' => $userId,
                        'found' => $user ? true : false,
                        'user_data' => $user ? $user->toArray() : null
                    ]);
                    break;
                    
                case 'public':
                    $user = PublicUser::find($userId);
                    \Log::info("Looking for public user", [
                        'user_id' => $userId,
                        'found' => $user ? true : false,
                        'user_data' => $user ? $user->toArray() : null
                    ]);
                    break;
                    
                default:
                    \Log::error("Invalid user type", ['user_type' => $userType]);
                    return response()->json(['error' => 'Invalid user type'], 400);
            }
            
            if (!$user) {
                \Log::warning("User not found for deletion", [
                    'user_id' => $userId,
                    'user_type' => $userType
                ]);
                return response()->json(['error' => 'User not found'], 404);
            }
            
            $userName = $user->AdminName ?? $user->StaffName ?? $user->UserName;
            
            // Log deletion before removing user
            \Log::info("About to delete user", [
                'deleted_user_id' => $userId,
                'deleted_user_type' => $userType,
                'deleted_user_name' => $userName,
                'deleted_by_admin_id' => session('user_id'),
                'reason' => $reason,
                'timestamp' => now()
            ]);
            
            // Permanently delete the user
            $deleted = $user->delete();
            
            \Log::info("User deletion result", [
                'user_id' => $userId,
                'user_type' => $userType,
                'deletion_result' => $deleted,
                'user_exists_after_delete' => $userType === 'admin' ? Administrator::find($userId) !== null : 
                                             ($userType === 'agency' ? AgencyStaff::find($userId) !== null : 
                                              PublicUser::find($userId) !== null)
            ]);
            
            \Log::info("=== DELETE USER REQUEST END ===");
            
            return response()->json([
                'success' => true, 
                'message' => 'User deleted successfully',
                'deleted_user' => $userName
            ]);
            
        } catch (\Exception $e) {
            \Log::error("Failed to delete user", [
                'user_id' => $userId,
                'user_type' => $userType,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['error' => 'Failed to delete user: ' . $e->getMessage()], 500);
        }
    }

    /**
     * ====================================
     * HELPER METHODS
     * ====================================
     */

    /**
     * Get user data based on user ID and type
     */
    private function getUserData($userId, $userType)
    {
        switch ($userType) {
            case 'public':
                return PublicUser::find($userId);
            case 'agency':
                return AgencyStaff::with('agency')->find($userId);
            case 'admin':
                return Administrator::find($userId);
            default:
                return null;
        }
    }

    /**
     * Format user data for display or editing
     */
    private function formatUserData($user, $userType)
    {
        $formatted = [
            'id' => $user->UserID ?? $user->StaffID ?? $user->AdminID,
            'name' => $user->UserName ?? $user->StaffName ?? $user->AdminName,
            'email' => $user->UserEmail ?? $user->staffEmail ?? $user->AdminEmail,
            'phone' => $user->UserPhoneNum ?? $user->staffPhoneNum ?? $user->AdminPhoneNum,
            'address' => $user->Useraddress ?? $user->AdminAddress ?? null,
            'role' => $user->AdminRole ?? 'N/A',
            'created_at' => $user->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $user->updated_at->format('Y-m-d H:i:s'),
            'login_history' => $user->LoginHistory ?? [],
            'profile_pic' => $user->ProfilePic ?? null,
            'password_change_required' => $user->password_change_required ?? false,
            'type' => ucfirst($userType)
        ];
        
        return $formatted;
    }
}
