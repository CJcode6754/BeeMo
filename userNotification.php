<?php
session_start();
require './src/db.php';
$db = new Database();
$conn = $db->getConnection();

if (!isset($_SESSION['userID'])) {
    header('Location: /'); // Redirect if not logged in
    exit;
}

$userID = $_SESSION['userID'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    if ($action == 'fetch') {
        // Fetch notifications
        $query = "SELECT * FROM user_tbl_notification WHERE userID = '$userID' ORDER BY noti_date DESC";
        $result = mysqli_query($conn, $query);

        $notifications = [];
        $total_unseen = 0;

        while ($row = mysqli_fetch_assoc($result)) {
            if ($row['noti_seen'] === 'unseen') {
                $total_unseen++;
            }
            $notifications[] = $row;
        }

        // Add the total count as the first element
        array_unshift($notifications, ['total' => $total_unseen]);

        echo json_encode($notifications);
    } elseif ($action == 'seen') {
        // Mark all notifications as seen
        $updateQuery = "UPDATE user_tbl_notification SET noti_seen = 'seen' WHERE userID = '$userID' AND noti_seen = 'unseen'";
        mysqli_query($conn, $updateQuery);

        echo json_encode(['status' => 'success']);
    }
}
?>
