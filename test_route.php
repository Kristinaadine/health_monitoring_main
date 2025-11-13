<?php
// Quick test for locale_route
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Test locale_route
try {
    $testId = encrypt(1);
    $url = locale_route('growth-monitoring.show', $testId);
    echo "Success: " . $url . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
