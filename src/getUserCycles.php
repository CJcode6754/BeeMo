<?php
function season_start() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}
season_start();

header('Content-Type: application/json');

// $servername = "localhost";
// $username = "u497761604_BeeMo";
// $password = "NewPassword@6789054321";
// $dbname = "u497761604_BeeMo_db";

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
$sql = "SELECT userCycleID, user_start_of_cycle, userCycleNumber, user_end_of_cycle FROM user_harvest_cycle WHERE adminID = '$adminID'";
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