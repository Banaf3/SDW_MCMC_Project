<?php

// Test status conversion to verify CSS class names
$statuses = [
    'Under Investigation',
    'Verified as True', 
    'Identified as Fake',
    'Rejected'
];

echo "Status conversion test:\n\n";

foreach ($statuses as $status) {
    $converted = 'status-' . strtolower(str_replace(' ', '-', $status));
    echo "'{$status}' -> '{$converted}'\n";
}

echo "\nMake sure CSS classes match these converted names!\n";

?>
