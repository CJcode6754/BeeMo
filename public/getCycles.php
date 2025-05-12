<?php
function season_start() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}
season_start();

header('Content-Type: application/json');

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

// Prepare the query to fetch harvest cycles
$adminID = 10;
$hiveID = 1;
$sql = "SELECT id, start_of_cycle, cycle_number, end_of_cycle FROM harvest_cycle WHERE adminID = '$adminID' AND hiveID = '$hiveID'";
$result = $conn->query($sql);
// Fetch data
$harvestCycles = [];
while ($row = $result->fetch_assoc()) {
    $harvestCycles[] = $row;
}

// Close connection
$conn->close();

// Return data as JSON
echo json_encode($harvestCycles);
?>
