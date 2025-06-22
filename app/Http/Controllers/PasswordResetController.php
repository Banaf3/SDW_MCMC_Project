<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Administrator;
use App\Models\AgencyStaff;
use App\Models\PublicUser;
use Illuminate\Support\Facades\Log;

/**
 * Password Reset Controller for handling password reset functionality
 */
class PasswordResetController extends Controller
{
    /**
     * Show the form to request a password reset link
     */
    public function showRequestForm()
    {
        return view('auth.passwords.email');
    }
    
    /**
     * Send a reset link to the user
     */
    public function sendResetLink(Request $request)
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
            
            // Create the reset URL
            $resetUrl = url(route('password.reset', [
                'token' => $token,
                'email' => $email,
            ], false));
            
            try {
                // Use a direct SQL query to check if the table exists
                $tableExists = DB::select("SHOW TABLES LIKE 'password_resets'");
                if (empty($tableExists)) {
                    // Table doesn't exist, create it
                    Schema::create('password_resets', function ($table) {
                        $table->id();
                        $table->string('email')->index();
                        $table->string('token');
                        $table->timestamp('created_at')->nullable();
                    });
                }
                    
                // Delete any existing tokens for this email
                DB::table('password_resets')->where('email', $email)->delete();
                
                // Add a new token
                DB::table('password_resets')->insert([
                    'email' => $email,
                    'token' => Hash::make($token),
                    'created_at' => Carbon::now()
                ]);
            } catch (\Exception $e) {
                // If there's a database error, log it and show a user-friendly message
                Log::error('Password reset database error: ' . $e->getMessage());
                return back()->withErrors(['email' => 'Database error. Please try again later or contact support.']);
            }
            
            // For development purposes, directly redirect to reset form
            return redirect()->route('password.reset', [
                'token' => $token,
                'email' => $email,
            ])->with('status', 'Email verified! You can now reset your password.');
            
        } catch (\Exception $e) {
            Log::error('Password reset general error: ' . $e->getMessage());
            return back()->withErrors(['email' => 'An error occurred. Please try again later.']);
        }
    }
    
    /**
     * Show the password reset form
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }
    
    /**
     * Reset the password
     */
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
                return back()->withErrors(['email' => 'No account found with this email address.']);
            }
            
            // Check if the token is valid
            try {
                $passwordReset = DB::table('password_resets')
                    ->where('email', $email)
                    ->first();
                    
                if (!$passwordReset || !Hash::check($request->token, $passwordReset->token)) {
                    return back()->withErrors(['token' => 'Invalid or expired password reset token.']);
                }
            } catch (\Exception $e) {
                Log::error('Password reset token check error: ' . $e->getMessage());
                return back()->withErrors(['token' => 'Error validating token. Please try requesting a new password reset.']);
            }
            
            // Update the password based on user type
            switch($userTable) {
                case 'administrators':
                    Administrator::where('AdminEmail', $email)
                        ->update(['Password' => Hash::make($request->password)]);
                    break;
                case 'agency_staff':
                    AgencyStaff::where('staffEmail', $email)
                        ->update(['Password' => Hash::make($request->password)]);
                    break;
                case 'public_users':
                    PublicUser::where('UserEmail', $email)
                        ->update(['Password' => Hash::make($request->password)]);
                    break;
                default:
                    return back()->withErrors(['email' => 'Could not reset password. Please try again later.']);
            }
            
            // Delete the token
            try {
                DB::table('password_resets')->where('email', $email)->delete();
            } catch (\Exception $e) {
                // If we can't delete the token, log it but continue (non-critical error)
                Log::warning('Could not delete password reset token: ' . $e->getMessage());
            }
            
            return redirect()->route('login')->with('status', 'Your password has been reset! You can now log in with your new password.');
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
            if (strpos($email, '@admin.com') !== false) {
                $exists = Administrator::where('AdminEmail', $email)->exists();
                return $exists ? 'administrators' : null;
            } elseif (strpos($email, '@agency.com') !== false) {
                $exists = AgencyStaff::where('staffEmail', $email)->exists();
                return $exists ? 'agency_staff' : null;
            } else {
                $exists = PublicUser::where('UserEmail', $email)->exists();
                return $exists ? 'public_users' : null;
            }
        } catch (\Exception $e) {
            Log::error('User table check error: ' . $e->getMessage());
            return null;
        }
    }
}
