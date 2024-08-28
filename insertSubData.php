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

// Start time (6 months ago)
$startTime = new DateTime();
$startTime->sub(new DateInterval('P6M')); // Subtract 6 months

$interval = new DateInterval('PT30S'); // 30 seconds
$period = new DatePeriod($startTime, $interval, 1555200); // 1555200 intervals (6 months * 30 days * 24 hours * 3600 seconds / 30 seconds)

// Initialize weight
$initialWeight = 1000; // Starting weight in grams
$weightIncrementPerDay = 15; // Increment weight by 5 grams each day

foreach ($period as $dt) {
    // Generate random values for temperature and humidity
    $temperature = rand(30, 38); // Random temperature between 30 and 38
    $humidity = rand(45, 70); // Random humidity between 45 and 70

    // Check if temperature and humidity are within the optimal range
    if ($temperature >= 32 && $temperature <= 34 && $humidity >= 50 && $humidity <= 60) {
        // Calculate the number of days since the start time
        $daysSinceStart = $startTime->diff($dt)->days;
        // Calculate weight based on the number of days
        $weight = $initialWeight + ($daysSinceStart * $weightIncrementPerDay);

        // Format timestamp
        $timestamp = $dt->format('Y-m-d H:i:s');

        // Prepare SQL statement
        $sql = "INSERT INTO subdata (temperature, humidity, weight, timestamp) VALUES ('$temperature', '$humidity', '$weight', '$timestamp')";

        // Execute SQL statement
        if ($conn->query($sql) === TRUE) {
            // Optionally, print progress
            echo "Record inserted successfully at $timestamp\n";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>
