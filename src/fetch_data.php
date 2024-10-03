<?php
header('Content-Type: application/json');

// Error reporting to ensure any issues are visible
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection settings
$servername = "localhost";
$username = "u497761604_BeeMo";
$password = "NewPassword@6789054321";
$dbname = "u497761604_BeeMo_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(['error' => 'Connection failed: ' . $conn->connect_error]);
    exit();
}

// Query to fetch data from the database
$sql = "SELECT temperature, humidity, weight FROM your_table ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode([
        'temperature' => $row['temperature'],
        'humidity' => $row['humidity'],
        'weight' => $row['weight']
    ]);
} else {
    echo json_encode(['error' => 'No data found']);
}

$conn->close();
?>
