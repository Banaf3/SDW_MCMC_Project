<?php

namespace App\Http\Controllers\InquiryProgressTracking;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inquiry;
use App\Models\Agency;

class AgencyController extends Controller
{
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
    }    public function editInquiry($id)
    {
        // Debug: Log the ID being accessed
        \Log::info('Edit Inquiry accessed with ID: ' . $id);
        
        // Get the inquiry from database
        $inquiry = Inquiry::with(['assignedAgency', 'user'])->findOrFail($id);
        
        // Debug: Log the inquiry found
        \Log::info('Inquiry found: ' . json_encode($inquiry->toArray()));
          
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
        try {
            // Validate the request
            $request->validate([
                'InquiryStatus' => 'required|in:Under Investigation,Verified as True,Identified as Fake,Rejected',
            ]);

            // Find the inquiry
            $inquiry = Inquiry::findOrFail($id);
            
            // Store the old status for notification
            $oldStatus = $inquiry->InquiryStatus;
            $newStatus = $request->input('InquiryStatus');            // Update only the inquiry status
            $inquiry->InquiryStatus = $newStatus;
            $inquiry->save();
            
            // Create a simple notification for the user
            $this->createSimpleNotification($inquiry, $oldStatus, $newStatus);
            
            // Log the successful update
            \Log::info('Inquiry status updated successfully', [
                'inquiry_id' => $id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully',
                'new_status' => $inquiry->InquiryStatus,
                'notification_sent' => true
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error: ' . implode(', ', $e->validator->errors()->all())
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Inquiry not found'
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Error updating inquiry status', [
                'inquiry_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error updating status: ' . $e->getMessage()
            ], 500);
        }
    }    public function updateInquiry(Request $request, $id)
    {
        try {
            // Log the incoming request
            \Log::info('Update inquiry request', [
                'inquiry_id' => $id,
                'request_data' => $request->all()
            ]);

            // Validate the request
            $request->validate([
                'InquiryStatus' => 'required|in:Under Investigation,Verified as True,Identified as Fake,Rejected',
                'ResolvedExplanation' => 'nullable|string|max:5000',
                'ResolvedSupportingDocs.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', // 10MB max
            ]);

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

                // Update the database with file names
                $inquiry->ResolvedSupportingDocs = implode(', ', $uploadedFiles);
            }            // Save the changes
            $inquiry->save();
            
            // Create notification if status changed
            if ($oldStatus !== $newStatus) {
                $this->createSimpleNotification($inquiry, $oldStatus, $newStatus);
            }
            
            \Log::info('Inquiry updated successfully', [
                'inquiry_id' => $id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus
            ]);

            return redirect()->route('agency.inquiries.assigned')
                           ->with('success', 'Inquiry updated successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error updating inquiry', [
                'inquiry_id' => $id,
                'errors' => $e->validator->errors()->toArray()
            ]);
            
            return back()->withErrors($e->validator)->withInput();
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error('Inquiry not found', ['inquiry_id' => $id]);
            
            return back()->with('error', 'Inquiry not found.');
            
        } catch (\Exception $e) {
            \Log::error('Error updating inquiry', [
                'inquiry_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
              return back()->with('error', 'Error updating inquiry: ' . $e->getMessage());
        }
    }
    
    /**
     * Create a simple notification without using a notification model
     */
    private function createSimpleNotification($inquiry, $oldStatus, $newStatus)
    {
        try {
            // Create notification data
            $notificationData = [
                'inquiry_id' => $inquiry->InquiryID,
                'inquiry_title' => $inquiry->InquiryTitle,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'message' => "Your inquiry '{$inquiry->InquiryTitle}' status has been changed from '{$oldStatus}' to '{$newStatus}'",
                'timestamp' => now()->format('Y-m-d H:i:s'),
                'read' => false
            ];
            
            // Store in session (in production, you might want to store in cache or database)
            $userNotifications = session()->get("user_notifications_{$inquiry->UserID}", []);
            $userNotifications[] = $notificationData;
            
            // Keep only the last 10 notifications
            if (count($userNotifications) > 10) {
                $userNotifications = array_slice($userNotifications, -10);
            }
            
            session()->put("user_notifications_{$inquiry->UserID}", $userNotifications);
            
            \Log::info('Simple notification created', [
                'user_id' => $inquiry->UserID,
                'inquiry_id' => $inquiry->InquiryID,
                'status_change' => "$oldStatus → $newStatus"
            ]);
            
            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to create simple notification', [
                'exception' => $e->getMessage(),
                'user_id' => $inquiry->UserID ?? 'unknown',
                'inquiry_id' => $inquiry->InquiryID ?? 'unknown'
            ]);
            return false;
        }
    }
}
