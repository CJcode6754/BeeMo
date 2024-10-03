<?php
class NotificationHandler
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function insertNotification($adminID, $status, $message, $type, $url, $seen)
    {
        $noti_user_uniqueID = uniqid();
        $noti_uniqueid = uniqid();
        $date = date('Y-m-d H:i:s');

        $query = "INSERT INTO tblNotification (adminID, noti_user_uniqueID, noti_status, noti_message, noti_date, noti_type, noti_url, noti_uniqueid, noti_seen) 
                  VALUES ('$adminID', '$noti_user_uniqueID', '$status', '$message', '$date', '$type', '$url', '$noti_uniqueid', '$seen')";
        mysqli_query($this->conn, $query);
    }
}
?>
