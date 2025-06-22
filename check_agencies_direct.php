<?php
// Direct MySQL connection to check if agencies were seeded
$host = '127.0.0.1';
$dbname = 'mcmc';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to MySQL database successfully.\n\n";
    
    // Check if agencies table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'agencies'");
    if ($stmt->rowCount() > 0) {
        echo "✓ Agencies table exists.\n\n";
        
        // Count agencies
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM agencies");
        $count = $stmt->fetch()['count'];
        echo "Current number of agencies: $count\n\n";
        
        if ($count > 0) {
            // Show all agencies
            $stmt = $pdo->query("SELECT AgencyID, AgencyName, AgencyType FROM agencies");
            echo "Existing agencies:\n";
            echo "ID | Name | Type\n";
            echo "---|------|-----\n";
            while ($row = $stmt->fetch()) {
                echo $row['AgencyID'] . " | " . $row['AgencyName'] . " | " . $row['AgencyType'] . "\n";
            }
        } else {
            echo "No agencies found. Running seeder would be needed.\n";
        }
    } else {
        echo "✗ Agencies table does not exist. Migration needs to be run first.\n";
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}
?>
