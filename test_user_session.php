<?php
// Simple test to check user session and inquiries

// Start session
session_start();

// Simulate a logged-in user (for testing)
$_SESSION['user_id'] = 1;
$_SESSION['user_type'] = 'public';
$_SESSION['user_name'] = 'Test User';
$_SESSION['user_email'] = 'test@example.com';

echo "Session data set:\n";
echo "User ID: " . $_SESSION['user_id'] . "\n";
echo "User Type: " . $_SESSION['user_type'] . "\n";
echo "User Name: " . $_SESSION['user_name'] . "\n";
echo "User Email: " . $_SESSION['user_email'] . "\n";

echo "\nYou can now test the inquiry system with user ID: " . $_SESSION['user_id'];
echo "\nGo to: http://localhost:8000/inquiries\n";
?>
