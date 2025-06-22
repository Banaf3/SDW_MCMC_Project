<?php

namespace App\Http\Controllers\ManageInquiryFormSubmission\Agency;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use App\Models\InquiryAuditLog;
use App\Models\Agency;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AssignedInquiriesController extends Controller
{    /**
     * Display assigned inquiries for the agency with filtering capabilities
     */
    public function index(Request $request)
    {        // Get the current agency ID from session (assuming agency authentication)
        $agencyId = session('agency_id');
        
        // Temporary: If no agency_id in session, use agency ID 1 for testing
        if (!$agencyId) {
            $agencyId = 1; // Default to first agency for testing
            session(['agency_id' => $agencyId]); // Set it in session for subsequent requests
        }

        // Only show inquiries within the ManageInquiryFormSubmission context
        // Restrict to the four allowed statuses only
        $allowedStatuses = ['Under Investigation', 'Verified as True', 'Identified as Fake', 'Rejected'];
        
        $query = Inquiry::with(['user', 'agency', 'auditLogs.administrator'])
            ->where('AgencyID', $agencyId)
            ->whereIn('InquiryStatus', $allowedStatuses);

        // Apply status filter (only from allowed statuses)
        if ($request->filled('status') && in_array($request->status, $allowedStatuses)) {
            $query->where('InquiryStatus', $request->status);
        }

        // Apply date range filters
        if ($request->filled('date_from')) {
            $query->whereDate('SubmitionDate', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('SubmitionDate', '<=', $request->date_to);
        }

        // Apply category/subject filter
        if ($request->filled('category')) {
            $query->where('InquiryTitle', 'like', '%' . $request->category . '%');
        }

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('InquiryTitle', 'like', '%' . $search . '%')
                  ->orWhere('InquiryDescription', 'like', '%' . $search . '%');
            });
        }

        // Get filtered inquiries
        $inquiries = $query->orderByDesc('SubmitionDate')->paginate(15);

        // Get statistics for the dashboard (only for allowed statuses)
        $totalAssigned = Inquiry::where('AgencyID', $agencyId)
            ->whereIn('InquiryStatus', $allowedStatuses)->count();
        $underInvestigation = Inquiry::where('AgencyID', $agencyId)
            ->where('InquiryStatus', 'Under Investigation')->count();
        $verified = Inquiry::where('AgencyID', $agencyId)
            ->where('InquiryStatus', 'Verified as True')->count();
        $fake = Inquiry::where('AgencyID', $agencyId)
            ->where('InquiryStatus', 'Identified as Fake')->count();
        $rejected = Inquiry::where('AgencyID', $agencyId)
            ->where('InquiryStatus', 'Rejected')->count();

        // Get available statuses for filter dropdown (only allowed ones)
        $statuses = $allowedStatuses;

        // Get available categories (based on inquiry titles/subjects)
        $categories = Inquiry::where('AgencyID', $agencyId)
            ->whereIn('InquiryStatus', $allowedStatuses)
            ->distinct('InquiryTitle')
            ->pluck('InquiryTitle')
            ->map(function($title) {
                // Extract category from title (first few words)
                return implode(' ', array_slice(explode(' ', $title), 0, 2));
            })
            ->unique()
            ->values();

        return view('ManageInquiryFormSubmission.Agency.assigned-inquiries', compact(
            'inquiries',
            'totalAssigned',
            'underInvestigation',
            'verified',
            'fake',
            'rejected',
            'statuses',
            'categories'
        ));
    }    /**
     * Display detailed view of a specific inquiry assigned to the agency
     */
    public function show($inquiryId)
    {        $agencyId = session('agency_id');
        
        // Temporary: If no agency_id in session, use agency ID 1 for testing
        if (!$agencyId) {
            $agencyId = 1;
            session(['agency_id' => $agencyId]);
        }

        // Only allow access to inquiries with allowed statuses within ManageInquiryFormSubmission
        $allowedStatuses = ['Under Investigation', 'Verified as True', 'Identified as Fake', 'Rejected'];

        $inquiry = Inquiry::with(['user', 'agency', 'evidence', 'auditLogs.administrator'])
            ->where('InquiryID', $inquiryId)
            ->where('AgencyID', $agencyId)
            ->whereIn('InquiryStatus', $allowedStatuses)
            ->firstOrFail();

        // Get full inquiry history (audit logs)
        $inquiryHistory = InquiryAuditLog::with('administrator')
            ->where('InquiryID', $inquiryId)
            ->orderByDesc('ActionDate')
            ->get();

        return view('ManageInquiryFormSubmission.Agency.inquiry-detail', compact(
            'inquiry',
            'inquiryHistory'
        ));
    }    /**
     * Update inquiry status (for agency actions within ManageInquiryFormSubmission)
     */    public function updateStatus(Request $request, $inquiryId)
    {
        $agencyId = session('agency_id');
        
        // Temporary: If no agency_id in session, use agency ID 1 for testing
        if (!$agencyId) {
            $agencyId = 1;
            session(['agency_id' => $agencyId]);
        }

        // Define allowed statuses for agencies within ManageInquiryFormSubmission
        $allowedStatuses = ['Under Investigation', 'Verified as True', 'Identified as Fake', 'Rejected'];
        
        // Validate that the new status is allowed
        if (!in_array($request->status, $allowedStatuses)) {
            return redirect()->back()->with('error', 'Invalid status. Only allowed statuses are: ' . implode(', ', $allowedStatuses));
        }

        $inquiry = Inquiry::where('InquiryID', $inquiryId)
            ->where('AgencyID', $agencyId)
            ->whereIn('InquiryStatus', $allowedStatuses)
            ->firstOrFail();

        $oldStatus = $inquiry->InquiryStatus;
        $newStatus = $request->status;

        // Update inquiry status (only within allowed statuses)
        $inquiry->update([
            'InquiryStatus' => $newStatus
        ]);

        // Create audit log entry within ManageInquiryFormSubmission context
        InquiryAuditLog::create([
            'InquiryID' => $inquiryId,
            'AdminID' => null, // This is an agency action, not admin
            'Action' => 'Status Updated by Agency (ManageInquiryFormSubmission)',
            'OldStatus' => $oldStatus,
            'NewStatus' => $newStatus,
            'ActionDate' => now(),
            'Reason' => $request->reason ?? 'Status updated by assigned agency within investigation workflow',
            'Notes' => $request->notes,
            'PerformedBy' => 'Agency ID: ' . $agencyId . ' (ManageInquiryFormSubmission)'
        ]);

        return redirect()->back()->with('success', 'Inquiry status updated successfully to: ' . $newStatus);
    }    /**
     * Add agency comments/notes to an inquiry (within ManageInquiryFormSubmission)
     */    public function addComment(Request $request, $inquiryId)
    {
        $agencyId = session('agency_id');
        
        // Temporary: If no agency_id in session, use agency ID 1 for testing
        if (!$agencyId) {
            $agencyId = 1;
            session(['agency_id' => $agencyId]);
        }

        // Only allow comments on inquiries with allowed statuses
        $allowedStatuses = ['Under Investigation', 'Verified as True', 'Identified as Fake', 'Rejected'];

        $inquiry = Inquiry::where('InquiryID', $inquiryId)
            ->where('AgencyID', $agencyId)
            ->whereIn('InquiryStatus', $allowedStatuses)
            ->firstOrFail();

        // Create audit log entry for the comment within ManageInquiryFormSubmission
        InquiryAuditLog::create([
            'InquiryID' => $inquiryId,
            'AdminID' => null,
            'Action' => 'Agency Investigation Comment (ManageInquiryFormSubmission)',
            'OldStatus' => $inquiry->InquiryStatus,
            'NewStatus' => $inquiry->InquiryStatus,
            'ActionDate' => now(),
            'Reason' => 'Agency investigation update within ManageInquiryFormSubmission workflow',
            'Notes' => $request->comment,
            'PerformedBy' => 'Agency ID: ' . $agencyId . ' (ManageInquiryFormSubmission)'
        ]);

        return redirect()->back()->with('success', 'Investigation comment added successfully.');
    }    /**
     * Generate agency inquiry report (within ManageInquiryFormSubmission context)
     */    public function generateReport(Request $request)
    {
        $agencyId = session('agency_id');
        
        // Temporary: If no agency_id in session, use agency ID 1 for testing
        if (!$agencyId) {
            $agencyId = 1;
            session(['agency_id' => $agencyId]);
        }

        // Date range for report
        $startDate = $request->input('start_date', Carbon::now()->subMonths(3)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        // Get agency information
        $agency = Agency::find($agencyId);

        // Only include inquiries with allowed statuses within ManageInquiryFormSubmission
        $allowedStatuses = ['Under Investigation', 'Verified as True', 'Identified as Fake', 'Rejected'];

        // Get inquiry statistics (only for allowed statuses)
        $inquiriesQuery = Inquiry::where('AgencyID', $agencyId)
            ->whereIn('InquiryStatus', $allowedStatuses)
            ->whereBetween('SubmitionDate', [$startDate, $endDate]);

        $totalInquiries = $inquiriesQuery->count();
        $statusBreakdown = $inquiriesQuery->groupBy('InquiryStatus')
            ->selectRaw('InquiryStatus, COUNT(*) as count')
            ->get();

        // Monthly trend (only for allowed statuses)
        $monthlyTrend = [];
        $currentDate = Carbon::parse($startDate);
        $endDateCarbon = Carbon::parse($endDate);

        while ($currentDate->lte($endDateCarbon)) {
            $monthCount = Inquiry::where('AgencyID', $agencyId)
                ->whereIn('InquiryStatus', $allowedStatuses)
                ->whereYear('SubmitionDate', $currentDate->year)
                ->whereMonth('SubmitionDate', $currentDate->month)
                ->count();

            $monthlyTrend[] = [
                'month' => $currentDate->format('M Y'),
                'count' => $monthCount
            ];

            $currentDate->addMonth();
        }

        return view('ManageInquiryFormSubmission.Agency.inquiry-report', compact(
            'agency',
            'totalInquiries',
            'statusBreakdown',
            'monthlyTrend',
            'startDate',
            'endDate'
        ));
    }
}
