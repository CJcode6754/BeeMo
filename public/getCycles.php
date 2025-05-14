<?php
// Enable error reporting and logging
ini_set('display_errors', 1);
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', 'php_error.log');

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

try {
    // Database credentials
    $dbConfig = [
        'host'     => 'localhost',
        'username' => 'root',
        'password' => '',
        'dbname'   => 'BeeMo_db'
    ];

    // Create connection
    $conn = new mysqli($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['dbname']);

    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Database connection failed: " . $conn->connect_error);
    }

    // Configuration / Query params
    $adminID = 10;
    $hiveID = 1;

    // Fetch harvest cycles for given admin and hive
    $sql = "SELECT id, start_of_cycle, cycle_number, end_of_cycle 
            FROM harvest_cycle 
            WHERE admin_id = ? AND hiveID = ? 
            ORDER BY id DESC";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Failed to prepare query: " . $conn->error);
    }

    $stmt->bind_param("ii", $adminID, $hiveID);
    $stmt->execute();

    $result = $stmt->get_result();
    $cycles = [];

    while ($row = $result->fetch_assoc()) {
        $cycles[] = $row;
    }

    if (empty($cycles)) {
        echo json_encode(['message' => 'No harvest cycles found']);
    } else {
        echo json_encode($cycles);
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    error_log("getCycles.php error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
