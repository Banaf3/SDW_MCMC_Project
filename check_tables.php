<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=mcmc', 'root', '');
    $tables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
    
    echo "Tables in mcmc database:\n";
    foreach($tables as $table) {
        echo "- $table\n";
    }
    
    // Check if sessions table exists
    if (in_array('sessions', $tables)) {
        echo "\n✓ Sessions table exists\n";
    } else {
        echo "\n✗ Sessions table does NOT exist\n";
        echo "Creating sessions table...\n";
        
        $sql = "CREATE TABLE sessions (
            id varchar(40) NOT NULL,
            ip_address varchar(45) DEFAULT NULL,
            user_agent text,
            payload text NOT NULL,
            last_activity int NOT NULL,
            user_id bigint unsigned DEFAULT NULL,
            PRIMARY KEY (id),
            KEY sessions_last_activity_index (last_activity),
            KEY sessions_user_id_index (user_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $pdo->exec($sql);
        echo "✓ Sessions table created successfully\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
