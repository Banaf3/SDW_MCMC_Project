<?php

namespace App\Http\Controllers\ManageInquiryFormSubmission\PublicUser;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserInquiriesController extends Controller
{    /**
     * Display a listing of the user's inquiries
     */
    public function index()
    {
        // Get the current authenticated user's ID from session
        $userId = session('user_id');
        
        // If no user is logged in, redirect to login or show empty state
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please log in to view your inquiries.');
        }
        
        // Get all inquiries for the current user, sorted by submission date (most recent first)
        $inquiries = Inquiry::where('UserID', $userId)
            ->orderByDesc('SubmitionDate')
            ->get();
        
        return view('ManageInquiryFormSubmission.PublicUser.user-inquiries', compact('inquiries'));
    }
      /**
     * Display details of a specific inquiry
     */
    public function show($id)
    {
        // Get the current authenticated user's ID from session
        $userId = session('user_id');
        
        // If no user is logged in, redirect to login
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please log in to view inquiry details.');
        }
        
        // Get the specific inquiry, ensuring it belongs to the current user
        $inquiry = Inquiry::where('InquiryID', $id)
            ->where('UserID', $userId)
            ->firstOrFail();
        
        return view('ManageInquiryFormSubmission.PublicUser.inquiry-details', compact('inquiry'));
    }
}
