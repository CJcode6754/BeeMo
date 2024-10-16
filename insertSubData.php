<?php
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

// Define the hive number and admin ID
$hiveID = 1;
$adminID = 10;

// Start time (6 months ago)
$startTime = new DateTime();
$startTime->modify('-6 months');
$startTime->modify('first day of this month'); // Start at the beginning of that month

// End time (6 months in the future)
$endTime = new DateTime();
$endTime->modify('+6 months');
$endTime->modify('last day of this month'); // End at the last day of that month

$interval = new DateInterval('PT30S'); // 30-second interval
$period = new DatePeriod($startTime, $interval, $endTime); // End time as a DateTime object

// Initialize weight
$initialWeight = 1000; // Starting weight in grams
$currentWeight = $initialWeight; // Set current weight to the initial value

$weightIncrementPerDay = 30; // Increment weight by 30 grams each day if conditions are optimal

// Define temperature and humidity ranges for optimal conditions
$optimalTempRange = [32, 34];
$optimalHumidityRange = [50, 60];

foreach ($period as $dt) {
    // Generate random values for temperature and humidity
    $temperature = rand(28, 40); // Random temperature between 28 and 38
    $humidity = rand(40, 70); // Random humidity between 40 and 70

    // Check if temperature and humidity are within the optimal range
    if ($temperature >= $optimalTempRange[0] && $temperature <= $optimalTempRange[1] &&
        $humidity >= $optimalHumidityRange[0] && $humidity <= $optimalHumidityRange[1]) {
        // Increment weight based on optimal conditions
        $currentWeight += $weightIncrementPerDay / 2880; // Increment based on 30-second intervals within a day
    }

    // Format timestamp
    $timestamp = $dt->format('Y-m-d H:i:s');

    // Prepare SQL statement
    $sql = "INSERT INTO subdata (adminID, hiveID, temperature, humidity, weight, timestamp) 
            VALUES ('$adminID', '$hiveID', '$temperature', '$humidity', '$currentWeight', '$timestamp')";

    // Execute SQL statement
    if ($conn->query($sql) === TRUE) {
        // Optionally, print progress
        echo "Record inserted successfully at $timestamp\n";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>