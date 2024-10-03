<?php
require_once './src/db.php';
require_once './src/notification_handler.php';
require_once './src/mailer.php';
require_once './src/otp.php';
class Profile {
    private $conn;
    private $adminID;
    private $notification;
    private $otp;
    private $mailer;

    public function __construct($conn, $adminID) {
        $this->conn = $conn;
        $this->adminID = $adminID;
        $this->notification = new NotificationHandler($this->conn);
        $this->otp = new OTP($this->conn);
        $this->mailer = new Mailer();
    }

    // Update Profile
    public function updateProfile($newName, $newEmail, $newPhoneNumber) {
        // Fetch current values from the database
        $stmt = $this->conn->prepare("SELECT admin_name, email, number FROM admin_table WHERE adminID = ?");
        $stmt->bind_param('i', $this->adminID);
        $stmt->execute();
        $result = $stmt->get_result();
        $currentData = $result->fetch_assoc();

        $currentName = $currentData['admin_name'];
        $currentEmail = $currentData['email'];
        $currentPhoneNumber = $currentData['number'];

        // Initialize update query parts
        $updateFields = [];
        $updateValues = [];

        // Compare new values with current ones and only update changed fields
        if ($newName !== $currentName) {
            $updateFields[] = 'admin_name = ?';
            $updateValues[] = $newName;
        }

        if ($newPhoneNumber !== $currentPhoneNumber) {
            $updateFields[] = 'number = ?';
            $updateValues[] = $newPhoneNumber;
        }

        // Handle email change and OTP generation
        if ($newEmail !== $currentEmail) {
            // Generate OTP
            $otpData = $this->otp->generateOTPUser($newEmail);
            $otp = $otpData['otp'];
            $otpExpiry = $otpData['otp_expiry'];

            // Store OTP in the database and mark the email as unverified
            $stmt = $this->conn->prepare("UPDATE admin_table SET email = ?, otp = ?, is_verified = 0, otp_expiry = ? WHERE adminID = ?");
            $stmt->bind_param('sssi', $newEmail, $otp, $otpExpiry, $this->adminID);
            $stmt->execute();

            // Send OTP to the new email
            $this->mailer->sendOTP($newEmail, $otp, $newName);

            // Redirect user to verify page
            $_SESSION['new_email'] = $newEmail;
            $_SESSION['otp_expiry'] = $otpExpiry;
            header("Location: /verifyProfile");
            exit;
        }

        // Proceed with updating other fields if there are any changes
        if (!empty($updateFields)) {
            $query = "UPDATE admin_table SET " . implode(', ', $updateFields) . " WHERE adminID = ?";
            $updateValues[] = $this->adminID;

            $stmt = $this->conn->prepare($query);
            $types = str_repeat('s', count($updateFields)) . 'i';
            $stmt->bind_param($types, ...$updateValues);

            // Execute the update query
            if ($stmt->execute()) {
                $this->notification->insertNotification($this->adminID, 'active', 'Profile updated successfully!', 'profileUpdate', '/dashboard', 'unseen');
                return true;
            } else {
                echo "Update Profile Error: (" . $stmt->errno . ") " . $stmt->error;
                $this->notification->insertNotification($this->adminID, 'active', 'Error updating profile!', 'errorProfile', '/dashboard', 'unseen');
                return false;
            }
        }

        return false;
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
