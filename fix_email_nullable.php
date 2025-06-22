<?php
// Fix the staffEmail field to allow NULL values

try {
    // Database connection
    $host = 'localhost';
    $dbname = 'mcmc';
    $username = 'root';
    $password = '';
    
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== FIXING STAFF EMAIL FIELD ===\n";
    
    // Check current structure
    echo "1. Checking current staffEmail field structure...\n";
    $stmt = $pdo->query("DESCRIBE agency_staff");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($columns as $column) {
        if ($column['Field'] === 'staffEmail') {
            echo "   Current: {$column['Field']} - Type: {$column['Type']} - Null: {$column['Null']} - Default: {$column['Default']}\n";
            break;
        }
    }
    
    // Modify the column to allow NULL
    echo "\n2. Modifying staffEmail column to allow NULL...\n";
    $pdo->exec("ALTER TABLE agency_staff MODIFY COLUMN staffEmail VARCHAR(255) NULL");
    
    echo "   ✅ staffEmail column updated to allow NULL values\n";
    
    // Verify the change
    echo "\n3. Verifying the change...\n";
    $stmt = $pdo->query("DESCRIBE agency_staff");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($columns as $column) {
        if ($column['Field'] === 'staffEmail') {
            echo "   Updated: {$column['Field']} - Type: {$column['Type']} - Null: {$column['Null']} - Default: {$column['Default']}\n";
            break;
        }
    }
    
    echo "\n✅ Database fix completed!\n";
    
} catch (PDOException $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
