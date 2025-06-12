<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    public function index()
    {
        $inquiries = Inquiry::with(['timeline', 'assignedAgency', 'administrator'])
            ->orderBy('SubmitionDate', 'desc')
            ->get()
            ->map(function ($inquiry) {
                return [
                    'id' => $inquiry->InquiryID,
                    'title' => $inquiry->InquiryTitle,
                    'status' => $inquiry->InquiryStatus,
                    'type' => 'Social Media Post', // You might want to add a type field to your database
                    'submittedDate' => $inquiry->SubmitionDate->format('F j, Y'),
                    'priority' => 'High', // You might want to add a priority field to your database
                    'assignedTo' => $inquiry->assignedAgency->AgencyName ?? 'Unassigned',
                    'description' => $inquiry->InquiryDescription,
                    'notes' => $inquiry->AdminComment,
                    'conclusion' => $inquiry->ResolvedExplanation,
                    'timeline' => $inquiry->timeline->map(function ($log) {
                        return [
                            'date' => $log->ActionDate->format('F j, Y - H:i'),
                            'event' => $log->Action
                        ];
                    })
                ];
            });

        return view('inquiry_list', [
            'inquiries' => $inquiries,
            'totalInquiries' => count($inquiries)
        ]);
    }

    public function show($id)
    {
        $inquiry = Inquiry::with(['timeline', 'assignedAgency', 'administrator'])
            ->findOrFail($id);

        $inquiryData = [
            'id' => $inquiry->InquiryID,
            'title' => $inquiry->InquiryTitle,
            'status' => $inquiry->InquiryStatus,
            'type' => 'Social Media Post',
            'submittedDate' => $inquiry->SubmitionDate->format('F j, Y'),
            'priority' => 'High',
            'assignedTo' => $inquiry->assignedAgency->AgencyName ?? 'Unassigned',
            'description' => $inquiry->InquiryDescription,
            'notes' => $inquiry->AdminComment,
            'conclusion' => $inquiry->ResolvedExplanation,
            'timeline' => $inquiry->timeline->map(function ($log) {
                return [
                    'date' => $log->ActionDate->format('F j, Y - H:i'),
                    'event' => $log->Action
                ];
            })
        ];

        return view('inquiry_detail', ['inquiry' => $inquiryData]);
    }
}
