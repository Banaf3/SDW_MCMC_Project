<?php

namespace App\Http\Controllers\ManageInquiryFormSubmission\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use App\Models\InquiryAuditLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class InquiryManagementController extends Controller
{
    /**
     * Display inquiries with filtering and search capabilities
     */
    public function viewNewInquiries(Request $request)
    {
        $query = Inquiry::with(['user', 'agency']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('InquiryStatus', $request->status);
        } else {
            // Default to showing new inquiries first, but allow viewing all
            $query->where('InquiryStatus', 'Pending Review');
        }

        if ($request->filled('date_from')) {
            $query->whereDate('SubmitionDate', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('SubmitionDate', '<=', $request->date_to);
        }

        if ($request->filled('agency')) {
            $query->where('AgencyID', $request->agency);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('InquiryTitle', 'like', '%' . $request->search . '%')
                  ->orWhere('InquiryDescription', 'like', '%' . $request->search . '%');
            });
        }

        $inquiries = $query->orderByDesc('SubmitionDate')->paginate(20);

        // Get statistics for all inquiries
        $totalInquiries = Inquiry::count();
        $newInquiries = Inquiry::where('InquiryStatus', 'Pending Review')->count();
        $todayInquiries = Inquiry::whereDate('SubmitionDate', today())->count();
        $weekInquiries = Inquiry::whereDate('SubmitionDate', '>=', now()->subWeek())->count();

        // Get available statuses for filter
        $statuses = Inquiry::distinct('InquiryStatus')->pluck('InquiryStatus');

        return view('ManageInquiryFormSubmission.MCMC.new-inquiries', compact('inquiries', 'totalInquiries', 'newInquiries', 'todayInquiries', 'weekInquiries', 'statuses'));
    }

    /**
     * Display previously processed inquiries
     */
    public function viewPreviousInquiries(Request $request)
    {
        $query = Inquiry::with(['user', 'agency'])
            ->whereIn('InquiryStatus', [
                'Under Investigation', 
                'Completed', 
                'Closed', 
                'Assigned to Agency',
                'Flagged as Non-serious',
                'Discarded'
            ]);

        // Apply filters similar to viewNewInquiries
        if ($request->filled('status')) {
            $query->where('InquiryStatus', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('SubmitionDate', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('SubmitionDate', '<=', $request->date_to);
        }

        if ($request->filled('agency')) {
            $query->where('AgencyID', $request->agency);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('InquiryTitle', 'like', '%' . $request->search . '%')
                  ->orWhere('InquiryDescription', 'like', '%' . $request->search . '%');
            });
        }

        $previousInquiries = $query->orderByDesc('SubmitionDate')->paginate(20);

        return view('ManageInquiryFormSubmission.MCMC.previous-inquiries', compact('previousInquiries'));
    }

    /**
     * Show detailed view of an inquiry for admin review
     */
    public function showInquiry($id)
    {
        $inquiry = Inquiry::with(['user', 'agency', 'auditLogs'])->findOrFail($id);
        
        return view('ManageInquiryFormSubmission.MCMC.inquiry-detail', compact('inquiry'));
    }

    /**
     * Update inquiry status (filter/validate)
     */
    public function updateInquiryStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
            'admin_notes' => 'nullable|string',
            'reason' => 'nullable|string'
        ]);

        $inquiry = Inquiry::findOrFail($id);
        $oldStatus = $inquiry->InquiryStatus;
        
        $inquiry->update([
            'InquiryStatus' => $request->status,
            'AdminNotes' => $request->admin_notes
        ]);

        // Create audit log entry
        InquiryAuditLog::create([
            'InquiryID' => $id,
            'AdminID' => session('user_id'),
            'Action' => 'Status Updated',
            'OldStatus' => $oldStatus,
            'NewStatus' => $request->status,
            'ActionDate' => now(),
            'Reason' => $request->reason,
            'Notes' => $request->admin_notes
        ]);

        return redirect()->back()->with('success', 'Inquiry status updated successfully.');
    }

    /**
     * Flag inquiry as non-serious
     */
    public function flagAsNonSerious(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string'
        ]);

        $inquiry = Inquiry::findOrFail($id);
        $oldStatus = $inquiry->InquiryStatus;
        
        $inquiry->update([
            'InquiryStatus' => 'Flagged as Non-serious',
            'AdminNotes' => $request->reason
        ]);

        // Create audit log entry
        InquiryAuditLog::create([
            'InquiryID' => $id,
            'AdminID' => session('user_id'),
            'Action' => 'Flagged as Non-serious',
            'OldStatus' => $oldStatus,
            'NewStatus' => 'Flagged as Non-serious',
            'ActionDate' => now(),
            'Reason' => $request->reason
        ]);

        return redirect()->back()->with('success', 'Inquiry flagged as non-serious.');
    }

    /**
     * Discard inquiry
     */
    public function discardInquiry(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string'
        ]);

        $inquiry = Inquiry::findOrFail($id);
        $oldStatus = $inquiry->InquiryStatus;
        
        $inquiry->update([
            'InquiryStatus' => 'Discarded',
            'AdminNotes' => $request->reason
        ]);

        // Create audit log entry
        InquiryAuditLog::create([
            'InquiryID' => $id,
            'AdminID' => session('user_id'),
            'Action' => 'Discarded',
            'OldStatus' => $oldStatus,
            'NewStatus' => 'Discarded',
            'ActionDate' => now(),
            'Reason' => $request->reason
        ]);

        return redirect()->back()->with('success', 'Inquiry discarded.');
    }

    /**
     * Display audit logs for all inquiries
     */
    public function viewAuditLogs(Request $request)
    {
        $query = InquiryAuditLog::with(['inquiry', 'administrator']);

        // Apply filters
        if ($request->filled('inquiry_id')) {
            $query->where('InquiryID', $request->inquiry_id);
        }

        if ($request->filled('action')) {
            $query->where('Action', $request->action);
        }

        if ($request->filled('admin_id')) {
            $query->where('AdminID', $request->admin_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('ActionDate', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('ActionDate', '<=', $request->date_to);
        }

        $auditLogs = $query->orderByDesc('ActionDate')->paginate(50);

        // Get available actions for filter
        $actions = InquiryAuditLog::distinct('Action')->pluck('Action');

        // Get administrators for filter
        $administrators = \App\Models\Administrator::select('AdminID', 'AdminName')->get();

        return view('ManageInquiryFormSubmission.MCMC.audit-logs', compact('auditLogs', 'actions', 'administrators'));
    }
}
