<?php
require_once './src/db.php';
require_once './src/mailer.php';
require_once './src/otp.php';
require_once './src/notification_handler.php';

class Worker {
    private $conn;
    private $mailer;
    private $otp;
    private $adminID;
    private $notification;

    public function __construct($conn, $adminID) {
        $this->conn = $conn;
        $this->adminID = $adminID;
        $this->mailer = new Mailer();
        $this->otp = new OTP($this->conn);
        $this->notification = new NotificationHandler($this->conn);
    }

    public function newUser($userName, $email, $number, $password) {
        // Check if the email already exists
        $stmt = $this->conn->prepare("SELECT email FROM user_table WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $this->notification->insertNotification($this->adminID, 'active', 'Email Already Exist!', 'emailExist', '/Worker', 'unseen');
            return false;
        }

        // Generate OTP and set OTP expiry
        $otpObj = new OTP($this->conn);
        $otpData = $otpObj->generateOTPUser($email);
        $otp = $otpData['otp'];
        $otp_expiry = $otpData['otp_expiry'];

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert new user
        $stmt = $this->conn->prepare("INSERT INTO user_table (user_name, email, number, password, adminID, otp, is_verified, otp_expiry)
                    VALUES (?, ?, ?, ?, ?, ?, 0, ?)");
        $stmt->bind_param('ssssiss', $userName, $email, $number, $hashedPassword, $this->adminID, $otp, $otp_expiry);
        $add = $stmt->execute();

        if ($add) {
            // Retrieve the userID of the newly added user
            $userID = $this->conn->insert_id;
            $_SESSION['userID'] = $userID;  // Store userID in session

            if ($this->mailer->sendOTPWorker($userName, $email, $otp)) {
                $_SESSION['status'] = 'Verify your email with the OTP sent.';
                $_SESSION['email'] = $email;
                $_SESSION['user_name'] = $userName;
                $_SESSION['adminID'] = $this->adminID;

                $this->notification->insertNotification($this->adminID, 'active', 'New User Added!', 'addUser', '/Worker', 'unseen');
                header('Location: /Verify');
                exit();
            } else {
                $this->notification->insertNotification($this->adminID, 'active', 'Failed to send OTP!', 'Failed_to_add_user', '/Worker', 'unseen');
            }
        }
    }
}
?>
