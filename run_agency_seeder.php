<?php
// Simple test to run the AgencySeeder
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Database\Seeders\AgencySeeder;
use Illuminate\Support\Facades\DB;

try {
    echo "Running AgencySeeder...\n";
    
    // First, check if we can connect to database
    $connection = DB::connection();
    echo "Database connection: " . $connection->getName() . "\n";
    
    // Check if agencies table exists
    $tables = DB::select("SHOW TABLES");
    echo "Available tables:\n";
    foreach ($tables as $table) {
        $tableName = array_values((array)$table)[0];
        echo "- $tableName\n";
    }
    
    // Run the seeder
    $seeder = new AgencySeeder();
    $seeder->run();
    
    echo "Seeder completed successfully!\n";
    
    // Check results
    $count = DB::table('agencies')->count();
    echo "Total agencies in database: $count\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>
