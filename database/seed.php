<?php

// Set maximum execution time to 30 minutes
ini_set('max_execution_time', 1800);
// Set memory limit to 512MB
ini_set('memory_limit', '512M');

require_once __DIR__ . '/seeders/HiveDataSeeder.php';

echo "Starting data seeding process...\n";
echo "This will generate hourly data from January 2024 to September 2025\n";

$seeder = new HiveDataSeeder();
$seeder->run();
