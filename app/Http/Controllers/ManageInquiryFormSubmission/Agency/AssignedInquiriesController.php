<?php

namespace App\Http\Controllers\ManageInquiryFormSubmission\Agency;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use App\Models\InquiryAuditLog;
use App\Models\Agency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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

        $inquiry = Inquiry::with(['user', 'agency', 'auditLogs.administrator'])
            ->where('InquiryID', $inquiryId)
            ->where('AgencyID', $agencyId)
            ->whereIn('InquiryStatus', $allowedStatuses)
            ->firstOrFail();

        // Get full inquiry history (audit logs)
        $inquiryHistory = InquiryAuditLog::with('administrator')
            ->where('InquiryID', $inquiryId)
            ->orderByDesc('ActionDate')
            ->get();

        // Parse evidence JSON if it exists
        $evidence = $inquiry->InquiryEvidence ? json_decode($inquiry->InquiryEvidence, true) : [];

        return view('ManageInquiryFormSubmission.Agency.inquiry-detail', compact(
            'inquiry',
            'inquiryHistory',
            'evidence'
        ));
    }

    /**
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

    /**
     * Download evidence file for an inquiry
     */
    public function downloadEvidence($inquiryId, $fileIndex)
    {
        try {
            $agencyId = session('agency_id');
            
            // Temporary: If no agency_id in session, use agency ID 1 for testing
            if (!$agencyId) {
                $agencyId = 1;
                session(['agency_id' => $agencyId]);
            }

            // Find the inquiry and ensure it belongs to this agency
            $inquiry = Inquiry::where('InquiryID', $inquiryId)
                ->where('AgencyID', $agencyId)
                ->firstOrFail();

            // Parse evidence JSON
            $evidenceJson = $inquiry->InquiryEvidence;
            if (!$evidenceJson) {
                Log::error("No evidence found for inquiry {$inquiryId}");
                abort(404, 'No evidence found for this inquiry');
            }

            $evidence = json_decode($evidenceJson, true);
            if (!$evidence || !isset($evidence['files'])) {
                Log::error("Evidence files not found for inquiry {$inquiryId}. Evidence data: " . $evidenceJson);
                abort(404, 'Evidence files not found');
            }

            // Check if the file index exists
            if (!isset($evidence['files'][$fileIndex])) {
                Log::error("Evidence file index {$fileIndex} not found for inquiry {$inquiryId}. Available files: " . count($evidence['files']));
                abort(404, 'Evidence file not found');
            }

            $file = $evidence['files'][$fileIndex];
            
            // Try different possible file paths
            $possiblePaths = [
                storage_path('app/' . $file['path']),
                storage_path('app/public/' . $file['path']),
                storage_path('app/public/inquiries/files/' . basename($file['path']))
            ];
            
            $filePath = null;
            foreach ($possiblePaths as $path) {
                if (file_exists($path)) {
                    $filePath = $path;
                    break;
                }
            }

            Log::info("Attempting to access file. Possible paths checked: " . implode(', ', $possiblePaths));
            Log::info("Found file at: " . ($filePath ?? 'NONE'));

            // Check if file exists
            if (!$filePath) {
                Log::error("Evidence file not found in any location. Paths checked: " . implode(', ', $possiblePaths));
                abort(404, 'Evidence file not found on disk');
            }

            // Get the original filename
            $originalName = $file['original_name'] ?? $file['name'] ?? 'evidence_file';

            Log::info("Serving file: {$originalName} from {$filePath}");

            // Return the file for download/viewing
            return response()->file($filePath, [
                'Content-Type' => $file['mime_type'] ?? 'application/octet-stream',
                'Content-Disposition' => 'inline; filename="' . $originalName . '"'
            ]);
        } catch (\Exception $e) {
            Log::error("Error in downloadEvidence: " . $e->getMessage());
            Log::error("Stack trace: " . $e->getTraceAsString());
            abort(500, 'Error accessing evidence file: ' . $e->getMessage());
        }
    }
}
