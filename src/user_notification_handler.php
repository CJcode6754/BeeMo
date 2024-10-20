<?php
class UserNotificationHandler
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function insertNotification($userID, $status, $message, $type, $url, $seen)
    {
        $noti_user_uniqueID = uniqid();
        $noti_uniqueid = uniqid();
        $date = date('Y-m-d H:i:s');

        $query = "INSERT INTO user_tbl_notification (userID, noti_user_uniqueID, noti_status, noti_message, noti_date, noti_type, noti_url, noti_uniqueid, noti_seen) 
                  VALUES ('$userID', '$noti_user_uniqueID', '$status', '$message', '$date', '$type', '$url', '$noti_uniqueid', '$seen')";
        mysqli_query($this->conn, $query);
    }
}
?>
