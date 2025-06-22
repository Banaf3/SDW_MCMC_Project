<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inquiry;
use App\Models\Agency;
use App\Models\AssignedInquiry;
use App\Models\InquiryAuditLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MCMCController extends Controller
{
    /**
     * Display unassigned inquiries for MCMC staff
     */
    public function unassignedInquiries()
    {
        // Get inquiries that are not assigned to any agency
        $unassignedInquiries = Inquiry::with(['user'])
            ->whereNull('AgencyID')
            ->orWhere('AgencyID', 0)
            ->orderBy('SubmitionDate', 'desc')
            ->get()
            ->map(function ($inquiry) {
                return [
                    'InquiryID' => $inquiry->InquiryID,
                    'title' => $inquiry->InquiryTitle,
                    'description' => $inquiry->InquiryDescription,
                    'status' => $inquiry->InquiryStatus,
                    'submittedDate' => $inquiry->SubmitionDate->format('F j, Y'),
                    'submittedDateISO' => $inquiry->SubmitionDate->format('Y-m-d'),
                    'submittedBy' => $inquiry->user->UserName ?? 'Unknown User',
                    'evidence' => $inquiry->InquiryEvidence,
                    'reference_number' => 'VT-' . $inquiry->SubmitionDate->format('Y') . '-' . str_pad($inquiry->InquiryID, 6, '0', STR_PAD_LEFT),
                ];
            });

        // Get all available agencies for assignment dropdown
        $agencies = Agency::orderBy('AgencyName')->get();

        // Calculate stats
        $totalUnassigned = $unassignedInquiries->count();
        $totalAgencies = $agencies->count();
        $todaySubmissions = Inquiry::whereDate('SubmitionDate', Carbon::today())
            ->whereNull('AgencyID')
            ->count();

        return view('Module3.MCMC.unassigned_inquiries', [
            'unassignedInquiries' => $unassignedInquiries,
            'agencies' => $agencies,
            'totalUnassigned' => $totalUnassigned,
            'totalAgencies' => $totalAgencies,
            'todaySubmissions' => $todaySubmissions
        ]);
    }

    /**
     * Assign an inquiry to an agency
     */
    public function assignInquiry(Request $request, $inquiryId)
    {
        $request->validate([
            'agency_id' => 'required|exists:agencies,AgencyID',
            'comments' => 'nullable|string|max:1000'
        ]);

        try {
            DB::beginTransaction();

            // Get the inquiry
            $inquiry = Inquiry::findOrFail($inquiryId);

            // Update the inquiry with agency assignment
            $inquiry->AgencyID = $request->agency_id;
            $inquiry->AdminID = 1; // Would be the current admin's ID in a real system
            $inquiry->save();

            // Create assignment record
            AssignedInquiry::create([
                'AdminID' => 1, // Would be the current admin's ID
                'AgencyID' => $request->agency_id,
                'InquiryID' => $inquiryId,
                'AssignedDate' => Carbon::now()->toDateString()
            ]);

            // Create audit log
            InquiryAuditLog::create([
                'InquiryID' => $inquiryId,
                'Action' => 'Assigned to Agency',
                'ActionDate' => Carbon::now(),
                'AdminID' => 1, // Would be the current admin's ID
                'Details' => 'Inquiry assigned to agency. Comments: ' . ($request->comments ?? 'No comments')
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Inquiry successfully assigned to agency.'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
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
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving inquiry details: ' . $e->getMessage()
            ], 500);
        }
    }
}