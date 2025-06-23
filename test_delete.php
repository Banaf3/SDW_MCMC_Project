<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Get a test user
$user = App\Models\PublicUser::first();

if ($user) {
    echo "Test user found: ID={$user->UserID}, Name={$user->UserName}, Email={$user->UserEmail}\n";
    
    // Test if we can find the user
    $foundUser = App\Models\PublicUser::find($user->UserID);
    echo "User can be found: " . ($foundUser ? "YES" : "NO") . "\n";
    
    // Test delete
    echo "Attempting to delete user...\n";
    $deleted = $foundUser->delete();
    echo "Delete result: " . ($deleted ? "SUCCESS" : "FAILED") . "\n";
    
    // Check if user still exists
    $stillExists = App\Models\PublicUser::find($user->UserID);
    echo "User still exists after delete: " . ($stillExists ? "YES" : "NO") . "\n";
    
} else {
    echo "No public users found in database\n";
}
?>
