<?php
session_start();

// Database class
class Database {
    private $host = 'localhost';
    private $username = 'root';
    private $password = '';
    private $dbname = 'BeeMo_db';
    private $conn;

    public function __construct() {
        $this->connect();
    }

    private function connect() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->dbname);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function getConnection() {
        return $this->conn;
    }

    public function closeConnection() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}

// Connect to DB
$db = new Database();
$conn = $db->getConnection();

// Make sure admin is logged in
// if (!isset($_SESSION['adminID'])) {
//     header('Location: index.php');
//     exit;
// }

$adminID = $_SESSION['user']['id'];

// Handle POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'fetch') {
        $query = "SELECT * FROM tblNotification WHERE adminID = '$adminID' ORDER BY noti_date DESC";
        $result = mysqli_query($conn, $query);

        $notifications = [];
        $total_unseen = 0;

        $messages = [
            'add_user' => 'User was added successfully.',
            'delete_user' => 'User was deleted successfully.',
            'failed_to_delete_user' => 'Failed to delete user.',
            'edit_user' => 'User was updated successfully.',
            'new_cycle' => 'Successfully added new cycle.',
            'failed_to_add_cycle' => 'Failed to add new cycle.',
            'delete_cycle' => 'Cycle deleted successfully.',
            'failed_to_delete_cycle' => 'Failed to delete cycle.',
            'edit_cycle_success' => 'Cycle edited successfully.',
            'edit_cycle_failed' => 'Failed to edit cycle info.',
            'notMatchPass' => 'Password does not match.',
            'newPass'=> 'Password changed successfully.',
            'errorPass'=> 'Error updating!',
            'error'=> 'Error fetching!',
            'profileUpdate'=> 'Profile updated successfully!',
            'errorProfile'=> 'Error updating profile!',
            'emailVerification'=> 'Email verified successfully!',
            'Failed_to_add_user'=> 'Failed to send Otp.',
            'addUser'=> 'New User Added!',
            'emailExist'=> 'Email Already Exist!',
            'email_verification'=> 'Verify email with OTP sent.',
            'email_verified'=> 'Email verified successfully!',
            'emptyHiveNum'=> 'Hive not recorded.',
            'user_new_cycle'=> 'Cycle added successfully.',
            'user_failed_to_add_cycle'=> 'Failed to add new cycle.',
            'highTemp'=> 'Temperature exceeds optimal range.',
            'lowTemp'=> 'Temperature below optimal range.',
            'highHumid'=> 'Humidity exceeds optimal range.',
            'lowHumid'=> 'Humidity below optimal range.',
            'hiveDelete'=> 'Hive deleted successfully.',
            'hiveDeletePartial'=> 'Hive deleted, but failed to drop its table.',
            'hiveDeleteError'=> 'Error preparing to drop the table.',
            'hiveDeleteFailure'=> 'Failed to delete hive entry.',
            'complete_cycle'=> 'A cycle has been completed.',
            'three_days_away'=> 'There is a cycle that will be completed in 3 days.',
            'wifi_connected'=> 'Device is connected to Wi-Fi.',
            'dht22_error'=> 'DHT22 sensor is not working.',
            'load_error'=> 'Load cell is not working.',
            'not_connected'=> 'Device is not connected to Wi-Fi.',
            'harvestReady' => 'Hive is in recommended weight range for harvest.',
            'otpfailed'=> 'Failed to send OTP. Please try again.',
            'hiveNotFound' => 'Hive not found or does not belong to you.',
            'FailedDelete' => 'Invalid OTP. Hive deletion cancelled.'
        ];

        while ($row = mysqli_fetch_assoc($result)) {
            if ($row['noti_seen'] === 'unseen') {
                $total_unseen++;
            }

            $row['message'] = $messages[$row['noti_type']] ?? 'Notification';
            $notifications[] = $row;
        }

        array_unshift($notifications, ['total' => $total_unseen]);

        echo json_encode($notifications);
    } elseif ($action === 'seen') {
        $updateQuery = "UPDATE tblNotification SET noti_seen = 'seen' WHERE adminID = '$adminID' AND noti_seen = 'unseen'";
        mysqli_query($conn, $updateQuery);

        echo json_encode(['status' => 'success']);
    }
}
?>
