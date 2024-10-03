<?php
function season_start() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}
season_start();

header('Content-Type: application/json');

// Database connection
// $servername = "localhost";
// $username = "u497761604_BeeMo";
// $password = "NewPassword@6789054321";
// $dbname = "u497761604_BeeMo_db";

    $servername = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = 'BeeMo_db';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

$data = json_decode(file_get_contents('php://input'), true);
$adminID = $_SESSION['adminID'];

if (isset($data['month_ago'])) {
    $monthAgo = (int)$data['month_ago']; // Get the number of months ago
    $endDate = new DateTime(); // Current date
    $endDate->setTime(23, 59, 59); // End of the current day

    // Calculate start date
    $startDate = new DateTime();
    $startDate->modify("-$monthAgo months");

    if ($monthAgo === 1) {
        // For 1 month filter, get data daily
        $startDate->modify('first day of this month'); // Start from the first day of the month N months ago
        $groupBy = "DATE_FORMAT(timestamp, '%Y-%m-%d')"; // Group by day
    } else {
        // For 3 and 6 months filter, get data weekly
        $startDate->modify('first day of this month'); // Start from the first day of the month N months ago
        $groupBy = "YEARWEEK(timestamp, 1)"; // Group by week (ISO week)
    }

    // Prepare SQL query
    $sql = "
        SELECT
            $groupBy AS period,
            AVG(temperature) AS avg_temperature,
            AVG(humidity) AS avg_humidity,
            AVG(weight) AS avg_weight,
            adminID
        FROM subdata
        WHERE timestamp >= ? AND timestamp <= ? AND adminID = ?
        GROUP BY period
        ORDER BY period
    ";

    // Bind the calculated start and end dates to the query
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $startDate->format('Y-m-d H:i:s'), $endDate->format('Y-m-d H:i:s'), $adminID);
} else {
    $selectedDate = $data['selected_date'];

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
    $stmt->bind_param("si", $selectedDate, $adminID);
}

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

$stmt->close();
$conn->close();

echo json_encode(['data' => $data, 'stats' => $stats]);
?>
