<?php
require_once 'vendor/autoload.php';

// Load Laravel environment
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Test database connection and data
try {
    echo "=== DATABASE CONNECTION TEST ===\n";
    
    // Check if agencies exist
    $agencies = App\Models\Agency::all();
    echo "Found " . $agencies->count() . " agencies:\n";
    foreach ($agencies as $agency) {
        echo "- {$agency->AgencyName} (ID: {$agency->AgencyID})\n";
    }
    
    echo "\n=== INQUIRY DATA TEST ===\n";
    
    // Check if inquiries exist
    $totalInquiries = App\Models\Inquiry::count();
    echo "Total inquiries: {$totalInquiries}\n";
    
    // Check inquiries with agency assignments
    $assignedInquiries = App\Models\Inquiry::whereNotNull('AgencyID')->count();
    echo "Inquiries with agency assignments: {$assignedInquiries}\n";
    
    // Show some sample inquiries
    $sampleInquiries = App\Models\Inquiry::with('assignedAgency')
        ->whereNotNull('AgencyID')
        ->take(5)
        ->get();
    
    echo "\nSample inquiries with agencies:\n";
    foreach ($sampleInquiries as $inquiry) {
        $agencyName = $inquiry->assignedAgency->AgencyName ?? 'No Agency';
        echo "- Inquiry ID: {$inquiry->InquiryID}, Status: {$inquiry->InquiryStatus}, Agency: {$agencyName}\n";
    }
    
    echo "\n=== AGENCY PERFORMANCE CALCULATION TEST ===\n";
    
    // Test the same calculation as in the controller
    $agencies = App\Models\Agency::all();
    $agencyStats = [];

    // Initialize stats for all agencies
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

    // Get all inquiries with agency assignments
    $inquiries = App\Models\Inquiry::with(['assignedAgency'])
        ->whereNotNull('AgencyID')
        ->get();

    foreach ($inquiries as $inquiry) {
        $agencyName = $inquiry->assignedAgency->AgencyName ?? 'Unassigned';
        
        if (!isset($agencyStats[$agencyName])) {
            continue;
        }

        $agencyStats[$agencyName]['assigned']++;

        // Check if resolved
        if (in_array($inquiry->InquiryStatus, ['Verified as True', 'Identified as Fake'])) {
            $agencyStats[$agencyName]['resolved']++;
            $agencyStats[$agencyName]['resolvedCount']++;
            
            // Calculate resolution time if ResolvedDate exists
            if ($inquiry->ResolvedDate && $inquiry->SubmitionDate) {
                $resolutionTime = $inquiry->SubmitionDate->diffInDays($inquiry->ResolvedDate);
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

    echo "Agency Performance Stats:\n";
    foreach ($agencyStats as $agencyName => $stats) {
        echo "\n{$agencyName}:\n";
        echo "  - Assigned: {$stats['assigned']}\n";
        echo "  - Resolved: {$stats['resolved']}\n";
        echo "  - Pending: {$stats['pending']}\n";
        echo "  - Avg Time: {$stats['avgTime']} days\n";
        echo "  - Resolution Rate: {$stats['resolutionRate']}%\n";
        echo "  - Delays: {$stats['delays']}\n";
    }
    
    echo "\n=== JSON OUTPUT TEST ===\n";
    echo "JSON for JavaScript:\n";
    echo json_encode($agencyStats, JSON_PRETTY_PRINT);
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>
