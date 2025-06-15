<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use Illuminate\Http\Request;

class InquiryController extends Controller
{    public function index()
    {
        // For demo purposes, we'll use user ID 3 which has notifications
        // In a real app, this would come from authentication
        $currentUserId = 3;
        
        $inquiries = Inquiry::with(['timeline', 'assignedAgency', 'administrator'])
            ->where('UserID', $currentUserId) // Only show inquiries for current user
            ->orderBy('SubmitionDate', 'desc')->get()            ->map(function ($inquiry) {
                return [
                    'id' => $inquiry->InquiryID,
                    'title' => $inquiry->InquiryTitle,
                    'status' => $inquiry->InquiryStatus,
                    'type' => 'Social Media Post',
                    'submittedDate' => $inquiry->SubmitionDate->format('F j, Y'),
                    'submittedDateISO' => $inquiry->SubmitionDate->format('Y-m-d'), // For JavaScript Date parsing
                    'assignedTo' => $inquiry->assignedAgency->AgencyName ?? 'Unassigned',
                    'description' => $inquiry->InquiryDescription,
                    'notes' => $inquiry->AdminComment,
                    'conclusion' => $inquiry->ResolvedExplanation,
                    'reference_number' => 'VT-' . $inquiry->SubmitionDate->format('Y') . '-' . str_pad($inquiry->InquiryID, 6, '0', STR_PAD_LEFT),
                    'timeline' => $inquiry->timeline->map(function ($log) {
                        return [
                            'date' => $log->ActionDate->format('F j, Y - H:i'),
                            'event' => $log->Action
                        ];
                    })
                ];
            });        return view('Module4.Public.inquiry_list', [
            'inquiries' => $inquiries,
            'totalInquiries' => count($inquiries),
            'currentUserId' => $currentUserId // Pass current user ID to view
        ]);
    }    public function show($id)
    {        $inquiry = Inquiry::with(['timeline', 'assignedAgency', 'administrator', 'assignedStaff', 'user'])
            ->findOrFail($id);

        $inquiryData = [
            'id' => $inquiry->InquiryID,
            'title' => $inquiry->InquiryTitle,
            'status' => $inquiry->InquiryStatus,
            'type' => 'Social Media Post',
            'submittedDate' => $inquiry->SubmitionDate->format('F j, Y'),
            'submittedDateISO' => $inquiry->SubmitionDate->format('Y-m-d'),            'assignedTo' => $inquiry->assignedAgency->AgencyName ?? 'Unassigned',
            'officerName' => $inquiry->assignedStaff->StaffName ?? null, // Staff name from agency_staff table'userDescription' => $inquiry->InquiryDescription, // User's description of the inquiry
            'agencyComment' => $inquiry->AdminComment ?? null, // Agency's comment/description
            'evidence' => $inquiry->InquiryEvidence ?? null, // Supporting documents/evidence
            'agencySupportingDocs' => $inquiry->ResolvedSupportingDocs ?? null, // Agency's supporting documents
            'notes' => $inquiry->AdminComment ?? null,
            'conclusion' => $inquiry->ResolvedExplanation ?? null,
            'reference_number' => 'VT-' . $inquiry->SubmitionDate->format('Y') . '-' . str_pad($inquiry->InquiryID, 6, '0', STR_PAD_LEFT),
            'timeline' => $inquiry->timeline->map(function ($log) {
                return [
                    'date' => $log->ActionDate->format('F j, Y - H:i'),
                    'event' => $log->Action
                ];
            })
        ];

        return view('Module4.Public.inquiry_detail', ['inquiry' => $inquiryData]);
    }
    public function allInquiries()
    {
        // Get all inquiries from database (no user filtering)
        $inquiries = Inquiry::with(['timeline', 'assignedAgency', 'administrator', 'user'])
            ->orderBy('SubmitionDate', 'desc')
            ->get()
            ->map(function ($inquiry) {
                return [
                    'id' => $inquiry->InquiryID,
                    'title' => $inquiry->InquiryTitle,
                    'status' => $inquiry->InquiryStatus,
                    'type' => 'Social Media Post',
                    'submittedDate' => $inquiry->SubmitionDate->format('F j, Y'),
                    'submittedDateISO' => $inquiry->SubmitionDate->format('Y-m-d'),
                    'assignedTo' => $inquiry->assignedAgency->AgencyName ?? 'Unassigned',
                    'submittedBy' => $inquiry->user->UserName ?? 'Unknown User',
                    'description' => $inquiry->InquiryDescription,
                    'notes' => $inquiry->AdminComment,
                    'conclusion' => $inquiry->ResolvedExplanation,
                    'reference_number' => 'VT-' . $inquiry->SubmitionDate->format('Y') . '-' . str_pad($inquiry->InquiryID, 6, '0', STR_PAD_LEFT),
                    'timeline' => $inquiry->timeline->map(function ($log) {
                        return [
                            'date' => $log->ActionDate->format('F j, Y - H:i'),
                            'event' => $log->Action
                        ];
                    })
                ];
            });

        // Calculate status counts
        $statusCounts = [
            'Under Investigation' => 0,
            'Verified as True' => 0,
            'Identified as Fake' => 0,
            'Rejected' => 0
        ];

        foreach ($inquiries as $inquiry) {
            if (isset($statusCounts[$inquiry['status']])) {
                $statusCounts[$inquiry['status']]++;
            }
        }

        return view('Module4-MCMC.inquiryList', [
            'inquiries' => $inquiries,
            'totalInquiries' => count($inquiries),
            'statusCounts' => $statusCounts,
            'currentUserId' => 1 // For notification system
        ]);
    }
}
