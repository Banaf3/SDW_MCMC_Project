<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Administrator;
use App\Models\AgencyStaff;
use App\Models\PublicUser;
use App\Models\Agency;

class ProfileController extends Controller
{
    /**
     * Show the profile editing form
     */
    public function edit(Request $request)
    {
        $userId = $request->session()->get('user_id');
        $userType = $request->session()->get('user_type');
        
        if (!$userId || !$userType) {
            return redirect()->route('login')->with('error', 'You must be logged in to access this page.');
        }
        
        // Get the user data based on type
        $user = $this->getUserData($userId, $userType);
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'User not found.');
        }
          // Format the user object to have consistent property names for the view
        $formattedUser = $this->formatUserData($user, $userType);
        
        // Determine which view to show based on user type
        $viewPath = $this->getEditProfileViewPath($userType);
        
        return view($viewPath, ['user' => $formattedUser]);
    }
    
    /**
     * Update the user's profile
     */
    public function update(Request $request)
    {
        $userId = $request->session()->get('user_id');
        $userType = $request->session()->get('user_type');
        
        if (!$userId || !$userType) {
            return redirect()->route('login')->with('error', 'You must be logged in to access this page.');
        }        // Validate common fields - make all fields optional except for file validation
        $validationRules = [
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'profile_pic' => 'nullable|image|max:2048', // 2MB max
            'address' => 'nullable|string|max:500',
        ];
        
        $request->validate($validationRules);
        
        // Get the user model based on type
        $user = $this->getUserData($userId, $userType);
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'User not found.');
        }
          // Handle profile picture upload if provided
        $profilePicPath = null;
        if ($request->hasFile('profile_pic')) {
            // Make sure the directory exists
            $path = storage_path('app/public/profile_pics');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $profilePicPath = $request->file('profile_pic')->store('profile_pics', 'public');
            // Make sure the file is public
            chmod(storage_path('app/public/' . $profilePicPath), 0644);
        }
        
        // Update user data based on type
        switch ($userType) {            case 'admin':
                $userData = [];
                
                if ($request->filled('name')) {
                    $userData['AdminName'] = $request->name;
                    $request->session()->put('user_name', $request->name);
                }
                
                if ($request->filled('phone')) {
                    $userData['AdminPhoneNum'] = $request->phone;
                }
                
                if ($request->filled('address')) {
                    $userData['AdminAddress'] = $request->address;
                }
                
                if ($profilePicPath) {
                    $userData['ProfilePicture'] = $profilePicPath;
                }
                
                if (!empty($userData)) {
                    Administrator::where('AdminID', $userId)->update($userData);
                }
                break;                
            case 'agency':
                $userData = [];
                
                if ($request->filled('name')) {
                    $userData['StaffName'] = $request->name;
                    $request->session()->put('user_name', $request->name);
                }
                
                if ($request->filled('phone')) {
                    $userData['staffPhoneNum'] = $request->phone;
                }
                
                if ($profilePicPath) {
                    $userData['ProfilePic'] = $profilePicPath;
                }
                
                if (!empty($userData)) {
                    AgencyStaff::where('StaffID', $userId)->update($userData);
                }
                break;
                  case 'public':
                $userData = [];
                
                if ($request->filled('name')) {
                    $userData['UserName'] = $request->name;
                    $request->session()->put('user_name', $request->name);
                }
                
                if ($request->filled('phone')) {
                    $userData['UserPhoneNum'] = $request->phone;
                }
                
                if ($request->filled('address')) {
                    $userData['Useraddress'] = $request->address;
                }
                
                if ($profilePicPath) {
                    $userData['ProfilePic'] = $profilePicPath;
                }
                
                if (!empty($userData)) {
                    PublicUser::where('UserID', $userId)->update($userData);
                }
                break;
        }
        
        // Handle password change if requested
        if ($request->filled('current_password') && $request->filled('new_password')) {
            // Verify the current password
            if (!Hash::check($request->current_password, $this->getUserPassword($user, $userType))) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }
            
            // Update the password
            $this->updateUserPassword($user, $userType, $request->new_password);
        }
        
        return back()->with('success', 'Profile updated successfully!');
    }
      /**
     * Get the appropriate edit profile view path based on user type
     */
    private function getEditProfileViewPath($userType)
    {
        switch ($userType) {
            case 'admin':
                return 'Module01.MCMC_Admin.edit-profile';
            case 'agency':
                return 'Module01.Agency.edit-profile';
            case 'public':
                return 'Module01.Public_user.edit-profile';
            default:
                return 'Module01.edit'; // Fallback to shared view
        }
    }

    /**
     * Get user data based on type and ID
     */
    private function getUserData($userId, $userType)
    {
        switch ($userType) {
            case 'admin':
                return Administrator::where('AdminID', $userId)->first();
            case 'agency':
                return AgencyStaff::where('StaffID', $userId)->first();
            case 'public':
                return PublicUser::where('UserID', $userId)->first();
            default:
                return null;
        }
    }
      /**
     * Format user data for consistent property names in view
     */
    private function formatUserData($user, $userType)
    {
        $formatted = new \stdClass();
        
        switch ($userType) {
            case 'admin':
                $formatted->name = $user->AdminName;
                $formatted->email = $user->AdminEmail;
                $formatted->phone = $user->AdminPhoneNum;
                $formatted->address = $user->AdminAddress;
                $formatted->admin_role = $user->AdminRole;
                $formatted->profile_pic = $user->ProfilePicture;
                break;
                
            case 'agency':
                $formatted->name = $user->StaffName;
                $formatted->email = $user->staffEmail;
                $formatted->phone = $user->staffPhoneNum;
                $formatted->address = ''; // Agency staff might not have address in DB
                $formatted->profile_pic = $user->ProfilePic;
                
                // Get agency information
                $agency = \App\Models\Agency::where('AgencyID', $user->AgencyID)->first();
                $formatted->agency_name = $agency ? $agency->AgencyName : 'N/A';
                $formatted->agency_type = $agency ? $agency->AgencyType : 'N/A';
                break;
                
            case 'public':
                $formatted->name = $user->UserName;
                $formatted->email = $user->UserEmail;
                $formatted->phone = $user->UserPhoneNum;
                $formatted->address = $user->Useraddress;
                $formatted->profile_pic = $user->ProfilePic;
                break;
        }
        
        return $formatted;
    }
    
    /**
     * Get the password hash for a user
     */
    private function getUserPassword($user, $userType)
    {
        return $user->Password;
    }
    
    /**
     * Update a user's password
     */
    private function updateUserPassword($user, $userType, $newPassword)
    {
        switch ($userType) {
            case 'admin':
                Administrator::where('AdminID', $user->AdminID)
                    ->update(['Password' => Hash::make($newPassword)]);
                break;
            case 'agency':
                AgencyStaff::where('StaffID', $user->StaffID)
                    ->update(['Password' => Hash::make($newPassword)]);
                break;
            case 'public':
                PublicUser::where('UserID', $user->UserID)
                    ->update(['Password' => Hash::make($newPassword)]);
                break;
        }
    }
}
