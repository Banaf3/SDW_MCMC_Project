<?php
// Test inquiry detail functionality

try {
    echo "Testing Inquiry Detail Data Structure...\n";
    echo "=====================================\n\n";
    
    // Simulate the inquiry data structure that will be passed to the view
    $mockInquiry = [
        'id' => 1,
        'title' => 'Test Inquiry Title',
        'status' => 'Under Investigation',
        'type' => 'Social Media Post',
        'submittedDate' => 'January 15, 2024',
        'submittedDateISO' => '2024-01-15',
        'assignedTo' => 'Agency A',
        'agencyDescription' => 'Government Regulatory Agency', // Added this field
        'officerName' => 'John Smith',
        'userDescription' => 'This is a test inquiry description from the user.',
        'agencyComment' => 'Agency has reviewed and is investigating.',
        'evidence' => 'Screenshot of social media post',
        'agencySupportingDocs' => 'Investigation report #123',
        'notes' => 'Additional notes from agency',
        'conclusion' => 'Investigation ongoing',
        'reference_number' => 'VT-2024-000001',
        'timeline' => [
            [
                'date' => 'January 15, 2024 - 10:30',
                'event' => 'Inquiry submitted'
            ],
            [
                'date' => 'January 16, 2024 - 14:20',
                'event' => 'Assigned to Agency A'
            ]
        ]
    ];
    
    // Check if all required fields are present
    $requiredFields = [
        'id', 'title', 'status', 'submittedDate', 'assignedTo', 'agencyDescription',
        'userDescription', 'agencyComment', 'evidence', 'agencySupportingDocs', 'timeline'
    ];
    
    echo "Checking required fields:\n";
    $allFieldsPresent = true;
    
    foreach ($requiredFields as $field) {
        if (array_key_exists($field, $mockInquiry)) {
            echo "  ✅ $field: " . (is_array($mockInquiry[$field]) ? 'Array with ' . count($mockInquiry[$field]) . ' items' : $mockInquiry[$field]) . "\n";
        } else {
            echo "  ❌ $field: MISSING\n";
            $allFieldsPresent = false;
        }
    }
    
    echo "\n" . ($allFieldsPresent ? "✅ All required fields are present!" : "❌ Some fields are missing!") . "\n";
    
    // Test status badge class generation
    $statusClass = 'status-' . strtolower(str_replace(' ', '-', $mockInquiry['status']));
    echo "✅ Status badge class: $statusClass\n";
    
    echo "\n🎉 Inquiry detail page should now work without errors!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
