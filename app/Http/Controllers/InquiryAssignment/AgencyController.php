<?php

namespace App\Http\Controllers\InquiryAssignment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inquiry;
use App\Models\Agency;
use App\Services\NotificationService;
use Carbon\Carbon;

/**
 * AgencyController - Module 3: Inquiry Assignment
 * 
 * This controller handles all functionality for the Agency interface.
 * It is responsible for:
 * - Displaying assigned inquiries to agency staff
 * - Updating inquiry status by agency staff
 * - Rejecting inquiry assignments (returning them to MCMC)
 * 
 * This controller serves ONLY the Agency interface and should not contain MCMC-specific functionality.
 */
class AgencyController extends Controller
{    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    public function assignedInquiries(Request $request)
    {
        // Get current agency staff ID from session
        $staffId = session('user_id');
        
        if (!$staffId || session('user_type') !== 'agency') {
            return redirect()->route('login')->with('error', 'Please log in as agency staff.');
        }
        
        // Get the agency ID for the current staff member
        $agencyStaff = \App\Models\AgencyStaff::find($staffId);
        
        if (!$agencyStaff) {
            return redirect()->route('login')->with('error', 'Agency staff not found.');
        }
        
        $agencyId = $agencyStaff->AgencyID;
        
        // Start with base query - only inquiries assigned to this agency
        $query = Inquiry::with(['assignedAgency', 'user'])
            ->where('AgencyID', $agencyId)  // Filter by agency
            ->whereNotNull('AgencyID')      // Only assigned inquiries
            ->orderBy('created_at', 'desc');

        // Apply status filter if provided
        if ($request->filled('status') && $request->status !== '') {
            $query->where('InquiryStatus', $request->status);
        }

        // Apply search filter if provided
        if ($request->filled('search') && $request->search !== '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('InquiryTitle', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('InquiryDescription', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('InquiryID', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        // Get filtered inquiries
        $inquiries = $query->get()
            ->map(function ($inquiry) {
                // Calculate more user-friendly time pending
                $submittedAt = Carbon::parse($inquiry->SubmitionDate);
                $now = Carbon::now();
                
                // Calculate the full breakdown of days, hours, and minutes
                $totalMinutes = $submittedAt->diffInMinutes($now);
                $days = intval($totalMinutes / (24 * 60));
                $hours = intval(($totalMinutes % (24 * 60)) / 60);
                $minutes = $totalMinutes % 60;
                
                // Build the time pending string with days, hours, and minutes
                $timeParts = [];
                if ($days > 0) {
                    $timeParts[] = $days . ' day' . ($days > 1 ? 's' : '');
                }
                if ($hours > 0) {
                    $timeParts[] = $hours . ' hour' . ($hours > 1 ? 's' : '');
                }
                if ($minutes > 0 || count($timeParts) == 0) { // Always show minutes if nothing else or if there are minutes
                    $timeParts[] = $minutes . ' minute' . ($minutes > 1 ? 's' : '');
                }
                
                $timePending = implode(', ', $timeParts);
                
                // For sorting and priority purposes, use total days
                $diffInDays = $days;
                
                return [
                    'InquiryID' => $inquiry->InquiryID,
                    'reference_number' => $inquiry->reference_number ?? 'VT-' . $inquiry->SubmitionDate->format('Y') . '-' . str_pad($inquiry->InquiryID, 6, '0', STR_PAD_LEFT),
                    'title' => $inquiry->InquiryTitle ?? 'No Title',
                    'description' => $inquiry->InquiryDescription ?? 'No description available',
                    'status' => $inquiry->InquiryStatus ?? 'Pending',
                    'submittedDate' => $inquiry->SubmitionDate ? $inquiry->SubmitionDate->format('F j, Y') : 'N/A',
                    'submittedDateTime' => $inquiry->SubmitionDate ? $inquiry->SubmitionDate->format('F j, Y g:i A') : 'N/A',
                    'submittedDateISO' => $inquiry->SubmitionDate ? $inquiry->SubmitionDate->format('Y-m-d') : 'N/A',
                    'submittedBy' => $inquiry->user->UserName ?? 'Unknown User',
                    'submitterEmail' => $inquiry->user->UserEmail ?? 'N/A',
                    'submitterPhone' => $inquiry->user->UserPhoneNum ?? 'N/A',
                    'submitterAddress' => $inquiry->user->Useraddress ?? 'N/A',
                    'evidence' => $inquiry->InquiryEvidence,
                    'evidenceData' => $inquiry->InquiryEvidence ? json_decode($inquiry->InquiryEvidence, true) : null,
                    'statusHistory' => $inquiry->StatusHistory,
                    'createdAt' => $inquiry->created_at->format('F j, Y g:i A'),
                    'daysSinceSubmission' => $diffInDays, // Keep for priority calculation
                    'timePending' => $timePending,
                    'pendingDays' => $days,
                    'pendingHours' => $hours,
                    'pendingMinutes' => $minutes,
                    'priority' => $diffInDays > 7 ? 'High' : ($diffInDays > 3 ? 'Medium' : 'Normal'),
                    'assignedDate' => $inquiry->updated_at ? $inquiry->updated_at->format('F j, Y') : 'N/A',
                    'mcmcComment' => $inquiry->AdminComment ? $inquiry->AdminComment : 'No MCMC comment provided',
                ];
            });

        // Calculate priority-based stats
        $totalInquiries = $inquiries->count();
        $highPriorityCount = $inquiries->where('priority', 'High')->count();
        $mediumPriorityCount = $inquiries->where('priority', 'Medium')->count();
        $normalPriorityCount = $inquiries->where('priority', 'Normal')->count();

        // Get count of filtered inquiries
        $filteredCount = $inquiries->count();        // Pass current filter values to maintain state
        $currentFilters = [
            'status' => $request->status ?? '',
            'search' => $request->search ?? ''
        ];

        return view('InquiryAssignment.Agency.assigned_inquiries', [
            'assignedInquiries' => $inquiries,
            'totalInquiries' => $totalInquiries,
            'highPriorityCount' => $highPriorityCount,
            'mediumPriorityCount' => $mediumPriorityCount,
            'normalPriorityCount' => $normalPriorityCount,
            'filteredCount' => $filteredCount,
            'currentFilters' => $currentFilters,
            'agencyName' => $agencyStaff->agency->AgencyName ?? 'Your Agency'
        ]);
    }

    /**
     * Update inquiry status (used by Agency UI for status changes)
     */
    public function updateInquiryStatus(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'InquiryStatus' => 'required|in:Under Investigation,Verified as True,Identified as Fake,Rejected',
        ]);

        try {
            // Find the inquiry
            $inquiry = Inquiry::findOrFail($id);
            
            // Store the old status for notification
            $oldStatus = $inquiry->InquiryStatus;
            $newStatus = $request->input('InquiryStatus');

            // Update only the inquiry status
            $inquiry->InquiryStatus = $newStatus;
            $inquiry->save();

            // Create notification for status update if status actually changed
            if ($oldStatus !== $newStatus) {
                $this->notificationService->createStatusUpdateNotification(
                    $inquiry->InquiryID,
                    $newStatus,
                    $oldStatus
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully',
                'new_status' => $inquiry->InquiryStatus
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject an inquiry assignment (agency staff rejects inquiry)
     */
    public function rejectInquiry(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string',
            'comments' => 'required|string|max:1000'
        ]);

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            // Get current agency staff ID from session
            $staffId = session('user_id');
            
            if (!$staffId || session('user_type') !== 'agency') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Agency access required.'
                ], 403);
            }

            // Get the agency ID for the current staff member
            $agencyStaff = \App\Models\AgencyStaff::find($staffId);
            
            if (!$agencyStaff) {
                return response()->json([
                    'success' => false,
                    'message' => 'Agency staff not found.'
                ], 404);
            }

            // Get the inquiry
            $inquiry = Inquiry::findOrFail($id);

            // Verify this inquiry is assigned to the current agency
            if ($inquiry->AgencyID !== $agencyStaff->AgencyID) {
                return response()->json([
                    'success' => false,
                    'message' => 'This inquiry is not assigned to your agency.'
                ], 403);
            }

            // Get agency name for rejection tracking
            $agencyName = $agencyStaff->agency->AgencyName ?? 'Unknown Agency';

            // Create structured rejection comment that includes agency info
            $rejectionData = [
                'rejected_by_agency' => $agencyName,
                'rejected_by_agency_id' => $agencyStaff->AgencyID,
                'rejection_reason' => $request->reason,
                'rejection_comments' => $request->comments,
                'rejection_date' => \Carbon\Carbon::now()->toDateTimeString()
            ];            // Update the inquiry - remove agency assignment and set status
            $inquiry->AgencyID = null;  // Remove agency assignment
            // Keep AdminID as is - don't set to null since it has NOT NULL constraint
            $inquiry->InquiryStatus = 'Rejected by Agency';
            $inquiry->AgencyRejectionComment = json_encode($rejectionData);
            $inquiry->save();

            // Remove from assigned_inquiries table
            \App\Models\AssignedInquiry::where('InquiryID', $id)
                ->where('AgencyID', $agencyStaff->AgencyID)
                ->delete();

            // Create audit log
            \App\Models\InquiryAuditLog::create([
                'InquiryID' => $id,
                'Action' => 'Rejected by Agency - ' . $request->reason,
                'ActionDate' => \Carbon\Carbon::now(),
                'PerformedBy' => $staffId
            ]);

            \Illuminate\Support\Facades\DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Inquiry rejected successfully. It has been returned to MCMC for reassignment.'
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollback();
            \Illuminate\Support\Facades\Log::error('Rejection error: ' . $e->getMessage());
            \Illuminate\Support\Facades\Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Error rejecting inquiry: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download evidence file for Agency staff
     */
    public function downloadEvidence($id, $fileIndex)
    {
        try {
            // Get current agency staff ID from session
            $staffId = session('user_id');
            
            if (!$staffId || session('user_type') !== 'agency') {
                abort(403, 'Unauthorized. Agency access required.');
            }

            // Get the agency ID for the current staff member
            $agencyStaff = \App\Models\AgencyStaff::find($staffId);
            
            if (!$agencyStaff) {
                abort(404, 'Agency staff not found.');
            }

            $inquiry = Inquiry::findOrFail($id);

            // Verify this inquiry is assigned to the current agency
            if ($inquiry->AgencyID !== $agencyStaff->AgencyID) {
                abort(403, 'This inquiry is not assigned to your agency.');
            }

            $evidence = $inquiry->InquiryEvidence ? json_decode($inquiry->InquiryEvidence, true) : [];
            
            if (!isset($evidence['files'][$fileIndex])) {
                abort(404, 'File not found');
            }
            
            $file = $evidence['files'][$fileIndex];
            $filePath = storage_path('app/public/' . $file['path']);
            
            if (!file_exists($filePath)) {
                abort(404, 'File not found on disk');
            }
            
            return response()->download($filePath, $file['original_name']);
            
        } catch (\Exception $e) {
            abort(500, 'Error downloading file: ' . $e->getMessage());
        }
    }
}
