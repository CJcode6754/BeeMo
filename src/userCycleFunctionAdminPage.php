<?php
require_once './src/db.php';
require_once './src/notification_handler.php';

class AdminPageHarvestCycle {
    private $conn;
    private $notification;

    public function __construct($conn) {
        $this->conn = $conn;  // Directly use the mysqli connection
        $this->notification = new NotificationHandler($this->conn);
    }

    public function insertCycle($start_date, $end_date) {
        $adminID = $_SESSION['adminID']; // Retrieve the session admin ID
    
        $honey_kg = 0;
        $status = 0;
    
        // Fetch the next cycle number for this admin
        $nextCycleNumber = 1; // Default to 1 if no cycles exist
        $query_next_cycle = "SELECT MAX(userCycleNumber) AS max_cycle_num FROM user_harvest_cycle WHERE adminID = '$adminID'";
        $result_next_cycle = mysqli_query($this->conn, $query_next_cycle);
        if ($row = mysqli_fetch_assoc($result_next_cycle)) {
            $nextCycleNumber = $row['max_cycle_num'] ? $row['max_cycle_num'] + 1 : 1;
        }
    
        // Parse and validate the start and end dates
        $start_of_cycle = DateTime::createFromFormat('Y-m-d', $start_date);
        $end_of_cycle = DateTime::createFromFormat('Y-m-d', $end_date);
    
        // Check if the date parsing was successful
        if (!$start_of_cycle || !$end_of_cycle) {
            $this->notification->insertNotification($adminID, 'active', 'Invalid date format.', 'invalid_date_format', 'harvestCycle.php', 'unseen');
            return; // Exit the function if date parsing fails
        }
    
        // Format the dates for the database
        $start_of_cycle = $start_of_cycle->format('Y-m-d');
        $end_of_cycle = $end_of_cycle->format('Y-m-d');
    
        // Insert the new cycle into the database
        $add_cycle = "INSERT INTO user_harvest_cycle (userCycleNumber, user_start_of_cycle, honey_kg, user_end_of_cycle, adminID, status)
                      VALUES ('$nextCycleNumber', '$start_of_cycle', '$honey_kg', '$end_of_cycle', '$adminID', '$status')";
    
        $add_cycle_query = mysqli_query($this->conn, $add_cycle);
    
        // Notify the admin based on the result of the insert operation
        if ($add_cycle_query) {
            $this->notification->insertNotification($adminID, 'active', 'Successfully added new cycle.', 'new_cycle', 'harvestCycle.php', 'unseen');
        } else {
            $this->notification->insertNotification($adminID, 'active', 'Failed to add new cycle.', 'failed_to_add_cycle', 'harvestCycle.php', 'unseen');
        }
    }
    


    public function deleteCycle($cycle_num, $adminID) {
    $delete_cycle = "DELETE FROM user_harvest_cycle WHERE userCycleNumber = ? AND adminID = ?";

    $stmt = $this->conn->prepare($delete_cycle);

    if ($stmt === false) {
        $this->notification->insertNotification($adminID, 'active', 'Failed to prepare delete query.', 'failed_to_prepare_delete_cycle', 'harvestCycle.php', 'unseen');
        return;
    }
    
    $stmt->bind_param('ii', $cycle_num, $adminID);

    if ($stmt->execute()) {
        // Check if a row was deleted
        if ($stmt->affected_rows > 0) {
            $this->notification->insertNotification($adminID, 'active', 'Cycle was deleted successfully.', 'delete_cycle', 'harvestCycle.php', 'unseen');
        } else {
            $this->notification->insertNotification($adminID, 'active', 'No cycle was deleted.', 'failed_to_delete_cycle', 'harvestCycle.php', 'unseen');
        }
    } else {
        $this->notification->insertNotification($adminID, 'active', 'Failed to delete cycle.', 'failed_to_delete_cycle', 'harvestCycle.php', 'unseen');
    }

    $stmt->close();
}


public function userEditCycle($current_cycle_num, $new_cycle_num, $edit_start, $edit_end, $adminID) {
    // Begin the transaction
    mysqli_begin_transaction($this->conn);

    // Check if the current cycle number exists
    $cycle_list = "SELECT userCycleNumber FROM user_harvest_cycle WHERE userCycleNumber = '$current_cycle_num' AND adminID = '$adminID'";
    $result = mysqli_query($this->conn, $cycle_list);

    if (mysqli_num_rows($result) === 0) {
        // If the cycle is not found, rollback and notify
        mysqli_rollback($this->conn);
        $this->notification->insertNotification($adminID, 'active', 'Cycle not found.', 'edit_cycle_failed', 'harvestCycle.php', 'unseen');
        return;
    }

    // Update the cycle information
    $update_query = "UPDATE user_harvest_cycle
                     SET userCycleNumber = '$new_cycle_num', user_start_of_cycle = '$edit_start', user_end_of_cycle = '$edit_end'
                     WHERE userCycleNumber = '$current_cycle_num' AND adminID = '$adminID'";

    // Execute the update query
    if (mysqli_query($this->conn, $update_query)) {
        // Commit the transaction if successful
        mysqli_commit($this->conn);
        $this->notification->insertNotification($adminID, 'active', 'Cycle was edited successfully.', 'edit_cycle_success', 'harvestCycle.php', 'unseen');
    } else {
        // Rollback the transaction if failed
        mysqli_rollback($this->conn);
        $this->notification->insertNotification($adminID, 'active', 'Failed to edit cycle info.', 'edit_cycle_failed', 'harvestCycle.php', 'unseen');
    }
}
}
?>
