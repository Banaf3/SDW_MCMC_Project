<?php

// Simple test script to verify Laravel routing
$basePath = 'c:/xampp/htdocs/SDW_MCMC_Project';

// Check if Laravel can boot
try {
    require_once $basePath . '/vendor/autoload.php';
    $app = require_once $basePath . '/bootstrap/app.php';
    
    echo "✅ Laravel autoload successful\n";
    
    // Try to access the application
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    echo "✅ Laravel kernel loaded\n";
    
    // Test database connection
    $app->make('db')->connection()->getPdo();
    echo "✅ Database connection successful\n";
    
    // Check if inquiry exists
    $inquiryCount = \App\Models\Inquiry::count();
    echo "✅ Found {$inquiryCount} inquiries in database\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
