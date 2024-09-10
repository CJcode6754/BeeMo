<?php
require_once './src/db.php';
require_once './src/notification_handler.php';

class Profile {
    private $conn;
    private $adminID;
    private $notification;

    public function __construct($conn, $adminID) {
        $this->conn = $conn;
        $this->adminID = $adminID;
        $this->notification = new NotificationHandler($this->conn);
    }

    // Fetch Admin Info
    public function getAdminInfo() {
        $stmt = $this->conn->prepare("SELECT admin_name, email, phone_number FROM admin_table WHERE adminID = ?");
        $stmt->bind_param('i', $this->adminID);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Update Profile
    public function updateProfile($name, $email, $phoneNumber) {
        $stmt = $this->conn->prepare("UPDATE admin_table SET admin_name = ?, email = ?, phone_number = ? WHERE adminID = ?");
        $stmt->bind_param('sssi', $name, $email, $phoneNumber, $this->adminID);

        if ($stmt->execute()) {
            $this->notification->insertNotification($this->adminID, 'active', 'Profile updated successfully!', 'profileUpdate', '/dashboard', 'unseen');
            return true;
        } else {
            echo "Update Profile Error: (" . $stmt->errno . ") " . $stmt->error;
            $this->notification->insertNotification($this->adminID, 'active', 'Error updating profile!', 'errorProfile', '/dashboard', 'unseen');
            return false;
        }
    }

    // Change Password
    public function changePassword($oldPass, $newPass, $conNewPass) {
        // Check if the new password and confirmation password match
        if ($newPass !== $conNewPass) {
            $this->notification->insertNotification($this->adminID, 'active', 'Passwords do not match.', 'notMatchPass', '/dashboard', 'unseen');
            return;
        }

        // Fetch the current hashed password from the database
        $stmt = $this->conn->prepare("SELECT password FROM admin_table WHERE adminID = ?");
        $stmt->bind_param('i', $this->adminID);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
            $hashPass = $row['password'];
            // Verify the provided old password against the hashed password
            if (!password_verify($oldPass, $hashPass)) {
                $this->notification->insertNotification($this->adminID, 'active', 'Old password is incorrect.', 'notMatchPass', '/dashboard', 'unseen');
                return;
            }

            $hashNewPass = password_hash($newPass, PASSWORD_BCRYPT);

            $updateStmt = $this->conn->prepare("UPDATE admin_table SET password = ? WHERE adminID = ?");
            $updateStmt->bind_param('si', $hashNewPass, $this->adminID);

            if ($updateStmt->execute()) {
                $this->notification->insertNotification($this->adminID, 'active', 'Password changed successfully!', 'newPass', '/dashboard', 'unseen');
            } else {
                echo "Change Password Error: (" . $updateStmt->errno . ") " . $updateStmt->error;
                $this->notification->insertNotification($this->adminID, 'active', 'Error updating password.', 'errorPass', '/dashboard', 'unseen');
            }
        } else {
            $this->notification->insertNotification($this->adminID, 'active', 'Error fetching current password.', 'error', '/dashboard', 'unseen');
        }
    }
}
?>
