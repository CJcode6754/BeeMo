<?php
session_start();
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
// Get the adminID and hiveID from the session
$adminID = 10;
$hiveID = 1;
// Query to fetch user names and their cycles
$sql = "
    SELECT 
        u.userID, u.user_name, 
        h.userCycleID, h.userCycleNumber, h.user_start_of_cycle, h.user_end_of_cycle 
    FROM 
        user_table u
    JOIN 
        user_harvest_cycle h 
    ON 
        u.userID = h.userID
    WHERE 
        h.adminID = '$adminID' AND u.adminID = '$adminID' AND h.hiveID = '$hiveID'
    ORDER BY 
        u.user_name, h.userCycleNumber";

$result = $conn->query($sql);

// Fetch data
$userCycles = [];
while ($row = $result->fetch_assoc()) {
    $userCycles[] = $row;
}

// Close the connection
$conn->close();

// Return data as JSON
echo json_encode($userCycles);
?>
