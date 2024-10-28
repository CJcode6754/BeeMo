<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start(); // Start the session

header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "BeeMo_db";

// $servername = "localhost";
// $username = "u497761604_BeeMo";
// $password = "NewPassword@6789054321";
// $dbname = "u497761604_BeeMo_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['error' => 'Connection failed: ' . $conn->connect_error]);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$adminID = $_SESSION['adminID'] ?? null;  // Ensure adminID is set
$hiveID = $_SESSION['hiveID'] ?? null; // Get hiveID from the session

if (!$adminID) {
    echo json_encode(['error' => 'Admin ID not found']);
    exit;
}

if (!$hiveID) {
    echo json_encode(['error' => 'Hive ID is required']);
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

    // Prepare SQL query for monthly data including hiveID
    $sql = "
        SELECT
            DATE_FORMAT(timestamp, '%Y-%m-%d') AS period,
            AVG(temperature) AS avg_temperature,
            AVG(humidity) AS avg_humidity,
            AVG(weight) AS avg_weight,
            adminID
        FROM subdata
        WHERE timestamp >= ? AND timestamp <= ? AND adminID = ? AND hiveID = ?
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

    // Ensure adminID and hiveID are valid integers
    $adminID = (int)$adminID;
    $hiveID = (int)$hiveID;

    // Bind the parameters
    $stmt->bind_param("ssii", $startDateFormatted, $endDateFormatted, $adminID, $hiveID);

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
            'max' => count($weight) ? max($weight) : null,
            'previous' => null,  // Placeholder for previous weight
            'gain' => null       // Placeholder for weight gain
        ]
    ];

    // Get previous weight and calculate weight gain
    $previousWeightSql = "
        SELECT MAX(weight) AS previous_max_weight
        FROM subdata
        WHERE timestamp < ? AND adminID = ? AND hiveID = ?
        GROUP BY DATE(timestamp)
        ORDER BY DATE(timestamp) DESC
        LIMIT 1
    ";

    // Prepare and execute the previous weight query
    $previousWeightStmt = $conn->prepare($previousWeightSql);
    if (!$previousWeightStmt) {
        echo json_encode(['error' => 'SQL prepare error: ' . $conn->error]);
        exit;
    }

    $previousWeightStmt->bind_param("sii", $startDateFormatted, $adminID, $hiveID);

    // Execute the query and check for errors
    if (!$previousWeightStmt->execute()) {
        echo json_encode(['error' => 'SQL execute error: ' . $previousWeightStmt->error]);
        exit;
    }

    $previousWeightResult = $previousWeightStmt->get_result();
    $previousWeight = $previousWeightResult->fetch_assoc();
    $previousMaxWeight = $previousWeight['previous_max_weight'] ?? null;

    // Update stats with previous weight and calculate weight gain
    $currentMaxWeight = $stats['weight']['max'] ?? 0;
    $weightGain = $currentMaxWeight - ($previousMaxWeight ?? 0);
    $stats['weight']['previous'] = $previousMaxWeight;
    $stats['weight']['gain'] = $weightGain;

    // Return data as JSON
    echo json_encode([
        'data' => $data,
        'stats' => $stats
    ]);

} else {
    // Default case for daily data
    if (!isset($data['selected_date'])) {
        echo json_encode(['error' => 'Selected date is required']);
        exit;
    }

    $selectedDate = $data['selected_date'];

    // Prepare SQL for daily data including hiveID
    $sql = "
        SELECT
            DATE_FORMAT(timestamp, '%Y-%m-%d %H:00:00') AS hour,
            AVG(temperature) AS avg_temperature,
            AVG(humidity) AS avg_humidity,
            AVG(weight) AS avg_weight,
            adminID
        FROM subdata
        WHERE DATE(timestamp) = ? AND adminID = ? AND hiveID = ?
        GROUP BY hour
        ORDER BY hour
    ";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(['error' => 'SQL prepare error: ' . $conn->error]);
        exit;
    }

    // Ensure adminID and hiveID are valid integers
    $adminID = (int)$adminID; // Cast adminID to int
    $hiveID = (int)$hiveID; // Cast hiveID to int

    // Bind the parameters
    $stmt->bind_param("sii", $selectedDate, $adminID, $hiveID);

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
            'max' => count($weight) ? max($weight) : null,
            'previous' => null,  // Placeholder for previous weight
            'gain' => null       // Placeholder for weight gain
        ]
    ];

    // Get previous weight and calculate weight gain
    $previousWeightSql = "
        SELECT MAX(weight) AS previous_max_weight
        FROM subdata
        WHERE timestamp < ? AND adminID = ? AND hiveID = ?
        GROUP BY DATE(timestamp)
        ORDER BY DATE(timestamp) DESC
        LIMIT 1
    ";

    // Prepare and execute the previous weight query
    $previousWeightStmt = $conn->prepare($previousWeightSql);
    if (!$previousWeightStmt) {
        echo json_encode(['error' => 'SQL prepare error: ' . $conn->error]);
        exit;
    }

    $previousWeightStmt->bind_param("sii", $selectedDate, $adminID, $hiveID);

    // Execute the query and check for errors
    if (!$previousWeightStmt->execute()) {
        echo json_encode(['error' => 'SQL execute error: ' . $previousWeightStmt->error]);
        exit;
    }

    $previousWeightResult = $previousWeightStmt->get_result();
    $previousWeight = $previousWeightResult->fetch_assoc();
    $previousMaxWeight = $previousWeight['previous_max_weight'] ?? null;

    // Update stats with previous weight and calculate weight gain
    $currentMaxWeight = $stats['weight']['max'] ?? 0;
    $weightGain = $currentMaxWeight - ($previousMaxWeight ?? 0);
    $stats['weight']['previous'] = $previousMaxWeight;
    $stats['weight']['gain'] = $weightGain;

    // Return data as JSON
    echo json_encode([
        'data' => $data,
        'stats' => $stats
    ]);
}

$conn->close(); // Close the database connection
?>
