<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Administrator;
use App\Models\AgencyStaff;
use App\Models\PublicUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class NewPasswordResetController extends Controller
{    
    public function showLinkRequestForm()
    {
        return view('Module01.recovery');
    }
    
    public function showRequestForm()
    {
        return view('Module01.recovery');
    }

    public function sendResetLinkEmail(Request $request)
    {
        try {
            $request->validate(['email' => 'required|email']);
            
            $email = $request->email;
            $token = Str::random(64);
            
            // Check what type of user is trying to reset
            $userTable = $this->getUserTable($email);
            
            if (!$userTable) {
                return back()->withErrors(['email' => 'No account found with this email address.']);
            }
            
            // Ensure the password_resets table exists
            try {
                // Check if the table exists
                $tableExists = Schema::hasTable('password_resets');
                Log::info('Password reset table check: ' . ($tableExists ? 'Exists' : 'Does not exist'));
                
                if (!$tableExists) {
                    Log::warning('Password resets table does not exist. Attempting to create it.');                    Schema::create('password_resets', function ($table) {
                        $table->id();
                        $table->string('email')->index();
                        $table->string('token');
                        $table->timestamp('created_at')->nullable();
                    });
                    Log::info('Password resets table created successfully.');
                }
                
                // Delete any existing tokens for this email
                DB::table('password_resets')->where('email', $email)->delete();
                
                // Create the token in the database - store as plain text for easier debugging
                DB::table('password_resets')->insert([
                    'email' => $email,
                    'token' => $token, // Store the plain token 
                    'created_at' => Carbon::now()
                ]);
                Log::info('Password reset token created for ' . $email . ': ' . $token);
            } catch (\Exception $e) {
                Log::error('Error with password_resets table: ' . $e->getMessage());
                return back()->withErrors(['email' => 'Database error. Please try again later.']);
            }
            
            // For development purposes, directly redirect to reset form
            return redirect()->route('password.reset', [
                'token' => $token,
                'email' => $email,
            ])->with('status', 'Email verified! You can now reset your password.');
            
        } catch (\Exception $e) {
            Log::error('Password reset error: ' . $e->getMessage());
            return back()->withErrors(['email' => 'An error occurred. Please try again later.']);
        }
    }
    
    public function sendResetLink(Request $request)
    {
        return $this->sendResetLinkEmail($request);
    }

    public function showResetForm(Request $request, $token = null)
    {
        return view('Module01.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    public function reset(Request $request)
    {
        try {
            $request->validate([
                'token' => 'required',
                'email' => 'required|email',
                'password' => 'required|min:6|confirmed',
            ]);

            $email = $request->email;
            $userTable = $this->getUserTable($email);
            
            if (!$userTable) {
                Log::error('No user found with email: ' . $email);
                return back()->withErrors(['email' => 'No account found with this email address.']);
            }
            
            // Check if the token is valid
            try {
                $passwordReset = DB::table('password_resets')
                    ->where('email', $email)
                    ->first();
                
                Log::info('Password reset token validation - Email: ' . $email . ', Token exists in DB: ' . ($passwordReset ? 'Yes' : 'No'));
                    
                if (!$passwordReset) {
                    return back()->withErrors(['token' => 'No password reset request found for this email.']);
                }
                
                // For development, we're using direct token comparison instead of hashing
                // Since we stored the token as plain text in the sendResetLink method
                $tokenValid = $request->token === $passwordReset->token;
                
                Log::info('Token validation - Input: ' . $request->token . ', DB: ' . $passwordReset->token . ', Result: ' . ($tokenValid ? 'Valid' : 'Invalid'));
                
                if (!$tokenValid) {
                    return back()->withErrors(['token' => 'Invalid password reset token. Please try again.']);
                }
            } catch (\Exception $e) {
                Log::error('Password reset token check error: ' . $e->getMessage());
                return back()->withErrors(['token' => 'Error validating token. Please try requesting a new password reset.']);
            }
            
            // Hash the new password
            $newPassword = Hash::make($request->password);
            Log::info('New password hash generated');
            
            // Update the password based on user type
            try {
                switch($userTable) {
                    case 'administrators':
                        // Debug the admin records
                        $admin = Administrator::where('AdminEmail', $email)->first();
                        if ($admin) {
                            Log::info('Found admin record: ID=' . $admin->AdminID . ', Name=' . $admin->AdminName);
                            
                            // Update with direct model instance for better debugging
                            $admin->Password = $newPassword;
                            $result = $admin->save();
                            
                            Log::info('Administrator password reset: ' . ($result ? 'Success' : 'Failed') . ' for ' . $email);
                        } else {
                            Log::error('Admin record not found for email: ' . $email);
                            $result = false;
                        }
                        break;
                        
                    case 'agency_staff':
                        // Debug the agency staff records
                        $staff = AgencyStaff::where('staffEmail', $email)->first();
                        if ($staff) {
                            Log::info('Found agency staff record: ID=' . $staff->StaffID . ', Name=' . $staff->StaffName);
                            
                            // Update with direct model instance for better debugging
                            $staff->Password = $newPassword;
                            $result = $staff->save();
                            
                            Log::info('Agency staff password reset: ' . ($result ? 'Success' : 'Failed') . ' for ' . $email);
                        } else {
                            Log::error('Agency staff record not found for email: ' . $email);
                            $result = false;
                        }
                        break;
                        
                    case 'public_users':
                        // Debug the public user records
                        $user = PublicUser::where('UserEmail', $email)->first();
                        if ($user) {
                            Log::info('Found public user record: ID=' . $user->UserID . ', Name=' . $user->UserName);
                            
                            // Update with direct model instance for better debugging
                            $user->Password = $newPassword;
                            $result = $user->save();
                            
                            Log::info('Public user password reset: ' . ($result ? 'Success' : 'Failed') . ' for ' . $email);
                        } else {
                            Log::error('Public user record not found for email: ' . $email);
                            $result = false;
                        }
                        break;
                        
                    default:
                        $result = false;
                        Log::error('Unknown user type for ' . $email);
                }
                
                if (!$result) {
                    return back()->withErrors(['email' => 'Failed to update the password. Please try again.']);
                }
            } catch (\Exception $e) {
                Log::error('Password update error: ' . $e->getMessage());
                Log::error('Stack trace: ' . $e->getTraceAsString());
                return back()->withErrors(['email' => 'Error updating password: ' . $e->getMessage()]);
            }
            
            // Delete the token
            try {
                DB::table('password_resets')->where('email', $email)->delete();
                Log::info('Password reset token deleted for ' . $email);
            } catch (\Exception $e) {
                // If we can't delete the token, log it but continue (non-critical error)
                Log::warning('Could not delete password reset token: ' . $e->getMessage());
            }
            
            // Success! Add a success message that will persist to the login page
            Log::info('Password reset successful for ' . $email);
            return redirect()->route('login')
                ->with('status', 'Password reset successful! You can now log in with your new password.');
        } catch (\Exception $e) {
            Log::error('Password reset general error: ' . $e->getMessage());
            return back()->withErrors(['email' => 'An error occurred. Please try again later.']);
        }
    }
    
    /**
     * Determine which user table contains the email
     */
    private function getUserTable($email)
    {
        try {
            Log::info('Checking user type for email: ' . $email);
            
            if (strpos($email, '@admin.com') !== false) {
                $exists = Administrator::where('AdminEmail', $email)->exists();
                Log::info('Admin check for ' . $email . ': ' . ($exists ? 'Found' : 'Not found'));
                return $exists ? 'administrators' : null;
            } elseif (strpos($email, '@agency.com') !== false) {
                $exists = AgencyStaff::where('staffEmail', $email)->exists();
                Log::info('Agency staff check for ' . $email . ': ' . ($exists ? 'Found' : 'Not found'));
                return $exists ? 'agency_staff' : null;
            } else {
                $exists = PublicUser::where('UserEmail', $email)->exists();
                Log::info('Public user check for ' . $email . ': ' . ($exists ? 'Found' : 'Not found'));
                return $exists ? 'public_users' : null;
            }
        } catch (\Exception $e) {
            Log::error('User table check error: ' . $e->getMessage());
            return null;
        }
    }
}
