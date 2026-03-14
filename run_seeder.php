<?php
require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

try {
    $kernel->call('db:seed', []);
    echo "Seed completed successfully.\n";
} catch (\Exception $e) {
    file_put_contents('seed_error.txt', $e->getMessage() . "\n" . $e->getTraceAsString());
}
