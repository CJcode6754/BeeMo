<?php

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "BeeMo_db";

try {
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    echo "Connected successfully\n";

    // Create table SQL
    $sql = "CREATE TABLE IF NOT EXISTS aggregated_hive_data (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        adminID INT NOT NULL,
        hiveID INT NOT NULL,
        temperature DECIMAL(5,2),
        humidity DECIMAL(5,2),
        weight DECIMAL(7,2),
        timestamp DATETIME NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_admin_hive (adminID, hiveID),
        INDEX idx_timestamp (timestamp),
        INDEX idx_timestamp_admin_hive (timestamp, adminID, hiveID)
    ) ENGINE=InnoDB";

    // Execute the SQL
    if ($conn->query($sql) === TRUE) {
        echo "Table aggregated_hive_data created successfully\n";
    } else {
        throw new Exception("Error creating table: " . $conn->error);
    }

    // Clear existing data if any
    $conn->query("TRUNCATE TABLE aggregated_hive_data");
    echo "Table cleared of any existing data\n";

    $conn->close();
    echo "Done.\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    if (isset($conn)) {
        $conn->close();
    }
    exit(1);
}
