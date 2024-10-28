<?php
require_once 'db.php';
require_once 'notification_handler.php';

session_start(); // Start the session
$adminID = $_SESSION['adminID'] ?? null; // Get adminID from session

if (!$adminID) {
    die(json_encode(['error' => 'Admin ID not set.'])); // Ensure adminID is set
}

$db = new Database();
$conn = $db->getConnection();
$notificationHandler = new NotificationHandler($conn);

// Query to fetch data from the database
$sql = "SELECT temperature, humidity, weight FROM hive1 ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Initialize previous temperature and humidity from session, if set
    $previousTemperature = $_SESSION['previousTemperature'] ?? null;
    $previousHumidity = $_SESSION['previousHumidity'] ?? null;

    // Notification logic for temperature
    if ($row['temperature'] > 36 && ($previousTemperature === null || $row['temperature'] !== $previousTemperature)) {
        $notificationHandler->insertNotification($adminID, 'active', 'Temperature exceeds optimal range.', 'highTemp', 'parameterMonitoring.php', 'unseen');
    } elseif ($row['temperature'] < 32 && ($previousTemperature === null || $row['temperature'] !== $previousTemperature)) {
        $notificationHandler->insertNotification($adminID, 'active', 'Temperature below optimal range.', 'lowTemp', 'parameterMonitoring.php', 'unseen');
    }

    // Notification logic for humidity
    if ($row['humidity'] > 60 && ($previousHumidity === null || $row['humidity'] !== $previousHumidity)) {
        $notificationHandler->insertNotification($adminID, 'active', 'Humidity exceeds optimal range.', 'highHumid', 'parameterMonitoring.php', 'unseen');
    } elseif ($row['humidity'] < 50 && ($previousHumidity === null || $row['humidity'] !== $previousHumidity)) {
        $notificationHandler->insertNotification($adminID, 'active', 'Humidity below optimal range.', 'lowHumid', 'parameterMonitoring.php', 'unseen');
    }

    // Update session with the current values
    $_SESSION['previousTemperature'] = $row['temperature'];
    $_SESSION['previousHumidity'] = $row['humidity'];

    // Output the data in JSON format
    echo json_encode([
        'temperature' => $row['temperature'],
        'humidity' => $row['humidity'],
        'weight' => $row['weight']
    ]);
} else {
    echo json_encode([
        'temperature' => null,
        'humidity' => null,
        'weight' => null
    ]);
}

$conn->close();
?>
