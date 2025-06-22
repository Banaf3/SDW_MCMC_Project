<?php
// Direct database insertion using the same data as AgencySeeder.php
$host = '127.0.0.1';
$dbname = 'mcmc';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to MySQL database successfully.\n\n";
    
    // Data from AgencySeeder.php
    $agencies = [
        [
            'AgencyName' => 'CyberSecurity Malaysia',
            'AgencyEmail' => 'contact@cybersecurity.gov.my',
            'AgencyPhoneNum' => '03-89926888',
            'AgencyType' => 'Cybersecurity'
        ],
        [
            'AgencyName' => 'Ministry of Health Malaysia (MOH)',
            'AgencyEmail' => 'webmaster@moh.gov.my',
            'AgencyPhoneNum' => '03-88834000',
            'AgencyType' => 'Health'
        ],
        [
            'AgencyName' => 'Royal Malaysia Police (PDRM)',
            'AgencyEmail' => 'webmaster@rmp.gov.my',
            'AgencyPhoneNum' => '03-21159999',
            'AgencyType' => 'Law Enforcement'
        ],
        [
            'AgencyName' => 'Ministry of Domestic Trade and Consumer Affairs (KPDN)',
            'AgencyEmail' => 'info@kpdn.gov.my',
            'AgencyPhoneNum' => '03-88825555',
            'AgencyType' => 'Trade and Consumer Affairs'
        ],
        [
            'AgencyName' => 'Ministry of Education (MOE)',
            'AgencyEmail' => 'webmoe@moe.gov.my',
            'AgencyPhoneNum' => '03-88846000',
            'AgencyType' => 'Education'
        ],
        [
            'AgencyName' => 'Ministry of Communications and Digital (KKD)',
            'AgencyEmail' => 'webmaster@kkmm.gov.my',
            'AgencyPhoneNum' => '03-88707000',
            'AgencyType' => 'Communications and Digital'
        ],
        [
            'AgencyName' => 'Department of Islamic Development Malaysia (JAKIM)',
            'AgencyEmail' => 'info@islam.gov.my',
            'AgencyPhoneNum' => '03-88925000',
            'AgencyType' => 'Religious Affairs'
        ],
        [
            'AgencyName' => 'Election Commission of Malaysia (SPR)',
            'AgencyEmail' => 'info@spr.gov.my',
            'AgencyPhoneNum' => '03-88922525',
            'AgencyType' => 'Electoral'
        ],
        [
            'AgencyName' => 'Malaysian Anti-Corruption Commission (MACC / SPRM)',
            'AgencyEmail' => 'info@sprm.gov.my',
            'AgencyPhoneNum' => '03-62062000',
            'AgencyType' => 'Anti-Corruption'
        ],
        [
            'AgencyName' => 'Department of Environment Malaysia (DOE)',
            'AgencyEmail' => 'pro@doe.gov.my',
            'AgencyPhoneNum' => '03-88712111',
            'AgencyType' => 'Environment'
        ]
    ];
    
    // Prepare INSERT statement
    $sql = "INSERT INTO agencies (AgencyName, AgencyEmail, AgencyPhoneNum, AgencyType, created_at, updated_at) 
            VALUES (?, ?, ?, ?, NOW(), NOW())";
    $stmt = $pdo->prepare($sql);
    
    $inserted = 0;
    foreach ($agencies as $agency) {
        try {
            $stmt->execute([
                $agency['AgencyName'],
                $agency['AgencyEmail'],
                $agency['AgencyPhoneNum'],
                $agency['AgencyType']
            ]);
            $inserted++;
            echo "✓ Inserted: " . $agency['AgencyName'] . "\n";
        } catch (PDOException $e) {
            echo "✗ Failed to insert " . $agency['AgencyName'] . ": " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n=== SUMMARY ===\n";
    echo "Total agencies inserted: $inserted\n";
    
    // Verify the insertion
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM agencies");
    $count = $stmt->fetch()['count'];
    echo "Total agencies in database: $count\n\n";
    
    // Show all inserted agencies
    echo "=== ALL AGENCIES ===\n";
    $stmt = $pdo->query("SELECT AgencyID, AgencyName, AgencyType FROM agencies ORDER BY AgencyID");
    while ($row = $stmt->fetch()) {
        echo sprintf("ID: %d | %s | %s\n", $row['AgencyID'], $row['AgencyName'], $row['AgencyType']);
    }
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}
?>
