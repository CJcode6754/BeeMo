<?php
function season_start() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}
season_start();

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

// Prepare the query to fetch harvest cycles
$adminID = $_SESSION['adminID'];
$sql = "SELECT id, start_of_cycle, end_of_cycle FROM harvest_cycle WHERE adminID = '$adminID'";
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
