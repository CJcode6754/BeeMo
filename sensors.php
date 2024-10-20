<?php

// Database connection details
$servername = 'localhost';
$username = 'u497761604_BeeMo';
$password = 'NewPassword@6789054321';
$dbname = 'u497761604_BeeMo_db';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Logging function
function logMessage($message) {
    file_put_contents('log.txt', date('Y-m-d H:i:s') . " - " . $message . "\n", FILE_APPEND);
}

logMessage("Received request");

// Check connection
if ($conn->connect_error) {
    $error_message = "Connection failed: " . $conn->connect_error;
    logMessage($error_message);
    die($error_message);
} else {
    logMessage("Database connection successful.");
}

// Log raw POST data to see what's being sent
logMessage("Raw POST Data: " . file_get_contents('php://input'));

// Check if POST data is received
if (!empty($_POST['temperature']) && !empty($_POST['humidity']) && !empty($_POST['weight'])) {
    $temperature = $conn->real_escape_string($_POST['temperature']);
    $humidity = $conn->real_escape_string($_POST['humidity']);
    $weight = $conn->real_escape_string($_POST['weight']);

    // Log the received values
    logMessage("Received data - Temperature: $temperature, Humidity: $humidity, Weight: $weight");

    // Insert query to update your table
    $sql = "INSERT INTO hive1 (temperature, humidity, weight) VALUES ('$temperature', '$humidity', '$weight')";

    if ($conn->query($sql) === TRUE) {
        logMessage("Values inserted into MySQL database table.");
        echo "Values inserted into MySQL database table.";
    } else {
        $error_message = "Error: " . $sql . "<br>" . $conn->error;
        logMessage("MySQL Error: " . $conn->error);
        echo $error_message;
    }
} else {
    logMessage("POST parameters missing or incomplete.");
    echo "Missing data";
}

// Close MySQL connection
$conn->close();
logMessage("Connection closed");

?>
