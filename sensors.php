<?php

require './src/db.php';
$db = new Database();
$conn = $db->getConnection();

// Logging function
function logMessage($message) {
    file_put_contents('log.txt', date('Y-m-d H:i:s') . " - " . $message . "\n", FILE_APPEND);
}

logMessage("Received request");

// Check connection
if (!$conn) {
    $error_message = "Connection failed: " . mysqli_connect_error();
    logMessage($error_message);
    die($error_message);
}

// If values sent by Arduino/NodeMCU are not empty then insert into MySQL database table
if (!empty($_POST['temperature']) && !empty($_POST['humidity']) && !empty($_POST['weight'])) {
    $temperature = $_POST['temperature'];
    $humidity = $_POST['humidity'];
    $weight = $_POST['weight'];

    // Log the received values
    logMessage("Received data - Temperature: $temperature, Humidity: $humidity, Weight: $weight");

    // Update your table name here
    $sql = "INSERT INTO hive1 (temperature, humidity, weight) VALUES ('$temperature', '$humidity', '$weight')";

    if ($conn->query($sql) === TRUE) {
        logMessage("Values inserted in MySQL database table.");
        echo "Values inserted in MySQL database table.";
    } else {
        $error_message = "Error: " . $sql . "<br>" . $conn->error;
        logMessage($error_message);
        echo $error_message;
    }
} else {
    logMessage("Received incomplete data");
    echo "Missing data";
}

// Close MySQL connection
$conn->close();
logMessage("Connection closed");
?>
