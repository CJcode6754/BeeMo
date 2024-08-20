<?php
require_once './src/db.php';
require_once './src/notification_handler.php';

class HarvestCycle {
    private $conn;
    private $notification;

    public function __construct($conn) {
        $this->conn = $conn->getConnection();
        $this->notification = new NotificationHandler($this->conn);
    }

    public function insertCycle($cycle_num, $start_date, $end_date, $adminID) {
        $honey_kg = 0;
        $status = 0;

        $start_of_cycle = DateTime::createFromFormat('Y-m-d', $start_date)->format('Y-m-d');
        $end_of_cycle = DateTime::createFromFormat('Y-m-d', $end_date)->format('Y-m-d');

        $add_cycle = "INSERT INTO harvest_cycle (cycle_number, start_of_cycle, honey_kg, end_of_cycle, adminID, status)
                      VALUES ('$cycle_num', '$start_of_cycle', '$honey_kg', '$end_of_cycle', '$adminID', '$status')";

        $add_cycle_query = mysqli_query($this->conn, $add_cycle);

        if ($add_cycle_query) {
            $this->notification->insertNotification($adminID, 'active', 'Successfully added new cycle.', 'new_cycle', 'harvestCycle.php', 'unseen');
        } else {
            $this->notification->insertNotification($adminID, 'active', 'Failed to add new cycle.', 'failed_to_add_cycle', 'harvestCycle.php', 'unseen');
        }
    }

    public function deleteCycle($cycle_num, $adminID) {
        $delete_cycle = "DELETE FROM harvest_cycle WHERE cycle_number = '$cycle_num' AND adminID = '$adminID'";
        $delete_query = mysqli_query($this->conn, $delete_cycle);

        if ($delete_query) {
            if (mysqli_affected_rows($this->conn) > 0) {
                $this->notification->insertNotification($adminID, 'active', 'Cycle was deleted successfully.', 'delete_cycle', 'harvestCycle.php', 'unseen');
            } else {
                $this->notification->insertNotification($adminID, 'active', 'No cycle was deleted.', 'failed_to_delete_cycle', 'harvestCycle.php', 'unseen');
            }
        } else {
            $this->notification->insertNotification($adminID, 'active', 'Failed to delete cycle.', 'failed_to_delete_cycle', 'harvestCycle.php', 'unseen');
        }
    }

    public function editCycle($current_cycle_num, $new_cycle_num, $edit_start, $edit_end, $adminID) {
        // Begin the transaction
        mysqli_begin_transaction($this->conn);

        // Check if the current cycle number exists
        $cycle_list = "SELECT cycle_number FROM harvest_cycle WHERE cycle_number = '$current_cycle_num' AND adminID = '$adminID'";
        $result = mysqli_query($this->conn, $cycle_list);

        if (mysqli_num_rows($result) === 0) {
            // If the cycle is not found, rollback and notify
            mysqli_rollback($this->conn);
            $this->notification->insertNotification($adminID, 'active', 'Cycle not found.', 'edit_cycle_failed', 'harvestCycle.php', 'unseen');
            return;
        }

        // Update the cycle information
        $update_query = "UPDATE harvest_cycle
                         SET cycle_number = '$new_cycle_num', start_of_cycle = '$edit_start', end_of_cycle = '$edit_end'
                         WHERE cycle_number = '$current_cycle_num' AND adminID = '$adminID'";

        // Execute the update query
        if (mysqli_query($this->conn, $update_query)) {
            // Commit the transaction if successful
            mysqli_commit($this->conn);
            $this->notification->insertNotification($adminID, 'active', 'Cycle info was edited successfully.', 'edit_cycle_success', 'harvestCycle.php', 'unseen');
        } else {
            // Rollback the transaction if failed
            mysqli_rollback($this->conn);
            $this->notification->insertNotification($adminID, 'active', 'Failed to edit cycle info.', 'edit_cycle_failed', 'harvestCycle.php', 'unseen');
        }
    }
}
?>
