<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Administrator;
use App\Models\AgencyStaff;
use App\Models\PublicUser;

class PasswordController extends Controller
{
    /**
     * Show the password change form
     */
    public function edit(Request $request)
    {
        $userId = $request->session()->get('user_id');
        $userType = $request->session()->get('user_type');
        
        if (!$userId || !$userType) {
            return redirect()->route('login')->with('error', 'You must be logged in to access this page.');
        }
        
        // Determine which view to show based on user type
        switch ($userType) {
            case 'admin':
                return view('Module01.MCMC_Admin.edit-password');
            case 'agency':
                return view('Module01.Agency.edit-password');
            case 'public':
                return view('Module01.Public_user.edit-password');
            default:
                return redirect()->route('login')->with('error', 'Invalid user type.');
        }
    }
    
    /**
     * Update the user's password
     */
    public function update(Request $request)
    {
        $userId = $request->session()->get('user_id');
        $userType = $request->session()->get('user_type');
        
        if (!$userId || !$userType) {
            return redirect()->route('login')->with('error', 'You must be logged in to access this page.');
        }
        
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);
        
        // Get the user based on type
        $user = $this->getUserModel($userId, $userType);
        
        if (!$user) {
            return back()->withErrors(['error' => 'User not found.']);
        }
        
        // Verify current password
        if (!Hash::check($request->current_password, $user->Password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }
        
        // Update password
        $user->Password = Hash::make($request->new_password);
        $user->save();
        
        return redirect()->route('profile.edit')->with('success', 'Password updated successfully!');
    }
    
    /**
     * Get user model based on type and ID
     */
    private function getUserModel($userId, $userType)
    {
        switch ($userType) {
            case 'admin':
                return Administrator::find($userId);
            case 'agency':
                return AgencyStaff::find($userId);
            case 'public':
                return PublicUser::find($userId);
            default:
                return null;
        }
    }
}
