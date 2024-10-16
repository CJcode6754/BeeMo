<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start(); // Start the session

header('Content-Type: application/json');

// Database connection setup
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'BeeMo_db';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

$data = json_decode(file_get_contents('php://input'), true);
$adminID = $_SESSION['adminID'] ?? null;  // Ensure adminID is set

if (!$adminID) {
    echo json_encode(['error' => 'Admin ID not found']);
    exit;
}

// Check for monthly data request
if (isset($data['start_date'], $data['end_date'], $data['cycle_id'])) {
    $startDateStr = $data['start_date'];
    $endDateStr = $data['end_date'];
    $cycleID = (int)$data['cycle_id']; // Get cycle ID from request

    // Validate the dates
    if (empty($startDateStr) || empty($endDateStr)) {
        echo json_encode(['error' => 'Start date and end date are required']);
        exit;
    }

    // Query the harvest_cycle table to get the cycle's start and end dates
    $cycleQuery = "SELECT start_of_cycle, end_of_cycle FROM harvest_cycle WHERE id = ?";
    $stmtCycle = $conn->prepare($cycleQuery);
    if (!$stmtCycle) {
        echo json_encode(['error' => 'SQL prepare error: ' . $conn->error]);
        exit;
    }

    $stmtCycle->bind_param("i", $cycleID);
    if (!$stmtCycle->execute()) {
        echo json_encode(['error' => 'SQL execute error: ' . $stmtCycle->error]);
        exit;
    }

    $cycleResult = $stmtCycle->get_result();
    if ($cycleResult->num_rows === 0) {
        echo json_encode(['error' => 'Invalid cycle ID']);
        exit;
    }

    // Fetch cycle dates
    $cycle = $cycleResult->fetch_assoc();
    $cycleStartDate = new DateTime($cycle['start_of_cycle']);
    $cycleEndDate = new DateTime($cycle['end_of_cycle']);
    $cycleEndDate->setTime(23, 59, 59); // End of the cycle day

    // Convert frontend start and end dates to DateTime objects
    $startDate = new DateTime($startDateStr);
    $endDate = new DateTime($endDateStr);
    $endDate->setTime(23, 59, 59); // End of the day

    // Adjust dates to ensure they fall within the cycle boundaries
    if ($startDate < $cycleStartDate) {
        $startDate = $cycleStartDate; // Ensure start date is not before cycle start
    }
    if ($endDate > $cycleEndDate) {
        $endDate = $cycleEndDate; // Ensure end date is not after cycle end
    }

    // Prepare SQL query for monthly data
    $sql = "
        SELECT
            DATE_FORMAT(timestamp, '%Y-%m-%d') AS period,
            AVG(temperature) AS avg_temperature,
            AVG(humidity) AS avg_humidity,
            AVG(weight) AS avg_weight,
            adminID
        FROM subdata
        WHERE timestamp >= ? AND timestamp <= ? AND adminID = ?
        GROUP BY period
        ORDER BY period
    ";

    // Prepare the SQL statement
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(['error' => 'SQL prepare error: ' . $conn->error]);
        exit;
    }

    // Format dates for binding
    $startDateFormatted = $startDate->format('Y-m-d H:i:s');
    $endDateFormatted = $endDate->format('Y-m-d H:i:s');

    // Ensure adminID is a valid integer
    $adminID = (int)$adminID;

    // Bind the parameters
    $stmt->bind_param("ssi", $startDateFormatted, $endDateFormatted, $adminID);

    // Execute the query and check for errors
    if (!$stmt->execute()) {
        echo json_encode(['error' => 'SQL execute error: ' . $stmt->error]);
        exit;
    }

    $result = $stmt->get_result();
    $data = [];
    $temperature = [];
    $humidity = [];
    $weight = [];

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
        $temperature[] = $row['avg_temperature'];
        $humidity[] = $row['avg_humidity'];
        $weight[] = $row['avg_weight'];
    }

    // Calculate statistics
    $stats = [
        'temperature' => [
            'average' => count($temperature) ? array_sum($temperature) / count($temperature) : null,
            'min' => count($temperature) ? min($temperature) : null,
            'max' => count($temperature) ? max($temperature) : null
        ],
        'humidity' => [
            'average' => count($humidity) ? array_sum($humidity) / count($humidity) : null,
            'min' => count($humidity) ? min($humidity) : null,
            'max' => count($humidity) ? max($humidity) : null
        ],
        'weight' => [
            'average' => count($weight) ? array_sum($weight) / count($weight) : null,
            'min' => count($weight) ? min($weight) : null,
            'max' => count($weight) ? max($weight) : null
        ]
    ];

    // Return data as JSON
    echo json_encode(['data' => $data, 'stats' => $stats]);

} else {
    // Default case for daily data
    if (!isset($data['selected_date'])) {
        echo json_encode(['error' => 'Selected date is required']);
        exit;
    }

    $selectedDate = $data['selected_date'];

    // Prepare SQL for daily data
    $sql = "
        SELECT
            DATE_FORMAT(timestamp, '%Y-%m-%d %H:00:00') AS hour,
            AVG(temperature) AS avg_temperature,
            AVG(humidity) AS avg_humidity,
            AVG(weight) AS avg_weight,
            adminID
        FROM subdata
        WHERE DATE(timestamp) = ? AND adminID = ?
        GROUP BY hour
        ORDER BY hour
    ";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(['error' => 'SQL prepare error: ' . $conn->error]);
        exit;
    }

    // Ensure adminID is a valid integer
    $adminID = (int)$adminID; // Cast adminID to int

    // Bind the parameters
    $stmt->bind_param("si", $selectedDate, $adminID);

    // Execute the query and check for errors
    if (!$stmt->execute()) {
        echo json_encode(['error' => 'Query execution failed: ' . $stmt->error]);
        exit;
    }

    $result = $stmt->get_result();
    $data = [];
    $temperature = [];
    $humidity = [];
    $weight = [];

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
        $temperature[] = $row['avg_temperature'];
        $humidity[] = $row['avg_humidity'];
        $weight[] = $row['avg_weight'];
    }

    // Calculate statistics
    $stats = [
        'temperature' => [
            'average' => count($temperature) ? array_sum($temperature) / count($temperature) : null,
            'min' => count($temperature) ? min($temperature) : null,
            'max' => count($temperature) ? max($temperature) : null
        ],
        'humidity' => [
            'average' => count($humidity) ? array_sum($humidity) / count($humidity) : null,
            'min' => count($humidity) ? min($humidity) : null,
            'max' => count($humidity) ? max($humidity) : null
        ],
        'weight' => [
            'average' => count($weight) ? array_sum($weight) / count($weight) : null,
            'min' => count($weight) ? min($weight) : null,
            'max' => count($weight) ? max($weight) : null
        ]
    ];

    // Close statement
    $stmt->close();

    // Return data and stats as JSON
    echo json_encode(['data' => $data, 'stats' => $stats]);
}

// Close the database connection
$conn->close();
?>
