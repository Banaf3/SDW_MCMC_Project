<?php
// Quick test to verify password recovery routes

require_once 'vendor/autoload.php';

try {
    // Bootstrap Laravel application
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    echo "=== ROUTE VERIFICATION ===\n";
    
    // Check if all password recovery routes exist
    $routes = [
        'password.request' => '/password/forgot',
        'password.email' => '/password/email',
        'password.reset' => '/password/reset/{token}',
        'password.reset.update' => '/password/reset',
        'login' => '/login'
    ];
    
    foreach ($routes as $name => $uri) {
        try {
            $route = route($name, $name === 'password.reset' ? 'dummy-token' : []);
            echo "✅ Route '{$name}' -> {$route}\n";
        } catch (Exception $e) {
            echo "❌ Route '{$name}' -> ERROR: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n=== TESTING CREDENTIALS ===\n";
    $admin = App\Models\Administrator::where('AdminEmail', 'testadmin@admin.com')->first();
    if ($admin) {
        echo "Test Administrator:\n";
        echo "  Email: {$admin->AdminEmail}\n";
        echo "  Username: {$admin->Username}\n";
        echo "  Password: admin123\n";
    }
    
    echo "\n✅ VERIFICATION COMPLETED!\n";
    echo "You can now test at: http://localhost:8000/login\n";

} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}
?>
