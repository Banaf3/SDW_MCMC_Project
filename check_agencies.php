<?php
require_once 'vendor/autoload.php';

// Load Laravel configuration
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    echo "Checking agencies table...\n";
    
    // Check if agencies table exists and has data
    $agencies = DB::table('agencies')->get();
    
    echo "Found " . count($agencies) . " agencies in the database:\n\n";
    
    foreach ($agencies as $agency) {
        echo "ID: " . $agency->AgencyID . "\n";
        echo "Name: " . $agency->AgencyName . "\n";
        echo "Email: " . $agency->AgencyEmail . "\n";
        echo "Type: " . $agency->AgencyType . "\n";
        echo "-------------------\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
