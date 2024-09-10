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

// Define a valid cycle_number (replace with actual cycle_number as needed)
$cycleNumber = 1; // Example: replace with an actual cycle_number that exists in your harvest_cycle table

// Start time (6 months ago)
$startTime = new DateTime();
$startTime->sub(new DateInterval('P6M')); // Subtract 6 months

// End time (1 year in the future)
$endTime = new DateTime();
$endTime->add(new DateInterval('P1Y')); // Add 1 year

$interval = new DateInterval('PT30S'); // 30 seconds
$period = new DatePeriod($startTime, $interval, $endTime); // End time as a DateTime object

// Initialize weight
$initialWeight = 1000; // Starting weight in grams
$currentWeight = $initialWeight; // Set current weight to the initial value

$weightIncrementPerDay = 15; // Increment weight by 15 grams each day if conditions are optimal

// Define temperature and humidity ranges for optimal conditions
$optimalTempRange = [32, 34];
$optimalHumidityRange = [50, 60];

foreach ($period as $dt) {
    // Generate random values for temperature and humidity
    $temperature = rand(28, 40); // Random temperature between 28 and 40
    $humidity = rand(40, 85); // Random humidity between 40 and 85

    // Check if temperature and humidity are within the optimal range
    if ($temperature >= $optimalTempRange[0] && $temperature <= $optimalTempRange[1] &&
        $humidity >= $optimalHumidityRange[0] && $humidity <= $optimalHumidityRange[1]) {
        // Increment weight based on optimal conditions
        $currentWeight += $weightIncrementPerDay / 2880; // Increment based on 30-second intervals within a day
    }

    // Format timestamp
    $timestamp = $dt->format('Y-m-d H:i:s');

    // Prepare SQL statement
    $sql = "INSERT INTO subdata (cycle_number, temperature, humidity, weight, timestamp) 
            VALUES ('$cycleNumber', '$temperature', '$humidity', '$currentWeight', '$timestamp')";

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
