<?php
// Simple PHP test file to check if basic PHP is working
echo "PHP is working on Apache!<br>";
echo "Current directory: " . __DIR__ . "<br>";
echo "Laravel Public Directory: " . realpath(__DIR__) . "<br>";

// Test if Laravel can be loaded
try {
    require_once '../vendor/autoload.php';
    echo "Laravel autoloader loaded successfully!<br>";
} catch (Exception $e) {
    echo "Error loading Laravel: " . $e->getMessage() . "<br>";
}
?>
