<?php

// Simulate the evidence structure that would be created
$newsSource = "BBC News";
$urlsData = json_encode([
    "https://www.bbc.com/news/example-article",
    "https://www.cnn.com/related-story",
    "https://twitter.com/user/status/123456"
]);

// This is how the evidence is now structured in the controller
$evidence = [
    'ContentSource' => $newsSource, // Mapped from news_source
    'ContentURL' => json_decode($urlsData, true), // Mapped from urls_data
    'files' => [],
];

echo "=== Updated Evidence Structure ===\n";
echo "ContentSource: " . $evidence['ContentSource'] . "\n";
echo "ContentURL: " . json_encode($evidence['ContentURL'], JSON_PRETTY_PRINT) . "\n";
echo "Files: " . json_encode($evidence['files'], JSON_PRETTY_PRINT) . "\n";

echo "\n=== Full Evidence JSON ===\n";
echo json_encode($evidence, JSON_PRETTY_PRINT) . "\n";

echo "\n=== Changes Made ===\n";
echo "✓ Removed 'additional_context' field from validation and processing\n";
echo "✓ Mapped 'news_source' form field → 'ContentSource' in evidence JSON\n";
echo "✓ Mapped 'urls_data' form field → 'ContentURL' in evidence JSON\n";
echo "✓ Removed 'Additional Context' field from the form UI\n";
