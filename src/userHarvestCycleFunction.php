<?php
    require_once './src/db.php';
    require_once './src/user_notification_handler.php';
    
    class UserHarvestCycle {
        private $conn;
        private $notification;
    
        public function __construct($conn) {
            $this->conn = $conn;  // Directly use the mysqli connection
            $this->notification = new UserNotificationHandler($this->conn);
        }
    
        public function insertCycle($start_date, $end_date) {
            $userID = $_SESSION['userID']; // Retrieve the session admin ID
        
            $honey_kg = 0;
            $status = 0;
            
            $getAdminID = ("SELECT adminID FROM user_table WHERE userID = $userID");
            $result = mysqli_query($this->conn, $getAdminID);
            if($row = mysqli_fetch_assoc($result)){
                $adminID = $row['adminID'];
            }else {
                // Handle error if adminID is not found
                $this->notification->insertNotification($userID, 'active', 'Failed to fetch admin ID.', 'failed_to_fetch_admin_id', 'harvestCycle.php', 'unseen');
                return; // Exit if adminID is not fetched
            }

            // Fetch the next cycle number for this admin
            $nextCycleNumber = 1; // Default to 1 if no cycles exist
            $query_next_cycle = "SELECT MAX(userCycleNumber) AS max_cycle_num FROM user_harvest_cycle WHERE userID = '$userID'";
            $result_next_cycle = mysqli_query($this->conn, $query_next_cycle);
            if ($row = mysqli_fetch_assoc($result_next_cycle)) {
                $nextCycleNumber = $row['max_cycle_num'] ? $row['max_cycle_num'] + 1 : 1;
            }
        
            // Parse and validate the start and end dates
            $start_of_cycle = DateTime::createFromFormat('Y-m-d', $start_date);
            $end_of_cycle = DateTime::createFromFormat('Y-m-d', $end_date);
        
            // Check if the date parsing was successful
            if (!$start_of_cycle || !$end_of_cycle) {
                $this->notification->insertNotification($userID, 'active', 'Invalid date format.', 'invalid_date_format', 'harvestCycle.php', 'unseen');
                return; // Exit the function if date parsing fails
            }
        
            // Format the dates for the database
            $start_of_cycle = $start_of_cycle->format('Y-m-d');
            $end_of_cycle = $end_of_cycle->format('Y-m-d');
        
            // Insert the new cycle into the database
            $add_cycle = "INSERT INTO user_harvest_cycle (userID, adminID, userCycleNumber, user_start_of_cycle, user_end_of_cycle, honey_kg, status)
                          VALUES ('$userID', '$adminID', '$nextCycleNumber', '$start_of_cycle', '$end_of_cycle', '$honey_kg', '$status')";
        
            $add_cycle_query = mysqli_query($this->conn, $add_cycle);
        
            // Notify the admin based on the result of the insert operation
            if ($add_cycle_query) {
                $this->notification->insertNotification($userID, 'active', 'Successfully added new cycle.', 'user_new_cycle', 'harvestCycle.php', 'unseen');
            } else {
                $this->notification->insertNotification($userID, 'active', 'Failed to add new cycle.', 'user_failed_to_add_cycle', 'harvestCycle.php', 'unseen');
            }
        }
    }
?>