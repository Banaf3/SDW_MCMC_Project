<?php
// Simple test to check if the reports page renders correctly
try {
    // Start output buffering
    ob_start();
    
    // Set up environment
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
    
    // Create a request to the reports route
    $request = Illuminate\Http\Request::create('/reports', 'GET');
    
    // Get the controller
    $controller = new App\Http\Controllers\InquiryController();
      // Call the reports method
    $response = $controller->reports();
    
    // Render the view to get the content
    $content = $response->render();
    
    // Stop output buffering
    ob_end_clean();
    
    echo "=== REPORTS PAGE TEST ===\n";    echo "Response status: SUCCESS\n";
    echo "Content length: " . strlen($content) . " characters\n";
    
    // Check if the JavaScript data is properly embedded
    if (strpos($content, 'agencyData') !== false) {
        echo "✓ JavaScript agencyData variable found in content\n";
    } else {
        echo "✗ JavaScript agencyData variable NOT found in content\n";
    }
    
    // Check if the table structure exists
    if (strpos($content, 'reportsTableBody') !== false) {
        echo "✓ Table structure found in content\n";
    } else {
        echo "✗ Table structure NOT found in content\n";
    }
    
    // Check if the generateReports function exists
    if (strpos($content, 'generateReports()') !== false) {
        echo "✓ generateReports() function call found in content\n";
    } else {
        echo "✗ generateReports() function call NOT found in content\n";
    }
    
    // Extract and show the JSON data
    if (preg_match('/const agencyData = (.+?);/', $content, $matches)) {
        echo "\n=== EMBEDDED AGENCY DATA ===\n";
        $jsonData = $matches[1];
        echo "Raw JSON: " . substr($jsonData, 0, 200) . "...\n";
        
        $decoded = json_decode($jsonData, true);
        if ($decoded) {
            echo "Successfully decoded JSON with " . count($decoded) . " agencies\n";
            foreach ($decoded as $agencyName => $stats) {
                if ($stats['assigned'] > 0) {
                    echo "- {$agencyName}: {$stats['assigned']} assigned, {$stats['resolved']} resolved\n";
                }
            }
        } else {
            echo "Failed to decode JSON data\n";
        }
    } else {
        echo "Could not extract agency data from response\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
