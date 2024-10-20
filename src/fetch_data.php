<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

// Database connection settings
$servername = "localhost";
$username = "u497761604_BeeMo";
$password = "NewPassword@6789054321";
$dbname = "u497761604_BeeMo_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo "data: Connection failed: " . $conn->connect_error . "\n\n";
    exit();
}

while (true) {
    // Query to fetch data from the database
    $sql = "SELECT temperature, humidity, weight FROM hive1 ORDER BY id DESC LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo "data: " . json_encode([
            'temperature' => $row['temperature'],
            'humidity' => $row['humidity'],
            'weight' => $row['weight']
        ]) . "\n\n";
        ob_flush(); // Flush the output buffer
        flush(); // Send the output to the client
    }

    // Wait before checking for new data again (e.g., 5 seconds)
    sleep(5);
}

$conn->close();
?>
