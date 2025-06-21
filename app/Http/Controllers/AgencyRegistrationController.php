<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agency;
use App\Models\AgencyStaff;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AgencyRegistrationController extends Controller
{
    /**
     * Show the agency registration form (only accessible by admins).
     */
    public function showRegistrationForm()
    {
        // Check if user is logged in as admin
        if (!session('user_type') || session('user_type') !== 'admin') {
            return redirect('/login')->with('error', 'Only administrators can access the agency registration page.');
        }        $agencies = Agency::all();
        return view('Module01.MCMC_Admin.agency-registration', compact('agencies'));
    }

    /**
     * Register a new agency staff member (only accessible by admins).
     */
    public function register(Request $request)
    {
        // Check if user is logged in as admin
        if (!session('user_type') || session('user_type') !== 'admin') {
            return redirect('/login')->with('error', 'Only administrators can register agency staff.');
        }

        $request->validate([
            'staff_name' => 'required|string|max:255',
            'agency_id' => 'required|exists:agencies,AgencyID',
            'staff_phone' => 'required|string|max:20',
        ]);        // Get the selected agency
        $agency = Agency::findOrFail($request->agency_id);
        
        // Generate unique email and password for the agency staff
        $baseEmail = strtolower(str_replace(' ', '', $request->staff_name));
        $email = $baseEmail . '@agency.com';
        
        // Ensure email is unique
        $counter = 1;
        $originalEmail = $email;
        while (AgencyStaff::where('staffEmail', $email)->exists()) {
            $email = str_replace('@', $counter . '@', $originalEmail);
            $counter++;
        }

        // Generate a secure random password
        $password = Str::random(12);

        // Create the agency staff record
        $staff = AgencyStaff::create([
            'StaffName' => $request->staff_name,
            'staffEmail' => $email,
            'Password' => Hash::make($password),
            'staffPhoneNum' => $request->staff_phone,
            'AgencyID' => $request->agency_id,
        ]);

        // Simulate sending credentials via email (hypothetical)
        $emailData = [
            'staff_name' => $request->staff_name,
            'agency_name' => $agency->AgencyName,
            'email' => $email,
            'password' => $password,
            'login_url' => url('/login')
        ];

        // Store the credentials in session to display to admin
        session()->flash('new_staff_credentials', $emailData);

        return redirect()->route('admin.agency.register')
                         ->with('success', 'Agency staff registered successfully! Credentials have been generated and would be sent via email.');
    }

    /**
     * Show the agency management page for admins.
     */
    public function showAgencyManagement()
    {
        // Check if user is logged in as admin
        if (!session('user_type') || session('user_type') !== 'admin') {
            return redirect('/login')->with('error', 'Only administrators can access agency management.');
        }        $agencies = Agency::with('staff')->get();
        return view('Module01.MCMC_Admin.agency-management', compact('agencies'));
    }

    /**
     * Create a new agency (for admin use).
     */
    public function createAgency(Request $request)
    {
        // Check if user is logged in as admin
        if (!session('user_type') || session('user_type') !== 'admin') {
            return redirect('/login')->with('error', 'Only administrators can create agencies.');
        }

        $request->validate([
            'agency_name' => 'required|string|max:255',
            'agency_email' => 'required|email|unique:agencies,AgencyEmail',
            'agency_phone' => 'required|string|max:20',
            'agency_type' => 'required|string|max:100',
        ]);

        Agency::create([
            'AgencyName' => $request->agency_name,
            'AgencyEmail' => $request->agency_email,
            'AgencyPhoneNum' => $request->agency_phone,
            'AgencyType' => $request->agency_type,
        ]);

        return redirect()->route('admin.agency.management')
                         ->with('success', 'Agency created successfully!');
    }
}
