<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Log errors to a file
ini_set('log_errors', 1);
ini_set('error_log', 'php_error.log');

function season_start() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}
season_start();

try {
    // Set proper content type header
    header('Content-Type: application/json');

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "BeeMo_db";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Simple query first to test database connection
    $test_query = "SELECT 1";
    if (!$conn->query($test_query)) {
        throw new Exception("Basic query failed: " . $conn->error);
    }

    // Prepare the query to fetch harvest cycles
    $adminID = 10;
    $hiveID = 1;
    
    // Use simpler query to test first
    $sql = "SELECT * FROM harvest_cycle LIMIT 1";
    $result = $conn->query($sql);
    
    if ($result === false) {
        throw new Exception("Query failed: " . $conn->error);
    }
    
    // Check if table exists and has data
    if ($result->num_rows > 0) {
        // Now try the actual query
        $sql = "SELECT id, start_of_cycle, cycle_number, end_of_cycle FROM harvest_cycle WHERE admin_id = '$adminID' AND hiveID = '$hiveID' ORDER BY id DESC LIMIT 1";
        $result = $conn->query($sql);
        
        if ($result === false) {
            throw new Exception("Main query failed: " . $conn->error);
        }
        
        if ($row = $result->fetch_assoc()) {
            // Format the response to match what JavaScript expects
            $response = [
                'start_date' => $row['start_of_cycle'],
                'end_date' => $row['end_of_cycle'],
                'cycle_number' => $row['cycle_number']
            ];
            echo json_encode($response);
        } else {
            // If no cycles found
            echo json_encode(['start_date' => '', 'end_date' => '', 'message' => 'No matching cycles found']);
        }
    } else {
        // Table empty or doesn't exist
        echo json_encode(['start_date' => '', 'end_date' => '', 'message' => 'No harvest cycles in database']);
    }
    
    // Close connection
    $conn->close();
    
} catch (Exception $e) {
    // Log error
    error_log("getCycles.php error: " . $e->getMessage());
    
    // Return error in JSON format
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>