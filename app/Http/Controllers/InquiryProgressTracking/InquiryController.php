<?php

namespace App\Http\Controllers\InquiryProgressTracking;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use App\Models\Agency;
use Illuminate\Http\Request;

class InquiryController extends Controller
{    public function index()
    {
        // Get user ID from session, fallback to 3 for demo purposes
        $currentUserId = session('user_id', 3);
        
        try {
            $inquiries = Inquiry::with(['timeline', 'assignedAgency', 'administrator'])
                ->where('UserID', $currentUserId) // Only show inquiries for current user
                ->orderBy('SubmitionDate', 'desc')->get()
                ->map(function ($inquiry) {
                    return [
                        'id' => $inquiry->InquiryID,
                        'title' => $inquiry->InquiryTitle,
                        'status' => $inquiry->InquiryStatus,
                        'type' => 'Social Media Post',
                        'submittedDate' => $inquiry->SubmitionDate->format('F j, Y'),
                        'submittedDateISO' => $inquiry->SubmitionDate->format('Y-m-d'), // For JavaScript Date parsing
                        'assignedTo' => $inquiry->assignedAgency->AgencyName ?? 'Unassigned',
                        'assignedDate' => $inquiry->updated_at ? $inquiry->updated_at->format('F j, Y') : 'Not assigned',
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
        } catch (\Exception $e) {
            // Fallback to sample data if database is not available
            $inquiries = collect([
                [
                    'id' => 1,
                    'title' => 'Fake News Report - Social Media',
                    'status' => 'Under Investigation',
                    'type' => 'Social Media Post',
                    'submittedDate' => 'June 15, 2025',
                    'submittedDateISO' => '2025-06-15',
                    'assignedTo' => 'Malaysian Communications and Multimedia Commission (MCMC)',
                    'assignedDate' => 'June 15, 2025',
                    'description' => 'Suspicious social media post containing false information about government policies.',
                    'notes' => 'High priority case due to public interest.',
                    'conclusion' => null,
                    'reference_number' => 'VT-2025-000001',
                    'timeline' => [
                        ['date' => 'June 15, 2025 - 10:30', 'event' => 'Inquiry Submitted']
                    ]
                ],
                [
                    'id' => 2,
                    'title' => 'Misinformation on Health Topics',
                    'status' => 'Verified as True',
                    'type' => 'Social Media Post',
                    'submittedDate' => 'June 14, 2025',
                    'submittedDateISO' => '2025-06-14',
                    'assignedTo' => 'Ministry of Health',
                    'assignedDate' => 'June 14, 2025',
                    'description' => 'Report about misleading health information being shared online.',
                    'notes' => 'Investigation completed successfully.',
                    'conclusion' => 'Content verified and found to be accurate.',
                    'reference_number' => 'VT-2025-000002',
                    'timeline' => [
                        ['date' => 'June 14, 2025 - 09:15', 'event' => 'Inquiry Submitted'],
                        ['date' => 'June 14, 2025 - 16:30', 'event' => 'Investigation Completed']
                    ]
                ]
            ]);
        }        return view('Module4.Public.inquiry_list', [
            'inquiries' => $inquiries,
            'totalInquiries' => count($inquiries),
            'currentUserId' => $currentUserId // Pass current user ID to view
        ]);
    }    public function show($id, Request $request = null)
    {
        try {
            $inquiry = Inquiry::with(['assignedAgency', 'administrator', 'assignedStaff', 'user'])
                ->findOrFail($id);

            // Generate timeline from real data
            $timeline = [];
            
            // 1. Inquiry Submitted
            $timeline[] = [
                'date' => $inquiry->SubmitionDate->format('F j, Y - H:i'),
                'event' => 'Inquiry Submitted',
                'description' => 'Inquiry was submitted by ' . ($inquiry->user->UserName ?? 'Unknown User'),
                'icon' => '📝',
                'type' => 'submitted'
            ];

            // 2. Agency Assignment (if assigned)
            if ($inquiry->AgencyID && $inquiry->assignedAgency) {
                $timeline[] = [
                    'date' => $inquiry->updated_at->format('F j, Y - H:i'),
                    'event' => 'Assigned to Agency',
                    'description' => 'Inquiry assigned to ' . $inquiry->assignedAgency->AgencyName,
                    'icon' => '🏢',
                    'type' => 'assigned'
                ];
            }

            // 3. Status Updates (if status changed from default)
            if ($inquiry->InquiryStatus && $inquiry->InquiryStatus !== 'Pending') {
                $statusIcon = match($inquiry->InquiryStatus) {
                    'Under Investigation' => '🔍',
                    'Verified as True' => '✅',
                    'Identified as Fake' => '❌',
                    'Rejected' => '🚫',
                    'Resolved' => '✅',
                    default => '🔄'
                };
                
                $timeline[] = [
                    'date' => $inquiry->updated_at->format('F j, Y - H:i'),
                    'event' => 'Status Updated',
                    'description' => 'Status changed to: ' . $inquiry->InquiryStatus,
                    'icon' => $statusIcon,
                    'type' => 'status'
                ];
            }

            // 4. Resolution (if resolved explanation exists)
            if ($inquiry->ResolvedExplanation) {
                $timeline[] = [
                    'date' => $inquiry->updated_at->format('F j, Y - H:i'),
                    'event' => 'Investigation Completed',
                    'description' => 'Investigation completed with resolution details',
                    'icon' => '🏁',
                    'type' => 'resolved'
                ];
            }

            // 5. Supporting Documents Added (if exists)
            if ($inquiry->ResolvedSupportingDocs) {
                $timeline[] = [
                    'date' => $inquiry->updated_at->format('F j, Y - H:i'),
                    'event' => 'Supporting Documents Added',
                    'description' => 'Additional documentation provided by investigating agency',
                    'icon' => '📎',
                    'type' => 'documents'
                ];
            }

            $inquiryData = [
                'id' => $inquiry->InquiryID,
                'title' => $inquiry->InquiryTitle,
                'status' => $inquiry->InquiryStatus,
                'type' => 'Social Media Post',
                'submittedDate' => $inquiry->SubmitionDate->format('F j, Y'),
                'submittedDateISO' => $inquiry->SubmitionDate->format('Y-m-d'),
                'assignedTo' => $inquiry->assignedAgency->AgencyName ?? 'Unassigned',
                'agencyDescription' => $inquiry->assignedAgency->AgencyDescription ?? 'No description available',
                'officerName' => $inquiry->assignedStaff->StaffName ?? null,
                'userDescription' => $inquiry->InquiryDescription,
                'agencyComment' => $inquiry->AdminComment ?? null,
                'evidence' => $inquiry->InquiryEvidence ?? null,
                'agencySupportingDocs' => $inquiry->ResolvedSupportingDocs ?? null,
                'notes' => $inquiry->AdminComment ?? null,
                'conclusion' => $inquiry->ResolvedExplanation ?? null,
                'reference_number' => 'VT-' . $inquiry->SubmitionDate->format('Y') . '-' . str_pad($inquiry->InquiryID, 6, '0', STR_PAD_LEFT),
                'timeline' => $timeline,
                // Additional data for MCMC view
                'userId' => $inquiry->UserID,
                'agencyId' => $inquiry->AgencyID,
                'adminId' => $inquiry->AdminID,
                'submittedBy' => $inquiry->user->UserName ?? 'Unknown User',
                'userEmail' => $inquiry->user->UserEmail ?? 'N/A',
                'createdAt' => $inquiry->created_at->format('F j, Y - H:i'),
                'updatedAt' => $inquiry->updated_at->format('F j, Y - H:i')
            ];
        } catch (\Exception $e) {
            // Fallback to sample data if database inquiry not found
            $sampleInquiries = [
                1 => [
                    'id' => 1,
                    'title' => 'Fake News Report - Social Media',
                    'status' => 'Under Investigation',
                    'type' => 'Social Media Post',
                    'submittedDate' => 'June 15, 2025',
                    'submittedDateISO' => '2025-06-15',
                    'assignedTo' => 'Malaysian Communications and Multimedia Commission (MCMC)',
                    'agencyDescription' => 'The regulatory body for communications and multimedia in Malaysia.',
                    'officerName' => 'John Doe',
                    'userDescription' => 'I found a suspicious social media post that appears to contain false information about government policies. The post has been shared multiple times and could mislead the public.',
                    'agencyComment' => 'Investigation has been initiated. We are reviewing the content and will take appropriate action.',
                    'evidence' => 'Screenshot of social media post, URL links, and user reports.',
                    'agencySupportingDocs' => null,
                    'notes' => 'High priority case due to public interest.',
                    'conclusion' => null,
                    'reference_number' => 'VT-2025-000001',
                    'timeline' => [
                        [
                            'date' => 'June 15, 2025 - 10:30',
                            'event' => 'Inquiry Submitted',
                            'description' => 'Inquiry was submitted by Test User',
                            'icon' => '📝',
                            'type' => 'submitted'
                        ],
                        [
                            'date' => 'June 15, 2025 - 14:20',
                            'event' => 'Assigned to Agency',
                            'description' => 'Inquiry assigned to Malaysian Communications and Multimedia Commission (MCMC)',
                            'icon' => '🏢',
                            'type' => 'assigned'
                        ],
                        [
                            'date' => 'June 15, 2025 - 16:45',
                            'event' => 'Status Updated',
                            'description' => 'Status changed to: Under Investigation',
                            'icon' => '🔍',
                            'type' => 'status'
                        ]
                    ],
                    'userId' => 1,
                    'agencyId' => 1,
                    'adminId' => 1,
                    'submittedBy' => 'Test User',
                    'userEmail' => 'test@example.com',
                    'createdAt' => 'June 15, 2025 - 10:30',
                    'updatedAt' => 'June 15, 2025 - 16:45'
                ],
                2 => [
                    'id' => 2,
                    'title' => 'Misinformation on Health Topics',
                    'status' => 'Verified as True',
                    'type' => 'Social Media Post',
                    'submittedDate' => 'June 14, 2025',
                    'submittedDateISO' => '2025-06-14',
                    'assignedTo' => 'Ministry of Health',
                    'agencyDescription' => 'Government ministry responsible for health policies.',
                    'officerName' => 'Dr. Sarah Ahmad',
                    'userDescription' => 'Report about misleading health information being shared online.',
                    'agencyComment' => 'Investigation completed. Content verified as accurate.',
                    'evidence' => 'Medical journals, expert reviews, official statements.',
                    'agencySupportingDocs' => 'Official health ministry verification document.',
                    'notes' => 'Investigation completed successfully.',
                    'conclusion' => 'Content verified and found to be accurate. No further action required.',
                    'reference_number' => 'VT-2025-000002',
                    'timeline' => [
                        [
                            'date' => 'June 14, 2025 - 09:15',
                            'event' => 'Inquiry Submitted',
                            'description' => 'Inquiry was submitted by Test User',
                            'icon' => '📝',
                            'type' => 'submitted'
                        ],
                        [
                            'date' => 'June 14, 2025 - 11:30',
                            'event' => 'Assigned to Agency',
                            'description' => 'Inquiry assigned to Ministry of Health',
                            'icon' => '🏢',
                            'type' => 'assigned'
                        ],
                        [
                            'date' => 'June 14, 2025 - 14:20',
                            'event' => 'Status Updated',
                            'description' => 'Status changed to: Under Investigation',
                            'icon' => '🔍',
                            'type' => 'status'
                        ],
                        [
                            'date' => 'June 14, 2025 - 16:30',
                            'event' => 'Investigation Completed',
                            'description' => 'Investigation completed with resolution details',
                            'icon' => '🏁',
                            'type' => 'resolved'
                        ]
                    ],
                    'userId' => 1,
                    'agencyId' => 2,
                    'adminId' => 1,
                    'submittedBy' => 'Test User',
                    'userEmail' => 'test@example.com',
                    'createdAt' => 'June 14, 2025 - 09:15',
                    'updatedAt' => 'June 14, 2025 - 16:30'
                ]
            ];
            
            $inquiryData = $sampleInquiries[$id] ?? $sampleInquiries[1];
        }

        // Check if this is MCMC view request by checking the route path
        if (request()->is('mcmc-inquiry-detail/*')) {
            return view('Module4-MCMC.InquiryDetail', ['inquiry' => $inquiryData]);
        }

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

        // Calculate agency performance metrics
        $agencyPerformance = $this->calculateAgencyPerformance();

        return view('Module4-MCMC.inquiryList', [
            'inquiries' => $inquiries,
            'totalInquiries' => count($inquiries),
            'statusCounts' => $statusCounts,
            'agencyPerformance' => $agencyPerformance,
            'currentUserId' => 1 // For notification system
        ]);
    }

    public function reports()
    {
        try {
            // Get all agencies from database
            $agencies = Agency::orderBy('AgencyName')->get();
            
            // Calculate real agency performance metrics from database
            $agencyPerformance = $this->calculateAgencyPerformance();
            
            // Debug: Add some test data if no real data exists
            if (empty($agencyPerformance) || collect($agencyPerformance)->sum('assigned') == 0) {
                // Create sample data for demonstration
                $agencyPerformance = $this->generateSampleAgencyData($agencies);
            }
            
            // Debug logging
            \Log::info('Reports data:', [
                'agencies_count' => $agencies->count(),
                'agency_names' => $agencies->pluck('AgencyName')->toArray(),
                'performance_keys' => array_keys($agencyPerformance),
                'total_assigned' => collect($agencyPerformance)->sum('assigned')
            ]);
            
            // Get real inquiry statistics
            $totalInquiries = Inquiry::count();
            $statusCounts = [
                'Under Investigation' => Inquiry::where('InquiryStatus', 'Under Investigation')->count(),
                'Verified as True' => Inquiry::where('InquiryStatus', 'Verified as True')->count(),
                'Identified as Fake' => Inquiry::where('InquiryStatus', 'Identified as Fake')->count(),
                'Rejected' => Inquiry::where('InquiryStatus', 'Rejected')->count(),
            ];

            return view('Module4-MCMC.Report', [
                'agencies' => $agencies,
                'agencyPerformance' => $agencyPerformance,
                'totalInquiries' => $totalInquiries,
                'statusCounts' => $statusCounts,
                'currentUserId' => 1 // For notification system
            ]);
        } catch (\Exception $e) {
            \Log::error('Reports method error: ' . $e->getMessage());
            
            // Fallback: Create minimal sample data
            $agencies = collect([]);
            $agencyPerformance = $this->generateSampleAgencyData();
            
            return view('Module4-MCMC.Report', [
                'agencies' => $agencies,
                'agencyPerformance' => $agencyPerformance,
                'totalInquiries' => 50,
                'statusCounts' => [
                    'Under Investigation' => 15,
                    'Verified as True' => 20,
                    'Identified as Fake' => 10,
                    'Rejected' => 5,
                ],
                'currentUserId' => 1
            ]);
        }
    }

    public function mcmcShow($id)
    {
        $inquiry = Inquiry::with(['assignedAgency', 'administrator', 'assignedStaff', 'user'])
            ->findOrFail($id);

        // Generate timeline from real data (same as public view but with more admin context)
        $timeline = [];
        
        // 1. Inquiry Submitted
        $timeline[] = [
            'date' => $inquiry->SubmitionDate->format('F j, Y - H:i'),
            'event' => 'Inquiry Submitted',
            'description' => 'Inquiry was submitted by ' . ($inquiry->user->UserName ?? 'Unknown User') . ' (UserID: ' . $inquiry->UserID . ')',
            'icon' => '📝',
            'type' => 'submitted'
        ];

        // 2. Agency Assignment (if assigned)
        if ($inquiry->AgencyID && $inquiry->assignedAgency) {
            $timeline[] = [
                'date' => $inquiry->updated_at->format('F j, Y - H:i'),
                'event' => 'Assigned to Agency',
                'description' => 'Inquiry assigned to ' . $inquiry->assignedAgency->AgencyName . ' (AgencyID: ' . $inquiry->AgencyID . ')',
                'icon' => '🏢',
                'type' => 'assigned'
            ];
        }

        // 3. Status Updates (if status changed from default)
        if ($inquiry->InquiryStatus && $inquiry->InquiryStatus !== 'Pending') {
            $statusIcon = match($inquiry->InquiryStatus) {
                'Under Investigation' => '🔍',
                'Verified as True' => '✅',
                'Identified as Fake' => '❌',
                'Rejected' => '🚫',
                'Resolved' => '✅',
                default => '🔄'
            };
            
            $timeline[] = [
                'date' => $inquiry->updated_at->format('F j, Y - H:i'),
                'event' => 'Status Updated',
                'description' => 'Status changed to: ' . $inquiry->InquiryStatus . ' by investigating agency',
                'icon' => $statusIcon,
                'type' => 'status'
            ];
        }

        // 4. Resolution (if resolved explanation exists)
        if ($inquiry->ResolvedExplanation) {
            $timeline[] = [
                'date' => $inquiry->updated_at->format('F j, Y - H:i'),
                'event' => 'Investigation Completed',
                'description' => 'Investigation completed with detailed resolution by ' . ($inquiry->assignedAgency->AgencyName ?? 'investigating agency'),
                'icon' => '🏁',
                'type' => 'resolved'
            ];
        }

        // 5. Supporting Documents Added (if exists)
        if ($inquiry->ResolvedSupportingDocs) {
            $timeline[] = [
                'date' => $inquiry->updated_at->format('F j, Y - H:i'),
                'event' => 'Supporting Documents Added',
                'description' => 'Additional documentation provided by investigating agency: ' . $inquiry->ResolvedSupportingDocs,
                'icon' => '📎',
                'type' => 'documents'
            ];
        }

        $inquiryData = [
            'id' => $inquiry->InquiryID,
            'title' => $inquiry->InquiryTitle,
            'status' => $inquiry->InquiryStatus,
            'type' => 'Social Media Post',
            'submittedDate' => $inquiry->SubmitionDate->format('F j, Y'),
            'submittedDateISO' => $inquiry->SubmitionDate->format('Y-m-d'),
            'assignedTo' => $inquiry->assignedAgency->AgencyName ?? 'Unassigned',
            'agencyDescription' => $inquiry->assignedAgency->AgencyDescription ?? 'No description available',
            'officerName' => $inquiry->assignedStaff->StaffName ?? null,
            'userDescription' => $inquiry->InquiryDescription,
            'agencyComment' => $inquiry->AdminComment ?? null,
            'evidence' => $inquiry->InquiryEvidence ?? null,
            'agencySupportingDocs' => $inquiry->ResolvedSupportingDocs ?? null,
            'notes' => $inquiry->AdminComment ?? null,
            'conclusion' => $inquiry->ResolvedExplanation ?? null,
            'reference_number' => 'VT-' . $inquiry->SubmitionDate->format('Y') . '-' . str_pad($inquiry->InquiryID, 6, '0', STR_PAD_LEFT),
            'timeline' => $timeline,
            // Additional MCMC-specific data
            'userId' => $inquiry->UserID,
            'agencyId' => $inquiry->AgencyID,
            'adminId' => $inquiry->AdminID,
            'submittedBy' => $inquiry->user->UserName ?? 'Unknown User',
            'userEmail' => $inquiry->user->UserEmail ?? 'N/A',
            'createdAt' => $inquiry->created_at->format('F j, Y - H:i'),
            'updatedAt' => $inquiry->updated_at->format('F j, Y - H:i')
        ];

        return view('Module4-MCMC.InquiryDetail', ['inquiry' => $inquiryData]);
    }

    public function publicShow($id)
    {
        // Get user ID from session, fallback to 3 for demo purposes
        $currentUserId = session('user_id', 3);
        
        try {
            $inquiry = Inquiry::with(['timeline', 'assignedAgency', 'administrator', 'assignedStaff'])
                ->where('InquiryID', $id)
                ->where('UserID', $currentUserId) // Only show inquiries for current user
                ->first();
            
            if (!$inquiry) {
                return redirect()->route('public.progress.track')->with('error', 'Inquiry not found or access denied.');
            }
            
            $inquiryData = [
                'id' => $inquiry->InquiryID,
                'title' => $inquiry->InquiryTitle,
                'status' => $inquiry->InquiryStatus,
                'submittedDate' => $inquiry->SubmitionDate->format('F j, Y'),
                'assignedTo' => $inquiry->assignedAgency->AgencyName ?? 'Unassigned',
                'assignedDate' => $inquiry->updated_at ? $inquiry->updated_at->format('F j, Y') : 'Not assigned',
                'agencyDescription' => $inquiry->assignedAgency->AgencyName ?? 'Not assigned yet',
                'officerName' => $inquiry->assignedStaff->StaffName ?? 'Not assigned',
                'userDescription' => $inquiry->InquiryDescription,
                'evidence' => $inquiry->Evidence,
                'agencyComment' => $inquiry->AdminComment,
                'agencySupportingDocs' => $inquiry->AgencySupportingDoc,
                'timeline' => $inquiry->timeline->map(function ($log) {
                    $type = 'default';
                    $icon = '📋';
                    
                    if (str_contains(strtolower($log->Action), 'submit')) {
                        $type = 'submitted';
                        $icon = '📝';
                    } elseif (str_contains(strtolower($log->Action), 'assign')) {
                        $type = 'assigned';
                        $icon = '👥';
                    } elseif (str_contains(strtolower($log->Action), 'status')) {
                        $type = 'status';
                        $icon = '📊';
                    } elseif (str_contains(strtolower($log->Action), 'document') || str_contains(strtolower($log->Action), 'evidence')) {
                        $type = 'documents';
                        $icon = '📎';
                    }
                    
                    return [
                        'date' => $log->ActionDate->format('M j, Y'),
                        'event' => $log->Action,
                        'description' => $log->Action,
                        'type' => $type,
                        'icon' => $icon
                    ];
                })
            ];
        } catch (\Exception $e) {
            // Fallback to sample data if database is not available
            $inquiryData = [
                'id' => $id,
                'title' => 'Sample Inquiry - ' . $id,
                'status' => 'Under Investigation',
                'submittedDate' => 'June 15, 2025',
                'assignedTo' => 'Malaysian Communications and Multimedia Commission',
                'assignedDate' => 'June 16, 2025',
                'agencyDescription' => 'Malaysian Communications and Multimedia Commission',
                'officerName' => 'John Doe',
                'userDescription' => 'This is a sample inquiry description for demonstration purposes.',
                'evidence' => 'sample-evidence.pdf',
                'agencyComment' => null,
                'agencySupportingDocs' => null,
                'timeline' => [
                    [
                        'date' => 'Jun 15, 2025',
                        'event' => 'Inquiry Submitted',
                        'description' => 'Initial inquiry submitted by user',
                        'type' => 'submitted',
                        'icon' => '📝'
                    ],
                    [
                        'date' => 'Jun 16, 2025',
                        'event' => 'Assigned to MCMC',
                        'description' => 'Inquiry assigned to Malaysian Communications and Multimedia Commission',
                        'type' => 'assigned',
                        'icon' => '👥'
                    ]
                ]
            ];
        }
        
        return view('Module4.Public.inquiry_detail', ['inquiry' => $inquiryData]);
    }

    private function calculateAgencyPerformance()
    {
        try {
            // Get all agencies from database
            $agencies = Agency::all();
            $agencyStats = [];

            // Initialize stats for all agencies (even those with no inquiries)
            foreach ($agencies as $agency) {
                $agencyStats[$agency->AgencyName] = [
                    'assigned' => 0,
                    'resolved' => 0,
                    'pending' => 0,
                    'totalTime' => 0,
                    'resolvedCount' => 0,
                    'delays' => 0,
                    'avgTime' => 0,
                    'resolutionRate' => 0
                ];
            }

            // Get all inquiries (not just those with agency assignments)
            $inquiries = Inquiry::with(['assignedAgency'])->get();

            foreach ($inquiries as $inquiry) {
                $agencyName = 'Unassigned';
                
                // Try to get agency name from different sources
                if ($inquiry->assignedAgency) {
                    $agencyName = $inquiry->assignedAgency->AgencyName;
                } elseif ($inquiry->AgencyID) {
                    // Try to find agency by ID if relationship failed
                    $agency = Agency::find($inquiry->AgencyID);
                    if ($agency) {
                        $agencyName = $agency->AgencyName;
                    }
                }
                
                // Initialize agency stats if not exists (for unassigned or missing agencies)
                if (!isset($agencyStats[$agencyName])) {
                    $agencyStats[$agencyName] = [
                        'assigned' => 0,
                        'resolved' => 0,
                        'pending' => 0,
                        'totalTime' => 0,
                        'resolvedCount' => 0,
                        'delays' => 0,
                        'avgTime' => 0,
                        'resolutionRate' => 0
                    ];
                }

                $agencyStats[$agencyName]['assigned']++;

                // Check if resolved (include more status options)
                $resolvedStatuses = [
                    'Verified as True', 
                    'Identified as Fake', 
                    'Resolved',
                    'Verified',
                    'Fake',
                    'Completed'
                ];
                
                if (in_array($inquiry->InquiryStatus, $resolvedStatuses)) {
                    $agencyStats[$agencyName]['resolved']++;
                    $agencyStats[$agencyName]['resolvedCount']++;
                    
                    // Calculate resolution time
                    if ($inquiry->SubmitionDate) {
                        $endDate = $inquiry->ResolvedDate ?? $inquiry->updated_at ?? now();
                        $resolutionTime = $inquiry->SubmitionDate->diffInDays($endDate);
                        $agencyStats[$agencyName]['totalTime'] += $resolutionTime;
                        
                        // Count delays (more than 7 days)
                        if ($resolutionTime > 7) {
                            $agencyStats[$agencyName]['delays']++;
                        }
                    }
                } else {
                    $agencyStats[$agencyName]['pending']++;
                    
                    // Check for pending delays (more than 7 days since submission)
                    if ($inquiry->SubmitionDate && $inquiry->SubmitionDate->diffInDays(now()) > 7) {
                        $agencyStats[$agencyName]['delays']++;
                    }
                }
            }

            // Calculate averages for each agency
            foreach ($agencyStats as $agencyName => &$stats) {
                $stats['avgTime'] = $stats['resolvedCount'] > 0 
                    ? round($stats['totalTime'] / $stats['resolvedCount'], 1) 
                    : 0;
                $stats['resolutionRate'] = $stats['assigned'] > 0 
                    ? round(($stats['resolved'] / $stats['assigned']) * 100, 1) 
                    : 0;
            }

            return $agencyStats;
            
        } catch (\Exception $e) {
            \Log::error('calculateAgencyPerformance error: ' . $e->getMessage());
            return [];
        }
    }

    private function generateSampleAgencyData($agencies = null)
    {
        $agencyNames = [];

        // Use real agency names if available
        if ($agencies && $agencies->count() > 0) {
            $agencyNames = $agencies->pluck('AgencyName')->toArray();
        } else {
            // Try to get agencies from database
            try {
                $dbAgencies = Agency::orderBy('AgencyName')->get();
                if ($dbAgencies->count() > 0) {
                    $agencyNames = $dbAgencies->pluck('AgencyName')->toArray();
                }
            } catch (\Exception $e) {
                // Fallback to default agency names if database fails
                $agencyNames = [
                    'CyberSecurity Malaysia',
                    'Ministry of Health Malaysia (MOH)',
                    'Royal Malaysia Police (PDRM)',
                    'Ministry of Domestic Trade and Consumer Affairs (KPDN)',
                    'Ministry of Education (MOE)',
                    'Ministry of Communications and Digital (KKD)',
                    'Department of Islamic Development Malaysia (JAKIM)',
                    'Election Commission of Malaysia (SPR)',
                    'Malaysian Anti-Corruption Commission (MACC / SPRM)',
                    'Department of Environment Malaysia (DOE)'
                ];
            }
        }

        $sampleData = [];
        
        foreach ($agencyNames as $agencyName) {
            // Generate realistic sample data
            $assigned = rand(5, 25);
            $resolved = rand(2, $assigned - 1);
            $pending = $assigned - $resolved;
            $avgTime = rand(3, 15);
            $delays = rand(0, floor($assigned * 0.3));
            
            $sampleData[$agencyName] = [
                'assigned' => $assigned,
                'resolved' => $resolved,
                'pending' => $pending,
                'totalTime' => $resolved * $avgTime,
                'resolvedCount' => $resolved,
                'delays' => $delays,
                'avgTime' => $avgTime,
                'resolutionRate' => $assigned > 0 ? round(($resolved / $assigned) * 100, 1) : 0
            ];
        }
        
        return $sampleData;
    }
}
