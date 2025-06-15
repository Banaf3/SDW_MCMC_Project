<?php

namespace App\Http\Controllers\ManageInquiryFormSubmission\PublicUser;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicInquiriesController extends Controller
{
    public function index(Request $request)
    {
        $query = Inquiry::query()
            ->select([
                'InquiryID',
                'InquiryTitle',
                'InquiryDescription',
                'InquiryStatus',
                'SubmitionDate',
                'InquiryEvidence',
                // Exclude sensitive personal information
                // 'UserID' - hidden for privacy
                // Join with agencies to show agency name instead of ID
            ])
            ->leftJoin('agencies', 'inquiries.AgencyID', '=', 'agencies.AgencyID')
            ->addSelect('agencies.AgencyName')
            ->orderBy('SubmitionDate', 'desc');

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->get('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('InquiryTitle', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('InquiryDescription', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Status filter
        if ($request->filled('status') && $request->get('status') !== 'all') {
            $query->where('InquiryStatus', $request->get('status'));
        }

        // Agency filter
        if ($request->filled('agency') && $request->get('agency') !== 'all') {
            $query->where('inquiries.AgencyID', $request->get('agency'));
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->where('SubmitionDate', '>=', $request->get('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->where('SubmitionDate', '<=', $request->get('date_to'));
        }

        $inquiries = $query->paginate(10)->appends($request->query());

        // Get filter options for dropdowns
        $statuses = Inquiry::distinct()->pluck('InquiryStatus')->sort();
        $agencies = DB::table('agencies')->select('AgencyID', 'AgencyName')->get();        return view('ManageInquiryFormSubmission.PublicUser.public-inquiries', compact(
            'inquiries', 
            'statuses', 
            'agencies'
        ));
    }

    public function show($id)
    {
        $inquiry = Inquiry::select([
                'InquiryID',
                'InquiryTitle',
                'InquiryDescription',
                'InquiryStatus',
                'SubmitionDate',
                'InquiryEvidence',
                // Exclude sensitive personal information
                // 'UserID' - hidden for privacy
            ])
            ->leftJoin('agencies', 'inquiries.AgencyID', '=', 'agencies.AgencyID')
            ->addSelect('agencies.AgencyName')
            ->where('InquiryID', $id)
            ->firstOrFail();

        // Parse evidence JSON if it exists
        $evidence = $inquiry->InquiryEvidence ? json_decode($inquiry->InquiryEvidence, true) : [];

        return view('ManageInquiryFormSubmission.PublicUser.public-inquiry-details', compact('inquiry', 'evidence'));
    }
}
