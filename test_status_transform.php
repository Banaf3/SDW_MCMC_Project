<?php
// Test status transformations to ensure filtering works correctly

$statuses = [
    'Under Investigation',
    'Verified as True', 
    'Identified as Fake',
    'Rejected'
];

echo "Testing status transformations for filtering:\n\n";

foreach ($statuses as $status) {
    $transformed = strtolower(str_replace(' ', '-', $status));
    echo "Original: '$status' -> Transformed: '$transformed'\n";
}

echo "\nFilter dropdown values:\n";
$filterValues = [
    'under-investigation',
    'verified-true',
    'identified-fake',
    'rejected'
];

foreach ($filterValues as $value) {
    echo "Filter value: '$value'\n";
}

echo "\nTransformations match filter values: ";
$matches = true;
foreach ($statuses as $i => $status) {
    $transformed = strtolower(str_replace(' ', '-', $status));
    if ($transformed !== $filterValues[$i]) {
        $matches = false;
        break;
    }
}

echo $matches ? "YES ✓" : "NO ✗";
echo "\n";
?>
