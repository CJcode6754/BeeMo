<?php
header('Content-Type: application/json');

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "BeeMo_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the selected date from POST request
$data = json_decode(file_get_contents('php://input'), true);
$selectedDate = $data['selected_date'];

// Prepare the query to fetch data for the selected date
$sql = "
    SELECT
        DATE_FORMAT(timestamp, '%Y-%m-%d %H:00:00') AS hour,
        AVG(temperature) AS avg_temperature,
        AVG(humidity) AS avg_humidity,
        AVG(weight) AS avg_weight
    FROM subdata
    WHERE DATE(timestamp) = ?
    GROUP BY hour
    ORDER BY hour
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $selectedDate);
$stmt->execute();
$result = $stmt->get_result();

// Initialize arrays for data and statistics
$data = [];
$temperature = [];
$humidity = [];
$weight = [];

// Fetch the data
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
    $temperature[] = $row['avg_temperature'];
    $humidity[] = $row['avg_humidity'];
    $weight[] = $row['avg_weight'];
}

// Calculate descriptive statistics
$stats = [
    'temperature' => [
        'average' => count($temperature) ? array_sum($temperature) / count($temperature) : 0,
        'min' => count($temperature) ? min($temperature) : 0,
        'max' => count($temperature) ? max($temperature) : 0
    ],
    'humidity' => [
        'average' => count($humidity) ? array_sum($humidity) / count($humidity) : 0,
        'min' => count($humidity) ? min($humidity) : 0,
        'max' => count($humidity) ? max($humidity) : 0
    ],
    'weight' => [
        'average' => count($weight) ? array_sum($weight) / count($weight) : 0,
        'min' => count($weight) ? min($weight) : 0,
        'max' => count($weight) ? max($weight) : 0
    ]
];

// Close connections
$stmt->close();
$conn->close();

// Return data and statistics as JSON
echo json_encode(['data' => $data, 'stats' => $stats]);
?>
