<?php

namespace App\Http\Controllers\InquiryAssignment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inquiry;
use App\Models\Agency;
use App\Models\AssignedInquiry;
use App\Models\InquiryAuditLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * MCMCController - Module 3: Inquiry Assignment
 * 
 * This controller handles all functionality for the MCMC (Malaysian Communications and Multimedia Commission) interface.
 * It is responsible for:
 * - Displaying unassigned inquiries to MCMC staff
 * - Assigning inquiries to appropriate agencies
 * - Retrieving inquiry details for MCMC operations
 * 
 * This controller serves ONLY the MCMC interface and should not contain Agency-specific functionality.
 */
class MCMCController extends Controller
{
    /**
     * Display unassigned inquiries for MCMC staff
     */
    public function unassignedInquiries()
    {        // Get inquiries that are not assigned to any agency or rejected by agency
        $unassignedInquiries = Inquiry::with(['user'])
            ->where(function ($query) {
                // Not assigned to any agency
                $query->where(function ($subQuery) {
                    $subQuery->whereNull('AgencyID')
                             ->orWhere('AgencyID', 0);
                })
                // OR rejected by agency (regardless of AgencyID state)
                ->orWhere('InquiryStatus', 'Rejected by Agency');            })
            // Exclude inquiries with "Submitted" and "Discarded" statuses
            ->whereNotIn('InquiryStatus', ['Submitted', 'Discarded'])
            ->orderBy('SubmitionDate', 'desc')
            ->get()->map(function ($inquiry) {
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
                    'title' => $inquiry->InquiryTitle,
                    'description' => $inquiry->InquiryDescription,
                    'status' => $inquiry->InquiryStatus,
                    'submittedDate' => $inquiry->SubmitionDate->format('F j, Y'),
                    'submittedDateTime' => $inquiry->SubmitionDate->format('F j, Y g:i A'),
                    'submittedDateISO' => $inquiry->SubmitionDate->format('Y-m-d'),
                    'submittedBy' => $inquiry->user->UserName ?? 'Unknown User',
                    'submitterEmail' => $inquiry->user->UserEmail ?? 'N/A',
                    'submitterPhone' => $inquiry->user->UserPhoneNum ?? 'N/A',
                    'submitterAddress' => $inquiry->user->Useraddress ?? 'N/A',
                    'evidence' => $inquiry->InquiryEvidence,
                    'evidenceData' => $inquiry->InquiryEvidence ? json_decode($inquiry->InquiryEvidence, true) : null,
                    'adminComment' => $inquiry->AdminComment,
                    'statusHistory' => $inquiry->StatusHistory,
                    'createdAt' => $inquiry->created_at->format('F j, Y g:i A'),
                    'lastUpdated' => $inquiry->updated_at->format('F j, Y g:i A'),                    'daysSinceSubmission' => $diffInDays, // Keep for priority calculation
                    'timePending' => $timePending,
                    'pendingDays' => $days,
                    'pendingHours' => $hours,
                    'pendingMinutes' => $minutes,
                    'reference_number' => 'VT-' . $inquiry->SubmitionDate->format('Y') . '-' . str_pad($inquiry->InquiryID, 6, '0', STR_PAD_LEFT),
                    'rejectionComment' => $inquiry->AgencyRejectionComment,
                    'rejectionData' => $inquiry->AgencyRejectionComment ? json_decode($inquiry->AgencyRejectionComment, true) : null,
                    'rejectedByAgency' => $inquiry->AgencyRejectionComment ? 
                        (json_decode($inquiry->AgencyRejectionComment, true)['rejected_by_agency'] ?? 'Unknown Agency') : null,
                    'rejectedByAgencyId' => $inquiry->AgencyRejectionComment ? 
                        (json_decode($inquiry->AgencyRejectionComment, true)['rejected_by_agency_id'] ?? null) : null,
                    'isRejected' => $inquiry->InquiryStatus === 'Rejected by Agency',
                    'priority' => $diffInDays > 7 ? 'High' : ($diffInDays > 3 ? 'Medium' : 'Normal')
                ];
            });

        // Get all available agencies for assignment dropdown
        $agencies = Agency::orderBy('AgencyName')->get();        // Calculate priority-based stats
        $totalUnassigned = $unassignedInquiries->count();
        $highPriorityCount = $unassignedInquiries->where('priority', 'High')->count();
        $mediumPriorityCount = $unassignedInquiries->where('priority', 'Medium')->count();
        $normalPriorityCount = $unassignedInquiries->where('priority', 'Normal')->count();

        return view('Module3.MCMC.unassigned_inquiries', [
            'unassignedInquiries' => $unassignedInquiries,
            'agencies' => $agencies,
            'totalUnassigned' => $totalUnassigned,
            'highPriorityCount' => $highPriorityCount,
            'mediumPriorityCount' => $mediumPriorityCount,
            'normalPriorityCount' => $normalPriorityCount
        ]);
    }

    /**
     * Assign an inquiry to an agency
     */    public function assignInquiry(Request $request, $inquiryId)
    {
        $request->validate([
            'agency_id' => 'required|exists:agencies,AgencyID',
            'comments' => 'nullable|string|max:1000'
        ]);

        try {
            DB::beginTransaction();

            // Get current admin ID from session
            $adminId = session('user_id');
            
            if (!$adminId || session('user_type') !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Admin access required.'
                ], 403);
            }

            // Get the inquiry
            $inquiry = Inquiry::findOrFail($inquiryId);

            // Check if inquiry is already assigned
            if ($inquiry->AgencyID) {
                return response()->json([
                    'success' => false,
                    'message' => 'Inquiry is already assigned to an agency.'
                ], 400);
            }            // Update the inquiry with agency assignment
            $inquiry->AgencyID = $request->agency_id;
            $inquiry->AdminID = $adminId;
            $inquiry->InquiryStatus = 'Assigned';
            
            // Store assignment comments if provided
            if ($request->filled('comments')) {
                $inquiry->AdminComment = $request->comments;
            }
            
            $inquiry->save();

            // Create assignment record
            AssignedInquiry::create([
                'AdminID' => $adminId,
                'AgencyID' => $request->agency_id,
                'InquiryID' => $inquiryId,
                'AssignedDate' => Carbon::now()->toDateString()
            ]);            // Create audit log
            InquiryAuditLog::create([
                'InquiryID' => $inquiryId,
                'Action' => 'Assigned to Agency',
                'ActionDate' => Carbon::now(),
                'PerformedBy' => $adminId
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Inquiry successfully assigned to agency.'
            ]);        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Assignment error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Error assigning inquiry: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get inquiry details for modal view
     */
    public function getInquiryDetails($inquiryId)
    {
        try {
            $inquiry = Inquiry::with(['user'])->findOrFail($inquiryId);

            // Parse evidence if it exists
            $evidence = [];
            if ($inquiry->InquiryEvidence) {
                $evidence = json_decode($inquiry->InquiryEvidence, true) ?? [];
            }

            return response()->json([
                'success' => true,
                'inquiry' => [
                    'InquiryID' => $inquiry->InquiryID,
                    'title' => $inquiry->InquiryTitle,
                    'description' => $inquiry->InquiryDescription,
                    'status' => $inquiry->InquiryStatus,
                    'submittedDate' => $inquiry->SubmitionDate->format('F j, Y'),
                    'submittedBy' => $inquiry->user->UserName ?? 'Unknown User',
                    'evidence' => $evidence,
                    'reference_number' => 'VT-' . $inquiry->SubmitionDate->format('Y') . '-' . str_pad($inquiry->InquiryID, 6, '0', STR_PAD_LEFT),
                ]            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving inquiry details: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download evidence file for MCMC staff
     */
    public function downloadEvidence($id, $fileIndex)
    {
        try {
            // Get current admin ID from session
            $adminId = session('user_id');
            
            if (!$adminId || session('user_type') !== 'admin') {
                abort(403, 'Unauthorized. Admin access required.');
            }

            $inquiry = Inquiry::findOrFail($id);
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
