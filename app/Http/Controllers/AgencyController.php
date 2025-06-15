<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inquiry;
use App\Models\Agency;
use App\Services\NotificationService;

class AgencyController extends Controller
{    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    public function assignedInquiries(Request $request)
    {
        // Start with base query
        $query = Inquiry::with(['assignedAgency', 'user'])
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
                return [
                    'InquiryID' => $inquiry->InquiryID, // Add the actual database ID
                    'id' => $inquiry->reference_number ?? 'VT-' . str_pad($inquiry->InquiryID, 6, '0', STR_PAD_LEFT),
                    'title' => $inquiry->InquiryTitle ?? 'No Title',
                    'description' => $inquiry->InquiryDescription ?? 'No description available',
                    'type' => $inquiry->type ?? 'General',
                    'status' => $inquiry->InquiryStatus ?? 'Pending',
                    'submittedBy' => $inquiry->user->UserName ?? 'Unknown User',
                    'submittedDate' => $inquiry->SubmitionDate ? $inquiry->SubmitionDate->format('F j, Y') : 'N/A',
                    'assignedDate' => $inquiry->updated_at ? $inquiry->updated_at->format('F j, Y') : 'N/A'
                ];
            });

        // Get total count of all inquiries (unfiltered)
        $totalInquiries = Inquiry::count();

        // Get count of filtered inquiries
        $filteredCount = $inquiries->count();

        // Pass current filter values to maintain state
        $currentFilters = [
            'status' => $request->status ?? '',
            'search' => $request->search ?? ''
        ];

        return view('Module4-Agency.InquiryAssigned', compact(
            'inquiries',
            'totalInquiries',
            'filteredCount',
            'currentFilters'
        ));
    }public function editInquiry($id)
    {
        // Get the inquiry from database
        $inquiry = Inquiry::with(['assignedAgency', 'user'])->findOrFail($id);
          // Format the inquiry data for the view
        $inquiryData = [
            'InquiryID' => $inquiry->InquiryID,
            'id' => 'VT-' . str_pad($inquiry->InquiryID, 6, '0', STR_PAD_LEFT),
            'InquiryTitle' => $inquiry->InquiryTitle,
            'InquiryDescription' => $inquiry->InquiryDescription,
            'InquiryStatus' => $inquiry->InquiryStatus,
            'InquiryEvidence' => $inquiry->InquiryEvidence,
            'ResolvedExplanation' => $inquiry->ResolvedExplanation,
            'ResolvedSupportingDocs' => $inquiry->ResolvedSupportingDocs,
            'SubmitionDate' => $inquiry->SubmitionDate ? $inquiry->SubmitionDate->format('F j, Y') : 'N/A',
        ];

        return view('Module4-Agency.InquiryEdit', ['inquiry' => $inquiryData]);
    }    public function updateInquiryStatus(Request $request, $id)
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

    public function updateInquiry(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'InquiryStatus' => 'required|in:Under Investigation,Verified as True,Identified as Fake,Rejected',
            'ResolvedExplanation' => 'nullable|string|max:5000',
            'ResolvedSupportingDocs.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', // 10MB max
        ]);        try {
            // Find the inquiry
            $inquiry = Inquiry::findOrFail($id);
            
            // Store the old status for notification
            $oldStatus = $inquiry->InquiryStatus;
            $newStatus = $request->input('InquiryStatus');

            // Update the inquiry status
            $inquiry->InquiryStatus = $newStatus;
            
            // Update resolved explanation if provided
            if ($request->filled('ResolvedExplanation')) {
                $inquiry->ResolvedExplanation = $request->input('ResolvedExplanation');
            }

            // Handle file uploads
            if ($request->hasFile('ResolvedSupportingDocs')) {
                $uploadedFiles = [];
                foreach ($request->file('ResolvedSupportingDocs') as $file) {
                    // Create storage directory if it doesn't exist
                    $storagePath = storage_path('app/public/inquiry_documents');
                    if (!file_exists($storagePath)) {
                        mkdir($storagePath, 0755, true);
                    }

                    // Generate unique filename
                    $filename = time() . '_' . $file->getClientOriginalName();
                    
                    // Store the file
                    $file->move($storagePath, $filename);
                    
                    $uploadedFiles[] = $filename;
                }

                // Update the database with file names (you might want to store this differently)
                $inquiry->ResolvedSupportingDocs = implode(', ', $uploadedFiles);
            }

            // Save the changes
            $inquiry->save();

            // Create notification for status update if status actually changed
            if ($oldStatus !== $newStatus) {
                $this->notificationService->createStatusUpdateNotification(
                    $inquiry->InquiryID,
                    $newStatus,
                    $oldStatus
                );
            }

            return redirect()->route('agency.inquiries.assigned')
                           ->with('success', 'Inquiry updated successfully!');

        } catch (\Exception $e) {
            return back()->with('error', 'Error updating inquiry: ' . $e->getMessage());
        }
    }
}
