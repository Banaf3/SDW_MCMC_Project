<?php

namespace App\Http\Controllers\ManageInquiryFormSubmission\PublicUser;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserInquiriesController extends Controller
{
    /**
     * Display a listing of the user's inquiries
     */
    public function index()
    {
        // For testing purposes, we'll use a hardcoded user ID
        // In production, use Auth::id() to get the authenticated user's ID
        $userId = 1; // Test user ID
        
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
        // For testing purposes, we'll use a hardcoded user ID
        // In production, use Auth::id() to get the authenticated user's ID
        $userId = 1; // Test user ID
        
        // Get the specific inquiry, ensuring it belongs to the current user
        $inquiry = Inquiry::where('InquiryID', $id)
            ->where('UserID', $userId)
            ->firstOrFail();
        
        return view('ManageInquiryFormSubmission.PublicUser.inquiry-details', compact('inquiry'));
    }
}
